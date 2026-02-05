<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'po_number',
        'po_date',
        'expected_delivery_date',
        'supplier_name',
        'supplier_email',
        'supplier_phone',
        'supplier_address',
        'supplier_contact_person',
        'items',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'discount',
        'shipping_cost',
        'total_amount',
        'status',
        'payment_terms',
        'delivery_method',
        'shipping_address',
        'shipping_instructions',
        'approved_by',
        'approved_at',
        'notes',
        'terms_conditions'
    ];

    protected $casts = [
        'po_date' => 'date',
        'expected_delivery_date' => 'date',
        'approved_at' => 'datetime',
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'tax_rate' => 'decimal:2'
    ];

    /**
     * Generate unique PO number
     */

    public static function generatePONumber($companyId)
    {
        $companyCode = strtoupper(substr(Auth::user()->name, 0, 3));
        $year = date('Y');
        $month = date('m');
        
        $count = static::where(function ($query) use ($companyId) {
                    $query->where('company_id', $companyId)
                        ->orWhereNull('company_id');
                })
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count() + 1;
        

        return sprintf('PO-%s-%s%s-%04d', $companyCode, $year, $month, $count);
    }

    /**
     * Relationship with Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relationship with Approver
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get formatted items
     */
    public function getFormattedItemsAttribute()
    {
        if (is_string($this->items)) {
            $decoded = json_decode($this->items, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        return is_array($this->items) ? $this->items : [];
    }

    /**
     * Calculate totals from items
     */
    public static function calculateTotals($items, $taxRate = 0, $discount = 0, $shippingCost = 0)
    {
        $subtotal = 0;
        
        if (is_array($items)) {
            foreach ($items as $item) {
                $quantity = floatval($item['quantity'] ?? 0);
                $price = floatval($item['price'] ?? 0);
                $subtotal += ($quantity * $price);
            }
        }
        
        $taxAmount = ($subtotal * $taxRate) / 100;
        $total = $subtotal + $taxAmount - $discount + $shippingCost;
        
        return [
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total' => $total
        ];
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'draft' => 'secondary',
            'pending_approval' => 'warning',
            'approved' => 'info',
            'ordered' => 'primary',
            'partial_received' => 'purple',
            'completed' => 'success',
            'cancelled' => 'danger'
        ];
        
        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Get status badge icon
     */
    public function getStatusIconAttribute()
    {
        $icons = [
            'draft' => 'draft',
            'pending_approval' => 'pending_actions',
            'approved' => 'check_circle',
            'ordered' => 'inventory',
            'partial_received' => 'inventory_2',
            'completed' => 'done_all',
            'cancelled' => 'cancel'
        ];
        
        return $icons[$this->status] ?? 'help';
    }

    /**
     * Get payment terms display text
     */
    public function getPaymentTermsTextAttribute()
    {
        $terms = [
            'net_15' => 'Net 15 Days',
            'net_30' => 'Net 30 Days',
            'net_45' => 'Net 45 Days',
            'net_60' => 'Net 60 Days',
            'upon_delivery' => 'Upon Delivery',
            'advance_payment' => 'Advance Payment'
        ];
        
        return $terms[$this->payment_terms] ?? 'Net 30 Days';
    }

    /**
     * Check if PO can be edited
     */
    public function getCanEditAttribute()
    {
        return in_array($this->status, ['draft', 'pending_approval']);
    }

    /**
     * Check if PO can be approved
     */
    public function getCanApproveAttribute()
    {
        return $this->status === 'pending_approval';
    }

    /**
     * Check if PO can be marked as ordered
     */
    public function getCanOrderAttribute()
    {
        return in_array($this->status, ['approved', 'draft']);
    }

    /**
     * Get days until expected delivery
     */
    public function getDaysUntilDeliveryAttribute()
    {
        if (!$this->expected_delivery_date) {
            return null;
        }
        
        $now = now();
        $deliveryDate = \Carbon\Carbon::parse($this->expected_delivery_date);
        
        return $now->diffInDays($deliveryDate, false);
    }

    // Add to PurchaseOrder.php model:
public function receipts()
{
    return $this->hasMany(Receipt::class);
}

public function getReceiptsCountAttribute()
{
    return $this->receipts()->count();
}

public function getLatestReceiptAttribute()
{
    return $this->receipts()->latest()->first();
}

public function getIsFullyReceivedAttribute()
{
    return $this->receipt_status === 'completed';
}

public function getIsPartiallyReceivedAttribute()
{
    return $this->receipt_status === 'partial';
}

public function getIsNotReceivedAttribute()
{
    return $this->receipt_status === 'not_received';
}

public function getRemainingQuantityAttribute()
{
    return $this->total_quantity_ordered - $this->total_quantity_received;
}

public function getReceiptPercentageAttribute()
{
    if ($this->total_quantity_ordered == 0) {
        return 0;
    }
    
    return round(($this->total_quantity_received / $this->total_quantity_ordered) * 100, 2);
}

/**
 * Calculate and set order quantities
 */
public static function calculateOrderQuantities($items)
{
    $totalItems = 0;
    $totalQuantity = 0;
    
    if (is_array($items)) {
        $totalItems = count($items);
        
        foreach ($items as $item) {
            $quantity = intval($item['quantity'] ?? 0);
            $totalQuantity += $quantity;
        }
    }
    
    return [
        'total_items' => $totalItems,
        'total_quantity' => $totalQuantity
    ];
}
}
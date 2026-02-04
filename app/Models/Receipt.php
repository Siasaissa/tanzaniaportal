<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'receipt_number',
        'purchase_order_id',
        'receipt_date',
        'received_by',
        'received_by_name',
        'supplier_name',
        'supplier_contact_person',
        'items',
        'total_items_received',
        'total_quantity_received',
        'total_amount',
        'status',
        'receipt_type',
        'delivery_note_number',
        'vehicle_number',
        'driver_name',
        'driver_contact',
        'quality_notes',
        'condition',
        'storage_location',
        'bin_location',
        'verified_by',
        'verified_at',
        'verification_notes',
        'notes',
        'return_reason'
    ];

    protected $casts = [
        'receipt_date' => 'date',
        'verified_at' => 'datetime',
        'items' => 'array',
        'total_amount' => 'decimal:2',
        'total_items_received' => 'integer',
        'total_quantity_received' => 'integer'
    ];

    /**
     * Generate unique receipt number
     */
    public static function generateReceiptNumber($companyId)
    {
        $company = Company::find($companyId);
        $companyCode = strtoupper(substr(Auth::user()->name, 0, 3));
        $year = date('Y');
        $month = date('m');
        
        $count = static::where('company_id', Auth::user()->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count() + 1;

        return sprintf('RC-%s-%s%s-%04d', 
            $companyCode, 
            $year, 
            $month, 
            $count
        );
    }

    /**
     * Relationship with Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    /**
     * Relationship with Purchase Order
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    /**
     * Relationship with Receiver
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Relationship with Verifier
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
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
    public static function calculateTotals(array $items): array
{
    $totalItems = 0;
    $totalQuantity = 0;
    $totalAmount = 0;

    foreach ($items as $item) {
        $quantity = (float) ($item['quantity_received'] ?? 0);
        $price = (float) ($item['price'] ?? 0);

        if ($quantity > 0) {
            $totalItems++;
        }

        $totalQuantity += $quantity;
        $totalAmount += $quantity * $price;
    }

    return [
        'total_items' => $totalItems,
        'total_quantity' => $totalQuantity,
        'total_amount' => round($totalAmount, 2),
    ];
}

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'draft' => 'secondary',
            'partial' => 'warning',
            'completed' => 'info',
            'verified' => 'success',
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
            'partial' => 'inventory_2',
            'completed' => 'inventory',
            'verified' => 'verified',
            'cancelled' => 'cancel'
        ];
        
        return $icons[$this->status] ?? 'help';
    }

    /**
     * Get receipt type text
     */
    public function getReceiptTypeTextAttribute()
    {
        $types = [
            'full_delivery' => 'Full Delivery',
            'partial_delivery' => 'Partial Delivery',
            'return' => 'Return',
            'damaged_goods' => 'Damaged Goods'
        ];
        
        return $types[$this->receipt_type] ?? 'Full Delivery';
    }

    /**
     * Get condition text
     */
    public function getConditionTextAttribute()
    {
        $conditions = [
            'excellent' => 'Excellent',
            'good' => 'Good',
            'fair' => 'Fair',
            'poor' => 'Poor',
            'damaged' => 'Damaged'
        ];
        
        return $conditions[$this->condition] ?? 'Good';
    }

    /**
     * Check if receipt can be edited
     */
    public function getCanEditAttribute()
    {
        return in_array($this->status, ['draft', 'partial']);
    }

    /**
     * Check if receipt can be verified
     */
    public function getCanVerifyAttribute()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if receipt can be deleted
     */
    public function getCanDeleteAttribute()
    {
        return in_array($this->status, ['draft', 'partial']);
    }

    /**
     * Update PO receipt status
     */
    public function updatePurchaseOrderStatus()
    {
        $po = $this->purchaseOrder;
        if (!$po) return;
        
        // Get all receipts for this PO
        $receipts = Receipt::where('purchase_order_id', $po->id)
            ->where('status', '!=', 'cancelled')
            ->get();
        
        $totalItemsReceived = 0;
        $totalQuantityReceived = 0;
        $totalAmountReceived = 0;
        
        foreach ($receipts as $receipt) {
            $totals = $this->calculateTotals($receipt->items);
            $totalItemsReceived += $totals['total_items'];
            $totalQuantityReceived += $totals['total_quantity'];
            $totalAmountReceived += $totals['total_amount'];
        }
        
        // Update PO
        $po->total_items_received = $totalItemsReceived;
        $po->total_quantity_received = $totalQuantityReceived;
        $po->amount_received = $totalAmountReceived;
        
        // Determine receipt status
        if ($totalQuantityReceived == 0) {
            $po->receipt_status = 'not_received';
        } elseif ($totalQuantityReceived < $po->total_quantity_ordered) {
            $po->receipt_status = 'partial';
        } elseif ($totalQuantityReceived == $po->total_quantity_ordered) {
            $po->receipt_status = 'completed';
        } else {
            $po->receipt_status = 'over_received';
        }
        
        // Update last receipt date
        $lastReceipt = Receipt::where('purchase_order_id', $po->id)
            ->where('status', '!=', 'cancelled')
            ->latest('receipt_date')
            ->first();
        
        if ($lastReceipt) {
            $po->last_receipt_date = $lastReceipt->receipt_date;
        }
        
        $po->save();
    }
}
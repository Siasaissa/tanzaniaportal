<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receipt extends Model
{
    use SoftDeletes;

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
        'items' => 'array',
        'total_amount' => 'decimal:2',
        'verified_at' => 'datetime'
    ];

    // Mutator to ensure proper data types
    public function setItemsAttribute($value)
    {
        if (is_array($value)) {
            foreach ($value as &$item) {
                // Ensure numeric values
                if (isset($item['price'])) {
                    $item['price'] = (float) $item['price'];
                }
                if (isset($item['quantity_received'])) {
                    $item['quantity_received'] = (float) $item['quantity_received'];
                }
                if (isset($item['quantity_ordered'])) {
                    $item['quantity_ordered'] = (float) $item['quantity_ordered'];
                }
            }
        }
        
        $this->attributes['items'] = json_encode($value);
    }

    // Generate receipt number
    public static function generateReceiptNumber($companyId)
    {
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        
        $count = self::where('company_id', $companyId)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count() + 1;
        
        return 'RC-' . $year . $month . $day . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    // Calculate totals
    public static function calculateTotals($items)
    {
        $totalItems = count($items);
        $totalQuantity = 0;
        $totalAmount = 0;
        
        foreach ($items as $item) {
            $quantity = (float) ($item['quantity_received'] ?? 0);
            $price = (float) ($item['price'] ?? 0);
            
            $totalQuantity += $quantity;
            $totalAmount += ($quantity * $price);
        }
        
        return [
            'total_items' => $totalItems,
            'total_quantity' => $totalQuantity,
            'total_amount' => $totalAmount
        ];
    }

    // Relationships
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
    
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryNote extends Model
{

    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'company_id',
        'delivery_note_number',
        'delivery_date',
        'dispatch_date',
        'delivery_address',
        'delivery_contact_person',
        'delivery_contact_phone',
        'vehicle_number',
        'driver_name',
        'driver_contact',
        'items',
        'status',
        'delivery_notes'
        
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'dispatch_date' => 'date',
    ];

    /**
     * Relationship with Quotation
     */
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    /**
     * Relationship with Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }



    /**
     * Generate unique delivery note number
     */
    public static function generateDeliveryNoteNumber($companyId)
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
        

        return sprintf('DN-%s-%s%s-%04d', $companyCode, $year, $month, $count);
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
     * Calculate total from items
     */
    public function getTotalAttribute()
    {
        $total = 0;
        $items = $this->formatted_items;
        
        foreach ($items as $item) {
            $quantity = floatval($item['quantity'] ?? 0);
            $price = floatval($item['price'] ?? 0);
            $total += ($quantity * $price);
        }
        
        return $total;
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'dispatched' => 'info',
            'delivered' => 'success',
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
            'pending' => 'pending_actions',
            'dispatched' => 'local_shipping',
            'delivered' => 'check_circle',
            'cancelled' => 'cancel'
        ];
        
        return $icons[$this->status] ?? 'help';
    }

        public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    

}
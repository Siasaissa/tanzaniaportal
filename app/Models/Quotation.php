<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'quotation_number',
        'date',
        'client_name',
        'client_email',
        'client_phone',
        'items',
        'total',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'items' => 'array',
        'total' => 'decimal:2'
    ];

    /**
     * Generate unique quotation number
     */
    public static function generateQuotationNumber($companyId)
    {
        $company = Company::find($companyId);
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
        
        return sprintf('QUO-%s-%s%s-%04d', 
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
     * Format items for display
     */
public function getFormattedItemsAttribute()
{
    return json_decode($this->items, true) ?? [];
}

// In Quotation.php model, add:
// In Quotation.php model, add:
public function deliveryNote()
{
    return $this->hasOne(DeliveryNote::class);
}


    /**
     * Calculate total from items
     */
    public static function calculateTotal($items)
    {
        $total = 0;
        if (is_array($items)) {
            foreach ($items as $item) {
                if (isset($item['quantity']) && isset($item['price'])) {
                    $total += ($item['quantity'] * $item['price']);
                }
            }
        }
        return $total;
    }

    public function getInvoiceAttribute()
{
    return optional($this->deliveryNote)->invoice;
}

public function getHasInvoiceAttribute()
{
    return !is_null($this->invoice);
}
}
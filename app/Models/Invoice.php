<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_note_id',
        'company_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'client_name',
        'client_email',
        'client_phone',
        'client_address',
        'items',
        'subtotal',
        'tax',
        'tax_rate',
        'discount',
        'total',
        'amount_paid',
        'balance',
        'payment_status',
        'payment_method',
        'transaction_reference',
        'payment_date',
        'status',
        'notes',
        'terms'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'payment_date' => 'date',
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance' => 'decimal:2'
    ];

    /**
     * Relationship with DeliveryNote
     */
    public function deliveryNote()
    {
        return $this->belongsTo(DeliveryNote::class);
    }

    /**
     * Relationship with Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    /**
     * Relationship with Payments
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Generate unique invoice number for admin
     */
    public static function generateInvoiceNumber($companyId)
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
        

            
        return sprintf('INV-%s-%s%s-%04d', $companyCode, $year, $month, $count);
    }

    /**
     * Generate unique invoice number for company
     */

    public static function generateInvoiceNumberC()
{
    // Get the authenticated company user
    $company = Auth::guard('company')->user();
    
    if (!$company) {
        throw new \Exception('Company user must be logged in');
    }
    
    $companyCode = strtoupper(substr($company->name, 0, 3));
    $year = date('Y');
    $month = date('m');
    
    // Count only for this company (remove orWhereNull)
    $count = static::where('company_id', $company->id)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count() + 1;
    
    return sprintf('INV-%s-%s%s-%04d', $companyCode, $year, $month, $count);
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
     * Calculate days overdue
     */
    public function getDaysOverdueAttribute()
    {
        if ($this->payment_status === 'overdue' || ($this->due_date < now() && $this->balance > 0)) {
            return now()->diffInDays($this->due_date);
        }
        return 0;
    }

    /**
     * Get payment status badge color
     */
    public function getPaymentStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'partial' => 'info',
            'paid' => 'success',
            'overdue' => 'danger',
            'cancelled' => 'secondary'
        ];
        
        return $colors[$this->payment_status] ?? 'secondary';
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'draft' => 'secondary',
            'sent' => 'info',
            'viewed' => 'primary',
            'paid' => 'success',
            'overdue' => 'danger',
            'cancelled' => 'dark'
        ];
        
        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Get payment status icon
     */
    public function getPaymentStatusIconAttribute()
    {
        $icons = [
            'pending' => 'pending',
            'partial' => 'partially_paid',
            'paid' => 'check_circle',
            'overdue' => 'warning',
            'cancelled' => 'cancel'
        ];
        
        return $icons[$this->payment_status] ?? 'help';
    }

    /**
     * Get payment method icon
     */
    public function getPaymentMethodIconAttribute()
    {
        $icons = [
            'cash' => 'payments',
            'bank_transfer' => 'account_balance',
            'cheque' => 'description',
            'mobile_money' => 'smartphone',
            'credit_card' => 'credit_card',
            'other' => 'payment'
        ];
        
        return $icons[$this->payment_method] ?? 'payment';
    }

    /**
     * Check if invoice is paid
     */
    public function getIsPaidAttribute()
    {
        return $this->payment_status === 'paid' || $this->balance <= 0;
    }

    /**
     * Check if invoice is overdue
     */
    public function getIsOverdueAttribute()
    {
        return $this->due_date < now() && $this->balance > 0;
    }

    /**
     * Update payment
     */
    public function recordPayment($amount, $method = null, $reference = null)
    {
        $this->amount_paid += $amount;
        $this->balance = $this->total - $this->amount_paid;
        
        if ($this->balance <= 0) {
            $this->payment_status = 'paid';
            $this->status = 'paid';
        } elseif ($this->amount_paid > 0) {
            $this->payment_status = 'partial';
        }
        
        if ($method) {
            $this->payment_method = $method;
        }
        
        if ($reference) {
            $this->transaction_reference = $reference;
        }
        
        $this->payment_date = now();
        $this->save();
    }
}
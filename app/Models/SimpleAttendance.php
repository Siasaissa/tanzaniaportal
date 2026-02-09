<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimpleAttendance extends Model
{
    use HasFactory;

    protected $table = 'simple_attendance';
    
    protected $fillable = [
        'name',
        'date',
        'work',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    /**
     * Scope for today's attendance
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    /**
     * Scope for specific date
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    /**
     * Scope for specific name
     */
    public function scopeForName($query, $name)
    {
        return $query->where('name', 'like', "%{$name}%");
    }

    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute()
    {
        return $this->date->format('F d, Y');
    }

    /**
     * Get short work summary (first 100 chars)
     */
    public function getWorkSummaryAttribute()
    {
        return strlen($this->work) > 100 
            ? substr($this->work, 0, 100) . '...' 
            : $this->work;
    }
}
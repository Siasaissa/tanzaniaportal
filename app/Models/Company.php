<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Company extends Authenticatable
{
    use Notifiable;

    protected $table = 'companies';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'logo',
        'company_desc',
        'password',
        'email_verified_at',
        'last_login_at',
        'last_login_ip',
        'status',
        'provider'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];


        public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
}

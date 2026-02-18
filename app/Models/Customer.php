<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'run',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'status',
        'total_orders',
        'total_spent',
        'last_order_date'
    ];

    protected $casts = [
        'last_order_date' => 'datetime',
        'total_spent' => 'decimal:2'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
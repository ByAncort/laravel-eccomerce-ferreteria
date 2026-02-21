<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stock';

    protected $fillable = [
        'item_id',
        'quantity',
        'min_stock',
        'location',
        'notes',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function isBelowMinStock(): bool
    {
        return $this->quantity <= $this->min_stock;
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'number',
        'customer_id',
        'user_id',
        'status',
        'delivery_date',
        'expiration_date',
        'subtotal',
        'tax',
        'discount',
        'total',
        'notes',
    ];

    protected $casts = [
        'delivery_date'   => 'date',
        'expiration_date' => 'date',
        'subtotal'        => 'decimal:2',
        'tax'             => 'decimal:2',
        'discount'        => 'decimal:2',
        'total'           => 'decimal:2',
    ];

    // ─── Relaciones ───────────────────────────────────────────────────────────

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Recalcula subtotal, tax y total a partir de los items.
     * Llama a este método antes de guardar la cotización.
     */
    public function recalculate(): void
    {
        $subtotal = $this->items->sum('subtotal');

        $this->subtotal = $subtotal - $this->discount;
        $this->tax      = round($this->subtotal * 0.19, 2); // 19% IVA — ajusta según tu país
        $this->total    = $this->subtotal + $this->tax;
    }

    /**
     * Genera el número correlativo: COT-YYYY-XXXX
     */
    public static function generateNumber(): string
    {
        $year = now()->year;
        $last = self::whereYear('created_at', $year)->max('id') ?? 0;

        return sprintf('COT-%d-%04d', $year, $last + 1);
    }

    public function isExpired(): bool
    {
        return $this->expiration_date && $this->expiration_date->isPast();
    }
}
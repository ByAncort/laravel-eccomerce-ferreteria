<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteItem extends Model
{
    protected $fillable = [
        'quote_id',
        'item_id',
        'quantity',
        'unit_price',
        'discount',
        'subtotal',
        'notes',
    ];

    protected $casts = [
        'quantity'   => 'decimal:2',
        'unit_price' => 'decimal:2',
        'discount'   => 'decimal:2',
        'subtotal'   => 'decimal:2',
    ];

    // ─── Relaciones ───────────────────────────────────────────────────────────

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Calcula el subtotal de la línea aplicando el descuento porcentual.
     * Llama esto antes de save() cuando la cantidad o precio cambia.
     */
    public function computeSubtotal(): void
    {
        $gross         = $this->quantity * $this->unit_price;
        $this->subtotal = round($gross - ($gross * $this->discount / 100), 2);
    }

    /**
     * Verifica si hay stock disponible para la cantidad solicitada.
     */
    public function hasStock(): bool
    {
        $stock = Stock::where('item_id', $this->item_id)->first();

        return $stock && $stock->quantity >= $this->quantity;
    }
}
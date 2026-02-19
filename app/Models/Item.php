<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'slug',
        'description',
        'category',
        'subcategory',
        'brand',
        'model',
        'cost_price',
        'selling_price',
        'offer_price',
        'offer_ends_at',
        'unit_measure',
        'weight',
        'dimensions',
        'material',
        'main_image',
        'gallery_images',
        'status',
        'featured',
        'views',
        'vendor_id'
    ];

    protected $casts = [
        'featured' => 'boolean',
        'offer_ends_at' => 'datetime',
        'gallery_images' => 'array',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'offer_price' => 'decimal:2',
        'weight' => 'decimal:2'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function getCategoryLabelAttribute()
    {
        $labels = [
            'herramientas_electricas' => 'Herramientas Eléctricas',
            'herramientas_manuales' => 'Herramientas Manuales',
            'seguridad' => 'Equipos de Seguridad',
            'jardineria' => 'Jardinería',
            'construccion' => 'Construcción',
            'plomeria' => 'Plomería',
            'electricidad' => 'Electricidad',
            'pintura' => 'Pintura',
            'ferreteria_general' => 'Ferretería General'
        ];

        return $labels[$this->category] ?? $this->category;
    }

    public function getHasOfferAttribute()
    {
        return !is_null($this->offer_price) && 
               $this->offer_price > 0 && 
               (is_null($this->offer_ends_at) || $this->offer_ends_at->isFuture());
    }
}
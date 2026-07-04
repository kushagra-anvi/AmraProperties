<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyConfiguration extends Model
{
    protected $fillable = [
        'property_id',
        'name',
        'price',
        'area',
        'area_unit',
        'bedrooms',
        'bathrooms',
        'status',
    ];

    protected $casts = [
        'price' => 'integer',
        'area' => 'integer',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get formatted price in INR.
     */
    public function getFormattedPriceAttribute(): string
    {
        if (!$this->price) {
            return 'Call for Price';
        }

        $price = $this->price;
        if ($price >= 10000000) {
            return '₹' . number_format($price / 10000000, 2) . ' Cr';
        } elseif ($price >= 100000) {
            return '₹' . number_format($price / 100000, 2) . ' Lac';
        }

        return '₹' . number_format($price);
    }
}

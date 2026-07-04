<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'submitted_by_user_id',
        'title',
        'slug',
        'description',
        'price',
        'avg_price_per_sqft',
        'possession_date',
        'possession_status',
        'bedrooms',
        'bathrooms',
        'area',
        'area_unit',
        'address',
        'latitude',
        'longitude',
        'rera_number',
        'featured_image',
        'gallery',
        'video_urls',
        'city',
        'state',
        'country',
        'status',
        'listing_type',
        'is_featured',
        'is_rera_approved',
        'configuration',
        'developer_name',
        'contact_phone',
        'contact_whatsapp',
        'amenities',
    ];

    protected $casts = [
        'gallery' => 'array',
        'video_urls' => 'array',
        'amenities' => 'array',
        'price' => 'integer',
        'avg_price_per_sqft' => 'decimal:2',
        'possession_date' => 'date',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'area' => 'integer',
        'is_featured' => 'boolean',
        'is_rera_approved' => 'boolean',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by_user_id');
    }

    public function enquiries(): HasMany
    {
        return $this->hasMany(PropertyEnquiry::class);
    }

    public function configurations(): HasMany
    {
        return $this->hasMany(PropertyConfiguration::class);
    }

    public function partners(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Partner::class, 'partner_property');
    }

    /**
     * Get formatted price in INR.
     */
    public function getFormattedPriceAttribute(): string
    {
        if ($this->price) {
            return $this->formatSinglePrice($this->price);
        }

        if ($this->configurations->isNotEmpty()) {
            $prices = $this->configurations->pluck('price')->filter()->all();
            if (!empty($prices)) {
                $minPrice = min($prices);
                $maxPrice = max($prices);
                if ($minPrice === $maxPrice) {
                    return $this->formatSinglePrice($minPrice);
                }
                return $this->formatSinglePrice($minPrice) . ' - ' . $this->formatSinglePrice($maxPrice);
            }
        }

        return 'Call for Price';
    }

    protected function formatSinglePrice($price): string
    {
        if ($price >= 10000000) {
            return '₹' . number_format($price / 10000000, 2) . ' Cr';
        } elseif ($price >= 100000) {
            return '₹' . number_format($price / 100000, 2) . ' Lac';
        }

        return '₹' . number_format($price);
    }

    public function getFormattedAvgPriceAttribute(): ?string
    {
        if (!$this->avg_price_per_sqft) {
            return null;
        }

        $price = (float) $this->avg_price_per_sqft;

        if ($price >= 1000) {
            return '₹' . rtrim(rtrim(number_format($price / 1000, 2), '0'), '.') . 'k/sq.ft';
        }

        return '₹' . number_format($price) . '/sq.ft';
    }

    public function getFormattedPossessionAttribute(): ?string
    {
        if ($this->possession_date) {
            return $this->possession_date->format('M, Y');
        }

        return $this->possession_status;
    }

    /**
     * Get the budget category for client-side filter: '25-50', '50-100', '100+', or 'all'.
     */
    public function getBudgetCategoryAttribute(): string
    {
        if (!$this->price) {
            return '';
        }

        $price = $this->price;
        if ($price >= 2500000 && $price <= 5000000) {
            return '25-50';
        } elseif ($price > 5000000 && $price <= 10000000) {
            return '50-100';
        } elseif ($price > 10000000) {
            return '100+';
        }

        return '';
    }

    /**
     * Map city/locations to client-side filters: 'mumbai' or 'lucknow'.
     */
    public function getLocationCategoryAttribute(): string
    {
        $city = strtolower($this->city ?? '');
        $mumbaiCities = ['mumbai', 'thane', 'navi mumbai', 'panvel', 'dombivli', 'chembur', 'prabhadevi', 'versova', 'airoli', 'kharghar', 'kolshet', 'kapurbawdi'];
        
        foreach ($mumbaiCities as $mc) {
            if (str_contains($city, $mc)) {
                return 'mumbai';
            }
        }

        return 'lucknow';
    }

    /**
     * Map property type to client-side filters: 'flat', 'villa', or 'plot'.
     */
    public function getTypeCategoryAttribute(): string
    {
        $type = strtolower($this->configuration ?? '');
        $title = strtolower($this->title ?? '');

        if (str_contains($type, 'commercial') || str_contains($type, 'office') || str_contains($type, 'shop') ||
            str_contains($title, 'commercial') || str_contains($title, 'office') || str_contains($title, 'shop')) {
            return 'commercial';
        }

        if (str_contains($type, 'bhk') || str_contains($type, 'flat') || str_contains($type, 'apartment')) {
            return 'flat';
        }

        if (str_contains($title, 'flat') || str_contains($title, 'apartment') || str_contains($title, 'bhk')) {
            return 'flat';
        }

        if (str_contains($type, 'plot') || str_contains($type, 'land') || str_contains($title, 'plot') || str_contains($title, 'land')) {
            return 'plot';
        }

        if (str_contains($type, 'villa') || str_contains($type, 'house') || str_contains($title, 'villa') || str_contains($title, 'house')) {
            return 'villa';
        }

        return 'flat';
    }
}

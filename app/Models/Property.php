<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
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
        'city',
        'state',
        'country',
        'status',
        'is_featured',
        'is_rera_approved',
        'configuration',
        'developer_name',
        'amenities',
    ];

    protected $casts = [
        'gallery' => 'array',
        'amenities' => 'array',
        'price' => 'integer',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'area' => 'integer',
        'is_featured' => 'boolean',
        'is_rera_approved' => 'boolean',
    ];

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
        $type = strtolower($this->configuration ?? ''); // We can check configuration or mapping
        if (str_contains($type, 'bhk') || str_contains($type, 'flat') || str_contains($type, 'apartment')) {
            return 'flat';
        }

        $title = strtolower($this->title);
        if (str_contains($title, 'flat') || str_contains($title, 'apartment') || str_contains($title, 'bhk')) {
            return 'flat';
        }

        if (str_contains($type, 'plot') || str_contains($type, 'land') || str_contains($title, 'plot') || str_contains($title, 'land')) {
            return 'plot';
        }

        // Default to flat/villa based on keywords
        if (str_contains($type, 'villa') || str_contains($type, 'house') || str_contains($title, 'villa') || str_contains($title, 'house')) {
            return 'villa';
        }

        return 'flat'; // default fallback
    }
}

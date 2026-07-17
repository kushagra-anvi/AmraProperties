<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'featured_image',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Get a clean, plain-text excerpt of the post content.
     */
    public function excerpt(int $limit = 130): string
    {
        // Remove style blocks and their content
        $cleanContent = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $this->content ?: '');
        // Remove script blocks and their content
        $cleanContent = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $cleanContent);
        // Strip other HTML tags
        $cleanContent = strip_tags($cleanContent);
        // Decode HTML entities
        $cleanContent = html_entity_decode($cleanContent);
        // Limit and trim
        return Str::limit(trim($cleanContent), $limit);
    }
}

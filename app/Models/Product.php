<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'sku', 'name', 'description', 'image_path',
        'base_price', 'takeaway_price', 'happy_hour_price', 'tags',
        'prep_time_minutes', 'kitchen_area', 'is_active', 'is_available',
        'auto_disable_on_stock', 'available_dine_in', 'available_takeaway',
        'available_delivery',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_available' => 'boolean',
            'tags' => 'json',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}

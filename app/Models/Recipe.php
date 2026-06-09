<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recipe extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_id', 'ingredient_id', 'quantity', 'auto_deduct',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'auto_deduct' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function getCostAttribute(): float
    {
        return round($this->quantity * $this->ingredient->unit_cost, 4);
    }
}

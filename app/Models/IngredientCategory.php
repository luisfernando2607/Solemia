<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IngredientCategory extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    public function ingredients(): HasMany
    {
        return $this->hasMany(Ingredient::class, 'ingredient_category_id');
    }
}

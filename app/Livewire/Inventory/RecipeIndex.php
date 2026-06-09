<?php

namespace App\Livewire\Inventory;

use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Recipe;
use Livewire\Component;

class RecipeIndex extends Component
{
    public $selectedProductId = null;
    public $search = '';

    // New recipe row
    public $recipeIngredientId = '';
    public $recipeQuantity = 1;
    public $recipeAutoDeduct = true;

    public function getProductsProperty()
    {
        return Product::with('category')
            ->where('is_active', true)
            ->where(function ($q) {
                if ($this->search) {
                    $q->where('name', 'like', "%{$this->search}%");
                }
            })
            ->orderBy('name')->get();
    }

    public function getSelectedProductProperty()
    {
        if (!$this->selectedProductId) return null;
        return Product::with('recipes.ingredient')->find($this->selectedProductId);
    }

    public function getAvailableIngredientsProperty()
    {
        return Ingredient::where('is_active', true)->orderBy('name')->get();
    }

    public function selectProduct($id): void
    {
        $this->selectedProductId = $id;
    }

    public function addIngredient(): void
    {
        $this->validate([
            'recipeIngredientId' => 'required|exists:ingredients,id',
            'recipeQuantity' => 'required|numeric|min:0.001',
        ]);

        if (!$this->selectedProductId) return;

        $exists = Recipe::where('product_id', $this->selectedProductId)
            ->where('ingredient_id', $this->recipeIngredientId)->exists();
        if ($exists) return;

        Recipe::create([
            'product_id' => $this->selectedProductId,
            'ingredient_id' => $this->recipeIngredientId,
            'quantity' => $this->recipeQuantity,
            'auto_deduct' => $this->recipeAutoDeduct,
        ]);

        $this->recipeIngredientId = '';
        $this->recipeQuantity = 1;
    }

    public function removeIngredient($id): void
    {
        Recipe::findOrFail($id)->delete();
    }

    public function toggleAutoDeduct($id): void
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->update(['auto_deduct' => !$recipe->auto_deduct]);
    }

    public function render()
    {
        return view('livewire.inventory.recipe-index')
            ->layout('layouts.app');
    }
}

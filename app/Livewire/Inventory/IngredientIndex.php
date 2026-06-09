<?php

namespace App\Livewire\Inventory;

use App\Models\Ingredient;
use App\Models\IngredientCategory;
use App\Models\InventoryMovement;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class IngredientIndex extends Component
{
    public $activeTab = 'ingredients';

    // Category form
    public $showCategoryForm = false;
    public $editingCategory = false;
    public $categoryId = null;
    public $categoryName = '';
    public $selectedCategoryId = null;

    // Ingredient form
    public $showIngredientForm = false;
    public $editingIngredient = false;
    public $ingredientId = null;
    public $ingredientName = '';
    public $ingredientUnit = '';
    public $ingredientCost = 0;
    public $ingredientStock = 0;
    public $ingredientMinStock = 0;
    public $ingredientActive = true;

    // Adjustment
    public $showAdjustment = false;
    public $adjustIngredientId = null;
    public $adjustType = 'manual_in';
    public $adjustQuantity = 0;
    public $adjustReason = '';

    // Movement history
    public $showMovements = false;
    public $movementIngredientId = null;

    protected $listeners = ['refresh' => '$refresh'];

    public function mount(): void
    {
        $first = IngredientCategory::orderBy('name')->first();
        $this->selectedCategoryId = $first?->id;
    }

    public function getCategoriesProperty()
    {
        return IngredientCategory::withCount('ingredients')->orderBy('name')->get();
    }

    public function getIngredientsProperty()
    {
        $q = Ingredient::with('category')->orderBy('name');
        if ($this->selectedCategoryId) {
            $q->where('ingredient_category_id', $this->selectedCategoryId);
        }
        return $q->get();
    }

    public function getLowStockCountProperty()
    {
        return Ingredient::whereColumn('stock_current', '<=', 'stock_minimum')
            ->where('is_active', true)->count();
    }

    public function selectCategory($id): void
    {
        $this->selectedCategoryId = $id;
        $this->resetIngredientForm();
    }

    // Category CRUD
    public function createCategory(): void
    {
        $this->resetCategoryForm();
        $this->showCategoryForm = true;
        $this->editingCategory = false;
    }

    public function editCategory($id): void
    {
        $cat = IngredientCategory::findOrFail($id);
        $this->categoryId = $cat->id;
        $this->categoryName = $cat->name;
        $this->editingCategory = true;
        $this->showCategoryForm = true;
    }

    public function saveCategory(): void
    {
        $this->validate(['categoryName' => 'required|string|max:80']);
        if ($this->editingCategory) {
            IngredientCategory::findOrFail($this->categoryId)->update(['name' => $this->categoryName]);
        } else {
            IngredientCategory::create(['name' => $this->categoryName]);
        }
        $this->showCategoryForm = false;
        $this->resetCategoryForm();
    }

    public function deleteCategory($id): void
    {
        $cat = IngredientCategory::findOrFail($id);
        if ($cat->ingredients()->count() > 0) return;
        $cat->delete();
        if ($this->selectedCategoryId == $id) {
            $this->selectedCategoryId = null;
        }
    }

    // Ingredient CRUD
    public function createIngredient(): void
    {
        $this->resetIngredientForm();
        $this->showIngredientForm = true;
        $this->editingIngredient = false;
    }

    public function editIngredient($id): void
    {
        $ing = Ingredient::findOrFail($id);
        $this->ingredientId = $ing->id;
        $this->ingredientName = $ing->name;
        $this->ingredientUnit = $ing->unit;
        $this->ingredientCost = $ing->unit_cost;
        $this->ingredientStock = $ing->stock_current;
        $this->ingredientMinStock = $ing->stock_minimum;
        $this->ingredientActive = $ing->is_active;
        $this->editingIngredient = true;
        $this->showIngredientForm = true;
    }

    public function saveIngredient(): void
    {
        $this->validate([
            'ingredientName' => 'required|string|max:150',
            'ingredientUnit' => 'required|string|max:20',
            'ingredientCost' => 'required|numeric|min:0',
            'ingredientStock' => 'required|numeric|min:0',
            'ingredientMinStock' => 'required|numeric|min:0',
        ]);

        $data = [
            'ingredient_category_id' => $this->selectedCategoryId,
            'name' => $this->ingredientName,
            'unit' => $this->ingredientUnit,
            'unit_cost' => $this->ingredientCost,
            'stock_current' => $this->ingredientStock,
            'stock_minimum' => $this->ingredientMinStock,
            'is_active' => $this->ingredientActive,
        ];

        if ($this->editingIngredient) {
            Ingredient::findOrFail($this->ingredientId)->update($data);
        } else {
            Ingredient::create($data);
        }

        $this->showIngredientForm = false;
        $this->resetIngredientForm();
    }

    public function deleteIngredient($id): void
    {
        Ingredient::findOrFail($id)->delete();
    }

    public function toggleActive($id): void
    {
        $ing = Ingredient::findOrFail($id);
        $ing->update(['is_active' => !$ing->is_active]);
    }

    // Stock adjustment
    public function openAdjustment($id): void
    {
        $this->adjustIngredientId = $id;
        $this->adjustType = 'manual_in';
        $this->adjustQuantity = 0;
        $this->adjustReason = '';
        $this->showAdjustment = true;
    }

    public function saveAdjustment(): void
    {
        $this->validate([
            'adjustQuantity' => 'required|numeric|min:0.001',
            'adjustReason' => 'required|string|max:255',
        ]);

        $ingredient = Ingredient::findOrFail($this->adjustIngredientId);
        $before = $ingredient->stock_current;
        $isIn = in_array($this->adjustType, ['purchase', 'manual_in']);
        $quantity = $isIn ? abs($this->adjustQuantity) : -abs($this->adjustQuantity);
        $after = $before + $quantity;

        InventoryMovement::create([
            'ingredient_id' => $ingredient->id,
            'user_id' => Auth::id(),
            'type' => $this->adjustType,
            'quantity' => $quantity,
            'stock_before' => $before,
            'stock_after' => max(0, $after),
            'unit_cost' => $ingredient->unit_cost,
            'reason' => $this->adjustReason,
        ]);

        $ingredient->update(['stock_current' => max(0, $after)]);

        if ($ingredient->isLowStock()) {
            app(NotificationService::class)->stockAlert(
                $ingredient->id, $ingredient->name, $ingredient->stock_current, $ingredient->stock_minimum
            );
        }

        $this->showAdjustment = false;
    }

    // Movements
    public function viewMovements($id): void
    {
        $this->movementIngredientId = $id;
        $this->showMovements = true;
    }

    public function getMovementsProperty()
    {
        if (!$this->movementIngredientId) return collect();
        return InventoryMovement::where('ingredient_id', $this->movementIngredientId)
            ->with('user')->orderByDesc('created_at')->limit(50)->get();
    }

    // Reset
    public function resetCategoryForm(): void
    {
        $this->categoryId = null;
        $this->categoryName = '';
    }

    public function resetIngredientForm(): void
    {
        $this->ingredientId = null;
        $this->ingredientName = '';
        $this->ingredientUnit = '';
        $this->ingredientCost = 0;
        $this->ingredientStock = 0;
        $this->ingredientMinStock = 0;
        $this->ingredientActive = true;
    }

    public function render()
    {
        return view('livewire.inventory.ingredient-index')
            ->layout('layouts.app');
    }
}

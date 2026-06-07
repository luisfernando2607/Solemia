<?php

namespace App\Livewire\Menu;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithFileUploads;

    public $selectedCategoryId = null;
    public $showCategoryForm = false;
    public $editingCategory = false;
    public $categoryName = '';
    public $categorySortOrder = 0;
    public $categoryImage;

    public $showProductForm = false;
    public $editingProduct = false;
    public $productName = '';
    public $productPrice = '';
    public $productDescription = '';
    public $productKitchenArea = 'cocina';
    public $productPrepTime = 5;
    public $productSku = '';
    public $productActive = true;
    public $productAvailable = true;
    public $productDineIn = true;
    public $productTakeaway = true;
    public $productDelivery = true;
    public $productImage;

    public function mount(): void
    {
        $first = Category::where('is_active', true)->orderBy('sort_order')->first();
        $this->selectedCategoryId = $first?->id;
    }

    public function getCategoriesProperty()
    {
        return Category::withCount('products')->orderBy('sort_order')->get();
    }

    public function getProductsProperty()
    {
        if (!$this->selectedCategoryId) {
            return collect();
        }
        return Product::where('category_id', $this->selectedCategoryId)->orderBy('name')->get();
    }

    public function selectCategory($id): void
    {
        if ($this->showCategoryForm || $this->showProductForm) return;
        $this->selectedCategoryId = $id;
    }

    // Category CRUD
    public function showCategoryCreate(): void
    {
        $this->resetCategoryForm();
        $this->showCategoryForm = true;
        $this->editingCategory = false;
    }

    public function showCategoryEdit(Category $category): void
    {
        $this->categoryName = $category->name;
        $this->categorySortOrder = $category->sort_order;
        $this->editingCategory = $category;
        $this->showCategoryForm = true;
    }

    public function resetCategoryForm(): void
    {
        $this->categoryName = '';
        $this->categorySortOrder = Category::max('sort_order') + 1;
        $this->categoryImage = null;
        $this->editingCategory = false;
        $this->showCategoryForm = false;
    }

    public function saveCategory(): void
    {
        $this->validate(['categoryName' => 'required|string|max:100']);

        $data = ['name' => $this->categoryName, 'sort_order' => $this->categorySortOrder ?: 0];

        if ($this->categoryImage) {
            $data['image_path'] = $this->categoryImage->store('menu/categories', 'public');
        }

        if ($this->editingCategory) {
            $this->editingCategory->update($data);
            $this->js("Swal.fire({icon: 'success', title: 'Categoría actualizada', toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true})");
        } else {
            $data['is_active'] = true;
            Category::create($data);
            $this->js("Swal.fire({icon: 'success', title: 'Categoría creada', toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true})");
        }

        $this->resetCategoryForm();
    }

    public function removeCategory($id)
    {
        $category = Category::find($id);
        if (!$category) {
            $this->js("Swal.fire({icon: 'error', title: 'Categoría no encontrada'})");
            return;
        }

        $other = Category::where('id', '!=', $id)->first();
        if ($other) {
            Product::withTrashed()->where('category_id', $id)->update(['category_id' => $other->id]);
        }

        $category->delete();

        if ($this->selectedCategoryId === $id) {
            $this->selectedCategoryId = Category::first()?->id;
        }

        $this->js("Swal.fire({icon: 'success', title: 'Categoría eliminada', toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true})");
    }

    // Product CRUD
    public function showProductCreate(): void
    {
        $this->resetProductForm();
        $this->showProductForm = true;
        $this->editingProduct = false;
    }

    public function showProductEdit(Product $product): void
    {
        $this->productName = $product->name;
        $this->productPrice = (string)$product->base_price;
        $this->productDescription = $product->description ?? '';
        $this->productKitchenArea = $product->kitchen_area ?? 'cocina';
        $this->productPrepTime = $product->prep_time_minutes ?? 5;
        $this->productSku = $product->sku ?? '';
        $this->productActive = $product->is_active;
        $this->productAvailable = $product->is_available;
        $this->productDineIn = $product->available_dine_in ?? true;
        $this->productTakeaway = $product->available_takeaway ?? false;
        $this->productDelivery = $product->available_delivery ?? false;
        $this->editingProduct = $product;
        $this->showProductForm = true;
    }

    public function resetProductForm(): void
    {
        $this->productName = '';
        $this->productPrice = '';
        $this->productDescription = '';
        $this->productKitchenArea = 'cocina';
        $this->productPrepTime = 5;
        $this->productSku = '';
        $this->productImage = null;
        $this->productActive = true;
        $this->productAvailable = true;
        $this->productDineIn = true;
        $this->productTakeaway = true;
        $this->productDelivery = true;
        $this->editingProduct = false;
        $this->showProductForm = false;
    }

    public function saveProduct(): void
    {
        $this->validate([
            'productName' => 'required|string|max:200',
            'productPrice' => 'required|numeric|min:0',
            'productImage' => 'nullable|image|max:2048',
        ]);

        $data = [
            'category_id' => $this->selectedCategoryId,
            'name' => $this->productName,
            'base_price' => $this->productPrice,
            'description' => $this->productDescription ?: null,
            'sku' => $this->productSku ?: null,
            'kitchen_area' => $this->productKitchenArea,
            'prep_time_minutes' => (int)$this->productPrepTime,
            'is_active' => $this->productActive,
            'is_available' => $this->productAvailable,
            'available_dine_in' => $this->productDineIn,
            'available_takeaway' => $this->productTakeaway,
            'available_delivery' => $this->productDelivery,
        ];

        if ($this->productImage) {
            if ($this->editingProduct && $this->editingProduct->image_path) {
                Storage::disk('public')->delete($this->editingProduct->image_path);
            }
            $data['image_path'] = $this->productImage->store('menu/products', 'public');
        }

        if ($this->editingProduct) {
            $this->editingProduct->update($data);
            $this->js("Swal.fire({icon: 'success', title: 'Producto actualizado', toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true})");
        } else {
            Product::create($data);
            $this->js("Swal.fire({icon: 'success', title: 'Producto creado', toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true})");
        }

        $this->resetProductForm();
    }

    public function deleteProduct($id): void
    {
        $product = Product::find($id);
        if (!$product) return;

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();
        $this->js("Swal.fire({icon: 'success', title: 'Producto eliminado', toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true})");
    }

    public function render()
    {
        return view('livewire.menu.index')
            ->layout('layouts.app');
    }
}

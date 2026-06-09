<?php

namespace App\Livewire\Pos;

use App\Models\Category;
use App\Models\Order as OrderModel;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\RestaurantSetting;
use App\Models\TableModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class TableOrder extends Component
{
    public TableModel $table;
    public ?OrderModel $order = null;
    public $selectedCategory = null;
    public $itemNotes = [];
    public $orderNotes = '';

    public function mount(TableModel $table): void
    {
        $this->table = $table->load('zone');
        $this->order = $table->activeOrder;
        $this->selectedCategory = Category::where('is_active', true)->orderBy('sort_order')->value('id');

        if (!$this->order) {
            $this->order = OrderModel::create([
                'table_id' => $table->id,
                'user_id' => Auth::id(),
                'type' => 'dine_in',
                'status' => 'open',
                'opened_at' => now(),
            ]);
        }
    }

    public function selectCategory($id): void
    {
        $this->selectedCategory = $id;
    }

    public function getCategoriesProperty()
    {
        return Category::where('is_active', true)->orderBy('sort_order')->get();
    }

    public function getProductsProperty()
    {
        if (!$this->selectedCategory) {
            return collect();
        }
        return Product::where('category_id', $this->selectedCategory)
            ->where('is_active', true)
            ->where('is_available', true)
            ->orderBy('name')
            ->get();
    }

    public function getOrderItemsProperty()
    {
        return $this->order?->items()->with('product')->get() ?? collect();
    }

    public function addItem($productId, $quantity = 1): void
    {
        $product = Product::findOrFail($productId);

        $existing = $this->order->items()
            ->where('product_id', $productId)
            ->where('kitchen_status', 'pending')
            ->first();

        if ($existing) {
            $existing->increment('quantity', $quantity);
            $existing->update([
                'subtotal' => ($existing->quantity * $existing->unit_price) + $existing->modifiers_total,
            ]);
        } else {
            $this->order->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->base_price,
                'modifiers_total' => 0,
                'subtotal' => $product->base_price * $quantity,
                'kitchen_status' => 'pending',
                'kitchen_area' => $product->kitchen_area,
            ]);
        }

        $this->recalculateOrder();
        $this->dispatch('swal', icon: 'success', title: 'Producto agregado', text: $product->name);
    }

    public function updateQuantity($itemId, $quantity): void
    {
        $item = OrderItem::findOrFail($itemId);
        $quantity = (int)$quantity;

        if ($quantity <= 0) {
            $item->delete();
            $this->recalculateOrder();
            return;
        }

        $item->update([
            'quantity' => $quantity,
            'subtotal' => ($quantity * $item->unit_price) + $item->modifiers_total,
        ]);

        $this->recalculateOrder();
    }

    public function removeItem($itemId): void
    {
        $item = OrderItem::findOrFail($itemId);
        $item->delete();
        $this->recalculateOrder();
        $this->dispatch('swal', icon: 'success', title: 'Producto eliminado');
    }

    public function sendToKitchen(): void
    {
        if ($this->order->items()->where('kitchen_status', 'pending')->count() === 0) {
            $this->js("Swal.fire({icon:'warning',title:'Sin items pendientes',text:'Todos los items ya fueron enviados',timer:2000,showConfirmButton:false})");
            return;
        }

        $newStatus = match ($this->order->status) {
            'open' => 'sent',
            'sent', 'partial' => 'partial',
            default => $this->order->status,
        };

        $this->order->update([
            'status' => $newStatus,
            'notes' => $this->orderNotes,
        ]);

        $this->order->items()->where('kitchen_status', 'pending')->update([
            'kitchen_status' => 'pending',
            'sent_at' => now(),
        ]);

        $this->js("Swal.fire({icon:'success',title:'¡Comanda enviada!',text:'La orden ha sido enviada a cocina',timer:2000,showConfirmButton:false})");
    }

    public function saveDraft(): void
    {
        $this->order->update([
            'notes' => $this->orderNotes,
        ]);
        $this->dispatch('swal', icon: 'success', title: 'Orden guardada', text: 'Puedes continuar después');
    }

    public function backToTables(): void
    {
        $this->redirectRoute('pos.tables', navigate: true);
    }

    protected function recalculateOrder(): void
    {
        $this->order->load('items');
        $subtotal = $this->order->items->sum('subtotal');
        $taxRate = (float)(RestaurantSetting::current()->tax_rate ?? 15) / 100;
        $tax = round($subtotal * $taxRate, 2);
        $total = $subtotal + $tax - $this->order->discount + $this->order->tip;

        $this->order->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => max(0, $total),
        ]);
    }

    #[On('table-status-updated')]
    public function refreshTable(): void
    {
        $this->table->refresh();
    }

    public function render()
    {
        return view('livewire.pos.table-order')
            ->layout('layouts.app');
    }
}

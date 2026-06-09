<?php

namespace App\Livewire\Inventory;

use App\Models\Ingredient;
use App\Models\InventoryMovement;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PurchaseOrderIndex extends Component
{
    public $showForm = false;
    public $editing = false;
    public $orderId = null;
    public $supplierId = '';
    public $notes = '';
    public $orderedAt = '';

    // Line items
    public $lines = [];
    public $newIngredientId = '';
    public $newQuantity = 1;
    public $newUnitCost = 0;

    public $selectedStatus = '';
    public $viewOrderId = null;

    protected $listeners = ['refresh' => '$refresh'];

    public function mount(): void
    {
        $this->orderedAt = now()->format('Y-m-d');
    }

    public function getOrdersProperty()
    {
        $q = PurchaseOrder::with('supplier', 'user', 'items.ingredient');
        if ($this->selectedStatus) {
            $q->where('status', $this->selectedStatus);
        }
        return $q->orderByDesc('created_at')->get();
    }

    public function getSuppliersProperty()
    {
        return Supplier::where('is_active', true)->orderBy('name')->get();
    }

    public function getIngredientsListProperty()
    {
        return Ingredient::where('is_active', true)->orderBy('name')->get();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editing = false;
    }

    public function addLine(): void
    {
        $this->validate([
            'newIngredientId' => 'required|exists:ingredients,id',
            'newQuantity' => 'required|numeric|min:0.001',
            'newUnitCost' => 'required|numeric|min:0',
        ]);

        $this->lines[] = [
            'ingredient_id' => (int)$this->newIngredientId,
            'ingredient_name' => Ingredient::find($this->newIngredientId)?->name ?? '',
            'quantity' => (float)$this->newQuantity,
            'unit_cost' => (float)$this->newUnitCost,
            'subtotal' => round((float)$this->newQuantity * (float)$this->newUnitCost, 2),
        ];

        $this->newIngredientId = '';
        $this->newQuantity = 1;
        $this->newUnitCost = 0;
    }

    public function removeLine($index): void
    {
        unset($this->lines[$index]);
        $this->lines = array_values($this->lines);
    }

    public function getLinesTotalProperty()
    {
        return array_sum(array_column($this->lines, 'subtotal'));
    }

    public function save(): void
    {
        $this->validate([
            'supplierId' => 'required|exists:suppliers,id',
            'lines' => 'required|array|min:1',
        ]);

        $order = PurchaseOrder::create([
            'supplier_id' => $this->supplierId,
            'user_id' => Auth::id(),
            'status' => 'draft',
            'total' => $this->linesTotal,
            'notes' => $this->notes,
            'ordered_at' => $this->orderedAt ?: now(),
        ]);

        foreach ($this->lines as $line) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $order->id,
                'ingredient_id' => $line['ingredient_id'],
                'quantity' => $line['quantity'],
                'unit_cost' => $line['unit_cost'],
                'subtotal' => $line['subtotal'],
            ]);
        }

        $this->showForm = false;
        $this->resetForm();
    }

    public function markSent($id): void
    {
        PurchaseOrder::findOrFail($id)->update(['status' => 'sent']);
    }

    public function markReceived($id): void
    {
        $order = PurchaseOrder::with('items.ingredient')->findOrFail($id);
        if ($order->status !== 'sent') return;

        foreach ($order->items as $item) {
            $ingredient = $item->ingredient;
            if (!$ingredient) continue;

            $before = $ingredient->stock_current;
            $after = $before + $item->quantity;

            InventoryMovement::create([
                'ingredient_id' => $ingredient->id,
                'user_id' => Auth::id(),
                'type' => 'purchase',
                'quantity' => $item->quantity,
                'stock_before' => $before,
                'stock_after' => $after,
                'unit_cost' => $item->unit_cost,
                'reference_id' => $order->id,
                'reference_type' => 'purchase_order',
                'reason' => 'Recepción OC #' . $order->id,
            ]);

            $ingredient->update([
                'stock_current' => $after,
                'unit_cost' => $item->unit_cost,
            ]);
        }

        $order->update([
            'status' => 'received',
            'received_at' => now(),
            'total' => $order->items->sum('subtotal'),
        ]);
    }

    public function markCancelled($id): void
    {
        PurchaseOrder::findOrFail($id)->update(['status' => 'cancelled']);
    }

    public function viewOrder($id): void
    {
        $this->viewOrderId = $id;
    }

    public function getViewOrderProperty()
    {
        if (!$this->viewOrderId) return null;
        return PurchaseOrder::with('supplier', 'user', 'items.ingredient')->find($this->viewOrderId);
    }

    private function resetForm(): void
    {
        $this->orderId = null;
        $this->supplierId = '';
        $this->notes = '';
        $this->orderedAt = now()->format('Y-m-d');
        $this->lines = [];
        $this->newIngredientId = '';
        $this->newQuantity = 1;
        $this->newUnitCost = 0;
    }

    public function render()
    {
        return view('livewire.inventory.purchase-order-index')
            ->layout('layouts.app');
    }
}

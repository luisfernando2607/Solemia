<?php

namespace App\Livewire\Kitchen;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $areaFilter = '';
    public $pendingCount = 0;

    public function getActiveOrdersProperty()
    {
        $query = Order::whereIn('status', ['sent', 'partial'])
            ->with(['items' => function ($q) {
                $q->whereIn('kitchen_status', ['pending', 'preparing', 'ready'])
                    ->with('product');
            }, 'table', 'user'])
            ->whereHas('items', function ($q) {
                $q->whereIn('kitchen_status', ['pending', 'preparing', 'ready']);
                if ($this->areaFilter) {
                    $q->where('kitchen_area', $this->areaFilter);
                }
            })
            ->orderBy('created_at')
            ->get()
            ->filter(fn($o) => $o->items->isNotEmpty());

        $this->pendingCount = $query->sum(fn($o) => $o->items->where('kitchen_status', 'pending')->count());

        return $query;
    }

    public function getAreasProperty()
    {
        return [
            '' => 'Todas las áreas',
            'parrilla' => 'Parrilla',
            'cocina' => 'Cocina',
            'horno' => 'Horno',
            'frio' => 'Frío',
            'postres' => 'Postres',
            'bebidas' => 'Bebidas',
        ];
    }

    public function markPreparing(OrderItem $item): void
    {
        if ($item->kitchen_status !== 'pending') return;
        $item->update(['kitchen_status' => 'preparing']);
    }

    public function markReady(OrderItem $item): void
    {
        if ($item->kitchen_status !== 'preparing') return;
        $item->update(['kitchen_status' => 'ready', 'ready_at' => now()]);
    }

    public function markAllReady($orderId): void
    {
        $order = Order::findOrFail($orderId);
        $order->items()->where('kitchen_status', 'preparing')->update([
            'kitchen_status' => 'ready',
            'ready_at' => now(),
        ]);
    }

    public function completeOrder($orderId): void
    {
        $order = Order::findOrFail($orderId);
        $pendingItems = $order->items()->whereIn('kitchen_status', ['pending', 'preparing'])->count();
        if ($pendingItems > 0) return;

        $order->update(['status' => 'complete', 'closed_at' => now()]);
        $this->dispatch('swal', icon: 'success', title: 'Comanda completada');
    }

    public function render()
    {
        return view('livewire.kitchen.index')
            ->layout('layouts.app');
    }
}

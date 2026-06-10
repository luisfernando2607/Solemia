<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class KitchenBadge extends Component
{
    protected $listeners = ['refreshKitchenBadge' => '$refresh'];

    public function getCountProperty()
    {
        return Order::whereIn('status', ['sent', 'partial'])->count();
    }

    public function render()
    {
        return view('livewire.kitchen-badge');
    }
}

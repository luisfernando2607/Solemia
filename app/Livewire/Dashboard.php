<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\TableModel;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public $occupiedTables = 0;
    public $kitchenOrders = 0;
    public $dailySales = 0;
    public $activeUsers = 0;
    public $totalUsers = 0;

    public function mount(): void
    {
        $this->refreshStats();
    }

    public function refreshStats(): void
    {
        $this->occupiedTables = TableModel::whereIn('status', ['occupied', 'reserved'])->count();
        $this->kitchenOrders = Order::whereIn('status', ['sent', 'partial'])->count();
        $this->dailySales = (float) Order::whereDate('created_at', today())
            ->where('status', 'complete')
            ->sum('total');
        $this->activeUsers = User::where('is_active', true)->count();
        $this->totalUsers = User::count();
    }

    public function render()
    {
        return view('livewire.dashboard')
            ->layout('layouts.app');
    }
}

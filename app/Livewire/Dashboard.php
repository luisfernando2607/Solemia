<?php

namespace App\Livewire;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TableModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public $occupiedTables = 0;
    public $kitchenOrders = 0;
    public $dailySales = 0;
    public $activeUsers = 0;
    public $totalUsers = 0;

    public $chartPeriod = 'week';
    public $weeklySales = [];
    public $lastWeekSales = [];
    public $monthlySales = [];
    public $lastMonthSales = [];

    public $ordersToday = 0;
    public $avgTicket = 0;
    public $lowStockCount = 0;
    public $itemsSoldToday = 0;
    public $topProductsToday = [];

    public function mount(): void
    {
        $this->refreshStats();
    }

    public function changeChartPeriod($period): void
    {
        $this->chartPeriod = $period;
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

        $ordersToday = Order::whereDate('created_at', today());
        $this->ordersToday = $ordersToday->count();

        $completedToday = Order::where('status', 'complete')->whereDate('closed_at', today());
        $this->avgTicket = (float) $completedToday->avg('total') ?? 0;

        $this->lowStockCount = Ingredient::whereColumn('stock_current', '<=', 'stock_minimum')
            ->where('is_active', true)->count();

        $this->itemsSoldToday = (int) OrderItem::whereHas('order', function ($q) {
            $q->whereDate('created_at', today());
        })->sum('quantity');

        $this->topProductsToday = OrderItem::whereHas('order', function ($q) {
                $q->whereDate('created_at', today());
            })
            ->select('product_id', DB::raw('SUM(quantity) as qty'), DB::raw('SUM(subtotal) as revenue'))
            ->groupBy('product_id')
            ->orderByDesc('qty')
            ->with('product')
            ->limit(5)
            ->get();

        $this->weeklySales = Order::where('status', 'complete')
            ->whereBetween('closed_at', [now()->startOfWeek()->startOfDay(), now()->endOfWeek()->endOfDay()])
            ->select(DB::raw('WEEKDAY(closed_at) as day_idx'), DB::raw('SUM(COALESCE(total,0)) as total'))
            ->groupBy('day_idx')
            ->orderBy('day_idx')
            ->pluck('total', 'day_idx');

        $this->lastWeekSales = Order::where('status', 'complete')
            ->whereBetween('closed_at', [now()->subWeek()->startOfWeek()->startOfDay(), now()->subWeek()->endOfWeek()->endOfDay()])
            ->select(DB::raw('WEEKDAY(closed_at) as day_idx'), DB::raw('SUM(COALESCE(total,0)) as total'))
            ->groupBy('day_idx')
            ->orderBy('day_idx')
            ->pluck('total', 'day_idx');

        $this->monthlySales = Order::where('status', 'complete')
            ->whereBetween('closed_at', [now()->startOfMonth()->startOfDay(), now()->endOfMonth()->endOfDay()])
            ->select(DB::raw('FLOOR((DAYOFMONTH(closed_at) - 1) / 7) + 1 as week_idx'), DB::raw('SUM(COALESCE(total,0)) as total'))
            ->groupBy('week_idx')
            ->orderBy('week_idx')
            ->pluck('total', 'week_idx');

        $this->lastMonthSales = Order::where('status', 'complete')
            ->whereBetween('closed_at', [now()->subMonth()->startOfMonth()->startOfDay(), now()->subMonth()->endOfMonth()->endOfDay()])
            ->select(DB::raw('FLOOR((DAYOFMONTH(closed_at) - 1) / 7) + 1 as week_idx'), DB::raw('SUM(COALESCE(total,0)) as total'))
            ->groupBy('week_idx')
            ->orderBy('week_idx')
            ->pluck('total', 'week_idx');
    }

    public function render()
    {
        return view('livewire.dashboard')
            ->layout('layouts.app');
    }
}

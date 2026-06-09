<?php

namespace App\Livewire\Reports;

use App\Models\CashRegister;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\TableModel;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public $activeTab = 'dashboard';
    public $dateFrom = '';
    public $dateTo = '';

    protected $listeners = ['refreshReports' => '$refresh'];

    public function mount(): void
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function goToday(): void
    {
        $this->dateFrom = now()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function goWeek(): void
    {
        $this->dateFrom = now()->startOfWeek()->format('Y-m-d');
        $this->dateTo = now()->endOfWeek()->format('Y-m-d');
    }

    public function goMonth(): void
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->endOfMonth()->format('Y-m-d');
    }

    // ===== DASHBOARD =====
    public function getDashboardSalesTodayProperty()
    {
        return (float)Order::where('status', 'complete')
            ->whereDate('closed_at', today())
            ->sum('total');
    }

    public function getDashboardOrdersTodayProperty()
    {
        return Order::whereDate('opened_at', today())->count();
    }

    public function getDashboardOpenTablesProperty()
    {
        return TableModel::whereIn('status', ['occupied', 'reserved'])->count();
    }

    public function getDashboardLowStockProperty()
    {
        return \App\Models\Ingredient::whereColumn('stock_current', '<=', 'stock_minimum')
            ->where('is_active', true)->count();
    }

    public function getDashboardPendingKitchenProperty()
    {
        return OrderItem::whereIn('kitchen_status', ['pending', 'preparing'])->count();
    }

    public function getDashboardHourlySalesProperty()
    {
        return Order::where('status', 'complete')
            ->whereDate('closed_at', today())
            ->select(DB::raw('HOUR(closed_at) as hour'), DB::raw('SUM(total) as total'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('total', 'hour');
    }

    public function getDashboardRecentOrdersProperty()
    {
        return Order::where('status', 'complete')
            ->with('table', 'user', 'payments')
            ->latest('closed_at')
            ->limit(8)
            ->get();
    }

    // ===== VENTAS =====
    public function getSalesTotalProperty()
    {
        return (float)Order::where('status', 'complete')
            ->whereBetween('closed_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->sum('total');
    }

    public function getSalesByDayProperty()
    {
        $rows = Order::where('status', 'complete')
            ->whereBetween('closed_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->select(DB::raw('DATE(closed_at) as date'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $period = CarbonPeriod::create($this->dateFrom, $this->dateTo);

        return collect($period)->map(function ($date) use ($rows) {
            $d = $date->format('Y-m-d');
            $r = $rows->get($d);
            return (object)[
                'date' => $d,
                'total' => $r ? (float)$r->total : 0,
                'count' => $r ? (int)$r->count : 0,
            ];
        })->values();
    }

    public function getSalesByHourProperty()
    {
        $from = $this->dateFrom . ' 00:00:00';
        $to = $this->dateTo . ' 23:59:59';

        $rows = Order::where('status', 'complete')
            ->whereBetween('closed_at', [$from, $to])
            ->select(DB::raw('HOUR(closed_at) as hour'), DB::raw('SUM(total) as total'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('total', 'hour');

        return collect(range(0, 23))->mapWithKeys(fn($h) => [$h => (float)($rows[$h] ?? 0)]);
    }

    public function getSalesByPaymentMethodProperty()
    {
        return Payment::where('status', 'approved')
            ->whereBetween('processed_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->select('method', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('method')
            ->get();
    }

    public function getSalesByCashierProperty()
    {
        return User::whereHas('cashierOrders', function ($q) {
            $q->where('status', 'complete')
              ->whereBetween('closed_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59']);
        })->withCount(['cashierOrders as total_sales' => function ($q) {
            $q->where('status', 'complete')
              ->whereBetween('closed_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
              ->select(DB::raw('COALESCE(SUM(total), 0)'));
        }])->withCount(['cashierOrders as order_count' => function ($q) {
            $q->where('status', 'complete')
              ->whereBetween('closed_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59']);
        }])->get();
    }

    public function getSalesAvgTicketProperty()
    {
        $count = Order::where('status', 'complete')
            ->whereBetween('closed_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->count();
        if ($count === 0) return 0;
        return $this->salesTotal / $count;
    }

    // ===== PRODUCTOS =====
    public function getTopProductsProperty()
    {
        return Product::whereHas('orderItems', function ($q) {
            $q->whereHas('order', function ($oq) {
                $oq->where('status', 'complete')
                   ->whereBetween('closed_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59']);
            });
        })->withCount(['orderItems as qty' => function ($q) {
            $q->whereHas('order', function ($oq) {
                $oq->where('status', 'complete')
                   ->whereBetween('closed_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59']);
            })->select(DB::raw('COALESCE(SUM(quantity), 0)'));
        }])->withCount(['orderItems as revenue' => function ($q) {
            $q->whereHas('order', function ($oq) {
                $oq->where('status', 'complete')
                   ->whereBetween('closed_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59']);
            })->select(DB::raw('COALESCE(SUM(subtotal), 0)'));
        }])->orderByDesc('qty')
          ->limit(15)
          ->get();
    }

    public function getSalesByCategoryProperty()
    {
        return \App\Models\Category::whereHas('products.orderItems.order', function ($q) {
            $q->where('status', 'complete')
              ->whereBetween('closed_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59']);
        })->get()->map(function ($cat) {
            $cat->total = (float) \App\Models\OrderItem::whereHas('product', function ($q) use ($cat) {
                $q->where('category_id', $cat->id);
            })->whereHas('order', function ($q) {
                $q->where('status', 'complete')
                  ->whereBetween('closed_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59']);
            })->sum('subtotal');
            return $cat;
        });
    }

    // ===== SERVICIO =====
    public function getWaiterPerformanceProperty()
    {
        return User::role('Mesero')->whereHas('orders', function ($q) {
            $q->whereBetween('opened_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59']);
        })->withCount(['orders as order_count' => function ($q) {
            $q->whereBetween('opened_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59']);
        }])->withCount(['orders as total_sales' => function ($q) {
            $q->where('status', 'complete')
              ->whereBetween('closed_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
              ->select(DB::raw('COALESCE(SUM(total), 0)'));
        }])->withCount(['orders as total_tips' => function ($q) {
            $q->whereBetween('closed_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
              ->select(DB::raw('COALESCE(SUM(tip), 0)'));
        }])->get();
    }

    public function getKitchenTimesProperty()
    {
        return OrderItem::whereNotNull('sent_at')
            ->whereNotNull('ready_at')
            ->whereBetween('sent_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->with('product')
            ->select('product_id', DB::raw('AVG(TIMESTAMPDIFF(MINUTE, sent_at, ready_at)) as avg_minutes'), DB::raw('COUNT(*) as count'))
            ->groupBy('product_id')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
    }

    // ===== CAJA =====
    public function getCashRegistersProperty()
    {
        return CashRegister::with('user')
            ->whereBetween('opened_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->orderByDesc('opened_at')
            ->get();
    }

    public function getDailyCashFlowProperty()
    {
        $rows = Order::where('status', 'complete')
            ->whereBetween('closed_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->select(DB::raw('DATE(closed_at) as date'),
                DB::raw('SUM(total) as income'),
                DB::raw('SUM(COALESCE(discount, 0)) as discounts'),
                DB::raw('SUM(COALESCE(tip, 0)) as tips'),
                DB::raw('COUNT(*) as orders'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $period = CarbonPeriod::create($this->dateFrom, $this->dateTo);

        return collect($period)->map(function ($date) use ($rows) {
            $d = $date->format('Y-m-d');
            $r = $rows->get($d);
            return (object)[
                'date' => $d,
                'income' => $r ? (float)$r->income : 0,
                'discounts' => $r ? (float)$r->discounts : 0,
                'tips' => $r ? (float)$r->tips : 0,
                'orders' => $r ? (int)$r->orders : 0,
            ];
        })->values();
    }

    public function changeTab($tab): void
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.reports.index')
            ->layout('layouts.app');
    }
}

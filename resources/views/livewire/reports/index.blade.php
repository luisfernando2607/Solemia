<div class="flex flex-col h-full">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-olive-900">Reportes y Analítica</h2>
            <p class="text-sm text-gray-500">Dashboard ejecutivo, ventas, productos, desempeño y caja</p>
        </div>
        <div class="flex items-center gap-2">
            <x-text-input wire:model="dateFrom" type="date" class="input-field text-xs w-32" />
            <span class="text-xs text-gray-400">→</span>
            <x-text-input wire:model="dateTo" type="date" class="input-field text-xs w-32" />
            <button wire:click="goToday" class="btn-secondary text-xs">Hoy</button>
            <button wire:click="goWeek" class="btn-secondary text-xs">Semana</button>
            <button wire:click="goMonth" class="btn-secondary text-xs">Mes</button>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-1 mb-4 overflow-x-auto">
        <button wire:click="changeTab('dashboard')"
            class="px-4 py-2 text-sm rounded-lg font-medium transition-all whitespace-nowrap
            {{ $activeTab === 'dashboard' ? 'bg-olive-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            <i class="fas fa-gauge-high mr-1.5"></i>Dashboard
        </button>
        <button wire:click="changeTab('ventas')"
            class="px-4 py-2 text-sm rounded-lg font-medium transition-all whitespace-nowrap
            {{ $activeTab === 'ventas' ? 'bg-olive-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            <i class="fas fa-dollar-sign mr-1.5"></i>Ventas
        </button>
        <button wire:click="changeTab('productos')"
            class="px-4 py-2 text-sm rounded-lg font-medium transition-all whitespace-nowrap
            {{ $activeTab === 'productos' ? 'bg-olive-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            <i class="fas fa-utensils mr-1.5"></i>Productos
        </button>
        <button wire:click="changeTab('servicio')"
            class="px-4 py-2 text-sm rounded-lg font-medium transition-all whitespace-nowrap
            {{ $activeTab === 'servicio' ? 'bg-olive-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            <i class="fas fa-users mr-1.5"></i>Servicio
        </button>
        <button wire:click="changeTab('caja')"
            class="px-4 py-2 text-sm rounded-lg font-medium transition-all whitespace-nowrap
            {{ $activeTab === 'caja' ? 'bg-olive-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            <i class="fas fa-cash-register mr-1.5"></i>Caja
        </button>
    </div>

    {{-- ==================== DASHBOARD ==================== --}}
    @if($activeTab === 'dashboard')
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs text-gray-400 uppercase tracking-wider">Ventas hoy</p>
                <p class="text-2xl font-bold text-olive-700 mt-1">${{ number_format($this->dashboardSalesToday, 2) }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs text-gray-400 uppercase tracking-wider">Órdenes hoy</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $this->dashboardOrdersToday }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs text-gray-400 uppercase tracking-wider">Mesas ocupadas</p>
                <p class="text-2xl font-bold text-amber-600 mt-1">{{ $this->dashboardOpenTables }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs text-gray-400 uppercase tracking-wider">Cocina pendiente</p>
                <div class="flex items-center gap-2 mt-1">
                    <p class="text-2xl font-bold {{ $this->dashboardPendingKitchen > 0 ? 'text-red-600' : 'text-gray-800' }}">{{ $this->dashboardPendingKitchen }}</p>
                    @if($this->dashboardLowStock > 0)
                        <span class="text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-700">
                            <i class="fas fa-exclamation-triangle mr-0.5"></i>{{ $this->dashboardLowStock }} stock
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex-1 grid grid-cols-1 lg:grid-cols-3 gap-4 min-h-0">
            {{-- Hourly Sales Chart --}}
            <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Ventas por hora (hoy)</h3>
                <div class="h-64">
                    <canvas wire:key="hourly-{{ $dateFrom }}-{{ $dateTo }}"
                        x-data="{}" x-init="
                        $nextTick(() => {
                        const ctx = $el.getContext('2d');
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: {{ Js::from(range(0, 23)) }},
                                datasets: [{
                                    label: 'Ventas ($)',
                                    data: {{ Js::from(array_values($this->dashboardHourlySales->toArray())) }},
                                    backgroundColor: '#6B8E4E',
                                    borderRadius: 4,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    x: { grid: { display: false }, ticks: { callback: v => v + 'h' } },
                                    y: { beginAtZero: true, ticks: { callback: v => '$' + v } }
                                }
                            }
                        });
                    });
                    "></canvas>
                </div>
            </div>

            {{-- Recent Orders --}}
            <div class="bg-white rounded-xl border border-gray-200 flex flex-col min-h-0">
                <div class="p-3 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900">Últimas ventas</h3>
                </div>
                <div class="flex-1 overflow-y-auto p-2 space-y-1">
                    @forelse($this->dashboardRecentOrders as $o)
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 text-sm">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="text-xs font-bold text-gray-400">#{{ $o->id }}</span>
                                <span class="text-xs text-gray-600 truncate">{{ $o->table ? 'Mesa ' . $o->table->number : 'LL' }}</span>
                            </div>
                            <span class="text-xs font-medium text-gray-800">${{ number_format($o->total, 2) }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 text-center py-4">Sin ventas hoy</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    {{-- ==================== VENTAS ==================== --}}
    @if($activeTab === 'ventas')
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs text-gray-400 uppercase">Total ventas</p>
                <p class="text-2xl font-bold text-olive-700 mt-1">${{ number_format($this->salesTotal, 2) }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs text-gray-400 uppercase">Ticket promedio</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">${{ number_format($this->salesAvgTicket, 2) }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs text-gray-400 uppercase">Órdenes</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $this->salesByDay->sum('count') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs text-gray-400 uppercase">Días</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $this->salesByDay->count() }}</p>
            </div>
        </div>

        <div class="flex-1 grid grid-cols-1 lg:grid-cols-2 gap-4 min-h-0 overflow-y-auto">
            {{-- Sales by Day / Hour Chart --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <h3 class="text-sm font-semibold text-gray-900">
                    {{ $dateFrom === $dateTo ? 'Ventas por hora' : 'Ventas diarias' }}
                    <span class="text-xs font-normal text-gray-400 ml-1">
                        @if($dateFrom === $dateTo)
                        — {{\Carbon\Carbon::parse($dateFrom)->format('d/m/Y')}}
                        @else
                        — {{\Carbon\Carbon::parse($dateFrom)->format('d/m')}} al {{\Carbon\Carbon::parse($dateTo)->format('d/m/Y')}}
                        @endif
                    </span>
                </h3>
                <div class="h-64">
                    @if($dateFrom === $dateTo)
                    <canvas wire:key="saleshour-{{ $dateFrom }}"
                        x-data="{}" x-init="
                        $nextTick(() => {
                        const ctx = $el.getContext('2d');
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: {{ Js::from(range(0, 23)) }},
                                datasets: [{
                                    label: 'Ventas ($)',
                                    data: {{ Js::from(array_values($this->salesByHour->toArray())) }},
                                    backgroundColor: '#6B8E4E',
                                    borderRadius: 4,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    x: { grid: { display: false }, ticks: { callback: v => v + 'h' } },
                                    y: { beginAtZero: true, ticks: { callback: v => '$' + v } }
                                }
                            }
                        });
                    });
                    "></canvas>
                    @else
                    <canvas wire:key="salesday-{{ $dateFrom }}-{{ $dateTo }}"
                        x-data="{}" x-init="
                        $nextTick(() => {
                        const ctx = $el.getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: {{ Js::from($this->salesByDay->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) }},
                                datasets: [{
                                    label: 'Ventas',
                                    data: {{ Js::from($this->salesByDay->pluck('total')->map(fn($v) => (float)$v)) }},
                                    borderColor: '#6B8E4E',
                                    backgroundColor: 'rgba(107,142,78,0.1)',
                                    fill: true,
                                    tension: 0.3,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: { y: { beginAtZero: true, ticks: { callback: v => '$' + v } } }
                            }
                        });
                    });
                    "></canvas>
                    @endif
                </div>
            </div>

            {{-- Sales by Payment Method --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Por método de pago</h3>
                <div class="h-64">
                    <canvas wire:key="payment-{{ $dateFrom }}-{{ $dateTo }}"
                        x-data="{}" x-init="
                        $nextTick(() => {
                        const ctx = $el.getContext('2d');
                        new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: {{ Js::from($this->salesByPaymentMethod->pluck('method')->map(fn($m) => str_replace('_', ' ', ucfirst($m)))) }},
                                datasets: [{
                                    data: {{ Js::from($this->salesByPaymentMethod->pluck('total')->map(fn($v) => (float)$v)) }},
                                    backgroundColor: ['#6B8E4E', '#D4A843', '#3B82F6', '#8B5CF6', '#EC4899', '#6B7280'],
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } }
                                }
                            }
                        });
                    });
                    "></canvas>
                </div>
            </div>

            {{-- Sales by Cashier --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4 lg:col-span-2">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Por cajero</h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-400 uppercase border-b border-gray-100">
                            <th class="text-left py-2">Cajero</th>
                            <th class="text-right py-2">Órdenes</th>
                            <th class="text-right py-2">Total</th>
                            <th class="text-right py-2">Promedio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->salesByCashier as $c)
                            <tr class="border-b border-gray-50">
                                <td class="py-2 font-medium text-gray-800">{{ $c->name }}</td>
                                <td class="py-2 text-right text-gray-600">{{ $c->order_count }}</td>
                                <td class="py-2 text-right text-gray-800">${{ number_format($c->total_sales, 2) }}</td>
                                <td class="py-2 text-right text-gray-600">
                                    ${{ $c->order_count > 0 ? number_format($c->total_sales / $c->order_count, 2) : '0.00' }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-4 text-center text-gray-400 text-xs">Sin datos</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- ==================== PRODUCTOS ==================== --}}
    @if($activeTab === 'productos')
        <div class="flex-1 grid grid-cols-1 lg:grid-cols-2 gap-4 min-h-0 overflow-y-auto">
            {{-- Top Products --}}
            <div class="bg-white rounded-xl border border-gray-200 flex flex-col min-h-0">
                <div class="p-3 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900">Top productos vendidos</h3>
                </div>
                <div class="flex-1 overflow-y-auto p-2">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-xs text-gray-400 uppercase border-b border-gray-100">
                                <th class="text-left py-2">#</th>
                                <th class="text-left py-2">Producto</th>
                                <th class="text-right py-2">Cant.</th>
                                <th class="text-right py-2">Ingresos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($this->topProducts as $i => $p)
                                <tr class="border-b border-gray-50">
                                    <td class="py-2 text-gray-400 w-8">{{ $i + 1 }}</td>
                                    <td class="py-2 font-medium text-gray-800">{{ $p->name }}</td>
                                    <td class="py-2 text-right text-gray-800">{{ $p->qty }}</td>
                                    <td class="py-2 text-right text-gray-800">${{ number_format($p->revenue, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-4 text-center text-gray-400 text-xs">Sin datos</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Sales by Category --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Por categoría</h3>
                <div class="h-64">
                    <canvas wire:key="category-{{ $dateFrom }}-{{ $dateTo }}"
                        x-data="{}" x-init="
                        $nextTick(() => {
                        const ctx = $el.getContext('2d');
                        new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: {{ Js::from($this->salesByCategory->pluck('name')) }},
                                datasets: [{
                                    data: {{ Js::from($this->salesByCategory->pluck('total')->map(fn($v) => (float)$v)) }},
                                    backgroundColor: ['#6B8E4E', '#D4A843', '#3B82F6', '#8B5CF6', '#EC4899', '#F97316', '#14B8A6'],
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } }
                                }
                            }
                        });
                    });
                    "></canvas>
                </div>
                <div class="mt-3 space-y-1">
                    @foreach($this->salesByCategory as $cat)
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-600">{{ $cat->name }}</span>
                            <span class="font-medium text-gray-800">${{ number_format($cat->total, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ==================== SERVICIO ==================== --}}
    @if($activeTab === 'servicio')
        <div class="flex-1 grid grid-cols-1 lg:grid-cols-2 gap-4 min-h-0 overflow-y-auto">
            {{-- Waiter Performance --}}
            <div class="bg-white rounded-xl border border-gray-200 flex flex-col min-h-0">
                <div class="p-3 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900">Desempeño de meseros</h3>
                </div>
                <div class="flex-1 overflow-y-auto p-2">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-xs text-gray-400 uppercase border-b border-gray-100">
                                <th class="text-left py-2">Mesero</th>
                                <th class="text-right py-2">Órdenes</th>
                                <th class="text-right py-2">Ventas</th>
                                <th class="text-right py-2">Ticket prom.</th>
                                <th class="text-right py-2">Propinas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($this->waiterPerformance as $w)
                                <tr class="border-b border-gray-50">
                                    <td class="py-2 font-medium text-gray-800">{{ $w->name }}</td>
                                    <td class="py-2 text-right text-gray-600">{{ $w->order_count }}</td>
                                    <td class="py-2 text-right text-gray-800">${{ number_format($w->total_sales, 2) }}</td>
                                    <td class="py-2 text-right text-gray-600">
                                        ${{ $w->order_count > 0 ? number_format($w->total_sales / $w->order_count, 2) : '0.00' }}
                                    </td>
                                    <td class="py-2 text-right text-gray-800">${{ number_format($w->total_tips, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-4 text-center text-gray-400 text-xs">Sin datos</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Kitchen Times --}}
            <div class="bg-white rounded-xl border border-gray-200 flex flex-col min-h-0">
                <div class="p-3 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900">Tiempos promedio de cocina</h3>
                </div>
                <div class="flex-1 overflow-y-auto p-2">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-xs text-gray-400 uppercase border-b border-gray-100">
                                <th class="text-left py-2">Producto</th>
                                <th class="text-right py-2">Tiempo prom.</th>
                                <th class="text-right py-2">Veces</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($this->kitchenTimes as $kt)
                                <tr class="border-b border-gray-50">
                                    <td class="py-2 font-medium text-gray-800">{{ $kt->product?->name ?? '—' }}</td>
                                    <td class="py-2 text-right">
                                        <span class="text-sm font-medium {{ $kt->avg_minutes > 15 ? 'text-red-600' : ($kt->avg_minutes > 8 ? 'text-amber-600' : 'text-emerald-600') }}">
                                            {{ number_format($kt->avg_minutes, 1) }} min
                                        </span>
                                    </td>
                                    <td class="py-2 text-right text-gray-600">{{ $kt->count }}x</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="py-4 text-center text-gray-400 text-xs">Sin datos</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- ==================== CAJA ==================== --}}
    @if($activeTab === 'caja')
        <div class="flex-1 grid grid-cols-1 lg:grid-cols-2 gap-4 min-h-0 overflow-y-auto">
            {{-- Daily Cash Flow --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Flujo de caja diario</h3>
                <div class="h-64">
                    <canvas wire:key="cashflow-{{ $dateFrom }}-{{ $dateTo }}"
                        x-data="{}" x-init="
                        $nextTick(() => {
                        const ctx = $el.getContext('2d');
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: {{ Js::from($this->dailyCashFlow->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) }},
                                datasets: [
                                    {
                                        label: 'Ingresos',
                                        data: {{ Js::from($this->dailyCashFlow->pluck('income')->map(fn($v) => (float)$v)) }},
                                        backgroundColor: '#6B8E4E',
                                        borderRadius: 4,
                                    },
                                    {
                                        label: 'Descuentos',
                                        data: {{ Js::from($this->dailyCashFlow->pluck('discounts')->map(fn($v) => (float)$v)) }},
                                        backgroundColor: '#EF4444',
                                        borderRadius: 4,
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } },
                                scales: { y: { beginAtZero: true, ticks: { callback: v => '$' + v } } }
                            }
                        });
                    });
                    "></canvas>
                </div>
            </div>

            {{-- Cash Register History --}}
            <div class="bg-white rounded-xl border border-gray-200 flex flex-col min-h-0">
                <div class="p-3 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900">Historial de cierres</h3>
                </div>
                <div class="flex-1 overflow-y-auto p-2">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-xs text-gray-400 uppercase border-b border-gray-100">
                                <th class="text-left py-2">Fecha</th>
                                <th class="text-left py-2">Cajero</th>
                                <th class="text-right py-2">Apertura</th>
                                <th class="text-right py-2">Esperado</th>
                                <th class="text-right py-2">Diferencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($this->cashRegisters as $cr)
                                <tr class="border-b border-gray-50">
                                    <td class="py-2 text-gray-600 text-xs">{{ $cr->opened_at->format('d/m/Y') }}</td>
                                    <td class="py-2 font-medium text-gray-800">{{ $cr->user?->name ?? '—' }}</td>
                                    <td class="py-2 text-right text-gray-600">${{ number_format($cr->opening_amount, 2) }}</td>
                                    <td class="py-2 text-right text-gray-800">${{ number_format($cr->expected_amount ?? 0, 2) }}</td>
                                    <td class="py-2 text-right">
                                        <span class="{{ ($cr->difference ?? 0) != 0 ? 'text-red-600 font-medium' : 'text-emerald-600' }}">
                                            ${{ number_format($cr->difference ?? 0, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-4 text-center text-gray-400 text-xs">Sin cierres</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>

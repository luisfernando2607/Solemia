<div class="flex flex-col h-full">
    {{-- Header + Cash Register Status --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-olive-900">Caja</h2>
            <p class="text-sm text-gray-500">Procesar pagos y gestionar turno de caja</p>
        </div>
        <div class="flex items-center gap-2">
            @if($activeRegister)
                <span class="text-xs px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-700 font-medium">
                    <i class="fas fa-cash-register mr-1"></i>{{ $activeRegister->name }}
                </span>
                <div class="hidden sm:flex items-center gap-3 text-xs text-gray-500">
                    <span><i class="fas fa-coins mr-1"></i>Apertura: ${{ number_format($activeRegister->opening_amount, 2) }}</span>
                    <span><i class="fas fa-dollar-sign mr-1"></i>Ventas: ${{ number_format($this->registerTotalSales, 2) }}</span>
                    <span><i class="fas fa-calculator mr-1"></i>Esperado: ${{ number_format($activeRegister->opening_amount + $this->registerTotalSales, 2) }}</span>
                    <span><i class="fas fa-receipt mr-1"></i>{{ $this->registerTransactionsCount }} trans.</span>
                </div>
                <button x-data @click.prevent="
                    Swal.fire({
                        title: '¿Cerrar turno de caja?',
                        text: 'Se cerrará el turno actual y se mostrará el resumen.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, cerrar caja',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) $wire.closeRegister();
                    });
                " class="btn-secondary text-xs">
                    <i class="fas fa-lock"></i> Cerrar caja
                </button>
            @else
                <span class="text-xs px-3 py-1.5 rounded-full bg-gray-100 text-gray-500 font-medium">
                    <i class="fas fa-power-off mr-1"></i>Caja cerrada
                </span>
                <button wire:click="showOpenRegister" class="btn-primary text-xs">
                    <i class="fas fa-cash-register"></i> Abrir caja
                </button>
            @endif
        </div>
    </div>

    {{-- Open Register Form --}}
    @if($showOpenForm)
        <div class="card mb-4">
            <div class="p-4">
                <form wire:submit="openRegister" class="flex flex-wrap items-end gap-3">
                    <div>
                        <x-input-label value="Nombre del turno" class="text-xs" />
                        <x-text-input wire:model="registerName" class="input-field text-sm" />
                    </div>
                    <div>
                        <x-input-label value="Monto inicial" class="text-xs" />
                        <x-text-input wire:model="openingAmount" type="number" step="0.01" class="input-field text-sm w-32" />
                        <x-input-error :messages="$errors->get('openingAmount')" />
                    </div>
                    <button type="submit" class="btn-primary text-sm">Abrir caja</button>
                    <button type="button" wire:click="$set('showOpenForm', false)" class="btn-secondary text-sm">Cancelar</button>
                </form>
            </div>
        </div>
    @endif

    <div class="flex-1 flex flex-col lg:flex-row gap-4 min-h-0">
        {{-- Orders List --}}
        <div class="lg:w-80 shrink-0 flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="p-3 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-900">
                    <i class="fas fa-receipt mr-1.5 text-olive-600"></i>Cuentas por cobrar
                    <span class="text-xs font-normal text-gray-400 ml-1">({{ $this->ordersForBilling->count() }})</span>
                </h3>
            </div>
            <div class="flex-1 overflow-y-auto p-2 space-y-1">
                @forelse ($this->ordersForBilling as $order)
                    <button wire:click="selectOrder({{ $order->id }})"
                        class="w-full flex items-center gap-3 p-3 rounded-lg text-left transition-all text-sm
                        {{ $selectedOrderId === $order->id ? 'bg-olive-100 border border-olive-200' : 'hover:bg-gray-50 border border-transparent' }}">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center font-bold text-sm
                            {{ $order->status === 'complete' ? 'bg-gray-100 text-gray-500' : 'bg-gold-100 text-gold-700' }}">
                            {{ $order->table?->number ?? 'LL' }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                {{ $order->table ? 'Mesa ' . $order->table->number : ($order->customer_name ?? 'Para llevar') }}
                            </p>
                            <p class="text-xs text-gray-400">{{ $order->user->name }} · {{ $order->created_at->format('H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-olive-700">${{ number_format($order->total, 2) }}</p>
                            <p class="text-[10px] text-gray-400">{{ $order->items->count() }} items</p>
                        </div>
                    </button>
                @empty
                    <div class="flex flex-col items-center py-8 text-gray-400 text-xs">
                        <i class="fas fa-inbox text-2xl mb-2"></i>
                        No hay cuentas pendientes
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Payment Panel --}}
        <div class="flex-1 min-h-0">
            @if(!$selectedOrderId && !$lastProcessedOrderId)
                <div class="h-full flex flex-col items-center justify-center text-gray-400 bg-white rounded-xl border border-gray-200">
                    <i class="fas fa-credit-card text-4xl mb-3"></i>
                    <p class="text-sm">Selecciona una cuenta para procesar el pago</p>
                </div>
            @elseif(!$selectedOrderId && $lastProcessedOrderId)
                @php $lastOrder = \App\Models\Order::with('payments','invoices')->find($lastProcessedOrderId); @endphp
                @if($lastOrder)
                    <div class="h-full flex flex-col items-center justify-center bg-white rounded-xl border border-emerald-200 p-6 text-center">
                        <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mb-4">
                            <i class="fas fa-check-circle text-3xl text-emerald-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Pago exitoso</h3>
                        <p class="text-sm text-gray-500 mb-4">
                            {{ $lastOrder->table ? 'Mesa ' . $lastOrder->table->number : 'Para llevar' }}
                            · ${{ number_format($lastOrder->total, 2) }}
                        </p>
                        <div class="flex flex-wrap justify-center gap-2">
                            <button
                                x-on:click.prevent="window.open('{{ route('cashier.receipt', $lastOrder) }}', 'ticket', 'width=380,height=600,menubar=no,toolbar=no,scrollbars=yes,resizable=yes')"
                                class="btn-primary text-sm flex items-center gap-2">
                                <i class="fas fa-receipt"></i> Imprimir ticket
                            </button>
                            @if($lastOrder->invoices->isEmpty())
                                @if($pendingInvoiceName)
                                    <button x-data="{ invLoading: false }"
                                        x-on:click="invLoading = true; $wire.generateInvoice().finally(() => invLoading = false)"
                                        x-bind:disabled="invLoading"
                                        class="btn-secondary text-sm flex items-center gap-2">
                                        <template x-if="!invLoading"><i class="fas fa-file-invoice"></i></template>
                                        <template x-if="invLoading"><i class="fas fa-spinner fa-spin"></i></template>
                                        <span x-show="!invLoading">Generar factura</span>
                                        <span x-show="invLoading">Generando...</span>
                                    </button>
                                @endif
                            @else
                                <span class="text-xs text-emerald-600 flex items-center gap-1">
                                    <i class="fas fa-check-circle"></i> Factura generada
                                </span>
                            @endif
                            <button wire:click="$set('lastProcessedOrderId', null)" class="btn-secondary text-sm">
                                Nueva cuenta
                            </button>
                        </div>
                    </div>
                @endif
            @elseif($selectedOrder = $this->selectedOrder)
                <div class="h-full flex flex-col lg:flex-row gap-4">
                    {{-- Order Detail --}}
                    <div class="flex-1 flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm min-h-0">
                        <div class="p-3 border-b border-gray-100 flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">
                                    {{ $selectedOrder->table ? 'Mesa ' . $selectedOrder->table->number : 'Para llevar' }}
                                </h3>
                                <p class="text-xs text-gray-400">{{ $selectedOrder->user->name }} · {{ $selectedOrder->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            @if($selectedOrder->notes)
                                <span class="text-[10px] text-gray-400 italic max-w-[200px] truncate" title="{{ $selectedOrder->notes }}">
                                    <i class="fas fa-comment mr-1"></i>{{ $selectedOrder->notes }}
                                </span>
                            @endif
                        </div>
                        <div class="flex-1 overflow-y-auto p-3 space-y-1.5">
                            @foreach ($selectedOrder->items as $item)
                                <div class="flex items-center justify-between py-1.5 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-800">
                                            <span class="font-medium">{{ $item->quantity }}x</span>
                                            {{ $item->product?->name ?? '—' }}
                                        </p>
                                        @if($item->notes)
                                            <p class="text-xs text-gray-400 italic">Nota: {{ $item->notes }}</p>
                                        @endif
                                    </div>
                                    <span class="text-sm font-medium text-gray-800 ml-3">${{ number_format($item->subtotal, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Payment Form --}}
                    <div class="lg:w-80 flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm">
                        <div class="p-3 border-b border-gray-100">
                            <h3 class="text-sm font-semibold text-gray-900">
                                <i class="fas fa-coins mr-1.5 text-olive-600"></i>Cobro
                            </h3>
                        </div>

                        <div class="flex-1 overflow-y-auto p-3 space-y-3">
                            {{-- Discount --}}
                            <div>
                                <x-input-label value="Descuento" class="text-xs" />
                                <select wire:model.live="discountType" class="input-field w-full text-xs rounded-lg border-gray-200 mb-1">
                                    <option value="none">Sin descuento</option>
                                    <option value="percent">Porcentaje (%)</option>
                                    <option value="fixed">Valor fijo ($)</option>
                                </select>
                                @if($discountType !== 'none')
                                    <x-text-input wire:model="discountValue" type="number" step="0.01" class="input-field w-full text-sm" placeholder="Valor" />
                                @endif
                            </div>

                            {{-- Tip --}}
                            <div>
                                <x-input-label value="Propina" class="text-xs" />
                                <div class="flex gap-1">
                                    <button wire:click="$set('tipMode', 'none')" class="px-2 py-1 text-[10px] rounded-lg border {{ $tipMode === 'none' ? 'bg-olive-600 text-white border-olive-600' : 'border-gray-200 text-gray-500 hover:bg-gray-50' }}">Sin</button>
                                    <button wire:click="$set('tipMode', 'ten')" class="px-2 py-1 text-[10px] rounded-lg border {{ $tipMode === 'ten' ? 'bg-olive-600 text-white border-olive-600' : 'border-gray-200 text-gray-500 hover:bg-gray-50' }}">10%</button>
                                    <button wire:click="$set('tipMode', 'fifteen')" class="px-2 py-1 text-[10px] rounded-lg border {{ $tipMode === 'fifteen' ? 'bg-olive-600 text-white border-olive-600' : 'border-gray-200 text-gray-500 hover:bg-gray-50' }}">15%</button>
                                    <button wire:click="$set('tipMode', 'twenty')" class="px-2 py-1 text-[10px] rounded-lg border {{ $tipMode === 'twenty' ? 'bg-olive-600 text-white border-olive-600' : 'border-gray-200 text-gray-500 hover:bg-gray-50' }}">20%</button>
                                </div>
                            </div>

                            {{-- Payment Method --}}
                            <div>
                                <x-input-label value="Método de pago" class="text-xs" />
                                <select wire:model.live="paymentMethod" class="input-field w-full text-sm rounded-lg border-gray-200">
                                    <option value="cash">Efectivo</option>
                                    <option value="credit_card">Tarjeta crédito</option>
                                    <option value="debit_card">Tarjeta débito</option>
                                    <option value="bank_transfer">Transferencia</option>
                                    <option value="qr_wallet">QR / Wallet</option>
                                    <option value="internal_credit">Crédito interno</option>
                                </select>
                            </div>

                            {{-- Cash Tendered --}}
                            @if($paymentMethod === 'cash')
                                <div>
                                    <x-input-label value="Recibido" class="text-xs" />
                                    <x-text-input wire:model.live="cashTendered" type="number" step="0.01" class="input-field w-full text-sm" placeholder="0.00" />
                                </div>
                            @endif

                            {{-- Customer Search --}}
                            <div class="relative">
                                <x-input-label value="Buscar cliente" class="text-xs" />
                                <x-text-input wire:model.live="customerSearch" class="input-field w-full text-sm" placeholder="Nombre, RUC o teléfono..." />
                                @if($showCustomerResults && $this->customerResults->isNotEmpty())
                                    <div class="absolute z-20 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-40 overflow-y-auto">
                                        @foreach ($this->customerResults as $c)
                                            <button wire:click="selectCustomer({{ $c->id }})" type="button"
                                                class="w-full text-left px-3 py-2 text-xs hover:bg-olive-50 border-b border-gray-50 last:border-0">
                                                <span class="font-medium text-gray-800">{{ $c->name }}</span>
                                                @if($c->ruc) <span class="text-gray-400 ml-2">{{ $c->ruc }}</span> @endif
                                                @if($c->phone) <span class="text-gray-400 ml-2">{{ $c->phone }}</span> @endif
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            {{-- Invoice Toggle --}}
                            <div>
                                <label class="flex items-center gap-2 text-xs text-gray-600">
                                    <input type="checkbox" wire:model.live="showInvoiceForm" class="rounded border-gray-300 text-olive-600" />
                                    <i class="fas fa-file-invoice"></i> Generar factura
                                </label>
                            </div>
                            @if($showInvoiceForm)
                                <div class="space-y-1.5 p-2 bg-gray-50 rounded-lg">
                                    <x-text-input wire:model="invoiceCustomerName" class="input-field w-full text-xs" placeholder="Cliente" />
                                    <x-text-input wire:model="invoiceCustomerRuc" class="input-field w-full text-xs" placeholder="RUC / Cédula" />
                                    <x-text-input wire:model="invoiceCustomerEmail" type="email" class="input-field w-full text-xs" placeholder="Email" />
                                    <x-text-input wire:model="invoiceCustomerAddress" class="input-field w-full text-xs" placeholder="Dirección" />
                                    <label class="flex items-center gap-2 text-xs text-gray-600 mt-1">
                                        <input type="checkbox" wire:model="sendInvoiceEmail" class="rounded border-gray-300 text-olive-600" />
                                        <i class="fas fa-envelope"></i> Enviar factura por email
                                    </label>
                                </div>
                            @endif
                        </div>

                        {{-- Totals + Pay Button --}}
                        <div class="p-3 border-t border-gray-100 space-y-1.5">
                            <div class="flex justify-between text-xs text-gray-500">
                                <span>Subtotal</span>
                                <span>${{ number_format($selectedOrder->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500">
                                <span>IVA 15%</span>
                                <span>${{ number_format($selectedOrder->tax, 2) }}</span>
                            </div>
                            @if($discountType !== 'none')
                                <div class="flex justify-between text-xs text-red-500">
                                    <span>Descuento</span>
                                    <span>-${{ number_format($selectedOrder->subtotal - ($this->orderTotal - $selectedOrder->tax - $this->tipAmount), 2) }}</span>
                                </div>
                            @endif
                            @if($tipAmount > 0)
                                <div class="flex justify-between text-xs text-olive-600">
                                    <span>Propina</span>
                                    <span>${{ number_format($tipAmount, 2) }}</span>
                                </div>
                            @endif
                            @if($paymentMethod === 'cash' && $cashChange > 0)
                                <div class="flex justify-between text-xs text-emerald-600">
                                    <span>Cambio</span>
                                    <span>${{ number_format($cashChange, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between font-bold text-sm text-gray-900 border-t border-gray-200 pt-1.5 mt-1.5">
                                <span>Total a pagar</span>
                                <span>${{ number_format($this->orderTotal, 2) }}</span>
                            </div>
                            <button x-data="{ loading: false }"
                                x-on:click="loading = true; $wire.processPayment().finally(() => loading = false)"
                                x-bind:disabled="loading"
                                class="w-full mt-2 py-2.5 bg-olive-600 text-white rounded-xl font-semibold text-sm hover:bg-olive-700 transition-colors shadow-lg shadow-olive-600/20 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <template x-if="!loading">
                                    <i class="fas fa-check-circle"></i>
                                </template>
                                <template x-if="loading">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </template>
                                <span x-show="!loading" x-text="'Cobrar $' + new Intl.NumberFormat('es-EC', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format({{ $this->orderTotal }})"></span>
                                <span x-show="loading">Procesando...</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

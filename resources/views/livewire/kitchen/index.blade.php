<div x-data="{ prevCount: 0 }" x-init="$watch('$wire.pendingCount', val => { if(val > prevCount && prevCount > 0) { let s = new Audio; s.src = 'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACAf39/f4B/f3+AgH9/f3+AgH9/f3+AgH9/f3+AgH9/f3+AgH9/f3+Af39/f4B/f3+AgH9/f4B/f3+AgH9/f3+AgH9/f3+Af39/f3+Af39/f4B/f3+Af39/f4B/f3+AgH9/f3+Af39/f4B/f3+Af3+Af39/f3+Af39/gH9/f3+Af39/gH+Af39/gH+Af3+Af3+Af39/gH+Af39/gH9/f3+Af39/gH+Af39/gH+Af3+Af39/gH9/f3+Af3+Af3+Af39/gH+Af3+Af39/gH+Af39/gH+Af3+Af4B/f3+Af3+Af4B/f3+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH9/f4B/gH+Af3+Af4B/f4B/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af4B/f3+Af4B/f3+Af39/gH9/f3+Af39/gH+Af39/gH9/f3+Af39/gH+Af39/gH+Af39/gH9/f3+Af39/gH+Af39/gH9/f4B/f3+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af4B/gH+Af39/gH+Af4B/f3+Af39/gH+Af39/gH9/f4B/gH+Af4B/gH+Af39/gH+Af39/gH+Af39/gH+Af4B/f3+Af4B/f3+Af4B/gH+Af4B/f3+Af39/gH+Af4B/f3+Af39/gH+Af4B/f3+Af4B/f3+Af39/gH+Af39/gH+Af4B/f3+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af4B/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af4B/f3+Af4B/f3+Af4B/gH+Af4B/f3+Af4B/f3+Af4B/f3+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af4B/f3+Af4B/f3+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af4B/f3+Af39/gH+Af39/gH+Af39/gH+Af39/gH9/f3+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af39/gH+Af0='; s.play(); } prevCount = val; }); document.title = 'Cocina | Solemia POS'; $watch('$wire.pendingCount', val => { document.title = val > 0 ? `(${val}) Cocina | Solemia POS` : 'Cocina | Solemia POS'; });" class="h-full flex flex-col">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-fire text-orange-500"></i>
                Cocina (KDS)
                <span class="text-sm font-normal text-gray-400 font-mono" x-text="new Date().toLocaleTimeString('es-EC')"></span>
            </h2>
            <p class="text-sm text-gray-500">Comandas activas en tiempo real</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs px-3 py-1.5 rounded-full font-medium {{ $pendingCount > 0 ? 'bg-orange-100 text-orange-700 animate-pulse' : 'bg-gray-100 text-gray-500' }}">
                <i class="fas fa-clock mr-1"></i>{{ $pendingCount }} pendientes
            </span>
        </div>
    </div>

    {{-- Area Filter --}}
    <div class="flex gap-1.5 mb-4 overflow-x-auto pb-1">
        @foreach ($this->areas as $key => $label)
            <button wire:click="$set('areaFilter', '{{ $key }}')"
                class="whitespace-nowrap px-3 py-1.5 rounded-lg text-xs font-medium transition-all
                {{ $areaFilter === $key ? 'bg-olive-600 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-olive-50 border border-gray-200' }}">
                <i class="fas {{ $key === '' ? 'fa-border-all' : ($key === 'parrilla' ? 'fa-fire' : ($key === 'horno' ? 'fa-temperature-high' : ($key === 'frio' ? 'fa-snowflake' : ($key === 'postres' ? 'fa-cake-candles' : ($key === 'bebidas' ? 'fa-wine-bottle' : 'fa-kitchen-set'))))) }} mr-1.5"></i>
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Orders Grid --}}
    <div class="flex-1 overflow-y-auto" wire:poll.5s>
        @if ($this->activeOrders->isEmpty())
            <div class="flex flex-col items-center justify-center h-full text-gray-400">
                <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                    <i class="fas fa-check-double text-2xl text-gray-300"></i>
                </div>
                <p class="text-sm font-medium text-gray-500">No hay comandas activas</p>
                <p class="text-xs text-gray-400 mt-1">Las nuevas órdenes aparecerán aquí automáticamente</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
                @foreach ($this->activeOrders as $order)
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden
                        {{ $order->items->contains(fn($i) => $i->kitchen_status === 'pending' && $i->created_at->diffInMinutes(now()) > 10) ? 'ring-2 ring-red-300 animate-pulse' : '' }}
                        {{ $order->items->contains(fn($i) => $i->kitchen_status === 'pending' && $i->created_at->diffInMinutes(now()) > 5) ? 'ring-2 ring-yellow-300' : '' }}">
                        {{-- Card Header --}}
                        <div class="px-4 py-3 bg-gradient-to-r from-olive-900 to-olive-800 text-white flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center font-bold text-lg">
                                    {{ $order->table?->number ?? 'LL' }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold">{{ $order->table ? 'Mesa ' . $order->table->number : ($order->customer_name ?? 'Para llevar') }}</p>
                                    <p class="text-[10px] text-olive-300">{{ $order->user->name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-mono text-olive-300">
                                    <i class="fas fa-clock mr-0.5"></i>
                                    {{ $order->created_at->diffForHumans(parts: 1) }}
                                </p>
                                <p class="text-[10px] text-olive-400">{{ $order->created_at->format('H:i') }}</p>
                            </div>
                        </div>

                        {{-- Items --}}
                        <div class="p-3 space-y-1.5">
                            @foreach ($order->items as $item)
                                <div class="flex items-center gap-2 p-2 rounded-lg transition-colors
                                    {{ $item->kitchen_status === 'pending' ? 'bg-gray-50' : '' }}
                                    {{ $item->kitchen_status === 'preparing' ? 'bg-yellow-50 border border-yellow-200' : '' }}
                                    {{ $item->kitchen_status === 'ready' ? 'bg-emerald-50 border border-emerald-200' : '' }}">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-xs px-1.5 py-0.5 rounded font-medium
                                                {{ $item->kitchen_area === 'parrilla' ? 'bg-red-100 text-red-700' : '' }}
                                                {{ $item->kitchen_area === 'cocina' ? 'bg-blue-100 text-blue-700' : '' }}
                                                {{ $item->kitchen_area === 'horno' ? 'bg-orange-100 text-orange-700' : '' }}
                                                {{ $item->kitchen_area === 'frio' ? 'bg-cyan-100 text-cyan-700' : '' }}
                                                {{ $item->kitchen_area === 'postres' ? 'bg-pink-100 text-pink-700' : '' }}
                                                {{ $item->kitchen_area === 'bebidas' ? 'bg-purple-100 text-purple-700' : '' }}">
                                                {{ ucfirst($item->kitchen_area) }}
                                            </span>
                                            <span class="text-sm font-semibold text-gray-800">{{ $item->quantity }}x</span>
                                            <span class="text-sm text-gray-800 truncate">{{ $item->product?->name ?? '—' }}</span>
                                        </div>
                                        @if($item->notes)
                                            <p class="text-xs text-gray-400 ml-1 mt-0.5 italic">Nota: {{ $item->notes }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-1 shrink-0">
                                        @if($item->kitchen_status === 'pending')
                                            <button wire:click="markPreparing({{ $item->id }})"
                                                class="px-2 py-1 text-[10px] font-medium rounded-lg bg-yellow-100 text-yellow-700 hover:bg-yellow-200 transition-colors whitespace-nowrap">
                                                Preparar
                                            </button>
                                        @elseif($item->kitchen_status === 'preparing')
                                            <button wire:click="markReady({{ $item->id }})"
                                                class="px-2 py-1 text-[10px] font-medium rounded-lg bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition-colors whitespace-nowrap">
                                                Listo
                                            </button>
                                        @elseif($item->kitchen_status === 'ready')
                                            <span class="text-xs text-emerald-600">
                                                <i class="fas fa-check-circle"></i>
                                            </span>
                                        @endif
                                        @if($item->sent_at)
                                            <span class="text-[10px] text-gray-400 font-mono">{{ $item->sent_at->format('H:i') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Actions --}}
                        <div class="px-3 py-2 border-t border-gray-100 flex items-center justify-between bg-gray-50/50">
                            @if($order->notes)
                                <p class="text-xs text-gray-500 italic truncate mr-2"><i class="fas fa-comment mr-1"></i>{{ $order->notes }}</p>
                            @else
                                <span></span>
                            @endif
                            <div class="flex gap-1">
                                @if($order->items->contains(fn($i) => $i->kitchen_status === 'preparing'))
                                    <button wire:click="markAllReady({{ $order->id }})"
                                        class="px-2.5 py-1 text-[10px] font-medium rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors">
                                        Todos listos
                                    </button>
                                @endif
                                @if($order->items->every(fn($i) => $i->kitchen_status === 'ready'))
                                    <button wire:click="completeOrder({{ $order->id }})"
                                        class="px-2.5 py-1 text-[10px] font-medium rounded-lg bg-olive-100 text-olive-700 hover:bg-olive-200 transition-colors">
                                        Completar
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

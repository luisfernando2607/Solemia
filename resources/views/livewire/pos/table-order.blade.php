<div class="h-full flex flex-col">
    {{-- Header --}}
    <div class="flex items-center justify-between gap-3 mb-4">
        <div class="flex items-center gap-3">
            <button wire:click="backToTables" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-arrow-left text-gray-500"></i>
            </button>
            <div>
                <h2 class="text-lg md:text-xl font-bold text-gray-900">
                    Mesa {{ $table->number }}
                    <span class="text-sm font-normal text-gray-400 ml-2">
                        <i class="fas fa-users mr-1"></i>{{ $table->capacity }}
                    </span>
                </h2>
                <p class="text-xs text-gray-500">
                    <i class="fas fa-layer-group mr-1"></i>{{ $table->zone->name }}
                    @if($order->created_at->isToday())
                        <span class="ml-2 text-olive-600">· {{ $order->created_at->format('H:i') }}</span>
                    @endif
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs px-2.5 py-1 rounded-full font-medium
                {{ $order->status === 'open' ? 'bg-yellow-100 text-yellow-700' : '' }}
                {{ $order->status === 'sent' ? 'bg-blue-100 text-blue-700' : '' }}
                {{ $order->status === 'partial' ? 'bg-purple-100 text-purple-700' : '' }}
                {{ $order->status === 'complete' ? 'bg-gray-100 text-gray-500' : '' }}
                {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                {{ $order->status === 'open' ? 'Abierta' : '' }}
                {{ $order->status === 'sent' ? 'En cocina' : '' }}
                {{ $order->status === 'partial' ? 'Parcial' : '' }}
                {{ $order->status === 'complete' ? 'Completada' : '' }}
                {{ $order->status === 'cancelled' ? 'Cancelada' : '' }}
            </span>
            <button wire:click="saveDraft" class="btn-secondary text-xs">Guardar</button>
        </div>
    </div>

    <div class="flex-1 flex flex-col lg:flex-row gap-4 min-h-0">
        {{-- Products --}}
        <div class="flex-1 flex flex-col min-h-0">
            {{-- Category Tabs --}}
            <div class="flex gap-1.5 mb-3 overflow-x-auto pb-1">
                @foreach ($this->categories as $category)
                    <button wire:click="selectCategory({{ $category->id }})"
                        class="whitespace-nowrap px-3 py-1.5 rounded-lg text-xs font-medium transition-all
                        {{ $selectedCategory === $category->id ? 'bg-olive-600 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-olive-50 border border-gray-200' }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            {{-- Product Grid --}}
            <div class="flex-1 overflow-y-auto">
                @if ($this->products->isEmpty())
                    <div class="flex flex-col items-center justify-center h-full text-gray-400">
                        <i class="fas fa-utensils text-3xl mb-2"></i>
                        <p class="text-sm">No hay productos en esta categoría</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                        @foreach ($this->products as $product)
                        <!-- IMAGEN POS -->
                            <!-- <button wire:click="addItem({{ $product->id }})" type="button"
                                class="flex flex-col rounded-xl border border-gray-200 bg-white hover:border-olive-300 hover:shadow-sm hover:bg-olive-50/50 transition-all text-center group overflow-hidden h-full">
                                <div class="h-36 bg-gradient-to-br from-olive-50 to-cream flex items-center justify-center overflow-hidden"> -->
                            <button wire:click="addItem({{ $product->id }})" type="button"
                                class="flex flex-col rounded-xl border border-gray-200 bg-white hover:border-olive-300 hover:shadow-sm hover:bg-olive-50/50 transition-all text-center group overflow-hidden h-full">
                                <div class="h-36 bg-gradient-to-br from-olive-50 to-cream flex items-center justify-center overflow-hidden">
                                    @if($product->image_path)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($product->image_path) }}"
                                            alt="{{ $product->name }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-utensil-spoon text-3xl text-olive-300"></i>
                                    @endif
                                </div>
                                <div class="p-2">
                                    <span class="text-xs font-medium text-gray-800 leading-tight line-clamp-2">{{ $product->name }}</span>
                                    <span class="text-xs font-bold text-olive-700 mt-0.5 block">${{ number_format($product->base_price, 2) }}</span>
                                </div>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Order Cart --}}
        <div class="lg:w-80 flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="p-3 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-900">
                    <i class="fas fa-shopping-cart mr-1.5 text-olive-600"></i>
                    Pedido
                    <span class="text-xs font-normal text-gray-400 ml-1">({{ $this->orderItems->count() }} items)</span>
                </h3>
            </div>

            <div class="flex-1 overflow-y-auto p-3 space-y-2 min-h-0">
                @if ($this->orderItems->isEmpty())
                    <div class="flex flex-col items-center justify-center h-full text-gray-300">
                        <i class="fas fa-cart-plus text-3xl mb-2"></i>
                        <p class="text-xs">Selecciona productos</p>
                    </div>
                @else
                    @foreach ($this->orderItems as $item)
                        <div class="flex items-center gap-2 p-2 rounded-lg group
                            {{ $item->kitchen_status === 'pending' ? 'bg-gray-50' : 'bg-blue-50/50' }}">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <p class="text-sm font-medium text-gray-800 truncate">{{ $item->product?->name ?? '—' }}</p>
                                    @if($item->kitchen_status !== 'pending')
                                        <span class="text-[10px] px-1.5 py-0.5 rounded
                                            {{ $item->kitchen_status === 'preparing' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                            {{ $item->kitchen_status === 'ready' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                            {{ $item->kitchen_status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                            {{ $item->kitchen_status === 'preparing' ? 'Prep.' : '' }}
                                            {{ $item->kitchen_status === 'ready' ? 'Listo' : '' }}
                                            {{ $item->kitchen_status === 'cancelled' ? 'Canc.' : '' }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400">${{ number_format($item->unit_price, 2) }} c/u</p>
                            </div>
                            <div class="flex items-center gap-1">
                                <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                    class="w-6 h-6 rounded bg-white border border-gray-200 flex items-center justify-center text-xs text-gray-600 hover:bg-gray-100"
                                    @disabled($item->kitchen_status !== 'pending')>
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="w-7 text-center text-sm font-bold text-gray-800">{{ $item->quantity }}</span>
                                <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                    class="w-6 h-6 rounded bg-white border border-gray-200 flex items-center justify-center text-xs text-gray-600 hover:bg-gray-100"
                                    @disabled($item->kitchen_status !== 'pending')>
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <span class="text-sm font-bold text-gray-800 w-16 text-right">${{ number_format($item->subtotal, 2) }}</span>
                            <button wire:click="removeItem({{ $item->id }})"
                                class="p-1 text-gray-300 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity"
                                @disabled($item->kitchen_status !== 'pending')>
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- Notes --}}
            <div class="px-3 py-2 border-t border-gray-100">
                <textarea wire:model="orderNotes" placeholder="Notas para cocina..."
                    class="w-full text-xs rounded-lg border-gray-200 resize-none h-16 focus:border-olive-400 focus:ring-olive-400"></textarea>
            </div>

            {{-- Totals --}}
            <div class="px-3 py-2 border-t border-gray-100 space-y-1 text-sm">
                <div class="flex justify-between text-gray-500">
                    <span>Subtotal</span>
                    <span>${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>IVA {{ \App\Models\RestaurantSetting::current()->tax_rate }}%</span>
                    <span>${{ number_format($order->tax, 2) }}</span>
                </div>
                <div class="flex justify-between font-bold text-gray-900 border-t border-gray-200 pt-1 mt-1">
                    <span>Total</span>
                    <span>${{ number_format($order->total, 2) }}</span>
                </div>
            </div>

            {{-- Actions --}}
            <div class="p-3 border-t border-gray-100 space-y-2">
                @if(in_array($order->status, ['open', 'sent', 'partial']))
                    <button wire:click="sendToKitchen"
                        class="w-full py-2.5 bg-olive-600 text-white rounded-xl font-semibold text-sm hover:bg-olive-700 transition-colors shadow-lg shadow-olive-600/20 flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i>
                        Enviar a cocina
                    </button>
                @elseif(in_array($order->status, ['complete', 'cancelled']))
                    <div class="w-full py-2.5 bg-gray-100 text-gray-500 rounded-xl font-semibold text-sm text-center flex items-center justify-center gap-2">
                        <i class="fas fa-lock"></i>
                        {{ $order->status === 'complete' ? 'Cuenta cerrada' : 'Orden cancelada' }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

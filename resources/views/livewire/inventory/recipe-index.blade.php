<div class="flex flex-col h-full">
    {{-- Sub-navigation --}}
    <div class="flex gap-1 mb-4 overflow-x-auto">
        <a href="{{ route('inventory.ingredients') }}"
            class="px-4 py-2 text-sm rounded-lg font-medium transition-all whitespace-nowrap bg-white text-gray-600 hover:bg-gray-100 border border-gray-200">
            <i class="fas fa-carrot mr-1.5"></i>Ingredientes
        </a>
        <a href="{{ route('inventory.recipes') }}"
            class="px-4 py-2 text-sm rounded-lg font-medium transition-all whitespace-nowrap bg-olive-600 text-white">
            <i class="fas fa-book-open mr-1.5"></i>Recetas
        </a>
        <a href="{{ route('inventory.suppliers') }}"
            class="px-4 py-2 text-sm rounded-lg font-medium transition-all whitespace-nowrap bg-white text-gray-600 hover:bg-gray-100 border border-gray-200">
            <i class="fas fa-truck mr-1.5"></i>Proveedores
        </a>
        <a href="{{ route('inventory.purchases') }}"
            class="px-4 py-2 text-sm rounded-lg font-medium transition-all whitespace-nowrap bg-white text-gray-600 hover:bg-gray-100 border border-gray-200">
            <i class="fas fa-shopping-cart mr-1.5"></i>Compras
        </a>
    </div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-olive-900">Recetas</h2>
            <p class="text-sm text-gray-500">Vincula productos del menú con ingredientes y sus cantidades</p>
        </div>
    </div>

    <div class="flex-1 flex flex-col lg:flex-row gap-4 min-h-0">
        {{-- Product List --}}
        <div class="lg:w-72 shrink-0 flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="p-3 border-b border-gray-100">
                <x-text-input wire:model.live="search" class="input-field w-full text-sm" placeholder="Buscar producto..." />
            </div>
            <div class="flex-1 overflow-y-auto p-2 space-y-1">
                @forelse($this->products as $p)
                    <button wire:click="selectProduct({{ $p->id }})"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-left transition-all text-sm
                        {{ $selectedProductId === $p->id ? 'bg-olive-100 border border-olive-200' : 'hover:bg-gray-50 border border-transparent' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold {{ $p->category ? 'bg-' . $p->category->color . '-100 text-' . $p->category->color . '-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ substr($p->name, 0, 2) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $p->name }}</p>
                            <p class="text-xs text-gray-400">{{ $p->category?->name ?? 'Sin categoría' }}</p>
                        </div>
                        @php $recipeCount = $p->recipes()->count(); @endphp
                        @if($recipeCount > 0)
                            <span class="text-xs bg-olive-100 text-olive-700 px-2 py-0.5 rounded-full">{{ $recipeCount }}</span>
                        @endif
                    </button>
                @empty
                    <div class="text-center py-8 text-gray-400 text-xs">
                        <i class="fas fa-search text-2xl mb-2 block"></i>
                        No se encontraron productos
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Recipe Panel --}}
        <div class="flex-1 flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm min-h-0">
            @if(!$selectedProductId)
                <div class="flex-1 flex flex-col items-center justify-center text-gray-400 p-6">
                    <i class="fas fa-utensils text-4xl mb-3"></i>
                    <p class="text-sm">Selecciona un producto del menú para gestionar su receta</p>
                </div>
            @elseif(!$selectedProduct)
                <div class="flex-1 flex flex-col items-center justify-center text-gray-400 p-6">
                    <p class="text-sm">Producto no encontrado</p>
                </div>
            @else
                {{-- Product Header --}}
                <div class="p-3 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">{{ $selectedProduct->name }}</h3>
                        <p class="text-xs text-gray-400">Precio venta: ${{ number_format($selectedProduct->base_price, 2) }}</p>
                    </div>
                    @php
                        $totalCost = $selectedProduct->recipes->sum('cost');
                        $margin = $selectedProduct->base_price > 0 ? round(($selectedProduct->base_price - $totalCost) / $selectedProduct->base_price * 100, 1) : 0;
                    @endphp
                    <div class="text-right">
                        <p class="text-xs text-gray-400">Costo receta: <span class="font-medium text-gray-700">${{ number_format($totalCost, 2) }}</span></p>
                        <p class="text-xs {{ $margin >= 30 ? 'text-emerald-600' : ($margin >= 10 ? 'text-amber-600' : 'text-red-600') }}">
                            Margen: {{ $margin }}%
                        </p>
                    </div>
                </div>

                {{-- Ingredients List --}}
                <div class="flex-1 overflow-y-auto p-3">
                    @if($selectedProduct->recipes->isEmpty())
                        <div class="text-center py-8 text-gray-400 text-xs">
                            <i class="fas fa-carrot text-2xl mb-2 block"></i>
                            Este producto no tiene ingredientes asignados
                        </div>
                    @else
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-xs text-gray-400 uppercase border-b border-gray-100">
                                    <th class="text-left py-2">Ingrediente</th>
                                    <th class="text-left py-2">Unidad</th>
                                    <th class="text-right py-2">Cantidad</th>
                                    <th class="text-right py-2">Costo</th>
                                    <th class="text-center py-2">Deducir</th>
                                    <th class="text-right py-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($selectedProduct->recipes as $r)
                                    <tr class="border-b border-gray-50" wire:key="recipe-{{ $r->id }}">
                                        <td class="py-2 font-medium text-gray-800">{{ $r->ingredient->name }}</td>
                                        <td class="py-2 text-gray-500">{{ $r->ingredient->unit }}</td>
                                        <td class="py-2 text-right text-gray-800">{{ number_format($r->quantity, 3) }}</td>
                                        <td class="py-2 text-right text-gray-600">${{ number_format($r->cost, 4) }}</td>
                                        <td class="py-2 text-center">
                                            <button wire:click="toggleAutoDeduct({{ $r->id }})" class="text-xs {{ $r->auto_deduct ? 'text-emerald-600' : 'text-gray-300' }}">
                                                <i class="fas {{ $r->auto_deduct ? 'fa-toggle-on' : 'fa-toggle-off' }} text-lg"></i>
                                            </button>
                                        </td>
                                        <td class="py-2 text-right">
                                            <button wire:click="removeIngredient({{ $r->id }})"
                                                onclick="return confirm('¿Quitar este ingrediente?')"
                                                class="p-1 text-gray-400 hover:text-red-500">
                                                <i class="fas fa-times text-[10px]"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="font-medium">
                                    <td colspan="3" class="pt-3 text-right text-gray-600">Costo total receta:</td>
                                    <td class="pt-3 text-right text-gray-900">${{ number_format($totalCost, 2) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    @endif
                </div>

                {{-- Add Ingredient Form --}}
                <div class="p-3 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-900 mb-2 uppercase tracking-wider">Agregar ingrediente</p>
                    <div class="flex flex-wrap items-end gap-2">
                        <div class="flex-1 min-w-[160px]">
                            <select wire:model="recipeIngredientId" class="input-field w-full text-xs rounded-lg border-gray-200">
                                <option value="">Seleccionar ingrediente...</option>
                                @foreach($this->availableIngredients as $ing)
                                    <option value="{{ $ing->id }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-24">
                            <x-text-input wire:model="recipeQuantity" type="number" step="0.001" class="input-field w-full text-xs" placeholder="Cant." />
                        </div>
                        <label class="flex items-center gap-1 text-xs text-gray-500">
                            <input type="checkbox" wire:model="recipeAutoDeduct" class="rounded border-gray-300 text-olive-600" checked />
                            Deducir
                        </label>
                        <button wire:click="addIngredient" class="btn-primary text-xs">
                            <i class="fas fa-plus"></i> Agregar
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('recipeIngredientId')" class="mt-1" />
                </div>
            @endif
        </div>
    </div>
</div>

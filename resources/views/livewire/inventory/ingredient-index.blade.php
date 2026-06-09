<div class="flex flex-col h-full">
    {{-- Sub-navigation --}}
    <div class="flex gap-1 mb-4 overflow-x-auto">
        <a href="{{ route('inventory.ingredients') }}"
            class="px-4 py-2 text-sm rounded-lg font-medium transition-all whitespace-nowrap bg-olive-600 text-white">
            <i class="fas fa-carrot mr-1.5"></i>Ingredientes
        </a>
        <a href="{{ route('inventory.recipes') }}"
            class="px-4 py-2 text-sm rounded-lg font-medium transition-all whitespace-nowrap bg-white text-gray-600 hover:bg-gray-100 border border-gray-200">
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
            <h2 class="text-xl md:text-2xl font-bold text-olive-900">Ingredientes</h2>
            <p class="text-sm text-gray-500">Gestiona los insumos de cocina, stock y movimientos</p>
        </div>
        <div class="flex items-center gap-2">
            @if($this->lowStockCount > 0)
                <span class="text-xs px-3 py-1.5 rounded-full bg-red-100 text-red-700 font-medium">
                    <i class="fas fa-exclamation-triangle mr-1"></i>{{ $this->lowStockCount }} stock bajo
                </span>
            @endif
            <button wire:click="createIngredient" class="btn-primary text-xs">
                <i class="fas fa-plus"></i> Nuevo ingrediente
            </button>
            <button wire:click="createCategory" class="btn-secondary text-xs">
                <i class="fas fa-tag"></i> Nueva categoría
            </button>
        </div>
    </div>

    <div class="flex-1 flex flex-col lg:flex-row gap-4 min-h-0">
        {{-- Categories Sidebar --}}
        <div class="lg:w-56 shrink-0 flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="p-3 border-b border-gray-100">
                <h3 class="text-xs font-semibold text-gray-900 uppercase tracking-wider">Categorías</h3>
            </div>
            <div class="flex-1 overflow-y-auto p-2 space-y-1">
                @foreach($this->categories as $cat)
                    <button wire:click="selectCategory({{ $cat->id }})"
                        class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-left text-sm transition-all
                        {{ $selectedCategoryId === $cat->id ? 'bg-olive-100 text-olive-800 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-layer-group text-xs {{ $selectedCategoryId === $cat->id ? 'text-olive-600' : 'text-gray-400' }}"></i>
                        <span class="flex-1 truncate">{{ $cat->name }}</span>
                        <span class="text-xs text-gray-400">{{ $cat->ingredients_count }}</span>
                        <button wire:click.stop="editCategory({{ $cat->id }})" class="text-gray-300 hover:text-olive-600">
                            <i class="fas fa-pen text-[10px]"></i>
                        </button>
                        <button wire:click.stop="deleteCategory({{ $cat->id }})"
                            onclick="return confirm('¿Eliminar esta categoría?')" class="text-gray-300 hover:text-red-500">
                            <i class="fas fa-times text-[10px]"></i>
                        </button>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Ingredients Grid --}}
        <div class="flex-1 flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm min-h-0">
            @if($this->ingredients->isEmpty())
                <div class="flex-1 flex flex-col items-center justify-center text-gray-400 p-6">
                    <i class="fas fa-carrot text-4xl mb-3"></i>
                    <p class="text-sm">No hay ingredientes en esta categoría</p>
                </div>
            @else
                <div class="p-3 border-b border-gray-100">
                    <p class="text-xs text-gray-400">{{ $this->ingredients->count() }} ingredientes</p>
                </div>
                <div class="flex-1 overflow-y-auto p-3">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-xs text-gray-400 uppercase border-b border-gray-100">
                                <th class="text-left py-2 pr-2">Nombre</th>
                                <th class="text-left py-2 px-2">Unidad</th>
                                <th class="text-right py-2 px-2">Stock</th>
                                <th class="text-right py-2 px-2">Min</th>
                                <th class="text-right py-2 px-2">Costo</th>
                                <th class="text-right py-2 px-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($this->ingredients as $ing)
                                <tr class="border-b border-gray-50 hover:bg-gray-50/50 {{ !$ing->is_active ? 'opacity-50' : '' }}"
                                    wire:key="ing-{{ $ing->id }}">
                                    <td class="py-2 pr-2">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full {{ $ing->isLowStock() ? 'bg-red-500' : 'bg-emerald-400' }}"></span>
                                            <span class="font-medium text-gray-800">{{ $ing->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-2 px-2 text-gray-500">{{ $ing->unit }}</td>
                                    <td class="py-2 px-2 text-right font-medium {{ $ing->isLowStock() ? 'text-red-600' : 'text-gray-800' }}">
                                        {{ number_format($ing->stock_current, 2) }}
                                    </td>
                                    <td class="py-2 px-2 text-right text-gray-400">{{ number_format($ing->stock_minimum, 2) }}</td>
                                    <td class="py-2 px-2 text-right text-gray-600">${{ number_format($ing->unit_cost, 4) }}</td>
                                    <td class="py-2 px-2 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <button wire:click="editIngredient({{ $ing->id }})" class="p-1 text-gray-400 hover:text-olive-600" title="Editar">
                                                <i class="fas fa-pen text-[10px]"></i>
                                            </button>
                                            <button wire:click="openAdjustment({{ $ing->id }})" class="p-1 text-gray-400 hover:text-amber-600" title="Ajustar stock">
                                                <i class="fas fa-balance-scale text-[10px]"></i>
                                            </button>
                                            <button wire:click="viewMovements({{ $ing->id }})" class="p-1 text-gray-400 hover:text-blue-600" title="Movimientos">
                                                <i class="fas fa-history text-[10px]"></i>
                                            </button>
                                            <button wire:click="toggleActive({{ $ing->id }})" class="p-1 {{ $ing->is_active ? 'text-emerald-400 hover:text-gray-400' : 'text-gray-300 hover:text-emerald-500' }}" title="Activar/Desactivar">
                                                <i class="fas {{ $ing->is_active ? 'fa-check-circle' : 'fa-circle' }} text-[10px]"></i>
                                            </button>
                                            <button wire:click="deleteIngredient({{ $ing->id }})"
                                                onclick="return confirm('¿Eliminar {{ $ing->name }}?')"
                                                class="p-1 text-gray-400 hover:text-red-500" title="Eliminar">
                                                <i class="fas fa-trash text-[10px]"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Category Form Modal --}}
    @if($showCategoryForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" wire:click.self="$set('showCategoryForm', false)">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm mx-4" wire:click.self.stop>
                <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $editingCategory ? 'Editar' : 'Nueva' }} categoría</h3>
                <div class="space-y-3">
                    <div>
                        <x-input-label value="Nombre" />
                        <x-text-input wire:model="categoryName" class="input-field w-full text-sm" placeholder="Ej: Carnes, Lácteos..." />
                        <x-input-error :messages="$errors->get('categoryName')" />
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button wire:click="$set('showCategoryForm', false)" class="btn-secondary text-sm">Cancelar</button>
                        <button wire:click="saveCategory" class="btn-primary text-sm">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Ingredient Form Modal --}}
    @if($showIngredientForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" wire:click.self="$set('showIngredientForm', false)">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-lg mx-4" wire:click.self.stop>
                <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $editingIngredient ? 'Editar' : 'Nuevo' }} ingrediente</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div class="col-span-2">
                        <x-input-label value="Nombre" />
                        <x-text-input wire:model="ingredientName" class="input-field w-full text-sm" placeholder="Nombre del insumo" />
                        <x-input-error :messages="$errors->get('ingredientName')" />
                    </div>
                    <div>
                        <x-input-label value="Unidad de medida" />
                        <x-text-input wire:model="ingredientUnit" class="input-field w-full text-sm" placeholder="kg, l, unid..." />
                        <x-input-error :messages="$errors->get('ingredientUnit')" />
                    </div>
                    <div>
                        <x-input-label value="Costo unitario" />
                        <x-text-input wire:model="ingredientCost" type="number" step="0.0001" class="input-field w-full text-sm" />
                        <x-input-error :messages="$errors->get('ingredientCost')" />
                    </div>
                    <div>
                        <x-input-label value="Stock actual" />
                        <x-text-input wire:model="ingredientStock" type="number" step="0.001" class="input-field w-full text-sm" />
                        <x-input-error :messages="$errors->get('ingredientStock')" />
                    </div>
                    <div>
                        <x-input-label value="Stock mínimo" />
                        <x-text-input wire:model="ingredientMinStock" type="number" step="0.001" class="input-field w-full text-sm" />
                        <x-input-error :messages="$errors->get('ingredientMinStock')" />
                    </div>
                    <div class="col-span-2 flex items-center gap-2">
                        <label class="flex items-center gap-2 text-sm text-gray-600">
                            <input type="checkbox" wire:model="ingredientActive" class="rounded border-gray-300 text-olive-600" />
                            Activo
                        </label>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button wire:click="$set('showIngredientForm', false)" class="btn-secondary text-sm">Cancelar</button>
                    <button wire:click="saveIngredient" class="btn-primary text-sm">Guardar</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Adjustment Modal --}}
    @if($showAdjustment)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" wire:click.self="$set('showAdjustment', false)">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md mx-4" wire:click.self.stop>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Ajustar stock</h3>
                <div class="space-y-3">
                    <div>
                        <x-input-label value="Tipo de movimiento" />
                        <select wire:model="adjustType" class="input-field w-full text-sm rounded-lg border-gray-200">
                            <option value="manual_in">Entrada manual</option>
                            <option value="manual_out">Salida manual</option>
                            <option value="adjustment">Ajuste</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label value="Cantidad" />
                        <x-text-input wire:model="adjustQuantity" type="number" step="0.001" class="input-field w-full text-sm" />
                        <x-input-error :messages="$errors->get('adjustQuantity')" />
                    </div>
                    <div>
                        <x-input-label value="Motivo" />
                        <textarea wire:model="adjustReason" class="input-field w-full text-sm rounded-lg border-gray-200" rows="2" placeholder="Ej: Inventario físico, merma..."></textarea>
                        <x-input-error :messages="$errors->get('adjustReason')" />
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button wire:click="$set('showAdjustment', false)" class="btn-secondary text-sm">Cancelar</button>
                        <button wire:click="saveAdjustment" class="btn-primary text-sm">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Movements Modal --}}
    @if($showMovements)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" wire:click.self="$set('showMovements', false)">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-2xl mx-4 max-h-[80vh] flex flex-col" wire:click.self.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Movimientos</h3>
                    <button wire:click="$set('showMovements', false)" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto">
                    @if($this->movements->isEmpty())
                        <p class="text-sm text-gray-400 text-center py-8">Sin movimientos registrados</p>
                    @else
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-xs text-gray-400 uppercase border-b border-gray-100">
                                    <th class="text-left py-2">Fecha</th>
                                    <th class="text-left py-2">Tipo</th>
                                    <th class="text-right py-2">Cantidad</th>
                                    <th class="text-right py-2">Stock anterior</th>
                                    <th class="text-right py-2">Stock nuevo</th>
                                    <th class="text-right py-2">Usuario</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($this->movements as $m)
                                    <tr class="border-b border-gray-50">
                                        <td class="py-2 text-gray-500">{{ $m->created_at->format('d/m H:i') }}</td>
                                        <td class="py-2">
                                            <span class="text-xs px-2 py-0.5 rounded-full
                                                {{ str_contains($m->type, 'in') || $m->type === 'purchase' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                                {{ str_replace('_', ' ', $m->type) }}
                                            </span>
                                        </td>
                                        <td class="py-2 text-right font-medium {{ $m->quantity > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                            {{ $m->quantity > 0 ? '+' : '' }}{{ number_format($m->quantity, 2) }}
                                        </td>
                                        <td class="py-2 text-right text-gray-500">{{ number_format($m->stock_before, 2) }}</td>
                                        <td class="py-2 text-right text-gray-800 font-medium">{{ number_format($m->stock_after, 2) }}</td>
                                        <td class="py-2 text-right text-gray-400">{{ $m->user?->name ?? '—' }}</td>
                                    </tr>
                                    @if($m->reason)
                                        <tr class="border-b border-gray-50">
                                            <td colspan="6" class="py-1 text-xs text-gray-400 italic pl-4">{{ $m->reason }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

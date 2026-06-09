<div class="flex flex-col h-full">
    {{-- Sub-navigation --}}
    <div class="flex gap-1 mb-4 overflow-x-auto">
        <a href="{{ route('inventory.ingredients') }}"
            class="px-4 py-2 text-sm rounded-lg font-medium transition-all whitespace-nowrap bg-white text-gray-600 hover:bg-gray-100 border border-gray-200">
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
            class="px-4 py-2 text-sm rounded-lg font-medium transition-all whitespace-nowrap bg-olive-600 text-white">
            <i class="fas fa-shopping-cart mr-1.5"></i>Compras
        </a>
    </div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-olive-900">Órdenes de compra</h2>
            <p class="text-sm text-gray-500">Registra y gestiona compras a proveedores</p>
        </div>
        <div class="flex items-center gap-2">
            <select wire:model.live="selectedStatus" class="input-field text-xs rounded-lg border-gray-200 w-32">
                <option value="">Todas</option>
                <option value="draft">Borrador</option>
                <option value="sent">Enviadas</option>
                <option value="received">Recibidas</option>
                <option value="cancelled">Canceladas</option>
            </select>
            <button wire:click="create" class="btn-primary text-xs">
                <i class="fas fa-plus"></i> Nueva orden
            </button>
        </div>
    </div>

    {{-- Orders List --}}
    <div class="flex-1 overflow-y-auto">
        @if($this->orders->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                <i class="fas fa-shopping-cart text-4xl mb-3"></i>
                <p class="text-sm">No hay órdenes de compra</p>
            </div>
        @else
            <div class="space-y-2">
                @foreach($this->orders as $o)
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-semibold text-gray-900">OC #{{ $o->id }}</span>
                                    <span class="text-xs px-2 py-0.5 rounded-full
                                        {{ $o->status === 'draft' ? 'bg-gray-100 text-gray-600' : '' }}
                                        {{ $o->status === 'sent' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $o->status === 'received' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                        {{ $o->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                        {{ ucfirst($o->status) }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500">
                                    {{ $o->supplier?->name ?? '—' }} · {{ $o->user?->name ?? '—' }} · {{ $o->created_at->format('d/m/Y') }}
                                </p>
                                @if($o->notes)
                                    <p class="text-xs text-gray-400 italic mt-1">{{ $o->notes }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-900">${{ number_format($o->total, 2) }}</p>
                                <p class="text-xs text-gray-400">{{ $o->items->count() }} ítems</p>
                            </div>
                        </div>
                        <div class="flex gap-1 mt-2 pt-2 border-t border-gray-100">
                            <button wire:click="viewOrder({{ $o->id }})" class="btn-secondary text-[10px] px-2 py-1">
                                <i class="fas fa-eye"></i> Ver
                            </button>
                            @if($o->status === 'draft')
                                <button wire:click="markSent({{ $o->id }})" class="btn-primary text-[10px] px-2 py-1"
                                    onclick="return confirm('¿Marcar como enviada?')">
                                    <i class="fas fa-paper-plane"></i> Enviar
                                </button>
                                <button wire:click="markCancelled({{ $o->id }})" class="btn-secondary text-[10px] px-2 py-1 text-red-600 border-red-200 hover:bg-red-50"
                                    onclick="return confirm('¿Cancelar esta orden?')">
                                    <i class="fas fa-ban"></i> Cancelar
                                </button>
                            @endif
                            @if($o->status === 'sent')
                                <button wire:click="markReceived({{ $o->id }})" class="btn-primary text-[10px] px-2 py-1 bg-emerald-600 hover:bg-emerald-700"
                                    onclick="return confirm('¿Recibir mercadería? El stock se actualizará automáticamente.')">
                                    <i class="fas fa-check"></i> Recibir
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Create/Edit Form Modal --}}
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" wire:click.self="$set('showForm', false)">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col" wire:click.self.stop>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Nueva orden de compra</h3>

                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <x-input-label value="Proveedor *" />
                        <select wire:model="supplierId" class="input-field w-full text-sm rounded-lg border-gray-200">
                            <option value="">Seleccionar...</option>
                            @foreach($this->suppliers as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('supplierId')" />
                    </div>
                    <div>
                        <x-input-label value="Fecha de orden" />
                        <x-text-input wire:model="orderedAt" type="date" class="input-field w-full text-sm" />
                    </div>
                    <div class="col-span-2">
                        <x-input-label value="Notas" />
                        <textarea wire:model="notes" class="input-field w-full text-sm rounded-lg border-gray-200" rows="2"></textarea>
                    </div>
                </div>

                {{-- Line Items --}}
                <p class="text-xs font-semibold text-gray-900 mb-2 uppercase tracking-wider">Ítems</p>

                <div class="flex-1 overflow-y-auto mb-3">
                    @if(empty($lines))
                        <p class="text-xs text-gray-400 text-center py-4">Agrega ingredientes a la orden</p>
                    @else
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-xs text-gray-400 uppercase border-b border-gray-100">
                                    <th class="text-left py-2">Ingrediente</th>
                                    <th class="text-right py-2">Cantidad</th>
                                    <th class="text-right py-2">Costo unit.</th>
                                    <th class="text-right py-2">Subtotal</th>
                                    <th class="text-right py-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lines as $i => $line)
                                    <tr class="border-b border-gray-50">
                                        <td class="py-2 font-medium text-gray-800">{{ $line['ingredient_name'] }}</td>
                                        <td class="py-2 text-right text-gray-800">{{ number_format($line['quantity'], 2) }}</td>
                                        <td class="py-2 text-right text-gray-600">${{ number_format($line['unit_cost'], 4) }}</td>
                                        <td class="py-2 text-right text-gray-800">${{ number_format($line['subtotal'], 2) }}</td>
                                        <td class="py-2 text-right">
                                            <button wire:click="removeLine({{ $i }})" class="p-1 text-gray-400 hover:text-red-500">
                                                <i class="fas fa-times text-[10px]"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="font-bold">
                                    <td colspan="3" class="pt-2 text-right text-gray-600">Total:</td>
                                    <td class="pt-2 text-right text-gray-900">${{ number_format($this->linesTotal, 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    @endif
                </div>

                {{-- Add Line Form --}}
                <div class="flex flex-wrap items-end gap-2 pt-3 border-t border-gray-100">
                    <div class="flex-1 min-w-[160px]">
                        <select wire:model="newIngredientId" class="input-field w-full text-xs rounded-lg border-gray-200">
                            <option value="">Agregar insumo...</option>
                            @foreach($this->ingredientsList as $ing)
                                <option value="{{ $ing->id }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-20">
                        <x-text-input wire:model="newQuantity" type="number" step="0.001" class="input-field w-full text-xs" placeholder="Cant." />
                    </div>
                    <div class="w-24">
                        <x-text-input wire:model="newUnitCost" type="number" step="0.0001" class="input-field w-full text-xs" placeholder="Costo u." />
                    </div>
                    <button wire:click="addLine" class="btn-primary text-xs">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('newIngredientId')" class="mt-1" />
                <x-input-error :messages="$errors->get('lines')" class="mt-1" />

                <div class="flex justify-end gap-2 pt-4 border-t border-gray-100 mt-3">
                    <button wire:click="$set('showForm', false)" class="btn-secondary text-sm">Cancelar</button>
                    <button wire:click="save" class="btn-primary text-sm">Crear orden</button>
                </div>
            </div>
        </div>
    @endif

    {{-- View Order Modal --}}
    @if($viewOrderId && ($viewOrder = $this->viewOrder))
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" wire:click.self="$set('viewOrderId', null)">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-lg mx-4" wire:click.self.stop>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">OC #{{ $viewOrder->id }}</h3>
                        <p class="text-xs text-gray-500">{{ $viewOrder->supplier?->name }} · {{ $viewOrder->created_at->format('d/m/Y') }}</p>
                    </div>
                    <button wire:click="$set('viewOrderId', null)" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-400 uppercase border-b border-gray-100">
                            <th class="text-left py-2">Ingrediente</th>
                            <th class="text-right py-2">Cant.</th>
                            <th class="text-right py-2">Costo u.</th>
                            <th class="text-right py-2">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($viewOrder->items as $item)
                            <tr class="border-b border-gray-50">
                                <td class="py-2 font-medium text-gray-800">{{ $item->ingredient?->name ?? '—' }}</td>
                                <td class="py-2 text-right text-gray-800">{{ number_format($item->quantity, 2) }}</td>
                                <td class="py-2 text-right text-gray-600">${{ number_format($item->unit_cost, 4) }}</td>
                                <td class="py-2 text-right text-gray-800">${{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-bold">
                            <td colspan="3" class="pt-2 text-right text-gray-600">Total:</td>
                            <td class="pt-2 text-right text-gray-900">${{ number_format($viewOrder->total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
                <div class="flex justify-end gap-2 pt-4">
                    <button wire:click="$set('viewOrderId', null)" class="btn-secondary text-sm">Cerrar</button>
                </div>
            </div>
        </div>
    @endif
</div>

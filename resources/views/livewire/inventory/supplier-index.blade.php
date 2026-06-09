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
            class="px-4 py-2 text-sm rounded-lg font-medium transition-all whitespace-nowrap bg-olive-600 text-white">
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
            <h2 class="text-xl md:text-2xl font-bold text-olive-900">Proveedores</h2>
            <p class="text-sm text-gray-500">Gestiona tus proveedores de insumos</p>
        </div>
        <button wire:click="create" class="btn-primary text-xs">
            <i class="fas fa-plus"></i> Nuevo proveedor
        </button>
    </div>

    {{-- Search --}}
    <div class="mb-4">
        <x-text-input wire:model.live="search" class="input-field w-full max-w-xs text-sm" placeholder="Buscar proveedor..." />
    </div>

    {{-- Grid --}}
    <div class="flex-1 overflow-y-auto">
        @if($this->suppliers->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                <i class="fas fa-truck text-4xl mb-3"></i>
                <p class="text-sm">No hay proveedores registrados</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($this->suppliers as $s)
                    <div class="bg-white rounded-xl border border-gray-200 p-4 {{ !$s->is_active ? 'opacity-60' : '' }}">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">{{ $s->name }}</h3>
                                @if($s->ruc)
                                    <p class="text-xs text-gray-400">{{ $s->ruc }}</p>
                                @endif
                            </div>
                            <div class="flex gap-1">
                                <button wire:click="edit({{ $s->id }})" class="p-1 text-gray-400 hover:text-olive-600">
                                    <i class="fas fa-pen text-[10px]"></i>
                                </button>
                                <button wire:click="toggleActive({{ $s->id }})" class="p-1 {{ $s->is_active ? 'text-emerald-400 hover:text-gray-400' : 'text-gray-300 hover:text-emerald-500' }}">
                                    <i class="fas {{ $s->is_active ? 'fa-check-circle' : 'fa-circle' }} text-[10px]"></i>
                                </button>
                                <button wire:click="delete({{ $s->id }})"
                                    onclick="return confirm('¿Eliminar {{ $s->name }}?')"
                                    class="p-1 text-gray-400 hover:text-red-500">
                                    <i class="fas fa-trash text-[10px]"></i>
                                </button>
                            </div>
                        </div>
                        <div class="space-y-1 text-xs text-gray-500">
                            @if($s->phone)
                                <p><i class="fas fa-phone w-4 text-gray-300"></i> {{ $s->phone }}</p>
                            @endif
                            @if($s->email)
                                <p><i class="fas fa-envelope w-4 text-gray-300"></i> {{ $s->email }}</p>
                            @endif
                            @if($s->contact_person)
                                <p><i class="fas fa-user w-4 text-gray-300"></i> {{ $s->contact_person }}</p>
                            @endif
                            @if($s->payment_terms)
                                <p><i class="fas fa-file-invoice w-4 text-gray-300"></i> {{ $s->payment_terms }}</p>
                            @endif
                        </div>
                        @if($s->notes)
                            <p class="text-xs text-gray-400 italic mt-2 border-t border-gray-100 pt-2">{{ $s->notes }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Form Modal --}}
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" wire:click.self="$set('showForm', false)">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto" wire:click.self.stop>
                <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $editing ? 'Editar' : 'Nuevo' }} proveedor</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div class="col-span-2">
                        <x-input-label value="Nombre *" />
                        <x-text-input wire:model="name" class="input-field w-full text-sm" />
                        <x-input-error :messages="$errors->get('name')" />
                    </div>
                    <div>
                        <x-input-label value="RUC" />
                        <x-text-input wire:model="ruc" class="input-field w-full text-sm" maxlength="13" />
                    </div>
                    <div>
                        <x-input-label value="Teléfono" />
                        <x-text-input wire:model="phone" class="input-field w-full text-sm" />
                    </div>
                    <div>
                        <x-input-label value="Email" />
                        <x-text-input wire:model="email" type="email" class="input-field w-full text-sm" />
                    </div>
                    <div>
                        <x-input-label value="Contacto" />
                        <x-text-input wire:model="contactPerson" class="input-field w-full text-sm" />
                    </div>
                    <div class="col-span-2">
                        <x-input-label value="Condiciones de pago" />
                        <x-text-input wire:model="paymentTerms" class="input-field w-full text-sm" placeholder="Ej: 30 días, contado..." />
                    </div>
                    <div class="col-span-2">
                        <x-input-label value="Notas" />
                        <textarea wire:model="notes" class="input-field w-full text-sm rounded-lg border-gray-200" rows="2"></textarea>
                    </div>
                    <div class="col-span-2 flex items-center gap-2">
                        <label class="flex items-center gap-2 text-sm text-gray-600">
                            <input type="checkbox" wire:model="isActive" class="rounded border-gray-300 text-olive-600" />
                            Activo
                        </label>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button wire:click="$set('showForm', false)" class="btn-secondary text-sm">Cancelar</button>
                    <button wire:click="save" class="btn-primary text-sm">Guardar</button>
                </div>
            </div>
        </div>
    @endif
</div>

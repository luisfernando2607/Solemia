<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-olive-900">Clientes</h2>
            <p class="text-sm text-gray-500">Gestión de clientes frecuentes</p>
        </div>
        <button wire:click="showCreateForm" class="btn-primary flex items-center gap-2 text-sm">
            <i class="fas fa-plus"></i> Cliente
        </button>
    </div>

    {{-- Search --}}
    <div class="mb-4">
        <x-text-input wire:model.live.debounce.300ms="search" class="input-field w-full max-w-md text-sm" placeholder="Buscar por nombre, RUC, teléfono o email..." />
    </div>

    {{-- Form --}}
    @if($showForm)
        <div class="card mb-4">
            <div class="p-4 md:p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-olive-100 flex items-center justify-center">
                        <i class="fas fa-user text-olive-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $editingCustomer ? 'Editar Cliente' : 'Nuevo Cliente' }}</h3>
                    </div>
                </div>
                <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <x-input-label value="Nombre *" />
                        <x-text-input wire:model="customerName" class="input-field w-full" placeholder="Nombre completo" />
                        <x-input-error :messages="$errors->get('customerName')" />
                    </div>
                    <div>
                        <x-input-label value="RUC / Cédula" />
                        <x-text-input wire:model="customerRuc" class="input-field w-full" placeholder="1234567890001" maxlength="13" />
                        <x-input-error :messages="$errors->get('customerRuc')" />
                    </div>
                    <div>
                        <x-input-label value="Teléfono" />
                        <x-text-input wire:model="customerPhone" class="input-field w-full" placeholder="0999999999" />
                    </div>
                    <div>
                        <x-input-label value="Email" />
                        <x-text-input wire:model="customerEmail" type="email" class="input-field w-full" placeholder="cliente@email.com" />
                    </div>
                    <div class="md:col-span-2 lg:col-span-2">
                        <x-input-label value="Dirección" />
                        <x-text-input wire:model="customerAddress" class="input-field w-full" placeholder="Dirección" />
                    </div>
                    <div class="md:col-span-2 lg:col-span-3">
                        <x-input-label value="Notas" />
                        <textarea wire:model="customerNotes" class="input-field w-full rounded-lg border-gray-200 resize-none h-16" placeholder="Notas opcionales"></textarea>
                    </div>
                    <div class="md:col-span-2 lg:col-span-3 flex justify-between items-center">
                        <label class="flex items-center gap-2 text-sm text-gray-600">
                            <input type="checkbox" wire:model="customerActive" class="rounded border-gray-300 text-olive-600" />
                            Cliente activo
                        </label>
                        <div class="flex gap-2">
                            <button type="button" wire:click="resetForm" class="btn-secondary">Cancelar</button>
                            <button type="submit" class="btn-primary">{{ $editingCustomer ? 'Actualizar' : 'Crear' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Customer List --}}
    <div class="card">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-left text-xs text-gray-500 uppercase">
                        <th class="px-4 py-3 font-medium">Nombre</th>
                        <th class="px-4 py-3 font-medium">RUC / Cédula</th>
                        <th class="px-4 py-3 font-medium">Teléfono</th>
                        <th class="px-4 py-3 font-medium">Email</th>
                        <th class="px-4 py-3 font-medium">Estado</th>
                        <th class="px-4 py-3 font-medium text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->customers as $customer)
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $customer->name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $customer->ruc ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $customer->phone ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $customer->email ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($customer->is_active)
                                    <span class="text-[10px] px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">Activo</span>
                                @else
                                    <span class="text-[10px] px-2 py-1 rounded-full bg-gray-100 text-gray-500">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button wire:click="showEditForm({{ $customer->id }})" class="text-gray-400 hover:text-olive-600 mr-2">
                                    <i class="fas fa-pen text-xs"></i>
                                </button>
                                <button wire:click="confirmDelete({{ $customer->id }})" class="text-gray-400 hover:text-red-500">
                                    <i class="fas fa-trash-can text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400 text-sm">
                                <i class="fas fa-users text-2xl mb-2 block"></i>
                                {{ $search ? 'No se encontraron clientes' : 'No hay clientes registrados' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($this->customers->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $this->customers->links() }}
            </div>
        @endif
    </div>
</div>

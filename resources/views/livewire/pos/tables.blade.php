<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-olive-900">POS / Sala</h2>
            <p class="text-sm text-gray-500">Gestión de mesas y zonas del restaurante</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="createTable" class="btn-primary flex items-center gap-2 text-sm">
                <i class="fas fa-plus"></i> Mesa
            </button>
            <button wire:click="createZone" class="btn-secondary flex items-center gap-2 text-sm">
                <i class="fas fa-layer-group"></i> Zona
            </button>
        </div>
    </div>

    {{-- Zone and Table Forms --}}
    @if ($showZoneForm)
        <div class="card mb-6">
            <div class="p-4 md:p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-olive-100 flex items-center justify-center">
                        <i class="fas fa-layer-group text-olive-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $editingZone ? 'Editar Zona' : 'Nueva Zona' }}</h3>
                        <p class="text-sm text-gray-500">Define las áreas del restaurante</p>
                    </div>
                </div>
                <form wire:submit="saveZone" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <x-input-label value="Nombre" />
                        <x-text-input wire:model="zoneName" class="input-field w-full" placeholder="Ej: Terraza" />
                        <x-input-error :messages="$errors->get('zoneName')" />
                    </div>
                    <div>
                        <x-input-label value="Descripción" />
                        <x-text-input wire:model="zoneDescription" class="input-field w-full" placeholder="Área exterior" />
                    </div>
                    <div>
                        <x-input-label value="Orden" />
                        <x-text-input wire:model="zoneSortOrder" type="number" class="input-field w-full" />
                    </div>
                    <div class="md:col-span-3 flex justify-end gap-2">
                        <button type="button" wire:click="resetZoneForm" class="btn-secondary">Cancelar</button>
                        <button type="submit" class="btn-primary">{{ $editingZone ? 'Actualizar' : 'Crear' }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($showTableForm)
        <div class="card mb-6">
            <div class="p-4 md:p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gold-100 flex items-center justify-center">
                        <i class="fas fa-chair text-gold-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $editingTable ? 'Editar Mesa' : 'Nueva Mesa' }}</h3>
                        <p class="text-sm text-gray-500">Agrega mesas a una zona</p>
                    </div>
                </div>
                <form wire:submit="saveTable" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <x-input-label value="Zona" />
                        <select wire:model="tableZoneId" class="input-field w-full rounded-lg border-gray-200">
                            <option value="">Seleccionar zona</option>
                            @foreach ($zones as $z)
                                <option value="{{ $z->id }}">{{ $z->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('tableZoneId')" />
                    </div>
                    <div>
                        <x-input-label value="Número" />
                        <x-text-input wire:model="tableNumber" class="input-field w-full" placeholder="1" />
                        <x-input-error :messages="$errors->get('tableNumber')" />
                    </div>
                    <div>
                        <x-input-label value="Capacidad" />
                        <x-text-input wire:model="tableCapacity" type="number" min="1" max="20" class="input-field w-full" />
                    </div>
                    <div>
                        <x-input-label value="Forma" />
                        <select wire:model="tableShape" class="input-field w-full rounded-lg border-gray-200">
                            <option value="square">Cuadrada</option>
                            <option value="round">Redonda</option>
                            <option value="rectangle">Rectangular</option>
                        </select>
                    </div>
                    <div class="md:col-span-4 flex justify-end gap-2">
                        <button type="button" wire:click="resetTableForm" class="btn-secondary">Cancelar</button>
                        <button type="submit" class="btn-primary">{{ $editingTable ? 'Actualizar' : 'Crear' }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Zone Tabs --}}
    @if ($zones->isNotEmpty())
        <div class="flex gap-2 mb-4 overflow-x-auto pb-2">
            @foreach ($zones as $zone)
                <button wire:click="selectZone({{ $zone->id }})"
                    class="whitespace-nowrap px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                    {{ $selectedZone === $zone->id ? 'bg-olive-600 text-white shadow-lg shadow-olive-600/25' : 'bg-white text-gray-600 hover:bg-olive-50 border border-gray-200' }}">
                    <i class="fas fa-layer-group mr-1.5"></i>{{ $zone->name }}
                    <span class="ml-1.5 text-xs opacity-75">({{ $zone->tables->count() }})</span>
                </button>
            @endforeach
        </div>

        {{-- Floor Plan --}}
        @if ($selectedZoneData)
            <div class="card">
                <div class="p-4 md:p-6 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <h3 class="font-semibold text-gray-900">{{ $selectedZoneData->name }}</h3>
                        <span class="text-xs text-gray-400">{{ $selectedZoneData->description }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <button wire:click="editZone({{ $selectedZoneData->id }})" class="text-xs text-gray-400 hover:text-olive-600">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button x-data="{ confirmed: false }"
                            wire:click.stop="deleteZone({{ $selectedZoneData->id }})"
                            x-on:click.capture="
                                if (!confirmed) {
                                    $event.preventDefault();
                                    $event.stopPropagation();
                                    Swal.fire({ title: '¿Eliminar zona?', text: 'Esta acción no se puede deshacer.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar'
                                    }).then((r) => {
                                        if (r.isConfirmed) {
                                            confirmed = true;
                                            $el.click();
                                            confirmed = false;
                                        }
                                    });
                                }
                            "
                            class="text-xs text-gray-400 hover:text-red-500">
                            <i class="fas fa-trash-can"></i>
                        </button>
                    </div>
                </div>

                @if ($selectedZoneData->tables->isEmpty())
                    <div class="p-8 md:p-12 text-center">
                        <i class="fas fa-chair text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 mb-3">No hay mesas en esta zona</p>
                        <button wire:click="createTable" class="btn-primary text-sm">Agregar mesa</button>
                    </div>
                @else
                    {{-- Desktop: Floor Grid --}}
                    <div class="hidden md:block p-6 bg-gradient-to-br from-olive-50/50 to-cream">
                        <div class="grid grid-cols-4 lg:grid-cols-6 gap-4 auto-rows-min">
                            @foreach ($selectedZoneData->tables as $table)
                                <a href="{{ route('pos.orders.create', $table) }}" wire:navigate class="relative group cursor-pointer" wire:key="table-{{ $table->id }}">
                                    {{-- Table Shape --}}
                                    <div class="flex flex-col items-center justify-center p-4 rounded-xl transition-all duration-200 border-2 
                                        {{ $table->status === 'available' ? 'border-emerald-300 bg-emerald-50 hover:bg-emerald-100' : '' }}
                                        {{ $table->status === 'occupied' ? 'border-red-300 bg-red-50 hover:bg-red-100' : '' }}
                                        {{ $table->status === 'reserved' ? 'border-gold-300 bg-gold-50 hover:bg-gold-100' : '' }}
                                        {{ $table->status === 'billing' ? 'border-blue-300 bg-blue-50 hover:bg-blue-100' : '' }}
                                        {{ $table->status === 'blocked' ? 'border-gray-300 bg-gray-100 hover:bg-gray-200' : '' }}
                                        {{ $table->shape === 'round' ? 'rounded-full aspect-square' : 'rounded-xl' }}
                                        {{ $table->shape === 'rectangle' ? 'col-span-2' : '' }}
                                        min-h-[100px]">
                                        <span class="text-lg font-bold 
                                            {{ $table->status === 'available' ? 'text-emerald-700' : '' }}
                                            {{ $table->status === 'occupied' ? 'text-red-700' : '' }}
                                            {{ $table->status === 'reserved' ? 'text-gold-700' : '' }}
                                            {{ $table->status === 'billing' ? 'text-blue-700' : '' }}
                                            {{ $table->status === 'blocked' ? 'text-gray-500' : '' }}">
                                            {{ $table->number }}
                                        </span>
                                        <span class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-users mr-1"></i>{{ $table->capacity }}
                                        </span>
                                        @if($table->activeOrder)
                                            <span class="text-[10px] font-medium mt-1 px-2 py-0.5 rounded-full bg-white/70">
                                                <i class="fas fa-clock mr-0.5"></i>{{ $table->activeOrder->created_at->diffForHumans(parts: 1) }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Hover Actions --}}
                                    <div class="absolute -top-2 -right-2 hidden group-hover:flex gap-1 z-10">
                                        @if($table->status === 'available')
                                            <button wire:click="changeTableStatus({{ $table->id }}, 'occupied')" 
                                                class="w-7 h-7 rounded-full bg-emerald-500 text-white text-xs flex items-center justify-center hover:scale-110 transition-transform shadow" title="Ocupar">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @else
                                            <button wire:click="changeTableStatus({{ $table->id }}, 'available')" 
                                                class="w-7 h-7 rounded-full bg-gray-400 text-white text-xs flex items-center justify-center hover:scale-110 transition-transform shadow" title="Disponible">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        @endif
                                        <button wire:click="editTable({{ $table->id }})" 
                                            class="w-7 h-7 rounded-full bg-olive-500 text-white text-xs flex items-center justify-center hover:scale-110 transition-transform shadow" title="Editar">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button x-data="{ confirmed: false }"
                                            wire:click.stop="deleteTable({{ $table->id }})"
                                            x-on:click.capture="
                                                if (!confirmed) {
                                                    $event.preventDefault();
                                                    $event.stopPropagation();
                                                    Swal.fire({ title: '¿Eliminar mesa?', text: 'Esta acción no se puede deshacer.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar'
                                                    }).then((r) => {
                                                        if (r.isConfirmed) {
                                                            confirmed = true;
                                                            $el.click();
                                                            confirmed = false;
                                                        }
                                                    });
                                                }
                                            "
                                            class="w-7 h-7 rounded-full bg-red-500 text-white text-xs flex items-center justify-center hover:scale-110 transition-transform shadow" title="Eliminar">
                                            <i class="fas fa-trash-can"></i>
                                        </button>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Mobile: Table List --}}
                    <div class="md:hidden divide-y divide-gray-100">
                        @foreach ($selectedZoneData->tables as $table)
                            <a href="{{ route('pos.orders.create', $table) }}" wire:navigate class="p-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm
                                        {{ $table->status === 'available' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                        {{ $table->status === 'occupied' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $table->status === 'reserved' ? 'bg-gold-100 text-gold-700' : '' }}
                                        {{ $table->status === 'billing' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $table->status === 'blocked' ? 'bg-gray-100 text-gray-500' : '' }}">
                                        {{ $table->number }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Mesa {{ $table->number }}</p>
                                        <p class="text-xs text-gray-500"><i class="fas fa-users mr-1"></i>{{ $table->capacity }} personas</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] px-2 py-1 rounded-full font-medium
                                        {{ $table->status === 'available' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                        {{ $table->status === 'occupied' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $table->status === 'reserved' ? 'bg-gold-100 text-gold-700' : '' }}
                                        {{ $table->status === 'billing' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $table->status === 'blocked' ? 'bg-gray-100 text-gray-500' : '' }}">
                                        {{ $this->getStatusLabel($table->status) }}
                                    </span>
                                    <button wire:click="editTable({{ $table->id }})" class="p-1.5 text-gray-400 hover:text-olive-600">
                                        <i class="fas fa-pen text-xs"></i>
                                    </button>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    {{-- Legend --}}
                    <div class="px-4 md:px-6 py-3 border-t border-gray-100 flex flex-wrap gap-4 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Disponible</span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-500"></span> Ocupada</span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-gold-500"></span> Reservada</span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-500"></span> En cuenta</span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-gray-400"></span> Bloqueada</span>
                    </div>
                @endif
            </div>
        @endif
    @else
        {{-- Empty state --}}
        <div class="card p-8 md:p-12 text-center">
            <div class="w-16 h-16 mx-auto bg-olive-100 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-map text-3xl text-olive-500"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Configura tu restaurante</h3>
            <p class="text-gray-500 max-w-md mx-auto mb-6">Crea zonas y mesas para comenzar a usar el sistema POS.</p>
            <button wire:click="createZone" class="btn-primary">Crear primera zona</button>
        </div>
    @endif
</div>

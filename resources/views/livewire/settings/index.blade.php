<div class="flex flex-col h-full">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-olive-900">Configuración</h2>
            <p class="text-sm text-gray-500">Datos del restaurante, impuestos, turnos e impresoras</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-1 mb-4 overflow-x-auto">
        <button wire:click="changeTab('restaurant')"
            class="px-4 py-2 text-sm rounded-lg font-medium transition-all whitespace-nowrap
            {{ $activeTab === 'restaurant' ? 'bg-olive-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            <i class="fas fa-store mr-1.5"></i>Restaurante
        </button>
        <button wire:click="changeTab('taxes')"
            class="px-4 py-2 text-sm rounded-lg font-medium transition-all whitespace-nowrap
            {{ $activeTab === 'taxes' ? 'bg-olive-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            <i class="fas fa-percent mr-1.5"></i>Impuestos y Propinas
        </button>
        <button wire:click="changeTab('shifts')"
            class="px-4 py-2 text-sm rounded-lg font-medium transition-all whitespace-nowrap
            {{ $activeTab === 'shifts' ? 'bg-olive-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            <i class="fas fa-clock mr-1.5"></i>Turnos
        </button>
        <button wire:click="changeTab('printers')"
            class="px-4 py-2 text-sm rounded-lg font-medium transition-all whitespace-nowrap
            {{ $activeTab === 'printers' ? 'bg-olive-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            <i class="fas fa-print mr-1.5"></i>Impresoras
        </button>
    </div>

    {{-- ==================== RESTAURANT ==================== --}}
    @if($activeTab === 'restaurant')
        <div class="max-w-2xl space-y-4">
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Información del restaurante</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <x-input-label value="Nombre comercial *" />
                        <x-text-input wire:model="tradeName" class="input-field w-full text-sm" />
                    </div>
                    <div>
                        <x-input-label value="Razón social" />
                        <x-text-input wire:model="legalName" class="input-field w-full text-sm" />
                    </div>
                    <div>
                        <x-input-label value="RUC" />
                        <x-text-input wire:model="ruc" class="input-field w-full text-sm" maxlength="13" />
                    </div>
                    <div>
                        <x-input-label value="Teléfono" />
                        <x-text-input wire:model="phone" class="input-field w-full text-sm" />
                    </div>
                    <div class="col-span-2">
                        <x-input-label value="Dirección" />
                        <x-text-input wire:model="address" class="input-field w-full text-sm" />
                    </div>
                    <div class="col-span-2">
                        <x-input-label value="Email" />
                        <x-text-input wire:model="email" type="email" class="input-field w-full text-sm" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Configuración regional</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <x-input-label value="Moneda" />
                        <select wire:model="currency" class="input-field w-full text-sm rounded-lg border-gray-200">
                            <option value="USD">USD ($)</option>
                            <option value="EUR">EUR (€)</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label value="Zona horaria" />
                        <select wire:model="timezone" class="input-field w-full text-sm rounded-lg border-gray-200">
                            <option value="America/Guayaquil">America/Guayaquil</option>
                            <option value="America/New_York">America/New_York</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label value="Formato fecha" />
                        <x-text-input wire:model="dateFormat" class="input-field w-full text-sm" />
                    </div>
                    <div>
                        <x-input-label value="Separador decimal" />
                        <select wire:model="decimalSep" class="input-field w-full text-sm rounded-lg border-gray-200">
                            <option value=".">Punto (.)</option>
                            <option value=",">Coma (,)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">SRI — Facturación electrónica</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <x-input-label value="Ambiente" />
                        <select wire:model="sriEnvironment" class="input-field w-full text-sm rounded-lg border-gray-200">
                            <option value="test">Pruebas</option>
                            <option value="production">Producción</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label value="Tipo contribuyente" />
                        <select wire:model="sriTaxpayerType" class="input-field w-full text-sm rounded-lg border-gray-200">
                            <option value="otro">Otro</option>
                            <option value="rimpe">RIMPE</option>
                            <option value="contribuyente_especial">Contribuyente Especial</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <x-input-label value="Certificado (.p12)" />
                        <x-text-input wire:model="sriCertPath" class="input-field w-full text-sm" placeholder="Ruta al archivo .p12" />
                    </div>
                    <div class="col-span-2">
                        <x-input-label value="Contraseña del certificado" />
                        <x-text-input wire:model="sriCertPass" type="password" class="input-field w-full text-sm" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">KDS (Pantalla de cocina)</h3>
                <div>
                    <x-input-label value="Minutos para alerta de tiempo" />
                    <x-text-input wire:model="kdsAlertMin" type="number" class="input-field w-full text-sm w-32" />
                </div>
            </div>

            <div class="flex justify-end">
                <button wire:click="saveRestaurant" class="btn-primary text-sm">
                    <i class="fas fa-save mr-1"></i>Guardar configuración
                </button>
            </div>
        </div>
    @endif

    {{-- ==================== TAXES ==================== --}}
    @if($activeTab === 'taxes')
        <div class="max-w-lg space-y-4">
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Impuesto (IVA)</h3>
                <div>
                    <x-input-label value="Porcentaje de IVA" />
                    <div class="flex items-center gap-2">
                        <x-text-input wire:model="taxRate" type="number" step="0.01" class="input-field text-sm w-24" />
                        <span class="text-sm text-gray-500">%</span>
                    </div>
                    <x-input-error :messages="$errors->get('taxRate')" />
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Cargo por servicio</h3>
                <label class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                    <input type="checkbox" wire:model="serviceChargeActive" class="rounded border-gray-300 text-olive-600" />
                    Activar cargo por servicio de mesa
                </label>
                @if($serviceChargeActive)
                    <div class="flex items-center gap-2">
                        <x-text-input wire:model="serviceChargeRate" type="number" step="0.01" class="input-field text-sm w-24" />
                        <span class="text-sm text-gray-500">%</span>
                    </div>
                    <x-input-error :messages="$errors->get('serviceChargeRate')" />
                @endif
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Propinas sugeridas</h3>
                <p class="text-xs text-gray-400 mb-2">Porcentajes que aparecerán en la pantalla de cobro</p>
                <div class="flex gap-2 items-center">
                    <x-text-input wire:model="tipSug10" type="number" class="input-field text-sm w-16" />
                    <span class="text-sm text-gray-500">%</span>
                    <x-text-input wire:model="tipSug15" type="number" class="input-field text-sm w-16" />
                    <span class="text-sm text-gray-500">%</span>
                    <x-text-input wire:model="tipSug20" type="number" class="input-field text-sm w-16" />
                    <span class="text-sm text-gray-500">%</span>
                </div>
            </div>

            <div class="flex justify-end">
                <button wire:click="saveTaxes" class="btn-primary text-sm">
                    <i class="fas fa-save mr-1"></i>Guardar impuestos
                </button>
            </div>
        </div>
    @endif

    {{-- ==================== SHIFTS ==================== --}}
    @if($activeTab === 'shifts')
        <div class="flex justify-end mb-3">
            <button wire:click="createShift" class="btn-primary text-xs">
                <i class="fas fa-plus"></i> Nuevo turno
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @forelse($this->shifts as $s)
                <div class="bg-white rounded-xl border border-gray-200 p-4 {{ !$s->is_active ? 'opacity-60' : '' }}">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="text-sm font-semibold text-gray-900">{{ $s->name }}</h3>
                        <div class="flex gap-1">
                            <button wire:click="editShift({{ $s->id }})" class="p-1 text-gray-400 hover:text-olive-600">
                                <i class="fas fa-pen text-[10px]"></i>
                            </button>
                            <button x-data @click.prevent="
                                Swal.fire({
                                    title: '¿Eliminar turno?',
                                    text: '{{ $s->name }}',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Sí, eliminar',
                                    cancelButtonText: 'Cancelar',
                                }).then((r) => { if (r.isConfirmed) $wire.deleteShift({{ $s->id }}); });
                            " class="p-1 text-gray-400 hover:text-red-500">
                                <i class="fas fa-trash text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">
                        <i class="fas fa-clock w-4 text-gray-300"></i>{{ substr($s->start_time, 0, 5) }} — {{ substr($s->end_time, 0, 5) }}
                    </p>
                    <p class="text-xs text-gray-500">{{ $s->users_count }} usuario(s) asignados</p>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-10 text-gray-400">
                    <i class="fas fa-clock text-3xl mb-2"></i>
                    <p class="text-sm">No hay turnos configurados</p>
                </div>
            @endforelse
        </div>

        {{-- Shift Form Modal --}}
        @if($showShiftForm)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
                 x-data @click.away="$wire.set('showShiftForm', false)">
                <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md mx-4" @@click.stop>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $editingShift ? 'Editar' : 'Nuevo' }} turno</h3>
                    <div class="space-y-3">
                        <div>
                            <x-input-label value="Nombre" />
                            <x-text-input wire:model="shiftName" class="input-field w-full text-sm" placeholder="Ej: Matutino" />
                            <x-input-error :messages="$errors->get('shiftName')" />
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <x-input-label value="Inicio" />
                                <x-text-input wire:model="shiftStart" type="time" class="input-field w-full text-sm" />
                                <x-input-error :messages="$errors->get('shiftStart')" />
                            </div>
                            <div>
                                <x-input-label value="Fin" />
                                <x-text-input wire:model="shiftEnd" type="time" class="input-field w-full text-sm" />
                                <x-input-error :messages="$errors->get('shiftEnd')" />
                            </div>
                        </div>
                        <div>
                            <x-input-label value="Usuarios asignados" />
                            <div class="max-h-40 overflow-y-auto border border-gray-200 rounded-lg p-2 space-y-1">
                                @foreach($this->users as $u)
                                    <label class="flex items-center gap-2 text-sm text-gray-600">
                                        <input type="checkbox" value="{{ $u->id }}" wire:model="shiftUsers"
                                            class="rounded border-gray-300 text-olive-600" />
                                        {{ $u->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <label class="flex items-center gap-2 text-sm text-gray-600">
                            <input type="checkbox" wire:model="shiftActive" class="rounded border-gray-300 text-olive-600" />
                            Activo
                        </label>
                    </div>
                    <div class="flex justify-end gap-2 pt-4">
                        <button wire:click="$set('showShiftForm', false)" class="btn-secondary text-sm">Cancelar</button>
                        <button wire:click="saveShift" class="btn-primary text-sm">Guardar</button>
                    </div>
                </div>
            </div>
        @endif
    @endif

    {{-- ==================== PRINTERS ==================== --}}
    @if($activeTab === 'printers')
        <div class="flex justify-end mb-3">
            <button wire:click="createPrinter" class="btn-primary text-xs">
                <i class="fas fa-plus"></i> Nueva impresora
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @forelse($this->printers as $p)
                <div class="bg-white rounded-xl border border-gray-200 p-4 {{ !$p->is_active ? 'opacity-60' : '' }}">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="text-sm font-semibold text-gray-900">{{ $p->name }}</h3>
                        <div class="flex gap-1">
                            <button wire:click="editPrinter({{ $p->id }})" class="p-1 text-gray-400 hover:text-olive-600">
                                <i class="fas fa-pen text-[10px]"></i>
                            </button>
                            <button wire:click="togglePrinter({{ $p->id }})"
                                class="p-1 {{ $p->is_active ? 'text-emerald-400' : 'text-gray-300' }}">
                                <i class="fas {{ $p->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                            </button>
                            <button x-data @click.prevent="
                                Swal.fire({
                                    title: '¿Eliminar impresora?',
                                    text: '{{ $p->name }}',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Sí, eliminar',
                                    cancelButtonText: 'Cancelar',
                                }).then((r) => { if (r.isConfirmed) $wire.deletePrinter({{ $p->id }}); });
                            " class="p-1 text-gray-400 hover:text-red-500">
                                <i class="fas fa-trash text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-1 text-xs text-gray-500">
                        <p><i class="fas fa-share-alt w-4 text-gray-300"></i>{{ $p->ip_address }}:{{ $p->port }}</p>
                        <p><i class="fas fa-tag w-4 text-gray-300"></i>{{ str_replace('_', ' ', $p->type) }}</p>
                        <p><span class="px-2 py-0.5 rounded-full text-[10px] {{ $p->printer_function === 'ticket_client' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ str_replace('_', ' ', $p->printer_function) }}
                        </span></p>
                        @if($p->model)
                            <p><i class="fas fa-cube w-4 text-gray-300"></i>{{ $p->model }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-10 text-gray-400">
                    <i class="fas fa-print text-3xl mb-2"></i>
                    <p class="text-sm">No hay impresoras registradas</p>
                </div>
            @endforelse
        </div>

        {{-- Printer Form Modal --}}
        @if($showPrinterForm)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
                 x-data @click.away="$wire.set('showPrinterForm', false)">
                <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-lg mx-4" @@click.stop>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $editingPrinter ? 'Editar' : 'Nueva' }} impresora</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="col-span-2">
                            <x-input-label value="Nombre" />
                            <x-text-input wire:model="printerName" class="input-field w-full text-sm" placeholder="Ej: Cocina Principal" />
                        </div>
                        <div>
                            <x-input-label value="Tipo" />
                            <select wire:model="printerType" class="input-field w-full text-sm rounded-lg border-gray-200">
                                <option value="thermal_escpos">Térmica ESC/POS</option>
                                <option value="laser">Láser</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label value="Función" />
                            <select wire:model="printerFunction" class="input-field w-full text-sm rounded-lg border-gray-200">
                                <option value="ticket_client">Ticket cliente</option>
                                <option value="kitchen_order">Comanda cocina</option>
                                <option value="cash_closure">Cierre de caja</option>
                                <option value="labels">Etiquetas</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label value="Dirección IP" />
                            <x-text-input wire:model="printerIp" class="input-field w-full text-sm" placeholder="192.168.1.100" />
                        </div>
                        <div>
                            <x-input-label value="Puerto" />
                            <x-text-input wire:model="printerPort" type="number" class="input-field w-full text-sm" />
                        </div>
                        <div>
                            <x-input-label value="Modelo" />
                            <x-text-input wire:model="printerModel" class="input-field w-full text-sm" placeholder="Opcional" />
                        </div>
                        <div>
                            <x-input-label value="Área de cocina" />
                            <select wire:model="printerArea" class="input-field w-full text-sm rounded-lg border-gray-200">
                                <option value="">Todas</option>
                                <option value="parrilla">Parrilla</option>
                                <option value="cocina">Cocina</option>
                                <option value="horno">Horno</option>
                                <option value="frio">Frío</option>
                                <option value="postres">Postres</option>
                                <option value="bebidas">Bebidas</option>
                            </select>
                        </div>
                        <div class="col-span-2 flex items-center gap-2">
                            <label class="flex items-center gap-2 text-sm text-gray-600">
                                <input type="checkbox" wire:model="printerActive" class="rounded border-gray-300 text-olive-600" />
                                Activa
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 pt-4">
                        <button wire:click="$set('showPrinterForm', false)" class="btn-secondary text-sm">Cancelar</button>
                        <button wire:click="savePrinter" class="btn-primary text-sm">Guardar</button>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>

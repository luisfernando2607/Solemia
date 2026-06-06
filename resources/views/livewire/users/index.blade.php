<div>
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <h2 class="text-xl md:text-2xl font-bold text-olive-900">Usuarios</h2>
        @can('gestionar_usuarios')
            <button wire:click="create" class="btn-primary flex items-center gap-2 w-full sm:w-auto justify-center">
                <i class="fas fa-plus"></i>
                Nuevo Usuario
            </button>
        @endcan
    </div>

    {{-- Formulario --}}
    @if ($showForm)
        <div class="card mb-6">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl {{ $editing ? 'bg-gold-100 text-gold-600' : 'bg-olive-100 text-olive-600' }} flex items-center justify-center">
                        <i class="fas {{ $editing ? 'fa-user-edit' : 'fa-user-plus' }} text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $editing ? 'Editar Usuario' : 'Nuevo Usuario' }}</h3>
                        <p class="text-sm text-gray-500">{{ $editing ? 'Modifica los datos del usuario seleccionado.' : 'Ingresa los datos del nuevo usuario.' }}</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form wire:submit="save" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="name" value="Nombre completo" />
                            <x-text-input id="name" wire:model="name" class="input-field" placeholder="Ej: Mario Rossi" />
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="email" value="Correo electrónico" />
                            <x-text-input id="email" type="email" wire:model="email" class="input-field" placeholder="ejemplo@solemia.com" />
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="password" value="{{ $editing ? 'Nueva contraseña (dejar vacío para mantener)' : 'Contraseña' }}" />
                            <x-text-input id="password" type="password" wire:model="password" class="input-field" placeholder="••••••" />
                            <x-input-error :messages="$errors->get('password')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="password_confirmation" value="Confirmar contraseña" />
                            <x-text-input id="password_confirmation" type="password" wire:model="passwordConfirmation" class="input-field" placeholder="••••••" />
                            <x-input-error :messages="$errors->get('passwordConfirmation')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="pin" value="PIN (4 dígitos para acceso POS)" />
                            <x-text-input id="pin" wire:model="pin" maxlength="4" class="input-field" placeholder="1234" />
                            <x-input-error :messages="$errors->get('pin')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label value="Estado" />
                            <label class="mt-2 inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-olive-600 shadow-sm focus:ring-olive-500" />
                                <span class="text-sm text-gray-600">Usuario activo</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <x-input-label value="Roles (selecciona al menos uno)" />
                        <div class="mt-2 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                            @foreach ($roles as $role)
                                <label class="relative flex items-center p-3 rounded-lg border-2 cursor-pointer transition-all duration-200"
                                    x-data
                                    :class="$wire.selectedRoles.includes({{ $role->id }}) ? 'border-olive-500 bg-olive-50' : 'border-gray-200 hover:border-gray-300'">
                                    <input type="checkbox" wire:model="selectedRoles" value="{{ $role->id }}" class="sr-only" />
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 rounded border-2 flex items-center justify-center transition-all duration-200"
                                            :class="$wire.selectedRoles.includes({{ $role->id }}) ? 'bg-olive-600 border-olive-600' : 'border-gray-300'">
                                            <i class="fas fa-check text-white text-[10px]" x-show="$wire.selectedRoles.includes({{ $role->id }})"></i>
                                        </div>
                                        <span class="text-sm font-medium" :class="$wire.selectedRoles.includes({{ $role->id }}) ? 'text-olive-800' : 'text-gray-600'">{{ $role->name }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('selectedRoles')" class="mt-1" />
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <x-secondary-button wire:click="resetForm" class="btn-secondary">
                            <i class="fas fa-times mr-2"></i>Cancelar
                        </x-secondary-button>
                        <button type="submit" class="btn-primary flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            {{ $editing ? 'Actualizar' : 'Crear Usuario' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Tabla (Desktop) --}}
    <div class="card hidden md:block">
        <div class="p-4 md:p-6 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="relative w-full sm:w-auto">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <x-text-input wire:model.live.debounce="search" placeholder="Buscar usuarios..." class="input-field pl-10 w-full sm:w-72" />
                </div>
                <span class="text-xs md:text-sm text-gray-500">
                    <i class="fas fa-users mr-1"></i> {{ $users->total() }} usuarios
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th wire:click="sortBy('name')" class="px-4 md:px-6 py-3 text-left text-[10px] md:text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:text-olive-600 transition-colors">
                            <div class="flex items-center gap-1">Nombre <i class="fas fa-sort text-gray-300 text-[10px]"></i></div>
                        </th>
                        <th wire:click="sortBy('email')" class="px-4 md:px-6 py-3 text-left text-[10px] md:text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:text-olive-600 transition-colors">
                            <div class="flex items-center gap-1">Email <i class="fas fa-sort text-gray-300 text-[10px]"></i></div>
                        </th>
                        <th class="px-4 md:px-6 py-3 text-left text-[10px] md:text-xs font-semibold text-gray-500 uppercase tracking-wider">Roles</th>
                        <th class="px-4 md:px-6 py-3 text-left text-[10px] md:text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-4 md:px-6 py-3 text-right text-[10px] md:text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($users as $user)
                        <tr class="hover:bg-olive-50/30 transition-colors duration-150">
                            <td class="px-4 md:px-6 py-3 md:py-4">
                                <div class="flex items-center gap-2 md:gap-3">
                                    <div class="w-7 h-7 md:w-9 md:h-9 rounded-full bg-olive-100 flex items-center justify-center text-xs md:text-sm font-bold text-olive-700 shrink-0">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs md:text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                        @if($user->pin)
                                            <p class="text-[10px] md:text-xs text-gray-400"><i class="fas fa-key mr-1"></i>PIN: {{ $user->pin }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm text-gray-600 truncate max-w-[150px] md:max-w-none">{{ $user->email }}</td>
                            <td class="px-4 md:px-6 py-3 md:py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($user->roles as $role)
                                        <span class="text-[10px] md:text-xs badge {{ $role->name === 'Administrador' ? 'badge-red' : ($role->name === 'Gerente' ? 'badge-gold' : ($role->name === 'Mesero' ? 'badge-blue' : ($role->name === 'Cocinero' ? 'badge-gray' : 'badge-green'))) }}">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 md:px-6 py-3 md:py-4">
                                <button wire:click="toggleActive({{ $user->id }})">
                                    @if ($user->is_active)
                                        <span class="badge badge-green text-[10px] md:text-xs"><i class="fas fa-circle text-[6px] mr-1"></i>Activo</span>
                                    @else
                                        <span class="badge badge-gray text-[10px] md:text-xs"><i class="fas fa-circle text-[6px] mr-1"></i>Inactivo</span>
                                    @endif
                                </button>
                            </td>
                            <td class="px-4 md:px-6 py-3 md:py-4 text-right">
                                <div class="flex items-center justify-end gap-1 md:gap-2">
                                    <button wire:click="edit({{ $user->id }})" class="p-1.5 md:p-2 text-gray-400 hover:text-olive-600 hover:bg-olive-50 rounded-lg transition-all duration-200" title="Editar">
                                        <i class="fas fa-pen text-xs md:text-sm"></i>
                                    </button>
                                    @if ($user->id !== auth()->id())
                                        <button wire:click="confirmDelete({{ $user->id }})" class="p-1.5 md:p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200" title="Eliminar">
                                            <i class="fas fa-trash-can text-xs md:text-sm"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 md:px-6 py-8 md:py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <i class="fas fa-users-slash text-3xl md:text-4xl text-gray-300"></i>
                                    <p class="text-sm md:text-base text-gray-500">No hay usuarios registrados.</p>
                                    <button wire:click="create" class="btn-primary text-xs md:text-sm">Crear primer usuario</button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="px-4 md:px-6 py-3 md:py-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- Cards (Mobile) --}}
    <div class="md:hidden space-y-3">
        <div class="relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <x-text-input wire:model.live.debounce="search" placeholder="Buscar..." class="input-field pl-10 w-full" />
        </div>

        @forelse ($users as $user)
            <div class="card p-4">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-olive-100 flex items-center justify-center text-sm font-bold text-olive-700 shrink-0">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <button wire:click="toggleActive({{ $user->id }})">
                        @if ($user->is_active)
                            <span class="badge badge-green text-[10px]"><i class="fas fa-circle text-[6px] mr-1"></i>Activo</span>
                        @else
                            <span class="badge badge-gray text-[10px]"><i class="fas fa-circle text-[6px] mr-1"></i>Inactivo</span>
                        @endif
                    </button>
                </div>
                <div class="flex flex-wrap gap-1 mb-3">
                    @foreach ($user->roles as $role)
                        <span class="text-[10px] badge {{ $role->name === 'Administrador' ? 'badge-red' : ($role->name === 'Gerente' ? 'badge-gold' : ($role->name === 'Mesero' ? 'badge-blue' : ($role->name === 'Cocinero' ? 'badge-gray' : 'badge-green'))) }}">
                            {{ $role->name }}
                        </span>
                    @endforeach
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                    @if($user->pin)
                        <span class="text-[10px] text-gray-400"><i class="fas fa-key mr-1"></i>PIN: {{ $user->pin }}</span>
                    @else
                        <span></span>
                    @endif
                    <div class="flex gap-2">
                        <button wire:click="edit({{ $user->id }})" class="text-olive-600 text-xs flex items-center gap-1">
                            <i class="fas fa-pen"></i> Editar
                        </button>
                        @if ($user->id !== auth()->id())
                            <button wire:click="confirmDelete({{ $user->id }})" class="text-red-500 text-xs flex items-center gap-1">
                                <i class="fas fa-trash-can"></i> Eliminar
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="card p-8 text-center">
                <div class="flex flex-col items-center gap-3">
                    <i class="fas fa-users-slash text-3xl text-gray-300"></i>
                    <p class="text-sm text-gray-500">No hay usuarios registrados.</p>
                    <button wire:click="create" class="btn-primary text-xs">Crear primer usuario</button>
                </div>
            </div>
        @endforelse

        @if ($users->hasPages())
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

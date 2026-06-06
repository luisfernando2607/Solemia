<div wire:poll.15s="refreshStats">
    {{-- KPIs --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="card p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-olive-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-utensils text-olive-600 text-xl"></i>
                </div>
                <span class="badge text-xs bg-olive-50 text-olive-600">Hoy</span>
            </div>
            <p class="text-sm text-gray-500 mb-1">Mesas ocupadas</p>
            <p class="text-3xl font-bold text-gray-900">{{ $occupiedTables }}</p>
        </div>

        <div class="card p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gold-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-fire text-gold-600 text-xl"></i>
                </div>
                <span class="badge text-xs bg-gold-50 text-gold-600">En cocina</span>
            </div>
            <p class="text-sm text-gray-500 mb-1">Comandas en preparación</p>
            <p class="text-3xl font-bold text-gray-900">{{ $kitchenOrders }}</p>
        </div>

        <div class="card p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-emerald-600 text-xl"></i>
                </div>
                <span class="badge text-xs bg-emerald-50 text-emerald-600">Ventas</span>
            </div>
            <p class="text-sm text-gray-500 mb-1">Ventas del día</p>
            <p class="text-3xl font-bold text-gray-900">${{ number_format($dailySales, 2) }}</p>
        </div>

        <div class="card p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <span class="badge text-xs bg-blue-50 text-blue-600">Sistema</span>
            </div>
            <p class="text-sm text-gray-500 mb-1">Usuarios activos</p>
            <p class="text-3xl font-bold text-gray-900">{{ $activeUsers }}</p>
        </div>
    </div>

    {{-- Welcome & Info --}}
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 card">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Bienvenido a Solemia</h3>
            </div>
            <div class="p-6">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-olive-100 flex items-center justify-center text-2xl">
                        🍝
                    </div>
                    <div>
                        <p class="text-gray-600 leading-relaxed">
                            "Oohh Solemia de mi corazón" — benvenuto! Has iniciado sesión como <strong>{{ auth()->user()->name }}</strong>.
                            Explora los módulos desde la barra lateral para gestionar tu restaurante.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-4 bg-olive-50 rounded-xl text-center">
                        <p class="text-2xl font-bold text-olive-700">10</p>
                        <p class="text-xs text-olive-600 mt-1">Módulos</p>
                    </div>
                    <div class="p-4 bg-gold-50 rounded-xl text-center">
                        <p class="text-2xl font-bold text-gold-700">5</p>
                        <p class="text-xs text-gold-600 mt-1">Roles</p>
                    </div>
                    <div class="p-4 bg-emerald-50 rounded-xl text-center">
                        <p class="text-2xl font-bold text-emerald-700">32</p>
                        <p class="text-xs text-emerald-600 mt-1">Permisos</p>
                    </div>
                    <div class="p-4 bg-blue-50 rounded-xl text-center">
                        <p class="text-2xl font-bold text-blue-700">{{ $totalUsers }}</p>
                        <p class="text-xs text-blue-600 mt-1">Usuarios</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="p-6 border-b border-gray-100 flex items-center gap-3">
                <i class="fas fa-user-circle text-2xl text-olive-400"></i>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</h3>
                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Rol</span>
                    <span class="font-medium text-gray-900">{{ auth()->user()->getRoleNames()->implode(', ') }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Estado</span>
                    @if (auth()->user()->is_active)
                        <span class="badge-green"><i class="fas fa-circle text-[6px] mr-1"></i>Activo</span>
                    @else
                        <span class="badge-gray">Inactivo</span>
                    @endif
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Miembro desde</span>
                    <span class="text-gray-900 font-medium">{{ auth()->user()->created_at->format('d/m/Y') }}</span>
                </div>
                <hr class="border-gray-100">
                <a href="{{ route('profile') }}" wire:navigate class="flex items-center justify-center gap-2 text-sm text-olive-600 hover:text-olive-700 font-medium py-2">
                    <i class="fas fa-user-cog"></i>
                    Editar perfil
                </a>
            </div>
        </div>
    </div>
</div>

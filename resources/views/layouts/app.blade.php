<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Solemia')) - Solemia POS</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=playfair-display:500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-cream">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        {{-- Overlay --}}
        <div x-show="sidebarOpen" @@click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black/50 lg:hidden" x-cloak></div>

        {{-- Sidebar --}}
        <aside class="fixed lg:static inset-y-0 left-0 z-30 w-64 bg-gradient-to-b from-olive-800 to-olive-950 text-white flex flex-col transition-transform duration-300 lg:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            {{-- Logo & Close --}}
            <div class="px-4 md:px-6 py-4 md:py-6 border-b border-olive-700/50 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3">
                    <div class="w-9 h-9 md:w-10 md:h-10 bg-gold-500 rounded-xl flex items-center justify-center shadow-lg shadow-gold-500/30 shrink-0">
                        <span class="font-serif font-bold text-white text-base md:text-lg">S</span>
                    </div>
                    <div class="min-w-0">
                        <span class="font-serif text-lg md:text-xl font-bold text-white block leading-tight">Solemia</span>
                        <p class="text-olive-400 text-[10px] md:text-xs">POS Ristorante</p>
                    </div>
                </a>
                <button @@click="sidebarOpen = false" class="lg:hidden text-olive-300 hover:text-white p-1">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            {{-- User Info --}}
            <div class="px-4 md:px-6 py-3 md:py-4 border-b border-olive-700/50 bg-olive-800/30">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 md:w-9 md:h-9 rounded-full bg-olive-600 flex items-center justify-center text-xs md:text-sm font-bold shrink-0">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] md:text-xs text-olive-400 truncate">{{ auth()->user()->getRoleNames()->implode(', ') }}</p>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto px-2 md:px-3 py-3 md:py-4 space-y-1">
                <p class="text-olive-400 text-[10px] md:text-xs font-medium uppercase tracking-wider px-3 mb-2">Principal</p>
                <x-sidebar-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="fa-solid fa-chart-pie">
                    Dashboard
                </x-sidebar-link>

                @can('gestionar_mesas')
                    <x-sidebar-link href="{{ route('pos.tables') }}" :active="request()->routeIs('pos.*')" icon="fa-solid fa-utensils">
                        POS / Sala
                    </x-sidebar-link>
                @endcan

                @can('ver_kds')
                    <x-sidebar-link href="{{ route('kitchen.index') }}" :active="request()->routeIs('kitchen.*')" icon="fa-solid fa-fire">
                        Cocina (KDS)
                    </x-sidebar-link>
                @endcan

                @can('editar_menu')
                    <x-sidebar-link href="{{ route('menu.categories') }}" :active="request()->routeIs('menu.*')" icon="fa-solid fa-book-open">
                        Menú
                    </x-sidebar-link>
                @endcan

                @can('ver_caja')
                    <x-sidebar-link href="{{ route('cashier.index') }}" :active="request()->routeIs('cashier.*')" icon="fa-solid fa-cash-register">
                        Caja
                    </x-sidebar-link>
                @endcan

                @can('ver_inventario')
                    <x-sidebar-link href="{{ route('inventory.index') }}" :active="request()->routeIs('inventory.*')" icon="fa-solid fa-boxes-stacked">
                        Inventario
                    </x-sidebar-link>
                @endcan

                <p class="text-olive-400 text-[10px] md:text-xs font-medium uppercase tracking-wider px-3 mt-4 md:mt-6 mb-2">Gestión</p>

                <x-sidebar-link href="{{ route('customers.index') }}" :active="request()->routeIs('customers.*')" icon="fa-solid fa-address-book">
                    Clientes
                </x-sidebar-link>

                @can('ver_reportes')
                    <x-sidebar-link href="{{ route('reports.index') }}" :active="request()->routeIs('reports.*')" icon="fa-solid fa-chart-line">
                        Reportes
                    </x-sidebar-link>
                @endcan

                @can('gestionar_usuarios')
                    <x-sidebar-link href="{{ route('users.index') }}" :active="request()->routeIs('users.*')" icon="fa-solid fa-users-cog">
                        Usuarios
                    </x-sidebar-link>
                @endcan

                @can('configurar_sistema')
                    <x-sidebar-link href="{{ route('settings.index') }}" :active="request()->routeIs('settings.*')" icon="fa-solid fa-gear">
                        Configuración
                    </x-sidebar-link>
                @endcan
            </nav>

            {{-- Logout --}}
            <div class="px-2 md:px-3 py-3 md:py-4 border-t border-olive-700/50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-link text-olive-300 hover:text-red-300 hover:bg-red-500/10 w-full text-sm md:text-base">
                        <i class="fas fa-sign-out-alt w-5 text-center"></i>
                        <span>Cerrar sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            {{-- Mobile Top Bar --}}
            <header class="bg-white border-b border-gray-100 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button @@click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-olive-600 p-1">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                            <h1 class="text-lg md:text-xl font-bold text-olive-900 font-serif">Solemia POS</h1>
                    </div>
                    <div class="flex items-center gap-2 md:gap-3">
                        <a href="{{ route('profile') }}" wire:navigate class="text-gray-400 hover:text-olive-600 transition-colors" title="Perfil">
                            <i class="fas fa-user-cog text-base md:text-lg"></i>
                        </a>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <div class="p-4 md:p-8 flex-1">
                {{ $slot }}
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('livewire:navigated', () => {});
    </script>
    @livewireScripts
    @stack('scripts')
</body>
</html>

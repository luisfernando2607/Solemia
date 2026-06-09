<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Solemia')) - Solémia POS</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=playfair-display:500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased"
      x-data="{
          sidebarOpen: false,
          sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
          dark: localStorage.getItem('dark') === 'true',
          toggleDark() { this.dark = !this.dark; localStorage.setItem('dark', this.dark); if (this.dark) document.documentElement.classList.add('dark'); else document.documentElement.classList.remove('dark') }
      }"

x-init="$nextTick(() => { if (dark) document.documentElement.classList.add('dark') })"
    <div class="flex h-screen overflow-hidden">
        {{-- Overlay --}}
        <div x-show="sidebarOpen" @@click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black/50 lg:hidden" x-cloak></div>

        {{-- Sidebar --}}

<aside class="fixed lg:static inset-y-0 left-0 z-30 bg-gradient-to-b from-olive-800 to-olive-950 text-white flex flex-col transition-[width] duration-300 ease-in-out lg:translate-x-0"
       :class="[sidebarOpen ? 'translate-x-0' : '-translate-x-full', sidebarCollapsed ? 'w-16' : 'w-64']">

            {{-- Logo & Close --}}
            <div class="px-4 md:px-6 py-4 md:py-6 border-b border-olive-700/50 flex items-center"
                 :class="sidebarCollapsed ? 'justify-center px-0' : 'justify-between'">
                <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center"
                   :class="sidebarCollapsed ? 'justify-center w-full' : 'gap-3'">
                    <div class="w-9 h-9 md:w-10 md:h-10 bg-gold-500 rounded-xl flex items-center justify-center shadow-lg shadow-gold-500/30 shrink-0">
                        <span class="font-serif font-bold text-white text-base md:text-lg">S</span>
                    </div>
                    <div class="min-w-0" x-show="!sidebarCollapsed">
                        <span class="font-serif text-lg md:text-xl font-bold text-white block leading-tight whitespace-nowrap">Solémia</span>
                        <p class="text-olive-400 text-[10px] md:text-xs whitespace-nowrap">Gestión para Restaurantes</p>
                    </div>
                </a>
                <button @@click="sidebarOpen = false" class="lg:hidden text-olive-300 hover:text-white p-1"
                        x-show="!sidebarCollapsed">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            {{-- User Info --}}
            <div class="px-4 md:px-6 py-3 md:py-4 border-b border-olive-700/50 bg-olive-800/30"
                 :class="sidebarCollapsed ? 'px-2 flex justify-center' : ''">
                <div class="flex items-center"
                     :class="sidebarCollapsed ? 'justify-center gap-0' : 'gap-3'">
                    <div class="w-8 h-8 md:w-9 md:h-9 rounded-full bg-olive-600 flex items-center justify-center text-xs md:text-sm font-bold shrink-0">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                    <div class="flex-1 min-w-0" x-show="!sidebarCollapsed">
                        <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] md:text-xs text-olive-400 truncate">{{ auth()->user()->getRoleNames()->implode(', ') }}</p>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto px-2 md:px-3 py-3 md:py-4 space-y-1"
                 :class="sidebarCollapsed ? 'px-1' : ''">
                <p class="text-olive-400 text-[10px] md:text-xs font-medium uppercase tracking-wider px-3 mb-2"
                   x-show="!sidebarCollapsed">Principal</p>
                <x-sidebar-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="fa-solid fa-chart-pie" :collapsed="true">
                    Dashboard
                </x-sidebar-link>

                @can('gestionar_mesas')
                    <x-sidebar-link href="{{ route('pos.tables') }}" :active="request()->routeIs('pos.*')" icon="fa-solid fa-utensils" :collapsed="true">
                        POS / Sala
                    </x-sidebar-link>
                @endcan

                @can('ver_kds')
                    <x-sidebar-link href="{{ route('kitchen.index') }}" :active="request()->routeIs('kitchen.*')" icon="fa-solid fa-fire" :collapsed="true">
                        Cocina (KDS)
                    </x-sidebar-link>
                @endcan

                @can('editar_menu')
                    <x-sidebar-link href="{{ route('menu.categories') }}" :active="request()->routeIs('menu.*')" icon="fa-solid fa-book-open" :collapsed="true">
                        Menú
                    </x-sidebar-link>
                @endcan

                @can('ver_caja')
                    <x-sidebar-link href="{{ route('cashier.index') }}" :active="request()->routeIs('cashier.*')" icon="fa-solid fa-cash-register" :collapsed="true">
                        Caja
                    </x-sidebar-link>
                @endcan

                @can('ver_inventario')
                    <x-sidebar-link href="{{ route('inventory.index') }}" :active="request()->routeIs('inventory.*')" icon="fa-solid fa-boxes-stacked" :collapsed="true">
                        Inventario
                    </x-sidebar-link>
                @endcan

                <p class="text-olive-400 text-[10px] md:text-xs font-medium uppercase tracking-wider px-3 mt-4 md:mt-6 mb-2"
                   x-show="!sidebarCollapsed">Gestión</p>

                <x-sidebar-link href="{{ route('customers.index') }}" :active="request()->routeIs('customers.*')" icon="fa-solid fa-address-book" :collapsed="true">
                    Clientes
                </x-sidebar-link>

                @can('ver_reportes')
                    <x-sidebar-link href="{{ route('reports.index') }}" :active="request()->routeIs('reports.*')" icon="fa-solid fa-chart-line" :collapsed="true">
                        Reportes
                    </x-sidebar-link>
                @endcan

                @can('gestionar_usuarios')
                    <x-sidebar-link href="{{ route('users.index') }}" :active="request()->routeIs('users.*')" icon="fa-solid fa-users-cog" :collapsed="true">
                        Usuarios
                    </x-sidebar-link>
                @endcan

                @can('configurar_sistema')
                    <x-sidebar-link href="{{ route('settings.index') }}" :active="request()->routeIs('settings.*')" icon="fa-solid fa-gear" :collapsed="true">
                        Configuración
                    </x-sidebar-link>
                @endcan
            </nav>

            {{-- Collapse Toggle & Logout --}}
            <div class="px-2 md:px-3 py-3 md:py-4 border-t border-olive-700/50 space-y-1"
                 :class="sidebarCollapsed ? 'px-1 flex flex-col items-center' : ''">
                <button @@click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)"
                    class="sidebar-link text-olive-300 hover:text-white hover:bg-olive-700 w-full text-sm md:text-base hidden lg:flex"
                    :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                    :title="sidebarCollapsed ? 'Expandir menú' : 'Colapsar menú'">
                    <i class="fas w-5 text-center text-sm md:text-base" :class="sidebarCollapsed ? 'fa-bars' : 'fa-xmark'"></i>
                    <span x-show="!sidebarCollapsed" class="text-sm">Colapsar menú</span>
                </button>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="sidebar-link text-olive-300 hover:text-red-300 hover:bg-red-500/10 w-full text-sm md:text-base"
                        :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                        :title="sidebarCollapsed ? 'Cerrar sesión' : ''">
                        <i class="fas fa-sign-out-alt w-5 text-center"></i>
                        <span x-show="!sidebarCollapsed">Cerrar sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            {{-- Top Bar --}}
            <header class="bg-white border-b border-gray-100 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button @@click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-olive-600 p-1">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <button @@click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)"
                            class="hidden lg:flex text-gray-400 hover:text-olive-600 p-1 transition-colors"
                            :title="sidebarCollapsed ? 'Expandir menú' : 'Colapsar menú'">
                            <i class="fas text-lg" :class="sidebarCollapsed ? 'fa-bars' : 'fa-xmark'"></i>
                        </button>
                        <h1 class="text-lg md:text-xl font-bold text-olive-900 font-serif">Mi Restaurante</h1>
                    </div>
                    <div class="flex items-center gap-2 md:gap-3">
                        {{-- Dark Mode Toggle --}}
                        <button @@click="toggleDark()"
                            class="relative w-10 h-6 rounded-full transition-colors duration-300 focus:outline-none"
                            :class="dark ? 'bg-olive-600' : 'bg-gray-200'"
                            :title="dark ? 'Modo claro' : 'Modo oscuro'">
                            <span class="absolute left-0.5 top-0.5 w-5 h-5 rounded-full bg-white shadow-sm flex items-center justify-center transition-transform duration-300 text-xs"
                                  :class="dark ? 'translate-x-4' : ''">
                                <i :class="dark ? 'fas fa-moon text-olive-600' : 'fas fa-sun text-amber-500'"></i>
                            </span>
                        </button>

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
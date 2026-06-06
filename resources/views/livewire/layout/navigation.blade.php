<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <span class="text-xl font-bold text-red-700">Solemia</span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        Dashboard
                    </x-nav-link>

                    @can('gestionar_mesas')
                        <x-nav-link :href="route('pos.tables')" :active="request()->routeIs('pos.*')" wire:navigate>
                            POS / Sala
                        </x-nav-link>
                    @endcan

                    @can('ver_kds')
                        <x-nav-link :href="route('kitchen.index')" :active="request()->routeIs('kitchen.*')" wire:navigate>
                            Cocina (KDS)
                        </x-nav-link>
                    @endcan

                    @can('editar_menu')
                        <x-nav-link :href="route('menu.categories')" :active="request()->routeIs('menu.*')" wire:navigate>
                            Menú
                        </x-nav-link>
                    @endcan

                    @can('ver_caja')
                        <x-nav-link :href="route('cashier.index')" :active="request()->routeIs('cashier.*')" wire:navigate>
                            Caja
                        </x-nav-link>
                    @endcan

                    @can('ver_inventario')
                        <x-nav-link :href="route('inventory.index')" :active="request()->routeIs('inventory.*')" wire:navigate>
                            Inventario
                        </x-nav-link>
                    @endcan

                    @can('ver_reportes')
                        <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" wire:navigate>
                            Reportes
                        </x-nav-link>
                    @endcan

                    @can('gestionar_usuarios')
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" wire:navigate>
                            Usuarios
                        </x-nav-link>
                    @endcan

                    @can('configurar_sistema')
                        <x-nav-link :href="route('settings.index')" :active="request()->routeIs('settings.*')" wire:navigate>
                            Configuración
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center gap-2">
                                <span class="text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded-full">{{ auth()->user()->getRoleNames()->implode(', ') }}</span>
                                <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                            </div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>Dashboard</x-responsive-nav-link>
            @can('gestionar_mesas')
                <x-responsive-nav-link :href="route('pos.tables')" :active="request()->routeIs('pos.*')" wire:navigate>POS / Sala</x-responsive-nav-link>
            @endcan
            @can('ver_kds')
                <x-responsive-nav-link :href="route('kitchen.index')" :active="request()->routeIs('kitchen.*')" wire:navigate>Cocina (KDS)</x-responsive-nav-link>
            @endcan
            @can('editar_menu')
                <x-responsive-nav-link :href="route('menu.categories')" :active="request()->routeIs('menu.*')" wire:navigate>Menú</x-responsive-nav-link>
            @endcan
            @can('ver_caja')
                <x-responsive-nav-link :href="route('cashier.index')" :active="request()->routeIs('cashier.*')" wire:navigate>Caja</x-responsive-nav-link>
            @endcan
            @can('ver_inventario')
                <x-responsive-nav-link :href="route('inventory.index')" :active="request()->routeIs('inventory.*')" wire:navigate>Inventario</x-responsive-nav-link>
            @endcan
            @can('ver_reportes')
                <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" wire:navigate>Reportes</x-responsive-nav-link>
            @endcan
            @can('gestionar_usuarios')
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" wire:navigate>Usuarios</x-responsive-nav-link>
            @endcan
            @can('configurar_sistema')
                <x-responsive-nav-link :href="route('settings.index')" :active="request()->routeIs('settings.*')" wire:navigate>Configuración</x-responsive-nav-link>
            @endcan
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>{{ __('Profile') }}</x-responsive-nav-link>
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>{{ __('Log Out') }}</x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>

<x-app-layout>
    <div class="flex flex-col h-full">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-olive-900">Inventario</h2>
                <p class="text-sm text-gray-500">Control de insumos, recetas, proveedores y compras</p>
            </div>
        </div>

        {{-- Sub-navigation --}}
        <div class="flex gap-1 mb-6 overflow-x-auto">
            <a href="{{ route('inventory.ingredients') }}"
                class="px-4 py-2 text-sm rounded-lg font-medium transition-all whitespace-nowrap
                {{ request()->routeIs('inventory.ingredients') ? 'bg-olive-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                <i class="fas fa-carrot mr-1.5"></i>Ingredientes
            </a>
            <a href="{{ route('inventory.recipes') }}"
                class="px-4 py-2 text-sm rounded-lg font-medium transition-all whitespace-nowrap
                {{ request()->routeIs('inventory.recipes') ? 'bg-olive-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                <i class="fas fa-book-open mr-1.5"></i>Recetas
            </a>
            <a href="{{ route('inventory.suppliers') }}"
                class="px-4 py-2 text-sm rounded-lg font-medium transition-all whitespace-nowrap
                {{ request()->routeIs('inventory.suppliers') ? 'bg-olive-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                <i class="fas fa-truck mr-1.5"></i>Proveedores
            </a>
            <a href="{{ route('inventory.purchases') }}"
                class="px-4 py-2 text-sm rounded-lg font-medium transition-all whitespace-nowrap
                {{ request()->routeIs('inventory.purchases') ? 'bg-olive-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                <i class="fas fa-shopping-cart mr-1.5"></i>Compras
            </a>
        </div>

        {{-- Sub-navigation content is rendered by individual Livewire components --}}
        <div class="flex-1 min-h-0">
            @yield('inventory-content')
        </div>
    </div>
</x-app-layout>

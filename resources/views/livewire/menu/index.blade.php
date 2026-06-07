<div class="flex flex-col h-full">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-olive-900">Menú</h2>
            <p class="text-sm text-gray-500">Categorías y productos del restaurante</p>
        </div>
        @if($selectedCategoryId)
            <button wire:click="showProductCreate" class="btn-primary flex items-center gap-2 text-sm">
                <i class="fas fa-plus"></i> Producto
            </button>
        @endif
    </div>

    <div class="flex-1 flex flex-col lg:flex-row gap-4 min-h-0">
        {{-- Categories Sidebar --}}
        <div class="lg:w-72 shrink-0 flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="p-3 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">
                    <i class="fas fa-layer-group mr-1.5 text-olive-600"></i>Categorías
                </h3>
                <button wire:click="showCategoryCreate" class="text-xs text-olive-600 hover:text-olive-700">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-2 space-y-1">
                @forelse ($this->categories as $category)
                    <div wire:key="cat-{{ $category->id }}"
                        class="group flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm transition-all cursor-pointer
                        {{ $selectedCategoryId === $category->id ? 'bg-olive-100 text-olive-800 font-medium ring-1 ring-olive-200' : 'text-gray-600 hover:bg-gray-50' }}">
                        <div class="flex items-center gap-2 flex-1 min-w-0" wire:click="selectCategory({{ $category->id }})">
                            @if($category->image_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($category->image_path) }}" class="w-7 h-7 rounded-lg object-cover" />
                            @else
                                <div class="w-7 h-7 rounded-lg bg-olive-50 flex items-center justify-center">
                                    <i class="fas fa-folder text-olive-400 text-xs"></i>
                                </div>
                            @endif
                            <span class="flex-1 truncate">{{ $category->name }}</span>
                            <span class="text-xs text-gray-400 mr-1">{{ $category->products_count }}</span>
                        </div>
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button wire:click.stop="showCategoryEdit({{ $category->id }})"
                                class="p-1.5 rounded-lg text-gray-400 hover:text-olive-600 hover:bg-olive-50 transition-colors" title="Editar">
                                <i class="fas fa-pen text-xs"></i>
                            </button>
                            <button x-data="{ confirmed: false }"
                                wire:click.stop="removeCategory({{ $category->id }})"
                                x-on:click.capture="
                                    if (!confirmed) {
                                        $event.preventDefault();
                                        $event.stopPropagation();
                                        Swal.fire({ title: '¿Eliminar categoría?', text: 'Los productos se moverán a otra categoría.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar'
                                        }).then((r) => {
                                            if (r.isConfirmed) {
                                                confirmed = true;
                                                $el.click();
                                                confirmed = false;
                                            }
                                        });
                                    }
                                "
                                class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Eliminar">
                                <i class="fas fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-400 text-xs">
                        <i class="fas fa-folder-open text-lg mb-1 block"></i>
                        Sin categorías
                    </div>
                @endforelse
            </div>

            {{-- Category Form --}}
            @if($showCategoryForm)
                <div class="p-3 border-t border-gray-100">
                    <form wire:submit="saveCategory" class="space-y-2">
                        <x-input-label value="{{ $editingCategory ? 'Editar' : 'Nueva' }} categoría" class="text-xs" />
                        <x-text-input wire:model="categoryName" class="input-field w-full text-sm" placeholder="Nombre" />
                        <x-input-error :messages="$errors->get('categoryName')" />
                        <div>
                            <x-input-label value="Imagen" class="text-xs" />
                            <input type="file" wire:model="categoryImage" accept="image/*"
                                class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-olive-50 file:text-olive-700 hover:file:bg-olive-100" />
                            <div wire:loading wire:target="categoryImage" class="text-xs text-olive-600 mt-1">Subiendo...</div>
                        </div>
                        <div class="flex gap-1">
                            <button type="submit" class="btn-primary text-xs flex-1">{{ $editingCategory ? 'Actualizar' : 'Crear' }}</button>
                            <button type="button" wire:click="resetCategoryForm" class="btn-secondary text-xs">Cancelar</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        {{-- Products Area --}}
        <div class="flex-1 flex flex-col min-h-0">
            @if(!$selectedCategoryId)
                <div class="flex-1 flex flex-col items-center justify-center text-gray-400 bg-white rounded-xl border border-gray-200">
                    <i class="fas fa-hand-pointer text-4xl mb-3"></i>
                    <p class="text-sm">Selecciona una categoría</p>
                </div>
            @else
                {{-- Product Form --}}
                @if($showProductForm)
                    <div class="card mb-4">
                        <div class="p-4 md:p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-olive-100 flex items-center justify-center">
                                    <i class="fas fa-utensil-spoon text-olive-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $editingProduct ? 'Editar Producto' : 'Nuevo Producto' }}</h3>
                                    <p class="text-xs text-gray-500">en {{ $this->categories->firstWhere('id', $selectedCategoryId)?->name }}</p>
                                </div>
                            </div>
                            <form wire:submit="saveProduct" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label value="Nombre *" />
                                    <x-text-input wire:model="productName" class="input-field w-full" placeholder="Ej: Pizza Margherita" />
                                    <x-input-error :messages="$errors->get('productName')" />
                                </div>
                                <div>
                                    <x-input-label value="Precio base *" />
                                    <x-text-input wire:model="productPrice" type="number" step="0.01" class="input-field w-full" placeholder="0.00" />
                                    <x-input-error :messages="$errors->get('productPrice')" />
                                </div>
                                <div>
                                    <x-input-label value="SKU" />
                                    <x-text-input wire:model="productSku" class="input-field w-full" placeholder="OPC-001" />
                                </div>
                                <div>
                                    <x-input-label value="Área de cocina" />
                                    <select wire:model="productKitchenArea" class="input-field w-full rounded-lg border-gray-200">
                                        <option value="cocina">Cocina</option>
                                        <option value="parrilla">Parrilla</option>
                                        <option value="horno">Horno</option>
                                        <option value="frio">Frío</option>
                                        <option value="postres">Postres</option>
                                        <option value="bebidas">Bebidas</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label value="Tiempo prep. (min)" />
                                    <x-text-input wire:model="productPrepTime" type="number" min="0" class="input-field w-full" />
                                </div>
                                <div>
                                    <x-input-label value="Imagen" />
                                    <input type="file" wire:model="productImage" accept="image/*"
                                        class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-olive-50 file:text-olive-700 hover:file:bg-olive-100" />
                                    <div wire:loading wire:target="productImage" class="text-xs text-olive-600 mt-1">Subiendo...</div>
                                    @if($editingProduct && $editingProduct->image_path)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($editingProduct->image_path) }}" class="mt-1 h-12 w-12 rounded object-cover" />
                                    @endif
                                </div>
                                <div class="md:col-span-2 lg:col-span-3">
                                    <x-input-label value="Descripción" />
                                    <textarea wire:model="productDescription" class="input-field w-full rounded-lg border-gray-200 resize-none h-16" placeholder="Opcional"></textarea>
                                </div>
                                <div class="md:col-span-2 lg:col-span-3 flex flex-wrap gap-4">
                                    <label class="flex items-center gap-2 text-sm text-gray-600">
                                        <input type="checkbox" wire:model="productActive" class="rounded border-gray-300 text-olive-600" />
                                        Activo
                                    </label>
                                    <label class="flex items-center gap-2 text-sm text-gray-600">
                                        <input type="checkbox" wire:model="productAvailable" class="rounded border-gray-300 text-olive-600" />
                                        Disponible
                                    </label>
                                    <label class="flex items-center gap-2 text-sm text-gray-600">
                                        <input type="checkbox" wire:model="productDineIn" class="rounded border-gray-300 text-olive-600" />
                                        En sala
                                    </label>
                                    <label class="flex items-center gap-2 text-sm text-gray-600">
                                        <input type="checkbox" wire:model="productTakeaway" class="rounded border-gray-300 text-olive-600" />
                                        Para llevar
                                    </label>
                                    <label class="flex items-center gap-2 text-sm text-gray-600">
                                        <input type="checkbox" wire:model="productDelivery" class="rounded border-gray-300 text-olive-600" />
                                        Delivery
                                    </label>
                                </div>
                                <div class="md:col-span-2 lg:col-span-3 flex justify-end gap-2">
                                    <button type="button" wire:click="resetProductForm" class="btn-secondary">Cancelar</button>
                                    <button type="submit" class="btn-primary">{{ $editingProduct ? 'Actualizar' : 'Crear' }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- Product List --}}
                <div class="flex-1 overflow-y-auto">
                    @if ($this->products->isEmpty() && !$showProductForm)
                        <div class="flex flex-col items-center justify-center h-full text-gray-400 bg-white rounded-xl border border-gray-200">
                            <i class="fas fa-utensils text-4xl mb-3"></i>
                            <p class="text-sm mb-3">No hay productos en esta categoría</p>
                            <button wire:click="showProductCreate" class="btn-primary text-sm">
                                <i class="fas fa-plus"></i> Agregar producto
                            </button>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                            @foreach ($this->products as $product)
                                <div class="bg-white rounded-xl border border-gray-200 hover:shadow-md transition-shadow group overflow-hidden">
                                    {{-- Product Image --}}
                                    <div class="h-28 bg-gradient-to-br from-olive-50 to-cream flex items-center justify-center overflow-hidden">
                                        @if($product->image_path)
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($product->image_path) }}" class="w-full h-full object-cover" />
                                        @else
                                            <i class="fas fa-utensil-spoon text-3xl text-olive-300"></i>
                                        @endif
                                    </div>
                                    <div class="p-3">
                                        <div class="flex items-start justify-between mb-1">
                                            <h4 class="text-sm font-semibold text-gray-900">{{ $product->name }}</h4>
                                            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity shrink-0">
                                                <button wire:click="showProductEdit({{ $product->id }})" class="p-1 text-gray-400 hover:text-olive-600">
                                                    <i class="fas fa-pen text-xs"></i>
                                                </button>
                                                <button x-data="{ confirmed: false }"
                                                    wire:click.stop="deleteProduct({{ $product->id }})"
                                                    x-on:click.capture="
                                                        if (!confirmed) {
                                                            $event.preventDefault();
                                                            $event.stopPropagation();
                                                            Swal.fire({ title: '¿Eliminar producto?', text: 'Esta acción no se puede deshacer.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar'
                                                            }).then((r) => {
                                                                if (r.isConfirmed) {
                                                                    confirmed = true;
                                                                    $el.click();
                                                                    confirmed = false;
                                                                }
                                                            });
                                                        }
                                                    "
                                                    class="p-1 text-gray-400 hover:text-red-500">
                                                    <i class="fas fa-trash-can text-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @if($product->description)
                                            <p class="text-xs text-gray-400 mb-2 line-clamp-1">{{ $product->description }}</p>
                                        @endif
                                        <div class="flex items-center justify-between">
                                            <span class="text-base font-bold text-olive-700">${{ number_format($product->base_price, 2) }}</span>
                                            <div class="flex items-center gap-1.5">
                                                @if(!$product->is_active)
                                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-100 text-gray-500">Inactivo</span>
                                                @endif
                                                @if(!$product->is_available)
                                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-red-50 text-red-600">No disp.</span>
                                                @endif
                                                <span class="text-[10px] text-gray-400">{{ $product->prep_time_minutes }}'</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

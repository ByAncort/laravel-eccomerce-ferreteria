{{-- resources/views/items/edit.blade.php --}}
<x-app-layout>
    <div class="bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 lg:py-12">
            
            {{-- Header --}}
            <div class="mb-6 sm:mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl sm:text-4xl text-gray-900 dark:text-white">Editar Producto</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $item->name }}</p>
                </div>
                <a href="{{ route('items.index') }}" 
                   class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-lg transition">
                    ← Volver
                </a>
            </div>

            {{-- Form --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl max-w-2xl mx-auto">
                <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl text-gray-900 dark:text-white">Información del Producto</h2>
                </div>
                
                <form action="{{ route('items.update', $item->id) }}" method="POST" class="p-4 sm:p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                            Nombre del producto *
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               required
                               value="{{ old('name', $item->name) }}" 
                               placeholder="Ej. Taladro Percutor 600W"
                               class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition {{ $errors->has('name') ? 'border-red-500 dark:border-red-500' : '' }}">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="code" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                Código SKU
                            </label>
                            <input type="text" 
                                   name="code" 
                                   id="code"
                                   value="{{ old('code', $item->code) }}" 
                                   placeholder="HER20240001"
                                   class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition {{ $errors->has('code') ? 'border-red-500 dark:border-red-500' : '' }}">
                            @error('code')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                Categoría *
                            </label>
                            <select name="category" 
                                    id="category" 
                                    required
                                    class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                                <option value="">Seleccionar</option>
                                <option value="herramientas_electricas" {{ old('category', $item->category) == 'herramientas_electricas' ? 'selected' : '' }}>Herramientas Eléctricas</option>
                                <option value="herramientas_manuales" {{ old('category', $item->category) == 'herramientas_manuales' ? 'selected' : '' }}>Herramientas Manuales</option>
                                <option value="seguridad" {{ old('category', $item->category) == 'seguridad' ? 'selected' : '' }}>Equipos de Seguridad</option>
                                <option value="jardineria" {{ old('category', $item->category) == 'jardineria' ? 'selected' : '' }}>Jardinería</option>
                                <option value="construccion" {{ old('category', $item->category) == 'construccion' ? 'selected' : '' }}>Construcción</option>
                                <option value="plomeria" {{ old('category', $item->category) == 'plomeria' ? 'selected' : '' }}>Plomería</option>
                                <option value="electricidad" {{ old('category', $item->category) == 'electricidad' ? 'selected' : '' }}>Electricidad</option>
                                <option value="pintura" {{ old('category', $item->category) == 'pintura' ? 'selected' : '' }}>Pintura</option>
                                <option value="ferreteria_general" {{ old('category', $item->category) == 'ferreteria_general' ? 'selected' : '' }}>Ferretería General</option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="brand" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                Marca
                            </label>
                            <input type="text" 
                                   name="brand" 
                                   id="brand"
                                   value="{{ old('brand', $item->brand) }}" 
                                   placeholder="Ej. Bosch, Black+Decker"
                                   class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                        </div>
                        <div>
                            <label for="model" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                Modelo
                            </label>
                            <input type="text" 
                                   name="model" 
                                   id="model"
                                   value="{{ old('model', $item->model) }}" 
                                   placeholder="Ej. GSB 600 RE"
                                   class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                            Descripción
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="3"
                                  placeholder="Características y detalles del producto..."
                                  class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">{{ old('description', $item->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="selling_price" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                Precio venta *
                            </label>
                            <input type="number" 
                                   name="selling_price" 
                                   id="selling_price" 
                                   required
                                   step="0.01"
                                   min="0"
                                   value="{{ old('selling_price', $item->selling_price) }}" 
                                   placeholder="0.00"
                                   class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition {{ $errors->has('selling_price') ? 'border-red-500 dark:border-red-500' : '' }}">
                            @error('selling_price')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="cost_price" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                Precio costo
                            </label>
                            <input type="number" 
                                   name="cost_price" 
                                   id="cost_price"
                                   step="0.01"
                                   min="0"
                                   value="{{ old('cost_price', $item->cost_price) }}" 
                                   placeholder="0.00"
                                   class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                        </div>
                    </div>
                    
                    <div>
                        <label for="vendor_id" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                            Proveedor
                        </label>
                        <select name="vendor_id" 
                                id="vendor_id" 
                                class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                            <option value="">Seleccionar proveedor</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('vendor_id', $item->vendor_id) == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" 
                                   name="featured" 
                                   value="1"
                                   {{ old('featured', $item->featured) ? 'checked' : '' }}
                                   class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:bg-gray-700">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Producto destacado</span>
                        </label>
                        
                        <label class="flex items-center gap-2">
                            <input type="checkbox" 
                                   name="status" 
                                   value="active"
                                   {{ old('status', $item->status) == 'active' ? 'checked' : '' }}
                                   class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:bg-gray-700">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Activo</span>
                        </label>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="submit" 
                                class="flex-1 px-4 py-2.5 bg-gray-900 dark:bg-gray-700 hover:bg-gray-800 dark:hover:bg-gray-600 text-white text-sm font-semibold rounded-lg transition active:scale-[0.99]">
                            Actualizar Producto
                        </button>
                        
                        <form action="{{ route('items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este producto?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition active:scale-[0.99]">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
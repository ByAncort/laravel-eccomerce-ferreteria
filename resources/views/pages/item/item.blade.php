{{-- resources/views/items/index.blade.php --}}
<x-app-layout>
    <div class="bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 lg:py-12">

            {{-- Header --}}
            <div class="mb-6 sm:mb-8">
                <h1 class="text-3xl sm:text-4xl text-gray-900 dark:text-white">Catálogo de Productos</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gestiona el inventario de herramientas y productos</p>
            </div>

            {{-- Alerts --}}
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-700 dark:text-green-400 text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M20 6L9 17l-5-5" />
                </svg>
                {{ session('success') }}
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-[1fr_400px] gap-6 lg:gap-8 items-start">

                {{-- Table --}}
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="relative w-full sm:max-w-xs">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8" />
                                <line x1="21" y1="21" x2="16.65" y2="16.65" />
                            </svg>
                            <input type="text" id="search-input" placeholder="Buscar por código, nombre o categoría…" class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                        </div>

                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <select id="category-filter" class="text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                                <option value="">Todas las categorías</option>
                                <option value="herramientas_electricas">Herramientas Eléctricas</option>
                                <option value="herramientas_manuales">Herramientas Manuales</option>
                                <option value="seguridad">Equipos de Seguridad</option>
                                <option value="jardineria">Jardinería</option>
                            </select>

                            <span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                Total: <strong class="text-gray-900 dark:text-white">{{ $items->total() }}</strong> productos
                            </span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                                    <th class="text-left px-4 sm:px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Producto</th>
                                    <th class="text-left px-4 sm:px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Código</th>
                                    <th class="text-left px-4 sm:px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nombre</th>
                                    <th class="text-left px-4 sm:px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                                    <th class="text-left px-4 sm:px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="table-body" class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse($items as $item)
                                <tr class="item-row hover:bg-gray-50 dark:hover:bg-gray-700/50 transition" data-name="{{ strtolower($item->name) }}" data-code="{{ strtolower($item->code) }}" data-category="{{ $item->category }}" data-brand="{{ strtolower($item->brand ?? '') }}">

                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900 dark:to-blue-800 flex items-center justify-center text-blue-700 dark:text-blue-300 font-bold text-xs flex-shrink-0">
                                                @if($item->main_image)
                                                <img src="{{ Storage::url($item->main_image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover rounded-lg">
                                                @else
                                                {{ substr($item->name, 0, 2) }}
                                                @endif
                                            </div>

                                        </div>
                                    </td>

                                    <td class="px-4 sm:px-6 py-4">
                                        <span class="font-mono text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                            {{ $item->code }}
                                        </span>
                                    </td>

                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="text-gray-600 dark:text-gray-300">{{ $item->name }}</div>
                                    </td>




                                    <td class="px-4 sm:px-6 py-4">
                                        @if($item->status === 'active')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-600 dark:bg-green-400"></span>
                                            Activo
                                        </span>
                                        @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-gray-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-600 dark:bg-gray-400"></span>
                                            Inactivo
                                        </span>
                                        @endif

                                        @if($item->featured)
                                        <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400">
                                            Destacado
                                        </span>
                                        @endif
                                    </td>

                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="flex items-center gap-1.5">
                                            @if($item->status != 'inactive')
                                            <a href="{{ route('items.edit', $item->id) }}">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                </svg>
                                            </a>



                                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este producto?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 transition" title="Eliminar">
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M3 6h18" />
                                                        <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2" />
                                                        <line x1="10" y1="11" x2="10" y2="17" />
                                                        <line x1="14" y1="11" x2="14" y2="17" />
                                                    </svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-4 sm:px-6 py-12">
                                        <div class="text-center text-gray-500 dark:text-gray-400">
                                            <div class="text-4xl mb-3">🔨</div>
                                            <p class="text-sm">No hay productos registrados aún.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div id="no-results" class="hidden text-center py-12 text-gray-500 dark:text-gray-400">
                            <div class="text-4xl mb-3">🔍</div>
                            <p class="text-sm">Sin resultados para tu búsqueda.</p>
                        </div>
                    </div>

                    @if($items->hasPages())
                    <div class="px-4 sm:px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-500 dark:text-gray-400">
                        <span>Página {{ $items->currentPage() }} de {{ $items->lastPage() }}</span>
                        <div class="flex gap-1">
                            {{ $items->links() }}
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Form Sidebar --}}
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl sticky top-6">
                    <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl text-gray-900 dark:text-white">Nuevo Producto</h2>
                    </div>

                    <form action="{{ route('items.store') }}" method="POST" class="p-4 sm:p-6 space-y-4">
                        @csrf

                        <div>
                            <label for="name" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                Nombre del producto *
                            </label>
                            <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="Ej. Taladro Percutor 600W" class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition {{ $errors->has('name') ? 'border-red-500 dark:border-red-500' : '' }}">
                            @error('name')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="code" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                    Código SKU *
                                </label>
                                <input type="text" name="code" id="code" value="{{ old('code') }}" placeholder="HER20240001" class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition {{ $errors->has('code') ? 'border-red-500 dark:border-red-500' : '' }}">
                                @error('code')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="category" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                    Categoría *
                                </label>
                                <select name="category" id="category" required class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                                    <option value="">Seleccionar</option>
                                    <option value="herramientas_electricas" {{ old('category') == 'herramientas_electricas' ? 'selected' : '' }}>Herramientas Eléctricas</option>
                                    <option value="herramientas_manuales" {{ old('category') == 'herramientas_manuales' ? 'selected' : '' }}>Herramientas Manuales</option>
                                    <option value="seguridad" {{ old('category') == 'seguridad' ? 'selected' : '' }}>Equipos de Seguridad</option>
                                    <option value="jardineria" {{ old('category') == 'jardineria' ? 'selected' : '' }}>Jardinería</option>
                                    <option value="construccion" {{ old('category') == 'construccion' ? 'selected' : '' }}>Construcción</option>
                                    <option value="plomeria" {{ old('category') == 'plomeria' ? 'selected' : '' }}>Plomería</option>
                                    <option value="electricidad" {{ old('category') == 'electricidad' ? 'selected' : '' }}>Electricidad</option>
                                    <option value="pintura" {{ old('category') == 'pintura' ? 'selected' : '' }}>Pintura</option>
                                    <option value="ferreteria_general" {{ old('category') == 'ferreteria_general' ? 'selected' : '' }}>Ferretería General</option>
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
                                <input type="text" name="brand" id="brand" value="{{ old('brand') }}" placeholder="Ej. Bosch, Black+Decker" class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                            </div>
                            <div>
                                <label for="model" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                    Modelo
                                </label>
                                <input type="text" name="model" id="model" value="{{ old('model') }}" placeholder="Ej. GSB 600 RE" class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                Descripción
                            </label>
                            <textarea name="description" id="description" rows="3" placeholder="Características y detalles del producto..." class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">{{ old('description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="selling_price" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                    Precio venta *
                                </label>
                                <input type="number" name="selling_price" id="selling_price" required step="0.01" min="0" value="{{ old('selling_price') }}" placeholder="0.00" class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition {{ $errors->has('selling_price') ? 'border-red-500 dark:border-red-500' : '' }}">
                                @error('selling_price')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="cost_price" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                    Precio costo
                                </label>
                                <input type="number" name="cost_price" id="cost_price" step="0.01" min="0" value="{{ old('cost_price') }}" placeholder="0.00" class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                            </div>
                        </div>


                        <div>
                            <label for="vendor_id" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                Proveedor
                            </label>
                            <select name="vendor_id" id="vendor_id" class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                                <option value="">Seleccionar proveedor</option>
                                @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:bg-gray-700">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Producto destacado</span>
                            </label>

                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="status" value="active" {{ old('status', 'active') == 'active' ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:bg-gray-700">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Activo</span>
                            </label>
                        </div>

                        <button type="submit" class="w-full mt-2 px-4 py-2.5 bg-gray-900 dark:bg-gray-700 hover:bg-gray-800 dark:hover:bg-gray-600 text-white text-sm font-semibold rounded-lg transition active:scale-[0.99]">
                            Guardar Producto
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const categoryFilter = document.getElementById('category-filter');
        const rows = document.querySelectorAll('.item-row');
        const noResults = document.getElementById('no-results');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const category = categoryFilter.value;
            let visible = 0;

            rows.forEach(row => {
                const matchesSearch = !searchTerm ||
                    row.dataset.name.includes(searchTerm) ||
                    row.dataset.code.includes(searchTerm) ||
                    row.dataset.brand.includes(searchTerm);

                const matchesCategory = !category || row.dataset.category === category;

                const match = matchesSearch && matchesCategory;

                row.classList.toggle('hidden', !match);
                if (match) visible++;
            });

            noResults.classList.toggle('hidden', visible > 0 || rows.length === 0);
        }

        searchInput.addEventListener('input', filterTable);
        categoryFilter.addEventListener('change', filterTable);

    </script>
</x-app-layout>

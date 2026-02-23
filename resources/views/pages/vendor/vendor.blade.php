<x-app-layout>
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 lg:py-12">

            {{-- Header --}}
            <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl sm:text-4xl text-gray-900 dark:text-white">Proveedores</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gestiona y registra los proveedores de tu negocio</p>
                </div>
                <button
                    onclick="openModal()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 text-sm font-semibold rounded-lg transition active:scale-[0.99] whitespace-nowrap">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Nuevo Proveedor
                </button>
            </div>

            {{-- Alerts --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-700 dark:text-green-400 text-sm font-medium flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M20 6L9 17l-5-5"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="relative w-full sm:max-w-xs">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input
                            type="text"
                            id="cr-search-input"
                            placeholder="Buscar por nombre, email o ciudad…"
                            class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition"
                        >
                    </div>
                    <span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                        Total: <strong class="text-gray-900 dark:text-white">{{ $vendors->total() }}</strong> proveedores
                    </span>
                </div>
                

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                                <th class="text-left px-4 sm:px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Proveedor</th>
                                <th class="text-left px-4 sm:px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Teléfono</th>
                                <th class="text-left px-4 sm:px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ciudad</th>
                                <th class="text-left px-4 sm:px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                                <th class="text-left px-4 sm:px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="cr-tbody" class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($vendors as $vendor)
                                <tr class="cr-row hover:bg-gray-50 dark:hover:bg-gray-700/50 transition"
                                    data-name="{{ strtolower($vendor->name) }}"
                                    data-email="{{ strtolower($vendor->email) }}"
                                    data-city="{{ strtolower($vendor->city ?? '') }}">
                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-200 to-blue-300 dark:from-blue-800 dark:to-blue-600 flex items-center justify-center font-bold text-xs text-blue-700 dark:text-blue-200 uppercase flex-shrink-0">
                                                {{ substr($vendor->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">{{ $vendor->name }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $vendor->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-gray-600 dark:text-gray-300">{{ $vendor->phone ?? '—' }}</td>
                                    <td class="px-4 sm:px-6 py-4 text-gray-600 dark:text-gray-300">{{ $vendor->city ?? '—' }}</td>
                                    <td class="px-4 sm:px-6 py-4">
                                        @if($vendor->status === 'active')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-600 dark:bg-green-400"></span>
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-600 dark:bg-red-400"></span>
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="flex items-center gap-1.5">
                                            <a href="{{ route('vendors.edit', $vendor->id) }}"
                                               class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 dark:hover:text-blue-400 transition"
                                               title="Editar">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('vendors.destroy', $vendor->id) }}"
                                                  onsubmit="return confirm('¿Eliminar a {{ addslashes($vendor->name) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:border-red-200 dark:hover:border-red-800 hover:text-red-600 dark:hover:text-red-400 transition"
                                                        title="Eliminar">
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <polyline points="3 6 5 6 21 6"/>
                                                        <path d="M19 6l-1 14H6L5 6"/>
                                                        <path d="M10 11v6M14 11v6"/>
                                                        <path d="M9 6V4h6v2"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 sm:px-6 py-12">
                                        <div class="text-center text-gray-500 dark:text-gray-400">
                                            <div class="text-4xl mb-3">👥</div>
                                            <p class="text-sm">No hay proveedores registrados aún.</p>
                                            <button onclick="openModal()" class="mt-3 text-sm text-blue-600 dark:text-blue-400 hover:underline">Agregar el primero</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div id="cr-no-results" class="hidden text-center py-12 text-gray-500 dark:text-gray-400">
                        <div class="text-4xl mb-3">🔍</div>
                        <p class="text-sm">Sin resultados para tu búsqueda.</p>
                    </div>
                </div>

                @if($vendors->hasPages())
                    <div class="px-4 sm:px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-500 dark:text-gray-400">
                        <span>Página {{ $vendors->currentPage() }} de {{ $vendors->lastPage() }}</span>
                        <div class="flex gap-1">
                            {{ $vendors->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ===================== MODAL ===================== --}}
    <div id="vendor-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
        {{-- Backdrop --}}
        <div
            id="modal-backdrop"
            onclick="closeModal()"
            class="absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-200">
        </div>

        {{-- Panel --}}
        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div
                id="modal-panel"
                class="relative w-full max-w-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl translate-y-4 opacity-0 transition-all duration-200 max-h-[90vh] flex flex-col">

                {{-- Modal Header --}}
                <div class="flex items-center justify-between p-5 sm:p-6 border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Nuevo Proveedor</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Completa los campos para registrar un proveedor</p>
                    </div>
                    <button onclick="closeModal()" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body (scrollable) --}}
                <div class="overflow-y-auto flex-1">
                    <form action="{{ route('vendors.store') }}" method="POST" id="vendor-form" class="p-5 sm:p-6 space-y-4">
                        @csrf

                        <div>
                            <label for="name" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                Nombre completo *
                            </label>
                            <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="Ej. María González"
                                class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition {{ $errors->has('name') ? 'border-red-500 dark:border-red-500' : '' }}">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="run" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                RUN *
                            </label>
                            <input type="text" name="run" id="run" required value="{{ old('run') }}" placeholder="Ej. 12.345.678-9"
                                class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition {{ $errors->has('run') ? 'border-red-500 dark:border-red-500' : '' }}">
                            @error('run')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                Email *
                            </label>
                            <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="correo@ejemplo.com"
                                class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition {{ $errors->has('email') ? 'border-red-500 dark:border-red-500' : '' }}">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                Teléfono
                            </label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+56 9 1234 5678"
                                class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition {{ $errors->has('phone') ? 'border-red-500 dark:border-red-500' : '' }}">
                            @error('phone')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                Estado *
                            </label>
                            <select name="status" id="status" required
                                class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="address" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                Dirección
                            </label>
                            <input type="text" name="address" id="address" value="{{ old('address') }}" placeholder="Av. Providencia 123"
                                class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                            @error('address')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="city" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                    Ciudad
                                </label>
                                <input type="text" name="city" id="city" value="{{ old('city') }}" placeholder="Santiago"
                                    class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                                @error('city')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="country" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                    País
                                </label>
                                <input type="text" name="country" id="country" value="{{ old('country') }}" placeholder="Chile"
                                    class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                                @error('country')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-end gap-3 px-5 sm:px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex-shrink-0">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Cancelar
                    </button>
                    <button type="submit" form="vendor-form"
                        class="px-5 py-2 bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 text-sm font-semibold rounded-lg transition active:scale-[0.99]">
                        Guardar Proveedor
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ── Modal ──────────────────────────────────────────────
        const modal = document.getElementById('vendor-modal');
        const backdrop = document.getElementById('modal-backdrop');
        const panel = document.getElementById('modal-panel');

        function openModal() {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            requestAnimationFrame(() => {
                backdrop.style.opacity = '1';
                panel.style.opacity = '1';
                panel.style.transform = 'translateY(0)';
            });
        }

        function closeModal() {
            backdrop.style.opacity = '0';
            panel.style.opacity = '0';
            panel.style.transform = 'translateY(1rem)';
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 200);
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });

        // Auto-open modal if there are validation errors
        @if($errors->any())
        document.addEventListener('DOMContentLoaded', openModal);
        @endif

        // ── Table filter ───────────────────────────────────────
        const searchInput = document.getElementById('cr-search-input');
        const rows = document.querySelectorAll('.cr-row');
        const noResults = document.getElementById('cr-no-results');

        searchInput.addEventListener('input', () => {
            const q = searchInput.value.toLowerCase().trim();
            let visible = 0;

            rows.forEach(row => {
                const match = !q
                    || row.dataset.name.includes(q)
                    || row.dataset.email.includes(q)
                    || row.dataset.city.includes(q);

                row.classList.toggle('hidden', !match);
                if (match) visible++;
            });

            noResults.classList.toggle('hidden', visible > 0 || rows.length === 0);
        });
    </script>
</x-app-layout>
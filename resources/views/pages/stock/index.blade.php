{{-- resources/views/pages/stock/index.blade.php --}}
<x-app-layout>
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 lg:py-12">

            {{-- Header --}}
            <div class="mb-6 sm:mb-8">
                <h1 class="text-3xl sm:text-4xl text-gray-900 dark:text-white">Inventario / Stock</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    El stock se actualiza automáticamente desde ventas, cotizaciones y órdenes de compra.
                    Usa <strong>Ajustar</strong> para correcciones manuales.
                </p>
            </div>

            {{-- Alerts --}}
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-700 dark:text-green-400 text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-red-700 dark:text-red-400 text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                {{ session('error') }}
            </div>
            @endif

            {{-- KPIs --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Productos con Stock</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalItems }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Unidades Totales</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($totalUnits) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 border {{ $lowStockCount > 0 ? 'border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/10' : 'border-gray-200 dark:border-gray-700' }} rounded-xl p-5">
                    <p class="text-xs font-semibold {{ $lowStockCount > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }} uppercase tracking-wider">Alertas Stock Bajo</p>
                    <p class="text-3xl font-bold {{ $lowStockCount > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }} mt-1">{{ $lowStockCount }}</p>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">

                <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <div class="relative w-full sm:max-w-xs">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" id="search-input" placeholder="Buscar producto…"
                            class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                    </div>
                    <div class="flex items-center gap-3 ml-auto">
                        <a href="{{ route('stock.index', ['low_stock' => request('low_stock') ? null : 1]) }}"
                            class="inline-flex items-center gap-1.5 px-3 py-2 text-sm rounded-lg border {{ request('low_stock') ? 'bg-red-50 dark:bg-red-900/20 border-red-300 dark:border-red-700 text-red-700 dark:text-red-400' : 'border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }} transition">
                            <span class="w-2 h-2 rounded-full bg-red-500 {{ $lowStockCount > 0 ? 'animate-pulse' : '' }}"></span>
                            Stock bajo{{ $lowStockCount > 0 ? " ($lowStockCount)" : '' }}
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                                <th class="text-left px-4 sm:px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Producto</th>
                                <th class="text-left px-4 sm:px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Ubicación</th>
                                <th class="text-center px-4 sm:px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stock</th>
                                <th class="text-center px-4 sm:px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Mínimo</th>
                                <th class="text-center px-4 sm:px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                                <th class="text-right px-4 sm:px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="table-body" class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($stocks as $stock)
                            @php $item = $stock->item; $low = $stock->isBelowMinStock(); @endphp
                            <tr class="stock-row hover:bg-gray-50 dark:hover:bg-gray-700/50 transition"
                                data-name="{{ strtolower($item->name ?? '') }}"
                                data-code="{{ strtolower($item->code ?? '') }}">

                                <td class="px-4 sm:px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900 dark:to-blue-800 flex items-center justify-center text-blue-700 dark:text-blue-300 font-bold text-xs flex-shrink-0 overflow-hidden">
                                            @if($item->main_image)
                                                <img src="{{ Storage::url($item->main_image?? '' ) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                            @else
                                                {{ substr($item->name, 0, 2) }}
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-medium text-gray-900 dark:text-white truncate">{{ $item->name }}</p>
                                            <p class="text-xs text-gray-400 font-mono">{{ $item->code ?? '—' }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 sm:px-6 py-4 text-gray-400 text-xs hidden md:table-cell">
                                    {{ $stock->location ?: '—' }}
                                </td>

                                <td class="px-4 sm:px-6 py-4 text-center">
                                    <span class="text-xl font-bold {{ $low ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                        {{ $stock->quantity }}
                                    </span>
                                </td>

                                <td class="px-4 sm:px-6 py-4 text-center text-gray-400 hidden sm:table-cell">
                                    {{ $stock->min_stock }}
                                </td>

                                <td class="px-4 sm:px-6 py-4 text-center">
                                    @if($low)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> Bajo
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Normal
                                    </span>
                                    @endif
                                </td>

                                <td class="px-4 sm:px-6 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button onclick="openStockModal(
                                                {{ $item->id }},
                                                '{{ addslashes($item->name) }}',
                                                {{ $stock->quantity }},
                                                {{ $stock->min_stock }},
                                                '{{ addslashes($stock->location ?? '') }}',
                                                '{{ addslashes($stock->notes ?? '') }}'
                                            )"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-700 transition">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                            Ajustar
                                        </button>
                                    
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-14 text-center">
                                    <div class="text-5xl mb-3">📦</div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">No hay stock registrado aún.</p>
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

                @if($stocks->hasPages())
                <div class="px-4 sm:px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                    <span>Página {{ $stocks->currentPage() }} de {{ $stocks->lastPage() }}</span>
                    <div>{{ $stocks->links() }}</div>
                </div>
                @endif
            </div>

            <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg flex gap-3">
                <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <p class="text-xs text-blue-700 dark:text-blue-300">
                    Los movimientos se generan automáticamente al confirmar <strong>ventas</strong>, aprobar <strong>cotizaciones</strong> y recibir <strong>órdenes de compra</strong>.
                    Desde <strong>Historial</strong> puedes eliminar movimientos erróneos (solo manuales).
                </p>
            </div>
        </div>
    </div>

    {{-- ============================================================
         MODAL: Ajustar Stock
    ============================================================ --}}
    <div id="stock-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
        <div id="stock-backdrop" onclick="closeStockModal()"
            class="absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-200"></div>

        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div id="stock-panel"
                class="relative w-full max-w-md bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl translate-y-4 opacity-0 transition-all duration-200">

                <div class="flex items-center justify-between p-5 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Ajustar Stock</h2>
                        <p id="stock-item-name" class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate max-w-xs"></p>
                    </div>
                    <button onclick="closeStockModal()"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>

                <form id="stock-form" method="POST" class="p-5 space-y-4">
                    @csrf

                    {{-- Stock actual badge --}}
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Stock actual</span>
                        <span id="stock-current" class="text-2xl font-bold text-gray-900 dark:text-white">0</span>
                    </div>

                    {{-- Tipo --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                            Tipo de ajuste *
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" data-type="entrada" class="type-btn px-3 py-2.5 text-sm font-medium rounded-lg border transition text-center">↑ Entrada</button>
                            <button type="button" data-type="salida"  class="type-btn px-3 py-2.5 text-sm font-medium rounded-lg border transition text-center">↓ Salida</button>
                        </div>
                        <input type="hidden" name="type" id="selected-type" value="entrada">
                    </div>

                    {{-- Cantidad --}}
                    <div>
                        <label for="modal-quantity" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                            <span id="qty-label">Cantidad a agregar *</span>
                        </label>
                        <input type="number" name="quantity" id="modal-quantity" required min="0" placeholder="0"
                            class="w-full px-3 py-2 text-lg font-bold text-center bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                        <div id="result-preview" class="hidden mt-2 text-center text-xs text-gray-400">
                            Stock resultante: <strong id="result-value" class="text-gray-700 dark:text-gray-300">—</strong>
                        </div>
                    </div>

                    {{-- Motivo --}}
                    <div>
                        <label for="modal-reason" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Motivo</label>
                        <input type="text" name="reason" id="modal-reason"
                            placeholder="Ej. Inventario físico, merma, devolución…"
                            class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                    </div>

             

                    {{-- Config colapsable --}}
                    <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
                        <button type="button" id="toggle-config"
                            class="text-xs text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1.5">
                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" id="config-icon"><path d="M12 5v14M5 12h14"/></svg>
                            Configurar stock mínimo y ubicación
                        </button>
                        <div id="config-section" class="hidden mt-3 space-y-3">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="modal-min-stock" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Stock mínimo</label>
                                    <input type="number" name="min_stock" id="modal-min-stock" min="0" placeholder="0"
                                        class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                                </div>
                                <div>
                                    <label for="modal-location" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Ubicación</label>
                                    <input type="text" name="location" id="modal-location" placeholder="Bodega A"
                                        class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                                </div>
                            </div>
                            <div>
                                <label for="modal-notes" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Notas</label>
                                <input type="text" name="notes" id="modal-notes" placeholder="Observaciones"
                                    class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-1">
                        <button type="button" onclick="closeStockModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-5 py-2 bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 text-sm font-semibold rounded-lg transition active:scale-[0.99]">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // ── Search ──────────────────────────────────────────────
        document.getElementById('search-input').addEventListener('input', function () {
            const term = this.value.toLowerCase().trim();
            let visible = 0;
            document.querySelectorAll('.stock-row').forEach(row => {
                const match = !term || row.dataset.name.includes(term) || row.dataset.code.includes(term);
                row.classList.toggle('hidden', !match);
                if (match) visible++;
            });
            document.getElementById('no-results').classList.toggle('hidden', visible > 0);
        });

        // ── Modal open/close ────────────────────────────────────
        const modal    = document.getElementById('stock-modal');
        const backdrop = document.getElementById('stock-backdrop');
        const panel    = document.getElementById('stock-panel');

        let _currentStock = 0;

        function openStockModal(itemId, itemName, currentStock, minStock, location, notes) {
            _currentStock = currentStock;

            document.getElementById('stock-item-name').textContent = itemName;
            document.getElementById('stock-current').textContent   = currentStock;
            document.getElementById('stock-form').action = `/stock/${itemId}/move`;
            document.getElementById('modal-quantity').value  = '';
            document.getElementById('modal-reason').value    = '';
            document.getElementById('modal-min-stock').value = minStock;
            document.getElementById('modal-location').value  = location;
            document.getElementById('modal-notes').value     = notes;
            document.getElementById('result-preview').classList.add('hidden');
            document.getElementById('config-section').classList.add('hidden');
            document.getElementById('config-icon').setAttribute('d', 'M12 5v14M5 12h14');

            selectType('entrada');

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            requestAnimationFrame(() => {
                backdrop.style.opacity = '1';
                panel.style.opacity    = '1';
                panel.style.transform  = 'translateY(0)';
            });
        }

        function closeStockModal() {
            backdrop.style.opacity = '0';
            panel.style.opacity    = '0';
            panel.style.transform  = 'translateY(1rem)';
            setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 200);
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeStockModal(); });

        // ── Type buttons ────────────────────────────────────────
        const typeStyles = {
            entrada: { on: 'bg-green-50 dark:bg-green-900/30 border-green-400 dark:border-green-600 text-green-700 dark:text-green-300', off: 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-50' },
            salida:  { on: 'bg-red-50 dark:bg-red-900/30 border-red-400 dark:border-red-600 text-red-700 dark:text-red-300',             off: 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-50' },
            ajuste:  { on: 'bg-blue-50 dark:bg-blue-900/30 border-blue-400 dark:border-blue-600 text-blue-700 dark:text-blue-300',       off: 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-50' },
        };
        const qtyLabels = { entrada: 'Cantidad a agregar *', salida: 'Cantidad a retirar *', ajuste: 'Stock final deseado *' };

        function selectType(type) {
            document.getElementById('selected-type').value = type;
            document.getElementById('qty-label').textContent = qtyLabels[type];
            document.querySelectorAll('.type-btn').forEach(btn => {
                const t = btn.dataset.type;
                btn.className = `type-btn px-3 py-2.5 text-sm font-medium rounded-lg border transition text-center ${t === type ? typeStyles[t].on : typeStyles[t].off}`;
            });
            updatePreview();
        }

        document.querySelectorAll('.type-btn').forEach(btn => btn.addEventListener('click', () => selectType(btn.dataset.type)));

        function updatePreview() {
            const qty  = parseInt(document.getElementById('modal-quantity').value) || 0;
            const type = document.getElementById('selected-type').value;
            const preview  = document.getElementById('result-preview');
            const resultEl = document.getElementById('result-value');
            if (!qty) { preview.classList.add('hidden'); return; }
            let result = type === 'entrada' ? _currentStock + qty : type === 'salida' ? _currentStock - qty : qty;
            resultEl.textContent = result;
            resultEl.className = result < 0 ? 'text-red-600 dark:text-red-400 font-bold' : 'text-gray-700 dark:text-gray-300 font-bold';
            preview.classList.remove('hidden');
        }

        document.getElementById('modal-quantity').addEventListener('input', updatePreview);

        // ── Toggle config section ───────────────────────────────
        document.getElementById('toggle-config').addEventListener('click', () => {
            const section = document.getElementById('config-section');
            const icon    = document.getElementById('config-icon');
            const isOpen  = !section.classList.contains('hidden');
            section.classList.toggle('hidden', isOpen);
            icon.setAttribute('d', isOpen ? 'M12 5v14M5 12h14' : 'M5 12h14');
        });
    </script>
</x-app-layout>
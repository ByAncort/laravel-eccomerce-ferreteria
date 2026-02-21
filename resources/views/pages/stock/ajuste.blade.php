{{-- resources/views/pages/stock/_modal-ajuste.blade.php
     Incluir con @include('pages.stock._modal-ajuste') en cualquier vista que necesite ajustar stock --}}

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

                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Stock actual</span>
                    <span id="stock-current" class="text-2xl font-bold text-gray-900 dark:text-white">0</span>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Tipo de ajuste *</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" data-type="entrada" class="type-btn px-3 py-2.5 text-sm font-medium rounded-lg border transition text-center">↑ Entrada</button>
                        <button type="button" data-type="salida"  class="type-btn px-3 py-2.5 text-sm font-medium rounded-lg border transition text-center">↓ Salida</button>
                        <button type="button" data-type="ajuste"  class="type-btn px-3 py-2.5 text-sm font-medium rounded-lg border transition text-center">⇄ Directo</button>
                    </div>
                    <input type="hidden" name="type" id="selected-type" value="entrada">
                </div>

                <div>
                    <label for="modal-quantity" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                        <span id="qty-label">Cantidad a agregar *</span>
                    </label>
                    <input type="number" name="quantity" id="modal-quantity" required min="0" placeholder="0"
                        class="w-full px-3 py-2 text-lg font-bold text-center bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                    <div id="result-preview" class="hidden mt-2 text-center text-xs text-gray-400">
                        Stock resultante: <strong id="result-value" class="font-bold">—</strong>
                    </div>
                </div>

                <div>
                    <label for="modal-reason" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Motivo</label>
                    <input type="text" name="reason" id="modal-reason" placeholder="Ej. Inventario físico, merma, devolución…"
                        class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                </div>

                <div>
                    <label for="modal-reference" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Referencia</label>
                    <input type="text" name="reference" id="modal-reference" placeholder="Ej. Factura #001, OC-2024"
                        class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                </div>

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
    const _modal    = document.getElementById('stock-modal');
    const _backdrop = document.getElementById('stock-backdrop');
    const _panel    = document.getElementById('stock-panel');
    let _currentStock = 0;

    function openStockModal(itemId, itemName, currentStock, minStock, location, notes) {
        _currentStock = currentStock;
        document.getElementById('stock-item-name').textContent = itemName;
        document.getElementById('stock-current').textContent   = currentStock;
        document.getElementById('stock-form').action = `/stock/${itemId}/move`;
        document.getElementById('modal-quantity').value  = '';
        document.getElementById('modal-reason').value    = '';
        document.getElementById('modal-reference').value = '';
        document.getElementById('modal-min-stock').value = minStock;
        document.getElementById('modal-location').value  = location;
        document.getElementById('modal-notes').value     = notes;
        document.getElementById('result-preview').classList.add('hidden');
        document.getElementById('config-section').classList.add('hidden');
        document.getElementById('config-icon').setAttribute('d', 'M12 5v14M5 12h14');
        selectType('entrada');
        _modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        requestAnimationFrame(() => {
            _backdrop.style.opacity = '1';
            _panel.style.opacity    = '1';
            _panel.style.transform  = 'translateY(0)';
        });
    }

    function closeStockModal() {
        _backdrop.style.opacity = '0';
        _panel.style.opacity    = '0';
        _panel.style.transform  = 'translateY(1rem)';
        setTimeout(() => { _modal.classList.add('hidden'); document.body.style.overflow = ''; }, 200);
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeStockModal(); });

    const _typeStyles = {
        entrada: { on: 'bg-green-50 dark:bg-green-900/30 border-green-400 dark:border-green-600 text-green-700 dark:text-green-300', off: 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-50' },
        salida:  { on: 'bg-red-50 dark:bg-red-900/30 border-red-400 dark:border-red-600 text-red-700 dark:text-red-300',             off: 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-50' },
        ajuste:  { on: 'bg-blue-50 dark:bg-blue-900/30 border-blue-400 dark:border-blue-600 text-blue-700 dark:text-blue-300',       off: 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-50' },
    };
    const _qtyLabels = { entrada: 'Cantidad a agregar *', salida: 'Cantidad a retirar *', ajuste: 'Stock final deseado *' };

    function selectType(type) {
        document.getElementById('selected-type').value = type;
        document.getElementById('qty-label').textContent = _qtyLabels[type];
        document.querySelectorAll('.type-btn').forEach(btn => {
            const t = btn.dataset.type;
            btn.className = `type-btn px-3 py-2.5 text-sm font-medium rounded-lg border transition text-center ${t === type ? _typeStyles[t].on : _typeStyles[t].off}`;
        });
        _updatePreview();
    }

    document.querySelectorAll('.type-btn').forEach(btn => btn.addEventListener('click', () => selectType(btn.dataset.type)));

    function _updatePreview() {
        const qty  = parseInt(document.getElementById('modal-quantity').value) || 0;
        const type = document.getElementById('selected-type').value;
        const preview = document.getElementById('result-preview');
        const el = document.getElementById('result-value');
        if (!qty) { preview.classList.add('hidden'); return; }
        const result = type === 'entrada' ? _currentStock + qty : type === 'salida' ? _currentStock - qty : qty;
        el.textContent = result;
        el.className = result < 0 ? 'text-red-600 dark:text-red-400 font-bold' : 'text-gray-700 dark:text-gray-300 font-bold';
        preview.classList.remove('hidden');
    }

    document.getElementById('modal-quantity').addEventListener('input', _updatePreview);

    document.getElementById('toggle-config').addEventListener('click', () => {
        const s = document.getElementById('config-section');
        const i = document.getElementById('config-icon');
        const open = !s.classList.contains('hidden');
        s.classList.toggle('hidden', open);
        i.setAttribute('d', open ? 'M12 5v14M5 12h14' : 'M5 12h14');
    });
</script>
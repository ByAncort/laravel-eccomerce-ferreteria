{{-- ============================================================
     MODAL: Agregar Stock
     Abre con: openAddStockModal()
     Cierra con: closeAddStockModal()
============================================================ --}}
{{-- resources/views/pages/stock/stock.blade.php --}}

{{-- Backdrop --}}
<div
    id="addStockBackdrop"
    class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm hidden transition-opacity duration-200"
    onclick="closeAddStockModal()"
></div>

{{-- Modal --}}
<div
    id="addStockModal"
    role="dialog"
    aria-modal="true"
    aria-labelledby="addStockTitle"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none"
>
    <div
        id="addStockPanel"
        class="pointer-events-auto w-full max-w-lg bg-white rounded-2xl shadow-2xl
               transform transition-all duration-200 scale-95 opacity-0"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                    </svg>
                </div>
                <div>
                    <h2 id="addStockTitle" class="text-base font-semibold text-gray-900 leading-tight">
                        Agregar Stock
                    </h2>
                    <p class="text-xs text-gray-400">Registra stock inicial para un producto</p>
                </div>
            </div>
            <button
                onclick="closeAddStockModal()"
                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400
                       hover:text-gray-600 hover:bg-gray-100 transition-colors"
                aria-label="Cerrar"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form
            id="addStockForm"
            method="POST"
            action="{{ route('stock.store') }}"
            class="px-6 py-5 space-y-5"
            novalidate
        >
            @csrf

            {{-- ── Búsqueda de producto ── --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Producto <span class="text-red-500">*</span>
                </label>

                {{-- Search input --}}
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg id="searchIcon" class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                        </svg>
                        <svg id="searchSpinner" class="w-4 h-4 text-blue-500 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                    </div>
                    <input
                        type="text"
                        id="productSearch"
                        placeholder="Buscar por nombre o código..."
                        autocomplete="off"
                        class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                               placeholder-gray-400 transition"
                    />
                </div>

                {{-- Dropdown results --}}
                <div
                    id="productDropdown"
                    class="hidden mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-lg
                           max-h-52 overflow-y-auto z-10 relative"
                >
                    {{-- Results injected by JS --}}
                </div>

                {{-- Selected product card --}}
                <div id="selectedProductCard" class="hidden mt-2 flex items-center gap-3 p-3 bg-blue-50 border border-blue-200 rounded-xl">
                    <div id="selectedProductThumb"
                         class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center
                                text-blue-700 font-bold text-xs overflow-hidden flex-shrink-0">
                    </div>
                    <div class="min-w-0 flex-1">
                        <p id="selectedProductName" class="text-sm font-semibold text-gray-900 truncate"></p>
                        <p id="selectedProductCode" class="text-xs text-gray-500"></p>
                    </div>
                    <button
                        type="button"
                        onclick="clearProductSelection()"
                        class="text-xs text-blue-600 hover:text-blue-800 font-medium flex-shrink-0"
                    >
                        Cambiar
                    </button>
                </div>

                {{-- Hidden field with item_id --}}
                <input type="hidden" name="item_id" id="selectedItemId" />
            </div>

            {{-- ── Cantidad + Ubicación ── --}}
            <div class="grid grid-cols-2 gap-4">
                {{-- Cantidad --}}
                <div>
                    <label for="addStockQty" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Cantidad inicial <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden
                                focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent transition">
                        <button
                            type="button"
                            onclick="stepQty(-1)"
                            class="px-3 py-2.5 text-gray-500 hover:bg-gray-50 hover:text-gray-700
                                   text-lg leading-none transition select-none"
                        >−</button>
                        <input
                            type="number"
                            id="addStockQty"
                            name="quantity"
                            value="1"
                            min="1"
                            required
                            class="w-full text-center text-sm font-medium text-gray-900 py-2.5
                                   focus:outline-none bg-transparent"
                        />
                        <button
                            type="button"
                            onclick="stepQty(1)"
                            class="px-3 py-2.5 text-gray-500 hover:bg-gray-50 hover:text-gray-700
                                   text-lg leading-none transition select-none"
                        >+</button>
                    </div>
                </div>

                {{-- Ubicación --}}
                <div>
                    <label for="addStockLocation" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Ubicación
                    </label>
                    <input
                        type="text"
                        id="addStockLocation"
                        name="location"
                        placeholder="Ej: Bodega A, Estante 2"
                        class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-xl
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                               placeholder-gray-400 transition"
                    />
                </div>
            </div>

            {{-- ── Validation notice ── --}}
            <div id="addStockError" class="hidden flex items-center gap-2 text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl px-3 py-2.5">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10A8 8 0 1 1 2 10a8 8 0 0 1 16 0zm-8-3a1 1 0 0 0-1 1v2a1 1 0 1 0 2 0V8a1 1 0 0 0-1-1zm0 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" clip-rule="evenodd"/>
                </svg>
                <span id="addStockErrorText"></span>
            </div>

            {{-- ── Footer actions ── --}}
            <div class="flex items-center justify-end gap-3 pt-1">
                <button
                    type="button"
                    onclick="closeAddStockModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800
                           hover:bg-gray-100 rounded-xl transition"
                >
                    Cancelar
                </button>
                <button
                    type="submit"
                    id="addStockSubmit"
                    class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-xl
                           hover:bg-blue-700 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed
                           transition-all flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Agregar Stock
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ============================================================
     Script: lógica del modal
============================================================ --}}
<script>
(function () {
    // ── Open / Close ──────────────────────────────────────────
    window.openAddStockModal = function () {
        const backdrop = document.getElementById('addStockBackdrop');
        const panel    = document.getElementById('addStockPanel');

        backdrop.classList.remove('hidden');
        requestAnimationFrame(() => {
            panel.classList.remove('scale-95', 'opacity-0');
            panel.classList.add('scale-100', 'opacity-100');
        });

        setTimeout(() => document.getElementById('productSearch').focus(), 150);
    };

    window.closeAddStockModal = function () {
        const backdrop = document.getElementById('addStockBackdrop');
        const panel    = document.getElementById('addStockPanel');

        panel.classList.add('scale-95', 'opacity-0');
        panel.classList.remove('scale-100', 'opacity-100');

        setTimeout(() => {
            backdrop.classList.add('hidden');
            resetAddStockModal();
        }, 180);
    };

    function resetAddStockModal() {
        clearProductSelection();
        document.getElementById('addStockQty').value      = 1;
        document.getElementById('addStockLocation').value = '';
        hideError();
    }

    // ── Quantity stepper ──────────────────────────────────────
    window.stepQty = function (delta) {
        const input = document.getElementById('addStockQty');
        const val   = parseInt(input.value) || 0;
        input.value = Math.max(1, val + delta);
    };

    // ── Product search ────────────────────────────────────────
    let searchTimeout = null;

    document.getElementById('productSearch').addEventListener('input', function () {
        const query = this.value.trim();
        clearTimeout(searchTimeout);

        if (query.length < 2) {
            hideDropdown();
            return;
        }

        setSearchLoading(true);
        searchTimeout = setTimeout(() => fetchProducts(query), 300);
    });

    function fetchProducts(query) {
        // Usa tu propio endpoint — ajusta la URL si es diferente
        const url = `/stock/items/search?q=${encodeURIComponent(query)}`;

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            setSearchLoading(false);
            renderDropdown(data.items ?? data);
        })
        .catch(() => {
            setSearchLoading(false);
            renderDropdown([]);
        });
    }

    function renderDropdown(items) {
        const dropdown = document.getElementById('productDropdown');

        if (!items.length) {
            dropdown.innerHTML = `
                <div class="px-4 py-6 text-center text-sm text-gray-400">
                    Sin resultados
                </div>`;
            dropdown.classList.remove('hidden');
            return;
        }

        dropdown.innerHTML = items.map(item => `
            <button
                type="button"
                class="w-full flex items-center gap-3 px-3 py-2.5 hover:bg-blue-50
                       text-left transition-colors border-b border-gray-100 last:border-0"
                onclick="selectProduct(${item.id}, '${escHtml(item.name)}', '${escHtml(item.code ?? '')}', '${escHtml(item.main_image ?? '')}')"
            >
                ${item.main_image
                    ? `<img src="${escHtml(item.main_image)}" class="w-8 h-8 rounded-lg object-cover flex-shrink-0" />`
                    : `<div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center
                                  text-gray-500 font-bold text-xs flex-shrink-0">
                            ${escHtml(item.name.substring(0, 2).toUpperCase())}
                       </div>`
                }
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">${escHtml(item.name)}</p>
                    ${item.code ? `<p class="text-xs text-gray-400">${escHtml(item.code)}</p>` : ''}
                </div>
            </button>
        `).join('');

        dropdown.classList.remove('hidden');
    }

    window.selectProduct = function (id, name, code, image) {
        document.getElementById('selectedItemId').value = id;

        // Actualiza card
        const thumb = document.getElementById('selectedProductThumb');
        if (image) {
            thumb.innerHTML = `<img src="${escHtml(image)}" class="w-full h-full object-cover" />`;
        } else {
            thumb.textContent = name.substring(0, 2).toUpperCase();
        }
        document.getElementById('selectedProductName').textContent = name;
        document.getElementById('selectedProductCode').textContent = code ? `Código: ${code}` : '';

        // Muestra card, oculta search+dropdown
        document.getElementById('selectedProductCard').classList.remove('hidden');
        document.getElementById('productSearch').classList.add('hidden');
        hideDropdown();
    };

    window.clearProductSelection = function () {
        document.getElementById('selectedItemId').value = '';
        document.getElementById('selectedProductCard').classList.add('hidden');
        document.getElementById('productSearch').classList.remove('hidden');
        document.getElementById('productSearch').value = '';
        document.getElementById('productSearch').focus();
        hideDropdown();
    };

    // ── Validation on submit ──────────────────────────────────
    document.getElementById('addStockForm').addEventListener('submit', function (e) {
        const itemId = document.getElementById('selectedItemId').value;
        const qty    = parseInt(document.getElementById('addStockQty').value);

        if (!itemId) {
            e.preventDefault();
            showError('Selecciona un producto antes de continuar.');
            return;
        }
        if (!qty || qty < 1) {
            e.preventDefault();
            showError('La cantidad debe ser al menos 1.');
            return;
        }

        // Estado de carga en botón
        const btn = document.getElementById('addStockSubmit');
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
            </svg>
            Guardando...`;
    });

    // ── Cerrar dropdown al hacer click fuera ─────────────────
    document.addEventListener('click', function (e) {
        if (!e.target.closest('#productSearch') && !e.target.closest('#productDropdown')) {
            hideDropdown();
        }
    });

    // ── Helpers ───────────────────────────────────────────────
    function hideDropdown() {
        document.getElementById('productDropdown').classList.add('hidden');
    }

    function setSearchLoading(loading) {
        document.getElementById('searchIcon').classList.toggle('hidden', loading);
        document.getElementById('searchSpinner').classList.toggle('hidden', !loading);
    }

    function showError(msg) {
        const el = document.getElementById('addStockError');
        document.getElementById('addStockErrorText').textContent = msg;
        el.classList.remove('hidden');
    }

    function hideError() {
        document.getElementById('addStockError').classList.add('hidden');
    }

    function escHtml(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    // Escape para click: reconstruye con data attrs en renderDropdown si tienes chars raros en nombres
})();
</script>
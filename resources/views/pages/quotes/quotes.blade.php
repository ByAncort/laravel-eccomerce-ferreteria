{{-- resources/views/pages/quotes/quotes.blade.php --}}

<x-app-layout>
<div class="bg-gray-50 dark:bg-gray-900 min-h-screen">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 lg:py-12">

    {{-- Header --}}
    <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">Cotizaciones</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Gestiona cotizaciones de clientes. Al aprobar, el stock se descuenta automáticamente.
            </p>
        </div>
        <button onclick="openModalAddQuote()"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 text-sm font-semibold rounded-lg transition active:scale-[0.99] whitespace-nowrap">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Nueva cotización
        </button>
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

    {{-- Stats rápidas --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
        @php
            $statuses = [
                ['label'=>'Total','value'=>$quotes->total(),'color'=>'text-gray-900 dark:text-white'],
                ['label'=>'Borradores','value'=>$quotes->getCollection()->where('status','draft')->count(),'color'=>'text-yellow-600 dark:text-yellow-400'],
                ['label'=>'Aprobadas','value'=>$quotes->getCollection()->where('status','approved')->count(),'color'=>'text-green-600 dark:text-green-400'],
                ['label'=>'Expiradas','value'=>$quotes->getCollection()->where('status','expired')->count(),'color'=>'text-red-600 dark:text-red-400'],
            ];
        @endphp
        @foreach($statuses as $s)
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">{{ $s['label'] }}</p>
            <p class="text-2xl font-bold mt-1 {{ $s['color'] }}">{{ $s['value'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Tabla --}}
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        @foreach(['N° Cotización','Cliente','Fecha entrega','Expira','Total','Estado','Acciones'] as $h)
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($quotes as $quote)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        {{-- Número --}}
                        <td class="px-5 py-3.5">
                            <span class="font-mono text-sm font-medium text-gray-900 dark:text-gray-100">{{ $quote->number }}</span>
                        </td>
                        {{-- Cliente --}}
                        <td class="px-5 py-3.5">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $quote->customer->name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $quote->customer->email }}</div>
                        </td>
                        {{-- Fecha entrega --}}
                        <td class="px-5 py-3.5 text-sm text-gray-600 dark:text-gray-300 whitespace-nowrap">
                            {{ $quote->delivery_date?->format('d/m/Y') ?? '—' }}
                        </td>
                        {{-- Expira --}}
                        <td class="px-5 py-3.5 text-sm whitespace-nowrap
                            {{ $quote->isExpired() ? 'text-red-600 dark:text-red-400 font-medium' : 'text-gray-600 dark:text-gray-300' }}">
                            {{ $quote->expiration_date?->format('d/m/Y') ?? '—' }}
                        </td>
                        {{-- Total --}}
                        <td class="px-5 py-3.5 text-sm font-semibold text-gray-900 dark:text-gray-100 whitespace-nowrap">
                            ${{ number_format($quote->total, 2) }}
                        </td>
                        {{-- Estado --}}
                        <td class="px-5 py-3.5">
                            @php
                                $badges = [
                                    'draft'    => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                    'sent'     => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                    'approved' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                    'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                    'expired'  => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                                ];
                                $labels = ['draft'=>'Borrador','sent'=>'Enviada','approved'=>'Aprobada','rejected'=>'Rechazada','expired'=>'Expirada'];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badges[$quote->status] ?? '' }}">
                                {{ $labels[$quote->status] ?? $quote->status }}
                            </span>
                        </td>
                        {{-- Acciones --}}
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2">
                                {{-- Aprobar --}}
                                @if($quote->status === 'draft')
                                <form action="{{ route('cotizaciones.approve', $quote->id) }}" method="POST"
                                      onsubmit="return confirm('¿Aprobar esta cotización y descontar stock?')">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 border border-green-200 dark:border-green-800 rounded-lg transition">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                                        Aprobar
                                    </button>
                                </form>
                                @endif
                                {{-- Eliminar --}}
                                @if($quote->status !== 'approved')
                                <form action="{{ route('cotizaciones.destroy', $quote->id) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar esta cotización?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 border border-red-200 dark:border-red-800 rounded-lg transition">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                                        Eliminar
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center">
                            <svg class="w-10 h-10 mx-auto text-gray-300 dark:text-gray-600 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M9 12h6M9 16h6M5 8h14M5 4h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                            </svg>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">No hay cotizaciones aún</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Crea una nueva usando el botón superior</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($quotes->hasPages())
        <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $quotes->links() }}
        </div>
        @endif
    </div>

</div>
</div>

{{-- ================================================================
     MODAL NUEVA COTIZACIÓN
     ================================================================ --}}
<div id="add-modal-quotes" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <div id="add-backdrop" onclick="closeAddModal()"
         class="absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-200"></div>

    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div id="add-panel"
             class="relative w-full max-w-4xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl translate-y-4 opacity-0 transition-all duration-200 max-h-[90vh] flex flex-col">

            {{-- Header modal --}}
            <div class="flex items-center justify-between p-5 sm:p-6 border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Nueva Cotización</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Completa los campos para registrar una cotización</p>
                </div>
                <button onclick="closeAddModal()" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            {{-- Body modal --}}
            <div class="overflow-y-auto flex-1 p-5 sm:p-6 space-y-5">
                <form id="quote-form" action="{{ route('cotizaciones.store') }}" method="POST">
                    @csrf

                    {{-- Datos principales --}}
                    <div class="bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-lg p-5 space-y-4">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Datos de la cotización</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Cliente --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Cliente *</label>
                                <select name="customer_id"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-violet-500 focus:border-violet-500 text-sm" required>
                                    <option value="">Selecciona un cliente</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Fechas --}}
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Fecha entrega</label>
                                    <input type="date" name="delivery_date"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-violet-500 focus:border-violet-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Expiración</label>
                                    <input type="date" name="expiration_date"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-violet-500 focus:border-violet-500 text-sm">
                                </div>
                            </div>
                        </div>

                        {{-- Notas --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Notas</label>
                            <textarea name="notes" rows="2" placeholder="Observaciones opcionales..."
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-violet-500 focus:border-violet-500 text-sm resize-none"></textarea>
                        </div>
                    </div>

                    {{-- Buscador / escáner --}}
                    <div class="bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-lg p-5">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-3">Agregar productos</h3>
                        <div class="flex gap-2">
                            <div class="flex-1 relative">
                                <input type="text" id="item-search"
                                    placeholder="Busca por nombre, código o escanea código de barras..."
                                    class="w-full px-3 py-2 pl-9 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-violet-500 focus:border-violet-500 text-sm"
                                    autocomplete="off">
                                <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>

                                {{-- Dropdown resultados --}}
                                <div id="search-results"
                                    class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg hidden max-h-52 overflow-y-auto">
                                </div>
                            </div>
                            <button type="button" onclick="addItemManual()"
                                class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white rounded-lg text-sm font-medium transition-colors whitespace-nowrap">
                                Agregar
                            </button>
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Escribe el nombre o escanea el código de barras para agregar rápidamente.</p>
                    </div>

                    {{-- Tabla de items --}}
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        @foreach(['Código','Producto','Cantidad','Precio unit.','Desc. %','Subtotal',''] as $h)
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">{{ $h }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody id="quote-lines" class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                    {{-- Filas inyectadas por JS --}}
                                </tbody>
                            </table>
                        </div>

                        {{-- Empty state tabla --}}
                        <div id="empty-lines" class="py-10 text-center bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                            <svg class="w-8 h-8 mx-auto text-gray-300 dark:text-gray-600 mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>
                            </svg>
                            <p class="text-sm text-gray-400 dark:text-gray-500">Sin productos. Usa el buscador para agregar.</p>
                        </div>

                        {{-- Totales --}}
                        <div class="bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 px-4 py-3 space-y-1.5">
                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                <span>Subtotal</span>
                                <span id="summary-subtotal">$0.00</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 items-center gap-4">
                                <span>Descuento global</span>
                                <div class="flex items-center gap-1">
                                    <span>$</span>
                                    <input type="number" name="discount" id="global-discount" value="0" min="0" step="0.01"
                                        oninput="updateTotals()"
                                        class="w-24 px-2 py-1 border border-gray-300 dark:border-gray-600 rounded text-sm text-right bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                </div>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                <span>IVA (19%)</span>
                                <span id="summary-tax">$0.00</span>
                            </div>
                            <div class="flex justify-between font-semibold text-gray-900 dark:text-gray-100 text-base pt-1.5 border-t border-gray-200 dark:border-gray-700">
                                <span>Total</span>
                                <span id="summary-total">$0.00</span>
                            </div>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end gap-3 pt-1">
                        <button type="button" onclick="closeAddModal()"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-5 py-2 bg-violet-600 hover:bg-violet-700 active:scale-[0.99] text-white rounded-lg text-sm font-semibold transition-all">
                            Generar Cotización
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- ================================================================
     JAVASCRIPT
     ================================================================ --}}
<script>
// ── Modal ───────────────────────────────────────────────────────────────────
const addModal    = document.getElementById('add-modal-quotes');
const addBackdrop = document.getElementById('add-backdrop');
const addPanel    = document.getElementById('add-panel');

function openModalAddQuote() {
    addModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => {
        addBackdrop.style.opacity = '1';
        addPanel.style.opacity    = '1';
        addPanel.style.transform  = 'translateY(0)';
    });
    document.getElementById('item-search').focus();
}

function closeAddModal() {
    addBackdrop.style.opacity = '0';
    addPanel.style.opacity    = '0';
    addPanel.style.transform  = 'translateY(1rem)';
    setTimeout(() => {
        addModal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 200);
}

// ── Catálogo de items (pasado desde Blade) ──────────────────────────────────
const catalog = {!! $itemsJson !!};


// ── Estado de líneas ────────────────────────────────────────────────────────
let lines       = [];   // [{id, code, name, price, quantity, discount}]
let selectedItem = null;

// ── Búsqueda ────────────────────────────────────────────────────────────────
const searchInput   = document.getElementById('item-search');
const searchResults = document.getElementById('search-results');

searchInput.addEventListener('input', function () {
    const q = this.value.trim().toLowerCase();
    selectedItem = null;

    if (!q) { searchResults.classList.add('hidden'); return; }

    const matches = catalog.filter(i =>
        i.name.toLowerCase().includes(q) || i.code.toLowerCase().includes(q)
    ).slice(0, 8);

    if (!matches.length) { searchResults.classList.add('hidden'); return; }

    searchResults.innerHTML = matches.map(i => `
        <button type="button" onclick="selectItem(${i.id})"
            class="w-full text-left px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex justify-between items-center gap-3">
            <span>
                <span class="block text-sm font-medium text-gray-900 dark:text-gray-100">${i.name}</span>
                <span class="block text-xs text-gray-500 dark:text-gray-400">${i.code || 'Sin código'}</span>
            </span>
            <span class="text-sm font-semibold text-violet-600 dark:text-violet-400 whitespace-nowrap">$${fmt(i.price)}</span>
        </button>
    `).join('');

    searchResults.classList.remove('hidden');
});

document.addEventListener('click', e => {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.classList.add('hidden');
    }
});

// Soporte escáner: detectar Enter (código de barras)
searchInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        if (selectedItem) { addItemManual(); return; }
        const code  = this.value.trim();
        const found = catalog.find(i => i.code === code);
        if (found) { selectItem(found.id); addItemManual(); }
    }
});

function selectItem(id) {
    selectedItem = catalog.find(i => i.id === id);
    searchInput.value = selectedItem ? selectedItem.name : '';
    searchResults.classList.add('hidden');
}

function addItemManual() {
    if (!selectedItem) return;
    addLine(selectedItem);
    selectedItem = null;
    searchInput.value = '';
}

// ── Líneas ───────────────────────────────────────────────────────────────────
function addLine(item) {
    const existing = lines.find(l => l.id === item.id);
    if (existing) {
        existing.quantity++;
        renderLines();
        return;
    }
    lines.push({ id: item.id, code: item.code, name: item.name, price: item.price, quantity: 1, discount: 0 });
    renderLines();
}

function removeLine(id) {
    lines = lines.filter(l => l.id !== id);
    renderLines();
}

function renderLines() {
    const tbody  = document.getElementById('quote-lines');
    const empty  = document.getElementById('empty-lines');

    empty.style.display = lines.length ? 'none' : '';

    if (!lines.length) { tbody.innerHTML = ''; updateTotals(); return; }

    tbody.innerHTML = lines.map((l, idx) => `
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
            <td class="px-4 py-2.5 text-xs font-mono text-gray-500 dark:text-gray-400 whitespace-nowrap">${l.code || '—'}</td>
            <td class="px-4 py-2.5 text-sm text-gray-900 dark:text-gray-100">${l.name}</td>

            {{-- Cantidad --}}
            <td class="px-4 py-2.5">
                <input type="number" name="items[${idx}][quantity]" value="${l.quantity}" min="0.01" step="0.01"
                    class="w-20 px-2 py-1 border border-gray-300 dark:border-gray-600 rounded text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                    oninput="updateLine(${l.id}, 'quantity', this.value)">
            </td>

            {{-- Precio --}}
            <td class="px-4 py-2.5">
                <input type="number" name="items[${idx}][unit_price]" value="${l.price}" min="0" step="0.01"
                    class="w-28 px-2 py-1 border border-gray-300 dark:border-gray-600 rounded text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                    oninput="updateLine(${l.id}, 'price', this.value)">
            </td>

            {{-- Descuento % --}}
            <td class="px-4 py-2.5">
                <input type="number" name="items[${idx}][discount]" value="${l.discount}" min="0" max="100" step="0.1"
                    class="w-20 px-2 py-1 border border-gray-300 dark:border-gray-600 rounded text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                    oninput="updateLine(${l.id}, 'discount', this.value)">
            </td>

            {{-- Subtotal --}}
            <td class="px-4 py-2.5 text-sm font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap">
                $${fmt(lineSubtotal(l))}
            </td>

            {{-- Inputs ocultos item_id --}}
            <td class="px-4 py-2.5">
                <input type="hidden" name="items[${idx}][item_id]" value="${l.id}">
                <button type="button" onclick="removeLine(${l.id})"
                    class="text-red-400 hover:text-red-600 dark:hover:text-red-300 transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/>
                    </svg>
                </button>
            </td>
        </tr>
    `).join('');

    updateTotals();
}

function updateLine(id, field, value) {
    const line = lines.find(l => l.id === id);
    if (!line) return;
    line[field] = parseFloat(value) || 0;
    updateTotals();
}

// ── Totales ───────────────────────────────────────────────────────────────────
function lineSubtotal(l) {
    const gross = l.quantity * l.price;
    return gross - (gross * l.discount / 100);
}

function updateTotals() {
    const subtotalRaw  = lines.reduce((s, l) => s + lineSubtotal(l), 0);
    const discount     = parseFloat(document.getElementById('global-discount').value) || 0;
    const subtotal     = subtotalRaw - discount;
    const tax          = subtotal * 0.19;
    const total        = subtotal + tax;

    document.getElementById('summary-subtotal').textContent = '$' + fmt(subtotalRaw);
    document.getElementById('summary-tax').textContent      = '$' + fmt(tax);
    document.getElementById('summary-total').textContent    = '$' + fmt(total);
}

function fmt(n) {
    return (parseFloat(n) || 0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}
</script>

</x-app-layout>
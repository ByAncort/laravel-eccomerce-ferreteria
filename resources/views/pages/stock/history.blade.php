{{-- resources/views/pages/stock/history.blade.php --}}
<x-app-layout>
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">

            {{-- Header --}}
            <div class="mb-6 flex items-center gap-4">
                <a href="{{ route('stock.index') }}"
                    class="w-9 h-9 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl text-gray-900 dark:text-white">Historial de Stock</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                        {{ $item->name }}
                        @if($item->code)· <span class="font-mono">{{ $item->code }}</span>@endif
                    </p>
                </div>
            </div>

            {{-- Alerts --}}
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-700 dark:text-green-400 text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                {{ session('success') }}
            </div>
            @endif

            {{-- Stock actual --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 mb-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stock Actual</p>
                    <p class="text-4xl font-bold mt-1 {{ $stock && $stock->isBelowMinStock() ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                        {{ $stock ? $stock->quantity : 0 }}
                    </p>
                </div>
                @if($stock)
                <div class="text-sm text-gray-500 dark:text-gray-400 space-y-0.5">
                    <p>Mínimo: <strong class="text-gray-700 dark:text-gray-300">{{ $stock->min_stock }}</strong></p>
                    @if($stock->location)<p>Ubicación: <strong class="text-gray-700 dark:text-gray-300">{{ $stock->location }}</strong></p>@endif
                </div>
                @endif

                {{-- Botón ajuste rápido desde historial también --}}
                <button onclick="openStockModal(
                        {{ $item->id }},
                        '{{ addslashes($item->name) }}',
                        {{ $stock ? $stock->quantity : 0 }},
                        {{ $stock ? $stock->min_stock : 0 }},
                        '{{ addslashes($stock->location ?? '') }}',
                        '{{ addslashes($stock->notes ?? '') }}'
                    )"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 text-sm font-semibold rounded-lg transition">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Ajustar Stock
                </button>
            </div>

            {{-- Movements --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Movimientos</h2>
                    <span class="text-xs text-gray-400 dark:text-gray-500">
                        Solo los movimientos manuales pueden eliminarse.
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                                <th class="text-left px-4 sm:px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fecha</th>
                                <th class="text-left px-4 sm:px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tipo</th>
                                <th class="text-center px-4 sm:px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cantidad</th>
                                <th class="text-center px-4 sm:px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Stock Post</th>
                                <th class="text-left px-4 sm:px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Motivo / Referencia</th>
                                <th class="text-left px-4 sm:px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">Origen</th>
                                <th class="px-4 sm:px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($movements as $m)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition {{ $m->source !== 'manual' ? 'opacity-90' : '' }}">

                                <td class="px-4 sm:px-6 py-3 whitespace-nowrap text-gray-500 dark:text-gray-400 text-xs">
                                    {{ $m->created_at->format('d/m/Y H:i') }}
                                </td>

                                <td class="px-4 sm:px-6 py-3">
                                    @if($m->type === 'entrada')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400">↑ Entrada</span>
                                    @elseif($m->type === 'salida')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400">↓ Salida</span>
                                    @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">⇄ Ajuste</span>
                                    @endif
                                </td>

                                <td class="px-4 sm:px-6 py-3 text-center font-semibold {{ $m->type === 'salida' ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                    {{ $m->type === 'salida' ? '-' : '+' }}{{ $m->quantity }}
                                </td>

                                <td class="px-4 sm:px-6 py-3 text-center font-medium text-gray-700 dark:text-gray-300 hidden sm:table-cell">
                                    {{ $m->stock_after }}
                                </td>

                                <td class="px-4 sm:px-6 py-3 hidden md:table-cell">
                                    @if($m->reason || $m->reference)
                                    <div class="text-xs text-gray-600 dark:text-gray-400">
                                        @if($m->reason)<p>{{ $m->reason }}</p>@endif
                                        @if($m->reference)<span class="font-mono bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">{{ $m->reference }}</span>@endif
                                    </div>
                                    @else
                                    <span class="text-gray-300 dark:text-gray-600">—</span>
                                    @endif
                                </td>

                                {{-- Origen (manual vs automático) --}}
                                <td class="px-4 sm:px-6 py-3 hidden lg:table-cell">
                                    @if(isset($m->source) && $m->source !== 'manual')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">
                                        {{ ucfirst($m->source) }}
                                    </span>
                                    @else
                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ $m->user?->name ?? 'Manual' }}
                                    </span>
                                    @endif
                                </td>

                                {{-- Eliminar (solo manuales) --}}
                                <td class="px-4 sm:px-6 py-3 text-right">
                                    @if(!isset($m->source) || $m->source === 'manual')
                                    <form action="{{ route('stock.movement.destroy', $m->id) }}" method="POST"
                                        onsubmit="return confirm('¿Eliminar este movimiento? El stock NO se recalculará automáticamente.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-7 h-7 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 hover:border-red-300 dark:hover:border-red-700 transition ml-auto"
                                            title="Eliminar movimiento">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-xs text-gray-300 dark:text-gray-600 pr-1" title="Generado automáticamente, no se puede eliminar">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center">
                                    <div class="text-3xl mb-2">📋</div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Sin movimientos registrados.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($movements->hasPages())
                <div class="px-4 sm:px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                    <span>Página {{ $movements->currentPage() }} de {{ $movements->lastPage() }}</span>
                    <div>{{ $movements->links() }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Reutilizar el mismo modal de ajuste --}}
    @include('pages.stock._modal-ajuste')
</x-app-layout>
<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        // Corregir la consulta - no necesitas hacer join manual si usas with()
        $query = Stock::with('item')  // Esto ya hace el join por la relación
            ->whereHas('item', function ($q) {
                $q->where('status', 'active');  // Filtrar por items activos
            });

        if ($request->boolean('low_stock')) {
            $query->whereColumn('quantity', '<=', 'min_stock');
        }

        // Quitamos el dd() que detiene la ejecución
        $stocks = $query->orderBy(
            Item::select('name')
                ->whereColumn('items.id', 'stock.item_id')
                ->limit(1)
        )->paginate(20);

        $totalItems = Stock::count();
        $lowStockCount = Stock::whereColumn('quantity', '<=', 'min_stock')->count();
        $totalUnits = Stock::sum('quantity');

        return view('pages.stock.index', compact('stocks', 'totalItems', 'lowStockCount', 'totalUnits'));
    }

    /**
     * Registrar movimiento manual.
     * También persiste min_stock y location si vienen en el form (sección colapsable).
     */
    public function move(Request $request, int $itemId)
    {
        $request->validate([
            'type'      => 'required|in:entrada,salida,ajuste',
            'quantity'  => 'required|integer|min:0',
            'reason'    => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:100',
            'min_stock' => 'nullable|integer|min:0',
            'location'  => 'nullable|string|max:100',
        ]);

        try {
            DB::transaction(function () use ($request, $itemId) {
                $stock = Stock::firstOrCreate(
                    ['item_id' => $itemId],
                    ['quantity' => 0, 'min_stock' => 0]
                );

                $qty = (int) $request->quantity;

                match ($request->type) {
                    'entrada' => $stock->quantity += $qty,
                    'salida'  => throw_if($stock->quantity < $qty, new \Exception('Stock insuficiente.'))
                              ?: ($stock->quantity -= $qty),
                    'ajuste'  => $stock->quantity = $qty,
                };

                if ($request->filled('min_stock')) $stock->min_stock = (int) $request->min_stock;
                if ($request->filled('location'))  $stock->location  = $request->location;

                $stock->save();

                StockMovement::create([
                    'item_id'     => $itemId,
                    'type'        => $request->type,
                    'quantity'    => $qty,
                    'stock_after' => $stock->quantity,
                    'reason'      => $request->reason,
                    'user_id'     => Auth::id(),
                ]);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Stock actualizado correctamente.');
    }

    /**
     * Eliminar un movimiento (todos son manuales en este módulo).
     * NOTA: el stock NO se recalcula — el usuario debe hacer un ajuste si es necesario.
     */
    public function destroyMovement(int $movementId)
    {
        StockMovement::findOrFail($movementId)->delete();

        return redirect()->back()->with('success', 'Movimiento eliminado. Verifica que el stock actual sea correcto.');
    }

    /**
     * Historial de movimientos de un producto.
     */
    public function history(int $itemId)
    {
        $item      = Item::findOrFail($itemId);
        $stock     = Stock::where('item_id', $itemId)->first();
        $movements = StockMovement::with('user')
            ->where('item_id', $itemId)
            ->latest()
            ->paginate(20);

        return view('pages.stock.history', compact('item', 'stock', 'movements'));
    }
}
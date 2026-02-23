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
        $query = Stock::with('item');

        if ($request->low_stock) {
            $query->whereColumn('quantity', '<', 'min_stock');
        }

        $stocks = $query->paginate(20);

        return view('pages.stock.index', [
            'stocks'       => $stocks,
            'items'        => Item::orderBy('name')->get(),
            'totalItems'   => Stock::count(),
            'totalUnits'   => Stock::sum('quantity'),
            'lowStockCount'=> Stock::whereColumn('quantity', '<', 'min_stock')->count(),
        ]);
    }

    // Agregar stock a un ítem nuevo
    public function store(Request $request)
    {
        $request->validate([
            'item_id'  => 'required|exists:items,id',
            'quantity' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $stock = Stock::firstOrCreate(
                ['item_id' => $request->item_id],
                ['quantity' => 0, 'min_stock' => 0]
            );

            $stock->quantity  += $request->quantity;
            $stock->min_stock  = $request->min_stock ?? $stock->min_stock;
            $stock->location   = $request->location ?? $stock->location;
            $stock->save();

            StockMovement::create([
                'item_id'    => $request->item_id,
                'type'       => 'entrada',
                'quantity'   => $request->quantity,
                'stock_after'=> $stock->quantity,
                'reason'     => $request->reason ?? 'Carga inicial',
                'source'     => 'manual',
                'user_id'    => auth()->id(),
            ]);
        });

        return redirect()->route('stock.index')->with('success', 'Stock agregado correctamente.');
    }
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
    public function destroyMovement(int $movementId)
    {
        StockMovement::findOrFail($movementId)->delete();

        return redirect()->back()->with('success', 'Movimiento eliminado. Verifica que el stock actual sea correcto.');
    }
     public function history(int $itemId)
    {
        $item      = Item::findOrFail($itemId);
        $stock     = Stock::where('item_id', $itemId)->first();
        $movements = StockMovement::with('user')
            ->where('item_id', $itemId)
            ->latest()
            ->paginate(20);
    }
    // Ajustar stock de un ítem existente
    public function adjust(Request $request, $itemId)
    {
        $request->validate([
            'type'     => 'required|in:entrada,salida,ajuste',
            'quantity' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $itemId) {
            $stock = Stock::where('item_id', $itemId)->firstOrFail();

            $qty = $request->quantity;

            if ($request->type === 'entrada') {
                $stock->quantity += $qty;
            } elseif ($request->type === 'salida') {
                $stock->quantity = max(0, $stock->quantity - $qty);
            } else { // ajuste
                $stock->quantity = $qty;
            }

            if ($request->filled('min_stock')) $stock->min_stock = $request->min_stock;
            if ($request->filled('location'))  $stock->location  = $request->location;
            if ($request->filled('notes'))     $stock->notes     = $request->notes;

            $stock->save();

            StockMovement::create([
                'item_id'    => $itemId,
                'type'       => $request->type,
                'quantity'   => $qty,
                'stock_after'=> $stock->quantity,
                'reason'     => $request->reason,
                'source'     => 'manual',
                'user_id'    => auth()->id(),
            ]);
        });

        return redirect()->route('stock.index')->with('success', 'Stock ajustado correctamente.');
    }
}
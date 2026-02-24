<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuotesController extends Controller
{
    public function index()
    {
    $items = Item::where('status', 'active')
        ->join('stock', 'items.id', '=', 'stock.item_id')
        ->where('stock.quantity', '>', 0)
        ->orderBy('name')
        ->get()
        ->map(function($i) {
            return [
                'id'    => $i->id,
                'code'  => $i->code ?? '',
                'name'  => $i->name,
                'price' => $i->selling_price,
            ];
        });

    return view('pages.quotes.quotes', [
        'quotes'    => Quote::with(['customer','user'])->latest()->paginate(20),
        'customers' => Customer::where('status','active')->orderBy('name')->get(),
        'items'     => $items,
        'itemsJson' => $items->toJson(), // Pasamos el JSON ya generado
    ]);
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'customer_id'     => 'required|exists:customers,id',
            'delivery_date'   => 'nullable|date',
            'expiration_date' => 'nullable|date|after_or_equal:today',
            'discount'        => 'nullable|numeric|min:0',
            'notes'           => 'nullable|string|max:1000',
            'items'           => 'required|array|min:1',
            'items.*.item_id'    => 'required|exists:items,id',
            'items.*.quantity'   => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount'   => 'nullable|numeric|min:0|max:100',
        ]);

        DB::transaction(function () use ($request) {
            $quote = Quote::create([
                'number'          => Quote::generateNumber(),
                'customer_id'     => $request->customer_id,
                'user_id'         => Auth::id(),
                'status'          => 'draft',
                'delivery_date'   => $request->delivery_date,
                'expiration_date' => $request->expiration_date,
                'discount'        => $request->discount ?? 0,
                'notes'           => $request->notes,
                'subtotal'        => 0,
                'tax'             => 0,
                'total'           => 0,
            ]);
            
            foreach ($request->items as $line) {
                $quoteItem = new QuoteItem([
                    'quote_id'   => $quote->id,
                    'item_id'    => $line['item_id'],
                    'quantity'   => $line['quantity'],
                    'unit_price' => $line['unit_price'],
                    'discount'   => $line['discount'] ?? 0,
                    'notes'      => $line['notes'] ?? null,
                ]);
                $quoteItem->computeSubtotal();
                $quoteItem->save();
            }

            // Recargar items para recalcular totales
            $quote->load('items');
            $quote->recalculate();
            $quote->save();
        });
        return redirect()->route('cotizaciones.index')->with('success', 'Cotización creada correctamente.');
    }

    /**
     * Aprobar cotización: descuenta stock por cada línea.
     */
    public function approve(int $id)
    {
        $quote = Quote::with('items.item')->findOrFail($id);

        if ($quote->status !== 'draft') {
            return redirect()->back()->with('error', 'Solo se pueden aprobar cotizaciones en borrador.');
        }

        try {
            DB::transaction(function () use ($quote) {
                foreach ($quote->items as $line) {
                    $stock = Stock::where('item_id', $line->item_id)->first();

                    if (! $stock || $stock->quantity < $line->quantity) {
                        throw new \Exception("Stock insuficiente para «{$line->item->name}».");
                    }
                    
                    $stock->quantity -= $line->quantity;
                    $stock->save();

                    StockMovement::create([
                        'item_id'     => $line->item_id,
                        'type'        => 'salida',
                        'quantity'    => $line->quantity,
                        'stock_after' => $stock->quantity,
                        'reason'      => "Cotización aprobada #{$quote->number}",
                        'source'      => 'quote',
                        'user_id'     => Auth::id(),
                    ]);
                }

                $quote->status = 'approved';
                $quote->save();
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Cotización aprobada y stock descontado correctamente.');
    }

    public function destroy(int $id)
    {
        $quote = Quote::findOrFail($id);

        if ($quote->status === 'approved') {
            return redirect()->back()->with('error', 'No puedes eliminar una cotización aprobada.');
        }

        $quote->delete();

        return redirect()->route('quotes.index')->with('success', 'Cotización eliminada.');
    }
}
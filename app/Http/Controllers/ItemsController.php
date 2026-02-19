<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ItemsController extends Controller
{
public function index()
{
    $vendors = Vendor::where('status', 'active')
            ->orderBy('name')
            ->get();
    $items = Item::with('vendor')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
    
    return view('pages/item/item', compact('items', 'vendors'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'nullable|unique:items|max:50',
            'name' => 'required|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'featured' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive',
            'vendor_id' => 'nullable|exists:vendors,id'
        ]);

        // Generar slug desde el nombre
        $validated['slug'] = Str::slug($validated['name']) . '-' . uniqid();
        
        // Valores por defecto
        $validated['featured'] = $request->has('featured');
        $validated['status'] = $request->has('status') ? 'active' : 'inactive';
        $validated['unit_measure'] = 'unidad'; // Valor por defecto
        
        Item::create($validated);

        return redirect()->route('items.index')
            ->with('success', 'Producto agregado exitosamente.');
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $vendors = Vendor::where('status', 'active')
                        ->orderBy('name')
                        ->get();
        
        return view('items.edit', compact('item', 'vendors'));
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        
        $validated = $request->validate([
            'code' => 'nullable|unique:items,code,' . $item->id . '|max:50',
            'name' => 'required|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'featured' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive',
            'vendor_id' => 'nullable|exists:vendors,id'
        ]);

        // Actualizar slug solo si cambió el nombre
        if ($item->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']) . '-' . uniqid();
        }
        
        $validated['featured'] = $request->has('featured');
        $validated['status'] = $request->has('status') ? 'active' : 'inactive';
        
        $item->update($validated);

        return redirect()->route('items.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->update(['status' => 'inactive']);

        return redirect()->route('items.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }
}
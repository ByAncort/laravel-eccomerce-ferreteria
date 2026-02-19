<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataFeed;
use App\Models\Vendor;

class VendorController extends Controller
{
    public function index()
    {
        $dataFeed = new DataFeed();
        $vendors = Vendor::paginate(10);
        return view('pages/vendor/vendor', compact('dataFeed', 'vendors'));
    }

    public function create()
    {
        $dataFeed = new DataFeed();
        return view('pages/vendor/create', compact('dataFeed'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'run' => 'required|string|max:20|unique:vendors',
            'email' => 'required|email|unique:vendors',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        Vendor::create($validated);

        return redirect()->route('vendors.index')->with('success', 'Proveedor creado exitosamente.');
    }

    public function edit($id)
    {
        $dataFeed = new DataFeed();
        $vendor = Vendor::findOrFail($id);
        return view('pages/vendor/edit', compact('dataFeed', 'vendor'));
    }

    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'run' => 'required|string|max:20|unique:vendors,run,' . $id,
            'email' => 'required|email|unique:vendors,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        $vendor->update($validated);

        return redirect()->route('vendors.index')->with('success', 'Proveedor actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->update(['status' => $vendor->status === 'active' ? 'inactive' : 'active']);

        return redirect()->route('vendors.index')->with('success', 'Estado del proveedor actualizado exitosamente.');
    }
}
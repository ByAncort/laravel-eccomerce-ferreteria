<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataFeed;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $dataFeed = new DataFeed();
        $customers = Customer::paginate(10);
        return view('pages/customer/customer', compact('dataFeed', 'customers'));
    }

    public function create()
    {
        $dataFeed = new DataFeed();
        return view('pages/customer/create', compact('dataFeed'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'run' => 'required|string|max:20|unique:customers',
            'email' => 'required|email|unique:customers',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Cliente creado exitosamente.');
    }

    public function edit($id)
    {
        $dataFeed = new DataFeed();
        $customer = Customer::findOrFail($id);
        return view('pages/customer/edit', compact('dataFeed', 'customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'run' => 'required|string|max:20|unique:customers,run,' . $id,
            'email' => 'required|email|unique:customers,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update(['status' => $customer->status === 'active' ? 'inactive' : 'active']);

        return redirect()->route('customers.index')->with('success', 'Estado del cliente actualizado exitosamente.');
    }
}
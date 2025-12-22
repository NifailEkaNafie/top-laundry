<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return response()->json(Customer::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'nullable',
        ]);

        $customer = Customer::create($request->all());

        return response()->json([
            'message' => 'Customer berhasil ditambahkan',
            'data' => $customer
        ]);
    }

    public function show($id)
    {
        return response()->json(Customer::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update($request->all());

        return response()->json([
            'message' => 'Customer berhasil diupdate',
            'data' => $customer
        ]);
    }

    public function destroy($id)
    {
        Customer::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Customer berhasil dihapus'
        ]);
    }
}

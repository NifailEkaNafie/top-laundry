<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(
            Order::with('customer')->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_type' => 'required',
            'weight_kg' => 'required|numeric',
            'price_total' => 'required|numeric',
            'status' => 'required',
        ]);

        $order = Order::create([
            'customer_id' => $request->customer_id,
            'service_type' => $request->service_type,
            'weight_kg' => $request->weight_kg,
            'price_total' => $request->price_total,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Order berhasil dibuat',
            'data' => $order
        ]);
    }

    public function show($id)
    {
        return response()->json(
            Order::with('customer')->findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update($request->all());

        return response()->json([
            'message' => 'Order berhasil diupdate',
            'data' => $order
        ]);
    }

    public function destroy($id)
    {
        Order::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Order berhasil dihapus'
        ]);
    }
}

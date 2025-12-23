<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Tambahkan ini

class OrderController extends Controller
{
    public function index()
    {
        // Menggunakan latest() agar order terbaru muncul di atas
        return response()->json(
            Order::with('customer')->latest()->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_type' => 'required|string',
            'weight_kg' => 'required|numeric|min:0.1',
            'price_total' => 'required|numeric|min:0',
            'status' => 'required|string',
        ]);

        // Menggunakan Transaction dan Try-Catch untuk keamanan data & error handling
        try {
            $order = DB::transaction(function () use ($validated) {
                return Order::create([
                    'customer_id' => $validated['customer_id'],
                    'service_type' => $validated['service_type'],
                    'weight_kg' => $validated['weight_kg'],
                    'price_total' => $validated['price_total'],
                    'status' => $validated['status'],
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dibuat',
                'data' => $order
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $order = Order::with('customer')->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data order tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'sometimes|required',
            'weight_kg' => 'sometimes|numeric',
        ]);

        try {
            $order = Order::findOrFail($id);

            $order->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil diupdate',
                'data' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update order'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus order'
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * GET ALL ORDERS
     * Pagination + Search + Sort
     */
    public function index(Request $req)
    {
        $limit   = $req->limit ?? 5;
        $page    = $req->page ?? 1;
        $search  = $req->search;
        $orderBy = $req->orderBy ?? 'created_at';
        $sortBy  = $req->sortBy ?? 'desc';

        $query = Order::query();

        if ($search) {
            $query->where('customer_name', 'LIKE', "%{$search}%");
        }

        $orders = $query
            ->orderBy($orderBy, $sortBy)
            ->paginate($limit, ['*'], 'page', $page);

        return response()->json($orders, 200);
    }

    /**
     * GET ORDER DETAIL
     */
    public function show($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status'  => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => $order
        ], 200);
    }

    /**
     * CREATE ORDER
     * Support JSON & form-data (image optional)
     */
    public function store(Request $req)
    {
        // Paksa API response JSON
        $req->headers->set('Accept', 'application/json');

        $data = $req->validate([
            'customer_name' => 'required|string|max:100',
            'service_type'  => 'required|string|max:100',
            'weight_kg'     => 'required|numeric|min:0.1',
            'price_total'   => 'required|numeric|min:0',
            'status'        => 'required|string|max:50',
            'image_path'    => 'nullable',
        ]);

        // Upload image jika ada (form-data)
        if ($req->hasFile('image_path')) {
            $data['image_path'] = $req->file('image_path')
                ->store('orders', 'public');
        }

        $order = Order::create($data);

        return response()->json([
            'status'  => true,
            'message' => 'Order berhasil dibuat',
            'data'    => $order
        ], 201);
    }

    /**
     * UPDATE ORDER
     */
    public function update(Request $req, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status'  => false,
                'message' => 'Order not found'
            ], 404);
        }

        $data = $req->validate([
            'customer_name' => 'sometimes|string|max:100',
            'service_type'  => 'sometimes|string|max:100',
            'weight_kg'     => 'sometimes|numeric|min:0.1',
            'price_total'   => 'sometimes|numeric|min:0',
            'status'        => 'sometimes|string|max:50',
            'image_path'    => 'nullable',
        ]);

        // Upload image baru jika ada
        if ($req->hasFile('image_path')) {
            $data['image_path'] = $req->file('image_path')
                ->store('orders', 'public');
        }

        $order->update($data);

        return response()->json([
            'status'  => true,
            'message' => 'Order berhasil diupdate',
            'data'    => $order
        ], 200);
    }

    /**
     * DELETE ORDER
     */
    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status'  => false,
                'message' => 'Order not found'
            ], 404);
        }

        $order->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Order berhasil dihapus'
        ], 200);
    }
}

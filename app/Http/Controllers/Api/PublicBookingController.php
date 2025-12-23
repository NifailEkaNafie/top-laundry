<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicBookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'service_type' => 'required|string',
            'notes' => 'nullable|string' // Catatan tambahan (opsional)
        ]);

        try {
            $result = DB::transaction(function () use ($validated) {
                // 1. Cek Customer (Update jika ada, Buat baru jika tidak)
                // Kita gunakan Nomor HP sebagai kunci unik
                $customer = Customer::firstOrCreate(
                    ['phone' => $validated['phone']],
                    [
                        'name' => $validated['name'],
                        'address' => $validated['address']
                    ]
                );

                // Jika customer lama, update alamatnya (opsional, takutnya pindah rumah)
                $customer->update(['address' => $validated['address']]);

                // 2. Buat Order Baru
                // Berat di-set 0 karena user belum menimbang, Admin yang nanti update
                $order = Order::create([
                    'customer_id' => $customer->id,
                    'service_type' => $validated['service_type'],
                    'weight_kg' => 0, // Pending ditimbang admin
                    'price_total' => 0, // Pending dihitung admin
                    'status' => 'pending', // Status awal
                ]);

                return $order;
            });

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil! Silakan tunggu konfirmasi admin.',
                'order_id' => $result->uuid
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan booking: ' . $e->getMessage()
            ], 500);
        }
    }
}

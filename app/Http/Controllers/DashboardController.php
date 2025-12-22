<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = Order::with('customer')->latest()->get();
        $stats = [
            'proses' => Order::where('status', 'Di Proses')->count(),
            'selesai' => Order::where('status', 'Selesai')->count(),
        ];
        return view('dashboard', compact('orders', 'stats'));
    }
}

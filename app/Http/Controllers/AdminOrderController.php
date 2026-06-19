<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Tampilkan daftar pesanan
     */
    public function index(Request $request)
    {
        $query = Order::with(['items.menu', 'user', 'transaction']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by nomor meja or customer name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_meja', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        // Counts per status for filter tabs
        $statusCounts = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    /**
     * Tampilkan detail pesanan
     */
    public function show(Order $order)
    {
        $order->load(['items.menu', 'user', 'transaction']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update status pesanan
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed',
        ]);

        $order->update(['status' => $validated['status']]);

        // Auto-create transaction when completed
        if ($validated['status'] === 'completed' && !$order->transaction) {
            Transaction::create([
                'order_id' => $order->id,
                'total_harga' => $order->total_price,
                'metode_pembayaran' => 'cash',
                'status' => 'completed',
            ]);
        }

        return redirect()->back()->with('success', "Status pesanan #{$order->id} berhasil diubah menjadi {$validated['status']}");
    }

    /**
     * Hapus pesanan
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')
            ->with('success', "Pesanan #{$order->id} berhasil dihapus");
    }
}

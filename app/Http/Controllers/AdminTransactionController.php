<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Tampilkan daftar transaksi
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['order.items.menu']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);

        // Summary stats
        $today = Carbon::today();
        $totalRevenue = Transaction::where('status', 'completed')->sum('total_harga');
        $todayRevenue = Transaction::where('status', 'completed')
            ->whereDate('created_at', $today)
            ->sum('total_harga');
        $completedCount = Transaction::where('status', 'completed')->count();
        $pendingCount = Transaction::where('status', 'pending')->count();

        return view('admin.transactions.index', compact(
            'transactions',
            'totalRevenue',
            'todayRevenue',
            'completedCount',
            'pendingCount'
        ));
    }
}

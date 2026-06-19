<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Tampilkan dashboard admin
     */
    public function index()
    {
        $today = Carbon::today();

        // Stats
        $todayOrders = Order::whereDate('created_at', $today)->count();
        $todayRevenue = Order::whereDate('created_at', $today)
            ->whereIn('status', ['completed', 'processing'])
            ->sum('total_price');
        $activeMenus = Menu::where('is_active', true)->count();
        $pendingOrders = Order::where('status', 'pending')->count();

        // Recent orders (last 10)
        $recentOrders = Order::with(['items.menu', 'transaction'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Revenue last 7 days for chart
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $revenue = Order::whereDate('created_at', $date)
                ->whereIn('status', ['completed', 'processing'])
                ->sum('total_price');
            $chartData[] = [
                'label' => $date->translatedFormat('D'),
                'value' => (float) $revenue,
            ];
        }

        // Total keseluruhan
        $totalRevenue = Order::whereIn('status', ['completed', 'processing'])->sum('total_price');
        $totalOrders = Order::count();

        return view('admin.dashboard', compact(
            'todayOrders',
            'todayRevenue',
            'activeMenus',
            'pendingOrders',
            'recentOrders',
            'chartData',
            'totalRevenue',
            'totalOrders'
        ));
    }
}

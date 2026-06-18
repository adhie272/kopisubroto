<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    /**
     * Tampilkan halaman menu utama dengan kategori
     */
    public function index()
    {
        // Ambil menu berdasarkan kategori
        $coffeeMenus = Menu::byCategory('coffee')->active()->get();
        $snackMenus = Menu::byCategory('snack')->active()->get();
        $othersMenus = Menu::byCategory('others')->active()->get();

        // Hitung total item di cart
        $cartCount = Cart::bySession(session()->getId())->sum('quantity');
        $myOrdersCount = $this->activeCustomerOrdersCount();

        return view('welcome', compact('coffeeMenus', 'snackMenus', 'othersMenus', 'cartCount', 'myOrdersCount'));
    }

    /**
     * Tampilkan pesanan milik browser/customer saat ini.
     */
    public function myOrders(Request $request)
    {
        $orderIds = $this->customerOrderIds();

        if ($request->integer('order')) {
            $orderIds = $this->persistCustomerOrderIds([
                $request->integer('order'),
                ...$orderIds,
            ]);
        }

        $orders = Order::with(['items.menu'])
            ->whereIn('id', $orderIds)
            ->orderBy('created_at', 'desc')
            ->get();

        $cartCount = Cart::bySession(session()->getId())->sum('quantity');
        $myOrdersCount = $this->activeCustomerOrdersCount();

        return view('orders.my', compact('orders', 'cartCount', 'myOrdersCount'));
    }

    /**
     * Status terbaru untuk polling halaman Pesanan Saya.
     */
    public function myOrdersStatus()
    {
        $orderIds = $this->customerOrderIds();

        $orders = Order::whereIn('id', $orderIds)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'status', 'updated_at']);

        return response()->json([
            'success' => true,
            'orders' => $orders->map(fn ($order) => [
                'id' => $order->id,
                'status' => $order->status,
                'label' => $this->statusLabel($order->status),
                'updated_at' => $order->updated_at?->toIso8601String(),
            ]),
        ]);
    }

    /**
     * Batalkan pesanan dari sisi user/customer.
     */
    public function cancelMyOrder(Order $order)
    {
        if (! $this->customerOwnsOrder($order->id)) {
            abort(404);
        }

        if (! in_array($order->status, ['pending', 'processing'], true)) {
            return redirect('/pesanan-saya')
                ->with('order_notice', "Pesanan #{$order->id} sudah tidak bisa dibatalkan.");
        }

        $order->update(['status' => 'cancelled']);

        return redirect('/pesanan-saya')
            ->with('order_notice', "Pesanan #{$order->id} berhasil dibatalkan. Admin akan melihat status dibatalkan.");
    }

    /**
     * Get menu items berdasarkan kategori via AJAX
     */
    public function getMenuByCategory($category)
    {
        $menus = Menu::byCategory($category)->active()->get();

        return response()->json([
            'success' => true,
            'data' => $menus,
            'message' => "Menu {$category} berhasil diambil"
        ]);
    }

    /**
     * Tambah item ke cart
     */
    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $menu = Menu::findOrFail($validated['menu_id']);
        $sessionId = session()->getId();

        // Cek apakah item sudah ada di cart
        $cartItem = Cart::where('menu_id', $menu->id)
            ->where('session_id', $sessionId)
            ->first();

        if ($cartItem) {
            // Update quantity jika sudah ada
            $cartItem->quantity += $validated['quantity'];
            $cartItem->save();
        } else {
            // Buat cart item baru
            Cart::create([
                'menu_id' => $menu->id,
                'quantity' => $validated['quantity'],
                'price' => $menu->price,
                'session_id' => $sessionId
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "{$menu->name} ditambahkan ke keranjang",
            'cartCount' => Cart::bySession($sessionId)->sum('quantity')
        ]);
    }

    /**
     * Tampilkan isi cart
     */
    public function viewCart()
    {
        $sessionId = session()->getId();
        $cartItems = Cart::bySession($sessionId)->with('menu')->get();

        $total = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return response()->json([
            'success' => true,
            'items' => $cartItems,
            'total' => $total,
            'count' => $cartItems->count()
        ]);
    }

    /**
     * Update quantity item di cart
     */
    public function updateCartQuantity(Request $request, $cartId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::findOrFail($cartId);
        $cartItem->update(['quantity' => $validated['quantity']]);

        $sessionId = session()->getId();

        return response()->json([
            'success' => true,
            'message' => 'Quantity berhasil diupdate',
            'cartCount' => Cart::bySession($sessionId)->sum('quantity')
        ]);
    }

    /**
     * Hapus item dari cart
     */
    public function removeFromCart($cartId)
    {
        $cartItem = Cart::findOrFail($cartId);
        $cartItem->delete();

        $sessionId = session()->getId();

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus dari keranjang',
            'cartCount' => Cart::bySession($sessionId)->sum('quantity')
        ]);
    }

    /**
     * Kosongkan semua cart
     */
    public function clearCart()
    {
        $sessionId = session()->getId();
        Cart::bySession($sessionId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil dikosongkan',
            'cartCount' => 0
        ]);
    }

    /**
     * order keranjang
     */
    public function order(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|min:1|max:100',
            'table_number' => 'required|integer|min:1',
            'item_notes' => 'nullable|array',
            'item_notes.*' => 'nullable|string|max:500'
        ]);

        $sessionId = session()->getId();
        $cartItems = Cart::bySession($sessionId)->with('menu')->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang Anda kosong!'
            ]);
        }

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        // Create Order
        $order = Order::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'customer_name' => $validated['customer_name'],
            'nomor_meja' => $validated['table_number']
        ]);

        // Create Order Items
        foreach ($cartItems as $item) {
            $itemNotes = $validated['item_notes'] ?? [];
            $keterangan = $itemNotes[$item->id] ?? null;

            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $item->menu_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'keterangan' => $keterangan ? trim($keterangan) : null
            ]);
        }

        // Clear cart after order
        Cart::bySession($sessionId)->delete();

        $customerOrderIds = $this->persistCustomerOrderIds([
            $order->id,
            ...$this->customerOrderIds(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat! Terima kasih.',
            'order_id' => $order->id,
            'cartCount' => 0
        ]);
    }

    private function activeCustomerOrdersCount(): int
    {
        $orderIds = $this->customerOrderIds();

        if (empty($orderIds)) {
            return 0;
        }

        return Order::whereIn('id', $orderIds)
            ->whereIn('status', ['pending', 'processing'])
            ->count();
    }

    private function customerOwnsOrder(int $orderId): bool
    {
        return in_array($orderId, $this->customerOrderIds(), true);
    }

    private function customerOrderIds(): array
    {
        $sessionIds = session('customer_order_ids', []);
        $cookieIds = json_decode((string) request()->cookie('customer_order_ids', '[]'), true);

        if (! is_array($cookieIds)) {
            $cookieIds = [];
        }

        return collect([...$sessionIds, ...$cookieIds])
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values()
            ->all();
    }

    private function persistCustomerOrderIds(array $orderIds): array
    {
        $ids = collect($orderIds)
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->take(20)
            ->values()
            ->all();

        session(['customer_order_ids' => $ids]);
        cookie()->queue(cookie('customer_order_ids', json_encode($ids), 60 * 24 * 14));

        return $ids;
    }

    private function statusLabel(string $status): string
    {
        return [
            'pending' => 'Menunggu',
            'processing' => 'Diproses',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ][$status] ?? ucfirst($status);
    }
}

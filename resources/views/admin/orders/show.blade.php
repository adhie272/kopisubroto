@extends('admin.layouts.app')

@section('title', 'Detail Pesanan #' . $order->id)
@section('page-title', 'Detail Pesanan #' . $order->id)
@section('breadcrumb', 'Admin / Pesanan / Detail #' . $order->id)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- ═══════ ORDER INFO ═══════ -->
    <div class="lg:col-span-2">
        <div class="content-card">
            <div class="card-header">
                <h3>📋 Informasi Pesanan</h3>
                <span class="badge-status badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
            </div>
            <div class="card-body padded">
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <div style="font-size:0.7rem; color:#94a3b8; font-weight:600; text-transform:uppercase;">ID Pesanan</div>
                        <div style="font-size:1.1rem; font-weight:700; color:var(--brand-ink);">#{{ $order->id }}</div>
                    </div>
                    <div>
                        <div style="font-size:0.7rem; color:#94a3b8; font-weight:600; text-transform:uppercase;">Nomor Meja</div>
                        <div style="font-size:1.1rem; font-weight:700; color:var(--brand-ink);">
                            {{ $order->nomor_meja ? 'Meja ' . $order->nomor_meja : '—' }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size:0.7rem; color:#94a3b8; font-weight:600; text-transform:uppercase;">Customer</div>
                        <div style="font-size:1.1rem; font-weight:700; color:var(--brand-ink);">
                            {{ $order->customer_name ?? ($order->user->name ?? 'Tamu') }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size:0.7rem; color:#94a3b8; font-weight:600; text-transform:uppercase;">Tanggal</div>
                        <div style="font-size:1.1rem; font-weight:700; color:var(--brand-ink);">
                            {{ $order->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div style="border-top:1px solid #f1f5f9; padding-top:20px;">
                    <h4 style="font-size:0.85rem; font-weight:700; color:var(--brand-ink); margin-bottom:12px;">Detail Item</h4>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th>Keterangan</th>
                                <th style="text-align:center;">Qty</th>
                                <th style="text-align:right;">Harga</th>
                                <th style="text-align:right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td style="font-weight:600;">{{ $item->menu->name ?? 'Item Dihapus' }}</td>
                                <td style="font-size:0.8rem; color:#64748b; max-width:220px;">
                                    {{ $item->keterangan ?: '-' }}
                                </td>
                                <td style="text-align:center;">{{ $item->quantity }}</td>
                                <td style="text-align:right;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td style="text-align:right; font-weight:700;">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="border-top:2px solid #e2e8f0;">
                                <td colspan="4" style="text-align:right; font-weight:700; font-size:0.95rem;">TOTAL</td>
                                <td style="text-align:right; font-weight:800; font-size:1.1rem; color:#059669;">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════ SIDEBAR ACTIONS ═══════ -->
    <div>
        <!-- Update Status -->
        <div class="content-card mb-4">
            <div class="card-header">
                <h3>⚡ Update Status</h3>
            </div>
            <div class="card-body padded">
                <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                    @csrf
                    @method('PUT')
                    <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold focus:ring-2 focus:ring-brand-teal mb-4">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>🔄 Diproses</option>
                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>✅ Selesai</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>❌ Dibatalkan</option>
                    </select>
                    <button type="submit" class="btn btn-primary w-full justify-center">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <!-- Transaction Info -->
        <div class="content-card mb-4">
            <div class="card-header">
                <h3>💳 Transaksi</h3>
            </div>
            <div class="card-body padded">
                @if($order->transaction)
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span style="font-size:0.8rem; color:#64748b;">Total</span>
                            <span style="font-size:0.85rem; font-weight:700;">Rp {{ number_format($order->transaction->total_harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span style="font-size:0.8rem; color:#64748b;">Metode</span>
                            <span style="font-size:0.85rem; font-weight:600;">{{ ucfirst($order->transaction->metode_pembayaran) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span style="font-size:0.8rem; color:#64748b;">Status</span>
                            <span class="badge-status badge-{{ $order->transaction->status }}">{{ ucfirst($order->transaction->status) }}</span>
                        </div>
                    </div>
                @else
                    <div style="text-align:center; padding:16px; color:#94a3b8;">
                        <div style="font-size:1.5rem; margin-bottom:6px;">💳</div>
                        <div style="font-size:0.8rem;">Transaksi akan dibuat otomatis saat pesanan diselesaikan</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Delete -->
        <div class="content-card">
            <div class="card-body padded">
                <form method="POST" action="{{ route('admin.orders.destroy', $order->id) }}" onsubmit="return confirm('Yakin hapus pesanan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-full justify-center">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus Pesanan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

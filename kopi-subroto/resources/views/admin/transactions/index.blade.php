@extends('admin.layouts.app')

@section('title', 'Transaksi')
@section('page-title', 'Transaksi')
@section('breadcrumb', 'Admin / Transaksi')

@section('content')
<!-- ═══════ STAT CARDS ═══════ -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stat-card emerald animate-in">
        <div class="stat-icon">💰</div>
        <div class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        <div class="stat-label">Total Pendapatan</div>
    </div>
    <div class="stat-card amber animate-in">
        <div class="stat-icon">📅</div>
        <div class="stat-value">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
        <div class="stat-label">Pendapatan Hari Ini</div>
    </div>
    <div class="stat-card blue animate-in">
        <div class="stat-icon">✅</div>
        <div class="stat-value">{{ $completedCount }}</div>
        <div class="stat-label">Transaksi Selesai</div>
    </div>
    <div class="stat-card rose animate-in">
        <div class="stat-icon">⏳</div>
        <div class="stat-value">{{ $pendingCount }}</div>
        <div class="stat-label">Transaksi Pending</div>
    </div>
</div>

<!-- ═══════ FILTER ═══════ -->
<div class="flex flex-wrap items-center gap-3 mb-6">
    <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex flex-wrap gap-3">
        <select name="status" class="px-4 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-brand-teal">
            <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>Semua Status</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
        </select>
        <input type="date" name="date" value="{{ request('date') }}"
               class="px-4 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-brand-teal">
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline btn-sm">Reset</a>
    </form>
</div>

<!-- ═══════ TRANSACTIONS TABLE ═══════ -->
<div class="content-card">
    <div class="card-header">
        <h3>💳 Daftar Transaksi</h3>
        <span style="font-size:0.75rem; color:#94a3b8;">{{ $transactions->total() }} transaksi</span>
    </div>
    <div class="card-body">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Order ID</th>
                    <th>Meja</th>
                    <th>Items</th>
                    <th>Total Harga</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                <tr>
                    <td style="font-weight:700;">#{{ $trx->id }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $trx->order_id) }}" style="color:var(--brand-ink); font-weight:600; text-decoration:none;">
                            #{{ $trx->order_id }}
                        </a>
                    </td>
                    <td>
                        @if($trx->order && $trx->order->nomor_meja)
                            <span style="background:var(--brand-teal-soft); color:var(--brand-ink); padding:2px 10px; border-radius:99px; font-size:0.72rem; font-weight:600;">
                                Meja {{ $trx->order->nomor_meja }}
                            </span>
                        @else
                            <span style="color:#94a3b8;">—</span>
                        @endif
                    </td>
                    <td style="font-size:0.8rem; color:#64748b;">
                        @if($trx->order)
                            {{ $trx->order->items->count() }} item
                        @endif
                    </td>
                    <td style="font-weight:700; color:var(--brand-brown);">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                    <td>
                        <span style="background:var(--brand-cream); color:var(--brand-ink); border:1px solid var(--brand-line); padding:3px 10px; border-radius:8px; font-size:0.75rem; font-weight:600;">
                            {{ ucfirst($trx->metode_pembayaran) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-status badge-{{ $trx->status }}">{{ ucfirst($trx->status) }}</span>
                    </td>
                    <td style="font-size:0.8rem; color:#64748b;">
                        {{ $trx->created_at->format('d/m/Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:50px; color:#94a3b8;">
                        <div style="font-size:2.5rem; margin-bottom:10px;">💳</div>
                        <div style="font-weight:600;">Belum ada transaksi</div>
                        <div style="font-size:0.8rem;">Transaksi akan dibuat otomatis saat pesanan diselesaikan</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-6 pagination-wrapper">
    {{ $transactions->withQueryString()->links() }}
</div>
@endsection

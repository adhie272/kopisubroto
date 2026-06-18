@extends('admin.layouts.app')

@section('title', 'Kelola Pesanan')
@section('page-title', 'Kelola Pesanan')
@section('breadcrumb', 'Admin / Pesanan')

@section('content')
<!-- ═══════ STATUS FILTER TABS ═══════ -->
<div class="flex flex-wrap items-center gap-2 mb-6">
    @php
        $currentStatus = request('status', 'all');
        $statuses = [
            'all' => ['label' => 'Semua', 'icon' => '📋'],
            'pending' => ['label' => 'Pending', 'icon' => '⏳'],
            'processing' => ['label' => 'Diproses', 'icon' => '🔄'],
            'completed' => ['label' => 'Selesai', 'icon' => '✅'],
            'cancelled' => ['label' => 'Dibatalkan', 'icon' => '❌'],
        ];
    @endphp

    @foreach($statuses as $key => $info)
        <a href="{{ route('admin.orders.index', ['status' => $key]) }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold transition-all
                  {{ $currentStatus === $key
                      ? 'bg-brand-deep text-brand-parchment shadow-lg'
                      : 'bg-white text-brand-ink border border-brand-line hover:border-brand-teal' }}">
            {{ $info['icon'] }} {{ $info['label'] }}
            <span class="ml-1 px-2 py-0.5 text-xs rounded-full
                         {{ $currentStatus === $key ? 'bg-brand-teal text-brand-deep' : 'bg-brand-cream text-brand-ink' }}">
                {{ $statusCounts[$key] }}
            </span>
        </a>
    @endforeach
</div>

<!-- ═══════ SEARCH ═══════ -->
<div class="mb-6">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="flex gap-3">
        <input type="hidden" name="status" value="{{ request('status', 'all') }}">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nomor meja, customer, atau ID pesanan..."
               class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-brand-teal focus:border-transparent outline-none">
        <button type="submit" class="btn btn-primary">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Cari
        </button>
    </form>
</div>

<!-- ═══════ ORDERS TABLE ═══════ -->
<div class="content-card">
    <div class="card-header">
        <h3>Daftar Pesanan</h3>
        <span style="font-size:0.75rem; color:#94a3b8;">{{ $orders->total() }} pesanan</span>
    </div>
    <div class="card-body">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Meja</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td style="font-weight:700;">#{{ $order->id }}</td>
                    <td>
                        @if($order->nomor_meja)
                            <span style="background:#dbeafe; color:#1e40af; padding:3px 12px; border-radius:99px; font-size:0.72rem; font-weight:600;">
                                Meja {{ $order->nomor_meja }}
                            </span>
                        @else
                            <span style="color:#94a3b8;">—</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600; color:var(--brand-ink);">{{ $order->customer_name ?? ($order->user->name ?? 'Tamu') }}</div>
                    </td>
                    <td>
                        <div style="font-size:0.8rem; color:#64748b;">
                            @foreach($order->items->take(2) as $item)
                                {{ $item->menu->name ?? 'N/A' }} ({{ $item->quantity }}x)<br>
                            @endforeach
                            @if($order->items->count() > 2)
                                <span style="color:#94a3b8;">+{{ $order->items->count() - 2 }} lainnya</span>
                            @endif
                        </div>
                    </td>
                    <td style="font-weight:700; color:#059669;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge-status badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    </td>
                    <td style="font-size:0.8rem; color:#64748b;">
                        {{ $order->created_at->format('d/m/Y') }}<br>
                        <span style="font-size:0.7rem;">{{ $order->created_at->format('H:i') }}</span>
                    </td>
                    <td style="text-align:center;">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline btn-sm" title="Detail">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>

                            <!-- Status update dropdown -->
                            @if($order->status !== 'completed' && $order->status !== 'cancelled')
                            <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()" class="text-xs border border-gray-200 rounded-lg px-2 py-1 cursor-pointer focus:ring-1 focus:ring-brand-teal">
                                    <option value="" disabled selected>Ubah ▾</option>
                                    @if($order->status === 'pending')
                                        <option value="processing">→ Proses</option>
                                    @endif
                                    @if($order->status === 'pending' || $order->status === 'processing')
                                        <option value="completed">→ Selesai</option>
                                        <option value="cancelled">→ Batal</option>
                                    @endif
                                </select>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:50px; color:#94a3b8;">
                        <div style="font-size:2.5rem; margin-bottom:10px;">📋</div>
                        <div style="font-weight:600;">Tidak ada pesanan</div>
                        <div style="font-size:0.8rem;">Pesanan baru akan muncul di sini</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-6 pagination-wrapper">
    {{ $orders->withQueryString()->links() }}
</div>
@endsection

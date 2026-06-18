@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Admin / Dashboard')

@section('content')
<!-- ═══════ STAT CARDS ═══════ -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stat-card amber animate-in">
        <div class="stat-icon">📋</div>
        <div class="stat-value">{{ $todayOrders }}</div>
        <div class="stat-label">Pesanan Hari Ini</div>
    </div>
    <div class="stat-card emerald animate-in">
        <div class="stat-icon">💰</div>
        <div class="stat-value">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
        <div class="stat-label">Pendapatan Hari Ini</div>
    </div>
    <div class="stat-card blue animate-in">
        <div class="stat-icon">🍽️</div>
        <div class="stat-value">{{ $activeMenus }}</div>
        <div class="stat-label">Menu Aktif</div>
    </div>
    <div class="stat-card rose animate-in">
        <div class="stat-icon">⏳</div>
        <div class="stat-value">{{ $pendingOrders }}</div>
        <div class="stat-label">Pesanan Pending</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- ═══════ CHART ═══════ -->
    <div class="lg:col-span-2 content-card animate-in">
        <div class="card-header">
            <h3>📊 Pendapatan 7 Hari Terakhir</h3>
            <span style="font-size:0.75rem; color:#94a3b8;">Total: Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
        </div>
        <div class="card-body padded">
            <canvas id="revenueChart" height="260"></canvas>
        </div>
    </div>

    <!-- ═══════ SUMMARY ═══════ -->
    <div class="content-card animate-in">
        <div class="card-header">
            <h3>📈 Ringkasan</h3>
        </div>
        <div class="card-body padded">
            <div class="space-y-5">
                <div class="flex items-center justify-between p-3 rounded-xl" style="background:var(--brand-teal-soft);">
                    <div>
                        <div style="font-size:0.7rem; color:var(--brand-ink); font-weight:600;">TOTAL PESANAN</div>
                        <div style="font-size:1.3rem; font-weight:800; color:var(--brand-ink);">{{ $totalOrders }}</div>
                    </div>
                    <div style="font-size:1.5rem;">📦</div>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl" style="background:#f1dfd2;">
                    <div>
                        <div style="font-size:0.7rem; color:var(--brand-brown-dark); font-weight:600;">TOTAL PENDAPATAN</div>
                        <div style="font-size:1.3rem; font-weight:800; color:var(--brand-ink);">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    </div>
                    <div style="font-size:1.5rem;">💵</div>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl" style="background:var(--brand-cream); border:1px solid var(--brand-line);">
                    <div>
                        <div style="font-size:0.7rem; color:var(--brand-ink); font-weight:600;">MENU AKTIF</div>
                        <div style="font-size:1.3rem; font-weight:800; color:var(--brand-ink);">{{ $activeMenus }}</div>
                    </div>
                    <div style="font-size:1.5rem;">☕</div>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl" style="background:#ead7d5;">
                    <div>
                        <div style="font-size:0.7rem; color:var(--brand-maroon); font-weight:600;">PENDING</div>
                        <div style="font-size:1.3rem; font-weight:800; color:var(--brand-ink);">{{ $pendingOrders }}</div>
                    </div>
                    <div style="font-size:1.5rem;">🔔</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════ RECENT ORDERS TABLE ═══════ -->
<div class="content-card mt-6 animate-in">
    <div class="card-header">
        <h3>🕐 Pesanan Terbaru</h3>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">Lihat Semua →</a>
    </div>
    <div class="card-body">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Meja</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                <tr>
                    <td style="font-weight:700;">#{{ $order->id }}</td>
                    <td>
                        @if($order->nomor_meja)
                            <span style="background:var(--brand-teal-soft); color:var(--brand-ink); padding:2px 10px; border-radius:99px; font-size:0.72rem; font-weight:600;">Meja {{ $order->nomor_meja }}</span>
                        @else
                            <span style="color:#94a3b8; font-size:0.8rem;">—</span>
                        @endif
                    </td>
                    <td style="font-size:0.8rem; color:#64748b;">
                        {{ $order->items->count() }} item
                    </td>
                    <td style="font-weight:700; color:var(--brand-brown);">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge-status badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    </td>
                    <td style="font-size:0.8rem; color:#64748b;">{{ $order->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:40px; color:#94a3b8;">
                        Belum ada pesanan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('revenueChart');
    if (!ctx) return;

    const chartData = @json($chartData);
    const styles = getComputedStyle(document.documentElement);
    const brandTeal = styles.getPropertyValue('--brand-teal').trim();
    const brandBrown = styles.getPropertyValue('--brand-brown').trim();
    const brandDeep = styles.getPropertyValue('--brand-deep').trim();
    const brandLine = styles.getPropertyValue('--brand-line').trim();

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.map(d => d.label),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: chartData.map(d => d.value),
                backgroundColor: chartData.map((d, i) =>
                    i === chartData.length - 1
                        ? brandBrown
                        : 'rgba(95, 168, 164, 0.45)'
                ),
                borderColor: chartData.map((d, i) =>
                    i === chartData.length - 1
                        ? brandBrown
                        : brandTeal
                ),
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: brandDeep,
                    titleFont: { family: 'Inter', size: 12 },
                    bodyFont: { family: 'Inter', size: 11 },
                    cornerRadius: 8,
                    padding: 12,
                    callbacks: {
                        label: function(ctx) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw);
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Inter', size: 11 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: brandLine },
                    ticks: {
                        font: { family: 'Inter', size: 11 },
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush

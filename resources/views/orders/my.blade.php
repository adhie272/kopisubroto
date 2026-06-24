<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - Kopi Subroto</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bungee&family=Figtree:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-brand-cream font-sans text-brand-ink min-h-screen pt-20">
    @php
        $statusMeta = [
            'pending' => [
                'label' => 'Menunggu',
                'title' => 'Pesanan masuk',
                'description' => 'Pesanan sudah diterima dan menunggu diproses.',
                'badge' => 'bg-[#f1dfd2] text-brand-brownDark',
                'dot' => 'bg-brand-brown',
                'step' => 1,
            ],
            'processing' => [
                'label' => 'Diproses',
                'title' => 'Sedang dibuat',
                'description' => 'Tim Kopi Subroto sedang menyiapkan pesanan.',
                'badge' => 'bg-brand-tealSoft text-brand-ink',
                'dot' => 'bg-brand-teal',
                'step' => 2,
            ],
            'completed' => [
                'label' => 'Selesai',
                'title' => 'Pesanan selesai',
                'description' => 'Pesanan sudah selesai.',
                'badge' => 'bg-[#dcefe2] text-brand-ink',
                'dot' => 'bg-[#6c9c80]',
                'step' => 3,
            ],
            'cancelled' => [
                'label' => 'Dibatalkan',
                'title' => 'Pesanan dibatalkan',
                'description' => 'Pesanan ini dibatalkan oleh user.',
                'badge' => 'bg-[#ead7d5] text-brand-maroon',
                'dot' => 'bg-brand-maroon',
                'step' => 0,
            ],
        ];

        $activeOrders = $orders->whereIn('status', ['pending', 'processing']);
        $historyOrders = $orders->whereIn('status', ['completed', 'cancelled']);
    @endphp

    <nav class="bg-brand-deep text-brand-parchment shadow-lg fixed top-0 w-full h-16 z-40">
        <div class="container mx-auto px-4 sm:px-6 h-full flex justify-between items-center">
            <a href="/" class="brand-lockup">
                <span class="brand-emblem" aria-hidden="true">S</span>
                <div class="flex flex-col justify-center min-w-0">
                    <h1 class="brand-wordmark text-[0.95rem] sm:text-lg text-brand-parchment">Kopi Subroto</h1>
                    <p class="hidden sm:block brand-subtitle text-[10px] text-brand-parchment/75 leading-tight">Kopi Premium & Snacks Enak</p>
                </div>
            </a>
            <div class="flex items-center gap-2 sm:gap-4">
                <a href="/pesanan-saya" class="bg-brand-teal px-3 py-2 rounded-lg transition relative inline-flex items-center gap-2 text-brand-deep" aria-label="Pesanan Saya">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6h8M8 10h8M8 14h5M5 4h14v16l-2-1.25L15 20l-2-1.25L11 20l-2-1.25L7 20l-2-1.25V4z" />
                    </svg>
                    <span class="hidden sm:inline text-xs font-bold">Pesanan</span>
                    @if(($myOrdersCount ?? 0) > 0)
                    <span class="absolute -top-1 -right-1 bg-brand-brown text-white text-xs font-bold rounded-full w-4 h-4 flex items-center justify-center text-[10px]">{{ $myOrdersCount }}</span>
                    @endif
                </a>
                <a href="/#cart" class="bg-brand-ink p-2 rounded-lg hover:bg-brand-teal hover:text-brand-deep transition relative" aria-label="Keranjang">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-brand-maroon text-white text-xs font-bold rounded-full w-4 h-4 flex items-center justify-center text-[10px]">{{ $cartCount ?? 0 }}</span>
                </a>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 sm:px-6 py-6 sm:py-10">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-6">
            <div>
                <div class="w-14 h-14 rounded-2xl bg-brand-brown text-white flex items-center justify-center mb-4 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6h8M8 10h8M8 14h5M5 4h14v16l-2-1.25L15 20l-2-1.25L11 20l-2-1.25L7 20l-2-1.25V4z" />
                    </svg>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-brand-ink">Pesanan Saya</h2>
                <p class="text-sm text-brand-muted mt-2">Status pesanan mengikuti data yang diubah dari dashboard admin.</p>
            </div>
            <button onclick="window.location.reload()" class="bg-brand-brown text-white px-5 py-3 rounded-2xl font-bold hover:bg-brand-brownDark transition">
                Refresh Status
            </button>
        </div>

        <div id="orderStatusNotice" class="hidden mb-5 rounded-2xl border border-brand-teal bg-brand-tealSoft px-5 py-4 text-sm font-bold text-brand-ink"></div>

        @if(session('order_notice'))
            <div class="mb-5 rounded-2xl border border-brand-teal bg-brand-tealSoft px-5 py-4 text-sm font-bold text-brand-ink">
                {{ session('order_notice') }}
            </div>
        @endif

        @if($orders->isEmpty())
            <section class="bg-white border border-brand-line rounded-3xl p-8 sm:p-10 shadow-lg text-center">
                <div class="mx-auto w-16 h-16 rounded-2xl bg-brand-tealSoft text-brand-ink flex items-center justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6h8M8 10h8M8 14h5M5 4h14v16l-2-1.25L15 20l-2-1.25L11 20l-2-1.25L7 20l-2-1.25V4z" />
                    </svg>
                </div>
                <h3 class="text-xl font-extrabold text-brand-ink">Belum ada pesanan</h3>
                <p class="text-sm text-brand-muted mt-2">Pesanan yang dibuat dari perangkat ini akan tampil di sini.</p>
                <a href="/" class="inline-flex mt-6 bg-brand-brown text-white px-6 py-3 rounded-2xl font-bold hover:bg-brand-brownDark transition">
                    Lihat Menu
                </a>
            </section>
        @else
            @if($activeOrders->isNotEmpty())
                <section class="mb-8">
                    <div class="flex items-center justify-between gap-4 mb-4">
                        <div>
                            <h3 class="text-xl sm:text-2xl font-extrabold text-brand-ink">Pesanan Aktif</h3>
                            <p class="text-sm text-brand-muted mt-1">Pantau status pesanan yang masih berjalan.</p>
                        </div>
                        <span class="rounded-full bg-brand-tealSoft text-brand-ink px-3 py-1 text-xs font-bold">{{ $activeOrders->count() }} aktif</span>
                    </div>
                    <div class="space-y-5">
                @foreach($activeOrders as $order)
                    @php
                        $meta = $statusMeta[$order->status] ?? $statusMeta['pending'];
                        $currentStep = $meta['step'];
                    @endphp
                    <article class="bg-white border border-brand-line rounded-3xl shadow-lg overflow-hidden order-card" data-order-id="{{ $order->id }}" data-status="{{ $order->status }}">
                        <div class="p-5 sm:p-6 border-b border-brand-line flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                            <div>
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <h3 class="text-xl font-extrabold text-brand-ink">Order #{{ $order->id }}</h3>
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold {{ $meta['badge'] }}">
                                        <span class="w-2 h-2 rounded-full {{ $meta['dot'] }}"></span>
                                        {{ $meta['label'] }}
                                    </span>
                                </div>
                                <p class="text-sm text-brand-muted">
                                    {{ $order->customer_name ?? 'Tamu' }}
                                    @if($order->nomor_meja)
                                        <span class="mx-2 text-brand-line">|</span> Meja {{ $order->nomor_meja }}
                                    @endif
                                    <span class="mx-2 text-brand-line">|</span> {{ $order->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div class="text-left lg:text-right">
                                <p class="text-xs font-bold uppercase text-brand-muted">Total</p>
                                <p class="text-2xl font-extrabold text-brand-brown">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                @if(in_array($order->status, ['pending', 'processing'], true))
                                    <form method="POST" action="/pesanan-saya/{{ $order->id }}/cancel" class="mt-3" onsubmit="return confirm('Batalkan pesanan #{{ $order->id }}?')">
                                        @csrf
                                        <button type="submit" class="bg-brand-maroon text-white px-4 py-2 rounded-xl text-xs font-bold hover:opacity-90 transition">
                                            Batalkan Pesanan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <div class="p-5 sm:p-6 grid lg:grid-cols-[1.2fr_0.8fr] gap-6">
                            <div>
                                <h4 class="font-extrabold text-brand-ink mb-3">{{ $meta['title'] }}</h4>
                                <p class="text-sm text-brand-muted mb-5">{{ $meta['description'] }}</p>

                                @if($order->status === 'cancelled')
                                    <div class="rounded-2xl border border-[#d7aaa6] bg-[#ead7d5] p-4 text-sm font-semibold text-brand-maroon">
                                        Pesanan ini sudah dibatalkan.
                                    </div>
                                @else
                                    <div class="grid grid-cols-3 gap-2">
                                        @foreach([1 => 'Masuk', 2 => 'Diproses', 3 => 'Selesai'] as $step => $label)
                                            <div class="rounded-2xl border p-3 {{ $currentStep >= $step ? 'border-brand-teal bg-brand-tealSoft text-brand-ink' : 'border-brand-line bg-brand-cream text-brand-muted' }}">
                                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-extrabold mb-2 {{ $currentStep >= $step ? 'bg-brand-teal text-brand-deep' : 'bg-white text-brand-muted' }}">
                                                    {{ $step }}
                                                </div>
                                                <p class="text-xs font-bold">{{ $label }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="rounded-2xl bg-brand-cream border border-brand-line p-4">
                                <h4 class="font-extrabold text-brand-ink mb-3">Detail Pesanan</h4>
                                <div class="space-y-3">
                                    @foreach($order->items as $item)
                                        <div class="flex justify-between gap-3 text-sm">
                                            <div>
                                                <p class="font-bold text-brand-ink">{{ $item->menu->name ?? 'Menu' }}</p>
                                                <p class="text-xs text-brand-muted">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                                @if($item->keterangan)
                                                    <p class="text-xs text-brand-brown mt-1">Catatan: {{ $item->keterangan }}</p>
                                                @endif
                                            </div>
                                            <p class="font-bold text-brand-ink whitespace-nowrap">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
                    </div>
                </section>
            @else
                <section class="mb-8 rounded-3xl border border-brand-line bg-white p-5 shadow-sm">
                    <h3 class="text-lg font-extrabold text-brand-ink">Tidak ada pesanan aktif</h3>
                    <p class="text-sm text-brand-muted mt-1">Pesanan yang sudah selesai atau dibatalkan dipindahkan ke riwayat di bawah.</p>
                </section>
            @endif

            @if($historyOrders->isNotEmpty())
                <section>
                    <div class="flex items-center justify-between gap-4 mb-4">
                        <div>
                            <h3 class="text-xl sm:text-2xl font-extrabold text-brand-ink">Riwayat Pesanan</h3>
                            <p class="text-sm text-brand-muted mt-1">Pesanan yang sudah selesai atau dibatalkan.</p>
                        </div>
                        <span class="rounded-full bg-brand-cream border border-brand-line text-brand-ink px-3 py-1 text-xs font-bold">{{ $historyOrders->count() }} riwayat</span>
                    </div>

                    <div class="space-y-3">
                        @foreach($historyOrders as $order)
                            @php
                                $meta = $statusMeta[$order->status] ?? $statusMeta['pending'];
                            @endphp
                            <article class="bg-white border border-brand-line rounded-2xl shadow-sm overflow-hidden order-card" data-order-id="{{ $order->id }}" data-status="{{ $order->status }}">
                                <button type="button" class="history-toggle w-full p-4 sm:p-5 text-left flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3" aria-expanded="false">
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h4 class="font-extrabold text-brand-ink">Order #{{ $order->id }}</h4>
                                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold {{ $meta['badge'] }}">
                                                <span class="w-2 h-2 rounded-full {{ $meta['dot'] }}"></span>
                                                {{ $meta['label'] }}
                                            </span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-brand-muted mt-1">
                                            {{ $order->customer_name ?? 'Tamu' }}
                                            @if($order->nomor_meja)
                                                <span class="mx-2 text-brand-line">|</span> Meja {{ $order->nomor_meja }}
                                            @endif
                                            <span class="mx-2 text-brand-line">|</span> {{ $order->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                    <div class="flex items-center justify-between sm:justify-end gap-4">
                                        <div class="sm:text-right">
                                            <p class="text-[10px] font-bold uppercase text-brand-muted">Total</p>
                                            <p class="text-lg font-extrabold text-brand-brown">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                        </div>
                                        <span class="history-chevron rounded-full border border-brand-line bg-brand-cream px-3 py-1 text-xs font-bold text-brand-ink">Detail</span>
                                    </div>
                                </button>

                                <div class="history-detail hidden border-t border-brand-line bg-brand-cream p-4 sm:p-5">
                                    <div class="space-y-3">
                                        @foreach($order->items as $item)
                                            <div class="flex justify-between gap-3 text-sm">
                                                <div>
                                                    <p class="font-bold text-brand-ink">{{ $item->menu->name ?? 'Menu' }}</p>
                                                    <p class="text-xs text-brand-muted">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                                    @if($item->keterangan)
                                                        <p class="text-xs text-brand-brown mt-1">Catatan: {{ $item->keterangan }}</p>
                                                    @endif
                                                </div>
                                                <p class="font-bold text-brand-ink whitespace-nowrap">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        @endif
    </main>
    <script>
        const currentOrderStatuses = {};

        document.querySelectorAll('.order-card').forEach((card) => {
            currentOrderStatuses[card.dataset.orderId] = card.dataset.status;
        });

        document.querySelectorAll('.history-toggle').forEach((button) => {
            button.addEventListener('click', () => {
                const card = button.closest('.order-card');
                const detail = card.querySelector('.history-detail');
                const chevron = card.querySelector('.history-chevron');
                const isOpen = !detail.classList.contains('hidden');

                detail.classList.toggle('hidden', isOpen);
                button.setAttribute('aria-expanded', String(!isOpen));
                chevron.textContent = isOpen ? 'Detail' : 'Tutup';
            });
        });

        function showOrderNotice(message) {
            const notice = document.getElementById('orderStatusNotice');
            notice.textContent = message;
            notice.classList.remove('hidden');
        }

        function pollOrderStatuses() {
            if (Object.keys(currentOrderStatuses).length === 0) {
                return;
            }

            fetch('/pesanan-saya/status', {
                headers: { 'Accept': 'application/json' },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (!data.success) return;

                    const changedOrder = data.orders.find((order) => {
                        return currentOrderStatuses[order.id] && currentOrderStatuses[order.id] !== order.status;
                    });

                    if (changedOrder) {
                        showOrderNotice(`Status pesanan #${changedOrder.id} berubah menjadi ${changedOrder.label}. Halaman diperbarui...`);
                        setTimeout(() => window.location.reload(), 1200);
                    }
                })
                .catch(() => {});
        }

        setInterval(pollOrderStatuses, 5000);
    </script>
</body>
</html>

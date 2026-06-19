<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') — Kopi Subroto</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bungee&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif; }

        /* ═══════ Sidebar ═══════ */
        .admin-sidebar {
            background: linear-gradient(180deg, var(--brand-deep) 0%, #174740 100%);
            width: 260px;
            min-height: 100vh;
            position: fixed;
            left: 0; top: 0;
            z-index: 50;
            transition: transform 0.3s cubic-bezier(.4,0,.2,1);
            display: flex;
            flex-direction: column;
        }
        .admin-sidebar.collapsed { transform: translateX(-260px); }

        /* Logo area */
        .sidebar-logo {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(237, 230, 214, 0.12);
        }
        .sidebar-logo .brand-emblem { width: 2.35rem; height: 2.35rem; font-size: 1.32rem; }
        .sidebar-logo .brand-wordmark { font-size: 1rem; color: var(--brand-parchment); }
        .sidebar-logo p {
            font-size: 0.65rem;
            color: rgba(237, 230, 214, 0.65);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 2px;
        }

        /* Nav items */
        .sidebar-nav { padding: 16px 12px; flex: 1; }
        .sidebar-nav .nav-label {
            font-size: 0.6rem;
            font-weight: 700;
            color: rgba(237, 230, 214, 0.48);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 12px 12px 6px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            border-radius: 12px;
            color: rgba(237, 230, 214, 0.72);
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
            position: relative;
            margin-bottom: 2px;
        }
        .nav-item:hover {
            background: rgba(237, 230, 214, 0.08);
            color: var(--brand-parchment);
        }
        .nav-item.active {
            background: linear-gradient(135deg, rgba(95, 168, 164, 0.22), rgba(237, 230, 214, 0.09));
            color: var(--brand-parchment);
            font-weight: 600;
        }
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 8px; bottom: 8px;
            width: 3px;
            background: linear-gradient(180deg, var(--brand-teal), var(--brand-parchment));
            border-radius: 0 4px 4px 0;
        }
        .nav-item .nav-icon {
            width: 20px; height: 20px;
            flex-shrink: 0;
            opacity: 0.7;
        }
        .nav-item.active .nav-icon { opacity: 1; }
        .nav-item .badge {
            margin-left: auto;
            background: var(--brand-brown);
            color: #fff;
            font-size: 0.6rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 99px;
            min-width: 18px;
            text-align: center;
        }

        /* User card at bottom */
        .sidebar-user {
            padding: 16px;
            border-top: 1px solid rgba(237, 230, 214, 0.12);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar-user .avatar {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--brand-teal), var(--brand-ink));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            color: #fff;
            flex-shrink: 0;
        }
        .sidebar-user .user-info { flex: 1; min-width: 0; }
        .sidebar-user .user-info .name {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--brand-parchment);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-user .user-info .role {
            font-size: 0.6rem;
            color: rgba(237, 230, 214, 0.58);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* ═══════ Main content ═══════ */
        .admin-main {
            margin-left: 260px;
            min-height: 100vh;
            background: var(--brand-cream);
            transition: margin-left 0.3s cubic-bezier(.4,0,.2,1);
        }
        .admin-main.expanded { margin-left: 0; }

        /* Top bar */
        .admin-topbar {
            background: rgba(255, 255, 255, 0.88);
            border-bottom: 1px solid var(--brand-line);
            padding: 0 28px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 30;
        }
        .topbar-left { display: flex; align-items: center; gap: 16px; }
        .topbar-left .page-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--brand-ink);
        }
        .topbar-left .breadcrumb {
            font-size: 0.75rem;
            color: #94a3b8;
        }
        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: #475569;
            cursor: pointer;
            padding: 6px;
            border-radius: 8px;
        }
        .menu-toggle:hover { background: var(--brand-teal-soft); }

        /* ═══════ Stat cards ═══════ */
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid var(--brand-line);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
        }
        .stat-card.amber::before,
        .stat-card.emerald::before,
        .stat-card.blue::before,
        .stat-card.rose::before { background: linear-gradient(90deg, var(--brand-teal), var(--brand-parchment)); }
        .stat-card .stat-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 14px;
        }
        .stat-card.amber .stat-icon,
        .stat-card.emerald .stat-icon,
        .stat-card.blue .stat-icon,
        .stat-card.rose .stat-icon { background: var(--brand-teal-soft); color: var(--brand-ink); }
        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--brand-ink);
            line-height: 1;
            margin-bottom: 4px;
        }
        .stat-card .stat-label {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
        }

        /* ═══════ Table styles ═══════ */
        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .data-table thead th {
            background: var(--brand-cream);
            padding: 12px 16px;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--brand-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: left;
            border-bottom: 1px solid var(--brand-line);
        }
        .data-table tbody td {
            padding: 14px 16px;
            font-size: 0.85rem;
            color: var(--brand-ink);
            border-bottom: 1px solid var(--brand-line);
        }
        .data-table tbody tr {
            transition: background 0.15s;
        }
        .data-table tbody tr:hover {
            background: var(--brand-cream);
        }

        /* ═══════ Status badges ═══════ */
        .badge-status {
            padding: 4px 12px;
            border-radius: 99px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .badge-status::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .badge-pending { background: #f1dfd2; color: var(--brand-brown-dark); }
        .badge-pending::before { background: var(--brand-brown); }
        .badge-processing { background: var(--brand-teal-soft); color: var(--brand-ink); }
        .badge-processing::before { background: var(--brand-teal); }
        .badge-completed { background: #dcefe2; color: var(--brand-ink); }
        .badge-completed::before { background: #6c9c80; }
        .badge-cancelled { background: #ead7d5; color: var(--brand-maroon); }
        .badge-cancelled::before { background: var(--brand-maroon); }

        /* ═══════ Buttons ═══════ */
        .btn {
            padding: 8px 18px;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-primary, .btn-success, .btn-amber { background: var(--brand-brown); color: #fff; }
        .btn-primary:hover, .btn-success:hover, .btn-amber:hover { background: var(--brand-brown-dark); transform: translateY(-1px); }
        .btn-danger { background: var(--brand-maroon); color: #fff; }
        .btn-danger:hover { opacity: 0.9; }
        .btn-outline {
            background: transparent;
            border: 1.5px solid var(--brand-line);
            color: var(--brand-ink);
        }
        .btn-outline:hover { background: var(--brand-teal-soft); border-color: var(--brand-teal); }
        .btn-sm { padding: 5px 12px; font-size: 0.72rem; border-radius: 8px; }

        /* ═══════ Content card ═══════ */
        .content-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid var(--brand-line);
            overflow: hidden;
        }
        .content-card .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--brand-line);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .content-card .card-header h3 {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--brand-ink);
        }
        .content-card .card-body { padding: 0; }
        .content-card .card-body.padded { padding: 24px; }

        /* ═══════ Content area ═══════ */
        .admin-content { padding: 28px; }

        /* ═══════ Alert ═══════ */
        .alert {
            padding: 14px 20px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success { background: var(--brand-teal-soft); color: var(--brand-ink); border: 1px solid var(--brand-teal); }
        .alert-danger { background: #ead7d5; color: var(--brand-maroon); border: 1px solid #d7aaa6; }

        /* ═══════ Pagination ═══════ */
        .pagination-wrapper nav span, .pagination-wrapper nav a {
            border-radius: 8px !important;
        }

        /* ═══════ Responsive ═══════ */
        @media (max-width: 1024px) {
            .admin-sidebar { transform: translateX(-260px); }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-main { margin-left: 0; }
            .menu-toggle { display: block; }
        }

        /* ═══════ Overlay (mobile) ═══════ */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 40;
        }
        .sidebar-overlay.show { display: block; }

        /* ═══════ Animations ═══════ */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeInUp 0.4s ease forwards;
        }
        .animate-in:nth-child(1) { animation-delay: 0.05s; }
        .animate-in:nth-child(2) { animation-delay: 0.1s; }
        .animate-in:nth-child(3) { animation-delay: 0.15s; }
        .animate-in:nth-child(4) { animation-delay: 0.2s; }

        input, select, textarea {
            color: var(--brand-ink);
            border-color: var(--brand-line);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-brand-cream antialiased">

    <!-- Sidebar Overlay (mobile) -->
    <div id="sidebarOverlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- ═══════ SIDEBAR ═══════ -->
    <aside id="adminSidebar" class="admin-sidebar">
        <!-- Logo -->
        <div class="sidebar-logo">
            <div class="brand-lockup">
                <span class="brand-emblem" aria-hidden="true">S</span>
                <div class="flex flex-col justify-center min-w-0">
                    <h1 class="brand-wordmark">Kopi Subroto</h1>
                    <p class="brand-subtitle">Admin Panel</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <div class="nav-label">Menu Utama</div>

            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('admin.menu.index') }}" class="nav-item {{ request()->routeIs('admin.menu.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Kelola Menu
            </a>

            <div class="nav-label" style="margin-top: 8px;">Pesanan & Transaksi</div>

            <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Pesanan
                @php
                    $pendingCount = \App\Models\Order::where('status', 'pending')->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="badge">{{ $pendingCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.transactions.index') }}" class="nav-item {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Transaksi
            </a>

            <div class="nav-label" style="margin-top: 8px;">Lainnya</div>

            <a href="{{ route('admin.qrcode') }}" class="nav-item {{ request()->routeIs('admin.qrcode') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                QR Code Meja
            </a>

            <a href="{{ route('admin.store.preview') }}" class="nav-item" target="_blank">
                <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Lihat Toko
            </a>
        </nav>

        <!-- User card -->
        <div class="sidebar-user">
            <div class="avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
            <div class="user-info">
                <div class="name">{{ Auth::user()->name ?? 'Admin' }}</div>
                <div class="role">{{ Auth::user()->role ?? 'admin' }}</div>
            </div>
            <form method="POST" action="/logout" style="margin:0;">
                @csrf
                <button type="submit" style="background:none; border:none; cursor:pointer; color:#64748b; padding:4px;" title="Logout">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </aside>

    <!-- ═══════ MAIN CONTENT ═══════ -->
    <main id="adminMain" class="admin-main">
        <!-- Top bar -->
        <div class="admin-topbar">
            <div class="topbar-left">
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <div class="page-title">@yield('page-title', 'Dashboard')</div>
                    <div class="breadcrumb">@yield('breadcrumb', 'Admin / Dashboard')</div>
                </div>
            </div>
            <div class="topbar-right">
                <span style="font-size:0.75rem; color:#94a3b8;">{{ now()->translatedFormat('l, d M Y') }}</span>
            </div>
        </div>

        <!-- Page content -->
        <div class="admin-content">
            @if(session('success'))
                <div class="alert alert-success">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const main = document.getElementById('adminMain');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        }
    </script>
    @stack('scripts')
</body>
</html>

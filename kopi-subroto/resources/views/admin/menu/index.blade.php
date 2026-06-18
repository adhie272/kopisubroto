@extends('admin.layouts.app')

@section('title', 'Kelola Menu')
@section('page-title', 'Kelola Menu')
@section('breadcrumb', 'Admin / Menu')

@section('content')
<!-- ═══════ HEADER ═══════ -->
<div class="flex justify-between items-center mb-6">
    <div>
        <p style="font-size:0.85rem; color:#64748b;">Total {{ $menus->total() }} menu items</p>
    </div>
    <a href="{{ route('admin.menu.create') }}" class="btn btn-primary">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Menu
    </a>
</div>

<!-- ═══════ TABLE ═══════ -->
<div class="content-card">
    <div class="card-body">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Gambar</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($menus as $menu)
                <tr>
                    <td style="font-weight:600;">{{ $loop->iteration + ($menus->currentPage() - 1) * $menus->perPage() }}</td>
                    <td>
                        <img src="{{ asset('images/' . $menu->image) }}"
                             alt="{{ $menu->name }}"
                             style="width:48px; height:48px; object-fit:cover; border-radius:10px; border:2px solid #f1f5f9;">
                    </td>
                    <td style="font-weight:700; color:var(--brand-ink);">{{ $menu->name }}</td>
                    <td>
                        @php
                            $catColors = [
                                'coffee' => 'background:var(--brand-teal-soft); color:var(--brand-ink);',
                                'snack' => 'background:#f1dfd2; color:var(--brand-brown-dark);',
                                'others' => 'background:var(--brand-cream); color:var(--brand-ink); border:1px solid var(--brand-line);',
                            ];
                        @endphp
                        <span style="{{ $catColors[$menu->category] ?? '' }} padding:3px 12px; border-radius:99px; font-size:0.72rem; font-weight:600;">
                            {{ ucfirst($menu->category) }}
                        </span>
                    </td>
                    <td style="font-weight:700; color:var(--brand-brown);">
                        Rp {{ number_format($menu->price, 0, ',', '.') }}
                    </td>
                    <td style="font-size:0.8rem; color:#64748b; max-width:180px;">
                        <div style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $menu->description }}</div>
                    </td>
                    <td style="text-align:center;">
                        <button onclick="toggleStatus({{ $menu->id }})"
                                class="toggle-btn badge-status {{ $menu->is_active ? 'badge-completed' : 'badge-cancelled' }}"
                                style="cursor:pointer; border:none;"
                                data-menu-id="{{ $menu->id }}"
                                data-is-active="{{ $menu->is_active ? 1 : 0 }}">
                            {{ $menu->is_active ? 'Aktif' : 'Nonaktif' }}
                        </button>
                    </td>
                    <td style="text-align:center;">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.menu.edit', $menu->id) }}" class="btn btn-primary btn-sm">
                                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.menu.destroy', $menu->id) }}" style="display:inline;" onsubmit="return confirm('Yakin hapus menu ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:50px; color:#94a3b8;">
                        <div style="font-size:2.5rem; margin-bottom:10px;">🍽️</div>
                        <div style="font-weight:600;">Tidak ada menu</div>
                        <a href="{{ route('admin.menu.create') }}" style="color:var(--brand-ink); font-weight:600; font-size:0.85rem;">Tambah yang pertama →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-6 pagination-wrapper">
    {{ $menus->links() }}
</div>
@endsection

@push('scripts')
<script>
    const CSRF_TOKEN = '{{ csrf_token() }}';

    function toggleStatus(menuId) {
        fetch(`/admin/menu/${menuId}/toggle-active`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endpush

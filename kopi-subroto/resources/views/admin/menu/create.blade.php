@extends('admin.layouts.app')

@section('title', 'Tambah Menu')
@section('page-title', 'Tambah Menu Baru')
@section('breadcrumb', 'Admin / Menu / Tambah')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.menu.index') }}" class="btn btn-outline btn-sm">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Menu
    </a>
</div>

<div class="content-card" style="max-width:680px;">
    <div class="card-header">
        <h3>🍽️ Form Tambah Menu</h3>
    </div>
    <div class="card-body padded">
        <form method="POST" action="{{ route('admin.menu.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Nama -->
            <div>
                <label style="font-size:0.78rem; font-weight:600; color:var(--brand-ink); display:block; margin-bottom:6px;">Nama Menu <span style="color:var(--brand-maroon);">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-teal focus:border-transparent outline-none @error('name') border-red-400 @enderror"
                    placeholder="Contoh: Espresso">
                @error('name')
                <p style="color:var(--brand-maroon); font-size:0.75rem; margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori -->
            <div>
                <label style="font-size:0.78rem; font-weight:600; color:var(--brand-ink); display:block; margin-bottom:6px;">Kategori <span style="color:var(--brand-maroon);">*</span></label>
                <select name="category" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-teal focus:border-transparent outline-none @error('category') border-red-400 @enderror">
                    <option value="">— Pilih Kategori —</option>
                    <option value="coffee" {{ old('category') == 'coffee' ? 'selected' : '' }}>☕ Coffee</option>
                    <option value="snack" {{ old('category') == 'snack' ? 'selected' : '' }}>🍰 Snack</option>
                    <option value="others" {{ old('category') == 'others' ? 'selected' : '' }}>🥤 Lainnya</option>
                </select>
                @error('category')
                <p style="color:var(--brand-maroon); font-size:0.75rem; margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Harga -->
            <div>
                <label style="font-size:0.78rem; font-weight:600; color:var(--brand-ink); display:block; margin-bottom:6px;">Harga (Rp) <span style="color:var(--brand-maroon);">*</span></label>
                <input type="number" name="price" value="{{ old('price') }}" required min="1000" step="1000"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-teal focus:border-transparent outline-none @error('price') border-red-400 @enderror"
                    placeholder="Contoh: 20000">
                @error('price')
                <p style="color:var(--brand-maroon); font-size:0.75rem; margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image -->
            <div>
                <label style="font-size:0.78rem; font-weight:600; color:var(--brand-ink); display:block; margin-bottom:6px;">Upload Gambar <span style="color:var(--brand-maroon);">*</span></label>
                <input type="file" name="image" required accept="image/*"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-teal @error('image') border-red-400 @enderror">
                <p style="font-size:0.7rem; color:#94a3b8; margin-top:4px;">Format: JPG, PNG, JPEG. Maks: 2MB.</p>
                @error('image')
                <p style="color:var(--brand-maroon); font-size:0.75rem; margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div>
                <label style="font-size:0.78rem; font-weight:600; color:var(--brand-ink); display:block; margin-bottom:6px;">Deskripsi <span style="color:var(--brand-maroon);">*</span></label>
                <textarea name="description" required rows="4"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-teal focus:border-transparent outline-none @error('description') border-red-400 @enderror"
                    placeholder="Contoh: Kopi hitam pekat dengan aroma kuat">{{ old('description') }}</textarea>
                @error('description')
                <p style="color:var(--brand-maroon); font-size:0.75rem; margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="flex items-center gap-3">
                <input type="checkbox" id="is_active" name="is_active" value="1" checked
                    class="w-4 h-4 text-brand-brown rounded focus:ring-brand-teal">
                <label for="is_active" style="font-size:0.82rem; color:#475569;">Aktifkan menu ini</label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn btn-primary flex-1 justify-center">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Menu
                </button>
                <a href="{{ route('admin.menu.index') }}" class="btn btn-outline flex-1 justify-center">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

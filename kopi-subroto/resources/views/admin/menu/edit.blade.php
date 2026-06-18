@extends('admin.layouts.app')

@section('title', 'Edit Menu')
@section('page-title', 'Edit Menu')
@section('breadcrumb', 'Admin / Menu / Edit')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.menu.index') }}" class="btn btn-outline btn-sm">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Menu
    </a>
</div>

<div class="content-card" style="max-width:680px;">
    <div class="card-header">
        <h3>Form Edit Menu</h3>
    </div>
    <div class="card-body padded">
        <form method="POST" action="{{ route('admin.menu.update', $menu->id) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label style="font-size:0.78rem; font-weight:600; color:var(--brand-ink); display:block; margin-bottom:6px;">Nama Menu <span style="color:var(--brand-maroon);">*</span></label>
                <input type="text" name="name" value="{{ old('name', $menu->name) }}" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-teal focus:border-transparent outline-none @error('name') border-red-400 @enderror"
                    placeholder="Contoh: Espresso">
                @error('name')
                <p style="color:var(--brand-maroon); font-size:0.75rem; margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label style="font-size:0.78rem; font-weight:600; color:var(--brand-ink); display:block; margin-bottom:6px;">Kategori <span style="color:var(--brand-maroon);">*</span></label>
                <select name="category" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-teal focus:border-transparent outline-none @error('category') border-red-400 @enderror">
                    <option value="coffee" {{ old('category', $menu->category) == 'coffee' ? 'selected' : '' }}>Coffee</option>
                    <option value="snack" {{ old('category', $menu->category) == 'snack' ? 'selected' : '' }}>Snack</option>
                    <option value="others" {{ old('category', $menu->category) == 'others' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('category')
                <p style="color:var(--brand-maroon); font-size:0.75rem; margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label style="font-size:0.78rem; font-weight:600; color:var(--brand-ink); display:block; margin-bottom:6px;">Harga (Rp) <span style="color:var(--brand-maroon);">*</span></label>
                <input type="number" name="price" value="{{ old('price', $menu->price) }}" required min="1000" step="1000"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-teal focus:border-transparent outline-none @error('price') border-red-400 @enderror"
                    placeholder="Contoh: 20000">
                @error('price')
                <p style="color:var(--brand-maroon); font-size:0.75rem; margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label style="font-size:0.78rem; font-weight:600; color:var(--brand-ink); display:block; margin-bottom:6px;">Upload Gambar Menu</label>
                <input type="file" name="image" accept="image/*"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-teal @error('image') border-red-400 @enderror">
                <p style="font-size:0.7rem; color:var(--brand-muted); margin-top:4px;">Kosongkan jika tidak ingin mengubah gambar. Format: JPG, PNG, JPEG. Maks: 2MB.</p>
                @if($menu->image)
                    <div class="mt-3">
                        <p style="font-size:0.78rem; color:var(--brand-muted); margin-bottom:6px;">Gambar saat ini:</p>
                        <img src="{{ $menu->image_url }}" alt="Current Image" style="width:128px; height:128px; object-fit:cover; border-radius:12px; border:1px solid var(--brand-line);">
                    </div>
                @endif
                @error('image')
                <p style="color:var(--brand-maroon); font-size:0.75rem; margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label style="font-size:0.78rem; font-weight:600; color:var(--brand-ink); display:block; margin-bottom:6px;">Deskripsi <span style="color:var(--brand-maroon);">*</span></label>
                <textarea name="description" required rows="4"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-teal focus:border-transparent outline-none @error('description') border-red-400 @enderror"
                    placeholder="Contoh: Kopi hitam pekat dengan aroma kuat">{{ old('description', $menu->description) }}</textarea>
                @error('description')
                <p style="color:var(--brand-maroon); font-size:0.75rem; margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn btn-primary flex-1 justify-center">Update Menu</button>
                <a href="{{ route('admin.menu.index') }}" class="btn btn-outline flex-1 justify-center">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

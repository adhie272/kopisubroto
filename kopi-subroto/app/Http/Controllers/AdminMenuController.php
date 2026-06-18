<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class AdminMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Tampilkan daftar menu
     */
    public function index()
    {
        $menus = Menu::paginate(10);
        return view('admin.menu.index', compact('menus'));
    }

    /**
     * Tampilkan form create
     */
    public function create()
    {
        $categories = ['coffee', 'snack', 'others'];
        return view('admin.menu.create', compact('categories'));
    }

    /**
     * Simpan menu baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:menus|max:100',
            'price' => 'required|numeric|min:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'required|string|max:255',
            'category' => 'required|in:coffee,snack,others',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $validated['image'] = $imageName;
        }

        Menu::create($validated);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit
     */
    public function edit(Menu $menu)
    {
        $categories = ['coffee', 'snack', 'others'];
        return view('admin.menu.edit', compact('menu', 'categories'));
    }

    /**
     * Update menu
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:menus,name,' . $menu->id,
            'price' => 'required|numeric|min:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'required|string|max:255',
            'category' => 'required|in:coffee,snack,others',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($menu->image && file_exists(public_path('images/' . $menu->image))) {
                unlink(public_path('images/' . $menu->image));
            }
            
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $validated['image'] = $imageName;
        } else {
            // Jika tidak upload gambar baru, hapus 'image' dari array agar tidak null
            unset($validated['image']);
        }

        $menu->update($validated);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil diupdate!');
    }

    /**
     * Hapus menu
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil dihapus!');
    }

    /**
     * Toggle is_active status (AJAX)
     */
    public function toggleActive(Menu $menu)
    {
        $menu->update(['is_active' => !$menu->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $menu->is_active,
            'message' => 'Status berhasil diubah'
        ]);
    }
}

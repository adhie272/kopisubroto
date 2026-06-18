# 🚀 QUICK START GUIDE - Kopi Subroto Menu & Cart System

## ⚡ Setup Awal (Hanya Sekali)

```bash
cd c:\laragon\www\kopi-subroto

# Jalankan migration dan seed
php artisan migrate:refresh --seed
```

✅ **Status**: Database siap dengan 13 menu items (6 coffee, 4 snack, 3 others)

---

## 🎯 Menjalankan Aplikasi

### Opsi 1: Menggunakan PHP Built-in Server
```bash
cd c:\laragon\www\kopi-subroto
php artisan serve
```
Akses di: `http://localhost:8000`

### Opsi 2: Menggunakan Laragon (Recommended)
1. Buka Laragon
2. Pastikan Apache dan MySQL sudah running
3. Akses di: `http://kopi-subroto.test/` atau `http://localhost`

---

## 🎮 Fitur yang Sudah Aktif

### ✅ Kategori Menu
- Klik tab **Coffee**, **Snack**, atau **Lainnya**
- Menu akan otomatis berganti via AJAX
- Tidak perlu reload halaman

### ✅ Tambah ke Keranjang
- Klik tombol **+ Tambah** pada setiap menu
- Item langsung ditambahkan ke keranjang
- Badge di navbar menampilkan jumlah item

### ✅ Buka Keranjang
- Klik **ikon keranjang** di navbar kanan atas
- Modal keranjang terbuka dengan semua items
- Lihat harga total

### ✅ Edit Keranjang
- **+**: Tambah 1 qty item
- **-**: Kurang 1 qty item
- **🗑️**: Hapus item sepenuhnya
- **Kosongkan Keranjang**: Hapus semua items
- Total harga otomatis terupdate

---

## 📊 Data Menu Tersedia

### ☕ COFFEE (Tab Pertama)
```
1. Espresso                  → Rp 18.000
2. Cappuccino               → Rp 25.000
3. Cafe Latte               → Rp 28.000
4. Americano                → Rp 20.000
5. Caramel Macchiato        → Rp 32.000
6. Mocha Latte              → Rp 30.000
```

### 🍪 SNACK (Tab Kedua)
```
1. Croissant Butter         → Rp 22.000
2. Donut Coklat             → Rp 15.000
3. Roti Bakar Selai         → Rp 12.000
4. Sandwich Tuna            → Rp 28.000
```

### 🥤 LAINNYA (Tab Ketiga)
```
1. Jus Jeruk Segar          → Rp 16.000
2. Teh Manis                → Rp 8.000
3. Smoothie Strawberry      → Rp 24.000
```

---

## 🔧 MVC Architecture Overview

### Model (Data Layer)
- **Menu.php**: Representasi produk menu
  - Fields: name, price, image, description, category, is_active
  - Scopes: `byCategory()`, `active()`

- **Cart.php**: Representasi item dalam keranjang
  - Fields: menu_id, quantity, price, session_id
  - Relations: `belongsTo(Menu)`
  - Methods: `getTotalPrice()`, `bySession()`

### Controller (Business Logic)
- **MenuController.php** (7 methods):
  1. `index()` - Load halaman utama
  2. `getMenuByCategory($category)` - Get menu per kategori (AJAX)
  3. `addToCart()` - Tambah item ke cart
  4. `viewCart()` - Lihat isi cart (AJAX)
  5. `updateCartQuantity()` - Update qty (AJAX)
  6. `removeFromCart()` - Hapus item (AJAX)
  7. `clearCart()` - Kosongkan cart (AJAX)

### View (Presentation Layer)
- **welcome.blade.php**:
  - Navigation Bar (fixed)
  - Category Tabs (sticky)
  - Menu Grid (dynamic AJAX)
  - Cart Modal (interactive)
  - 250+ lines JavaScript untuk interaksi

### Routes (API Endpoints)
```
GET  /                    → Tampilkan halaman utama
GET  /menu/{category}     → AJAX get menu by category
POST /cart/add            → AJAX tambah ke cart
GET  /cart/view           → AJAX lihat keranjang
POST /cart/{id}/update    → AJAX update qty
DELETE /cart/{id}/remove  → AJAX hapus item
DELETE /cart/clear        → AJAX kosongkan cart
```

---

## 📂 File Penting

| File | Fungsi |
|------|--------|
| `app/Models/Menu.php` | Model Menu dengan scopes |
| `app/Models/Cart.php` | Model Cart dengan relasi |
| `app/Http/Controllers/MenuController.php` | 7 controller methods |
| `routes/web.php` | 7 API routes |
| `resources/views/welcome.blade.php` | View + JavaScript 250+ lines |
| `database/migrations/2024_04_24_*.php` | 2 tabel (menus, carts) |
| `database/seeders/MenuSeeder.php` | 13 data menu |

---

## 🐛 Troubleshooting

### ❌ Menu tidak tampil
```
✅ Solusi:
- Pastikan database di-seed: php artisan migrate:refresh --seed
- Cek file di public/images/ ada gambar menu
- Reload halaman (Ctrl+F5)
```

### ❌ Keranjang tidak bisa dibuka
```
✅ Solusi:
- Cek browser developer console (F12) ada error?
- Clear cache browser (Ctrl+Shift+Delete)
- Pastikan Laravel server running (php artisan serve)
```

### ❌ Item tidak bisa ditambah
```
✅ Solusi:
- Pastikan CSRF token di-load: Buka inspect > Console > csrf_token
- Check Laravel server logs
- Coba pada browser/incognito baru
```

### ❌ Gambar tidak muncul
```
✅ Solusi:
File gambar harus di public/images/ dengan nama:
- espresso.jpg, cappuccino.jpg, cafe_latte.jpg
- americano.jpg, caramel_macchiato.jpg, mocha_latte.jpg
- croissant.jpg, donut.jpg, roti_bakar.jpg
- sandwich.jpg, jus_jeruk.jpg, teh_manis.jpg
- smoothie.jpg
```

---

## 🧪 Testing Manual

### Test 1: Lihat Menu Coffee
1. ✅ Buka http://localhost:8000/
2. ✅ Lihat 6 menu coffee
3. ✅ Harga format: Rp 18.000 (dengan titik)
4. ✅ Ada tombol "+ Tambah" untuk setiap item

### Test 2: Berpindah Kategori
1. ✅ Klik tab "🍪 Snack"
2. ✅ Menu berubah ke 4 snack items (tanpa reload)
3. ✅ Klik tab "🥤 Lainnya"
4. ✅ Menu berubah ke 3 other items

### Test 3: Tambah ke Cart
1. ✅ Di tab Coffee, klik "+ Tambah" pada Espresso
2. ✅ Toast notification muncul "Espresso ditambahkan ke keranjang"
3. ✅ Badge di navbar berubah dari 0 → 1
4. ✅ Tambah lagi Cappuccino
5. ✅ Badge berubah 1 → 2

### Test 4: Buka & Edit Keranjang
1. ✅ Klik ikon keranjang di navbar
2. ✅ Modal terbuka menampilkan 2 items (Espresso + Cappuccino)
3. ✅ Tampil harga: Rp 18.000 dan Rp 25.000
4. ✅ Total harga: Rp 43.000
5. ✅ Klik "+" untuk Espresso 1 → 2
6. ✅ Total berubah: Rp 43.000 → Rp 61.000
7. ✅ Badge di navbar berubah 2 → 3

### Test 5: Hapus & Kosongkan
1. ✅ Di modal cart, klik "🗑️" pada Cappuccino
2. ✅ Item dihapus, total jadi Rp 36.000
3. ✅ Badge navbar berubah 3 → 2
4. ✅ Klik "Kosongkan Keranjang"
5. ✅ Konfirmasi dialog muncul
6. ✅ Klik OK
7. ✅ Cart kosong, badge jadi 0, modal tampil "Keranjang kosong"

---

## 📝 Catatan Penting

1. **Session-based Cart**: Cart di-track per session, bukan per user
   - Untuk production dengan login, ubah ke user_id

2. **AJAX Requests**: Semua fitur kategori + cart menggunakan AJAX
   - Tidak perlu reload halaman
   - Response dalam format JSON

3. **Real-time Updates**: 
   - Badge cart update real-time
   - Total harga terupdate saat qty berubah
   - Toast notifikasi untuk feedback

4. **Responsive Design**:
   - Mobile: 1 kolom menu
   - Tablet: 2 kolom menu
   - Desktop: 3 kolom menu

5. **Database Relations**:
   - Tabel menus: 13 records (6+4+3)
   - Tabel carts: dynamic, di-populate saat user tambah item
   - Foreign key: carts.menu_id → menus.id (cascade delete)

---

## 🎓 Pembelajaran

Kode ini mengimplementasikan:
- ✅ MVC Architecture (Model-View-Controller)
- ✅ RESTful API Principles
- ✅ AJAX dengan Fetch API
- ✅ Database Relationships (Foreign Keys)
- ✅ Eloquent ORM (Scopes, Query Builder)
- ✅ Laravel Blade Templating
- ✅ Form Validation
- ✅ Session Management
- ✅ CSRF Protection
- ✅ Error Handling

---

## 📚 Dokumentasi Lengkap

Baca file-file dokumentasi untuk detail lebih lanjut:
- **DOKUMENTASI_MVC.md** - Penjelasan MVC detail
- **FITUR_BARU.md** - Panduan penggunaan fitur
- **STRUKTUR_PROJECT.md** - Struktur folder & API endpoints
- **README.md** - Original project readme

---

**Happy Coding! ☕✨**

Dibuat untuk: **Kopi Subroto Coffee Shop**
Tanggal: **24 April 2026**

# Dokumentasi Sistem Menu & Cart - Kopi Subroto

## 📋 Struktur MVC

### 1. MODEL (app/Models/)

#### **Menu.php**
- Merepresentasikan produk menu (Coffee, Snack, Others)
- Fields:
  - `id`: Primary key
  - `name`: Nama produk
  - `price`: Harga (decimal)
  - `image`: Nama file gambar
  - `description`: Deskripsi produk
  - `category`: Kategori (coffee, snack, others)
  - `is_active`: Status aktif/nonaktif
  - `timestamps`: created_at, updated_at

- Methods:
  - `scopeByCategory()`: Filter menu berdasarkan kategori
  - `scopeActive()`: Filter menu yang aktif

#### **Cart.php**
- Merepresentasikan item dalam keranjang belanja
- Fields:
  - `id`: Primary key
  - `menu_id`: Foreign key ke Menu
  - `quantity`: Jumlah item
  - `price`: Harga item (snapshot dari harga saat ditambahkan)
  - `session_id`: ID session untuk tracking user
  - `timestamps`: created_at, updated_at

- Relations:
  - `belongsTo(Menu)`: Relasi ke Menu

- Methods:
  - `getTotalPrice()`: Hitung total harga item (price × quantity)
  - `scopeBySession()`: Filter cart berdasarkan session

### 2. CONTROLLER (app/Http/Controllers/)

#### **MenuController.php**

**Methods:**

1. **index()**
   - Route: `GET /`
   - Menampilkan halaman welcome dengan menu berdasarkan kategori
   - Return: View welcome dengan data coffeeMenus, snackMenus, othersMenus, cartCount

2. **getMenuByCategory($category)**
   - Route: `GET /menu/{category}`
   - AJAX endpoint untuk mengambil menu berdasarkan kategori
   - Parameter: coffee, snack, others
   - Return: JSON response dengan data menu

3. **addToCart(Request $request)**
   - Route: `POST /cart/add`
   - Tambahkan item ke cart
   - Input: `menu_id`, `quantity`
   - Logic: Cek apakah item sudah ada di cart, jika ada update quantity, jika tidak buat baru
   - Return: JSON success message dengan cartCount terbaru

4. **viewCart()**
   - Route: `GET /cart/view`
   - Ambil semua item cart untuk session user
   - Include relasi ke Menu untuk nama dan detail
   - Return: JSON dengan items dan total harga

5. **updateCartQuantity(Request $request, $cartId)**
   - Route: `POST /cart/{id}/update`
   - Update jumlah item di cart
   - Input: `quantity` (minimal 1)
   - Return: JSON success message dengan cartCount terbaru

6. **removeFromCart($cartId)**
   - Route: `DELETE /cart/{id}/remove`
   - Hapus item dari cart
   - Return: JSON success message dengan cartCount terbaru

7. **clearCart()**
   - Route: `DELETE /cart/clear`
   - Kosongkan semua isi cart
   - Return: JSON success message

### 3. VIEW (resources/views/)

#### **welcome.blade.php**
- Tampilan utama aplikasi
- Fitur:
  - Navigation bar dengan logo dan cart button
  - Category tabs (Coffee, Snack, Lainnya) yang dapat diklik
  - Menu grid yang dinamis (di-load via AJAX)
  - Modal keranjang belanja dengan:
    - List item dengan jumlah dan harga
    - Tombol +/- untuk update quantity
    - Tombol delete untuk hapus item
    - Total harga
    - Button clear cart dan pesan sekarang

#### **JavaScript Functionality:**

1. **setupEventListeners()**
   - Setup event listener untuk kategori buttons
   - Setup event listener untuk cart button
   - Setup event listener untuk modal close

2. **loadMenuByCategory(category)**
   - Fetch menu dari server berdasarkan kategori
   - Trigger renderMenu() dengan data yang diterima

3. **renderMenu(menus)**
   - Render menu items di container
   - Attach event listener ke tombol "Tambah"

4. **addToCart(menuId, menuName)**
   - Fetch POST ke /cart/add
   - Update cart count badge
   - Tampilkan toast notification

5. **openCart()**
   - Fetch GET ke /cart/view
   - Render cart items dengan quantity controls
   - Tampilkan modal

6. **renderCart(items, total)**
   - Render setiap item dengan controls (-, qty, +)
   - Attach event listener untuk quantity update
   - Tampilkan total harga

7. **updateCartQuantity(cartId, quantity)**
   - Fetch POST ke /cart/{id}/update
   - Refresh cart display

8. **removeFromCart(cartId)**
   - Fetch DELETE ke /cart/{id}/remove
   - Refresh cart display

9. **clearCart()**
   - Konfirmasi dengan user
   - Fetch DELETE ke /cart/clear
   - Clear cart display

### 4. ROUTES (routes/web.php)

```php
// Menu Routes
Route::get('/', [MenuController::class, 'index'])->name('home');
Route::get('/menu/{category}', [MenuController::class, 'getMenuByCategory'])->name('menu.category');

// Cart Routes
Route::post('/cart/add', [MenuController::class, 'addToCart'])->name('cart.add');
Route::get('/cart/view', [MenuController::class, 'viewCart'])->name('cart.view');
Route::post('/cart/{id}/update', [MenuController::class, 'updateCartQuantity'])->name('cart.update');
Route::delete('/cart/{id}/remove', [MenuController::class, 'removeFromCart'])->name('cart.remove');
Route::delete('/cart/clear', [MenuController::class, 'clearCart'])->name('cart.clear');
```

## 🗄️ DATABASE SCHEMA

### Tabel: menus
```sql
CREATE TABLE menus (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) UNIQUE NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    description TEXT,
    category ENUM('coffee', 'snack', 'others') DEFAULT 'coffee',
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX (category),
    INDEX (is_active)
);
```

### Tabel: carts
```sql
CREATE TABLE carts (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    menu_id BIGINT NOT NULL,
    quantity INT DEFAULT 1,
    price DECIMAL(10,2) NOT NULL,
    session_id VARCHAR(255) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE,
    INDEX (session_id),
    UNIQUE (menu_id, session_id)
);
```

## 📦 DATA FLOW

### Flow: User Melihat Menu
1. User buka halaman `/`
2. MenuController.index() mengambil menu per kategori dari DB
3. View render dengan coffee menu default
4. JavaScript sudah siap mendengarkan kategori button click

### Flow: User Berpindah Kategori
1. User klik button category (Snack/Lainnya)
2. JavaScript fetch `/menu/{category}`
3. MenuController.getMenuByCategory() return JSON
4. JavaScript render menu items baru

### Flow: User Tambah ke Cart
1. User klik tombol "+ Tambah"
2. JavaScript fetch `POST /cart/add` dengan menu_id dan quantity
3. MenuController.addToCart():
   - Validasi input
   - Cari Cart berdasarkan menu_id dan session_id
   - Jika exist: update quantity
   - Jika tidak: buat Cart baru
4. Return JSON dengan cartCount
5. JavaScript update badge dan tampilkan toast

### Flow: User Buka Keranjang
1. User klik tombol cart di navbar
2. JavaScript fetch `/cart/view`
3. MenuController.viewCart() ambil semua Cart item dengan relasi Menu
4. Return JSON dengan items dan total
5. JavaScript render modal dengan cart items

### Flow: User Update Quantity
1. User klik +/- button di modal cart
2. JavaScript fetch `POST /cart/{id}/update` dengan quantity baru
3. MenuController.updateCartQuantity() update quantity di DB
4. Return JSON dengan cartCount
5. JavaScript refresh cart display

### Flow: User Hapus Item
1. User klik tombol delete di item cart
2. JavaScript fetch `DELETE /cart/{id}/remove`
3. MenuController.removeFromCart() delete Cart record
4. Return JSON dengan cartCount
5. JavaScript refresh cart display

### Flow: User Kosongkan Keranjang
1. User klik "Kosongkan Keranjang"
2. Konfirmasi dialog muncul
3. JavaScript fetch `DELETE /cart/clear`
4. MenuController.clearCart() delete semua Cart dengan session_id
5. Return JSON dengan cartCount = 0
6. JavaScript update cart display

## 🔐 Security Features

1. **CSRF Protection**: Semua POST/DELETE request menggunakan X-CSRF-TOKEN
2. **Input Validation**: MenuController validasi input dengan request->validate()
3. **Session Tracking**: Cart di-track per session_id bukan user ID
4. **Foreign Key**: Relasi menu_id ke menus table mencegah orphan records

## 🎨 UI/UX Features

1. **Sticky Navigation**: Navbar tetap di atas saat scroll
2. **Sticky Categories**: Category tabs tetap terlihat saat scroll
3. **Modal Keranjang**: Keranjang dalam modal yang dapat di-scroll
4. **Toast Notification**: Feedback setiap aksi user (tambah item, delete, dll)
5. **Real-time Cart Count**: Badge di navbar update real-time
6. **Responsive Design**: Grid menu responsive (1 col mobile, 2 col tablet, 3 col desktop)
7. **Hover Effects**: Image zoom hover, button color change

## 🚀 Cara Jalankan

1. Jalankan migrations:
   ```bash
   php artisan migrate:refresh --seed
   ```

2. Start Laravel dev server:
   ```bash
   php artisan serve
   ```

3. Akses aplikasi di: `http://localhost:8000`

## 📝 Catatan Penting

- Cart di-track menggunakan session ID, bukan user login
- Untuk production, pertimbangkan untuk use user ID jika login di-require
- Gambar menu harus berada di folder `public/images/`
- Format mata uang menggunakan Rp dengan format Indonesian (ribuan dengan titik)

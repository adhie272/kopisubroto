# 📂 Struktur Project Kopi Subroto - Fitur Menu & Cart

```
kopi-subroto/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── MenuController.php ⭐ [MODIFIED]
│   │       │   ├── index() - Tampilkan menu welcome
│   │       │   ├── getMenuByCategory() - AJAX untuk kategori
│   │       │   ├── addToCart() - Tambah ke cart
│   │       │   ├── viewCart() - Lihat isi cart
│   │       │   ├── updateCartQuantity() - Update qty
│   │       │   ├── removeFromCart() - Hapus item
│   │       │   └── clearCart() - Kosongkan cart
│   │       └── ProfileController.php
│   ├── Models/
│   │   ├── Menu.php ⭐ [NEW]
│   │   │   ├── Relasi: -
│   │   │   ├── Scopes: byCategory(), active()
│   │   │   └── Attributes: name, price, image, category, etc
│   │   ├── Cart.php ⭐ [NEW]
│   │   │   ├── Relasi: belongsTo Menu
│   │   │   ├── Scopes: bySession()
│   │   │   └── Methods: getTotalPrice()
│   │   └── User.php
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   └── View/
│       └── Components/
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2024_04_24_000000_create_menus_table.php ⭐ [NEW]
│   │   │   └── Tabel menus dengan fields:
│   │   │       - id, name, price, image, description, category, is_active
│   │   └── 2024_04_24_000001_create_carts_table.php ⭐ [NEW]
│   │       └── Tabel carts dengan fields:
│   │           - id, menu_id, quantity, price, session_id
│   ├── seeders/
│   │   ├── DatabaseSeeder.php ⭐ [MODIFIED]
│   │   │   └── Tambah call MenuSeeder
│   │   └── MenuSeeder.php ⭐ [NEW]
│   │       └── Data 13 menu items (6 coffee, 4 snack, 3 others)
│   └── database.sqlite
├── public/
│   ├── images/ 📸
│   │   ├── espresso.jpg
│   │   ├── cappuccino.jpg
│   │   ├── cafe_latte.jpg
│   │   ├── americano.jpg
│   │   ├── caramel_macchiato.jpg
│   │   ├── mocha_latte.jpg
│   │   ├── croissant.jpg (baru)
│   │   ├── donut.jpg (baru)
│   │   ├── roti_bakar.jpg (baru)
│   │   ├── sandwich.jpg (baru)
│   │   ├── jus_jeruk.jpg (baru)
│   │   ├── teh_manis.jpg (baru)
│   │   └── smoothie.jpg (baru)
│   └── index.php
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views/
│       ├── welcome.blade.php ⭐ [MODIFIED]
│       │   ├── Navigation Bar (fixed top)
│       │   │   ├── Logo "Kopi Subroto"
│       │   │   └── Cart Button dengan badge count
│       │   ├── Category Tabs (sticky)
│       │   │   ├── Coffee Tab
│       │   │   ├── Snack Tab
│       │   │   └── Lainnya Tab
│       │   ├── Menu Grid Container
│       │   │   └── Menu Items (dinamis, di-load via AJAX)
│       │   ├── Cart Modal
│       │   │   ├── Header "🛒 Keranjang Belanja"
│       │   │   ├── Cart Items Container
│       │   │   │   └── Each Item:
│       │   │   │       - Nama item
│       │   │   │       - Harga
│       │   │   │       - Qty Controls (-, qty, +)
│       │   │   │       - Delete Button
│       │   │   └── Footer
│       │   │       ├── Total Harga
│       │   │       ├── Clear Cart Button
│       │   │       └── order Button
│       │   ├── Toast Notification
│       │   └── JavaScript Functions (250+ lines)
│       │       ├── setupEventListeners()
│       │       ├── loadMenuByCategory()
│       │       ├── renderMenu()
│       │       ├── addToCart()
│       │       ├── openCart()
│       │       ├── renderCart()
│       │       ├── updateCartQuantity()
│       │       ├── removeFromCart()
│       │       ├── clearCart()
│       │       ├── updateCartCount()
│       │       └── showToast()
│       ├── dashboard.blade.php
│       ├── auth/
│       └── layouts/
├── routes/
│   ├── web.php ⭐ [MODIFIED]
│   │   ├── GET / - Home (MenuController@index)
│   │   ├── GET /menu/{category} - AJAX menu (MenuController@getMenuByCategory)
│   │   ├── POST /cart/add - Add to cart (MenuController@addToCart)
│   │   ├── GET /cart/view - View cart (MenuController@viewCart)
│   │   ├── POST /cart/{id}/update - Update qty (MenuController@updateCartQuantity)
│   │   ├── DELETE /cart/{id}/remove - Remove item (MenuController@removeFromCart)
│   │   └── DELETE /cart/clear - Clear cart (MenuController@clearCart)
│   ├── auth.php
│   └── console.php
├── tests/
│   ├── Feature/
│   │   ├── ExampleTest.php
│   │   ├── ProfileTest.php
│   │   └── Auth/
│   └── Unit/
│       └── ExampleTest.php
├── vendor/
│   └── ... (Laravel dependencies)
├── bootstrap/
│   ├── app.php
│   └── providers.php
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── database.php
│   ├── filesystems.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── services.php
│   └── session.php
├── storage/
│   ├── app/
│   │   ├── private/
│   │   └── public/
│   ├── framework/
│   │   ├── cache/
│   │   ├── sessions/
│   │   ├── testing/
│   │   └── views/
│   └── logs/
│       └── laravel.log
├── DOKUMENTASI_MVC.md ⭐ [NEW]
│   └── Dokumentasi lengkap struktur MVC, data flow, schema, dll
├── FITUR_BARU.md ⭐ [NEW]
│   └── Panduan penggunaan fitur, troubleshooting, next steps
├── artisan
├── composer.json
├── composer.lock
├── package.json
├── phpunit.xml
├── postcss.config.js
├── tailwind.config.js
├── vite.config.js
└── README.md
```

## 🔍 File Files yang Paling Penting (Untuk Dipelajari)

### Priority 1 - CORE LOGIC
1. **app/Http/Controllers/MenuController.php** - Semua logic controller
2. **app/Models/Menu.php** - Menu model dengan scopes
3. **app/Models/Cart.php** - Cart model dengan relasi
4. **resources/views/welcome.blade.php** - Frontend & JavaScript

### Priority 2 - DATABASE
5. **database/migrations/2024_04_24_000000_create_menus_table.php** - Struktur tabel menus
6. **database/migrations/2024_04_24_000001_create_carts_table.php** - Struktur tabel carts
7. **database/seeders/MenuSeeder.php** - Data dummy menu

### Priority 3 - ROUTES & CONFIG
8. **routes/web.php** - Semua route yang digunakan
9. **config/database.php** - Konfigurasi database

## 📊 Database Relationships

```
menus (1) ──────────── (n) carts
    ↓
    └─ id (primary key)
    └─ Setiap menu bisa memiliki banyak cart items
    └─ Cascade delete: hapus menu → hapus cart items yang referensi menu itu

carts
    ├─ session_id (group cart per session/user)
    └─ unique(menu_id, session_id) - satu item hanya 1x per session
```

## 🌐 API Endpoints Summary

| Method | Route | Controller Method | Purpose |
|--------|-------|------------------|---------|
| GET | `/` | index() | Load home page |
| GET | `/menu/{category}` | getMenuByCategory() | Get menu by category (AJAX) |
| POST | `/cart/add` | addToCart() | Add item to cart (AJAX) |
| GET | `/cart/view` | viewCart() | Get cart items (AJAX) |
| POST | `/cart/{id}/update` | updateCartQuantity() | Update qty (AJAX) |
| DELETE | `/cart/{id}/remove` | removeFromCart() | Remove item (AJAX) |
| DELETE | `/cart/clear` | clearCart() | Clear all cart (AJAX) |

## 📈 Data Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    USER INTERFACE                            │
│                   welcome.blade.php                          │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────────┐  │
│  │ Category Tab │  │  Menu Grid   │  │  Cart Button     │  │
│  │   Coffee     │  │  (6 items)   │  │  (Badge Count)   │  │
│  │   Snack      │──│  (AJAX Load) │──│  (Modal Open)    │  │
│  │   Lainnya    │  │  (Dynamic)   │  │  (Edit Qty)      │  │
│  └──────────────┘  └──────────────┘  └──────────────────┘  │
└─────────────────────────────────────────────────────────────┘
         ↑              ↑              ↑              ↑
         │              │              │              │
         │ AJAX         │ AJAX         │ AJAX        │ AJAX
         │ Calls        │ Calls        │ Calls       │ Calls
         │              │              │             │
┌─────────────────────────────────────────────────────────────┐
│              CONTROLLER LAYER                               │
│           MenuController.php                                │
│  ┌─────────────────┐  ┌──────────────┐  ┌────────────────┐│
│  │ getMenuByCategory│  │ addToCart()  │  │ updateCart...()││
│  │ viewCart()      │  │ removeFrom...│  │ clearCart()   ││
│  │ index()         │  │              │  │               ││
│  └─────────────────┘  └──────────────┘  └────────────────┘│
└─────────────────────────────────────────────────────────────┘
         ↑              ↑              ↑
         │              │              │
         │ Query        │ Insert/      │ Update/
         │ Select       │ Update       │ Delete
         │              │              │
┌─────────────────────────────────────────────────────────────┐
│              DATABASE LAYER                                 │
│              Laravel Eloquent ORM                           │
│  ┌──────────────────┐  ┌──────────────────────────────────┐│
│  │  Menu Model      │  │  Cart Model                      ││
│  │  - byCategory()  │  │  - bySession()                   ││
│  │  - active()      │  │  - getTotalPrice()               ││
│  │  - scopes        │  │  - belongsTo(Menu)               ││
│  └──────────────────┘  └──────────────────────────────────┘│
└─────────────────────────────────────────────────────────────┘
         ↑              ↑
         │              │
         │ SQL Query    │ SQL Query
         │              │
┌─────────────────────────────────────────────────────────────┐
│              DATABASE                                       │
│  ┌──────────────────┐  ┌──────────────────────────────────┐│
│  │ menus table      │  │ carts table                      ││
│  │ (13 rows)        │  │ (dynamic rows)                   ││
│  └──────────────────┘  └──────────────────────────────────┘│
└─────────────────────────────────────────────────────────────┘
```

---

**Last Updated**: 24 April 2026
**Project**: Kopi Subroto - Menu & Cart System
**Status**: ✅ Fully Functional

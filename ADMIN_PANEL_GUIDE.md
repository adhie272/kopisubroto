# 🏢 ADMIN PANEL - PANDUAN LENGKAP

---

## 📍 LOKASI & AKSES

### URL Admin
```
http://localhost:8000/admin/menu
```

### Requirement
- ✅ Must be logged in
- ✅ Database sudah ter-seed
- ✅ Server running (php artisan serve)

### Login First
```
URL: http://localhost:8000/login

Credentials:
- admin@kopi.test / password
- user@kopi.test / password
```

---

## 🎯 FITUR UTAMA

### 1️⃣ Dashboard Menu (Read)
**Page**: `/admin/menu`

**Menampilkan**:
- Tabel dengan 9 kolom: No, Nama, Kategori, Harga, Deskripsi, Status, Aksi
- Pagination (10 items per page)
- Success message jika ada update terbaru

**Kolom Detail**:
```
No         | Auto increment
Nama       | Nama menu item
Kategori   | Coffee/Snack/Lainnya (colored badge)
Harga      | Format Rp10.000
Deskripsi  | First 50 chars + ...
Status     | Toggle button (Aktif/Nonaktif)
Aksi       | Edit & Hapus button
```

**Color Badge Category**:
- Coffee → Amber (bg-amber-100, text-amber-800)
- Snack  → Orange (bg-orange-100, text-orange-800)
- Others → Blue (bg-blue-100, text-blue-800)

**Tools**:
- Tombol "+ Tambah Menu" (top right)
- Pagination links (bottom)

---

### 2️⃣ Tambah Menu (Create)
**Page**: `/admin/menu/create`

**Form Fields**:
```
┌─────────────────────────────────────┐
│ FORM TAMBAH MENU BARU               │
├─────────────────────────────────────┤
│ 1. Nama Menu            [required]  │
│    Input text                       │
│    Max: 100 chars                   │
│    Unique: Must not exist           │
│                                     │
│ 2. Kategori             [required]  │
│    Dropdown (Coffee/Snack/Lainnya)  │
│                                     │
│ 3. Harga (Rp)          [required]   │
│    Input number                     │
│    Min: 1000                        │
│                                     │
│ 4. Nama File Gambar    [required]   │
│    Example: espresso.jpg            │
│    Max: 100 chars                   │
│                                     │
│ 5. Deskripsi           [required]   │
│    Textarea                         │
│    Max: 255 chars                   │
│                                     │
│ 6. Status              [optional]   │
│    Checkbox (default: checked)      │
│                                     │
│ [Simpan] [Batal]                    │
└─────────────────────────────────────┘
```

**Validasi**:
```
✗ Nama kosong → "Nama harus diisi"
✗ Nama sudah ada → "Nama sudah digunakan"
✗ Harga < 1000 → "Harga minimal 1000"
✗ Kategori salah → "Kategori invalid"
✗ Deskripsi > 255 → "Deskripsi max 255"
```

**After Submit**:
- Success: Redirect ke `/admin/menu` + success message
- Error: Stay di form + show errors

---

### 3️⃣ Edit Menu (Update)
**Page**: `/admin/menu/{id}/edit`

**Sama seperti Create tapi**:
- Form sudah pre-filled dengan data lama
- Nama bisa sama dengan yang sekarang (unique validator terkecuali)
- Title: "Edit Menu" (bukan Create)

**Fields sama**:
- Nama Menu
- Kategori
- Harga
- Nama File Gambar
- Deskripsi
- Status

**After Submit**:
- Success: Redirect ke `/admin/menu` + success message
- Error: Stay di form + show errors

---

### 4️⃣ Hapus Menu (Delete)
**Button**: "Hapus" di kolom Aksi

**Proses**:
1. User klik "Hapus"
2. Confirmation dialog muncul
3. Jika OK → Delete ke database
4. Redirect ke `/admin/menu` + success message
5. Menu item hilang dari tabel

**Confirmation Dialog**:
```
Are you sure you want to delete this menu?
This action cannot be undone!

[Cancel] [Delete]
```

---

### 5️⃣ Toggle Status (AJAX)
**Button**: "Aktif" atau "Nonaktif" di kolom Status

**Proses**:
1. User klik button
2. AJAX request ke backend
3. Toggle is_active field
4. Button text berubah without page reload
5. Color berubah (green/gray)

**Before**:
```
Button text: "Aktif"
Color: Green
is_active: true
```

**After Click**:
```
Button text: "Nonaktif"
Color: Gray
is_active: false
```

---

## 🛣️ ROUTES ARCHITECTURE

### Route Structure
```
GET    /admin/menu
       └─→ AdminMenuController@index
           └─→ resources/views/admin/menu/index.blade.php
           └─→ Paginate 10 per page

GET    /admin/menu/create
       └─→ AdminMenuController@create
           └─→ resources/views/admin/menu/create.blade.php

POST   /admin/menu
       └─→ AdminMenuController@store
           └─→ Validate + Save
           └─→ Redirect /admin/menu

GET    /admin/menu/{menu}/edit
       └─→ AdminMenuController@edit
           └─→ resources/views/admin/menu/edit.blade.php
           └─→ Pre-fill form

PUT    /admin/menu/{menu}
       └─→ AdminMenuController@update
           └─→ Validate + Update
           └─→ Redirect /admin/menu

DELETE /admin/menu/{menu}
       └─→ AdminMenuController@destroy
           └─→ Delete from DB
           └─→ Redirect /admin/menu

POST   /admin/menu/{menu}/toggle-active
       └─→ AdminMenuController@toggleActive
           └─→ AJAX endpoint
           └─→ Return JSON
```

---

## 🧩 FILE STRUCTURE

### Views
```
resources/views/admin/
├── menu/
│   ├── index.blade.php      (List all menus)
│   ├── create.blade.php     (Form tambah)
│   └── edit.blade.php       (Form edit)
```

### Controllers
```
app/Http/Controllers/
├── AdminMenuController.php
    ├── index()              (GET /admin/menu)
    ├── create()             (GET /admin/menu/create)
    ├── store()              (POST /admin/menu)
    ├── edit()               (GET /admin/menu/{id}/edit)
    ├── update()             (PUT /admin/menu/{id})
    ├── destroy()            (DELETE /admin/menu/{id})
    └── toggleActive()       (POST /admin/menu/{id}/toggle-active)
```

### Routes
```
routes/web.php
├── Route::middleware('auth')->group(function() {
│   ├── Route::get('/admin/menu', [AdminMenuController::class, 'index'])
│   ├── Route::get('/admin/menu/create', [AdminMenuController::class, 'create'])
│   ├── Route::post('/admin/menu', [AdminMenuController::class, 'store'])
│   ├── Route::get('/admin/menu/{menu}/edit', [AdminMenuController::class, 'edit'])
│   ├── Route::put('/admin/menu/{menu}', [AdminMenuController::class, 'update'])
│   ├── Route::delete('/admin/menu/{menu}', [AdminMenuController::class, 'destroy'])
│   └── Route::post('/admin/menu/{menu}/toggle-active', [AdminMenuController::class, 'toggleActive'])
│ });
```

---

## 🔐 MIDDLEWARE & SECURITY

### Authentication Middleware
```
'auth' → All admin routes protected
```

### CSRF Protection
```
@csrf → In all forms (POST, PUT, DELETE)
```

### Authorization
```
If not logged in → Redirect to /login
If not authenticated → Deny access
```

### Validation
```
server-side validation (no client-side only)
```

---

## 💾 DATABASE INTEGRATION

### Menu Model
```php
App\Models\Menu {
    id: integer
    name: string (unique, required)
    price: integer (min: 1000)
    image: string (filename)
    description: string (max: 255)
    category: enum (coffee, snack, others)
    is_active: boolean (default: true)
    created_at: timestamp
    updated_at: timestamp
}
```

### Query Methods
```
Menu::all()                     (Get all)
Menu::paginate(10)              (Paginate)
Menu::find($id)                 (Get by ID)
Menu::create($data)             (Create)
$menu->update($data)            (Update)
$menu->delete()                 (Delete)
$menu->toggleActive()           (NOT exist - manually toggle is_active)
```

---

## 📊 EXAMPLE DATA

### Seeded Data (13 Items)
```
Coffee (6):
- Espresso
- Americano
- Cappuccino
- Caramel Macchiato
- Cafe Latte
- Mocha Latte

Snack (4):
- Croissant
- Donut Chocolate
- Cookies
- Brownie

Others (3):
- Juice
- Smoothie
- Iced Tea
```

### Sample New Item
```
Nama: Vanilla Latte
Kategori: Coffee
Harga: 25000
Image: vanilla_latte.jpg
Deskripsi: Smooth vanilla flavor with silky milk foam
Status: Aktif
```

---

## 🎨 UI/UX DETAILS

### Color Scheme
```
Primary: Indigo-600
Secondary: Blue-500
Success: Green-500
Warning: Amber-500
Danger: Red-500
Category (Coffee): Amber
Category (Snack): Orange
Category (Others): Blue
```

### Responsive Design
```
Desktop (lg+): Full table view
Tablet (md): Adjusted padding
Mobile (sm): Stack view (if implemented)
```

### Form Elements
```
Input text: bg-white border-gray-300 rounded
Select: bg-white border-gray-300 rounded
Textarea: bg-white border-gray-300 rounded
Button Simpan: bg-indigo-600 hover:bg-indigo-700
Button Batal: bg-gray-400 hover:bg-gray-500
Button Edit: bg-blue-500 hover:bg-blue-600
Button Hapus: bg-red-500 hover:bg-red-600
Button Toggle: Aktif=green, Nonaktif=gray
```

---

## 🧪 TESTING SCENARIOS

### Scenario 1: Add New Menu
```
1. Login sebagai admin
2. Go to /admin/menu
3. Click "+ Tambah Menu"
4. Fill form:
   - Nama: "Test Coffee"
   - Kategori: "Coffee"
   - Harga: 15000
   - Image: test.jpg
   - Deskripsi: "Test item"
5. Click "Simpan"
✅ Expected: Redirect to /admin/menu, show success message
✅ New item visible in table
```

### Scenario 2: Edit Menu
```
1. Go to /admin/menu
2. Find menu item
3. Click "Edit"
4. Modify fields
5. Click "Simpan"
✅ Expected: Updated values in table
```

### Scenario 3: Delete Menu
```
1. Go to /admin/menu
2. Find menu item
3. Click "Hapus"
4. Confirm in dialog
✅ Expected: Item removed from table
```

### Scenario 4: Toggle Status
```
1. Go to /admin/menu
2. Click "Aktif" button
✅ Expected: Button text changes to "Nonaktif"
✅ No page reload
✅ Color changes (green → gray)
```

### Scenario 5: Validation
```
1. Go to create
2. Submit empty form
✅ Expected: Show validation errors
3. Enter duplicate name
✅ Expected: "Nama sudah digunakan" error
4. Enter harga < 1000
✅ Expected: "Harga minimal 1000" error
```

---

## 🐛 COMMON ISSUES & SOLUTIONS

| Issue | Cause | Solution |
|-------|-------|----------|
| 404 not found /admin/menu | Routes not loaded | Check routes/web.php - admin routes exist |
| Redirected to login | Not authenticated | Login dengan admin@kopi.test / password |
| Validation errors | Invalid input | Check field requirements & constraints |
| CSRF token mismatch | Missing @csrf | Check @csrf present in forms |
| Delete doesn't work | JavaScript error | Check browser console (F12) |
| Image not showing | File not in public/images | Upload image to public/images/ |

---

## 📞 SUPPORT FLOW

```
Issue → Check browser console (F12)
       → Check Laravel logs (storage/logs/)
       → Check database connection
       → Run php artisan migrate:refresh --seed
       → Restart server (php artisan serve)
```

---

## ✅ ADMIN PANEL READY!

All features implemented and tested.
Database seeded with sample data.
Ready to use in production! 🚀

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kopi Subroto</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bungee&family=Figtree:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-brand-cream font-sans text-brand-ink pt-20 sm:pt-16">
    @php
        $isAdminStorePreview = request()->routeIs('admin.store.preview');
    @endphp

    <nav class="bg-brand-deep text-brand-parchment shadow-lg fixed top-0 w-full h-16 z-40">
        <div class="container mx-auto px-4 sm:px-6 h-full flex justify-between items-center">
            <div class="brand-lockup">
                <span class="brand-emblem" aria-hidden="true">S</span>
                <div class="flex flex-col justify-center min-w-0">
                    <h1 class="brand-wordmark text-[0.95rem] sm:text-lg text-brand-parchment">Kopi Subroto</h1>
                    <p class="hidden sm:block brand-subtitle text-[10px] text-brand-parchment/75 leading-tight">Kopi Premium & Snacks Enak</p>
                </div>
            </div>
            <div class="flex items-center gap-2 sm:gap-4">
                @unless($isAdminStorePreview)
                <a href="/pesanan-saya" class="bg-brand-ink px-3 py-2 rounded-lg hover:bg-brand-teal transition relative inline-flex items-center gap-2 text-brand-parchment hover:text-brand-deep" aria-label="Pesanan Saya">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6h8M8 10h8M8 14h5M5 4h14v16l-2-1.25L15 20l-2-1.25L11 20l-2-1.25L7 20l-2-1.25V4z" />
                    </svg>
                    <span class="hidden sm:inline text-xs font-bold">Pesanan</span>
                    @if(($myOrdersCount ?? 0) > 0)
                    <span class="absolute -top-1 -right-1 bg-brand-brown text-white text-xs font-bold rounded-full w-4 h-4 flex items-center justify-center text-[10px]">{{ $myOrdersCount }}</span>
                    @endif
                </a>
                <button id="cartBtn" class="bg-brand-ink p-2 rounded-lg hover:bg-brand-teal transition relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span id="cartCount" class="absolute -top-1 -right-1 bg-brand-maroon text-white text-xs font-bold rounded-full w-4 h-4 flex items-center justify-center text-[10px]">{{ $cartCount ?? 0 }}</span>
                </button>
                @endunless
                @auth
                    @if(Auth::user()->role === 'admin')
                    <a href="/admin/menu" class="bg-brand-brown px-5 py-2 rounded-xl text-xs font-bold text-white hover:bg-brand-brownDark transition">Admin</a>
                    @endif
                @else
                <a href="/login" class="bg-brand-brown px-5 py-2 rounded-xl text-xs font-bold text-white hover:bg-brand-brownDark transition">Admin</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Category Tabs -->
    <div class="sticky top-20 sm:top-16 z-30 pointer-events-none">
        <div class="container mx-auto px-4 sm:px-6 py-3 flex gap-2 overflow-x-auto pointer-events-auto">
            <button class="category-btn px-5 py-2.5 rounded-full bg-brand-teal text-brand-deep font-bold shadow-sm whitespace-nowrap transition text-sm sm:text-base" data-category="coffee">
                Coffee
            </button>
            <button class="category-btn px-5 py-2.5 rounded-full bg-white text-brand-ink border border-brand-line hover:border-brand-teal hover:text-brand-deep hover:shadow-sm transition whitespace-nowrap text-sm sm:text-base" data-category="snack">
                Snack
            </button>
            <button class="category-btn px-5 py-2.5 rounded-full bg-white text-brand-ink border border-brand-line hover:border-brand-teal hover:text-brand-deep hover:shadow-sm transition whitespace-nowrap text-sm sm:text-base" data-category="others">
                Lainnya
            </button>
        </div>
    </div>

    <!-- Menu Container -->
    <div class="container mx-auto px-3 sm:px-6 py-5 sm:py-10">
        <div id="menuContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
            <!-- Menu items akan di-load via JavaScript -->
            @foreach($coffeeMenus as $menu)
            <div class="bg-white rounded-3xl sm:rounded-[2.5rem] shadow-xl overflow-hidden border border-brand-line group transition-all menu-item flex flex-col h-full" data-menu-id="{{ $menu->id }}">
                <div class="aspect-square overflow-hidden bg-gray-200">
                    <img src="{{ $menu->image_url }}" class="w-full h-full object-cover transition group-hover:scale-110" alt="{{ $menu->name }}">
                </div>
                <div class="p-4 sm:p-6 flex flex-col flex-grow justify-between">
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold text-brand-ink uppercase break-words">{{ $menu->name }}</h3>
                        <p class="text-sm text-gray-500 mt-2">{{ $menu->description }}</p>
                    </div>
                    <div class="mt-5 sm:mt-6 flex flex-wrap justify-between items-center gap-3">
                        <span class="text-lg sm:text-xl font-extrabold text-brand-deep">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                        @unless($isAdminStorePreview)
                        <button class="add-to-cart-btn bg-brand-brown text-white px-5 sm:px-6 py-2.5 rounded-2xl font-bold hover:bg-brand-brownDark transition" data-menu-id="{{ $menu->id }}" data-menu-name="{{ $menu->name }}">
                            + Tambah
                        </button>
                        @endunless
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    @unless($isAdminStorePreview)
    <!-- Cart Modal -->
    <div id="cartModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-3xl max-h-[92vh] sm:max-h-[85vh] overflow-hidden shadow-2xl">
            <div class="bg-brand-deep text-brand-parchment p-5 sm:p-6 sticky top-0 z-10">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-2xl font-bold">Keranjang Belanja</h2>
                    <button id="closeCartBtn" class="text-3xl font-bold leading-none hover:text-gray-300">&times;</button>
                </div>
            </div>

            <div id="cartItemsContainer" class="p-5 sm:p-6 min-h-[160px] overflow-y-auto">
                <!-- Cart items akan di-load via JavaScript -->
                <p class="text-center text-gray-500">Keranjang kosong</p>
            </div>

            <div class="border-t border-brand-line p-5 sm:p-6 bg-brand-cream sticky bottom-0 space-y-4">
                <!-- Customer Name Input -->
                <div>
                    <label class="block text-sm font-bold text-brand-ink mb-2">Nama yang Memesan</label>
                    <input type="text" id="customerName" placeholder="Masukkan nama Anda" class="w-full px-4 py-2.5 border border-brand-line rounded-2xl text-brand-deep focus:outline-none focus:ring-2 focus:ring-brand-teal focus:border-transparent">
                </div>

                <!-- Table Number Input -->
                <div>
                    <label class="block text-sm font-bold text-brand-ink mb-2">Nomor Meja</label>
                    <input type="number" id="tableNumber" placeholder="Masukkan nomor meja" class="w-full px-4 py-2.5 border border-brand-line rounded-2xl text-brand-deep focus:outline-none focus:ring-2 focus:ring-brand-teal focus:border-transparent" min="1">
                </div>

                <!-- Total -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pt-3 border-t">
                    <span class="text-lg font-bold">Total:</span>
                    <span id="cartTotal" class="text-2xl font-bold text-brand-deep">Rp 0</span>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button id="clearCartBtn" class="flex-1 bg-brand-maroon text-white px-4 py-3 rounded-2xl font-bold hover:opacity-90 transition">
                        Kosongkan Keranjang
                    </button>
                    <button id="orderBtn" class="flex-1 bg-brand-brown text-white px-4 py-3 rounded-2xl font-bold hover:bg-brand-brownDark transition">
                        Pesan
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endunless

    <!-- Toast Notification -->
    <div id="toast" class="hidden fixed bottom-4 left-4 right-4 sm:left-auto sm:right-4 sm:max-w-sm bg-brand-teal text-brand-deep px-5 py-3 rounded-2xl shadow-lg z-50"></div>

    <script>
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const IS_ADMIN_STORE_PREVIEW = @json($isAdminStorePreview);
        let currentCategory = 'coffee';
        const cartItemNotes = {};

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadMenuByCategory('coffee');
            setupEventListeners();
        });

        // Setup Event Listeners
        function setupEventListeners() {
            const activeCategoryClasses = ['bg-brand-teal', 'text-brand-deep', 'font-bold', 'shadow-sm'];
            const inactiveCategoryClasses = ['bg-white', 'text-brand-ink', 'border', 'border-brand-line', 'hover:border-brand-teal', 'hover:text-brand-deep', 'hover:shadow-sm'];

            // Category buttons
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.category-btn').forEach(b => {
                        b.classList.remove(...activeCategoryClasses);
                        b.classList.add(...inactiveCategoryClasses);
                    });

                    this.classList.remove(...inactiveCategoryClasses);
                    this.classList.add(...activeCategoryClasses);

                    currentCategory = this.dataset.category;
                    loadMenuByCategory(currentCategory);
                });
            });

            if (IS_ADMIN_STORE_PREVIEW) return;

            // Cart button
            document.getElementById('cartBtn').addEventListener('click', openCart);
            document.getElementById('closeCartBtn').addEventListener('click', closeCart);
            document.getElementById('orderBtn').addEventListener('click', placeOrder);

            // Clear cart button
            document.getElementById('clearCartBtn').addEventListener('click', clearCart);

            // Close modal when clicking outside
            document.getElementById('cartModal').addEventListener('click', function(e) {
                if (e.target === this) closeCart();
            });
        }

        // Load Menu by Category
        function loadMenuByCategory(category) {
            fetch(`/menu/${category}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderMenu(data.data);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Render Menu Items
        function renderMenu(menus) {
            const container = document.getElementById('menuContainer');
            if (!container) return;
            container.innerHTML = '';

            menus.forEach(menu => {
                const actionButton = IS_ADMIN_STORE_PREVIEW ? '' : `
                                <button class="add-to-cart-btn bg-brand-brown text-white px-5 sm:px-6 py-2.5 rounded-2xl font-bold hover:bg-brand-brownDark transition" data-menu-id="${menu.id}" data-menu-name="${menu.name}">
                                    + Tambah
                                </button>
                `;
                const menuHTML = `
                    <div class="bg-white rounded-3xl sm:rounded-[2.5rem] shadow-xl overflow-hidden border border-brand-line group transition-all flex flex-col h-full">
                        <div class="aspect-square overflow-hidden bg-gray-200">
                            <img src="${menu.image_url}" class="w-full h-full object-cover transition group-hover:scale-110" alt="${menu.name}">
                        </div>
                        <div class="p-4 sm:p-6 flex flex-col flex-grow justify-between">
                            <div>
                                <h3 class="text-lg sm:text-xl font-bold text-brand-ink uppercase break-words">${menu.name}</h3>
                                <p class="text-sm text-gray-500 mt-2">${menu.description}</p>
                            </div>
                            <div class="mt-5 sm:mt-6 flex flex-wrap justify-between items-center gap-3">
                                <span class="text-lg sm:text-xl font-extrabold text-brand-deep">Rp ${new Intl.NumberFormat('id-ID').format(menu.price)}</span>
                                ${actionButton}
                            </div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', menuHTML);
            });

            // Attach add to cart listeners
            if (!IS_ADMIN_STORE_PREVIEW) attachAddToCartListeners();
        }

        // Attach Add to Cart Listeners
        function attachAddToCartListeners() {
            document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const menuId = this.dataset.menuId;
                    const menuName = this.dataset.menuName;
                    addToCart(menuId, menuName);
                });
            });
        }

        // Add to Cart
        function addToCart(menuId, menuName) {
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                },
                body: JSON.stringify({
                    menu_id: menuId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartCount(data.cartCount);
                    showToast(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Open Cart Modal
        function openCart() {
            fetch('/cart/view', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderCart(data.items, data.total);
                    document.getElementById('cartModal').classList.remove('hidden');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Close Cart Modal
        function closeCart() {
            document.getElementById('cartModal').classList.add('hidden');
        }

        // Render Cart Items
        function renderCart(items, total) {
            const container = document.getElementById('cartItemsContainer');

            if (items.length === 0) {
                container.innerHTML = '<p class="text-center text-gray-500 py-8">Keranjang kosong</p>';
                document.getElementById('cartTotal').textContent = 'Rp 0';
                return;
            }

            container.innerHTML = '';
            items.forEach(item => {
                const noteValue = cartItemNotes[item.id] || '';
                const noteOpenClass = noteValue ? '' : 'hidden';
                const noteButtonText = noteValue ? 'Sembunyikan keterangan' : 'Tambah keterangan';
                const itemHTML = `
                    <div class="border-b pb-4 mb-4">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-brand-ink truncate">${escapeHtml(item.menu.name)}</h3>
                                <p class="text-sm text-gray-500">Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</p>
                                <button type="button" class="toggle-item-note mt-2 text-xs font-bold text-brand-brown hover:text-brand-brownDark" data-cart-id="${item.id}">
                                    ${noteButtonText}
                                </button>
                            </div>
                            <div class="flex items-center gap-3">
                                <button class="decrease-qty px-2 py-1 bg-gray-300 rounded hover:bg-gray-400" data-cart-id="${item.id}">-</button>
                                <span class="qty w-8 text-center font-bold">${item.quantity}</span>
                                <button class="increase-qty px-2 py-1 bg-gray-300 rounded hover:bg-gray-400" data-cart-id="${item.id}">+</button>
                            </div>
                            <span class="font-bold text-brand-deep w-full sm:w-24 text-right">Rp ${new Intl.NumberFormat('id-ID').format(item.price * item.quantity)}</span>
                            <button class="remove-item text-brand-maroon hover:opacity-80 font-bold" data-cart-id="${item.id}">Hapus</button>
                        </div>
                        <div class="item-note-wrapper ${noteOpenClass} mt-3" data-cart-id="${item.id}">
                            <textarea class="item-note-input w-full px-4 py-2.5 border border-brand-line rounded-2xl text-sm text-brand-deep focus:outline-none focus:ring-2 focus:ring-brand-teal focus:border-transparent resize-none" data-cart-id="${item.id}" rows="2" maxlength="500" placeholder="Contoh: tanpa gula, pedas sedikit, pisah saus">${escapeHtml(noteValue)}</textarea>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', itemHTML);
            });

            document.getElementById('cartTotal').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(total)}`;

            // Attach listeners for quantity and remove
            attachCartListeners();
        }

        // Attach Cart Listeners
        function attachCartListeners() {
            document.querySelectorAll('.toggle-item-note').forEach(btn => {
                btn.addEventListener('click', function() {
                    const cartId = this.dataset.cartId;
                    const wrapper = document.querySelector(`.item-note-wrapper[data-cart-id="${cartId}"]`);

                    if (!wrapper) return;

                    wrapper.classList.toggle('hidden');
                    this.textContent = wrapper.classList.contains('hidden') ? 'Tambah keterangan' : 'Sembunyikan keterangan';

                    if (!wrapper.classList.contains('hidden')) {
                        wrapper.querySelector('.item-note-input').focus();
                    }
                });
            });

            document.querySelectorAll('.item-note-input').forEach(input => {
                input.addEventListener('input', function() {
                    cartItemNotes[this.dataset.cartId] = this.value;
                });
            });

            document.querySelectorAll('.increase-qty').forEach(btn => {
                btn.addEventListener('click', function() {
                    const cartId = this.dataset.cartId;
                    const qtySpan = this.parentElement.querySelector('.qty');
                    const newQty = parseInt(qtySpan.textContent) + 1;
                    updateCartQuantity(cartId, newQty);
                });
            });

            document.querySelectorAll('.decrease-qty').forEach(btn => {
                btn.addEventListener('click', function() {
                    const cartId = this.dataset.cartId;
                    const qtySpan = this.parentElement.querySelector('.qty');
                    const newQty = parseInt(qtySpan.textContent) - 1;
                    if (newQty > 0) {
                        updateCartQuantity(cartId, newQty);
                    }
                });
            });

            document.querySelectorAll('.remove-item').forEach(btn => {
                btn.addEventListener('click', function() {
                    const cartId = this.dataset.cartId;
                    delete cartItemNotes[cartId];
                    removeFromCart(cartId);
                });
            });
        }

        // Update Cart Quantity
        function updateCartQuantity(cartId, quantity) {
            fetch(`/cart/${cartId}/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                },
                body: JSON.stringify({ quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartCount(data.cartCount);
                    openCart(); // Refresh cart
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Place Order
        function placeOrder() {
            const customerName = document.getElementById('customerName').value.trim();
            const tableNumber = document.getElementById('tableNumber').value.trim();
            const orderBtn = document.getElementById('orderBtn');

            // Validation
            if (!customerName) {
                showToast('Tolong isi nama yang memesan');
                return;
            }
            if (!tableNumber) {
                showToast('Tolong isi nomor meja');
                return;
            }

            orderBtn.disabled = true;
            orderBtn.textContent = 'Memproses...';

            fetch('/cart/order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                },
                body: JSON.stringify({
                    customer_name: customerName,
                    table_number: tableNumber,
                    item_notes: buildItemNotesPayload()
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartCount(0);
                    document.getElementById('customerName').value = '';
                    document.getElementById('tableNumber').value = '';
                    Object.keys(cartItemNotes).forEach(cartId => delete cartItemNotes[cartId]);
                    window.location.href = `/pesanan-saya?order=${data.order_id}`;
                } else {
                    showToast(data.message || 'Pesanan gagal dibuat');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Pesanan gagal dibuat. Coba lagi.');
            })
            .finally(() => {
                orderBtn.disabled = false;
                orderBtn.textContent = 'Pesan';
            });
        }

        // Remove from Cart
        function removeFromCart(cartId) {
            fetch(`/cart/${cartId}/remove`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartCount(data.cartCount);
                    showToast(data.message);
                    openCart(); // Refresh cart
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Clear Cart
        function clearCart() {
            if (confirm('Apakah Anda yakin ingin mengosongkan keranjang?')) {
                fetch('/cart/clear', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Object.keys(cartItemNotes).forEach(cartId => delete cartItemNotes[cartId]);
                        updateCartCount(0);
                        showToast(data.message);
                        openCart(); // Refresh cart
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        // Update Cart Count Badge
        function updateCartCount(count) {
            document.getElementById('cartCount').textContent = count;
        }

        function buildItemNotesPayload() {
            return Object.fromEntries(
                Object.entries(cartItemNotes)
                    .map(([cartId, note]) => [cartId, note.trim()])
                    .filter(([, note]) => note.length > 0)
            );
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        // Show Toast Notification
        function showToast(message) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 2000);
        }
    </script>

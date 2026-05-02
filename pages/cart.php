<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skrrt | Cart</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/vendor/fontawesome/css/all.min.css">
</head>

<body class="bg-white">
    <!-- NAVIGATION -->
    <nav id="navbar"
        class="fixed w-full z-50 top-0 start-0 transition-all duration-500 ease-in-out py-3 bg-white text-black">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto px-10">
            <a href="../customer.php" class="flex items-center">
                <span id="nav-logo" class="transition-all duration-500">
                    <img id="logo-img" src="../assets/images/Skrrt_logo-Alt.png" alt="Logo"
                        class="h-10 w-auto object-contain">
                </span>
            </a>

            <div class="items-center justify-between hidden w-full md:flex md:w-auto">
                <ul id="nav-menu"
                    class="flex flex-col p-4 md:p-0 mt-4 font-semibold md:space-x-10 md:flex-row md:mt-0 text-[14px] tracking-wide uppercase transition-colors duration-500">
                    <li><a href="../pages/shop.php" class="hover:opacity-60">Shop</a></li>
                    <li><a href="../pages/collections.php" class="hover:opacity-60">Collections</a></li>
                    <li><a href="../pages/about.php" class="hover:opacity-60">About</a></li>
                    <li><a href="../pages/contact.php" class="hover:opacity-60">Contact Us</a></li>
                </ul>
            </div>

            <div id="nav-icons" class="flex items-center space-x-6 text-sm transition-colors duration-500">
                <a href="../pages/cart.php" class="relative hover:opacity-60 transition-opacity">
                    <div class="indicator">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span id="cart-indicator" class="bg-brand-green text-black font-semibold px-1 min-w-[10px]">
                            0
                        </span>
                    </div>
                </a>

                <a href="profile.php" class="relative hover:opacity-60 transition-opacity">
                    <i class="fa-regular fa-user"></i>
                </a>
            </div>
    </nav>

    <main class="pt-32 pb-20 px-4 md:px-20 max-w-screen-xl mx-auto">

        <div class="text-center mb-10">
            <h1 class="text-2xl font-bold uppercase tracking-tighter">Your Shopping Cart</h1>
            <p class="text-xs text-gray-500 mt-1">Total Items: <span id="itemCount">0</span></p>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="text-center py-20">
            <p class="text-xs text-gray-400 uppercase tracking-widest animate-pulse">Loading your cart...</p>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden text-center py-24">
            <p class="text-2xl font-semibold uppercase tracking-widest text-black/20 mb-6">Your cart is empty</p>
            <a href="../pages/shop.php"
                class="inline-block bg-black text-white px-12 py-4 text-xs font-bold uppercase tracking-widest hover:opacity-80 transition-colors">
                Browse Shop
            </a>
        </div>

        <!-- Cart Table -->
        <div id="cartContent" class="hidden w-full">
            <div
                class="hidden md:grid grid-cols-6 gap-4 border-b border-gray-100 pb-2 text-[10px] uppercase font-bold text-gray-400 tracking-widest">
                <div class="col-span-3">Product</div>
                <div class="text-center">Quantity</div>
                <div class="text-right">Total</div>
                <div></div>
            </div>

            <!-- Cart Items injected here -->
            <div id="cartItems"></div>

            <!-- Subtotal -->
            <div class="mt-10">
                <div class="flex items-baseline space-x-2">
                    <span class="text-lg font-medium text-gray-500">Subtotal:</span>
                    <span id="subtotal" class="text-2xl font-bold">₱0.00</span>
                </div>
                <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider">Excluding taxes and shipping</p>

                <div class="flex flex-col md:flex-row gap-4 mt-8">
                    <a href="../pages/checkout.php"
                        class="bg-black text-white px-12 py-4 text-xs font-bold uppercase tracking-widest hover:bg-black/70 transition-colors text-center">
                        Checkout
                    </a>
                    <a href="../pages/shop.php"
                        class="border border-black text-black px-12 py-4 text-xs font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-all text-center">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="bg-[#A6F000] pt-20 pb-0 px-4 md:px-20 text-black relative overflow-hidden mt-10">
        <div class="max-w-screen-xl mx-auto relative z-30">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-10">
                <div>
                    <h4 class="text-[10px] uppercase tracking-[0.2em] font-bold mb-6 opacity-50">Information</h4>
                    <ul class="space-y-3 text-[13px] font-medium">
                        <li><a href="#" class="hover:underline">Privacy</a></li>
                        <li><a href="#" class="hover:underline">FAQ</a></li>
                        <li><a href="#" class="hover:underline">Shipping and payment</a></li>
                        <li><a href="#" class="hover:underline">Partners</a></li>
                        <li><a href="#" class="hover:underline">Blog</a></li>
                        <li><a href="#" class="hover:underline">Contacts</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[10px] uppercase tracking-[0.2em] font-bold mb-6 opacity-50">Menu</h4>
                    <ul class="space-y-3 text-[13px] font-medium">
                        <li><a href="shop.php" class="hover:underline">Shop</a></li>
                        <li><a href="collections.php" class="hover:underline">Collections</a></li>
                        <li><a href="new_releases.php" class="hover:underline">New Releases</a></li>
                    </ul>
                </div>
                <div class="hidden md:block"></div>
                <div class="flex flex-col items-start md:items-end">
                    <div
                        class="bg-black text-[#A6F000] px-8 py-3 rounded-full text-[12px] font-bold uppercase mb-4 cursor-pointer hover:scale-105 transition-transform">
                        Request a call
                    </div>
                    <p class="text-[14px] font-bold">+1 (888) 999-99-99</p>
                    <p class="text-[14px] font-medium opacity-70">info@skrrtworldwide.com</p>
                </div>
            </div>
            <div class="flex flex-col md:flex-row justify-between items-center border-t border-black/10 pt-10 mb-10">
                <div class="flex space-x-4">
                    <div class="w-10 h-10 bg-black rounded-full flex items-center justify-center text-white">
                        <i class="fa-brands fa-telegram"></i>
                    </div>
                    <div class="w-10 h-10 bg-black rounded-full flex items-center justify-center text-white">
                        <i class="fa-brands fa-instagram"></i>
                    </div>
                </div>
                <div class="text-[11px] font-bold uppercase tracking-widest text-center mt-4 md:mt-0">
                    2ITB Information Management. PNC, Philippines 81063
                </div>
                <div class="text-[11px] opacity-60 mt-4 md:mt-0">
                    &copy; 2026 Skrrt Worldwide. All Rights Reserved.
                </div>
            </div>
        </div>
        <div
            class="relative left-1/2 -translate-x-1/2 w-[115%] md:w-[130%] mt-5 pointer-events-none z-10 origin-bottom">
            <img src="../assets/images/Skrrt_logo-Half.svg" alt="Logo"
                class="w-full h-auto object-contain object-bottom select-none">
        </div>
    </footer>

    <script>
        // ─── Helpers ──────────────────────────────────────────────────────────────

        const API = '../api.php';

        function formatPrice(amount) {
            return '₱' + parseFloat(amount).toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // ─── localStorage cart (guest) ────────────────────────────────────────────

        function getLocalCart() {
            return JSON.parse(localStorage.getItem('skrrt_cart') || '[]');
        }

        function saveLocalCart(cart) {
            localStorage.setItem('skrrt_cart', JSON.stringify(cart));
        }

        // ─── Check if user is logged in via API ───────────────────────────────────

        async function isLoggedIn() {
            try {
                const res = await fetch(API, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'getProfile' })
                });
                const data = await res.json();
                return data.status === true;
            } catch {
                return false;
            }
        }

        // ─── Build a single cart row ──────────────────────────────────────────────

        function buildRow(item, isLoggedIn) {
            const image = item.image_url
                ? '../' + item.image_url
                : '../assets/images/placeholder_tee.png';
            const name = item.product_name;
            const price = parseFloat(item.price);
            const qty = parseInt(item.quantity);
            const total = price * qty;
            const id = isLoggedIn ? item.cart_item_id : item.product_id;

            return `
        <div class="flex flex-col md:grid md:grid-cols-6 gap-4 items-center py-8 border-b border-gray-100"
             id="row-${id}" data-id="${id}" data-price="${price}" data-logged="${isLoggedIn}">

            <!-- Product Info -->
            <div class="col-span-3 flex items-center w-full">
                <div class="w-20 h-20 bg-gray-50 flex-shrink-0">
                    <img src="${image}"
                         alt="${name}"
                         onerror="this.src='../assets/images/placeholder_tee.png'"
                         class="w-full h-full object-contain">
                </div>
                <div class="ml-6">
                    <h3 class="font-bold text-sm uppercase">${name}</h3>
                    <p class="text-xs font-semibold mt-1">${formatPrice(price)}</p>
                </div>
            </div>

            <!-- Quantity -->
            <div class="flex justify-center items-center">
                <div class="flex items-center border border-gray-200 rounded">
                    <button onclick="changeQty('${id}', -1, ${isLoggedIn})"
                        class="px-3 py-1 hover:bg-gray-100 text-lg">−</button>
                    <span id="qty-${id}" class="px-4 py-1 text-sm font-bold">${qty}</span>
                    <button onclick="changeQty('${id}', 1, ${isLoggedIn})"
                        class="px-3 py-1 hover:bg-gray-100 text-lg">+</button>
                </div>
            </div>

            <!-- Row Total -->
            <div id="total-${id}" class="text-right font-bold text-sm">${formatPrice(total)}</div>

            <!-- Remove -->
            <div class="text-right">
                <button onclick="removeItem('${id}', ${isLoggedIn})"
                    class="text-gray-300 hover:text-red-400 transition-colors text-xl font-semibold">×</button>
            </div>
        </div>`;
        }

        // ─── Render the full cart ─────────────────────────────────────────────────

        function renderCart(items, loggedIn) {
            const loading = document.getElementById('loadingState');
            const empty = document.getElementById('emptyState');
            const content = document.getElementById('cartContent');
            const container = document.getElementById('cartItems');

            loading.classList.add('hidden');

            if (!items || items.length === 0) {
                empty.classList.remove('hidden');
                content.classList.add('hidden');
                return;
            }

            empty.classList.add('hidden');
            content.classList.remove('hidden');

            container.innerHTML = items.map(item => buildRow(item, loggedIn)).join('');
            updateSubtotal();
            updateItemCount();
        }

        // ─── Subtotal + count ─────────────────────────────────────────────────────

        function updateSubtotal() {
            let total = 0;
            document.querySelectorAll('[data-price]').forEach(row => {
                const price = parseFloat(row.dataset.price);
                const id = row.dataset.id;
                const qty = parseInt(document.getElementById('qty-' + id)?.textContent || 0);
                total += price * qty;
            });
            document.getElementById('subtotal').textContent = formatPrice(total);
        }

        function updateItemCount() {
            let count = 0;
            document.querySelectorAll('[data-price]').forEach(row => {
                const id = row.dataset.id;
                const qty = parseInt(document.getElementById('qty-' + id)?.textContent || 0);
                count += qty;
            });
            document.getElementById('itemCount').textContent = count;
            document.getElementById('cart-indicator').textContent = count;
        }

        // ─── Change Quantity ──────────────────────────────────────────────────────

        async function changeQty(id, delta, loggedIn) {
            const qtyEl = document.getElementById('qty-' + id);
            const newQty = Math.max(1, parseInt(qtyEl.textContent) + delta);
            qtyEl.textContent = newQty;

            const price = parseFloat(document.querySelector(`[data-id="${id}"]`).dataset.price);
            document.getElementById('total-' + id).textContent = formatPrice(price * newQty);

            updateSubtotal();
            updateItemCount();

            if (loggedIn) {
                // Sync with DB
                await fetch(API, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'updateCartQuantity', cartId: id, quantity: newQty })
                });
            } else {
                // Sync with localStorage
                const cart = getLocalCart();
                const item = cart.find(i => i.product_id == id);
                if (item) {
                    item.quantity = newQty;
                    saveLocalCart(cart);
                }
            }
        }

        // ─── Remove Item ──────────────────────────────────────────────────────────

        async function removeItem(id, loggedIn) {
            document.getElementById('row-' + id)?.remove();
            updateSubtotal();
            updateItemCount();

            // Show empty state if no items left
            if (document.querySelectorAll('[data-price]').length === 0) {
                document.getElementById('cartContent').classList.add('hidden');
                document.getElementById('emptyState').classList.remove('hidden');
            }

            if (loggedIn) {
                await fetch(API, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'removeFromCart', cartId: id })
                });
            } else {
                const cart = getLocalCart().filter(i => i.product_id != id);
                saveLocalCart(cart);
            }
        }

        // ─── Load Cart ────────────────────────────────────────────────────────────

        async function loadCart() {
            const loggedIn = await isLoggedIn();

            if (loggedIn) {
                // Fetch from DB
                try {
                    const res = await fetch(API, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ action: 'fetchDatabaseCart' })
                    });
                    const data = await res.json();
                    renderCart(data.status ? data.cart : [], true);
                } catch (err) {
                    console.error('Cart fetch error:', err);
                    renderCart([], true);
                }
            } else {
                // Load from localStorage
                const localCart = getLocalCart();
                renderCart(localCart, false);
            }
        }

        // ─── Kick off ─────────────────────────────────────────────────────────────
        loadCart();
    </script>
</body>

</html>
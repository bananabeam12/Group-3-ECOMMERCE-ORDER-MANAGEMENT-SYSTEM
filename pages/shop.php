<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skrrt | Streetwear</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/vendor/fontawesome/css/all.min.css">
</head>

<body>
    <!-- NAVIGATION -->
    <nav id="navbar" class="fixed w-full z-50 top-0 start-0 transition-all duration-500 ease-in-out py-6 text-white">
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
                    <li><a href="../pages/404.php" class="hover:opacity-60">Collections</a></li>
                    <li><a href="../pages/404.php" class="hover:opacity-60">About</a></li>
                    <li><a href="../pages/404.php" class="hover:opacity-60">Contact Us</a></li>
                </ul>
            </div>

            <div id="nav-icons" class="flex items-center space-x-6 text-sm transition-colors duration-500">
                <a href="../pages/cart.php" class="relative hover:opacity-60 transition-opacity">
                    <div class="indicator">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span id="cart-indicator" class="bg-white text-black font-semibold px-1 min-w-[10px]">0</span>
                    </div>
                </a>
                 <a href="../pages/profile.php" class="relative hover:opacity-60 transition-opacity">
                    <i class="fa-regular fa-user"></i>
                </a>
            </div>
    </nav>

    <!-- HEADER -->
    <section class="relative w-full h-screen overflow-hidden">
        <img src="../assets/images/hero_carousel_2.jpg" class="absolute inset-0 w-full h-full object-cover"
            alt="Skrrt Hero">
        <div class="absolute inset-0 bg-black/40 z-10"></div>
        <div class="relative z-20 flex flex-col items-center justify-center h-full text-center px-4">
            <h1 class="text-white text-3xl md:text-5xl font-semibold tracking-wider leading-none">
                All Products
            </h1>
        </div>
    </section>

    <!-- SHOP SECTION -->
    <section class="bg-white py-20 px-4 md:px-10">
        <div class="max-w-screen-xl mx-auto">

            <!-- Search & Sort Bar -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-16 gap-6 border-b border-black/5 pb-8">
                <div class="relative w-full md:w-96 group">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-black/30 group-focus-within:text-black/40 transition-colors"></i>
                    <input id="searchBar" type="text" placeholder="Search products..."
                        class="w-full pl-12 pr-4 py-3 bg-zinc-50 border border-black/5 rounded-full text-xs font-bold tracking-widest focus:outline-none focus:border-black/40 transition-all">
                </div>

                <div class="flex items-center space-x-4 w-full md:w-auto justify-between md:justify-end">
                    <span class="text-xs font-semibold tracking-widest text-black/40">Sort By:</span>
                    <select id="sortSelect"
                        class="bg-transparent text-xs font-bold uppercase tracking-wider focus:outline-none cursor-pointer border-b-2 border-black pb-1 hover:text-black/80 transition-colors">
                        <option value="newest">Newest Arrivals</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                    </select>
                </div>
            </div>

            <!-- Product Grid -->
            <div id="productGrid" class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-x-12 md:gap-y-16">
                <!-- Skeleton loaders shown while fetching -->
                <?php for ($i = 0; $i < 8; $i++): ?>
                <div class="skeleton-card animate-pulse">
                    <div class="aspect-square w-full bg-zinc-100 mb-6 rounded"></div>
                    <div class="h-4 bg-zinc-100 rounded w-3/4 mx-auto mb-3"></div>
                    <div class="h-4 bg-zinc-100 rounded w-1/2 mx-auto"></div>
                </div>
                <?php endfor; ?>
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="hidden text-center py-24">
                <p class="text-2xl font-semibold uppercase tracking-widest text-black/20">No products found</p>
            </div>

        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-[#A6F000] pt-20 pb-0 px-4 md:px-20 text-black relative overflow-hidden mt-10">
        <div class="max-w-screen-xl mx-auto relative z-30">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-10">
                <div>
                    <h4 class="text-[10px] uppercase tracking-[0.2em] font-bold mb-6 opacity-50">Information</h4>
                    <ul class="space-y-3 text-[13px] font-medium">
                        <li><a href="../pages/404.php" class="hover:underline">Privacy</a></li>
                        <li><a href="../pages/404.php" class="hover:underline">FAQ</a></li>
                        <li><a href="../pages/404.php" class="hover:underline">Shipping and payment</a></li>
                        <li><a href="../pages/404.php" class="hover:underline">Partners</a></li>
                        <li><a href="../pages/404.php" class="hover:underline">Blog</a></li>
                        <li><a href="../pages/404.php" class="hover:underline">Contacts</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[10px] uppercase tracking-[0.2em] font-bold mb-6 opacity-50">Menu</h4>
                    <ul class="space-y-3 text-[13px] font-medium">
                        <li><a href="../pages/shop.php" class="hover:underline">Shop</a></li>
                        <li><a href="../pages/404.php" class="hover:underline">Collections</a></li>
                        <li><a href="../pages/404.php" class="hover:underline">New Releases</a></li>
                    </ul>
                </div>
                <div class="hidden md:block"></div>
                <div class="flex flex-col items-start md:items-end">
                    <div class="bg-black text-[#A6F000] px-8 py-3 rounded-full text-[12px] font-bold uppercase mb-4 cursor-pointer hover:scale-105 transition-transform">
                        Request a call
                    </div>
                    <p class="text-[14px] font-bold">+1 (888) 999-99-99</p>
                    <p class="text-[14px] font-medium opacity-70">info@skrrtworldwide.com</p>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center border-t border-black/10 pt-10 mb-10 md:mb-15">
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
        <div class="relative left-1/2 -translate-x-1/2 w-[115%] md:w-[130%] mt-5 pointer-events-none z-10 origin-bottom">
            <img src="../assets/images/Skrrt_logo-Half.svg" alt="Logo"
                class="w-full h-auto object-contain object-bottom select-none">
        </div>
    </footer>
    <!--for navscroll effect -->
    <script src="../assets/js/navScroll.js"></script>  
    <!-- for cart indicator update -->
    <script src="../assets/js/cart.js"></script> 
    <script>
        const grid       = document.getElementById('productGrid');
        const emptyState = document.getElementById('emptyState');
        const searchBar  = document.getElementById('searchBar');
        const sortSelect = document.getElementById('sortSelect');

        let allProducts = []; // Master copy from the API
        let searchTimeout = null;

        // ── Build a single product card HTML string ──────────────────────────
        function buildCard(product) {
            const image = product.image_url
                ? `../${product.image_url}`
                : '../assets/images/placeholder_tee.png';

            const name  = product.product_name.replace(/"/g, '&quot;');
            const price = parseFloat(product.price).toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            return `
                <a href="../pages/product_details.php?id=${product.product_id}"
                   class="block product-card group text-center cursor-pointer">
                    <div class="relative aspect-square w-full mb-6 overflow-hidden">
                        <img src="${image}" alt="${name}"
                             class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300 ease-in-out"
                             onerror="this.src='../assets/images/placeholder_tee.png'">
                    </div>
                    <p class="text-lg font-semibold text-black uppercase tracking-normal mb-2">${product.product_name}</p>
                    <p class="text-base font-semibold text-black opacity-80">₱${price}</p>
                </a>`;
        }

        // ── Render a list of products into the grid ───────────────────────────
        function renderProducts(products) {
            if (products.length === 0) {
                grid.innerHTML = '';
                emptyState.classList.remove('hidden');
                return;
            }

            emptyState.classList.add('hidden');
            grid.innerHTML = products.map(buildCard).join('');
        }

        // ── Sort the current product list ─────────────────────────────────────
        function getSorted(products) {
            const sorted = [...products];
            switch (sortSelect.value) {
                case 'price-low':  return sorted.sort((a, b) => a.price - b.price);
                case 'price-high': return sorted.sort((a, b) => b.price - a.price);
                default:           return sorted.sort((a, b) => b.product_id - a.product_id); // newest
            }
        }

        // ── Filter by search term then sort ───────────────────────────────────
        function applyFilters() {
            const term = searchBar.value.trim().toLowerCase();
            let filtered = allProducts;

            if (term) {
                filtered = allProducts.filter(p =>
                    p.product_name.toLowerCase().includes(term) ||
                    (p.product_description && p.product_description.toLowerCase().includes(term))
                );
            }

            renderProducts(getSorted(filtered));
        }

        // ── Initial load: fetch all products from API ─────────────────────────
        async function loadProducts() {
            try {
                // Fetch all products from the API and store in allProducts
                const res  = await fetch('../api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'fetchAllProducts' })
                });
                const data = await res.json();

                if (data.status && data.products.length > 0) {
                    allProducts = data.products;
                    renderProducts(getSorted(allProducts));
                } else {
                    grid.innerHTML = '';
                    emptyState.classList.remove('hidden');
                }
            } catch (err) {
                console.error('Failed to load products:', err);
                grid.innerHTML = '<p class="col-span-4 text-center text-black/40 py-20 text-sm tracking-widest uppercase">Something went wrong. Please refresh.</p>';
            }
        }

        // ── Event Listeners ───────────────────────────────────────────────────

        // Debounce search so it doesn't fire on every keystroke
        searchBar.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(applyFilters, 300);
        });

        sortSelect.addEventListener('change', applyFilters);

        // ── Kick off ──────────────────────────────────────────────────────────
        loadProducts();
    </script>
</body>

</html>
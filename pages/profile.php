<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: pages/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skrrt | Account</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/vendor/fontawesome/css/all.min.css">
</head>

<body class="bg-white">

    <!-- NAVIGATION -->
    <nav id="navbar" class="fixed w-full z-50 top-0 start-0 py-4 bg-white border-b border-black/5 text-black">
        <div class="max-w-screen-xl flex items-center justify-between mx-auto px-10">
            <a href="../customer.php">
                <img src="../assets/images/Skrrt_logo-Alt.png" alt="Logo" class="h-10 w-auto object-contain">
            </a>
            <div class="hidden md:flex items-center">
                <ul class="flex font-semibold space-x-10 text-[14px] tracking-wide uppercase">
                    <li><a href="../pages/shop.php" class="hover:opacity-60">Shop</a></li>
                    <li><a href="../pages/404.php" class="hover:opacity-60">Collections</a></li>
                    <li><a href="../pages/404.php" class="hover:opacity-60">About</a></li>
                    <li><a href="../pages/404.php" class="hover:opacity-60">Contact Us</a></li>
                </ul>
            </div>
            <div class="flex items-center space-x-6 text-sm">
                <a href="../pages/cart.php" class="relative hover:opacity-60 transition-opacity">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span id="cart-indicator" class="bg-[#A6F000] text-black font-semibold px-1 min-w-[10px] text-xs">0</span>
                </a>
                <a href="profile.php" class="hover:opacity-60 transition-opacity">
                    <i class="fa-regular fa-user"></i>
                </a>
            </div>
        </div>
    </nav>

    <main class="min-h-screen pt-28 pb-20 px-6 md:px-10 max-w-screen-xl mx-auto">

        <!-- Loading State -->
        <div id="loadingState" class="flex items-center justify-center py-40">
            <p class="text-xs text-black/30 uppercase tracking-widest animate-pulse">Loading account...</p>
        </div>

        <!-- Main Content (hidden until loaded) -->
        <div id="mainContent" class="hidden">

            <!-- Header -->
            <div class="flex items-start justify-between mb-12">
                <div>
                    <h1 class="text-3xl font-black uppercase tracking-tight">Account</h1>
                    <p id="welcomeName" class="text-sm text-black/40 mt-1"></p>
                </div>
                <button id="logoutBtn"
                    class="text-xs font-bold uppercase tracking-widest text-black/40 hover:text-black transition-colors border-b border-black/20 hover:border-black pb-0.5">
                    Sign Out
                </button>
            </div>

            <!-- Success / Error banners -->
            <div id="successBanner" class="hidden mb-6 bg-[#A6F000] text-black px-4 py-3 text-sm font-semibold rounded-lg"></div>
            <div id="errorBanner"   class="hidden mb-6 bg-red-50 text-red-600 border border-red-100 px-4 py-3 text-sm font-semibold rounded-lg"></div>

            <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-16">

                <!-- ── LEFT: ORDERS + PROFILE FORM ───────────────────────── -->
                <div class="space-y-16">

                    <!-- ORDERS -->
                    <section>
                        <h2 class="text-xl font-black uppercase tracking-tight mb-6">Your Orders</h2>
                        <div id="ordersLoading" class="text-xs text-black/30 uppercase tracking-widest animate-pulse">Loading orders...</div>
                        <div id="ordersList" class="hidden space-y-4"></div>
                        <div id="ordersEmpty" class="hidden text-sm text-black/40">No orders yet.</div>
                    </section>

                    <!-- PROFILE FORM -->
                    <section>
                        <h2 class="text-xl font-black uppercase tracking-tight mb-6">Account Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-xl">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[10px] uppercase tracking-widest font-bold text-black/40">First Name</label>
                                <input id="profileFirstName" type="text"
                                    class="w-full px-4 py-3 border border-black/20 rounded-lg text-sm font-medium focus:outline-none focus:border-black transition-colors">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[10px] uppercase tracking-widest font-bold text-black/40">Last Name</label>
                                <input id="profileLastName" type="text"
                                    class="w-full px-4 py-3 border border-black/20 rounded-lg text-sm font-medium focus:outline-none focus:border-black transition-colors">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[10px] uppercase tracking-widest font-bold text-black/40">Phone</label>
                                <input id="profilePhone" type="tel"
                                    class="w-full px-4 py-3 border border-black/20 rounded-lg text-sm font-medium focus:outline-none focus:border-black transition-colors">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[10px] uppercase tracking-widest font-bold text-black/40">Email</label>
                                <input id="profileEmail" type="email" disabled
                                    class="w-full px-4 py-3 border border-black/10 rounded-lg text-sm font-medium bg-gray-50 text-black/40 cursor-not-allowed">
                            </div>
                        </div>

                        <button id="saveProfileBtn"
                            class="mt-6 bg-black text-white px-10 py-4 text-xs font-black uppercase tracking-widest hover:bg-[#A6F000] hover:text-black transition-all rounded-lg">
                            Save Details
                        </button>
                    </section>

                </div>

                <!-- ── RIGHT: ADDRESS ─────────────────────────────────────── -->
                <aside class="lg:pt-2">

                    <!-- Saved Addresses -->
                    <div class="mb-8">
                        <h2 class="text-xl font-black uppercase tracking-tight mb-4">Saved Address</h2>
                        <div id="addressDisplay" class="text-sm text-black/40 space-y-1"></div>
                    </div>

                    <!-- Update Address Form -->
                    <div>
                        <h3 class="text-base font-black uppercase tracking-tight mb-4">Update Address</h3>
                        <div class="space-y-3">
                            <input id="addrStreet" type="text" placeholder="Street address"
                                class="w-full px-4 py-3 border border-black/20 rounded-lg text-sm font-medium placeholder-black/30 focus:outline-none focus:border-black transition-colors">
                            <div class="grid grid-cols-2 gap-3">
                                <input id="addrCity" type="text" placeholder="City"
                                    class="w-full px-4 py-3 border border-black/20 rounded-lg text-sm font-medium placeholder-black/30 focus:outline-none focus:border-black transition-colors">
                                <input id="addrProvince" type="text" placeholder="Province"
                                    class="w-full px-4 py-3 border border-black/20 rounded-lg text-sm font-medium placeholder-black/30 focus:outline-none focus:border-black transition-colors">
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <input id="addrZip" type="text" placeholder="ZIP code"
                                    class="w-full px-4 py-3 border border-black/20 rounded-lg text-sm font-medium placeholder-black/30 focus:outline-none focus:border-black transition-colors">
                                <input id="addrCountry" type="text" placeholder="Country" value="Philippines"
                                    class="w-full px-4 py-3 border border-black/20 rounded-lg text-sm font-medium placeholder-black/30 focus:outline-none focus:border-black transition-colors">
                            </div>
                        </div>
                        <input type="hidden" id="addrId">
                        <button id="saveAddressBtn"
                            class="mt-4 w-full bg-black text-white px-6 py-3 text-xs font-black uppercase tracking-widest hover:bg-[#A6F000] hover:text-black transition-all rounded-lg">
                            Save Address
                        </button>
                    </div>

                </aside>
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
                        <li><a href="pages/shop.php" class="hover:underline">Shop</a></li>
                        <li><a href="pages/collections.php" class="hover:underline">Collections</a></li>
                        <li><a href="pages/new_releases.php" class="hover:underline">New Releases</a></li>
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
        <div class="relative left-1/2 -translate-x-1/2 w-[115%] md:w-[130%] mt-5 pointer-events-none z-10 origin-bottom">
            <img src="assets/images/Skrrt_logo-Half.svg" alt="Logo"
                class="w-full h-auto object-contain object-bottom select-none">
        </div>
    </footer>

    <script src="../assets/js/cart.js"></script>
    <script>
        
        const API = '../api.php';

        function formatPrice(amount) {
            return '₱' + parseFloat(amount).toLocaleString('en-PH', {
                minimumFractionDigits: 2, maximumFractionDigits: 2
            });
        }

        function showSuccess(msg) {
            const el = document.getElementById('successBanner');
            el.textContent = msg;
            el.classList.remove('hidden');
            setTimeout(() => el.classList.add('hidden'), 4000);
        }

        function showError(msg) {
            const el = document.getElementById('errorBanner');
            el.textContent = msg;
            el.classList.remove('hidden');
            setTimeout(() => el.classList.add('hidden'), 5000);
        }

        // ── Load profile + address ────────────────────────────────────────────
        let currentAddressId = null;
        // Fetch user profile and address data from the API and populate the form fields
        async function loadProfile() {
            const res  = await fetch(API, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'getProfile' })
            });
            const data = await res.json();

            // If not logged in or error, redirect to login page
            if (!data.status) {
                window.location.href = '../index.php';
                return;
            }

            const u = data.user;
            document.getElementById('welcomeName').textContent     = u.first_name + ' ' + u.last_name;
            document.getElementById('profileFirstName').value      = u.first_name    || '';
            document.getElementById('profileLastName').value       = u.last_name     || '';
            document.getElementById('profilePhone').value          = u.phone_number  || '';
            document.getElementById('profileEmail').value          = u.email         || '';

            // Fill address if exists
            const addresses = data.addresses || [];
            if (addresses.length > 0) {
                const a = addresses[0];
                currentAddressId = a.address_id;
                document.getElementById('addrId').value      = a.address_id           || '';
                document.getElementById('addrStreet').value  = a.address_description  || '';
                document.getElementById('addrCity').value    = a.city                 || '';
                document.getElementById('addrProvince').value = a.province            || '';
                document.getElementById('addrZip').value     = a.zip_code             || '';
                document.getElementById('addrCountry').value = a.country              || 'Philippines';

                document.getElementById('addressDisplay').innerHTML = `
                    <p class="font-semibold text-black text-sm">${a.address_description}</p>
                    <p>${a.city}, ${a.province} ${a.zip_code}</p>
                    <p>${a.country}</p>`;
            } else {
                document.getElementById('addressDisplay').innerHTML =
                    '<p class="text-xs text-black/30 uppercase tracking-widest">No address saved yet.</p>';
            }

            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('mainContent').classList.remove('hidden');
        }

        // ── Load orders ───────────────────────────────────────────────────────
        async function loadOrders() {
            // Fetch user orders from the API and populate the orders list
            const res  = await fetch(API, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'fetchUserOrders' })
            });
            const data = await res.json();

            document.getElementById('ordersLoading').classList.add('hidden');

            if (!data.status || data.orders.length === 0) {
                document.getElementById('ordersEmpty').classList.remove('hidden');
                return;
            }

            const list = document.getElementById('ordersList');
            list.classList.remove('hidden');
            list.innerHTML = data.orders.map(order => `
                <div class="border border-black/10 rounded-xl p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-xs font-black uppercase tracking-widest">Order #${order.order_id}</p>
                            <p class="text-xs text-black/40 mt-0.5">${order.created_at ?? ''}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-full ${
                                order.status === 'delivered'  ? 'bg-green-100 text-green-700' :
                                order.status === 'cancelled'  ? 'bg-red-100 text-red-500'     :
                                order.status === 'shipped'    ? 'bg-blue-100 text-blue-600'   :
                                'bg-yellow-100 text-yellow-700'
                            }">${order.status}</span>
                            <span class="text-sm font-black">${formatPrice(order.total_amount)}</span>
                        </div>
                    </div>
                    <div class="space-y-1">
                        ${(order.items || []).map(item => `
                            <p class="text-xs text-black/50">${item.product_name} × ${item.quantity}</p>
                        `).join('')}
                    </div>
                    ${order.status === 'pending' ? `
                    <button onclick="cancelOrder(${order.order_id})"
                        class="mt-3 text-xs font-bold uppercase tracking-widest text-red-400 hover:text-red-600 transition-colors">
                        Cancel Order
                    </button>` : ''}
                </div>`).join('');
        }

        // ── Cancel order ──────────────────────────────────────────────────────
        async function cancelOrder(orderId) {
            if (!confirm('Are you sure you want to cancel this order?')) return;

            const res  = await fetch(API, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'cancelOrder', orderId: orderId })
            });
            const data = await res.json();

            if (data.status) {
                showSuccess(data.message);
                loadOrders();
            } else {
                showError(data.message);
            }
        }

        // ── Save profile ──────────────────────────────────────────────────────
        document.getElementById('saveProfileBtn').addEventListener('click', async () => {
            const res  = await fetch(API, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action:    'updateProfile',
                    firstName: document.getElementById('profileFirstName').value,
                    lastName:  document.getElementById('profileLastName').value,
                    phone:     document.getElementById('profilePhone').value,
                    // Pass address fields too so the address isn't wiped
                    addressId: currentAddressId,
                    address:   document.getElementById('addrStreet').value,
                    city:      document.getElementById('addrCity').value,
                    province:  document.getElementById('addrProvince').value,
                    zip:       document.getElementById('addrZip').value,
                    country:   document.getElementById('addrCountry').value,
                })
            });
            const data = await res.json();
            data.status ? showSuccess(data.message) : showError(data.message);
        });

        // ── Save address ──────────────────────────────────────────────────────
        document.getElementById('saveAddressBtn').addEventListener('click', async () => {
            const firstName = document.getElementById('profileFirstName').value;
            const lastName  = document.getElementById('profileLastName').value;
            const phone     = document.getElementById('profilePhone').value;

            const res  = await fetch(API, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action:    'updateProfile',
                    firstName: firstName,
                    lastName:  lastName,
                    phone:     phone,
                    addressId: currentAddressId,
                    address:   document.getElementById('addrStreet').value,
                    city:      document.getElementById('addrCity').value,
                    province:  document.getElementById('addrProvince').value,
                    zip:       document.getElementById('addrZip').value,
                    country:   document.getElementById('addrCountry').value,
                })
            });
            const data = await res.json();
            if (data.status) {
                showSuccess('Address saved!');
                loadProfile(); // refresh address display
            } else {
                showError(data.message);
            }
        });

        // ── Logout ────────────────────────────────────────────────────────────
        document.getElementById('logoutBtn').addEventListener('click', async () => {
            await fetch(API, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'logout' })
            });
            window.location.href = '../index.php';
        });

        // ── Init ──────────────────────────────────────────────────────────────
        loadProfile();
        loadOrders();
    </script>
</body>

</html>
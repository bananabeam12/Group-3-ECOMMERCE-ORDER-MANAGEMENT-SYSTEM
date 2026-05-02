<?php
// Checkout page - users must be logged in to access
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=checkout");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Skrrt Streetwear</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/vendor/fontawesome/css/all.min.css">
</head>

<body class="bg-white">

    <nav class="border border-black/10 py-5 ">
        <div class="flex mx-45 ">
            <a href="../index.php">
                <h1 class="font-semi text-6xl tracking-wider">SKRRT</h1>
            </a>
        </div>
    </nav>
    <div class="flex flex-col lg:flex-row min-h-screen">

        <!-- ── LEFT COLUMN: FORM ─────────────────────────────────────────────── -->
        <div class="w-full lg:w-[58%] px-6 md:px-14 lg:px-20 py-10 border-r border-gray-100">
            <div class="max-w-lg ml-auto">

                <!-- Logo -->

                <div id="checkoutForm" class="space-y-8">

                    <!-- CONTACT -->
                    <section>
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-base font-bold uppercase tracking-widest">Contact</h2>
                        </div>
                        <div class="space-y-3">
                            <input id="email" type="email" placeholder="Email"
                                class="w-full px-4 py-3 border border-black/20 rounded-lg text-sm font-medium placeholder-black/30 focus:outline-none focus:border-black transition-colors">
                            <div class="grid grid-cols-2 gap-3">
                                <input id="firstName" type="text" placeholder="First name"
                                    class="w-full px-4 py-3 border border-black/20 rounded-lg text-sm font-medium placeholder-black/30 focus:outline-none focus:border-black transition-colors">
                                <input id="lastName" type="text" placeholder="Last name"
                                    class="w-full px-4 py-3 border border-black/20 rounded-lg text-sm font-medium placeholder-black/30 focus:outline-none focus:border-black transition-colors">
                            </div>
                            <input id="phone" type="tel" placeholder="Phone number"
                                class="w-full px-4 py-3 border border-black/20 rounded-lg text-sm font-medium placeholder-black/30 focus:outline-none focus:border-black transition-colors">
                        </div>
                        <label class="flex items-center mt-3 gap-2 cursor-pointer">
                            <input type="checkbox" class="h-4 w-4 rounded border-gray-300 accent-black">
                            <span class="text-xs text-gray-400">Email me with news and offers</span>
                        </label>
                    </section>

                    <hr class="border-gray-100">

                    <!-- DELIVERY -->
                    <section>
                        <h2 class="text-base font-bold uppercase tracking-widest mb-4">Delivery</h2>
                        <div class="space-y-3">
                            <select id="country"
                                class="w-full px-4 py-3 border border-black/20 rounded-lg text-sm font-medium focus:outline-none focus:border-black transition-colors bg-white">
                                <option value="Philippines">Philippines</option>
                                <option value="United States">United States</option>
                                <option value="Japan">Japan</option>
                                <option value="Singapore">Singapore</option>
                                <option value="Australia">Australia</option>
                            </select>
                            <input id="address" type="text" placeholder="Address"
                                class="w-full px-4 py-3 border border-black/20 rounded-lg text-sm font-medium placeholder-black/30 focus:outline-none focus:border-black transition-colors">
                            <input id="apartment" type="text" placeholder="Apartment, suite, etc. (optional)"
                                class="w-full px-4 py-3 border border-black/20 rounded-lg text-sm font-medium placeholder-black/30 focus:outline-none focus:border-black transition-colors">
                            <div class="grid grid-cols-3 gap-3">
                                <input id="city" type="text" placeholder="City"
                                    class="w-full px-4 py-3 border border-black/20 rounded-lg text-sm font-medium placeholder-black/30 focus:outline-none focus:border-black transition-colors">
                                <input id="province" type="text" placeholder="Province"
                                    class="w-full px-4 py-3 border border-black/20 rounded-lg text-sm font-medium placeholder-black/30 focus:outline-none focus:border-black transition-colors">
                                <input id="zip" type="text" placeholder="Postal code"
                                    class="w-full px-4 py-3 border border-black/20 rounded-lg text-sm font-medium placeholder-black/30 focus:outline-none focus:border-black transition-colors">
                            </div>
                        </div>
                    </section>

                    <hr class="border-gray-100">

                    <!-- PAYMENT -->
                    <section>
                        <h2 class="text-base font-bold uppercase tracking-widest mb-1">Payment</h2>
                        <p class="text-xs text-gray-400 mb-4">All transactions are secure and encrypted.</p>
                        <div class="space-y-2">

                            <label
                                class="flex items-center gap-4 px-4 py-3 border border-black/20 rounded-lg cursor-pointer hover:border-gray-400 transition-colors has-[:checked]:ring-1 has-[:checked]:ring-black has-[:checked]:border-transparent">
                                <input type="radio" name="paymentMethod" value="COD" checked
                                    class="accent-black w-4 h-4">
                                <i class="fa-solid fa-money-bill-wave text-sm text-gray-400 "></i>
                                <div>
                                    <p class="text-sm font-bold">Cash on Delivery</p>
                                    <p class="text-xs text-gray-400">Pay when your order arrives</p>
                                </div>
                            </label>

                            <label
                                class="flex items-center gap-4 px-4 py-3 border border-black/20 rounded-lg cursor-pointer hover:border-gray-400 transition-colors has-[:checked]:ring-1 has-[:checked]:ring-black has-[:checked]:border-transparent">
                                <input type="radio" name="paymentMethod" value="GCash" class="accent-black w-4 h-4">
                                <i class="fa-solid fa-mobile-screen text-sm text-gray-400"></i>
                                <div>
                                    <p class="text-sm font-bold">GCash</p>
                                    <p class="text-xs text-gray-400">Pay via GCash e-wallet</p>
                                </div>
                            </label>

                            <label
                                class="flex items-center gap-4 px-4 py-3 border border-black/20 rounded-lg cursor-pointer hover:border-gray-400 transition-colors has-[:checked]:ring-1 has-[:checked]:ring-black has-[:checked]:border-transparent">
                                <input type="radio" name="paymentMethod" value="Bank Transfer"
                                    class="accent-black w-4 h-4">
                                <i class="fa-solid fa-building-columns text-sm text-gray-400"></i>
                                <div>
                                    <p class="text-sm font-bold">Bank Transfer</p>
                                    <p class="text-xs text-gray-400">Direct bank deposit or transfer</p>
                                </div>
                            </label>

                        </div>
                    </section>

                    <!-- Error -->
                    <div id="errorMsg"
                        class="hidden text-xs text-red-500 font-semibold bg-red-50 border border-red-100 rounded-lg px-4 py-3">
                    </div>

                    <!-- Submit -->
                    <button id="placeOrderBtn"
                        class="w-full bg-black text-white py-4 font-black uppercase tracking-widest text-sm hover:bg-black/80 transition-all">
                        Place Order
                    </button>

                    <p class="text-center text-xs text-gray-300">
                        <i class="fa-solid fa-lock text-[10px] mr-1"></i>
                        Secured by Skrrt Worldwide
                    </p>

                </div>

                <!-- Success State -->
                <div id="successState" class="hidden text-center py-20">
                    <div class="w-16 h-16 bg-[#A6F000] rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-check text-black text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-black uppercase tracking-tight mb-2">Order Placed!</h2>
                    <p id="successOrderId" class="text-sm text-gray-400 mb-8"></p>
                    <a href="../pages/shop.php"
                        class="inline-block bg-black text-white px-10 py-4 text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition-colors rounded-lg">
                        Continue Shopping
                    </a>
                </div>

            </div>
        </div>

        <!-- ── RIGHT COLUMN: ORDER SUMMARY ───────────────────────────────────── -->
        <div
            class="w-full lg:w-[42%] bg-[#f5f5f5] px-6 md:px-14 lg:px-16 py-10 lg:sticky lg:top-0 lg:h-screen lg:overflow-y-auto">
            <div class="max-w-md pt-4 lg:pt-[72px]">

                <!-- Loading -->
                <div id="summaryLoading" class="text-center py-10">
                    <p class="text-xs text-gray-400 uppercase tracking-widest animate-pulse">Loading cart...</p>
                </div>

                <!-- Items -->
                <div id="summaryItems" class="hidden space-y-5 mb-8"></div>

                <!-- Empty -->
                <div id="summaryEmpty" class="hidden text-center py-10">
                    <p class="text-xs text-gray-400 uppercase tracking-widest mb-4">Your cart is empty</p>
                    <a href="../pages/shop.php" class="text-xs font-bold uppercase tracking-widest underline">Browse
                        Shop</a>
                </div>

                <!-- Discount -->
                <div id="discountRow" class="hidden flex gap-2 mb-6">
                    <input type="text" placeholder="Discount code"
                        class="flex-1 px-4 py-3 border border-black/20 rounded-lg text-sm font-medium placeholder-black/30 focus:outline-none focus:border-black transition-colors bg-white">
                    <button
                        class="px-5 py-3 bg-gray-200 rounded-lg text-xs font-bold text-gray-500 uppercase tracking-widest hover:bg-gray-300 transition-colors whitespace-nowrap">
                        Apply
                    </button>
                </div>

                <!-- Totals -->
                <div id="summaryTotals" class="hidden space-y-2 text-sm border-t border-black/20 pt-5">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal</span>
                        <span id="summarySubtotal" class="font-semibold text-black">—</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Shipping</span>
                        <span class="text-xs text-gray-400">Free Shipping</span>
                    </div>
                    <div class="flex justify-between font-black text-base pt-4 border-t border-black/20">
                        <span>Total</span>
                        <div class="flex items-baseline gap-2">
                            <span class="text-[10px] text-gray-400 font-normal uppercase">PHP</span>
                            <span id="summaryTotal">—</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script src="../assets/js/cart.js"></script>
    <script>
        // API endpoint for AJAX calls
        const API = '../api.php';

        function formatPrice(amount) {
            return '₱' + parseFloat(amount).toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
        // Builds the HTML for a single item in the order summary sidebar
        function buildSummaryItem(item) {
            const image = item.image_url
                ? '../../' + item.image_url
                : '../../assets/images/placeholder_tee.png';
            const total = parseFloat(item.price) * parseInt(item.quantity);

            return `
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="relative w-16 h-16 bg-white border border-black/20 rounded-lg flex-shrink-0">
                <img src="${image}"
                     onerror="this.onerror=null; this.src='../assets/images/placeholder_tee.png'"
                     class="w-full h-full object-contain p-1 rounded-lg">
                <span class="absolute -top-2 -right-2 bg-gray-500 text-white text-[10px] w-5 h-5 flex items-center justify-center rounded-full font-bold">
                    ${item.quantity}
                </span>
            </div>
            <div>
                <p class="text-sm font-bold uppercase leading-tight">${item.product_name}</p>
            </div>
        </div>
        <p class="text-sm font-semibold whitespace-nowrap">${formatPrice(total)}</p>
    </div>`;
        }

        let cartItems = [];
        let cartTotal = 0;
        // Loads the cart summary and pre-fills contact/delivery info if available
        async function loadSummary() {
            try {
                const cartRes = await fetch(API, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'fetchDatabaseCart' })
                });
                const cartData = await cartRes.json();

                // prefill contact info
                const profileRes = await fetch(API, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'getProfile' })
                });
                const profileData = await profileRes.json();
                if (profileData.status && profileData.user) {
                    const u = profileData.user;
                    document.getElementById('email').value     = u.email      || '';
                    document.getElementById('firstName').value = u.first_name || '';
                    document.getElementById('lastName').value  = u.last_name  || '';
                    document.getElementById('phone').value     = u.phone_number || '';
                }

                // Prefill delivery fields
                if (profileData.status && profileData.addresses && profileData.addresses.length > 0) {
                    const a = profileData.addresses[0];
                    document.getElementById('address').value  = a.address_description || '';
                    document.getElementById('city').value     = a.city                || '';
                    document.getElementById('province').value = a.province            || '';
                    document.getElementById('zip').value      = a.zip_code            || '';

                    
                    const countrySelect = document.getElementById('country');
                    const countryVal = a.country || '';
                    const matchingOption = Array.from(countrySelect.options)
                        .find(opt => opt.value.toLowerCase() === countryVal.toLowerCase());
                    if (matchingOption) countrySelect.value = matchingOption.value;
                }

                cartItems = cartData.status ? cartData.cart : [];
                cartTotal = cartData.total || cartItems.reduce((s, i) => s + i.price * i.quantity, 0);
                renderSummary();
            } catch (err) {
                console.error('Summary load error:', err);
                renderSummary();
            }
        }
        // Renders the order summary sidebar based on the current cart items and total
        function renderSummary() {
            document.getElementById('summaryLoading').classList.add('hidden');

            if (cartItems.length === 0) {
                document.getElementById('summaryEmpty').classList.remove('hidden');
                document.getElementById('placeOrderBtn').disabled = true;
                document.getElementById('placeOrderBtn').classList.add('opacity-40', 'cursor-not-allowed');
                return;
            }

            document.getElementById('summaryItems').classList.remove('hidden');
            document.getElementById('summaryItems').innerHTML = cartItems.map(buildSummaryItem).join('');
            document.getElementById('discountRow').classList.remove('hidden');
            document.getElementById('summaryTotals').classList.remove('hidden');
            document.getElementById('summarySubtotal').textContent = formatPrice(cartTotal);
            document.getElementById('summaryTotal').textContent = formatPrice(cartTotal);
        }
        // Validates the checkout form fields and highlights any missing required fields
        function validate() {
            const required = ['email', 'firstName', 'lastName', 'address', 'city', 'province', 'zip'];
            let valid = true;
            required.forEach(id => {
                const el = document.getElementById(id);
                el.classList.remove('border-red-400');
                if (!el.value.trim()) {
                    el.classList.add('border-red-400');
                    valid = false;
                }
            });
            return valid;
        }

        document.getElementById('placeOrderBtn').addEventListener('click', async () => {
            const errEl = document.getElementById('errorMsg');
            errEl.classList.add('hidden');

            if (!validate()) {
                errEl.textContent = 'Please fill in all required fields.';
                errEl.classList.remove('hidden');
                errEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
            const btn = document.getElementById('placeOrderBtn');
            btn.disabled = true;
            btn.textContent = 'Placing Order...';

            try {
                const res = await fetch(API, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'placeOrder',
                        totalAmount: cartTotal,
                        shippingAddress: document.getElementById('address').value,
                        city: document.getElementById('city').value,
                        country: document.getElementById('country').value,
                        paymentMethod: paymentMethod
                    })
                });
                const data = await res.json();
                if (data.status) {
                    showSuccess(data.orderId);
                } else {
                    throw new Error(data.message || 'Order failed.');
                }
            } catch (err) {
                errEl.textContent = err.message || 'Something went wrong. Please try again.';
                errEl.classList.remove('hidden');
                btn.disabled = false;
                btn.textContent = 'Place Order';
            }
        });
        // Displays the success message and order ID after a successful order placement
        function showSuccess(orderId) {
            document.getElementById('checkoutForm').classList.add('hidden');
            document.getElementById('successState').classList.remove('hidden');
            document.getElementById('successOrderId').textContent = orderId
                ? `Your order #${orderId} has been confirmed. We'll be in touch soon.`
                : 'Your order has been received!';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        loadSummary();
    </script>
</body>

</html>
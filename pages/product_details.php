<!DOCTYPE html>
<html lang="en">
<?php
require_once __DIR__ . "/../includes/config.php";

// ── requires a valid product ID in the URL ──────────────────────────────
$pId = intval($_GET['id'] ?? 0);

if ($pId <= 0) {
    header("Location: shop.php");
    exit;
}

// ── Fetch product + category ──────────────────────────────────────────────────
$stmt = $conn->prepare(
    "SELECT p.*, c.category_name 
     FROM products p 
     JOIN categories c ON p.category_id = c.category_id 
     WHERE p.product_id = ? AND p.status = 'active'"
);
$stmt->bind_param("i", $pId);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header("Location: shop.php");
    exit;
}

// ── Fetch all gallery images ──────────────────────────────────────────────────
$stmtG = $conn->prepare("SELECT image_url FROM product_images WHERE product_id = ? ORDER BY product_image_id ASC");
$stmtG->bind_param("i", $pId);
$stmtG->execute();
$images = $stmtG->get_result()->fetch_all(MYSQLI_ASSOC);

// Fallback if no images
if (empty($images)) {
    $images = [['image_url' => 'assets/images/placeholder_tee.png']];
}

// ── Fetch reviews ─────────────────────────────────────────────────────────────
$stmtR = $conn->prepare(
    "SELECT r.rating, r.review_comments, r.review_id,
            u.first_name, u.last_name
     FROM reviews r
     JOIN users u ON r.user_id = u.user_id
     WHERE r.product_id = ?
     ORDER BY r.review_id DESC"
);
$stmtR->bind_param("i", $pId);
$stmtR->execute();
$reviews = $stmtR->get_result()->fetch_all(MYSQLI_ASSOC);

// ── Compute average rating ────────────────────────────────────────────────────
$reviewCount = count($reviews);
$avgRating = $reviewCount > 0
    ? array_sum(array_column($reviews, 'rating')) / $reviewCount
    : 0;
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skrrt | Streetwear</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/vendor/fontawesome/css/all.min.css">
</head>

<body>
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
                    <li><a href="../pages/404.php" class="hover:opacity-60">Collections</a></li>
                    <li><a href="../pages/404.php" class="hover:opacity-60">About</a></li>
                    <li><a href="../pages/404.php" class="hover:opacity-60">Contact Us</a></li>
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

    <!-- PRODUCT SECTION -->
    <section class="max-w-5xl mx-auto px-4 py-[8em]">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

            <!-- Image Gallery -->
            <div class="flex gap-3">
                <!-- Thumbnails -->
                <div class="flex flex-col gap-2" id="thumbs">
                    <?php foreach ($images as $i => $img):
                        $src = '../' . htmlspecialchars($img['image_url']);
                        ?>
                        <div class="thumb w-[72px] h-[72px] rounded-lg overflow-hidden cursor-pointer border-2 <?= $i === 0 ? 'border-black' : 'border-transparent' ?> transition-all duration-150 flex-shrink-0 bg-gray-100"
                            onclick="switchImage('<?= $src ?>', this)">
                            <img src="<?= $src ?>" alt="Product view <?= $i + 1 ?>" class="w-full h-full object-cover"
                            onerror="this.src='../assets/images/placeholder_tee.png'">
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Main Image -->
                <div class="flex-1 rounded-xl overflow-hidden bg-gray-100 aspect-[4/5]">
                    <img id="mainImg" src="../<?= htmlspecialchars($images[0]['image_url']) ?>"
                        alt="<?= htmlspecialchars($product['product_name']) ?>"
                        class="w-full h-full object-cover transition-opacity duration-200"
                        onerror="this.src='../assets/images/placeholder_tee.png'">
                </div>
            </div>

            <!-- Product Info -->
            <div class="flex flex-col gap-4">

                <!-- Category -->
                <p class="text-xs uppercase tracking-widest text-gray-400">
                    <?= htmlspecialchars($product['category_name']) ?>
                </p>

                <!-- Name -->
                <h1 class="text-[1.6rem] font-black uppercase tracking-tight leading-tight">
                    <?= htmlspecialchars($product['product_name']) ?>
                </h1>

                <!-- Stars -->
                <div class="flex items-center gap-1">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="text-sm <?= $i <= round($avgRating) ? 'text-yellow-500' : 'text-gray-300' ?>">★</span>
                    <?php endfor; ?>
                    <span class="text-xs text-gray-500 ml-1"><?= $reviewCount ?> reviews</span>
                </div>

                <!-- Price -->
                <p class="text-xl font-medium">
                    ₱<?= number_format($product['price'], 2) ?>
                </p>

                <!-- Stock -->
                <p class="text-xs <?= $product['stock_quantity'] > 0 ? 'text-green-600' : 'text-red-500' ?>">
                    <?= $product['stock_quantity'] > 0
                        ? $product['stock_quantity'] . ' in stock'
                        : 'Out of stock' ?>
                </p>

                <hr class="border-gray-200">

                <!-- Size -->
                <div>
                    <p class="text-xs uppercase tracking-widest text-gray-500 mb-2">Size</p>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach (['XS', 'S', 'M', 'L', 'XL', '2XL'] as $size): ?>
                            <button
                                class="size-btn w-11 h-11 rounded-lg border border-gray-300 text-sm font-medium transition-all duration-150"
                                onclick="selectSize(this)" id="btn-<?= $size == 'M' ? 'M' : ''; ?>">
                                <?= $size ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Quantity -->
                <div>
                    <p class="text-xs uppercase tracking-widest text-gray-500 mb-2">Quantity</p>
                    <div class="flex items-center border border-gray-300 rounded-lg w-fit">
                        <button onclick="changeQty(-1)"
                            class="w-9 h-9 flex items-center justify-center text-lg text-black hover:bg-gray-100 rounded-l-lg transition-colors">−</button>
                        <span id="qty" class="text-sm min-w-[28px] text-center">1</span>
                        <button onclick="changeQty(1)"
                            class="w-9 h-9 flex items-center justify-center text-lg text-black hover:bg-gray-100 rounded-r-lg transition-colors">+</button>
                    </div>
                </div>

                <!-- Add to Cart -->
                <button id="addToCartBtn" data-product-id="<?= $product['product_id'] ?>" <?= $product['stock_quantity'] <= 0 ? 'disabled' : '' ?>
                    class="w-full py-4 bg-black text-white uppercase text-sm font-bold tracking-widest rounded-lg hover:opacity-80 transition-opacity disabled:opacity-40 disabled:cursor-not-allowed">
                    <?= $product['stock_quantity'] > 0 ? 'Add to Cart' : 'Out of Stock' ?>
                </button>

                <hr class="border-gray-200">

                <!-- Description -->
                <div class="text-sm text-gray-600 leading-relaxed space-y-2">
                    <p><?= htmlspecialchars($product['product_description']) ?></p>
                    <p class="text-xs text-gray-400">* Product color may vary due to photographic lighting situations or
                        monitor settings.</p>
                    <p class="text-xs text-gray-400">* The measurements may have minor variations because of the
                        production process.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- REVIEWS SECTION -->
    <section class="max-w-5xl mx-auto px-4 py-10 border-t border-gray-200">

        <div class="flex items-baseline justify-between mb-6">
            <h2 class="text-lg font-medium uppercase tracking-wide">Customer Reviews</h2>
            <div class="flex items-center gap-3">
                <span class="text-[2rem] font-medium"><?= number_format($avgRating, 1) ?></span>
                <div>
                    <div class="flex gap-0.5">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span
                                class="text-xs <?= $i <= round($avgRating) ? 'text-yellow-500' : 'text-gray-300' ?>">★</span>
                        <?php endfor; ?>
                    </div>
                    <p class="text-xs text-gray-400">Based on <?= $reviewCount ?> reviews</p>
                </div>
            </div>
        </div>

        <!-- Review Cards -->
        <div class="flex flex-col gap-4">
            <?php if (empty($reviews)): ?>
                <p class="text-sm text-gray-400 tracking-wide">No reviews yet. Be the first to leave one!</p>
            <?php else: ?>
                <?php foreach ($reviews as $review):
                    $initials = strtoupper(substr($review['first_name'], 0, 1) . substr($review['last_name'], 0, 1));
                    ?>
                    <div class="border border-gray-100 rounded-xl p-5">
                        <div class="flex items-center gap-3 mb-3">
                            <!-- Avatar -->
                            <div
                                class="w-9 h-9 rounded-full bg-[#A6F000] flex items-center justify-center text-xs font-medium flex-shrink-0">
                                <?= htmlspecialchars($initials) ?>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium">
                                    <?= htmlspecialchars($review['first_name'] . ' ' . $review['last_name']) ?>
                                </p>
                            </div>
                        </div>
                        <!-- Stars -->
                        <div class="flex gap-0.5 mb-2">
                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                <span class="text-xs <?= $s <= $review['rating'] ? 'text-yellow-500' : 'text-gray-300' ?>">★</span>
                            <?php endfor; ?>
                        </div>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            <?= htmlspecialchars($review['review_comments']) ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Write Review Button -->
        <button onclick="openReviewModal()"
            class="mt-5 px-5 py-2.5 border border-gray-300 rounded-lg text-sm text-black hover:bg-gray-50 transition-colors">
            Write a Review
        </button>

    </section>

    <!-- REVIEW MODAL-->
    <div id="reviewModal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-black/50" onclick="closeReviewModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 z-10">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                <h3 class="text-base font-black uppercase tracking-widest">Write a Review</h3>
                <button onclick="closeReviewModal()" class="text-gray-300 hover:text-black text-2xl leading-none transition-colors">&times;</button>
            </div>
            <div class="flex flex-col gap-5">
                <div class="flex flex-col gap-2">
                    <label class="text-[10px] uppercase tracking-widest font-bold text-black/40">Rating</label>
                    <div id="starPicker" class="flex gap-1">
                        <button type="button" onclick="setRating(1)" data-star="1" class="star-btn text-3xl text-gray-200 hover:text-yellow-400 transition-colors leading-none">&#9733;</button>
                        <button type="button" onclick="setRating(2)" data-star="2" class="star-btn text-3xl text-gray-200 hover:text-yellow-400 transition-colors leading-none">&#9733;</button>
                        <button type="button" onclick="setRating(3)" data-star="3" class="star-btn text-3xl text-gray-200 hover:text-yellow-400 transition-colors leading-none">&#9733;</button>
                        <button type="button" onclick="setRating(4)" data-star="4" class="star-btn text-3xl text-gray-200 hover:text-yellow-400 transition-colors leading-none">&#9733;</button>
                        <button type="button" onclick="setRating(5)" data-star="5" class="star-btn text-3xl text-gray-200 hover:text-yellow-400 transition-colors leading-none">&#9733;</button>
                    </div>
                    <input type="hidden" id="reviewRating" value="0">
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-[10px] uppercase tracking-widest font-bold text-black/40">Your Review</label>
                    <textarea id="reviewText" placeholder="Share your thoughts about this product..." rows="4"
                        class="w-full px-4 py-3 border border-black/20 rounded-lg text-sm font-medium placeholder-black/30 focus:outline-none focus:border-black transition-colors resize-none"></textarea>
                    <p class="text-[9px] text-gray-400 uppercase tracking-wide italic">Please keep your review helpful and respectful.</p>
                </div>
                <div id="reviewError" class="hidden text-xs text-red-500 bg-red-50 border border-red-100 rounded-lg px-3 py-2"></div>
                <div class="flex gap-3 mt-1">
                    <button onclick="submitReview()" class="flex-1 py-3 bg-black text-white text-xs font-black uppercase tracking-widest rounded-lg hover:bg-[#A6F000] hover:text-black transition-all">
                        Submit Review
                    </button>
                    <button onclick="closeReviewModal()" class="px-5 py-3 border border-gray-200 text-xs font-bold uppercase tracking-widest rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

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
                    <div
                        class="bg-black text-[#A6F000] px-8 py-3 rounded-full text-[12px] font-bold uppercase mb-4 cursor-pointer hover:scale-105 transition-transform">
                        Request a call
                    </div>
                    <p class="text-[14px] font-bold">+1 (888) 999-99-99</p>
                    <p class="text-[14px] font-medium opacity-70">info@skrrtworldwide.com</p>
                </div>
            </div>

            <div
                class="flex flex-col md:flex-row justify-between items-center border-t border-black/10 pt-10 mb-10 md:mb-15">
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
    <script src="../assets/js/cart.js"></script>
    <script>
        let qty = 1;
        let selectedRating = 0;

        // ── Default size + add to cart ────────────────────────────────────────
        document.addEventListener("DOMContentLoaded", () => {
            const defaultBtn = document.getElementById("btn-M");
            if (defaultBtn) selectSize(defaultBtn);
        });

        document.getElementById('addToCartBtn').addEventListener('click', async () => {
            const result = await addToCart(
                <?= $product['product_id'] ?>,
                <?= json_encode($product['product_name']) ?>,
                <?= $product['price'] ?>,
                <?= json_encode($images[0]['image_url']) ?>,
                qty
            );
            alert(result.message);
        });

        // ── Image gallery ─────────────────────────────────────────────────────
        function switchImage(src, thumbEl) {
            const mainImg = document.getElementById('mainImg');
            mainImg.style.opacity = '0';
            setTimeout(() => {
                mainImg.src = src;
                mainImg.style.opacity = '1';
            }, 150);
            document.querySelectorAll('.thumb').forEach(t => {
                t.classList.remove('border-black');
                t.classList.add('border-transparent');
            });
            thumbEl.classList.remove('border-transparent');
            thumbEl.classList.add('border-black');
        }

        // ── Size selector ─────────────────────────────────────────────────────
        function selectSize(btn) {
            document.querySelectorAll('.size-btn').forEach(b => {
                b.classList.remove('bg-black', 'text-white', 'border-black');
                b.classList.add('bg-white', 'text-black');
            });
            btn.classList.add('bg-black', 'text-white', 'border-black');
            btn.classList.remove('bg-white', 'text-black');
        }

        // ── Quantity ──────────────────────────────────────────────────────────
        function changeQty(delta) {
            qty = Math.max(1, qty + delta);
            document.getElementById('qty').textContent = qty;
        }

        // ── Review Modal Functions ──────────────────────────────────────────────────────
        function openReviewModal() {
            selectedRating = 0;
            document.getElementById('reviewRating').value = 0;
            document.getElementById('reviewText').value   = '';
            document.getElementById('reviewError').classList.add('hidden');
            updateStars(0);
            document.getElementById('reviewModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function setRating(value) {
            selectedRating = value;
            document.getElementById('reviewRating').value = value;
            updateStars(value);
        }

        function updateStars(value) {
            document.querySelectorAll('.star-btn').forEach(btn => {
                const star = parseInt(btn.dataset.star);
                btn.classList.toggle('text-yellow-400', star <= value);
                btn.classList.toggle('text-gray-200',   star > value);
            });
        }
                                
        async function submitReview() {
            const errEl   = document.getElementById('reviewError');
            const rating  = parseInt(document.getElementById('reviewRating').value);
            const comment = document.getElementById('reviewText').value.trim();

            errEl.classList.add('hidden');

            if (rating < 1) {
                errEl.textContent = 'Please select a star rating.';
                errEl.classList.remove('hidden');
                return;
            }
            if (!comment) {
                errEl.textContent = 'Please write a review before submitting.';
                errEl.classList.remove('hidden');
                return;
            }

            try {
                // Submit the review data to the API
                const res  = await fetch('../api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action:    'addReview',
                        productId: <?= $pId ?>,
                        rating:    rating,
                        comment:   comment
                    })
                });
                const data = await res.json();

                if (data.status) {
                    closeReviewModal();
                    location.reload();
                } else {
                    errEl.textContent = data.message || 'Something went wrong.';
                    errEl.classList.remove('hidden');
                }
            } catch (err) {
                errEl.textContent = 'Connection error. Please try again.';
                errEl.classList.remove('hidden');
            }
        }
    </script>
</body>

</html>
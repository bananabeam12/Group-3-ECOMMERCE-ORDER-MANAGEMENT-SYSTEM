<?php require_once __DIR__ . "../includes/config.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skrrt | Streetwear</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/vendor/fontawesome/css/all.min.css">
</head>

<body>
    <!-- NAVIGATION -->
    <nav id="navbar" class="fixed w-full z-50 top-0 start-0 transition-all duration-500 ease-in-out py-6 text-white">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto px-10">
            <a href="customer.php" class="flex items-center">
                <span id="nav-logo" class="transition-all duration-500">
                    <img id="logo-img" src="assets/images/Skrrt_logo-Alt.png" alt="Logo"
                        class="h-10 w-auto object-contain">
                </span>
            </a>

            <div class="items-center justify-between hidden w-full md:flex md:w-auto">
                <ul id="nav-menu"
                    class="flex flex-col p-4 md:p-0 mt-4 font-semibold md:space-x-10 md:flex-row md:mt-0 text-[14px] tracking-wide uppercase transition-colors duration-500">
                    <li><a href="pages/shop.php" class="hover:opacity-60">Shop</a></li>
                    <li><a href="pages/collections.php" class="hover:opacity-60">Collections</a></li>
                    <li><a href="pages/about.php" class="hover:opacity-60">About</a></li>
                    <li><a href="pages/contact.php" class="hover:opacity-60">Contact Us</a></li>
                </ul>
            </div>

            <div id="nav-icons" class="flex items-center space-x-6 text-sm transition-colors duration-500">
                <a href="pages/cart.php" class="relative hover:opacity-60 transition-opacity">
                    <div class="indicator">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span id="cart-indicator" class="bg-white text-black font-semibold px-1 min-w-[10px]">
                            0
                        </span>
                    </div>
                </a>

                <a href="pages/profile.php" class="relative hover:opacity-60 transition-opacity">
                    <i class="fa-regular fa-user"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- HERO CAROUSEL -->
    <div id="hero-carousel" class="carousel w-full h-screen overflow-x-hidden flex flex-row">
        <div id="slide1" class="carousel-item relative w-full h-full flex-shrink-0">
            <img src="assets/images/hero_carousel_1.jpg" class="w-full h-full object-cover" alt="Skrrt 1">
            <div class="absolute inset-0 bg-black/20"></div>
        </div>

        <div id="slide2" class="carousel-item relative w-full h-full flex-shrink-0">
            <img src="assets/images/hero_carousel_2.jpg" class="w-full h-full object-cover" alt="Skrrt 2">
            <div class="absolute inset-0 bg-black/20"></div>
        </div>

        <div id="slide3" class="carousel-item relative w-full h-full flex-shrink-0">
            <img src="assets/images/hero_carousel_3.jpg" class="w-full h-full object-cover" alt="Skrrt 3">
            <div class="absolute inset-0 bg-black/20"></div>
        </div>

        <div id="slide4" class="carousel-item relative w-full h-full flex-shrink-0">
            <img src="assets/images/hero_carousel_4.jpg" class="w-full h-full object-cover" alt="Skrrt 4">
            <div class="absolute inset-0 bg-black/20"></div>
        </div>
    </div>

    <!-- MARQUEE SCROLL DIVIDER ANIMATION -->
    <div class="absolute z-30 flex -translate-x-1/2 bottom-10 left-1/2 space-x-3">
        <div class="indicator-dot w-2 h-2 rounded-full bg-white opacity-100 transition-all duration-500"></div>
        <div class="indicator-dot w-2 h-2 rounded-full bg-white opacity-40 transition-all duration-500"></div>
        <div class="indicator-dot w-2 h-2 rounded-full bg-white opacity-40 transition-all duration-500"></div>
        <div class="indicator-dot w-2 h-2 rounded-full bg-white opacity-40 transition-all duration-500"></div>
    </div>

    <!-- PRDUCT SECTIONS -->
    <section class="bg-white py-20 px-4 md:px-10">
        <div class="max-w-screen-xl mx-auto text-center">
            <h2 class="text-3xl font-semibold text-black uppercase tracking-wide mb-12">New Releases</h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-x-12 md:gap-y-16">
                <?php
                $sql = "SELECT p.product_id, p.product_name, p.price, pi.image_url
            FROM products p
            LEFT JOIN product_images pi ON p.product_image_id = pi.product_image_id
            WHERE p.status = 'active'
            ORDER BY p.product_id DESC";

                $result = $conn->query($sql);

                while ($product = $result->fetch_assoc()):
                    $image = !empty($product['image_url'])
                        ? $product['image_url']
                        : 'assets/images/placeholder_tee.png';
                    ?>

                    <a href="pages/product_details.php?id=<?php echo $product['product_id']; ?>"
                        class="block product-card group text-center cursor-pointer">

                        <div class="relative aspect-square w-full mb-6 overflow-hidden">
                            <img src="<?php echo htmlspecialchars($image); ?>"
                                alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                                onerror="this.src='assets/images/placeholder_tee.png'"
                                class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300 ease-in-out">
                        </div>

                        <p class="text-lg font-semibold text-black uppercase tracking-normal mb-2">
                            <?php echo htmlspecialchars($product['product_name']); ?>
                        </p>
                        <p class="text-base font-semibold text-black opacity-80">
                            ₱<?php echo number_format($product['price'], 2); ?>
                        </p>

                    </a>

                <?php endwhile; ?>
            </div>

            <div class="mt-20">
                <a href="pages/shop.php"
                    class="inline-block bg-[#2C2C2C] text-white px-12 py-4 rounded-none text-xs capitalize tracking-widest font-bold hover:scale-105 transition-all">
                    Browse Shop
                </a>
            </div>
        </div>
    </section>

    <!-- MARQUEE SCROLL DIVIDER ANIMATION -->
    <section class="relative w-full h-[250px] bg-white overflow-hidden py-10">
        <div
            class="absolute top-1/2 left-[-10%] w-[120%] bg-[#A6F000] py-4  rotate-[-5deg] shadow-lg z-10 overflow-hidden flex items-center">
            <div class="flex flex-nowrap items-center whitespace-nowrap animate-marquee w-max">
                <div class="flex items-center flex-shrink-0">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">FOR THE DREAMERS</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">THE DOERS AND THE
                        DRIFTERS</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">GEAR FOR THE GRIND</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">SKRRRRRRT</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">ALWAYS ON THE MOVE</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">KEEP UP OR GET LEFT
                        BEHIND</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                </div>

                <div class="flex items-center flex-shrink-0">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">FOR THE DREAMERS</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">THE DOERS AND THE
                        DRIFTERS</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">GEAR FOR THE GRIND</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">SKRRRRRRT</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">ALWAYS ON THE MOVE</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">KEEP UP OR GET LEFT
                        BEHIND</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                </div>
            </div>

        </div>
        <div
            class="absolute top-1/2 left-[-10%] w-[120%] bg-[#A6F000] py-4 rotate-[4deg] shadow-lg z-10 overflow-hidden">
            <div class="flex flex-nowrap items-center whitespace-nowrap animate-marquee-reverse w-max">
                <div class="flex items-center flex-shrink-0">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">FOR THE DREAMERS</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">THE DOERS AND THE
                        DRIFTERS</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">GEAR FOR THE GRIND</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">SKRRRRRRT</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">ALWAYS ON THE MOVE</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">KEEP UP OR GET LEFT
                        BEHIND</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                </div>

                <div class="flex items-center">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">FOR THE DREAMERS</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">THE DOERS AND THE
                        DRIFTERS</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">GEAR FOR THE GRIND</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">SKRRRRRRT</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">ALWAYS ON THE MOVE</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                    <span class="text-black font-bold uppercase text-sm tracking-wider px-4">KEEP UP OR GET LEFT
                        BEHIND</span>
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="h-6 w-auto inline-block mx-2">
                </div>
            </div>
        </div>
    </section>
    <section class="bg-white py-12 px-4 md:px-10 mt-20">
        <div class="max-w-screen-xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 auto-rows-[350px]">

                <a href="shop.php?collection=better-days" class="group relative block overflow-hidden bg-gray-100">
                    <img src="assets/images/hero_carousel_3.jpg" alt="two man in a photo"
                        class="w-full h-full object-cover">

                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent flex items-end p-8">
                        <h3 class="text-white text-2xl font-bold uppercase tracking-widest">All Collections</h3>
                    </div>

                    <div
                        class="absolute inset-0 bg-black/70 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <span
                            class="text-white text-xl font-semibold capitalize tracking-widest border-b-2 border-white pb-1">
                            Browse Collection
                        </span>
                    </div>
                </a>

                <a href="shop.php?collection=josh" class="group relative block overflow-hidden bg-gray-100">
                    <img src="assets/images/collection_thumbnail_2.jpg" alt="sunglasses"
                        class="w-full h-full object-cover">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent flex items-end p-8">
                        <h3 class="text-white text-2xl font-bold uppercase tracking-widest">Accessories</h3>
                    </div>
                    <div
                        class="absolute inset-0 bg-black/70 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <span
                            class="text-white text-xl font-semibold capitalize tracking-widest border-b-2 border-white pb-1">
                            Browse Collection
                        </span>
                    </div>
                </a>

                <a href="shop.php?collection=ordinary"
                    class="group relative block overflow-hidden bg-gray-100 md:col-span-2 h-[400px]">
                    <img src="assets/images/collection_thumbnail_3.jpg" alt="Ordinary Vol 1"
                        class="w-full h-full object-cover">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-black/60 via-transparent to-transparent flex items-end p-10">
                        <h3 class="text-white text-3xl font-bold uppercase tracking-widest">T-Shirts</h3>
                    </div>
                    <div
                        class="absolute inset-0 bg-black/70 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <span
                            class="text-white text-xl font-semibold capitalize tracking-widest border-b-2 border-white pb-1">
                            Browse Collection
                        </span>
                    </div>
                </a>

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
            <img src="assets/images/Skrrt_logo-Half.svg" alt="Logo"
                class="w-full h-auto object-contain object-bottom select-none">
        </div>
    </footer>
    <!--for navscroll effect -->
    <script src="assets/js/navScroll.js"></script>
    <!-- for cart indicator update -->
    <script src="assets/js/cart.js"></script> 
    <script>
        let currentSlide = 1;
        const totalSlides = 4; // Update this if you add more banners
        const dots = document.querySelectorAll('.indicator-dot');

        function autoSlide() {
            const container = document.getElementById('hero-carousel');
            const target = document.getElementById(`slide${currentSlide}`);

            // Calculate the exact position based on width
            const scrollAmount = container.clientWidth * (currentSlide - 1);

            container.scrollTo({
                left: scrollAmount,
                behavior: 'smooth'
            });

            // Update dots
            dots.forEach((dot, index) => {
                dot.style.opacity = (index + 1 === currentSlide) ? "1" : "0.4";
            });

            // Increment or reset
            if (currentSlide >= totalSlides) {
                currentSlide = 1;
            } else {
                currentSlide++;
            }
        }

        // Set the interval (5000ms = 5 seconds)
        setInterval(autoSlide, 5000);

    </script>
</body>

</html>
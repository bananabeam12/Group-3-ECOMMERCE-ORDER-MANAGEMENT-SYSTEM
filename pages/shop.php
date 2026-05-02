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
            <a href="#" class="flex items-center">
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
                        <span id="cart-indicator"
                            class="indicator-item badge badge-sm bg-white text-black font-semibold">0</span>
                    </div>
                </a>

                <button type="button" class="hover:opacity-60 transition-opacity">
                    <i class="fa-regular fa-user"></i>
                </button>
            </div>
    </nav>

    

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
            <img src="../assets/images/Skrrt_logo-Half.svg" alt="Logo"
                class="w-full h-auto object-contain object-bottom select-none">
        </div>
    </footer>
    <script src="../assets/js/navScroll.js"></script>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skrrt | Sign Up</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/vendor/fontawesome/css/all.min.css">
</head>

<body class="bg-white text-black">
    <nav id="navbar" class="fixed w-full z-50 top-0 start-0 transition-all duration-500 ease-in-out py-6 text-black bg-white shadow-sm">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto px-6 md:px-10">
            <a href="profile.php" class="flex items-center">
                <span id="nav-logo" class="transition-all duration-500">
                    <img id="logo-img" src="assets/images/Skrrt_logo-Alt.png" alt="Logo" class="h-10 w-auto object-contain">
                </span>
            </a>

            <div class="items-center justify-between hidden w-full md:flex md:w-auto">
                <ul id="nav-menu" class="flex flex-col p-4 md:p-0 mt-4 font-semibold md:space-x-10 md:flex-row md:mt-0 text-[14px] tracking-wide uppercase transition-colors duration-500">
                    <li><a href="pages/shop.php" class="hover:opacity-60">Shop</a></li>
                    <li><a href="#" class="hover:opacity-60">Collections</a></li>
                    <li><a href="#" class="hover:opacity-60">About</a></li>
                    <li><a href="#" class="hover:opacity-60">Contact Us</a></li>
                </ul>
            </div>

            <div id="nav-icons" class="flex items-center space-x-6 text-sm transition-colors duration-500">
                <button type="button" class="hover:opacity-60 transition-opacity">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>

                <a href="pages/cart.php" class="relative hover:opacity-60 transition-opacity">
                    <div class="indicator">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span id="cart-indicator" class="indicator-item badge badge-sm bg-black text-white px-1 rounded-full text-[10px]">0</span>
                    </div>
                </a>

                <a href="profile.php" class="hover:opacity-60 transition-opacity">
                    <i class="fa-regular fa-user"></i>
                </a>
            </div>
        </div>
    </nav>

    <main class="min-h-screen flex items-center justify-center pt-20">
        <div class="w-full max-w-4xl px-6 text-center">
            <h1 class="text-4xl font-bold mb-10">Register Your Account</h1>

            <?php if ($dbError !== ''): ?>
                <div class="mb-6 bg-red-100 text-red-700 px-4 py-3 font-semibold">
                    <?php echo htmlspecialchars($dbError); ?>
                </div>
            <?php endif; ?>

            <?php if ($successMessage !== ''): ?>
                <div class="mb-6 bg-[#A6F000] text-black px-4 py-3 font-semibold">
                    <?php echo htmlspecialchars($successMessage); ?>
                </div>
            <?php endif; ?>

            <form action="signup.php" method="POST" class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-400 mb-2 ml-1">First Name</label>
                        <input type="text" id="first_name" name="first_name"
                            value="<?php echo htmlspecialchars($formData['first_name']); ?>"
                            class="w-full border border-gray-200 p-4 focus:outline-none focus:border-black transition-colors">
                        <?php if (isset($errors['first_name'])): ?>
                            <p class="text-[12px] text-red-600 mt-2"><?php echo htmlspecialchars($errors['first_name']); ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-400 mb-2 ml-1">Last Name</label>
                        <input type="text" id="last_name" name="last_name"
                            value="<?php echo htmlspecialchars($formData['last_name']); ?>"
                            class="w-full border border-gray-200 p-4 focus:outline-none focus:border-black transition-colors">
                        <?php if (isset($errors['last_name'])): ?>
                            <p class="text-[12px] text-red-600 mt-2"><?php echo htmlspecialchars($errors['last_name']); ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-400 mb-2 ml-1">Email</label>
                        <input type="email" id="email" name="email"
                            value="<?php echo htmlspecialchars($formData['email']); ?>"
                            class="w-full border border-gray-200 p-4 focus:outline-none focus:border-black transition-colors">
                        <?php if (isset($errors['email'])): ?>
                            <p class="text-[12px] text-red-600 mt-2"><?php echo htmlspecialchars($errors['email']); ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-400 mb-2 ml-1">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password"
                                value="<?php echo htmlspecialchars($formData['password']); ?>"
                                class="w-full border border-gray-200 p-4 pr-12 focus:outline-none focus:border-black transition-colors">
                            <button type="button" id="togglePassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-black/60 hover:text-black">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        <?php if (isset($errors['password'])): ?>
                            <p class="text-[12px] text-red-600 mt-2"><?php echo htmlspecialchars($errors['password']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="flex flex-col items-center gap-4 pt-4">
                    <button type="submit" class="bg-black text-white px-12 py-4 text-sm font-bold uppercase tracking-widest hover:bg-gray-800 transition-all">
                        Submit
                    </button>
                    <a href="profile.php" class="text-sm font-medium hover:underline">Cancel</a>
                </div>
            </form>

            <div class="mt-8 space-y-2">
                <div>
                    <a href="profile.php" class="text-sm font-medium hover:underline">Back to Login</a>
                </div>
                <div>
                    <a href="forgot_password.php" class="text-sm font-medium hover:underline">Recover Password</a>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-[#A6F000] pt-20 pb-0 px-4 md:px-20 text-black relative overflow-hidden">
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
                        <li><a href="#" class="hover:underline">Collections</a></li>
                        <li><a href="#" class="hover:underline">New Releases</a></li>
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
            <img src="assets/images/Skrrt_logo-Half.svg" alt="Logo" class="w-full h-auto object-contain object-bottom select-none">
        </div>
    </footer>

    <script src="assets/js/navScroll.js"></script>
    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        if (passwordInput && togglePassword) {
            togglePassword.addEventListener('click', function() {
                const isHidden = passwordInput.type === 'password';
                passwordInput.type = isHidden ? 'text' : 'password';
                togglePassword.innerHTML = isHidden
                    ? '<i class="fa-regular fa-eye-slash"></i>'
                    : '<i class="fa-regular fa-eye"></i>';
            });
        }
    </script>
</body>

</html>

<!DOCTYPE html>
<html lang="en">
<!-- 404 Page -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skrrt | 404</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/vendor/fontawesome/css/all.min.css">
</head>

<body class="bg-white min-h-screen flex flex-col overflow-hidden">

    <!-- NAVIGATION -->
    <nav class="w-full py-5 px-10">
        <a href="../customer.php">
            <img src="../assets/images/Skrrt_logo-Alt.png" alt="Logo" class="h-9 w-auto object-contain invert">
        </a>
    </nav>

    <main class="flex-1 flex flex-col items-center justify-center text-center px-6 relative">
        <p class="absolute text-[28vw] font-black text-black/10 leading-none select-none pointer-events-none top-1/2 -translate-y-1/2 tracking-tighter">
            404
        </p>

        <!-- Content -->
        <div class="relative z-10 flex flex-col items-center gap-6">

            <div class="flex items-center gap-3 bg-black text-[#A6F000] px-5 py-2 rounded-full ">
                <span class="w-2 h-2 rounded-full bg-[#A6F000] animate-pulse"></span>
                <span class="text-[11px] font-bold uppercase tracking-[0.25em] animate-pulse">Still Under Construction</span>
            </div>

            <h1 class="text-5xl md:text-7xl font-black uppercase tracking-tighter leading-none text-black">
                Page Not<br>Found
            </h1>

            <p class="text-sm font-semibold text-black/60 max-w-sm leading-relaxed">
                This page is either broken or we're still building it. Check back soon — good things take time.
            </p>

            <div class="flex flex-col sm:flex-row items-center gap-3 mt-4">
                <a href="../customer.php"
                    class="bg-black text-white px-8 py-4 text-xs font-black uppercase tracking-widest hover:scale-105 transition-transform rounded-none">
                    Go Home
                </a>
                <a href="pagesshop.php"
                    class="border-2 border-black text-black px-8 py-4 text-xs font-black uppercase tracking-widest hover:bg-black hover:text-white transition-all rounded-none">
                    Browse Shop
                </a>
            </div>

        </div>

    </main>

    <!-- Footer -->
    <div class="w-full overflow-hidden pointer-events-none select-none mt-auto">
        <img src="../assets/images/Skrrt_logo-Half.svg" alt=""
            class="w-full h-auto object-contain object-bottom opacity-20">
    </div>

</body>

</html>
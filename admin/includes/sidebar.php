<aside id="sidebar" class="fixed top-0 left-0 h-screen w-[260px] bg-[#111] text-white z-50 transition-transform duration-300 -translate-x-full shadow-2xl">
        <div class="bg-white h-20 flex items-center justify-between px-4">
            <div class="flex-1 flex justify-center">
                <img src="../assets/images/main_logo.svg" class="h-10" alt="SKRRT Logo">
            </div>
            <button onclick="toggleSidebar()" class="btn btn-ghost btn-sm btn-circle text-black">
                ✕
            </button>
        </div>
        <div class="p-4">
            <h2 class="text-center font-bold text-sm my-4 border-b border-gray-700 pb-4">Admin Dashboard</h2>
            <ul class="menu w-full p-0 gap-2">
               <form method="post" action="">
                 <li><a href="dashboard.php" class="hover:bg-gray-800 hover:text-[#00ff41] justify-center transition-colors py-3">Dashboard</a></li>
                 <li><a href="inventory.php" class="hover:bg-gray-800 hover:text-[#00ff41] justify-center transition-colors py-3">Inventory</a></li>
                 <li><a href="admin_orders.php" class="hover:bg-gray-800 hover:text-[#00ff41] justify-center transition-colors py-3">Orders</a></li>
                 <li><a href="manage_reviews.php" class="hover:bg-gray-800 hover:text-[#00ff41] justify-center transition-colors py-3">Reviews</a></li>
                 <li><a href="logout.php" id="logoutButton" name="logoutButton" class="hover:bg-gray-800 hover:text-[#00ff41] justify-center transition-colors py-3">Log-Out</a></li>
               </form>
                
            </ul>
        </div>
    </aside>
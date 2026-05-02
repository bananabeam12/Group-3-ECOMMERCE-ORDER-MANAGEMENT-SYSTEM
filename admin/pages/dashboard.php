<?php
session_start();
// Security: Kick out if not logged in OR if they are a customer
if (empty($_SESSION['user_id']) || empty($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

include('../includes/header.php');
include('../includes/sidebar.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SKRRT WORLDWIDE</title>
    
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" /> 
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> 
    
    <!-- Include jsPDF Library via CDN for PDF generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body class="bg-base-200 min-h-screen relative">

<div class="admin-layout">

    <main class="main-content p-4 sm:p-6 md:p-8 w-full overflow-x-hidden">
    
        <header class="mb-8">
          <h1 id="welcome-message" class="text-center text-xl sm:text-2xl font-bold">WELCOME, ADMIN</h1>
          <h1 class="text-center text-sm sm:text-md font-bold text-gray-500 uppercase tracking-widest mt-1">System Overview</h1>
        </header>
   
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 md:gap-6 w-full h-auto"> 
                <div class="stat-card p-6 h-32 flex flex-col justify-center bg-base-100 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-xs sm:text-sm font-semibold text-gray-500 uppercase">Total Revenue</p>
                    <b id="total-revenue" class="text-xl sm:text-2xl">0</b>
                </div>
        
                <div class="stat-card p-6 h-32 flex flex-col justify-center bg-base-100 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-xs sm:text-sm font-semibold text-gray-500 uppercase">Order Processed</p>
                    <b id="total-orders" class="text-xl sm:text-2xl text-blue-600">0</b>
                </div>

                <div class="stat-card p-6 h-32 flex flex-col justify-center bg-base-100 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-xs sm:text-sm font-semibold text-gray-500 uppercase">Registered Users</p>
                    <b id="total-users" class="text-xl sm:text-2xl text-blue-600">0</b>
                </div>

                <div class="stat-card p-6 h-32 flex flex-col justify-center bg-base-100 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-xs sm:text-sm font-semibold text-gray-500 uppercase">Total Products</p>
                    <b id="total-products" class="text-xl sm:text-2xl text-blue-600">0</b>
                </div>

                <div class="stat-card p-6 h-32 flex flex-col justify-center bg-base-100 border border-red-100 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-xs sm:text-sm font-semibold text-red-500 uppercase">Low Stock Alert</p>
                    <b id="low-stock" class="text-xl sm:text-2xl text-red-600">0</b>
                </div>
         </section>
            
        <!-- Analytics Charts Section -->
        <h2 class="mt-8 text-md sm:text-lg font-bold mb-4 text-gray-700 tracking-wide uppercase">Sales Analytics</h2>
        
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 w-full mb-8">
            <div class="bg-base-100 p-6 rounded-xl shadow-sm border border-base-300 lg:col-span-2">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">Top 5 Selling Products</h3>
                <div class="relative h-72 w-full bg-white">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
            
            <div class="bg-base-100 p-6 rounded-xl shadow-sm border border-base-300">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">Order Status Distribution</h3>
                <div class="relative h-72 w-full flex justify-center bg-white">
                    <canvas id="orderStatusChart"></canvas>
                </div>
            </div>

            <div class="bg-base-100 p-6 rounded-xl shadow-sm border border-base-300 lg:col-span-3">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">Revenue by Category</h3>
                <div class="relative h-80 w-full flex justify-center bg-white">
                    <canvas id="categoryRevenueChart"></canvas>
                </div>
            </div>
        </section>

        <!-- Registry Header with Print Button -->
        <div class="flex justify-between items-center mt-5 mb-4">
            <h2 class="text-md sm:text-lg font-bold text-gray-700 tracking-wide uppercase">USER REGISTRY</h2>
            
            <!-- Generate PDF Button -->
            <button onclick="generatePDF()" class="btn btn-primary text-white shadow-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.728 15H5.25A2.25 2.25 0 013 12.75V8.25A2.25 2.25 0 015.25 6h13.5A2.25 2.25 0 0121 8.25v4.5A2.25 2.25 0 0118.75 15h-1.472M6.728 15L6 21h12l-.728-6m-10.5 0h9" />
                </svg>
                Print Data
            </button>
        </div>

        <!-- Search Bar -->
        <label class="input input-bordered mb-5 flex items-center gap-3 w-full max-w-md bg-white border focus-within:border-none focus-within:ring-1 focus-within:ring-none rounded-lg shadow-sm transition-all duration-200">
            <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </g>
            </svg>
            <input type="search" required placeholder="Search" class="grow text-black placeholder-gray-400 text-sm font-medium bg-transparent border-none focus:outline-none" id="searchBar"/>
        </label>

        <section class="w-full bg-base-100 rounded-xl shadow-sm border border-base-300 overflow-hidden">
            <div class="overflow-x-auto w-full">
                <table class="table table-zebra w-full text-xs sm:text-sm">
                    <thead class="bg-base-200/50 text-base-content/70">
                        <tr>
                            <th class="py-4 font-bold uppercase text-xs whitespace-nowrap">Customer ID</th>
                            <th class="py-4 font-bold uppercase text-xs whitespace-nowrap">Customer Name</th>
                            <th class="py-4 font-bold uppercase text-xs whitespace-nowrap">Email Address</th>
                            <th class="py-4 font-bold uppercase text-xs whitespace-nowrap">User Role</th>
                            <th class="py-4 font-bold uppercase text-xs whitespace-nowrap">Address</th>
                            <th class="py-4 font-bold uppercase text-xs whitespace-nowrap">City</th>
                            <th class="py-4 font-bold uppercase text-xs whitespace-nowrap">Province</th>
                            <th class="py-4 font-bold uppercase text-xs whitespace-nowrap">Zip Code</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <tr>
                            <td colspan="8" class="text-center py-10 text-gray-500 font-medium">Loading registry...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/js/dashboard.js"></script>
<script>
    let typingTimer;
    const searchBar = document.getElementById('searchBar');
    const tableBody = document.getElementById('tbody');

    document.addEventListener('DOMContentLoaded', function() {
        // Fetch Sales Summary on Load
        fetch('../../api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: "adminFetchSalesSummary" })
        })
        .then(response => {
            if (!response.ok) throw new Error("Network error");
            return response.json();
        })
        .then(data => {
            if (data.status) {
                document.getElementById('total-revenue').innerText = "₱" + parseFloat(data.revenue || 0).toLocaleString();
                document.getElementById('total-orders').innerText = data.orderCount || 0;
                document.getElementById('total-users').innerText = data.userCount || 0;
                document.getElementById('total-products').innerText = data.productCount || 0;
                document.getElementById('low-stock').innerText = data.lowStockCount || 0;
            }
        })
        .catch(error => {
            console.error("Analytics load failed:", error);
            document.getElementById('total-revenue').innerText = "ERROR";
        });
        
        loadAnalyticsCharts();
        searchUser(""); 
    });
      
    if(searchBar){
        searchBar.addEventListener('input', function() {
            clearTimeout(typingTimer);
            const searchTerm = this.value.trim();
            typingTimer = setTimeout(() => {
                searchUser(searchTerm);
            }, 300);
        });
    }
    
    async function searchUser(searchTerm){
        try {
            const request = await fetch('../../api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: "searchUsers", keyword: searchTerm })
            });

            if (!request.ok) throw new Error("Network error");
            const data = await request.json();
            
            if(data.status && data.users) {
                renderUsers(data.users);
            } else {
                 renderUsers([]); 
            }
        } catch (error) {
            console.error("Search failed:", error);
            tableBody.innerHTML = `<tr><td colspan="8" class="text-center py-10 text-red-500 font-medium">Failed to load registry.</td></tr>`;
        }
    }

    function renderUsers(users) {
        let rows = "";
        if (!users || users.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="8" class="text-center py-10 text-gray-500 font-medium">No users found.</td></tr>`;
            return;
        }
       
        for(let i = 0; i < users.length; i++){
            const column = users[i];
            rows += `<tr>
                        <td>${column.user_id}</td>
                        <td class="font-bold">${column.first_name} ${column.last_name}</td>
                        <td>${column.email}</td>
                        <td>
                            <span class="badge ${column.user_role === 'admin' ? 'badge-neutral' : 'badge-primary'} badge-sm uppercase font-bold tracking-widest text-[9px]">
                                ${column.user_role}
                            </span>
                        </td>
                        <td class="text-xs text-gray-500 max-w-[150px] truncate" title="${column.address_description || ''}">${column.address_description || "N/A"}</td>
                        <td>${column.city || "N/A"}</td>
                        <td>${column.province || "N/A"}</td>
                        <td>${column.zip_code || "N/A"}</td>
                    </tr>`;
        }
        tableBody.innerHTML = rows;
    }

    function logout() {
        fetch('../../api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: "logout" })
        })
        .then(res => res.json())
        .then(() => {
            localStorage.clear();
            window.location.href = 'logout.php';
        })
        .catch(() => {
            localStorage.clear();
            window.location.href = 'logout.php';
        });
    }

    async function loadAnalyticsCharts() {
        try {
            const response = await fetch('../../api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: "fetchSalesAnalytics" })
            });

            if (!response.ok) throw new Error("Network response was not ok");
            const parsedData = await response.json();

            if (parsedData.status === true && parsedData.data) {
                const chartData = parsedData.data;

                const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
                new Chart(topProductsCtx, {
                    type: 'bar',
                    data: {
                        labels: chartData.topProducts.labels,
                        datasets: [{
                            label: 'Total Units Sold',
                            data: chartData.topProducts.data,
                            backgroundColor: 'rgba(59, 130, 246, 0.7)', 
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
                });

                const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
                new Chart(orderStatusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: chartData.orderStatus.labels,
                        datasets: [{
                            data: chartData.orderStatus.data,
                            backgroundColor: ['#F59E0B', '#10B981', '#EF4444', '#3B82F6', '#6B7280'],
                            borderWidth: 0
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });

                const categoryRevenueCtx = document.getElementById('categoryRevenueChart').getContext('2d');
                new Chart(categoryRevenueCtx, {
                    type: 'polarArea',
                    data: {
                        labels: chartData.categoryRevenue.labels,
                        datasets: [{
                            label: 'Revenue Generated (₱)',
                            data: chartData.categoryRevenue.data,
                            backgroundColor: [
                                'rgba(16, 185, 129, 0.6)', 'rgba(59, 130, 246, 0.6)', 
                                'rgba(245, 158, 11, 0.6)', 'rgba(239, 68, 68, 0.6)', 'rgba(139, 92, 246, 0.6)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }
        } catch (error) {
            console.error("Failed to load chart analytics:", error);
        }
    }

    /**
     * Generates a PDF summary of the Dashboard using jsPDF.
     * Extracts text statistics and converts Chart.js canvases into images.
     */
    function generatePDF() {
        // Initialize jsPDF object (Portrait, millimeters, A4 page size)
        const { jsPDF } = window.jspdf;
        const documentPdf = new jsPDF('p', 'mm', 'a4');
        const pageWidth = documentPdf.internal.pageSize.getWidth();

        // 1. Construct Header Section
        documentPdf.setFont("helvetica", "bold");
        documentPdf.setFontSize(22);
        documentPdf.text("SKRRT WORLDWIDE", pageWidth / 2, 20, { align: "center" });
        
        documentPdf.setFont("helvetica", "normal");
        documentPdf.setFontSize(12);
        documentPdf.setTextColor(100);
        documentPdf.text("Sales & System Overview Report", pageWidth / 2, 28, { align: "center" });

        // Add a horizontal divider line
        documentPdf.setLineWidth(0.5);
        documentPdf.setDrawColor(200);
        documentPdf.line(15, 34, pageWidth - 15, 34);

        // 2. Fetch and Render Text Statistics
        documentPdf.setTextColor(0);
        documentPdf.setFont("helvetica", "bold");
        documentPdf.setFontSize(14);
        documentPdf.text("System Summary", 15, 45);

        // Retrieve raw text values directly from the DOM elements
        const revenueText = document.getElementById('total-revenue').innerText;
        const ordersText = document.getElementById('total-orders').innerText;
        const usersText = document.getElementById('total-users').innerText;
        const productsText = document.getElementById('total-products').innerText;
        const lowStockText = document.getElementById('low-stock').innerText;

        documentPdf.setFont("helvetica", "normal");
        documentPdf.setFontSize(11);

        // Arrange stats in two columns for professional layout
        // Column 1
        documentPdf.text(`Total Revenue: ${revenueText}`, 15, 55);
        documentPdf.text(`Orders Processed: ${ordersText}`, 15, 63);
        documentPdf.text(`Registered Users: ${usersText}`, 15, 71);
        
        // Column 2
        documentPdf.text(`Total Products: ${productsText}`, pageWidth / 2, 55);
        documentPdf.text(`Low Stock Alert: ${lowStockText}`, pageWidth / 2, 63);

        // 3. Render Chart Images
        documentPdf.setFont("helvetica", "bold");
        documentPdf.setFontSize(14);
        documentPdf.text("Graphical Analytics", 15, 90);

        try {
            // Convert existing Canvas objects to Base64 PNG images
            // We use image/png with 1.0 quality to preserve chart crispness
            const barChartData = document.getElementById('topProductsChart').toDataURL("image/png", 1.0);
            const doughnutChartData = document.getElementById('orderStatusChart').toDataURL("image/png", 1.0);
            const polarChartData = document.getElementById('categoryRevenueChart').toDataURL("image/png", 1.0);

            // Add Top Products Bar Chart (Spans horizontally)
            documentPdf.setFontSize(10);
            documentPdf.setFont("helvetica", "normal");
            documentPdf.text("Top 5 Selling Products", 15, 100);
            documentPdf.addImage(barChartData, 'PNG', 15, 105, 180, 65);

            // Add Order Status Doughnut Chart (Bottom Left)
            documentPdf.text("Order Status Distribution", 15, 185);
            documentPdf.addImage(doughnutChartData, 'PNG', 15, 190, 80, 80);

            // Add Category Revenue Polar Chart (Bottom Right)
            documentPdf.text("Revenue by Category", 110, 185);
            documentPdf.addImage(polarChartData, 'PNG', 110, 190, 80, 80);

        } catch (error) {
            console.error("Chart rendering error:", error);
            documentPdf.text("Unable to load graphical data into PDF.", 15, 100);
        }

        // 4. Add Timestamp Footer
        const dateString = new Date().toLocaleString();
        documentPdf.setFontSize(8);
        documentPdf.setTextColor(150);
        documentPdf.text(`Report generated automatically on: ${dateString}`, 15, 285);

        // 5. Trigger File Download
        documentPdf.save("SKRRT_Worldwide_Summary.pdf");
    }
</script>
<script src="../assets/js/stylingAdmin.js"></script>
</body>
</html>
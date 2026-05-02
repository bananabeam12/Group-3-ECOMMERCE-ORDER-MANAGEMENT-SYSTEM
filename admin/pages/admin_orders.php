<?php
session_start();

if (empty($_SESSION['user_id']) || empty($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

include('../includes/header.php');
include('../includes/sidebar.php');

?>
<!DOCTYPE html>
<html lang="en" data-theme="cupcake">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management - SKRRT WORLDWIDE</title>
    
     <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" /> 
     <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> 
</head>

<body class="bg-base-200 min-h-screen relative">
  
    <div class="flex flex-col md:flex-row w-full min-h-screen">
       
        <main class="flex-1 p-4 sm:p-6 md:p-8 w-full max-w-7xl mx-auto overflow-x-hidden">
            <header class="mb-8">
                <h1 class="text-center text-xl sm:text-2xl font-bold uppercase tracking-widest">ORDER LOGISTICS</h1>
                <h1 class="text-center text-sm font-bold text-gray-500 uppercase tracking-widest mt-1">Track and manage global shipments</h1>
            </header>

            <section class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6 mb-8 w-full h-auto">
                <div class="stat-card p-6 h-32 flex flex-col justify-center bg-base-100 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-xs sm:text-sm font-semibold text-gray-500 uppercase tracking-widest">Total Revenue</p>
                    <b id="total-revenue" class="text-xl sm:text-2xl font-black text-black">₱0.00</b>
                </div>

                <div class="stat-card p-6 h-32 flex flex-col justify-center bg-base-100 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-xs sm:text-sm font-semibold text-gray-500 uppercase tracking-widest">Active Shipments</p>
                    <b id="total-orders" class="text-xl sm:text-2xl font-black text-blue-600">0</b>
                </div>
            </section>

            <h2 class="mt-5 text-md sm:text-lg font-bold mb-4 text-black uppercase tracking-widest">ORDER DIRECTORY</h2>

            <section class="w-full bg-base-100 rounded-xl shadow-sm border border-base-300 overflow-hidden">
                <div class="overflow-x-auto w-full">
                    <table class="table table-zebra w-full text-xs sm:text-sm">
                        <thead class="bg-base-200/50 text-base-content/70">
                            <tr>
                                <th class="py-4 font-bold uppercase text-xs whitespace-nowrap">Order ID</th>
                                <th class="py-4 font-bold uppercase text-xs whitespace-nowrap">User ID</th>
                                <th class="py-4 font-bold uppercase text-xs whitespace-nowrap">Status</th>
                                <th class="py-4 font-bold uppercase text-xs whitespace-nowrap">Shipping Address</th>
                                <th class="py-4 font-bold uppercase text-xs whitespace-nowrap">Shipping City</th>
                                <th class="py-4 font-bold uppercase text-xs whitespace-nowrap">Shipping Country</th>
                                <th class="py-4 font-bold uppercase text-xs whitespace-nowrap">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody id="adminOrderTable">
                            <tr>
                                <td class="px-4 py-10 text-center text-gray-500 font-medium" colspan="7">
                                    <span class="loading loading-spinner loading-md block mx-auto mb-2"></span>
                                    Accessing order history...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            loadAnalytics();
            loadAdminOrders();
        });

        function loadAnalytics() {
            fetch('../../api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action: "adminFetchSalesSummary"
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        document.getElementById('total-revenue').innerText = "₱" + parseFloat(data.revenue || 0).toLocaleString();
                        document.getElementById('total-orders').innerText = data.orderCount || 0;
                    }
                });
        }

        async function loadAdminOrders() {
    const adminOrderTable = document.getElementById('adminOrderTable');
    const statusOptions = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled', 'refunded'];

    try {
        const response = await fetch('../../api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: "fetchAllOrders" })
        });

        if (!response.ok) throw new Error("Network response was not ok");

        let data = await response.json();

        if (data.status) {
            adminOrderTable.innerHTML = "";
            let orders = data.orders;
            let rows = "";

            for (let i = 0; i < orders.length; i++) {
                // Generate the <select> options dynamically
                let optionsHTML = statusOptions.map(status => `
                    <option value="${status}" ${orders[i].status === status ? 'selected' : ''}>
                        ${status.toUpperCase()}
                    </option>
                `).join('');

                rows += `
                    <tr class="hover:bg-base-200 transition-colors">
                        <td class="font-bold align-middle">#${orders[i].order_id}</td>
                        <td class="align-middle">${orders[i].user_id}</td>
                        <td class="align-middle">
                            <select 
                                class="select select-bordered select-xs sm:select-sm w-full max-w-xs font-bold tracking-widest text-primary"
                                onchange="updateOrderStatus(${orders[i].order_id}, this.value)"
                            >
                                ${optionsHTML}
                            </select>
                        </td>
                        <td class="align-middle">${orders[i].shipping_address}</td>
                        <td class="align-middle">${orders[i].shipping_city}</td>
                        <td class="align-middle text-center">${orders[i].shipping_country}</td>
                        <td class="align-middle font-bold text-success">
                            ₱${parseFloat(orders[i].total_amount).toLocaleString()}
                        </td>
                    </tr>
                `;
            }
            adminOrderTable.innerHTML = rows;
        }

    } catch (error) {
        console.error("Failed to load orders:", error);
        adminOrderTable.innerHTML = `<tr><td colspan="7" class="text-center py-10 text-error font-bold uppercase tracking-widest">Unable to load orders.</td></tr>`;
    }
}


        function updateOrderStatus(orderId, newStatus) {
            if (!confirm(`Update order #${orderId} to ${newStatus.toUpperCase()}?`)) {
                loadAdminOrders(); 
                return;
            }

            fetch('../../api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action: "adminUpdateOrderStatus",
                        orderId: orderId,
                        status: newStatus
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        alert("SKRRT! Status updated.");
                        loadAdminOrders(); 
                        loadAnalytics(); 
                    } else {
                        alert("Update Failed: " + data.message);
                    }
                })
                .catch(err => alert("Connection lost. Status not updated."));
        }

        function logout() {
            localStorage.clear();
            window.location.href = 'logout.php';
        }
    </script>
    <script src="../assets/js/stylingAdmin.js"></script>
</body>

</html>
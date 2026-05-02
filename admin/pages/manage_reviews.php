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
    <title>Review Moderation - SKRRT ADMIN</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" /> 
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> 
</head>
<body class="bg-base-200 min-h-screen">

<div class="flex flex-col md:flex-row w-full min-h-screen">
    
    <main class="flex-1 p-4 sm:p-6 md:p-8 w-full max-w-7xl mx-auto">
        <header class="mb-8">
            <h1 class="text-center text-xl sm:text-2xl font-bold">REVIEW MODERATION</h1>
            <p class="text-center text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-[0.2em] mt-1">Manage the community voice in the SKRRT vault.</p>
        </header>

        <!-- RESPONSIVE CONTROLS -->
        <div class="flex flex-col lg:flex-row gap-4 items-center justify-between mb-6 w-full">
            <!-- Search Bar (Full width on mobile) -->
            <div class="w-full lg:max-w-md">
                <label class="input input-bordered flex items-center gap-3 w-full bg-white shadow-sm rounded-xl">
                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </g>
                    </svg>
                    <input type="search" id="searchBar" placeholder="Search product or customer..." class="grow text-sm font-medium" />
                </label>
            </div>

            <!-- Filter & Bulk Actions -->
            <div class="w-full lg:w-auto flex flex-col sm:flex-row gap-2">
                <!-- Bulk Delete Button (Hidden by default) -->
                <button id="bulkDeleteBtn" class="btn bg-red-600 border-none text-white hover:bg-red-700 hidden shadow-sm uppercase tracking-widest text-xs" onclick="deleteMultipleReviews()">
                    Purge Selected
                </button>
                
                <select id="ratingFilter" class="select select-bordered w-full lg:w-48 bg-white shadow-sm" onchange="applyFilter(this.value)">
                    <option value="">All Ratings</option>
                    <option value="5">5 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="2">2 Stars</option>
                    <option value="1">1 Star</option>
                </select>
            </div>
        </div>
        
        <!-- TABLE SECTION -->
        <section class="w-full bg-base-100 rounded-2xl shadow-sm border border-base-300 overflow-hidden">
             <div class="overflow-x-auto">
                 <table class="table table-zebra w-full">
                   <thead class="bg-base-200/50 text-base-content/70">
                      <tr>
                        <!-- Select All Checkbox -->
                        <th class="py-4 w-12 text-center">
                            <input type="checkbox" id="selectAllReviews" class="checkbox checkbox-sm" onchange="toggleAllReviews(this)">
                        </th>
                        <th class="py-4 font-bold uppercase text-[10px] tracking-widest hidden sm:table-cell">ID</th>
                        <th class="py-4 font-bold uppercase text-[10px] tracking-widest">Product</th>
                        <th class="py-4 font-bold uppercase text-[10px] tracking-widest">Customer</th>
                        <th class="py-4 font-bold uppercase text-[10px] tracking-widest hidden md:table-cell">Rating</th>
                        <th class="py-4 font-bold uppercase text-[10px] tracking-widest">Comment</th>
                        <th class="py-4 font-bold uppercase text-[10px] tracking-widest text-center">Action</th>
                      </tr>
                   </thead>
                <tbody id="adminReviewList" class="text-sm">
                    <tr><td colspan="7" class="text-center py-10 text-gray-500 font-medium italic">Scanning vault...</td></tr>
                </tbody>
               </table>
            </div>
       </section>
        
    </main>
</div>

<script>
    const tableBody = document.getElementById('adminReviewList');
    const searchInput = document.getElementById('searchBar');
    let typingTimer;

    document.addEventListener('DOMContentLoaded', loadAllReviews);

    // --- RENDER TABLE ---
    function renderTable(data) {
        if (data.status) {
            if (data.reviews.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="7" class="text-center py-10 text-gray-500">No records found.</td></tr>`;
                return;
            }
            tableBody.innerHTML = data.reviews.map(r => `
                <tr class="hover">
                    <td class="text-center align-middle">
                        <input type="checkbox" class="checkbox checkbox-sm review-checkbox" value="${r.review_id}" onchange="toggleBulkDeleteBtn()" />
                    </td>
                    <td class="font-bold text-gray-400 hidden sm:table-cell align-middle">#${r.review_id}</td>
                    <td class="align-middle"><div class="font-bold uppercase text-xs truncate max-w-[100px] sm:max-w-none">${r.product_name}</div></td>
                    <td class="font-medium align-middle">${r.first_name} ${r.last_name}</td>
                    <td class="text-warning whitespace-nowrap hidden md:table-cell align-middle">
                        ${"★".repeat(r.rating)}${"☆".repeat(5-r.rating)}
                    </td>
                    <td class="max-w-[120px] sm:max-w-xs align-middle">
                        <div class="truncate text-xs text-gray-600" title="${r.review_comments}">"${r.review_comments}"</div>
                    </td>
                    <td class="text-center align-middle">
                        <button class="btn btn-ghost btn-xs text-error font-black tracking-tighter" onclick="deleteReview(${r.review_id})">PURGE</button>
                    </td>
                </tr>
            `).join('');

            // Reset bulk actions state on render
            const selectAll = document.getElementById('selectAllReviews');
            if (selectAll) selectAll.checked = false;
            toggleBulkDeleteBtn();
        } else {
            tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-error font-bold">${data.message}</td></tr>`;
        }
    }

    // --- FETCH LOGIC ---
    function loadAllReviews() {
        tableBody.innerHTML = `<tr><td colspan="7" class="text-center py-10 text-gray-500 font-medium">Loading vault reviews...</td></tr>`;
        fetch('../../api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: "fetchAllReviewsAdmin" })
        })
        .then(res => res.json())
        .then(data => renderTable(data))
        .catch(err => {
            tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-error font-bold">Failed to connect to the vault.</td></tr>`;
        });
    }

    function applyFilter(ratingValue) {
        if (!ratingValue) {
            loadAllReviews();
            return;
        }

        tableBody.innerHTML = `<tr><td colspan="7" class="text-center py-10 text-gray-500 font-medium">Filtering vault reviews...</td></tr>`;
        fetch('../../api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: "filterReviews", rating: parseInt(ratingValue), productId: 0, exact: true }) 
        })
        .then(res => res.json())
        .then(data => renderTable(data))
        .catch(err => {
            tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-error font-bold">Failed to filter reviews.</td></tr>`;
        });
    }
         
    // --- SEARCH LOGIC ---
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            const searchTerm = this.value.trim();
            
            typingTimer = setTimeout(() => {
                if (searchTerm === '') {
                    const currentFilter = document.getElementById('ratingFilter').value;
                    applyFilter(currentFilter);
                } else {
                    searchReviewsAction(searchTerm);
                }
            }, 300);
        });
    }

    async function searchReviewsAction(searchTerm) {
        tableBody.innerHTML = `<tr><td colspan="7" class="text-center py-10 text-gray-500 font-medium">Searching vault reviews...</td></tr>`;
        try {
            const request = await fetch('../../api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: "searchReviews",
                    query: searchTerm
                })
            });
        
            const response = await request.json();
            renderTable(response); 
        } catch (error) {
            console.error("Search error:", error);
            tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-error font-bold">Failed to search the vault.</td></tr>`;
        }
    }
        
    // --- MULTIPLE PURGE / BULK DELETE LOGIC ---
    function toggleAllReviews(source) {
        const checkboxes = document.querySelectorAll('.review-checkbox');
        checkboxes.forEach(cb => cb.checked = source.checked);
        toggleBulkDeleteBtn();
    }

    function toggleBulkDeleteBtn() {
        const checkboxes = document.querySelectorAll('.review-checkbox:checked');
        const btn = document.getElementById('bulkDeleteBtn');
        if (btn) {
            if (checkboxes.length > 0) {
                btn.classList.remove('hidden');
                btn.innerText = `Purge Selected (${checkboxes.length})`;
            } else {
                btn.classList.add('hidden');
            }
        }
    }

    function deleteMultipleReviews() {
        const checkedBoxes = document.querySelectorAll('.review-checkbox:checked');
        const selectedIds = Array.from(checkedBoxes).map(cb => parseInt(cb.value));

        if (selectedIds.length === 0) return;
        if (!confirm(`Are you sure you want to purge ${selectedIds.length} review(s) from the vault?`)) return;

        fetch('../../api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: "deleteReview", reviewIds: selectedIds })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status) {
                alert("SKRRT! " + data.message);
                const currentFilter = document.getElementById('ratingFilter').value;
                applyFilter(currentFilter);
            } else {
                alert("Vault Error: " + data.message);
            }
        });
    }

    // --- SINGLE DELETE ---
    function deleteReview(id) {
        if (!confirm("Are you sure you want to purge this review from the vault?")) return;

        fetch('../../api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: "deleteReview", reviewId: id })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status) {
                alert("Review purged.");
                const currentFilter = document.getElementById('ratingFilter').value;
                applyFilter(currentFilter); 
            } else {
                alert("Vault Error: " + data.message);
            }
        });
    }
       
    // --- AUTH LOGIC ---
    function logout() {
        fetch('../../api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: "logout" })
        })
        .then(() => {
            localStorage.clear();
            window.location.href = 'logout.php';
        });
    }

</script>
<script src="../assets/js/stylingAdmin.js"></script>
</body>
</html>
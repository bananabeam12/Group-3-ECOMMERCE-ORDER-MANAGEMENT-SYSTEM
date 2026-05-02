<?php
// Security: Kick out if user is not an admin
session_start();
if (empty($_SESSION['user_id']) || empty($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

include('../includes/header.php');
include('../includes/sidebar.php');
include('../includes/addinventory_modal.php');
include('../includes/restoreproduct_modal.php');
include('../includes/addcategory_modal.php');
include('../includes/restock_modal.php');
include('../includes/viewimage_modal.php');
?>
<!DOCTYPE html>
<html lang="en" data-theme="cupcake">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management - SKRRT WORLDWIDE</title>
    
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" /> 
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> 
</head>

<body class="bg-base-200 min-h-screen relative">

    <div class="flex flex-col md:flex-row w-full min-h-screen">

        <main class="flex-1 p-4 sm:p-6 md:p-8 w-full max-w-7xl mx-auto overflow-x-hidden">
        <header class="mb-8 flex flex-col sm:flex-row justify-between items-center gap-2">
    
            <div>
                <h1 class="text-xl sm:text-2xl font-bold uppercase tracking-widest text-center sm:text-left">
                    INVENTORY VAULT
                </h1>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1 text-center sm:text-left">
                    Manage your global drops and stock
                </p>
            </div>

            <!-- BUTTON GROUP -->
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <button 
                    class="btn bg-indigo-600 border-none text-white hover:bg-indigo-700 font-bold uppercase tracking-widest shadow-md rounded-lg px-6 py-2 w-full sm:w-auto"
                    onclick="document.getElementById('addProductModal').style.display = 'flex'">
                    + Add Product
                </button>

                <button 
                    class="btn bg-indigo-600 border-none text-white hover:bg-indigo-700 font-bold uppercase tracking-widest shadow-md rounded-lg px-6 py-2 w-full sm:w-auto"
                    onclick="openRestoreModal()">
                    Restore Product
                </button>

                <button 
                    class="btn bg-indigo-600 border-none text-white hover:bg-indigo-700 font-bold uppercase tracking-widest shadow-md rounded-lg px-6 py-2 w-full sm:w-auto"
                    onclick="openAddCategoryModal()">
                    Add Category
                </button>
            </div>

        </header>
                   
        <section class="flex flex-col sm:flex-row items-center gap-3 w-full mb-4">
            <label class="input input-bordered flex items-center gap-3 w-full max-w-md bg-white border focus-within:border-none focus-within:ring-1 focus-within:ring-none rounded-lg shadow-sm transition-all duration-200">
                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </g>
                </svg>
                <input type="search" required placeholder="Search" class="grow text-black placeholder-gray-400 text-sm font-medium bg-transparent border-none focus:outline-none" id="searchBar" />
            </label>

            <!-- Category Filter Dropdown -->
            <select id="filterCategory" class="select select-bordered bg-white w-full sm:w-auto text-sm font-medium" onchange="filterByCategory()">
                <option value="">All Categories</option>
            </select>

            <!-- Bulk Delete Button (Hidden by default) -->
            <button id="bulkDeleteBtn" class="btn bg-red-600 border-none text-white hover:bg-red-700 hidden shadow-sm uppercase tracking-widest text-xs" onclick="deleteMultipleProducts()">
                Delete Selected
            </button>
        </section>

        <section class="w-full bg-base-100 rounded-xl shadow-sm border border-base-300 overflow-hidden">
            <div class="overflow-x-auto w-full">
                <table class="table table-zebra w-full text-xs sm:text-sm">
                    <thead class="bg-base-200/50 text-base-content/70">
                        <tr>
                            <!-- Select All Checkbox Header -->
                            <th class="py-4 w-12 text-center">
                                <input type="checkbox" id="selectAllProducts" class="checkbox checkbox-sm" onchange="toggleAllProducts(this)">
                            </th>
                            <th class="py-4 font-bold uppercase text-xs whitespace-nowrap">Product</th>
                            <th class="py-4 font-bold uppercase text-xs whitespace-nowrap">Description</th>
                            <th class="py-4 font-bold uppercase text-xs whitespace-nowrap">Category</th>
                            <th class="py-4 font-bold uppercase text-xs whitespace-nowrap">Price</th>
                            <th class="py-4 font-bold uppercase text-xs whitespace-nowrap">Stock</th>
                            <th class="py-4 font-bold uppercase text-xs whitespace-nowrap">Preview</th>
                            <th class="py-4 font-bold uppercase text-xs whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="inventoryTable">
                        <tr>
                            <td colspan="8" class="text-center py-10 text-gray-500 font-medium">Fetching vault contents...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        </main>
    </div>
       
    <dialog id="editModal" class="modal">
        <div class="modal-box w-full max-w-lg rounded-xl p-8 bg-base-100 border border-black">
            <h3 class="font-black text-xl mb-6 uppercase tracking-widest border-b border-base-300 pb-3">Edit Product</h3>
            <form id="editProductForm" class="flex flex-col gap-4">
                <input type="hidden" id="editId" name="productId">

                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-[10px] uppercase text-gray-500 tracking-widest">Name</span></label>
                    <input type="text" id="editName" name="productName" class="input input-bordered w-full bg-gray-50 rounded-lg">
                </div>

                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-[10px] uppercase text-gray-500 tracking-widest">Description</span></label>
                    <textarea id="editDescription" name="productDescription" class="textarea textarea-bordered w-full h-20 resize-none bg-gray-50 rounded-lg"></textarea>
                </div>

                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-[10px] uppercase text-gray-500 tracking-widest">Category</span></label>
                    <select id="editCategory" name="categoryId" class="select select-bordered w-full bg-gray-50 rounded-lg"></select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-bold text-[10px] uppercase text-gray-500 tracking-widest">Price (PHP)</span></label>
                        <input type="number" id="editPrice" name="productPrice" step="0.01" class="input input-bordered w-full bg-gray-50 rounded-lg">
                    </div>
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-bold text-[10px] uppercase text-gray-500 tracking-widest">Stock</span></label>
                        <input type="number" id="editStock" name="stockQuantity" class="input input-bordered w-full bg-gray-50 rounded-lg">
                    </div>
                </div>

                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-[10px] uppercase text-gray-500 tracking-widest">Current Gallery</span></label>
                    <div id="editGalleryPreview" class="flex gap-3 flex-wrap p-3 bg-gray-50 border border-base-300 rounded-lg min-h-[80px] shadow-inner">
                    </div>
                </div>

                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-[10px] uppercase text-gray-500 tracking-widest">Update Gallery</span></label>
                    <input type="file" name="productImages[]" accept="image/*" multiple class="file-input file-input-bordered w-full bg-gray-50 rounded-lg">
                    <p class="text-[9px] text-gray-400 mt-2 uppercase italic font-medium tracking-wide">Adding new images will merge into the vault.</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 mt-4">
                    <button type="button" onclick="submitProductUpdate()" class="btn bg-black text-white hover:bg-[#ccff00] hover:text-black flex-1 font-bold uppercase tracking-widest rounded-lg transition-colors border-none">
                        Update Product
                    </button>
                    <button type="button" onclick="closeEditModal()" class="btn btn-ghost text-gray-500 hover:text-black hover:bg-gray-200 w-full sm:w-auto font-bold uppercase tracking-widest rounded-lg border-none">
                        Cancel
                    </button>
                </div>
            </form>
        </div>

        <form method="dialog" class="modal-backdrop bg-black/50">
            <button onclick="closeEditModal()">close</button>
        </form>
    </dialog>


<script>
    // --- DOM Elements & Global Variables ---
    const searchInput = document.getElementById('searchBar');
    const tableBody = document.getElementById('inventoryTable');
    let typingTimer;

    // --- Initialization ---
    
    document.addEventListener('DOMContentLoaded', () => {
        loadCategories();
        displayInventory();
    });

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            const searchTerm = this.value.trim().toLowerCase();
            
            typingTimer = setTimeout(() => {
                search(searchTerm);
            }, 300);
        });
    }

  
   // --- UI Helper Functions ---
    
   function generateTableRow(p) {
        return `
        <tr>
            <td class="align-middle text-center">
                <input type="checkbox" class="checkbox checkbox-sm product-checkbox" value="${p.product_id}" onchange="toggleBulkDeleteBtn()" />
            </td>
            <td class='font-bold uppercase tracking-tight align-middle'>${p.product_name}</td>
            <td class='text-gray-500 text-xs align-middle max-w-xs truncate'>${p.product_description || 'No description'}</td>
            <td class='text-gray-600 font-medium uppercase align-middle'>${p.category_name || 'Category'}</td>
            <td class='font-black align-middle'>₱${Number(p.price).toLocaleString()}</td>
            <td class='font-bold align-middle'>${Number(p.stock_quantity)}</td>
            <td class='align-middle'>
                <img 
                    src="../${p.image_url || 'assets/images/default-placeholder.jpg'}" 
                    alt="${p.product_name}" 
                    class="w-14 h-14 object-cover rounded-lg border border-base-300 shadow-sm cursor-pointer hover:opacity-75 transition-opacity"
                    onclick="openViewImageModal('../${p.image_url || 'assets/images/default-placeholder.jpg'}', '${p.product_name}')"
                    title="Click to view image"
                >
            </td>
            <td class="align-middle">
                <div class="flex items-center gap-3 whitespace-nowrap">
                    <img src="../assets/images/edit_icon.svg" onclick="openEditModal(${p.product_id})" class="w-6 h-6 cursor-pointer hover:opacity-70 transition-opacity" title="Edit"/>
                    <img src="../assets/images/trash_icon.svg" onclick="deleteProduct(${p.product_id})" class="w-6 h-6 cursor-pointer hover:opacity-70 transition-opacity" title="Delete"/>
                    
                    <!-- New Restock Icon -->
                    <svg onclick="openRestockModal(${p.product_id})" title="Restock" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 cursor-pointer hover:opacity-70 transition-opacity text-black">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                </div>
            </td>
        </tr>`;
    }

    // --- Core API Functions ---

    async function loadCategories() { 
        try {
            const res = await fetch('../../api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: "fetchAllCategories" })
            });
            
            if (!res.ok) throw new Error(`HTTP ${res.status}: Vault connection failed`);
            
            const data = await res.json();
            
            if (data.status) {
                const options = data.categories.map(c =>
                    `<option value="${c.category_id}">${c.category_name.toUpperCase()}</option>`
                ).join('');
                
                const addCat = document.getElementById('categorySelect');
                const editCat = document.getElementById('editCategory');
                const filterCat = document.getElementById('filterCategory');
                
                if (addCat) addCat.innerHTML = options;
                if (editCat) editCat.innerHTML = options;
                if (filterCat) filterCat.innerHTML += options; 
            } else {
                throw new Error(data.message || "Failed to parse categories.");
            }
        } catch (err) {
            console.error("Category load failed:", err);
            // Optional: fallback UI logic could go here
        }
    }

    async function loadArchivedProducts() {
        const listBody = document.getElementById('archivedProductsList');
        listBody.innerHTML = '<tr><td colspan="4" class="text-center py-6 text-gray-500 font-medium">Fetching archived products...</td></tr>';

        try {
            const res = await fetch('../../api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: "fetchArchivedProducts" })
            });
            
            if (!res.ok) throw new Error("Vault connection failed");
            
            const data = await res.json();

            if (data.status) {
                archivedProductsData = data.archive; 
                renderArchivedProducts(archivedProductsData);
            } else {
                listBody.innerHTML = `<tr><td colspan="4" class="text-center py-6 text-red-500 font-bold">Error: ${data.message}</td></tr>`;
            }
        } catch (error) {
            console.error("Archive fetch error:", error);
            listBody.innerHTML = `<tr><td colspan="4" class="text-center py-6 text-red-500 font-bold">Connection error. Please try again.</td></tr>`;
        }
    }

    async function displayInventory(categoryId = '') {
        try {
            const res = await fetch('../../api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: "fetchInventory", categoryId: categoryId })
            });
             
            if (!res.ok) throw new Error("HTTP connection failed");
                
            const data = await res.json();
            const rows = data.inventory.map(p => generateTableRow(p)).join('');
            tableBody.innerHTML = rows || `<tr><td colspan="8" class="text-center py-10 text-gray-500 font-medium">Vault is empty.</td></tr>`;
            
            // Reset bulk actions state on load
            const selectAll = document.getElementById('selectAllProducts');
            if (selectAll) selectAll.checked = false;
            toggleBulkDeleteBtn();
            
        } catch (error) {
            console.error("Inventory load failed:", error);
        } 
    }

     // --- Global Number Validation ---
function preventNegativeNumbers() {
    document.addEventListener('input', function(event) {
        // Target only number inputs
        if (event.target.type === 'number') {
            const value = parseFloat(event.target.value);
            // If the value is less than 0, clear it and warn the user
            if (value < 0) {
                event.target.value = ''; 
                alert("Negative numbers are not allowed in the vault.");
            }
        }
    });
}

// Activate the function
preventNegativeNumbers();

    // Filter Logic
    function filterByCategory() {
        const catId = document.getElementById('filterCategory').value;
        displayInventory(catId);
    }

    // Bulk Delete Selection Logic
    function toggleAllProducts(source) {
        const checkboxes = document.querySelectorAll('.product-checkbox');
        checkboxes.forEach(cb => cb.checked = source.checked);
        toggleBulkDeleteBtn();
    }

    function toggleBulkDeleteBtn() {
        const checkboxes = document.querySelectorAll('.product-checkbox:checked');
        const btn = document.getElementById('bulkDeleteBtn');
        if (btn) {
            if (checkboxes.length > 0) {
                btn.classList.remove('hidden');
                btn.innerText = `Delete Selected (${checkboxes.length})`;
            } else {
                btn.classList.add('hidden');
            }
        }
    }

    async function deleteProduct(productId) {
        if (!confirm("Are you sure you want to remove this item from the shop?")) return;

        try {
            const res = await fetch("../../api.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ action: "deleteProduct", productId: productId })
            });

            if (!res.ok) throw new Error("Vault connection failed");

            const result = await res.json();

            if (result.status) {
                alert(result.message);
                const currentCategory = document.getElementById('filterCategory').value;
                displayInventory(currentCategory); 
            } else {
                alert("Error: " + (result.message || "Deletion failed."));
            }
        } catch (error) {
            console.error("Delete failed:", error);
            alert("Connection lost. Could not delete the product.");
        }
    }

    async function deleteMultipleProducts() {
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        const selectedIds = Array.from(checkedBoxes).map(cb => parseInt(cb.value));

        if (selectedIds.length === 0) return;
        if (!confirm(`Are you sure you want to remove ${selectedIds.length} items from the shop?`)) return;

        try {
            const res = await fetch("../../api.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ action: "deleteProduct", productIds: selectedIds })
            });

            if (!res.ok) throw new Error("Vault connection failed");

            const result = await res.json();

            if (result.status) {
                alert(result.message);
                const currentCategory = document.getElementById('filterCategory').value;
                displayInventory(currentCategory); 
                loadCategories(); // Refresh categories in case some got orphaned
            } else {
                alert("Error: " + (result.message || "Bulk deletion failed."));
            }
        } catch (error) {
            console.error("Bulk delete failed:", error);
            alert("Connection lost. Could not process bulk deletion.");
        }
    }

    async function submitRestoreProduct() {
        const checkedBoxes = document.querySelectorAll('.restore-checkbox:checked');
        const selectedIds = Array.from(checkedBoxes).map(cb => parseInt(cb.value));

        if (selectedIds.length === 0) {
            alert("Please select at least one product to restore.");
            return;
        }

        try {
            const request = await fetch('../../api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: "restoreProduct", 
                    productIds: selectedIds 
                })
            });

            if (!request.ok) throw new Error("Vault connection failed");

            const response = await request.json();

            if (response.status) {
                alert("SKRRT! " + response.message);
                closeRestoreModal(); 
                const currentCategory = document.getElementById('filterCategory').value;
                displayInventory(currentCategory); 
            } else {
                alert("Vault Error: " + (response.message || "Restoration failed."));
            }
        } catch (error) {
            console.error("Restore failed:", error);
            alert("Connection lost. Failed to restore the products.");
        }
    }

    async function search(searchTerm) {
        try {
            const request = await fetch('../../api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: "searchProducts",
                    query: searchTerm
                })
            });
        
            const response = await request.json();
              
            if (response.status) {
                const rows = response.products.map(p => generateTableRow(p)).join('');
                tableBody.innerHTML = rows || `<tr><td colspan="8" class="text-center py-10 text-gray-500 font-medium">No products found.</td></tr>`;
            } else {
                throw new Error("Search API returned an error");
            }
        } catch (error) {
            console.error("Search error:", error);
        }
    }

    // --- Product Management (Add/Edit/Delete Single) ---

    const addForm = document.getElementById('addProductForm');
    if (addForm) {
        addForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'addProduct');

            fetch('../../api.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error("Vault Connection Error");
                return response.json();
            })
            .then(result => {
                if (result.status) {
                    alert("SKRRT! Product added successfully.");
                    window.location.href = "inventory.php";
                } else {
                    alert("Vault Error: " + result.message);
                }
            })
            .catch(error => {
                console.error("Submission error:", error);
                alert("Failed to reach the server. Please check your connection.");
            });
        });
    }

    function submitProductUpdate() {
        const formData = new FormData(document.getElementById('editProductForm'));
        formData.append('action', 'updateProduct');

        fetch('../../api.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(result => {
            if (result.status) {
                alert("Product updated successfully.");
                closeEditModal();
                const currentCategory = document.getElementById('filterCategory').value;
                displayInventory(currentCategory);
            } else {
                alert("Error: " + result.message);
            }
        })
        .catch(err => alert("Connection error. Please try again."));
    }
    
    function deleteProduct(productId) {
        if (!confirm("Are you sure you want to remove this item from the shop?")) return;

        fetch("../../api.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ action: "deleteProduct", productId: productId })
        })
        .then(response => response.json())
        .then(result => {
            if (result.status) {
                alert(result.message);
                const currentCategory = document.getElementById('filterCategory').value;
                displayInventory(currentCategory); 
            } else {
                alert("Error: " + result.message);
            }
        })
        .catch(error => console.error("Delete failed:", error));
    }

    // --- Modal Handling ---

    async function openEditModal(productId) {
        const res = await fetch('../../api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: "fetchProductDetails", productId })
        });
        const data = await res.json();

        if (data.status) {
            const p = data.product;
            document.getElementById('editId').value = p.product_id;
            document.getElementById('editName').value = p.product_name;
            document.getElementById('editDescription').value = p.product_description;
            document.getElementById('editPrice').value = p.price;
            document.getElementById('editStock').value = p.stock_quantity;
            document.getElementById('editCategory').value = p.category_id;

            const galleryContainer = document.getElementById('editGalleryPreview');
            galleryContainer.innerHTML = p.gallery.length > 0
                ? p.gallery.map(img => `
                    <div class="relative w-16 h-16 flex-shrink-0 group">
                        <img src="../${img.image_url}"
                            class="w-full h-full object-cover rounded-md border border-base-300 shadow-sm">
                        <button type="button"
                            onclick="removeImageFromGallery(${img.product_image_id}, this)"
                            class="absolute -top-2 -right-2 bg-error text-white rounded-full w-5 h-5 text-[10px] flex items-center justify-center font-black leading-none border-none cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity shadow-md hover:scale-110">
                            ✕
                        </button>
                    </div>
                `).join('')
                : '<p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest py-4 w-full text-center">No images in gallery.</p>';

            document.getElementById('editModal').showModal();
        } else {
            alert("Failed to load product details.");
        }
    }

    function closeEditModal() {
        document.getElementById('editModal').close();
    }

    function openRestockModal(productId) {
        const modal = document.getElementById('restockModal');
        document.getElementById('restockId').value = productId;
        document.getElementById('restockAmount').value = ''; 
        modal.showModal();
    }

    function closeRestockModal() {
        document.getElementById('restockModal').close();
    }


    function closeRestoreModal() {
        document.getElementById('restoreModal').close();
        document.getElementById('restoreProductId').value = ''; 
    }

    // --- Action Submissions ---

    async function submitRestock() {
        const productId = document.getElementById('restockId').value;
        const amount = document.getElementById('restockAmount').value;

        if (!amount || amount <= 0) {
            alert("Please enter a valid quantity.");
            return;
        }

        try {
            const response = await fetch('../../api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: "restockProduct",
                    productId: productId,
                    quantity: parseInt(amount)
                })
            });

            if (!response.ok) throw new Error("HTTP connection failed");

            const result = await response.json();

            if (result.status) {
                alert("SKRRT! " + result.message);
                closeRestockModal();
                const currentCategory = document.getElementById('filterCategory').value;
                displayInventory(currentCategory);
            } else {
                alert("Vault Error: " + result.message);
            }
        } catch (error) {
            console.error("Restock failed:", error);
            alert("Connection lost. Failed to update the stock.");
        }
    }

    

    async function removeImageFromGallery(imageId, element) {
        if (!confirm("Remove this image from the vault?")) return;

        const res = await fetch('../../api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: "deleteProductImage", imageId })
        });
        const result = await res.json();

        if (result.status) {
            element.parentElement.remove();
        } else {
            alert("Error: " + result.message);
        }
    }

    // --- Utilities ---

    function previewImages(event) {
        const container = document.getElementById('imagePreviewContainer');
        const files = event.target.files;
        if(container){
            container.innerHTML = '';

            if (files.length === 0) {
                const placeholder = document.getElementById('placeholderText');
                if (placeholder) container.appendChild(placeholder);
                return;
            }

            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = "relative aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-white group";
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="text-[8px] text-white font-bold uppercase tracking-tighter">Selected</span>
                        </div>
                    `;
                    container.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
    }
       

    // --- Restore Modal Variables & Logic ---
    let archivedProductsData = [];

    async function openRestoreModal() {
        const modal = document.getElementById('restoreModal');
        modal.showModal();
        
        // Reset modal state
        document.getElementById('restoreSearch').value = '';
        document.getElementById('selectAllRestore').checked = false;
        
        await loadArchivedProducts();
    }

    function closeRestoreModal() {
        document.getElementById('restoreModal').close();
    }

    async function loadArchivedProducts() {
        const listBody = document.getElementById('archivedProductsList');
        listBody.innerHTML = '<tr><td colspan="4" class="text-center py-6 text-gray-500 font-medium">Fetching archived products...</td></tr>';

        try {
            const res = await fetch('../../api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: "fetchArchivedProducts" })
            });
            const data = await res.json();

            if (data.status) {
                archivedProductsData = data.archive; // Store globally for client-side search
                renderArchivedProducts(archivedProductsData);
            } else {
                listBody.innerHTML = `<tr><td colspan="4" class="text-center py-6 text-error">Failed to load archive.</td></tr>`;
            }
        } catch (error) {
            console.error("Archive fetch error:", error);
            listBody.innerHTML = `<tr><td colspan="4" class="text-center py-6 text-error">Connection error.</td></tr>`;
        }
    }

    function renderArchivedProducts(products) {
        const listBody = document.getElementById('archivedProductsList');
        if (!products || products.length === 0) {
            listBody.innerHTML = '<tr><td colspan="4" class="text-center py-6 text-gray-500 font-medium">No archived products found.</td></tr>';
            return;
        }

        listBody.innerHTML = products.map(p => `
            <tr>
                <td class="align-middle">
                    <input type="checkbox" class="checkbox checkbox-sm restore-checkbox" value="${p.product_id}" />
                </td>
                <td class="align-middle">
                    <img src="../${p.image_url || 'assets/images/default-placeholder.jpg'}" alt="${p.product_name}" class="w-10 h-10 object-cover rounded border border-base-300 shadow-sm">
                </td>
                <td class="font-bold align-middle">${p.product_id}</td>
                <td class="uppercase font-medium align-middle">${p.product_name}</td>
            </tr>
        `).join('');
    }

    // Modal Search Bar Event Listener
    document.getElementById('restoreSearch')?.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase().trim();
        
        // Filter locally by ID or Name
        const filtered = archivedProductsData.filter(p => 
            p.product_name.toLowerCase().includes(searchTerm) || 
            p.product_id.toString().includes(searchTerm)
        );
        
        renderArchivedProducts(filtered);
        document.getElementById('selectAllRestore').checked = false; // Reset "Select All" on new search
    });

    // "Select All" Checkbox Event Listener
    document.getElementById('selectAllRestore')?.addEventListener('change', function(e) {
        const checkboxes = document.querySelectorAll('.restore-checkbox');
        checkboxes.forEach(cb => cb.checked = e.target.checked);
    });

    // Submit Restore Array
    async function submitRestoreProduct() {
        const checkedBoxes = document.querySelectorAll('.restore-checkbox:checked');
        const selectedIds = Array.from(checkedBoxes).map(cb => parseInt(cb.value));

        if (selectedIds.length === 0) {
            alert("Please select at least one product to restore.");
            return;
        }

        try {
            let request = await fetch('../../api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: "restoreProduct", 
                    productIds: selectedIds // Sending as an array
                })
            });

            let response = await request.json();

            if (response.status) {
                alert("SKRRT! " + response.message);
                closeRestoreModal(); 
                const currentCategory = document.getElementById('filterCategory').value;
                displayInventory(currentCategory); // Refresh the main inventory table
            } else {
                alert("Vault Error: " + response.message);
            }
        } catch (error) {
            console.error("Restore failed:", error);
            alert("Connection lost. Failed to restore the products.");
        }
    }   

    function openAddCategoryModal() {
        document.getElementById("addCategoryModal").style.display = 'flex';
    }

    // Handle the Add Category Form Submission
    const addCategoryForm = document.getElementById('addCategoryForm');

    if (addCategoryForm) {
        addCategoryForm.addEventListener('submit', async function(event) {
            event.preventDefault();

            const formData = new FormData(this);
            formData.append('action', 'addCategory');

            try {
                const response = await fetch('../../api.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error("HTTP connection failed");
                }

                const result = await response.json();

                if (result.status) {
                    alert("SKRRT! Category created successfully.");
                    document.getElementById('addCategoryModal').style.display = 'none';
                    
                    // Reset form fields and refresh category dropdowns
                    this.reset();
                    document.getElementById('filterCategory').innerHTML = '<option value="">All Categories</option>'; // Clear existing append cache
                    loadCategories();
                } else {
                    alert("Vault Error: " + result.message);
                }
            } catch (error) {
                console.error("Submission error:", error);
                alert("Failed to reach the server. Please check your connection.");
            }
        });
    }

    // --- Image Viewer Variables ---
    let viewImageScale = 1;
    let viewImagePointX = 0;
    let viewImagePointY = 0;
    let viewImagePanning = false;
    let viewImageStartX = 0;
    let viewImageStartY = 0;

    const zoomableImage = document.getElementById('zoomableImage');
    const imageContainer = document.getElementById('imageContainer');

    // --- Image Viewer Methods ---
    function openViewImageModal(imageUrl, productName) {
        zoomableImage.src = imageUrl;
        zoomableImage.alt = productName;
        resetImageTransform();
        document.getElementById('viewImageModal').showModal();
    }

    function applyImageTransform() {
        if (viewImageScale < 0.3) viewImageScale = 0.3;
        if (viewImageScale > 5) viewImageScale = 5;
        zoomableImage.style.transform = `translate(${viewImagePointX}px, ${viewImagePointY}px) scale(${viewImageScale})`;
    }

    function resetImageTransform() {
        viewImageScale = 1;
        viewImagePointX = 0;
        viewImagePointY = 0;
        applyImageTransform();
    }

    function zoomInImage() {
        viewImageScale *= 1.2;
        applyImageTransform();
    }

    function zoomOutImage() {
        viewImageScale /= 1.2;
        applyImageTransform();
    }

    imageContainer.addEventListener('wheel', function(event) {
        event.preventDefault(); 
        if (event.deltaY < 0) {
            viewImageScale *= 1.15; 
        } else {
            viewImageScale /= 1.15; 
        }
        applyImageTransform();
    }, { passive: false });

    imageContainer.addEventListener('mousedown', function(event) {
        event.preventDefault();
        viewImagePanning = true;
        viewImageStartX = event.clientX - viewImagePointX;
        viewImageStartY = event.clientY - viewImagePointY;
        imageContainer.classList.remove('cursor-move');
        imageContainer.style.cursor = 'grabbing';
    });

    imageContainer.addEventListener('mousemove', function(event) {
        if (!viewImagePanning) return;
        event.preventDefault();
        viewImagePointX = event.clientX - viewImageStartX;
        viewImagePointY = event.clientY - viewImageStartY;
        applyImageTransform();
    });

    window.addEventListener('mouseup', function() {
        if (viewImagePanning) {
            viewImagePanning = false;
            imageContainer.style.cursor = '';
            imageContainer.classList.add('cursor-move');
        }
    });
    
    function logout() {
        localStorage.clear();
        window.location.href = 'logout.php';
    }
</script>
    <script src="../assets/js/stylingAdmin.js"></script>
</body>

</html>
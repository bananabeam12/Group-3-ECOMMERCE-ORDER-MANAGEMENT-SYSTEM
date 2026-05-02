/**
 * Admin Inventory Management
 * Refactored to .then() syntax
 */

// 1. Fetch and display the inventory table
function displayInventory() {
    const tbody = document.getElementById("inventoryTable");
    if (!tbody) return;

    fetch("../../api.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action: "fetchAllProducts" })
    })
    .then(response => response.json())
    .then(result => {
        if (result.status) {
            // Map through results to build the table rows
            tbody.innerHTML = result.products.map(product => {
                const formattedPrice = parseFloat(product.price).toLocaleString('en-PH', {
                    style: 'currency',
                    currency: 'PHP'
                });

                return `
                <tr>
                    <td>${product.product_name}</td>
                    <td><span class="badge bg-light text-dark border">
                        ${product.category_name || 'Uncategorized'}
                    </span></td>
                    <td>${formattedPrice}</td>
                    <td>${product.stock_quantity}</td>
                    <td>
                        <img src="${product.image_url ? '../' + product.image_url : '../assets/images/no-image.png'}" 
                             alt="product" style="width:50px; height:50px; object-fit:cover;">
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-dark" onclick='openEditModal(${JSON.stringify(product)})'>EDIT</button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteProduct(${product.product_id})">DELETE</button>
                    </td>
                </tr>
                `;
            }).join('');
        }
    })
    .catch(error => console.error("Error loading inventory:", error));
}

// 2. Delete a product
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
            displayInventory(); // Refresh table without reloading page
        } else {
            alert("Error: " + result.message);
        }
    })
    .catch(error => console.error("Delete failed:", error));
}

// 3. Submit Update (Handles Files/FormData)
function submitProductUpdate() {
    const form = document.getElementById('editProductForm');
    
    // Package text fields AND the image file
    const formData = new FormData(form);
    formData.append('action', 'updateProduct');

    // Leader's Syntax: No 'await', just the chain
    fetch("../../api.php", {
        method: "POST",
        // Note: NO 'Content-Type' header here, FormData handles it!
        body: formData 
    })
    .then(response => response.json())
    .then(result => {
        if (result.status) {
            alert("SKRRT! Product updated successfully.");
            location.reload(); 
        } else {
            alert("Update Failed: " + result.message);
        }
    })
    .catch(error => {
        console.error("Update error:", error);
        alert("Failed to connect to server.");
    });
}

// Helper to open the modal (Already synchronous)
function openEditModal(product) {
    document.getElementById('editId').value = product.product_id;
    document.getElementById('editName').value = product.product_name;
    document.getElementById('editDescription').value = product.product_description;
    document.getElementById('editPrice').value = product.price;
    document.getElementById('editStock').value = product.stock_quantity;
    document.getElementById('editCategory').value = product.category_id || "";

    document.getElementById('editModal').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
}
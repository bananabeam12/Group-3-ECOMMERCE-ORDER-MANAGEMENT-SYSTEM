document.addEventListener("DOMContentLoaded", function () {

    // Check session via PHP (NOT localStorage — session is set server-side on login)
    fetch("../../api.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action: "getSessionUser" })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.status) {
            // No valid session — kick to login
            window.location.href = "../../index.php";
            return;
        }

        const userData = data.user;

        // Set welcome message
        const welcomeMsg = document.getElementById("welcome-message");
        if (welcomeMsg) {
            welcomeMsg.innerText = `WELCOME, ${(userData.firstName || userData.first_name || "ADMIN").toUpperCase()}!`;
        }
    })
    .catch(error => {
        console.error("Session check failed:", error);
        // Don't redirect on network error — just log it
    });
});

/**
 * --- DATA FETCHING FUNCTIONS (.then Style) ---
 */

// A. Load All Users
function displayUsers(container) {
    fetch("../../api.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action: "fetchAllUsers" })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            container.innerHTML = data.users.map(user => `
                <tr>
                    <td class="ps-4">${user.first_name} ${user.last_name}</td>
                    <td>${user.email}</td>
                    <td class="small">
                        ${user.address_description ?
                            `<strong>${user.address_description}</strong><br>${user.city}, ${user.province}` :
                            '<span class="text-muted">No Address Saved</span>'}
                    </td>
                    <td class="pe-4">
                        <span class="badge ${user.user_role === 'admin' ? 'bg-dark' : 'bg-primary'}">
                            ${(user.user_role || 'customer').toUpperCase()}
                        </span>
                    </td>
                </tr>
            `).join('');
        }
    })
    .catch(error => console.error("User Load Error:", error));
}

// B. Load Inventory (Simplified version for Dashboard)
function displayInventory(container) {
    fetch('../../api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: "fetchAllProducts" })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            container.innerHTML = data.products.map(p => `
                <tr>
                    <td class="ps-4">${p.product_id}</td>
                    <td>${p.product_name}</td>
                    <td>₱${parseFloat(p.price).toLocaleString()}</td>
                    <td>${p.stock_quantity}</td>
                    <td class="pe-4"><button class="btn btn-sm btn-dark">VIEW</button></td>
                </tr>
            `).join('');
        }
    })
    .catch(error => console.error("Inventory Load Error:", error));
}

// C. Load Admin Orders
function displayAdminOrders(container) {
    fetch('../../api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: "fetchAllOrders" })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            container.innerHTML = data.orders.map(o => `
                <tr>
                    <td class="ps-4">#${o.order_id}</td>
                    <td>${o.first_name} ${o.last_name}</td>
                    <td>₱${parseFloat(o.total_amount).toLocaleString()}</td>
                    <td><span class="badge bg-info">${o.status.toUpperCase()}</span></td>
                    <td class="pe-4">${new Date(o.created_at).toLocaleDateString()}</td>
                </tr>
            `).join('');
        }
    })
    .catch(error => console.error("Order Load Error:", error));
}
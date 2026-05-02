/**
 * SKRRT WORLDWIDE - Authentication & Header Logic
 * Refactored to .then() Promise Syntax
 */

// 1. Function to update the UI based on login status
function updateHeader(user) {
    const nav = document.getElementById('main-nav');
    if (!nav) return;

    // Use the name if it exists, otherwise use a placeholder
    const name = (user && user.firstName) ? user.firstName.toUpperCase() : "USER";

    nav.innerHTML = `
        <div class="container-fluid d-flex justify-content-between align-items-center py-3">
            <div class="header-left">
                <a href="shop.php" class="text-white fw-bold text-decoration-none" style="letter-spacing: 2px;">SKRRT WORLDWIDE</a>
            </div>
            
            <div class="header-right d-flex align-items-center gap-3 text-white small">
                <span class="d-none d-lg-inline">WELCOME, ${name}</span>
                <a href="shop.php" class="text-white text-decoration-none">Shop</a>
                <a href="cart.php" class="text-white text-decoration-none">My Cart</a>
                <a href="orders.php" class="text-white text-decoration-none">My Orders</a>
                <a href="profile.php" class="text-white text-decoration-none fw-bold" style="color: #ccff00 !important; margin-left: 15px;">My Profile</a>
                <button onclick="logout()" class="btn btn-outline-light btn-sm rounded-0 ms-2">Logout</button>
            </div>
        </div>
    `;
}

// 2. Logout Logic (Refactored to .then Style)
function logout() {
    // Leader Style: Using .then() instead of await
    fetch('../../api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: "logout" }) // Matches Batch 1 of your refactored api.php
    })
    .then(response => {
        // First check if the server responded correctly
        if (!response.ok) throw new Error("Server Error");
        return response.json();
    })
    .then(result => {
        if (result.status) {
            // 1. Clear the browser's local memory
            localStorage.removeItem('currentUser');
            localStorage.removeItem('cart');

            // 2. Show alert and redirect
            alert("Logged out successfully!");
            window.location.href = '../index.php';
        } else {
            console.error("Logout failed on server:", result.message);
            // Fallback: Logout locally anyway if the session was already expired
            localStorage.clear();
            window.location.href = '../../index.php';
        }
    })
    .catch(error => {
        console.error("Logout Network Error:", error);
        // Safety Fallback: Even if the API fails, clear local data and redirect
        localStorage.clear();
        window.location.href = '../../index.php';
    });
}

// 3. Auto-run header update if user data exists in storage
document.addEventListener("DOMContentLoaded", () => {
    const savedUser = JSON.parse(localStorage.getItem("currentUser"));
    if (savedUser) {
        updateHeader(savedUser);
    }
});
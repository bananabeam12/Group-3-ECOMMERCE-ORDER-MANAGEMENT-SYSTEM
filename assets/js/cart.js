// ─── cart.js — shared across all pages ───────────────────────────────────────
// Handles Add to Cart for both logged-in users (API) and guests (localStorage)

const CART_API = '/E-commerce_Project/api.php';

function getLocalCart() {
    return JSON.parse(localStorage.getItem('skrrt_cart') || '[]');
}

function saveLocalCart(cart) {
    localStorage.setItem('skrrt_cart', JSON.stringify(cart));
    updateCartIndicator();
}

// ── Update the navbar cart badge ──────────────────────────────────────────────
// Logged-in users → count from DB | Guests → count from localStorage
async function updateCartIndicator() {
    const el = document.getElementById('cart-indicator');
    if (!el) return;

    try {
        const sessionRes  = await fetch(CART_API, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'getProfile' })
        });
        const sessionData = await sessionRes.json();

        if (sessionData.status) {
            // Logged in — fetch count from DB
            const cartRes  = await fetch(CART_API, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'fetchDatabaseCart' })
            });
            const cartData = await cartRes.json();
            const items    = cartData.status ? cartData.cart : [];
            el.textContent = items.reduce((sum, i) => sum + parseInt(i.quantity), 0);
        } else {
            // Guest — read from localStorage
            const cart     = getLocalCart();
            el.textContent = cart.reduce((sum, i) => sum + parseInt(i.quantity), 0);
        }
    } catch {
        // API unreachable — fallback to localStorage
        const cart     = getLocalCart();
        el.textContent = cart.reduce((sum, i) => sum + parseInt(i.quantity), 0);
    }
}

// ── Add to Cart ───────────────────────────────────────────────────────────────
async function addToCart(productId, productName, price, imageUrl, quantity = 1) {
    try {
        const sessionRes  = await fetch(CART_API, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'getProfile' })
        });
        const sessionData = await sessionRes.json();

        if (sessionData.status) {
            // Logged in — add to DB
            const res  = await fetch(CART_API, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action:    'addToDatabaseCart',
                    productId: productId,
                    quantity:  quantity
                })
            });
            const data = await res.json();
            await updateCartIndicator(); // refresh badge
            return data;

        } else {
            // Guest — save to localStorage
            const cart     = getLocalCart();
            const existing = cart.find(i => i.product_id == productId);

            if (existing) {
                existing.quantity += quantity;
            } else {
                cart.push({
                    product_id:   productId,
                    product_name: productName,
                    price:        price,
                    image_url:    imageUrl,
                    quantity:     quantity
                });
            }

            saveLocalCart(cart); // also calls updateCartIndicator
            return { status: true, message: 'Added to cart!' };
        }

    } catch (err) {
        console.error('addToCart error:', err);
        return { status: false, message: 'Something went wrong.' };
    }
}

// ── Run on every page load ────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', updateCartIndicator);
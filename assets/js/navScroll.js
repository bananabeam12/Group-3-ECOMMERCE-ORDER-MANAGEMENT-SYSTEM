const navbar = document.getElementById('navbar');
const logo = document.getElementById('nav-logo');
const icons = document.getElementById('nav-icons');
const indicator = document.getElementById('cart-indicator');

window.onscroll = function () {
    if (window.pageYOffset > 50) {
        // SCROLLED: White background, Black text
        navbar.classList.add("bg-white", "py-3", "text-black");
        navbar.classList.remove("py-6", "text-white");

        icons.classList.remove("text-white");
        icons.classList.add("text-black");

        indicator.classList.remove("bg-white");
        indicator.classList.add("bg-brand-green");
    } else {
        // TOP: Transparent background, White text
        navbar.classList.add("py-6", "text-white");
        navbar.classList.remove("bg-white", "py-3", "text-black");

        icons.classList.remove("text-black");
        icons.classList.add("text-white");

        indicator.classList.add("bg-white");
        indicator.classList.remove("bg-brand-green");
    }
};
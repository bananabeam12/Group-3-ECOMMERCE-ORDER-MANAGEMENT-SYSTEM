document.addEventListener('DOMContentLoaded', function () {
    const loginBtn = document.getElementById("logInBtn");
    if (loginBtn) {
        loginBtn.addEventListener("click", validateLogIn);
    }

    const regBtn = document.getElementById("registerBtn");
    if (regBtn) {
        regBtn.addEventListener("click", validateRegister);
    }

    document.querySelectorAll('.form-control').forEach(function (input) {
        input.addEventListener('input', function () {
            clearError(this.id);
        });
    });
});

/**
 * 1. Login Logic (.then style)
 */
function validateLogIn() {
    let email = document.getElementById("logInEmail").value.trim();
    let password = document.getElementById("logInPassword").value;

    if (email === "" || password === "") {
        showToast("Please fill in all fields.");
        return;
    }

    let data = { mail: email, pass: password, action: "loginAccount" };

    // Leader's Syntax: Call the function and handle results in a chain
    sendRequest(data).then(message => {
        if (message.status) {
            localStorage.setItem("currentUser", JSON.stringify(message.user));
            showToast("Login successful! Redirecting...", "success");

            setTimeout(() => {
                if (message.user && (message.user.role === 'admin' || message.user.accountType === 'admin')) {
                    window.location.href = 'pages/inventory.php';
                } else {
                    window.location.href = 'pages/shop.php';
                }
            }, 2000);
        } else {
            showToast(message.message || "Invalid email or password.");
        }
    });
}

/**
 * 2. Registration Logic (.then style)
 */
function validateRegister() {
    let firstName = document.getElementById("registerFirstName").value.trim();
    let lastName = document.getElementById("registerLastName").value.trim();
    let email = document.getElementById("registerEmail").value.trim();
    let password = document.getElementById("registerPassword").value;
    let confirmPassword = document.getElementById("registerConfirmPassword").value;
    
    // Additional fields
    let phone = document.getElementById("registerPhone").value.trim();
    let address = document.getElementById("registerAddress").value.trim();
    let city = document.getElementById("registerCity").value.trim();
    let province = document.getElementById("registerProvince").value.trim();
    let zip = document.getElementById("registerZip").value.trim();
    let country = document.getElementById("registerCountry").value.trim();

    if (!firstName || !lastName || !email || !password) {
        showToast("Basic fields are required.");
        return;
    }

    if (password !== confirmPassword) {
        showToast("Passwords do not match.");
        return;
    }

    let data = { 
        action: "registerAccount",
        firstName, lastName, email, password,
        phone, address, city, province, zip, country 
    };

    sendRequest(data).then(message => {
        if (message.status) {
            showToast("Registration successful! You can now log in.", "success");
            setTimeout(() => {
                window.location.href = '../../index.php';
            }, 2000);
        } else {
            showToast(message.message || "Registration failed.");
        }
    });
}

/**
 * 3. Core Request Helper (The "Suitcase")
 */
function sendRequest(data) {
    // Leader's Syntax: return the fetch promise
    return fetch("../../../api.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .catch(error => {
        console.error("Request failed:", error);
        return { status: false, message: "Network error. Please try again." };
    });
}

// UI Helpers
function showToast(message, type = "error") {
    const toastEl = document.getElementById("toastNotification");
    const toastMsg = document.getElementById("toastMessage");
    if (!toastEl) return;
    toastMsg.textContent = message;
    toastEl.classList.remove("bg-danger", "bg-success");
    toastEl.classList.add(type === "success" ? "bg-success" : "bg-danger");
    new bootstrap.Toast(toastEl, { delay: 3000 }).show();
}

function clearError(elementId) { 
    const el = document.getElementById(elementId);
    if(el) el.classList.remove("input-error"); 
}
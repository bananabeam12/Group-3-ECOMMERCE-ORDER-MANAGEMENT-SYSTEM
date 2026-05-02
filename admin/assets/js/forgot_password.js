// Variable to store email between Step 1 and Step 2
let verifiedEmail = "";

document.addEventListener("DOMContentLoaded", function () {

    // 1. Step 1 — Verify Email Logic
    const verifyBtn = document.getElementById("verifyEmailBtn");
    if (verifyBtn) {
        verifyBtn.addEventListener("click", function () {
            const emailInput = document.getElementById("forgotEmail");
            if (!emailInput) return;

            const email = emailInput.value.trim();

            if (email === "") {
                showToast("Please enter your email address.");
                return;
            }

            if (!validateEmail(email)) {
                showToast("Please enter a valid email format.");
                return;
            }

            // Leader's Syntax: Call the promise-based request
            sendRequest({ action: "forgotPassword", email: email })
                .then(response => {
                    if (response.status) {
                        verifiedEmail = email;
                        showStep(2); // Move to password entry
                        showToast("Email verified! Please enter your new password.", "success");
                    } else {
                        showToast(response.message || "Email not found.");
                    }
                });
        });
    }

    // 2. Step 2 — Reset Password Logic
    const resetBtn = document.getElementById("resetBtn");
    if (resetBtn) {
        resetBtn.addEventListener("click", function () {
            const newPassword = document.getElementById("newPassword").value;
            const confirmPassword = document.getElementById("confirmNewPassword").value;

            if (!newPassword || !confirmPassword) {
                showToast("Please fill in all fields.");
                return;
            }

            if (newPassword !== confirmPassword) {
                showToast("Passwords do not match.");
                return;
            }

            // Send the update to the server
            sendRequest({ 
                action: "resetPassword", 
                email: verifiedEmail, 
                newPass: newPassword 
            })
            .then(response => {
                if (response.status) {
                    showToast("Password updated! Redirecting to login...", "success");
                    setTimeout(() => {
                        window.location.href = "index.php";
                    }, 2500);
                } else {
                    showToast(response.message || "Failed to reset password.");
                }
            });
        });
    }
});

/**
 * Request Helper (Matches the style in validation.js)
 */
function sendRequest(data) {
    return fetch("../../api.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .catch(error => {
        console.error("Fetch error:", error);
        return { status: false, message: "Server connection failed." };
    });
}

// UI & Validation Helpers
function showStep(stepNumber) {
    document.getElementById("step1").style.display = (stepNumber === 1) ? "block" : "none";
    document.getElementById("step2").style.display = (stepNumber === 2) ? "block" : "none";
}

function showToast(message, type = "error") {
    const toastEl = document.getElementById("toastNotification");
    const toastMsg = document.getElementById("toastMessage");
    if (!toastEl) return;

    toastMsg.textContent = message;
    toastEl.classList.remove("bg-danger", "bg-success");
    toastEl.classList.add(type === "success" ? "bg-success" : "bg-danger");
    
    const bsToast = new bootstrap.Toast(toastEl);
    bsToast.show();
}

function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}
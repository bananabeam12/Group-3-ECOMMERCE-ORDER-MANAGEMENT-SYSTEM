<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SKRRT WORLDWIDE</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        body { background: #000; color: #fff; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .reset-box { background: #fff; color: #000; padding: 40px; border-radius: 0; width: 100%; max-width: 450px; border: 5px solid #ccff00; }
        h1 { font-weight: 900; letter-spacing: -1px; text-transform: uppercase; margin-bottom: 20px; }
        .form-control { border-radius: 0; border: 2px solid #000; padding: 12px; }
        .btn-skrrt { background: #000; color: #fff; border-radius: 0; font-weight: bold; width: 100%; padding: 12px; margin-top: 15px; border: none; }
        .btn-skrrt:hover { background: #ccff00; color: #000; }
        .step-indicator { font-size: 10px; font-weight: bold; color: #999; margin-bottom: 10px; display: block; }
    </style>
</head>
<body>

    <main class="reset-box">
        <div id="step1">
            <span class="step-indicator">STEP 01 / ACCOUNT RECOVERY</span>
            <h1>Lost your keys?</h1>
            <p style="font-size: 13px; color: #666;">Enter your registered email to verify your identity in our vault.</p>
            <input type="email" id="forgotEmail" class="form-control" placeholder="EMAIL ADDRESS">
            <button id="verifyEmailBtn" class="btn-skrrt">VERIFY ACCOUNT</button>
            <div style="text-align: center; margin-top: 20px;">
                <a href="../../index.php" style="color: #000; font-size: 12px; text-decoration: underline;">BACK TO LOGIN</a>
            </div>
        </div>

        <div id="step2" style="display:none;">
            <span class="step-indicator">STEP 02 / SECURE VAULT</span>
            <h1>New Access Code</h1>
            <div class="mb-3">
                <label style="font-size: 11px; font-weight: bold;">NEW PASSWORD</label>
                <input type="password" id="newPassword" class="form-control">
            </div>
            <div class="mb-3">
                <label style="font-size: 11px; font-weight: bold;">CONFIRM PASSWORD</label>
                <input type="password" id="confirmNewPassword" class="form-control">
            </div>
            <button id="resetBtn" class="btn-skrrt">UPDATE PASSWORD</button>
        </div>

        <div id="step3" style="display:none; text-align: center;">
            <span class="step-indicator">COMPLETE</span>
            <h1>VAULT UPDATED</h1>
            <p>Your password has been successfully reset. Redirecting you to login...</p>
            <a href="../../index.php" class="btn-skrrt" style="display: block; text-decoration: none;">LOGIN NOW</a>
        </div>
    </main>

    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="toastNotification" class="toast align-items-center text-white border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script>
        let verifiedEmail = "";

        document.addEventListener("DOMContentLoaded", function () {
            
            // 1. Logic for Step 1
            document.getElementById("verifyEmailBtn").addEventListener("click", function() {
                const email = document.getElementById("forgotEmail").value.trim();
                if (!email) return showToast("Please enter your email.");

                // Leader Style: .then() chain
                fetch("../../api.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ action: "forgotPassword", email: email })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        verifiedEmail = email; // Lock in the email
                        document.getElementById("step1").style.display = "none";
                        document.getElementById("step2").style.display = "block";
                        showToast("Email verified!", "success");
                    } else {
                        showToast(data.message || "Account not found.");
                    }
                })
                .catch(err => showToast("Vault connection failed."));
            });

            // 2. Logic for Step 2
            document.getElementById("resetBtn").addEventListener("click", function() {
                const pass = document.getElementById("newPassword").value;
                const confirm = document.getElementById("confirmNewPassword").value;

                if (pass !== confirm) return showToast("Passwords do not match.");
                if (pass.length < 6) return showToast("Password is too short.");

                // Leader Style: .then() chain
                fetch("../../api.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ 
                        action: "resetPassword", 
                        email: verifiedEmail, 
                        newPass: pass 
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        document.getElementById("step2").style.display = "none";
                        document.getElementById("step3").style.display = "block";
                        setTimeout(() => { window.location.href = "../../index.php"; }, 3000);
                    } else {
                        showToast(data.message);
                    }
                });
            });
        });

        function showToast(msg, type = "error") {
            const toastEl = document.getElementById("toastNotification");
            document.getElementById("toastMessage").innerText = msg;
            toastEl.classList.remove("bg-danger", "bg-success");
            toastEl.classList.add(type === "success" ? "bg-success" : "bg-danger");
            new bootstrap.Toast(toastEl).show();
        }
    </script>
    <script src="../assets/js/stylingAdmin.js"></script>
</body>
</html>
<?php
session_start();
// Redirect logged-in users away from the forgot password page
if (!empty($_SESSION['user_id']) && !empty($_SESSION['user_role'])) {
    header($_SESSION['user_role'] === 'admin'
        ? 'Location: ../admin/pages/dashboard.php'
        : 'Location: ../customer.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SKRRT WORLDWIDE — Reset Password</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/vendor/fontawesome/css/all.min.css">
    <style>
        @keyframes fadeUp  { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
        @keyframes slideIn { from { opacity:0; transform:translateX(14px); } to { opacity:1; transform:none; } }
        @keyframes spin    { to   { transform: rotate(360deg); } }

        .anim-fade-up  { animation: fadeUp  .45s ease both; }
        .anim-slide-in { animation: slideIn .25s ease both; }

        .spinner {
            display: inline-block;
            width: 13px; height: 13px;
            border: 2px solid rgba(255,255,255,.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .6s linear infinite;
            vertical-align: middle;
            margin-right: 6px;
        }

        .grid-bg {
            background-image:
                linear-gradient(rgba(0,0,0,.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,0,0,.06) 1px, transparent 1px);
            background-size: 36px 36px;
        }

        .step { display: none; }
        .step.active { display: block; }
    </style>
</head>
<body class="bg-white text-black h-screen overflow-hidden">

<div class="flex h-screen">

    <!-- ══ LEFT — BRAND PANEL ══ -->
    <div class="hidden lg:flex w-1/2 bg-[#A6F000] grid-bg flex-col justify-between p-14 relative overflow-hidden">
        <p class="text-[10px] font-bold uppercase tracking-[.22em] text-black/50 relative z-10">Account Recovery</p>

        <div class="relative z-10 anim-fade-up">
            <img src="../assets/images/Skrrt_logo-Full.png" alt="Skrrt" class="w-100 h-auto mb-5 opacity-100">
            <h1 class="font-black text-[5.5rem] leading-[.88] tracking-tight uppercase text-black select-none">
                RESET<br>VAULT
            </h1>
        </div>

        <div class="relative z-10">
            <p class="text-[11px] font-medium text-black/40 tracking-wide">© 2026 Skrrt Worldwide</p>
            <p class="text-[11px] font-medium text-black/40 mt-1">Always on the move.</p>
        </div>
    </div>

    <!-- ══ RIGHT — FORM PANEL ══ -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-8 md:px-16 py-10 overflow-y-auto bg-white">
        <div class="w-full max-w-sm anim-fade-up">

            <!-- Mobile logo -->
            <div class="flex items-center gap-3 mb-8 lg:hidden">
                <img src="../assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="w-8 h-auto">
                <span class="font-black text-base uppercase tracking-widest">Skrrt Worldwide</span>
            </div>

            <!-- ── STEP INDICATOR ── -->
            <div class="flex items-center gap-2 mb-8">
                <div id="dot1" class="w-2 h-2 rounded-full bg-black transition-colors"></div>
                <div id="dot2" class="w-2 h-2 rounded-full bg-black/20 transition-colors"></div>
                <div id="dot3" class="w-2 h-2 rounded-full bg-black/20 transition-colors"></div>
                <span id="stepLabel" class="text-[10px] font-bold uppercase tracking-widest text-gray-400 ml-1">Step 1 of 3</span>
            </div>

            <!-- ── STEP 1: Verify Email ── -->
            <div id="step1" class="step active">
                <h2 class="font-black text-4xl uppercase tracking-tight leading-none mb-1">Forgot<br>Password?</h2>
                <p class="text-xs text-gray-400 font-medium mb-8">Enter your registered email and we'll verify your account.</p>

                <div class="mb-4">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">Email Address</label>
                    <input type="email" id="forgotEmail" placeholder="you@skrrt.com"
                           class="w-full px-4 py-3 border border-black/15 bg-gray-50 text-sm font-medium placeholder-black/25 focus:outline-none focus:border-black focus:bg-white transition-colors rounded-none">
                </div>

                <button type="button" id="verifyBtn" onclick="handleVerify()"
                        class="w-full py-4 bg-black text-white text-[11px] font-black uppercase tracking-[.18em] hover:opacity-80 transition-colors disabled:opacity-40 disabled:cursor-not-allowed mt-2">
                    Verify Account
                </button>

                <div class="mt-6 text-center">
                    <a href="../index.php" class="text-xs font-medium text-gray-400 hover:text-black transition-colors">← Back to Login</a>
                </div>
            </div>

            <!-- ── STEP 2: New Password ── -->
            <div id="step2" class="step">
                <h2 class="font-black text-4xl uppercase tracking-tight leading-none mb-1">New<br>Password.</h2>
                <p class="text-xs text-gray-400 font-medium mb-8">Choose a strong new password for your account.</p>

                <div class="mb-4">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">New Password</label>
                    <div class="flex">
                        <input type="password" id="newPassword" placeholder="••••••••"
                               class="flex-1 px-4 py-3 border border-black/15 border-r-0 bg-gray-50 text-sm font-medium placeholder-black/25 focus:outline-none focus:border-black focus:bg-white transition-colors rounded-none">
                        <button type="button" onclick="togglePassword('newPassword', this)"
                                class="px-3.5 border border-black/15 bg-gray-100 text-gray-400 hover:text-black hover:bg-gray-200 transition-colors flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 pointer-events-none">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">Confirm Password</label>
                    <div class="flex">
                        <input type="password" id="confirmNewPassword" placeholder="••••••••"
                               class="flex-1 px-4 py-3 border border-black/15 border-r-0 bg-gray-50 text-sm font-medium placeholder-black/25 focus:outline-none focus:border-black focus:bg-white transition-colors rounded-none">
                        <button type="button" onclick="togglePassword('confirmNewPassword', this)"
                                class="px-3.5 border border-black/15 bg-gray-100 text-gray-400 hover:text-black hover:bg-gray-200 transition-colors flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 pointer-events-none">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="button" id="resetBtn" onclick="handleReset()"
                        class="w-full py-4 bg-black text-white text-[11px] font-black uppercase tracking-[.18em] hover:opacity-80 transition-colors disabled:opacity-40 disabled:cursor-not-allowed mt-2">
                    Update Password
                </button>
            </div>

            
            <div id="step3" class="step">
                <div class="mb-6">
                    <div class="w-14 h-14 bg-[#A6F000] rounded-full flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-7 h-7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </div>
                    <h2 class="font-black text-4xl uppercase tracking-tight leading-none mb-1">Password<br>Updated.</h2>
                    <p class="text-xs text-gray-400 font-medium mt-4">Your password has been reset successfully. Redirecting you to login...</p>
                </div>

                <a href="../index.php"
                   class="block w-full py-4 bg-black text-white text-[11px] font-black uppercase tracking-[.18em] hover:opacity-80 transition-colors text-center mt-6">
                    Back to Login
                </a>
            </div>

        </div>
    </div>
</div>

<!-- Toast -->
<div id="toastWrap" class="fixed top-6 right-6 z-50 flex flex-col gap-2"></div>

<script>
    // ── State ──
    let verifiedEmail = '';

    // ── Step navigation ──
    function goToStep(n) {
        document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
        document.getElementById('step' + n).classList.add('active');

        // Update dots
        ['dot1','dot2','dot3'].forEach((id, i) => {
            document.getElementById(id).className =
                `w-2 h-2 rounded-full transition-colors ${i < n ? 'bg-black' : 'bg-black/20'}`;
        });

        document.getElementById('stepLabel').textContent = `Step ${n} of 3`;
    }

    // ── Toggle password visibility ──
    function togglePassword(inputId, btn) {
        const input  = document.getElementById(inputId);
        const isPass = input.type === 'password';
        input.type   = isPass ? 'text' : 'password';
        btn.querySelector('svg').innerHTML = isPass
            ? '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/>'
            : '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>';
    }

    // ── Toast ──
    function showToast(message, type = 'success') {
        const wrap = document.getElementById('toastWrap');
        const div  = document.createElement('div');
        div.className = `anim-slide-in bg-black text-white text-xs font-semibold px-4 py-3 min-w-[220px] border-l-4 ${type === 'success' ? 'border-[#A6F000]' : 'border-red-500'}`;
        div.textContent = message;
        wrap.appendChild(div);
        setTimeout(() => div.remove(), 3500);
    }

    // ── Loading state ──
    function setLoading(btnId, loading, defaultText) {
        const btn    = document.getElementById(btnId);
        btn.disabled = loading;
        btn.innerHTML = loading ? `<span class="spinner"></span>Loading…` : defaultText;
    }

    // ── STEP 1: Verify Email → calls verifyEmail action ──
    async function handleVerify() {
        const email = document.getElementById('forgotEmail').value.trim();
        if (!email) { showToast('Please enter your email address.', 'error'); return; }

        setLoading('verifyBtn', true, 'Verify Account');
        try {
            const res  = await fetch('../api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'verifyEmail', email })
            });
            const data = await res.json();

            if (data.status) {
                verifiedEmail = email;
                showToast('Account found! Set your new password.');
                goToStep(2);
            } else {
                showToast(data.message || 'Email not registered.', 'error');
            }
        } catch (err) {
            showToast('Server error. Please try again.', 'error');
        } finally {
            setLoading('verifyBtn', false, 'Verify Account');
        }
    }

    // ── STEP 2: Reset Password → calls resetPassword action ──
    async function handleReset() {
        const newPass     = document.getElementById('newPassword').value;
        const confirmPass = document.getElementById('confirmNewPassword').value;

        if (!newPass || !confirmPass) { showToast('Please fill in both fields.', 'error'); return; }
        if (newPass !== confirmPass)  { showToast('Passwords do not match.', 'error'); return; }
        if (newPass.length < 6)       { showToast('Password must be at least 6 characters.', 'error'); return; }

        setLoading('resetBtn', true, 'Update Password');
        try {
            const res  = await fetch('../api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'resetPassword', email: verifiedEmail, newPass })
            });
            const data = await res.json();

            if (data.status) {
                goToStep(3);
                // Auto-redirect after 3 seconds
                setTimeout(() => { window.location.href = '../index.php'; }, 3000);
            } else {
                showToast(data.message || 'Failed to reset password.', 'error');
            }
        } catch (err) {
            showToast('Server error. Please try again.', 'error');
        } finally {
            setLoading('resetBtn', false, 'Update Password');
        }
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            const active = document.querySelector('.step.active');
            if (active && active.id === 'step1') handleVerify();
            if (active && active.id === 'step2') handleReset();
        }
    });
</script>
</body>
</html>
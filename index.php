<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ── ?clear=1 wipes stale session (use this if you get stuck redirecting) ──
if (isset($_GET['clear'])) {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $p["path"], $p["domain"], $p["secure"], $p["httponly"]);
    }
    session_destroy();
    header("Location: index.php");
    exit();
}

/**
 * SKRRT WORLDWIDE - ENTRY VAULT
 * Only redirect if a real login session exists (set by api.php loginAccount).
 */
if (
    !empty($_SESSION['user_id']) &&
    !empty($_SESSION['user_role']) &&
    in_array($_SESSION['user_role'], ['admin', 'customer'])
) {
    if ($_SESSION['user_role'] === 'admin') {
        header("Location: admin/pages/inventory.php");
    } else {
        header("Location: customer.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SKRRT WORLDWIDE — SIGN IN</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/vendor/fontawesome/css/all.min.css">
    <style>
        @keyframes fadeUp  { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
        @keyframes slideIn { from { opacity:0; transform:translateX(14px); } to { opacity:1; transform:none; } }
        @keyframes spin    { to   { transform: rotate(360deg); } }

        .anim-fade-up  { animation: fadeUp  .45s ease both; }
        .anim-slide-in { animation: slideIn .25s ease both; }

        .register-scroll::-webkit-scrollbar       { width: 3px; }
        .register-scroll::-webkit-scrollbar-thumb { background: black; border-radius: 2px; }

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

        /* subtle dot grid on green panel */
        .grid-bg {
            background-image:
                linear-gradient(rgba(0,0,0,.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,0,0,.06) 1px, transparent 1px);
            background-size: 36px 36px;
        }
    </style>
</head>
<body class="bg-white text-black h-screen overflow-hidden">

<div class="flex h-screen">

    <!-- ══ LEFT — BRAND PANEL ══ -->
    <div class="hidden lg:flex w-1/2 bg-[#A6F000] grid-bg flex-col justify-between p-14 relative overflow-hidden">
        <p class="text-[10px] font-bold uppercase tracking-[.22em] text-black/50 relative z-10">Streetwear Apparel</p>

        <div class="relative z-10 anim-fade-up">
            <img src="assets/images/Skrrt_logo-Full.png" alt="Skrrt" class="w-100 h-auto mb-5 opacity-100">
            <h1 class="font-black text-[5.5rem] leading-[.88] tracking-tight uppercase text-black select-none">
                WORLD<br>WIDE
            </h1>
        </div>

        <div class="relative z-10">
            <p class="text-[11px] font-medium text-black/40 tracking-wide">© 2026 Skrrt Worldwide</p>
            <p class="text-[11px] font-medium text-black/40 mt-1">Always on the move.</p>
        </div>
    </div>

    <!-- ══ RIGHT — FORM PANEL ══ -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-8 md:px-16 py-10 overflow-y-auto bg-white">
        <div class="w-full max-w-sm">

            <!-- ─── LOGIN ─── -->
            <div id="loginSection" class="anim-fade-up">

                <!-- Mobile-only logo strip -->
                <div class="flex items-center gap-3 mb-8 lg:hidden">
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="w-8 h-auto">
                    <span class="font-black text-base uppercase tracking-widest">Skrrt Worldwide</span>
                </div>

                <h2 class="font-black text-5xl uppercase tracking-tight leading-none mb-1">Sign In.</h2>
                <p class="text-xs text-gray-400 font-medium mb-8">
                    No account?
                    <span class="text-black font-bold underline underline-offset-2 cursor-pointer hover:opacity-60 transition-opacity"
                          onclick="toggleForm('register')">Create one</span>
                </p>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">Email</label>
                    <input type="email" id="logInEmail" placeholder="you@skrrt.com"
                           class="w-full px-4 py-3 border border-black/15 bg-gray-50 text-sm font-medium placeholder-black/25 focus:outline-none focus:border-black focus:bg-white transition-colors rounded-none">
                </div>

                <!-- Password -->
                <div class="mb-2">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">Password</label>
                    <div class="flex">
                        <input type="password" id="logInPassword" placeholder="••••••••"
                               class="flex-1 px-4 py-3 border border-black/15 border-r-0 bg-gray-50 text-sm font-medium placeholder-black/25 focus:outline-none focus:border-black focus:bg-white transition-colors rounded-none">
                        <button type="button" onclick="togglePassword('logInPassword', this)"
                                class="px-3.5 border border-black/15 bg-gray-100 text-gray-400 hover:text-black hover:bg-gray-200 transition-colors flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 pointer-events-none">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </button>
                    </div>
                    <a href="pages/forgot_password.php"
                       class="block text-right mt-1.5 text-[11px] text-gray-300 hover:text-black transition-colors font-medium">
                        Forgot password?
                    </a>
                </div>

                <button type="button" id="logInBtn" onclick="handleLogin()"
                        class="w-full mt-5 py-4 bg-black text-white text-[11px] font-black uppercase tracking-[.18em] hover:opacity-80 transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                    Sign In
                </button>
            </div>

            <!-- ─── REGISTER ─── -->
            <div id="registerSection" class="anim-fade-up" style="display:none;">

                <!-- Mobile-only logo strip -->
                <div class="flex items-center gap-3 mb-8 lg:hidden">
                    <img src="assets/images/Skrrt_logo-Alt.png" alt="Skrrt" class="w-8 h-auto">
                    <span class="font-black text-base uppercase tracking-widest">Skrrt Worldwide</span>
                </div>

                <h2 class="font-black text-5xl uppercase tracking-tight leading-none mb-1">Join Up.</h2>
                <p class="text-xs text-gray-400 font-medium mb-6">
                    Already a member?
                    <span class="text-black font-bold underline underline-offset-2 cursor-pointer hover:opacity-60 transition-opacity"
                          onclick="toggleForm('login')">Sign in</span>
                </p>

                <div class="register-scroll max-h-[62vh] overflow-y-auto pr-1 space-y-4">

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">First Name</label>
                            <input type="text" id="registerFirstName"
                                   class="w-full px-4 py-3 border border-black/15 bg-gray-50 text-sm font-medium focus:outline-none focus:border-black focus:bg-white transition-colors rounded-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">Last Name</label>
                            <input type="text" id="registerLastName"
                                   class="w-full px-4 py-3 border border-black/15 bg-gray-50 text-sm font-medium focus:outline-none focus:border-black focus:bg-white transition-colors rounded-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">Email Address</label>
                        <input type="email" id="registerEmail"
                               class="w-full px-4 py-3 border border-black/15 bg-gray-50 text-sm font-medium focus:outline-none focus:border-black focus:bg-white transition-colors rounded-none">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">Phone Number</label>
                        <input type="text" id="registerPhone" placeholder="09XXXXXXXXX"
                               class="w-full px-4 py-3 border border-black/15 bg-gray-50 text-sm font-medium placeholder-black/25 focus:outline-none focus:border-black focus:bg-white transition-colors rounded-none">
                    </div>

                    <!-- Divider -->
                    <div class="flex items-center gap-3 py-1">
                        <div class="flex-1 h-px bg-black/10"></div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-300">Shipping Address</span>
                        <div class="flex-1 h-px bg-black/10"></div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">Street / House No.</label>
                        <textarea id="registerAddress" rows="2"
                                  class="w-full px-4 py-3 border border-black/15 bg-gray-50 text-sm font-medium focus:outline-none focus:border-black focus:bg-white transition-colors rounded-none resize-none"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">City</label>
                            <input type="text" id="registerCity"
                                   class="w-full px-4 py-3 border border-black/15 bg-gray-50 text-sm font-medium focus:outline-none focus:border-black focus:bg-white transition-colors rounded-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">Province</label>
                            <input type="text" id="registerProvince"
                                   class="w-full px-4 py-3 border border-black/15 bg-gray-50 text-sm font-medium focus:outline-none focus:border-black focus:bg-white transition-colors rounded-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">ZIP Code</label>
                            <input type="text" id="registerZip"
                                   class="w-full px-4 py-3 border border-black/15 bg-gray-50 text-sm font-medium focus:outline-none focus:border-black focus:bg-white transition-colors rounded-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">Country</label>
                            <input type="text" id="registerCountry" value="Philippines"
                                   class="w-full px-4 py-3 border border-black/15 bg-gray-50 text-sm font-medium focus:outline-none focus:border-black focus:bg-white transition-colors rounded-none">
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="flex items-center gap-3 py-1">
                        <div class="flex-1 h-px bg-black/10"></div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-300">Security</span>
                        <div class="flex-1 h-px bg-black/10"></div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">Password</label>
                        <div class="flex">
                            <input type="password" id="registerPassword"
                                   class="flex-1 px-4 py-3 border border-black/15 border-r-0 bg-gray-50 text-sm font-medium focus:outline-none focus:border-black focus:bg-white transition-colors rounded-none">
                            <button type="button" onclick="togglePassword('registerPassword', this)"
                                    class="px-3.5 border border-black/15 bg-gray-100 text-gray-400 hover:text-black hover:bg-gray-200 transition-colors flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 pointer-events-none">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">Confirm Password</label>
                        <div class="flex">
                            <input type="password" id="registerConfirmPassword"
                                   class="flex-1 px-4 py-3 border border-black/15 border-r-0 bg-gray-50 text-sm font-medium focus:outline-none focus:border-black focus:bg-white transition-colors rounded-none">
                            <button type="button" onclick="togglePassword('registerConfirmPassword', this)"
                                    class="px-3.5 border border-black/15 bg-gray-100 text-gray-400 hover:text-black hover:bg-gray-200 transition-colors flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 pointer-events-none">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="button" id="registerBtn" onclick="handleRegister()"
                            class="w-full py-4 bg-black text-white text-[11px] font-black uppercase tracking-[.18em] hover:bg-[#A6F000] hover:text-black transition-colors disabled:opacity-40 disabled:cursor-not-allowed mb-6">
                        Complete Registration
                    </button>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- Toast -->
<div id="toastWrap" class="fixed top-6 right-6 z-50 flex flex-col gap-2"></div>

<script>
function toggleForm(show) {
    document.getElementById('loginSection').style.display    = show === 'login'    ? 'block' : 'none';
    document.getElementById('registerSection').style.display = show === 'register' ? 'block' : 'none';
}

function togglePassword(inputId, btn) {
    const input  = document.getElementById(inputId);
    const isPass = input.type === 'password';
    input.type   = isPass ? 'text' : 'password';
    btn.querySelector('svg').innerHTML = isPass
        ? '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/>'
        : '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>';
}

function showToast(message, type = 'success') {
    const wrap = document.getElementById('toastWrap');
    const div  = document.createElement('div');
    div.className = `anim-slide-in bg-black text-white text-xs font-semibold px-4 py-3 min-w-[220px] border-l-4 ${type === 'success' ? 'border-[#A6F000]' : 'border-red-500'}`;
    div.textContent = message;
    wrap.appendChild(div);
    setTimeout(() => div.remove(), 3500);
}

function setLoading(btnId, loading, defaultText) {
    const btn  = document.getElementById(btnId);
    btn.disabled = loading;
    btn.innerHTML = loading ? `<span class="spinner"></span>Loading…` : defaultText;
}

async function handleLogin() {
    const email    = document.getElementById('logInEmail').value.trim();
    const password = document.getElementById('logInPassword').value;
    if (!email || !password) { showToast('Please fill in all fields.', 'error'); return; }

    setLoading('logInBtn', true, 'Sign In');
    try {
        const res  = await fetch('api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'loginAccount', email, password })
        });
        const data = await res.json();
        if (data.status) {
            showToast(`Welcome back, ${data.user.firstName}! 🔥`);
            setTimeout(() => {
                window.location.href = data.role === 'admin' ? 'admin/pages/dashboard.php' : 'customer.php';
            }, 800);
        } else {
            showToast(data.message || 'Login failed.', 'error');
            setLoading('logInBtn', false, 'Sign In');
        }
    } catch (err) {
        showToast('Server error. Please try again.', 'error');
        setLoading('logInBtn', false, 'Sign In');
    }
}

async function handleRegister() {
    const firstName       = document.getElementById('registerFirstName').value.trim();
    const lastName        = document.getElementById('registerLastName').value.trim();
    const email           = document.getElementById('registerEmail').value.trim();
    const phone           = document.getElementById('registerPhone').value.trim();
    const address         = document.getElementById('registerAddress').value.trim();
    const city            = document.getElementById('registerCity').value.trim();
    const province        = document.getElementById('registerProvince').value.trim();
    const zip             = document.getElementById('registerZip').value.trim();
    const country         = document.getElementById('registerCountry').value.trim();
    const password        = document.getElementById('registerPassword').value;
    const confirmPassword = document.getElementById('registerConfirmPassword').value;

    if (!firstName || !lastName || !email || !phone || !address || !city || !province || !zip || !password) {
        showToast('Please fill in all required fields.', 'error'); return;
    }
    if (password !== confirmPassword) { showToast('Passwords do not match.', 'error'); return; }
    if (password.length < 6) { showToast('Password must be at least 6 characters.', 'error'); return; }

    setLoading('registerBtn', true, 'Complete Registration');
    try {
        const res  = await fetch('api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'registerAccount', firstName, lastName, email, phone, address, city, province, zip, country, password })
        });
        const data = await res.json();
        if (data.status) {
            showToast('Account created! Please sign in. 🎉');
            setTimeout(() => toggleForm('login'), 1200);
        } else {
            showToast(data.message || 'Registration failed.', 'error');
        }
    } catch (err) {
        showToast('Server error. Please try again.', 'error');
    } finally {
        setLoading('registerBtn', false, 'Complete Registration');
    }
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && document.getElementById('loginSection').style.display !== 'none') handleLogin();
});
</script>
</body>
</html>
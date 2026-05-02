<?php
session_start();
// Security Check: Kick out if not logged in
if (empty($_SESSION['user_id']) || empty($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en" data-theme="cupcake">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - SKRRT</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" /> 
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> 
</head>

<body class="bg-base-200 min-h-screen">

    <nav id="main-nav"></nav>

    <a href="dashboard.php" class="btn btn-sm  bg-gray absolute top-4 right-4 z-40"> <- Back</a>
   <button class="btn btn-sm bg-gray absolute top-4 right-20 z-40" onclick="logout()">Log Out</button>
    <main class="p-8 max-w-5xl mx-auto pt-28">
        <div class="flex items-center gap-4 mb-8">
            <h1 class="text-3xl font-bold text-gray-800">My Profile</h1>
            <div id="display-role-badge" class="badge bg-black text-white font-semibold uppercase tracking-widest text-[10px]">Loading...</div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="card bg-base-100 shadow-sm p-8 flex flex-col items-center text-center">
                <div class="avatar mb-4">
                    <div class="w-32 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                        <img src="../assets/images/user.png" onerror="this.src='../assets/images/default-placeholder.jpg'" alt="Profile Picture" />
                    </div>
                </div>
                <h2 id="display-full-name" class="text-xl font-bold italic tracking-tight">Syncing...</h2>
                <p id="display-email-sub" class="text-sm text-gray-500 mb-6 font-medium">Please wait</p>
                <button class="btn bg-black text-white btn-block" onclick="document.getElementById('editProfileModal').showModal()">
                    Edit Profile
                </button>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="card bg-base-100 shadow-sm p-8">
                    <h3 class="text-lg font-bold border-b pb-4 mb-6 text-black uppercase tracking-tighter">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">First Name</p>
                            <p id="info-fname" class="font-medium text-gray-700">---</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Last Name</p>
                            <p id="info-lname" class="font-medium text-gray-700">---</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Email Address</p>
                            <p id="info-email" class="font-medium text-gray-700">---</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Phone Number</p>
                            <p id="info-phone" class="font-medium text-gray-700">---</p>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-sm p-8">
                    <div class="flex justify-between items-center border-b pb-4 mb-6">
                        <h3 class="text-lg font-bold text-black uppercase tracking-tighter">Primary Shipping Address</h3>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Street / Building</p>
                            <p id="info-address" class="font-medium text-gray-700">---</p>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">City</p>
                                <p id="info-city" class="font-medium text-gray-700">---</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Province</p>
                                <p id="info-province" class="font-medium text-gray-700">---</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Zip Code</p>
                                <p id="info-zip" class="font-medium text-gray-700">---</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Country</p>
                                <p id="info-country" class="font-medium text-gray-700">---</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
   
    <dialog id="editProfileModal" class="modal">
        
            
        <div class="modal-box max-w-xl bg-white relative">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
            </form>
            <h3 class="font-bold text-xl mb-6 text-gray-800 uppercase tracking-widest">Account Settings</h3>
            
            <form id="profileForm" class="space-y-4">
                <input type="hidden" id="profAddressId">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-[10px] uppercase text-gray-500 tracking-widest">First Name</span></label>
                        <input type="text" id="profFirstName" class="input input-bordered w-full bg-gray-50" required />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-[10px] uppercase text-gray-500 tracking-widest">Last Name</span></label>
                        <input type="text" id="profLastName" class="input input-bordered w-full bg-gray-50" required />
                    </div>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-[10px] uppercase text-gray-500 tracking-widest">Phone Number</span></label>
                    <input type="text" id="profPhone" class="input input-bordered w-full bg-gray-50" required />
                </div>

                <div class="divider text-xs text-gray-400 font-bold uppercase tracking-widest mt-6">Shipping Address</div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-[10px] uppercase text-gray-500 tracking-widest">Street / Building</span></label>
                    <textarea id="profAddress" class="textarea textarea-bordered h-20 bg-gray-50" required></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-[10px] uppercase text-gray-500 tracking-widest">City</span></label>
                        <input type="text" id="profCity" class="input input-bordered w-full bg-gray-50" required />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-[10px] uppercase text-gray-500 tracking-widest">Province</span></label>
                        <input type="text" id="profProvince" class="input input-bordered w-full bg-gray-50" required />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-[10px] uppercase text-gray-500 tracking-widest">Zip Code</span></label>
                        <input type="text" id="profZip" class="input input-bordered w-full bg-gray-50" required />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-[10px] uppercase text-gray-500 tracking-widest">Country</span></label>
                        <input type="text" id="profCountry" class="input input-bordered w-full bg-gray-50" required />
                    </div>
                </div>

                <div class="mt-2 text-right">
                    <button type="button" onclick="clearAddressForm()" class="text-[10px] font-bold text-blue-500 hover:text-blue-700 uppercase tracking-widest cursor-pointer underline">
                        + Add New Address Instead
                    </button>
                </div>
                
                <div class="modal-action mt-6">
                    <button type="submit" class="btn bg-black btn-block shadow-lg text-white font-bold tracking-widest">SAVE CHANGES</button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Populate role badge from PHP session (no localStorage dependency needed)
            fetch('../../api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'getSessionUser' })
            })
            .then(r => r.json())
            .then(data => {
                if (data.status) {
                    document.getElementById('display-role-badge').textContent = data.user.user_role.toUpperCase();
                }
            });

            loadProfileData();
        });

        // Fetch user data from backend
        function loadProfileData() {
            fetch('../../api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: "getProfile" })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        const user = data.user;
                        const addresses = data.addresses || [];

                        // 1. Update UI Text Displays
                        document.getElementById('display-full-name').textContent = `${user.first_name} ${user.last_name}`;
                        document.getElementById('display-email-sub').textContent = user.email || 'No email attached';
                        
                        document.getElementById('info-fname').textContent = user.first_name;
                        document.getElementById('info-lname').textContent = user.last_name;
                        document.getElementById('info-email').textContent = user.email || '---';
                        document.getElementById('info-phone').textContent = user.phone_number || '---';

                        // 2. Populate Edit Modal (Personal Info)
                        document.getElementById('profFirstName').value = user.first_name;
                        document.getElementById('profLastName').value = user.last_name;
                        document.getElementById('profPhone').value = user.phone_number || "";

                        // 3. Handle Address Data
                        if (addresses.length > 0) {
                            const primary = addresses[0]; // Get the first address
                            
                            // Display Address
                            document.getElementById('info-address').textContent = primary.address_description;
                            document.getElementById('info-city').textContent = primary.city;
                            document.getElementById('info-province').textContent = primary.province;
                            document.getElementById('info-zip').textContent = primary.zip_code;
                            document.getElementById('info-country').textContent = primary.country;

                            // Populate Edit Modal (Address)
                            document.getElementById('profAddressId').value = primary.address_id;
                            document.getElementById('profAddress').value = primary.address_description;
                            document.getElementById('profCity').value = primary.city;
                            document.getElementById('profProvince').value = primary.province;
                            document.getElementById('profZip').value = primary.zip_code;
                            document.getElementById('profCountry').value = primary.country;
                        } else {
                            document.getElementById('info-address').textContent = 'No address added yet.';
                        }
                    }
                })
                .catch(err => console.error("Profile load failed:", err));
        }

        // Handle Form Submission
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const updateData = {
                action: "updateProfile",
                addressId: document.getElementById('profAddressId').value,
                firstName: document.getElementById('profFirstName').value,
                lastName: document.getElementById('profLastName').value,
                phone: document.getElementById('profPhone').value,
                address: document.getElementById('profAddress').value,
                city: document.getElementById('profCity').value,
                province: document.getElementById('profProvince').value,
                zip: document.getElementById('profZip').value,
                country: document.getElementById('profCountry').value
            };

            fetch('../../api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(updateData)
                })
                .then(res => res.json())
                .then(result => {
                    if (result.status) {
                        alert("SKRRT! Profile updated successfully.");

                        // Sync LocalStorage so the header updates instantly
                        const currentUser = JSON.parse(localStorage.getItem('currentUser'));
                        if (currentUser) {
                            currentUser.firstName = updateData.firstName;
                            localStorage.setItem('currentUser', JSON.stringify(currentUser));
                        }

                        location.reload();
                    } else {
                        alert("Error: " + result.message);
                    }
                })
                .catch(err => alert("Connection to vault lost."));
        });
         
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
            window.location.href = '../../index.php';
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

// Header update handled via getSessionUser above

        function clearAddressForm() {
            // Clearing the ID forces the API to do an INSERT instead of an UPDATE
            document.getElementById('profAddressId').value = "";
            document.getElementById('profAddress').value = "";
            document.getElementById('profCity').value = "";
            document.getElementById('profProvince').value = "";
            document.getElementById('profZip').value = "";
            document.getElementById('profCountry').value = "";
            alert("Form cleared. You are now adding a new primary shipping destination.");
        }
    </script>
</body>
</html>
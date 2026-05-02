<?php
session_start();
// If user is already logged in, skip registration and go to shop
if (isset($_SESSION['user_id'])) {
    header("Location: ../../customer.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - SKRRT WORLDWIDE</title>
    <style>
        .register-container { max-width: 500px; margin: 50px auto; padding: 20px; border: 2px solid #000; }
        form input, form textarea { width: 100%; margin-bottom: 10px; padding: 8px; border: 1px solid #000; }
        button { background: #000; color: #fff; padding: 10px; width: 100%; cursor: pointer; border: none; }
        button:hover { background: #333; }
    </style>
     <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" /> 
     <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> 
</head>
<body>

<div class="register-container">
    <h2 style="letter-spacing: 2px; text-align: center;">JOIN THE SQUAD</h2>
    <form id="registerForm">
        <input type="text" id="firstName" placeholder="First Name" required>
        <input type="text" id="lastName" placeholder="Last Name" required>
        <input type="email" id="email" placeholder="Email" required>
        <input type="password" id="password" placeholder="Password" required>
        <input type="text" id="phone" placeholder="Phone Number" required>
        
        <hr>
        <p style="font-size: 12px; font-weight: bold;">SHIPPING DETAILS (3NF)</p>
        <textarea id="address" placeholder="Street Address / House No." required></textarea>
        <input type="text" id="city" placeholder="City" required>
        <input type="text" id="province" placeholder="Province" required>
        <input type="text" id="zip" placeholder="Zip Code" required>
        <input type="text" id="country" placeholder="Country" required>
        
        <button type="submit">REGISTER ACCOUNT</button>
    </form>
    <p style="text-align: center; margin-top: 15px;">
        Already a member? <a href="../../index.php" style="color: #000; font-weight: bold;">Login here</a>
    </p>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Package data to match the keys in your api.php (Batch 1)
    const registrationData = {
        action: "registerAccount",
        firstName: document.getElementById('firstName').value,
        lastName: document.getElementById('lastName').value,
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
        phone: document.getElementById('phone').value,
        address: document.getElementById('address').value,
        city: document.getElementById('city').value,
        province: document.getElementById('province').value,
        zip: document.getElementById('zip').value,
        country: document.getElementById('country').value
    };

    // Leader Style: Using .then() for the response handling
    fetch('../../api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(registrationData)
    })
    .then(response => {
        if (!response.ok) throw new Error("Network response was not ok");
        return response.json();
    })
    .then(result => {
        if (result.status) {
            alert("SKRRT! Registration successful. Please login.");
            // Redirect to the root index.php (login)
            window.location.href = '../../index.php';
        } else {
            alert("Registration Failed: " + result.message);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Server error. Please try again later.");
    });
});
</script>
<script src="../assets/js/stylingAdmin.js"></script>
</body>
</html>
<?php
/**
 * SKRRT WORLDWIDE - Secure Logout
 * This file handles the server-side session destruction.
 */
session_start();

// 1. Clear all session variables in memory
$_SESSION = array();

// 2. Explicitly destroy the session cookie in the user's browser
// This prevents "Session Fixation" attacks where an old ID might be reused.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Destroy the session object on the server
session_destroy();

// 4. Redirect back to the landing page (Login)
// The ../ goes out of the 'pages' folder to find index.php in the root
header("Location: ../../index.php");
exit();
?>
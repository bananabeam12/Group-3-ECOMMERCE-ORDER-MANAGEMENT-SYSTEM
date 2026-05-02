<?php
ob_start();
error_reporting(0);
ini_set('display_errors', 0);
session_start();
header('Content-Type: application/json');

require_once __DIR__ . "/includes/config.php";

// ─── Controllers ────────────────────────────────────────────────────────────
require_once __DIR__ . "/controllers/AuthController.php";
require_once __DIR__ . "/controllers/UserController.php";
require_once __DIR__ . "/controllers/ProductController.php";
require_once __DIR__ . "/controllers/CartController.php";
require_once __DIR__ . "/controllers/OrderController.php";
require_once __DIR__ . "/controllers/ReviewController.php";
require_once __DIR__ . "/controllers/AdminController.php";

// ─── Parse Request ───────────────────────────────────────────────────────────
$contentType = $_SERVER["CONTENT_TYPE"] ?? '';

if (strpos($contentType, "application/json") !== false) {
    $data   = json_decode(file_get_contents("php://input"), true) ?? [];
    $action = $data['action'] ?? '';
} else {
    $data   = $_POST;
    $action = $_POST['action'] ?? '';
}

if (empty($action)) {
    echo json_encode(["status" => false, "message" => "No action specified"]);
    exit;
}

// ─── Route to Controller ─────────────────────────────────────────────────────
$authActions = [
    'registerAccount', 'loginAccount', 'logout',
    'forgotPassword', 'verifyEmail', 'resetPassword',
    'getSessionUser',
];

$userActions = [
    'getProfile', 'updateProfile', 'fetchAllUsers', 'adminUpdateUserRole',
];

$productActions = [
    'fetchAllCategories', 'fetchProductsByCategory', 'fetchProductDetails', 'addCategory',
    'addProduct', 'updateProduct', 'deleteProduct', 'restoreProduct',
    'fetchAllProducts', 'fetchInventory', 'fetchArchivedProducts',
    'fetchFeaturedProducts', 'fetchArrivalProducts', 'fetchLatestProducts',
    'searchProducts', 'fetchFilteredProducts', 'deleteProductImage', 'restockProduct',
];

$cartActions = [
    'addToDatabaseCart', 'fetchDatabaseCart', 'removeFromCart', 'updateCartQuantity',
];

$orderActions = [
    'placeOrder', 'fetchUserOrders', 'cancelOrder',
    'fetchAllOrders', 'adminUpdateOrderStatus',
];

$reviewActions = [
    'addReview', 'fetchReviews', 'fetchAllReviewsAdmin', 'deleteReview',
];

$adminActions = [
    'adminFetchSalesSummary',
    'fetchSalesAnalytics',
    'searchUsers',
    'filterReviews',
    'searchReviews',
];

if (in_array($action, $authActions)) {
    (new AuthController($conn))->handle($action, $data);

} elseif (in_array($action, $userActions)) {
    (new UserController($conn))->handle($action, $data);

} elseif (in_array($action, $productActions)) {
    (new ProductController($conn))->handle($action, $data);

} elseif (in_array($action, $cartActions)) {
    (new CartController($conn))->handle($action, $data);

} elseif (in_array($action, $orderActions)) {
    (new OrderController($conn))->handle($action, $data);

} elseif (in_array($action, $reviewActions)) {
    (new ReviewController($conn))->handle($action, $data);

} elseif (in_array($action, $adminActions)) {
    (new AdminController($conn))->handle($action, $data);

} else {
    echo json_encode(["status" => false, "message" => "Invalid Action"]);
}

// ─── Cleanup ──────────────────────────────────────────────────────────────────
$conn->close();
ob_end_flush();
ob_end_flush();
<?php

class AdminController {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function handle(string $action, array $data): void {
        switch ($action) {
            case 'adminFetchSalesSummary': $this->fetchSalesSummary();   break;
            case 'fetchSalesAnalytics':    $this->fetchSalesAnalytics(); break;
            case 'searchUsers':            $this->searchUsers($data);    break;
            case 'filterReviews':          $this->filterReviews($data);  break;
            case 'searchReviews':          $this->searchReviews($data);  break;
            default:
                echo json_encode(["status" => false, "message" => "Unknown admin action."]);
        }
    }

    // ADMIN DASHBOARD SUMMARY
    private function fetchSalesSummary(): void {
        $revRes   = $this->conn->query("SELECT SUM(total_amount) as total FROM orders WHERE status = 'delivered'");
        $revRow   = $revRes ? $revRes->fetch_assoc() : ['total' => 0];
        $orderRes = $this->conn->query("SELECT COUNT(*) as count FROM orders");
        $userRes  = $this->conn->query("SELECT COUNT(*) as count FROM users WHERE user_role = 'customer'");
        $prodRes  = $this->conn->query("SELECT COUNT(*) as count FROM products");
        $lowRes   = $this->conn->query("SELECT COUNT(*) as count FROM products WHERE stock_quantity <= 5");

        echo json_encode([
            "status"        => true,
            "revenue"       => $revRow['total'] ?? 0,
            "orderCount"    => $orderRes->fetch_assoc()['count'] ?? 0,
            "userCount"     => $userRes->fetch_assoc()['count'] ?? 0,
            "productCount"  => $prodRes->fetch_assoc()['count'] ?? 0,
            "lowStockCount" => $lowRes->fetch_assoc()['count'] ?? 0,
        ]);
    }

    // SALES ANALYTICS — chart data for dashboard
    private function fetchSalesAnalytics(): void {
        $topRes = $this->conn->query(
            "SELECT p.product_name, SUM(oi.quantity) as total_sold
             FROM order_item oi
             JOIN products p ON oi.product_id = p.product_id
             GROUP BY oi.product_id
             ORDER BY total_sold DESC
             LIMIT 5"
        );
        $topLabels = []; $topData = [];
        if ($topRes) {
            while ($row = $topRes->fetch_assoc()) {
                $topLabels[] = $row['product_name'];
                $topData[]   = (int)$row['total_sold'];
            }
        }

        $statusRes = $this->conn->query(
            "SELECT status, COUNT(*) as count FROM orders GROUP BY status"
        );
        $statusLabels = []; $statusData = [];
        if ($statusRes) {
            while ($row = $statusRes->fetch_assoc()) {
                $statusLabels[] = ucfirst($row['status']);
                $statusData[]   = (int)$row['count'];
            }
        }

        $catRes = $this->conn->query(
            "SELECT c.category_name, SUM(oi.subtotal) as revenue
             FROM order_item oi
             JOIN products p ON oi.product_id = p.product_id
             JOIN categories c ON p.category_id = c.category_id
             GROUP BY c.category_id
             ORDER BY revenue DESC
             LIMIT 5"
        );
        $catLabels = []; $catData = [];
        if ($catRes) {
            while ($row = $catRes->fetch_assoc()) {
                $catLabels[] = $row['category_name'];
                $catData[]   = (float)$row['revenue'];
            }
        }

        echo json_encode([
            "status" => true,
            "data"   => [
                "topProducts"     => ["labels" => $topLabels,    "data" => $topData],
                "orderStatus"     => ["labels" => $statusLabels, "data" => $statusData],
                "categoryRevenue" => ["labels" => $catLabels,    "data" => $catData],
            ]
        ]);
    }

    // SEARCH USERS
    private function searchUsers(array $data): void {
        $keyword = "%" . ($data['keyword'] ?? '') . "%";
        $stmt = $this->conn->prepare(
            "SELECT u.user_id, u.first_name, u.last_name, u.email, u.user_role,
                    a.address_description, a.city, a.province, a.zip_code
             FROM users u
             LEFT JOIN address a ON u.user_id = a.user_id
             WHERE u.first_name LIKE ? OR u.last_name LIKE ? OR u.email LIKE ?
             GROUP BY u.user_id
             ORDER BY u.user_id ASC"
        );
        $stmt->bind_param("sss", $keyword, $keyword, $keyword);
        $stmt->execute();
        $users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        echo json_encode(["status" => true, "users" => $users]);
    }

    // FILTER REVIEWS BY RATING
    private function filterReviews(array $data): void {
        $rating = (int)($data['rating'] ?? 0);
        $stmt = $this->conn->prepare(
            "SELECT r.review_id, r.rating, r.review_comments,
                    u.first_name, u.last_name, p.product_name
             FROM reviews r
             JOIN users u ON r.user_id = u.user_id
             JOIN products p ON r.product_id = p.product_id
             WHERE r.rating = ?
             ORDER BY r.review_id DESC"
        );
        $stmt->bind_param("i", $rating);
        $stmt->execute();
        $reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        echo json_encode(["status" => true, "reviews" => $reviews]);
    }

    // SEARCH REVIEWS
    private function searchReviews(array $data): void {
        $keyword = "%" . ($data['query'] ?? '') . "%";
        $stmt = $this->conn->prepare(
            "SELECT r.review_id, r.rating, r.review_comments,
                    u.first_name, u.last_name, p.product_name
             FROM reviews r
             JOIN users u ON r.user_id = u.user_id
             JOIN products p ON r.product_id = p.product_id
             WHERE p.product_name LIKE ? OR u.first_name LIKE ? OR u.last_name LIKE ?
             ORDER BY r.review_id DESC"
        );
        $stmt->bind_param("sss", $keyword, $keyword, $keyword);
        $stmt->execute();
        $reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        echo json_encode(["status" => true, "reviews" => $reviews]);
    }
}
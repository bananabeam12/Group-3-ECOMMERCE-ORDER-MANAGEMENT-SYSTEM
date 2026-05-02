<?php

class ReviewController {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function handle(string $action, array $data): void {
        switch ($action) {
            case 'addReview':             $this->addReview($data);            break;
            case 'fetchReviews':          $this->fetchReviews($data);         break;
            case 'fetchAllReviewsAdmin':  $this->fetchAllReviewsAdmin();      break;
            case 'deleteReview':          $this->deleteReview($data);         break;
            default:
                echo json_encode(["status" => false, "message" => "Unknown review action."]);
        }
    }

    // 25. ADD REVIEW
    private function addReview(array $data): void {
        $uId     = $_SESSION['user_id'] ?? null;
        $pId     = $data['productId']   ?? $data['id'] ?? '';
        $rate    = $data['rating']      ?? 5;
        $comment = $data['comment']     ?? '';

        if (!$uId) {
            echo json_encode(["status" => false, "message" => "Please login to drop a review."]);
            return;
        }
        if (empty($pId)) {
            echo json_encode(["status" => false, "message" => "Product ID is missing from the request."]);
            return;
        }

        try {
            $stmt = $this->conn->prepare("INSERT INTO reviews (product_id, user_id, rating, review_comments) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $pId, $uId, $rate, $comment);

            if ($stmt->execute()) {
                echo json_encode(["status" => true, "message" => "Review secured in the vault!"]);
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => false, "message" => "Vault Error: " . $e->getMessage()]);
        }
    }

    // 26. FETCH PRODUCT REVIEWS
    private function fetchReviews(array $data): void {
        $pId = $data['productId'] ?? '';

        if (empty($pId)) {
            echo json_encode(["status" => false, "message" => "Missing Product ID."]);
            return;
        }

        try {
            $sql = "SELECT r.rating, r.review_comments, u.first_name, u.last_name 
                    FROM reviews r 
                    JOIN users u ON r.user_id = u.user_id 
                    WHERE r.product_id = ? 
                    ORDER BY r.review_id DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $pId);
            $stmt->execute();
            $reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            echo json_encode(["status" => true, "reviews" => $reviews]);
        } catch (Exception $e) {
            echo json_encode(["status" => false, "message" => "Database Error: " . $e->getMessage()]);
        }
    }

    // 26.5 FETCH ALL REVIEWS (Admin)
    private function fetchAllReviewsAdmin(): void {
        if (($_SESSION['user_role'] ?? '') !== 'admin') {
            echo json_encode(["status" => false, "message" => "Unauthorized access to the vault."]);
            return;
        }

        try {
            $sql = "SELECT r.review_id, r.rating, r.review_comments, 
                           u.first_name, u.last_name, 
                           p.product_name 
                    FROM reviews r 
                    JOIN users u ON r.user_id = u.user_id 
                    JOIN products p ON r.product_id = p.product_id 
                    ORDER BY r.review_id DESC";

            $result  = $this->conn->query($sql);
            $reviews = $result->fetch_all(MYSQLI_ASSOC);

            echo json_encode(["status" => true, "reviews" => $reviews]);
        } catch (Exception $e) {
            echo json_encode(["status" => false, "message" => "Vault Query Failed: " . $e->getMessage()]);
        }
    }

    // 31. DELETE REVIEW
    private function deleteReview(array $data): void {
        $uId = $_SESSION['user_id'] ?? null;

        if (!$uId) {
            echo json_encode(["status" => false, "message" => "Unauthorized"]);
            return;
        }

        $isAdmin = ($_SESSION['user_role'] ?? '') === 'admin';

        // ── Bulk delete: reviewIds array ──
        if (!empty($data['reviewIds']) && is_array($data['reviewIds'])) {
            if (!$isAdmin) {
                echo json_encode(["status" => false, "message" => "Unauthorized bulk delete."]);
                return;
            }
            $ids = array_map('intval', $data['reviewIds']);
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $types = str_repeat('i', count($ids));
            $stmt = $this->conn->prepare("DELETE FROM reviews WHERE review_id IN ($placeholders)");
            $stmt->bind_param($types, ...$ids);
            if ($stmt->execute()) {
                $count = $stmt->affected_rows;
                echo json_encode(["status" => true, "message" => "$count review(s) purged from the vault."]);
            } else {
                echo json_encode(["status" => false, "message" => "Bulk delete failed."]);
            }
            return;
        }

        // ── Single delete: reviewId ──
        $rId = $data['reviewId'] ?? '';
        $sql  = $isAdmin
            ? "DELETE FROM reviews WHERE review_id = ?"
            : "DELETE FROM reviews WHERE review_id = ? AND user_id = ?";

        $stmt = $this->conn->prepare($sql);
        if ($isAdmin) {
            $stmt->bind_param("i", $rId);
        } else {
            $stmt->bind_param("ii", $rId, $uId);
        }

        if ($stmt->execute()) {
            echo json_encode(["status" => true, "message" => "Review deleted."]);
        } else {
            echo json_encode(["status" => false, "message" => "Delete failed."]);
        }
    }
}
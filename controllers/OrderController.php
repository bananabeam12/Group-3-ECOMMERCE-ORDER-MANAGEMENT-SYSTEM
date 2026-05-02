<?php

class OrderController {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function handle(string $action, array $data): void {
        switch ($action) {
            case 'placeOrder':              $this->placeOrder($data);             break;
            case 'fetchUserOrders':         $this->fetchUserOrders();             break;
            case 'cancelOrder':             $this->cancelOrder($data);            break;
            case 'fetchAllOrders':          $this->fetchAllOrders();              break;
            case 'adminUpdateOrderStatus':  $this->adminUpdateOrderStatus($data); break;
            default:
                echo json_encode(["status" => false, "message" => "Unknown order action."]);
        }
    }

    // 21. PLACE ORDER
    private function placeOrder(array $data): void {
        $uId       = $_SESSION['user_id'] ?? null;
        $total     = $data['totalAmount']     ?? 0;
        $address   = $data['shippingAddress'] ?? '';
        $city      = $data['city']            ?? '';
        $country   = $data['country']         ?? '';
        $payMethod = $data['paymentMethod']   ?? 'COD';

        if (!$uId) {
            echo json_encode(["status" => false, "message" => "Session expired."]);
            return;
        }

        $this->conn->begin_transaction();
        try {
            $stmtO = $this->conn->prepare(
                "INSERT INTO orders (user_id, total_amount, shipping_address, shipping_city, shipping_country, status) VALUES (?, ?, ?, ?, ?, 'pending')"
            );
            $stmtO->bind_param("idsss", $uId, $total, $address, $city, $country);
            $stmtO->execute();
            $orderId = $this->conn->insert_id;

            $transId = "SKRRT-" . strtoupper(uniqid());
            $stmtP   = $this->conn->prepare(
                "INSERT INTO payment (order_id, user_id, amount, method_of_payment, payment_status, transaction_id) VALUES (?, ?, ?, ?, 'pending', ?)"
            );
            $stmtP->bind_param("iidss", $orderId, $uId, $total, $payMethod, $transId);
            $stmtP->execute();

            $itemsRes = $this->conn->query(
                "SELECT ci.*, p.product_name FROM cart_item ci 
                 JOIN products p ON ci.product_id = p.product_id 
                 JOIN cart c ON ci.cart_id = c.cart_id 
                 WHERE c.user_id = $uId"
            );

            while ($item = $itemsRes->fetch_assoc()) {
                $stmtI = $this->conn->prepare(
                    "INSERT INTO order_item (order_id, product_id, quantity, unit_price, subtotal) VALUES (?, ?, ?, ?, ?)"
                );
                $stmtI->bind_param("iiidd", $orderId, $item['product_id'], $item['quantity'], $item['unit_price'], $item['subtotal']);
                $stmtI->execute();

                $this->conn->query("UPDATE products SET stock_quantity = stock_quantity - {$item['quantity']} WHERE product_id = {$item['product_id']}");
            }

            $this->conn->query("DELETE FROM cart_item WHERE cart_id = (SELECT cart_id FROM cart WHERE user_id = $uId)");
            $this->conn->commit();
            echo json_encode(["status" => true, "message" => "Order #$orderId secured!", "orderId" => $orderId]);
        } catch (Exception $e) {
            $this->conn->rollback();
            echo json_encode(["status" => false, "message" => $e->getMessage()]);
        }
    }

    // 22. FETCH USER ORDERS
    private function fetchUserOrders(): void {
        $uId = $_SESSION['user_id'] ?? null;
        if (!$uId) {
            echo json_encode(["status" => false, "message" => "Login required."]);
            return;
        }

        $stmt = $this->conn->prepare(
            "SELECT o.*, p.payment_status, p.method_of_payment 
             FROM orders o 
             LEFT JOIN payment p ON o.order_id = p.order_id 
             WHERE o.user_id = ? ORDER BY o.order_id DESC"
        );
        $stmt->bind_param("i", $uId);
        $stmt->execute();
        $orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        foreach ($orders as &$order) {
            $oId           = $order['order_id'];
            $items         = $this->conn->query(
                "SELECT oi.*, p.product_name 
                 FROM order_item oi 
                 JOIN products p ON oi.product_id = p.product_id 
                 WHERE oi.order_id = $oId"
            );
            $order['items'] = $items->fetch_all(MYSQLI_ASSOC);
        }

        echo json_encode(["status" => true, "orders" => $orders]);
    }

    // 22.2 CANCEL ORDER (Customer)
    private function cancelOrder(array $data): void {
        $uId = $_SESSION['user_id'] ?? null;
        $oId = $data['orderId']     ?? 0;

        if (!$uId) {
            echo json_encode(["status" => false, "message" => "Login required."]);
            return;
        }

        $this->conn->begin_transaction();
        try {
            $check = $this->conn->prepare("SELECT status FROM orders WHERE order_id = ? AND user_id = ?");
            $check->bind_param("ii", $oId, $uId);
            $check->execute();
            $order = $check->get_result()->fetch_assoc();

            if (!$order) {
                throw new Exception("Order not found.");
            }
            if ($order['status'] !== 'pending') {
                throw new Exception("Only pending orders can be cancelled.");
            }

            $this->conn->query("UPDATE orders SET status = 'cancelled' WHERE order_id = $oId");
            $this->conn->query("UPDATE payment SET payment_status = 'failed' WHERE order_id = $oId");

            $items = $this->conn->query("SELECT product_id, quantity FROM order_item WHERE order_id = $oId");
            while ($row = $items->fetch_assoc()) {
                $this->conn->query("UPDATE products SET stock_quantity = stock_quantity + {$row['quantity']} WHERE product_id = {$row['product_id']}");
            }

            $this->conn->commit();
            echo json_encode(["status" => true, "message" => "Order #$oId cancelled."]);
        } catch (Exception $e) {
            $this->conn->rollback();
            echo json_encode(["status" => false, "message" => $e->getMessage()]);
        }
    }

    // 23. FETCH ALL ORDERS (Admin)
    private function fetchAllOrders(): void {
        if (($_SESSION['user_role'] ?? '') !== 'admin') {
            echo json_encode(["status" => false, "message" => "Unauthorized"]);
            return;
        }

        $sql = "SELECT o.*, u.first_name, u.last_name 
                FROM orders o 
                JOIN users u ON o.user_id = u.user_id 
                ORDER BY o.order_id DESC";
        $res    = $this->conn->query($sql);
        $orders = $res->fetch_all(MYSQLI_ASSOC);

        foreach ($orders as &$order) {
            $oId           = $order['order_id'];
            $itemSql       = "SELECT oi.*, p.product_name 
                              FROM order_item oi 
                              JOIN products p ON oi.product_id = p.product_id 
                              WHERE oi.order_id = $oId";
            $itemRes       = $this->conn->query($itemSql);
            $order['items'] = $itemRes->fetch_all(MYSQLI_ASSOC);
        }

        echo json_encode(["status" => true, "orders" => $orders]);
    }

    // 24. UPDATE ORDER STATUS (Admin)
    private function adminUpdateOrderStatus(array $data): void {
        $oId       = $data['orderId'];
        $newStatus = $data['status'];

        $this->conn->begin_transaction();
        try {
            $stmt = $this->conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
            $stmt->bind_param("si", $newStatus, $oId);
            $stmt->execute();

            if ($newStatus === 'delivered') {
                $this->conn->query("UPDATE payment SET payment_status = 'completed' WHERE order_id = $oId");
            }

            if ($newStatus === 'cancelled') {
                $this->conn->query("UPDATE payment SET payment_status = 'refunded' WHERE order_id = $oId");
                $items = $this->conn->query("SELECT product_id, quantity FROM order_item WHERE order_id = $oId");
                while ($row = $items->fetch_assoc()) {
                    $this->conn->query("UPDATE products SET stock_quantity = stock_quantity + {$row['quantity']} WHERE product_id = {$row['product_id']}");
                }
            }

            $this->conn->commit();
            echo json_encode(["status" => true, "message" => "Vault updated to $newStatus"]);
        } catch (Exception $e) {
            $this->conn->rollback();
            echo json_encode(["status" => false, "message" => $e->getMessage()]);
        }
    }
}

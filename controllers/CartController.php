<?php

class CartController {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function handle(string $action, array $data): void {
        switch ($action) {
            case 'addToDatabaseCart':   $this->addToCart($data);      break;
            case 'fetchDatabaseCart':   $this->fetchCart();            break;
            case 'removeFromCart':      $this->removeFromCart($data);  break;
            case 'updateCartQuantity':  $this->updateCartQuantity($data); break;
            default:
                echo json_encode(["status" => false, "message" => "Unknown cart action."]);
        }
    }

    // 17. ADD TO CART
    private function addToCart(array $data): void {
        $uId = $_SESSION['user_id'] ?? null;
        $pId = $data['productId']   ?? 0;
        $qty = $data['quantity']    ?? 1;

        if (!$uId) {
            echo json_encode(["status" => false, "message" => "Please login first."]);
            return;
        }

        $prodRes = $this->conn->query("SELECT price FROM products WHERE product_id = " . intval($pId));
        $product = $prodRes->fetch_assoc();
        if (!$product) {
            echo json_encode(["status" => false, "message" => "Product missing from vault."]);
            return;
        }

        $price    = $product['price'];
        $subtotal = $qty * $price;

        $this->conn->begin_transaction();
        try {
            // Find or create cart
            $res = $this->conn->query("SELECT cart_id FROM cart WHERE user_id = $uId");
            if ($res->num_rows == 0) {
                $this->conn->query("INSERT INTO cart (user_id, total_amount) VALUES ($uId, 0)");
                $cartId = $this->conn->insert_id;
            } else {
                $cartId = $res->fetch_assoc()['cart_id'];
            }

            // Add or update cart item
            $check = $this->conn->query("SELECT cart_item_id, quantity FROM cart_item WHERE cart_id = $cartId AND product_id = $pId");
            if ($check->num_rows > 0) {
                $item   = $check->fetch_assoc();
                $newQty = $item['quantity'] + $qty;
                $newSub = $newQty * $price;
                $stmt   = $this->conn->prepare("UPDATE cart_item SET quantity = ?, subtotal = ? WHERE cart_item_id = ?");
                $stmt->bind_param("idi", $newQty, $newSub, $item['cart_item_id']);
            } else {
                $stmt = $this->conn->prepare("INSERT INTO cart_item (cart_id, product_id, quantity, unit_price, subtotal) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("iiidd", $cartId, $pId, $qty, $price, $subtotal);
            }
            $stmt->execute();

            // Recalculate grand total
            $totalRes   = $this->conn->query("SELECT SUM(subtotal) as total FROM cart_item WHERE cart_id = $cartId");
            $grandTotal = $totalRes->fetch_assoc()['total'] ?? 0;
            $this->conn->query("UPDATE cart SET total_amount = $grandTotal WHERE cart_id = $cartId");

            $this->conn->commit();
            echo json_encode(["status" => true, "message" => "Item added to bag!"]);
        } catch (Exception $e) {
            $this->conn->rollback();
            echo json_encode(["status" => false, "message" => "Vault Error: " . $e->getMessage()]);
        }
    }

    // 18. FETCH CART
    private function fetchCart(): void {
        $uId = $_SESSION['user_id'] ?? null;
        if (!$uId) {
            echo json_encode(["status" => false, "message" => "Session expired."]);
            return;
        }

        $sql = "SELECT ci.cart_item_id, p.product_id, p.product_name, p.price, ci.quantity, pi.image_url 
                FROM cart_item ci
                JOIN cart c ON ci.cart_id = c.cart_id
                JOIN products p ON ci.product_id = p.product_id
                LEFT JOIN product_images pi ON p.product_image_id = pi.product_image_id
                WHERE c.user_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $uId);
        $stmt->execute();
        $res = $stmt->get_result();

        $displayCart = [];
        $total       = 0;

        while ($row = $res->fetch_assoc()) {
            $total       += ($row['price'] * $row['quantity']);
            $displayCart[] = $row;
        }

        echo json_encode(["status" => true, "cart" => $displayCart, "total" => $total]);
    }

    // 19. REMOVE FROM CART
    private function removeFromCart(array $data): void {
        $ciId = $data['cartId'] ?? '';

        $this->conn->begin_transaction();
        try {
            $stmtGet = $this->conn->prepare("SELECT cart_id FROM cart_item WHERE cart_item_id = ?");
            $stmtGet->bind_param("i", $ciId);
            $stmtGet->execute();
            $cartId = $stmtGet->get_result()->fetch_assoc()['cart_id'] ?? null;

            if ($cartId) {
                $stmtDel = $this->conn->prepare("DELETE FROM cart_item WHERE cart_item_id = ?");
                $stmtDel->bind_param("i", $ciId);
                $stmtDel->execute();

                $totalRes   = $this->conn->query("SELECT SUM(subtotal) as grand_total FROM cart_item WHERE cart_id = $cartId");
                $grandTotal = $totalRes->fetch_assoc()['grand_total'] ?? 0;

                $stmtU = $this->conn->prepare("UPDATE cart SET total_amount = ? WHERE cart_id = ?");
                $stmtU->bind_param("di", $grandTotal, $cartId);
                $stmtU->execute();
            }

            $this->conn->commit();
            echo json_encode(["status" => true, "message" => "Item removed and totals synced."]);
        } catch (Exception $e) {
            $this->conn->rollback();
            echo json_encode(["status" => false, "message" => "Sync Failed: " . $e->getMessage()]);
        }
    }

    // 20. UPDATE CART QUANTITY
    private function updateCartQuantity(array $data): void {
        $cId  = $data['cartId']   ?? '';
        $qty  = $data['quantity'] ?? 1;
        $stmt = $this->conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
        $stmt->bind_param("ii", $qty, $cId);
        $stmt->execute();
        echo json_encode(["status" => true]);
    }
}

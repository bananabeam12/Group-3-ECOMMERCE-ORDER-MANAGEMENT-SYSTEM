<?php

class ProductController {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function handle(string $action, array $data): void {
        switch ($action) {
            case 'fetchAllCategories':    $this->fetchAllCategories();          break;
            case 'fetchProductsByCategory': $this->fetchProductsByCategory($data); break;
            case 'fetchProductDetails':   $this->fetchProductDetails($data);    break;
            case 'addProduct':            $this->addProduct($data);             break;
            case 'updateProduct':         $this->updateProduct($data);          break;
            case 'deleteProduct':         $this->deleteProduct($data);          break;
            case 'restoreProduct':        $this->restoreProduct($data);         break;
            case 'fetchAllProducts':      $this->fetchAllProducts();            break;
            case 'fetchInventory':        $this->fetchInventory($data);         break;
            case 'fetchArchivedProducts': $this->fetchArchivedProducts();       break;
            case 'fetchFeaturedProducts': $this->fetchFeaturedProducts();       break;
            case 'fetchArrivalProducts':  $this->fetchArrivalProducts();        break;
            case 'fetchLatestProducts':   $this->fetchLatestProducts();         break;
            case 'searchProducts':        $this->searchProducts($data);         break;
            case 'fetchFilteredProducts': $this->fetchFilteredProducts($data);  break;
            case 'deleteProductImage':    $this->deleteProductImage($data);     break;
            case 'restockProduct':        $this->restockProduct($data);         break;
            case 'addCategory':           $this->addCategory($data);            break;
            default:
                echo json_encode(["status" => false, "message" => "Unknown product action."]);
        }
    }

    // 9. FETCH ALL CATEGORIES
    private function fetchAllCategories(): void {
        $result     = $this->conn->query("SELECT * FROM categories ORDER BY category_name ASC");
        $categories = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode(["status" => true, "categories" => $categories]);
    }

    // 10. FETCH PRODUCTS BY CATEGORY
    private function fetchProductsByCategory(array $data): void {
        $catId = $data['categoryId'] ?? null;

        if ($catId) {
            $stmt = $this->conn->prepare(
                "SELECT p.*, pi.image_url 
                 FROM products p 
                 LEFT JOIN product_images pi ON p.product_image_id = pi.product_image_id 
                 WHERE p.category_id = ? AND p.status = 'active'"
            );
            $stmt->bind_param("i", $catId);
        } else {
            $stmt = $this->conn->prepare(
                "SELECT p.*, pi.image_url 
                 FROM products p 
                 LEFT JOIN product_images pi ON p.product_image_id = pi.product_image_id
                 WHERE p.status = 'active'"
            );
        }

        $stmt->execute();
        $products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        echo json_encode(["status" => true, "products" => $products]);
    }

    // 11. FETCH PRODUCT DETAILS
    private function fetchProductDetails(array $data): void {
        $pId = $data['productId'] ?? '';

        $stmt = $this->conn->prepare(
            "SELECT p.*, c.category_name FROM products p 
             JOIN categories c ON p.category_id = c.category_id 
             WHERE p.product_id = ?"
        );
        $stmt->bind_param("i", $pId);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();

        if ($product) {
            $stmtG = $this->conn->prepare("SELECT image_url, product_image_id FROM product_images WHERE product_id = ?");
            $stmtG->bind_param("i", $pId);
            $stmtG->execute();
            $product['gallery'] = $stmtG->get_result()->fetch_all(MYSQLI_ASSOC);
            echo json_encode(["status" => true, "product" => $product]);
        } else {
            echo json_encode(["status" => false, "message" => "Product not found."]);
        }
    }

    // 12. ADD NEW PRODUCT (Admin)
    private function addProduct(array $data): void {
        $pName  = $_POST['productName']        ?? '';
        $pDesc  = $_POST['productDescription'] ?? '';
        $pPrice = $_POST['productPrice']       ?? 0;
        $pStock = $_POST['stockQuantity']      ?? 0;
        $catId  = $_POST['categoryId']         ?? 1;

        $this->conn->begin_transaction();
        try {
            $stmtP = $this->conn->prepare(
                "INSERT INTO products (product_name, product_description, price, stock_quantity, category_id) VALUES (?, ?, ?, ?, ?)"
            );
            $stmtP->bind_param("ssdii", $pName, $pDesc, $pPrice, $pStock, $catId);
            $stmtP->execute();
            $newPId = $this->conn->insert_id;

            if (isset($_FILES['productImages'])) {
                $totalFiles = count($_FILES['productImages']['name']);
                $mainImgId  = 0;

                for ($i = 0; $i < $totalFiles; $i++) {
                    if ($_FILES['productImages']['error'][$i] === 0) {
                        $newName = time() . "_" . uniqid() . "." . pathinfo($_FILES['productImages']['name'][$i], PATHINFO_EXTENSION);
                        $dbPath  = "assets/images/products/" . $newName;

                        if (move_uploaded_file($_FILES['productImages']['tmp_name'][$i], "../" . $dbPath)) {
                            $stmtI = $this->conn->prepare("INSERT INTO product_images (product_id, image_url) VALUES (?, ?)");
                            $stmtI->bind_param("is", $newPId, $dbPath);
                            $stmtI->execute();

                            if ($i === 0) $mainImgId = $this->conn->insert_id;
                        }
                    }
                }

                if ($mainImgId > 0) {
                    $stmtL = $this->conn->prepare("UPDATE products SET product_image_id = ? WHERE product_id = ?");
                    $stmtL->bind_param("ii", $mainImgId, $newPId);
                    $stmtL->execute();
                }
            }

            $this->conn->commit();
            echo json_encode(["status" => true, "message" => "Gallery Uploaded Successfully!"]);
        } catch (Exception $e) {
            $this->conn->rollback();
            echo json_encode(["status" => false, "message" => $e->getMessage()]);
        }
    }

    // 13. UPDATE PRODUCT (Admin)
    private function updateProduct(array $data): void {
        $pId    = $_POST['productId']                              ?? '';
        $pName  = $_POST['name']        ?? $_POST['productName']   ?? '';
        $pDesc  = $_POST['description'] ?? $_POST['productDescription'] ?? '';
        $pPrice = $_POST['price']       ?? $_POST['productPrice']  ?? 0;
        $pStock = $_POST['stock']       ?? $_POST['stockQuantity'] ?? 0;
        $catId  = $_POST['categoryId']  ?? 1;

        if (empty($pId)) {
            echo json_encode(["status" => false, "message" => "Missing Product ID"]);
            return;
        }

        $this->conn->begin_transaction();
        try {
            $stmt = $this->conn->prepare(
                "UPDATE products SET product_name = ?, product_description = ?, price = ?, stock_quantity = ?, category_id = ? WHERE product_id = ?"
            );
            $stmt->bind_param("ssdiii", $pName, $pDesc, $pPrice, $pStock, $catId, $pId);
            $stmt->execute();

            if (isset($_FILES['productImages']) && !empty($_FILES['productImages']['name'][0])) {
                $totalFiles = count($_FILES['productImages']['name']);
                for ($i = 0; $i < $totalFiles; $i++) {
                    if ($_FILES['productImages']['error'][$i] === 0) {
                        $ext     = pathinfo($_FILES['productImages']['name'][$i], PATHINFO_EXTENSION);
                        $newName = time() . "_" . uniqid() . "." . $ext;
                        $dbPath  = "assets/images/products/" . $newName;

                        if (move_uploaded_file($_FILES['productImages']['tmp_name'][$i], "../" . $dbPath)) {
                            $stmtI = $this->conn->prepare("INSERT INTO product_images (product_id, image_url) VALUES (?, ?)");
                            $stmtI->bind_param("is", $pId, $dbPath);
                            $stmtI->execute();
                            $newImgId = $this->conn->insert_id;

                            $checkMain   = $this->conn->query("SELECT product_image_id FROM products WHERE product_id = $pId");
                            $currentMain = $checkMain->fetch_assoc()['product_image_id'] ?? 0;

                            if ($currentMain == 0) {
                                $this->conn->query("UPDATE products SET product_image_id = $newImgId WHERE product_id = $pId");
                            }
                        }
                    }
                }
            }

            $this->conn->commit();
            echo json_encode(["status" => true, "message" => "Vault Updated Successfully!"]);
        } catch (Exception $e) {
            $this->conn->rollback();
            echo json_encode(["status" => false, "message" => "Update Failed: " . $e->getMessage()]);
        }
    }

    // 14. DELETE PRODUCT (Soft Delete) — supports single productId OR array productIds
    private function deleteProduct(array $data): void {
        // Bulk: productIds array sent from bulk delete button
        if (!empty($data['productIds']) && is_array($data['productIds'])) {
            $ids = array_map('intval', $data['productIds']);
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $types = str_repeat('i', count($ids));
            $stmt = $this->conn->prepare("UPDATE products SET status = 'deleted' WHERE product_id IN ($placeholders)");
            $stmt->bind_param($types, ...$ids);
            if ($stmt->execute()) {
                $count = $stmt->affected_rows;
                echo json_encode(["status" => true, "message" => "$count product(s) removed from the vault."]);
            } else {
                echo json_encode(["status" => false, "message" => "Bulk delete failed: " . $this->conn->error]);
            }
            return;
        }

        // Single: productId
        $pId  = $data['productId'] ?? 0;
        $stmt = $this->conn->prepare("UPDATE products SET status = 'deleted' WHERE product_id = ?");
        $stmt->bind_param("i", $pId);
        if ($stmt->execute()) {
            echo json_encode(["status" => true, "message" => "Product marked as deleted in the vault."]);
        } else {
            echo json_encode(["status" => false, "message" => "Vault Update Error: " . $this->conn->error]);
        }
    }

    // 14.1 RESTORE PRODUCT — supports single productId OR array productIds
    private function restoreProduct(array $data): void {
        // Bulk: productIds array
        if (!empty($data['productIds']) && is_array($data['productIds'])) {
            $ids = array_map('intval', $data['productIds']);
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $types = str_repeat('i', count($ids));
            $stmt = $this->conn->prepare("UPDATE products SET status = 'active' WHERE product_id IN ($placeholders)");
            $stmt->bind_param($types, ...$ids);
            if ($stmt->execute()) {
                $count = $stmt->affected_rows;
                echo json_encode(["status" => true, "message" => "$count product(s) restored to the vault."]);
            } else {
                echo json_encode(["status" => false, "message" => "Bulk restore failed: " . $this->conn->error]);
            }
            return;
        }

        // Single: productId
        $pId  = $data['productId'] ?? 0;
        $stmt = $this->conn->prepare("UPDATE products SET status = 'active' WHERE product_id = ?");
        $stmt->bind_param("i", $pId);
        if ($stmt->execute()) {
            echo json_encode(["status" => true, "message" => "Product restored to the shop."]);
        } else {
            echo json_encode(["status" => false, "message" => "Restoration failed."]);
        }
    }

    // 14.2 FETCH ALL PRODUCTS
    private function fetchAllProducts(): void {
        $sql = "SELECT p.*, c.category_name, pi.image_url 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.category_id 
                LEFT JOIN product_images pi ON p.product_image_id = pi.product_image_id 
                WHERE p.status = 'active'
                ORDER BY p.product_id DESC";
        $products = $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        echo json_encode(["status" => true, "products" => $products]);
    }

    // 15. FETCH INVENTORY (Admin)
    private function fetchInventory(array $data = []): void {
        $catId = !empty($data['categoryId']) ? (int)$data['categoryId'] : null;

        if ($catId) {
            $stmt = $this->conn->prepare(
                "SELECT p.*, c.category_name, pi.image_url 
                 FROM products p 
                 JOIN categories c ON p.category_id = c.category_id 
                 LEFT JOIN product_images pi ON p.product_image_id = pi.product_image_id 
                 WHERE p.status != 'deleted' AND p.category_id = ?
                 ORDER BY p.product_id DESC"
            );
            $stmt->bind_param("i", $catId);
            $stmt->execute();
            $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } else {
            $sql = "SELECT p.*, c.category_name, pi.image_url 
                    FROM products p 
                    JOIN categories c ON p.category_id = c.category_id 
                    LEFT JOIN product_images pi ON p.product_image_id = pi.product_image_id 
                    WHERE p.status != 'deleted'
                    ORDER BY p.product_id DESC";
            $items = $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        }

        echo json_encode(["status" => true, "inventory" => $items]);
    }

    // 15.1 FETCH ARCHIVED PRODUCTS
    private function fetchArchivedProducts(): void {
        $sql = "SELECT p.*, c.category_name, pi.image_url 
                FROM products p 
                JOIN categories c ON p.category_id = c.category_id 
                LEFT JOIN product_images pi ON p.product_image_id = pi.product_image_id 
                WHERE p.status = 'deleted'
                ORDER BY p.product_id DESC";
        $items = $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        echo json_encode(["status" => true, "archive" => $items]);
    }

    // 27. FETCH FEATURED PRODUCTS
    private function fetchFeaturedProducts(): void {
        $res = $this->conn->query(
            "SELECT p.*, pi.image_url 
             FROM products p 
             LEFT JOIN product_images pi ON p.product_image_id = pi.image_id 
             ORDER BY RAND() LIMIT 4"
        );
        echo json_encode(["status" => true, "products" => $res->fetch_all(MYSQLI_ASSOC)]);
    }

    // 28. FETCH NEW ARRIVALS
    private function fetchArrivalProducts(): void {
        $query = "SELECT p.*, pi.image_url 
                  FROM products p 
                  LEFT JOIN product_images pi ON p.product_image_id = pi.product_image_id 
                  WHERE p.status = 'active'
                  ORDER BY p.product_id DESC LIMIT 6";
        $result   = mysqli_query($this->conn, $query);
        $products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        echo json_encode(['status' => true, 'products' => $products]);
    }

    // FETCH LATEST PRODUCTS
    private function fetchLatestProducts(): void {
        $res = $this->conn->query(
            "SELECT p.*, pi.image_url 
             FROM products p 
             LEFT JOIN product_images pi ON p.product_image_id = pi.image_id 
             ORDER BY p.product_id DESC LIMIT 8"
        );
        echo json_encode(["status" => true, "products" => $res->fetch_all(MYSQLI_ASSOC)]);
    }

    // 29. SEARCH PRODUCTS
    private function searchProducts(array $data): void {
        $query      = $data['query'] ?? '';
        $searchTerm = "%$query%";

        $stmt = $this->conn->prepare(
            "SELECT p.*, pi.image_url 
             FROM products p 
             LEFT JOIN product_images pi ON p.product_image_id = pi.product_image_id 
             WHERE (p.product_name LIKE ? OR p.product_description LIKE ?) 
             AND p.status = 'active'"
        );
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        echo json_encode(["status" => true, "products" => $stmt->get_result()->fetch_all(MYSQLI_ASSOC)]);
    }

    // 30. FETCH FILTERED PRODUCTS
    private function fetchFilteredProducts(array $data): void {
        $min = $data['minPrice']   ?? 0;
        $max = $data['maxPrice']   ?? 999999;
        $cat = $data['categoryId'] ?? null;

        if ($cat) {
            $stmt = $this->conn->prepare(
                "SELECT p.*, pi.image_url FROM products p 
                 LEFT JOIN product_images pi ON p.product_image_id = pi.image_id 
                 WHERE p.category_id = ? AND p.price BETWEEN ? AND ?"
            );
            $stmt->bind_param("idd", $cat, $min, $max);
        } else {
            $stmt = $this->conn->prepare(
                "SELECT p.*, pi.image_url FROM products p 
                 LEFT JOIN product_images pi ON p.product_image_id = pi.image_id 
                 WHERE p.price BETWEEN ? AND ?"
            );
            $stmt->bind_param("dd", $min, $max);
        }

        $stmt->execute();
        echo json_encode(["status" => true, "products" => $stmt->get_result()->fetch_all(MYSQLI_ASSOC)]);
    }

    // 33. DELETE SPECIFIC PRODUCT IMAGE
    private function deleteProductImage(array $data): void {
        $imgId = $data['imageId'];

        $stmt = $this->conn->prepare("SELECT image_url, product_id FROM product_images WHERE product_image_id = ?");
        $stmt->bind_param("i", $imgId);
        $stmt->execute();
        $imgData = $stmt->get_result()->fetch_assoc();

        if (!$imgData) {
            echo json_encode(["status" => false, "message" => "Image record not found."]);
            return;
        }

        $filePath = "../" . $imgData['image_url'];
        $pId      = $imgData['product_id'];

        $del = $this->conn->prepare("DELETE FROM product_images WHERE product_image_id = ?");
        $del->bind_param("i", $imgId);

        if ($del->execute()) {
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Maintenance: If this was the main thumbnail, pick a new one
            $checkThumb  = $this->conn->prepare("SELECT product_image_id FROM products WHERE product_id = ?");
            $checkThumb->bind_param("i", $pId);
            $checkThumb->execute();
            $currentThumb = $checkThumb->get_result()->fetch_assoc()['product_image_id'];

            if ($currentThumb == $imgId) {
                $newThumbRes = $this->conn->query("SELECT product_image_id FROM product_images WHERE product_id = $pId LIMIT 1");
                $newThumb    = $newThumbRes->fetch_assoc()['product_image_id'] ?? 0;

                $updateThumb = $this->conn->prepare("UPDATE products SET product_image_id = ? WHERE product_id = ?");
                $updateThumb->bind_param("ii", $newThumb, $pId);
                $updateThumb->execute();
            }

            echo json_encode(["status" => true, "message" => "Image purged from vault."]);
        }
    }

    // RESTOCK PRODUCT (Admin)
    private function restockProduct(array $data): void {
        if (($_SESSION['user_role'] ?? '') !== 'admin') {
            echo json_encode(["status" => false, "message" => "Unauthorized access to the vault."]);
            return;
        }

        $pId       = $data['productId'] ?? 0;
        $addAmount = intval($data['quantity'] ?? 0);

        if ($pId > 0 && $addAmount > 0) {
            $stmt = $this->conn->prepare("UPDATE products SET stock_quantity = stock_quantity + ? WHERE product_id = ?");
            $stmt->bind_param("ii", $addAmount, $pId);

            if ($stmt->execute()) {
                echo json_encode(["status" => true, "message" => "Inventory restocked by $addAmount units."]);
            } else {
                echo json_encode(["status" => false, "message" => "Vault Update Error: " . $this->conn->error]);
            }
        } else {
            echo json_encode(["status" => false, "message" => "Invalid product or quantity."]);
        }
    }

    // ADD CATEGORY (Admin)
    private function addCategory(array $data): void {
        if (($_SESSION['user_role'] ?? '') !== 'admin') {
            echo json_encode(["status" => false, "message" => "Unauthorized."]);
            return;
        }

        // Accepts both JSON body and FormData
        $name = trim($data['categoryName'] ?? $_POST['categoryName'] ?? '');
        $desc = trim($data['categoryDescription'] ?? $_POST['categoryDescription'] ?? '');

        if (empty($name)) {
            echo json_encode(["status" => false, "message" => "Category name is required."]);
            return;
        }

        // Prevent duplicate category names
        $check = $this->conn->prepare("SELECT category_id FROM categories WHERE LOWER(category_name) = LOWER(?)");
        $check->bind_param("s", $name);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            echo json_encode(["status" => false, "message" => "Category '$name' already exists."]);
            return;
        }

        $stmt = $this->conn->prepare("INSERT INTO categories (category_name, category_description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $desc);
        if ($stmt->execute()) {
            echo json_encode(["status" => true, "message" => "Category '$name' added successfully.", "category_id" => $this->conn->insert_id]);
        } else {
            echo json_encode(["status" => false, "message" => "Failed to add category: " . $this->conn->error]);
        }
    }
}
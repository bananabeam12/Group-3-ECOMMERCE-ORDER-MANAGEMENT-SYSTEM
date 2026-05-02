<?php

class UserController
{

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function handle(string $action, array $data): void
    {
        switch ($action) {
            case 'getProfile':
                $this->getProfile();
                break;
            case 'updateProfile':
                $this->updateProfile($data);
                break;
            case 'fetchAllUsers':
                $this->fetchAllUsers();
                break;
            case 'adminUpdateUserRole':
                $this->adminUpdateUserRole($data);
                break;
            default:
                echo json_encode(["status" => false, "message" => "Unknown user action."]);
        }
    }

    // 6. GET PROFILE
    private function getProfile(): void
    {
        $uId = $_SESSION['user_id'] ?? null;

        // Not logged in — return false cleanly instead of crashing
        if (!$uId) {
            echo json_encode(["status" => false, "message" => "Not logged in."]);
            return;
        }

        $stmtU = $this->conn->prepare("SELECT first_name, last_name, email, phone_number FROM users WHERE user_id = ?");
        $stmtU->bind_param("i", $uId);
        $stmtU->execute();
        $user = $stmtU->get_result()->fetch_assoc();

        $stmtA = $this->conn->prepare("SELECT * FROM address WHERE user_id = ? ORDER BY address_id DESC");
        $stmtA->bind_param("i", $uId);
        $stmtA->execute();
        $addrs = $stmtA->get_result()->fetch_all(MYSQLI_ASSOC);

        echo json_encode(["status" => true, "user" => $user, "addresses" => $addrs]);
    }

    // 7. UPDATE PROFILE
    private function updateProfile(array $data): void
    {
        $uId = $_SESSION['user_id'];
        $aId = $data['addressId'] ?? null;

        $this->conn->begin_transaction();
        try {
            $stmtU = $this->conn->prepare(
                "UPDATE users SET first_name = ?, last_name = ?, phone_number = ? WHERE user_id = ?"
            );
            $stmtU->bind_param("sssi", $data['firstName'], $data['lastName'], $data['phone'], $uId);
            $stmtU->execute();

            if ($aId) {
                $stmtA = $this->conn->prepare(
                    "UPDATE address SET address_description = ?, city = ?, province = ?, zip_code = ?, country = ? WHERE address_id = ?"
                );
                $stmtA->bind_param("sssssi", $data['address'], $data['city'], $data['province'], $data['zip'], $data['country'], $aId);
            } else {
                $stmtA = $this->conn->prepare(
                    "INSERT INTO address (user_id, address_description, city, province, zip_code, country) VALUES (?, ?, ?, ?, ?, ?)"
                );
                $stmtA->bind_param("isssss", $uId, $data['address'], $data['city'], $data['province'], $data['zip'], $data['country']);
            }
            $stmtA->execute();

            $this->conn->commit();
            echo json_encode(["status" => true, "message" => "Profile successfully saved!"]);
        } catch (Exception $e) {
            $this->conn->rollback();
            echo json_encode(["status" => false, "message" => "Update Failed"]);
        }
    }

    // 8. FETCH ALL USERS (Admin)
    private function fetchAllUsers(): void
    {
        $sql = "SELECT u.user_id, u.first_name, u.last_name, u.email, u.user_role, 
                       a.address_description, a.city, a.province, a.zip_code
                FROM users u
                LEFT JOIN address a ON u.user_id = a.user_id
                GROUP BY u.user_id 
                ORDER BY u.user_id ASC";
        $res = $this->conn->query($sql);
        $users = $res->fetch_all(MYSQLI_ASSOC);
        echo json_encode(["status" => true, "users" => $users]);
    }

    // 32. UPDATE USER ROLE (Admin Panel)
    private function adminUpdateUserRole(array $data): void
    {
        $targetId = $data['targetUserId'];
        $newRole = $data['newRole'];

        $stmt = $this->conn->prepare("UPDATE users SET user_role = ? WHERE user_id = ?");
        $stmt->bind_param("si", $newRole, $targetId);
        $stmt->execute();
        echo json_encode(["status" => true, "message" => "Role updated."]);
    }
}

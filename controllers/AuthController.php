<?php

class AuthController {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function handle(string $action, array $data): void {
        switch ($action) {
            case 'registerAccount':    $this->registerAccount($data);    break;
            case 'loginAccount':       $this->loginAccount($data);        break;
            case 'logout':             $this->logout();                   break;
            case 'forgotPassword':
            case 'verifyEmail':        $this->verifyEmail($data);         break;
            case 'resetPassword':      $this->resetPassword($data);       break;
            case 'getSessionUser':     $this->getSessionUser();            break;
            default:
                echo json_encode(["status" => false, "message" => "Unknown auth action."]);
        }
    }

    // 1. REGISTER ACCOUNT
    private function registerAccount(array $data): void {
        $fName   = $data['firstName'] ?? '';
        $lName   = $data['lastName']  ?? '';
        $email   = $data['email']     ?? '';
        $pass    = password_hash($data['password'], PASSWORD_BCRYPT);
        $addr    = $data['address']   ?? '';
        $city    = $data['city']      ?? '';
        $prov    = $data['province']  ?? '';
        $zip     = $data['zip']       ?? '';
        $country = $data['country']   ?? '';

        $this->conn->begin_transaction();
        try {
            $stmtU = $this->conn->prepare(
                "INSERT INTO users (first_name, last_name, email, password, user_role) VALUES (?, ?, ?, ?, 'customer')"
            );
            $stmtU->bind_param("ssss", $fName, $lName, $email, $pass);
            $stmtU->execute();
            $newUserId = $this->conn->insert_id;

            $stmtA = $this->conn->prepare(
                "INSERT INTO address (user_id, address_description, city, province, zip_code, country) VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmtA->bind_param("isssss", $newUserId, $addr, $city, $prov, $zip, $country);
            $stmtA->execute();

            $this->conn->commit();
            echo json_encode(["status" => true, "message" => "Account created successfully!"]);
        } catch (Exception $e) {
            $this->conn->rollback();
            echo json_encode(["status" => false, "message" => "Error: " . $e->getMessage()]);
        }
    }

    // 2. LOGIN ACCOUNT
    private function loginAccount(array $data): void {
        $email = $data['email']    ?? $data['mail'] ?? '';
        $pass  = $data['password'] ?? $data['pass'] ?? '';

        $sql = "SELECT u.*, a.address_description, a.city, a.province, a.zip_code, a.country 
                FROM users u 
                LEFT JOIN address a ON u.user_id = a.user_id 
                WHERE u.email = ? 
                ORDER BY a.address_id DESC LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($pass, $user['password'])) {
            $_SESSION['user_id']   = $user['user_id'];
            $_SESSION['user_role'] = $user['user_role'];
            $_SESSION['user_name'] = $user['first_name'];

            echo json_encode([
                "status" => true,
                "role"   => $user['user_role'],
                "user"   => [
                    "id"        => $user['user_id'],
                    "firstName" => $user['first_name'],
                    "lastName"  => $user['last_name'],
                    "email"     => $user['email'],
                    "role"      => $user['user_role'],
                    "address"   => $user['address_description'] ?? 'No address set',
                    "city"      => $user['city']     ?? '',
                    "province"  => $user['province'] ?? '',
                    "zip"       => $user['zip_code'] ?? '',
                    "country"   => $user['country']  ?? ''
                ]
            ]);
        } else {
            echo json_encode(["status" => false, "message" => "Invalid email or password."]);
        }
    }

    // 3. LOGOUT
    private function logout(): void {
        session_destroy();
        echo json_encode(["status" => true, "message" => "Logged out"]);
    }

    // 4. FORGOT PASSWORD / VERIFY EMAIL
    private function verifyEmail(array $data): void {
        $email = $data['email'] ?? $data['mail'] ?? '';
        $stmt  = $this->conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user) {
            $_SESSION['reset_user_id'] = $user['user_id'];
            echo json_encode(["status" => true, "message" => "Account found."]);
        } else {
            echo json_encode(["status" => false, "message" => "Email not registered."]);
        }
    }

    // GET SESSION USER — lets JS verify PHP session without localStorage
    private function getSessionUser(): void {
        if (!empty($_SESSION['user_id']) && !empty($_SESSION['user_role'])) {
            echo json_encode([
                "status" => true,
                "user"   => [
                    "user_id"   => $_SESSION['user_id'],
                    "firstName" => $_SESSION['user_name'] ?? '',
                    "user_role" => $_SESSION['user_role'],
                ]
            ]);
        } else {
            echo json_encode(["status" => false, "message" => "Not logged in."]);
        }
    }

    // 5. RESET PASSWORD
    private function resetPassword(array $data): void {
        $email   = $data['email']   ?? '';
        $rawPass = $data['newPass'] ?? '';

        if (empty($rawPass) || empty($email)) {
            echo json_encode(["status" => false, "message" => "Required data missing."]);
            return;
        }

        $hashed = password_hash($rawPass, PASSWORD_BCRYPT);
        $stmt   = $this->conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed, $email);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(["status" => true, "message" => "Password updated successfully!"]);
        } else {
            echo json_encode(["status" => false, "message" => "No changes made."]);
        }
    }
}
<?php
require_once('config/Database.php');
class UserModel {
    private $db;

    public function __construct(Database $database) {
        $this->db = $database;
    }

    public function registerUser($username, $password, $email) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Hash the password
        $query = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sss", $username, $hashedPassword, $email);
        return $stmt->execute();
    }

    public function loginUser($username, $password) {
        $query = "SELECT password FROM users WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        // Verify the hashed password
        return $stmt->num_rows > 0 && password_verify($password, $hashedPassword);
    }
}
?>

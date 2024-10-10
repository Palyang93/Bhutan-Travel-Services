<?php
require_once('config/Database.php');
class SupportModel {
    private $db;

    public function createSupportRequest($email, $subject, $message) {
        if (empty($email) || empty($subject) || empty($message)) {
            throw new InvalidArgumentException('All fields are required');
        }
    
        $query = "INSERT INTO support_requests (email, subject, message) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sss", $email, $subject, $message);
    
        if (!$stmt->execute()) {
            throw new RuntimeException('Failed to create support request: ' . $stmt->error);
        }
    
        $stmt->close();
        return true;
    }
}

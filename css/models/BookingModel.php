<?php
require_once('config/Database.php');

class BookingModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getServices() {
        $sql = "SELECT id, service_name FROM services";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            error_log("Prepare Error: " . $this->db->connection->error);
            return [];
        }
        if (!$stmt->execute()) {
            error_log("Execution Error: " . $stmt->error);
            return [];
        }
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function createBooking($email, $contact_number, $destination, $start_date, $end_date, $travelers, $activities) {
        $sql = "INSERT INTO bookings (email, contact_number, destination, start_date, end_date, travelers, activities) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            error_log("Prepare Error: " . $this->db->connection->error);
            return false;
        }

        // Convert activities array to a string (e.g., CSV)
        $activities_string = implode(',', $activities);
        
        if (!$stmt->bind_param("sssssis", $email, $contact_number, $destination, $start_date, $end_date, $travelers, $activities_string)) {
            error_log("Binding Error: " . $stmt->error);
            return false;
        }

        return $stmt->execute(); // Execute and return success
    }
}
?>

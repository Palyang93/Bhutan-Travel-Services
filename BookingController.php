<?php
require_once('models/BookingModel.php');

class BookingController {
    private $bookingModel;

    public function __construct() {
        $this->bookingModel = new BookingModel();
    }

    public function createBooking() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $contact_number = $_POST['contact_number'] ?? '';
            $destination = $_POST['destination'] ?? '';
            $start_date = $_POST['start_date'] ?? '';
            $end_date = $_POST['end_date'] ?? '';
            $travelers = $_POST['travelers'] ?? '';
            $activities = $_POST['activities'] ?? [];

            if ($this->bookingModel->createBooking($email, $contact_number, $destination, $start_date, $end_date, $travelers, $activities)) {
                $_SESSION['message'] = "Travel plan submitted successfully!";
                header("Location: index.php?page=travel_management"); // Redirect to travel management or confirmation page
                exit;
            } else {
                $_SESSION['error'] = "Submission failed! Please try again.";
            }
        }
    }

    public function getServices() {
        return $this->bookingModel->getServices(); // Fetch services to populate the dropdown if necessary
    }
}
?>

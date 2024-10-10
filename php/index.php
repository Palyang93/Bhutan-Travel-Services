<?php
session_start(); // Start session for all pages

require_once('config/Database.php');
require_once('controllers/UserController.php');
require_once('controllers/BookingController.php'); // Include the BookingController

$page = $_GET['page'] ?? 'home'; // Default to home if no page parameter
$action = $_GET['action'] ?? ''; // Handle login/register action

// Instantiate models
$database = new Database();
$userModel = new UserModel($database);
$userController = new UserController($userModel);
$bookingController = new BookingController(); // Create BookingController instance

$pageContent = '';

switch ($page) {
    case 'login_register':
        if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->login();
        } elseif ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->register();
        }
        $pageContent = 'views/login_register.html';
        break;

    case 'travel_management':
        $pageContent = 'views/travel_management.html';
        break;

    case 'travel_planning':
        if (!isset($_SESSION['username'])) {
            header("Location: index.php?page=login_register"); // Redirect to login if not logged in
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookingController->createBooking(); // Handle the travel planning form submission
        }
        $pageContent = 'views/travel_planning.html';
        break;

    case 'logout':
        session_destroy();
        header("Location: index.php?page=home");
        exit;
        case 'support':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $supportModel = new SupportModel($database);
                $supportController = new SupportController($supportModel);
                $supportController->submitSupportRequest(); // Call the submission method
            }
            $pageContent = 'views/support.html';
            break;
        
    case 'home':
    default:
        $pageContent = 'views/home.html';
        break;
}

include('views/layout.php'); // Load the layout that includes the header, footer, etc.
?>

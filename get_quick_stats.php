<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'tenant') {
    exit(json_encode(['error' => 'Unauthorized']));
}

$db = new mysqli('localhost', 'root', '', 'rent_right_rentals');
if ($db->connect_error) {
    exit(json_encode(['error' => 'Database connection failed']));
}

$user_id = $_SESSION['user_id'];

// Get rent due
$rent_query = $db->prepare("SELECT rent_amount FROM tenants WHERE user_id = ?");
$rent_query->bind_param("i", $user_id);
$rent_query->execute();
$rent_result = $rent_query->get_result();
$rent_due = $rent_result->fetch_assoc()['rent_amount'];

// Get next payment date
$payment_query = $db->prepare("SELECT payment_date FROM payments WHERE user_id = ? ORDER BY payment_date DESC LIMIT 1");
$payment_query->bind_param("i", $user_id);
$payment_query->execute();
$payment_result = $payment_query->get_result();
$last_payment_date = $payment_result->fetch_assoc()['payment_date'];
$next_payment_date = date('Y-m-d', strtotime($last_payment_date . ' +1 month'));

// Get open service requests
$service_query = $db->prepare("SELECT COUNT(*) as count FROM service_requests WHERE user_id = ? AND status = 'open'");
$service_query->bind_param("i", $user_id);
$service_query->execute();
$service_result = $service_query->get_result();
$open_service_requests = $service_result->fetch_assoc()['count'];

$db->close();

echo json_encode([
    'rentDue' => $rent_due,
    'nextPaymentDate' => $next_payment_date,
    'openServiceRequests' => $open_service_requests
]);
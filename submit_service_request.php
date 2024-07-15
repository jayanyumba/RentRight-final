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
$service_type = $_POST['service_type'];
$description = $_POST['description'];
$status = 'open';
$request_date = date('Y-m-d H:i:s');

$query = $db->prepare("INSERT INTO service_requests (tenant_id, type, description, status, request_date) VALUES (?, ?, ?, ?, ?)");
$query->bind_param("issss", $user_id, $service_type, $description, $status, $request_date);
echo $user_id;

if ($query->execute()) {
    echo json_encode(['message' => 'Service request submitted successfully']);
    header("Location: tenant_dashboard.php");
} else {
    echo json_encode(['error' => 'Failed to submit service request']);
}

$db->close();
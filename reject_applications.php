<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'landlord') {
    exit(json_encode(['error' => 'Unauthorized']));
}

$db = new mysqli('localhost', 'root', '', 'rent_right_rentals');
if ($db->connect_error) {
    exit(json_encode(['error' => 'Database connection failed']));
}

$data = json_decode(file_get_contents('php://input'), true);
$application_id = $data['applicationId'];

$query = $db->prepare("UPDATE applications SET status = 'rejected' WHERE id = ?");
$query->bind_param("i", $application_id);

if ($query->execute()) {
    echo json_encode(['message' => 'Application rejected successfully']);
} else {
    echo json_encode(['error' => 'Failed to reject application']);
}

$db->close();
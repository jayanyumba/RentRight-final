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

$query = $db->prepare("SELECT type, status, request_date FROM service_requests WHERE user_id = ? ORDER BY request_date DESC LIMIT 10");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

$service_history = [];
while ($row = $result->fetch_assoc()) {
    $service_history[] = [
        'type' => $row['type'],
        'status' => $row['status'],
        'date' => $row['request_date']
    ];
}

$db->close();

echo json_encode($service_history);
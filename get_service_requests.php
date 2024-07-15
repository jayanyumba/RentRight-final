<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'landlord') {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$db = new mysqli('localhost', 'root', '', 'rent_right_rentals');
if ($db->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

$query = "SELECT sr.id, t.name AS tenant, sr.type, sr.status, sr.request_date AS date 
          FROM service_requests sr 
          JOIN tenants t ON sr.tenant_id = t.id";
$result = $db->query($query);

if ($result) {
    $serviceRequests = [];
    while ($row = $result->fetch_assoc()) {
        $serviceRequests[] = $row;
    }
    echo json_encode($serviceRequests);
} else {
    echo json_encode(['error' => 'Failed to fetch service requests']);
}

$db->close();
?>

<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'landlord') {
    exit(json_encode(['error' => 'Unauthorized']));
}

$db = new mysqli('localhost', 'root', '', 'rent_right_rentals');
if ($db->connect_error) {
    exit(json_encode(['error' => 'Database connection failed']));
}

$tenant_id = $_GET['id'];

$query = $db->prepare("SELECT name, email, phone, unit, lease_start, lease_end, rent, balance FROM tenants WHERE id = ?");
$query->bind_param("i", $tenant_id);
$query->execute();
$result = $query->get_result();

if ($tenant = $result->fetch_assoc()) {
    echo json_encode($tenant);
} else {
    echo json_encode(['error' => 'Tenant not found']);
}

$db->close();
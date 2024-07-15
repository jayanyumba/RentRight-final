<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'landlord') {
    exit(json_encode(['error' => 'Unauthorized']));
}

$db = new mysqli('localhost', 'root', '', 'rent_right_rentals');
if ($db->connect_error) {
    exit(json_encode(['error' => 'Database connection failed']));
}

$user_id = $_SESSION['user_id'];

$query = $db->prepare("
    SELECT t.id, t.name, p.unit_number, t.lease_end
    FROM tenants t
    JOIN properties p ON t.property_id = p.id
    WHERE t.landlord_id = ?
    ORDER BY t.name
");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

$tenants = [];
while ($row = $result->fetch_assoc()) {
    $tenants[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'unit' => $row['unit_number'],
        'leaseEnd' => $row['lease_end']
    ];
}

$db->close();

echo json_encode($tenants);
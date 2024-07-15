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
    SELECT id, address, total_units, occupied_units
    FROM properties
    WHERE landlord_id = ?
    ORDER BY address
");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

$properties = [];
while ($row = $result->fetch_assoc()) {
    $occupancy = ($row['occupied_units'] / $row['total_units']) * 100;
    $properties[] = [
        'id' => $row['id'],
        'address' => $row['address'],
        'units' => $row['total_units'],
        'occupancy' => round($occupancy, 2)
    ];
}

$db->close();

echo json_encode($properties);
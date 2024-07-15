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

// Example query to fetch stats
$query = "SELECT COUNT(*) AS totalProperties, SUM(CASE WHEN status = 'occupied' THEN 1 ELSE 0 END) AS occupiedUnits, SUM(CASE WHEN status = 'vacant' THEN 1 ELSE 0 END) AS vacantUnits, SUM(rent) AS monthlyIncome FROM properties";
$result = $db->query($query);

if ($result) {
    $stats = $result->fetch_assoc();
    echo json_encode($stats);
} else {
    echo json_encode(['error' => 'Failed to fetch stats']);
}

$db->close();
?>

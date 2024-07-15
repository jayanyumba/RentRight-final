<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'landlord') {
    exit(json_encode(['error' => 'Unauthorized']));
}

$db = new mysqli('localhost', 'root', '', 'rent_right_rentals');
if ($db->connect_error) {
    exit(json_encode(['error' => 'Database connection failed']));
}

$property_id = $_GET['id'];

$query = $db->prepare("SELECT address, total_units, occupied_units, vacant_units, total_monthly_rent, building_type, year_built FROM properties WHERE id = ?");
$query->bind_param("i", $property_id);
$query->execute();
$result = $query->get_result();

if ($property = $result->fetch_assoc()) {
    echo json_encode($property);
} else {
    echo json_encode(['error' => 'Property not found']);
}

$db->close();
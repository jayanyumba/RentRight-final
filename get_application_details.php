<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'landlord') {
    exit(json_encode(['error' => 'Unauthorized']));
}

new mysqli('localhost', 'root', '', 'rent_right_rentals');
if ($db->connect_error) {
    exit(json_encode(['error' => 'Database connection failed']));
}

$application_id = $_GET['id'];

$query = $db->prepare("SELECT name, email, phone, desired_unit, move_in_date, employment, income, credit_score FROM applications WHERE id = ?");
$query->bind_param("i", $application_id);
$query->execute();
$result = $query->get_result();

if ($application = $result->fetch_assoc()) {
    echo json_encode($application);
} else {
    echo json_encode(['error' => 'Application not found']);
}

$db->close();
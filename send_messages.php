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
$message = $_POST['message'];
$sender = 'Tenant';
$recipient_id = 0;
$timestamp = date('Y-m-d H:i:s');

$query = $db->prepare("INSERT INTO messages (sender_id, recipient_id, message, timestamp) VALUES (?, ?, ?, ?)");
$query->bind_param("siss", $sender, $recipient_id, $message, $timestamp);

if ($query->execute()) {
    echo json_encode(['message' => 'Message sent successfully']);
    header("Location: tenant_dashboard.php");
} else {
    echo json_encode(['error' => 'Failed to send message']);
}

$db->close();
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'tenant') {
    exit(json_encode(['error' => 'Unauthorized']));
}

new mysqli('localhost', 'root', '', 'rent_right_rentals');
if ($db->connect_error) {
    exit(json_encode(['error' => 'Database connection failed']));
}

$user_id = $_SESSION['user_id'];

$query = $db->prepare("SELECT sender, message, timestamp FROM messages WHERE recipient_id = ? ORDER BY timestamp DESC LIMIT 10");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = [
        'from' => $row['sender'],
        'content' => $row['message'],
        'date' => $row['timestamp']
    ];
}

$db->close();

echo json_encode($messages);
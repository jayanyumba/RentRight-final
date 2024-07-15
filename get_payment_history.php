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
    SELECT t.name as tenant, p.amount, p.payment_date, p.status
    FROM payments p
    JOIN tenants t ON p.user_id = t.user_id
    WHERE t.landlord_id = ?
    ORDER BY p.payment_date DESC
    LIMIT 20
");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

$payments = [];
while ($row = $result->fetch_assoc()) {
    $payments[] = [
        'tenant' => $row['tenant'],
        'amount' => $row['amount'],
        'date' => $row['payment_date'],
        'status' => $row['status']
    ];
}

$db->close();

echo json_encode($payments);
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

// Get last payment
$last_payment_query = $db->prepare("SELECT amount, payment_date FROM payments WHERE user_id = ? ORDER BY payment_date DESC LIMIT 1");
$last_payment_query->bind_param("i", $user_id);
$last_payment_query->execute();
$last_payment_result = $last_payment_query->get_result();
$last_payment = $last_payment_result->fetch_assoc();

// Get current balance
$balance_query = $db->prepare("SELECT balance FROM tenants WHERE user_id = ?");
$balance_query->bind_param("i", $user_id);
$balance_query->execute();
$balance_result = $balance_query->get_result();
$current_balance = $balance_result->fetch_assoc()['balance'];

$db->close();

echo json_encode([
    'lastPayment' => $last_payment['amount'],
    'lastPaymentDate' => $last_payment['payment_date'],
    'currentBalance' => $current_balance
]);
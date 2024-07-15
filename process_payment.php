<?php

$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "rent_right_payments"; // Replace with your database name


$conn = new mysqli('localhost', 'root', '', 'rent_right_rentals');
session_start();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $payment_method = $conn->real_escape_string($_POST['payment_method']);
    // $total_amount = $_POST['service'];
    $card_number = $conn->real_escape_string($_POST['card_number']);
    $expiry_date = $conn->real_escape_string($_POST['expiry_date']);
    $cvv = $conn->real_escape_string($_POST['cvv']);
    $mpesa_number = $conn->real_escape_string($_POST['mpesa_number']);
        $selectedServices = $_POST['service'];
        $totalPrice = 0;
        $servicePrices = [
            'Service 1' => 50,
            'Service 2' => 75,
            'Service 3' => 100
        ];
        foreach ($selectedServices as $service) {
            if (isset($servicePrices[$service])) {
                $totalPrice += $servicePrices[$service];
            }
        }
$user_id = $_SESSION['user_id'];
$sql = "INSERT INTO payments (tenant_id, payment_method, amount, transaction_id)
        VALUES ('$user_id', '$payment_method', '$totalPrice', '$mpesa_number')";

    if ($conn->query($sql) === TRUE) {
        echo "Payment recorded successfully!";
        header("Location: tenant_dashboard.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
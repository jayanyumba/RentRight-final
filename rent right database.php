<?php
$servername = "localhost";
$username = "root"; // replace with your database username
$password = ""; // replace with your database password
$dbname = "rent_right_registration";

// Create connection
$conn = new mysqli('localhost', 'root', '', 'rent_right_rentals');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle file uploads
function uploadFile($file) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($file["name"]);
    move_uploaded_file($file["tmp_name"], $target_file);
    return $target_file;
}

// Get form data and handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['role'])) {
    $role = $_POST['role'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $id_number = $_POST['id_number'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $passport_photo = uploadFile($_FILES['passport_photo']);

    if ($role === 'caretaker') {
        $sql = "INSERT INTO caretakers (fullname, email, id_number, passport_photo, phone, password) 
                VALUES ('$fullname', '$email', '$id_number', '$passport_photo', '$phone', '$password')";
    } elseif ($role === 'tenant') {
        $house_number = $_POST['house_number'];
        $family_members = $_POST['family_members'];
        $sql = "INSERT INTO tenants (fullname, email, id_number, passport_photo, phone, house_number, family_members, password) 
                VALUES ('$fullname', '$email', '$id_number', '$passport_photo', '$phone', '$house_number', '$family_members', '$password')";
    } elseif ($role === 'landlord') {
        $sql = "INSERT INTO landlords (fullname, email, phone, id_number, password) 
                VALUES ('$fullname', '$email', '$phone', '$id_number', '$password')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
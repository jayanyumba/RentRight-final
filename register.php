<?php

$conn = new mysqli('localhost', 'root', '', 'rent_right_rentals');


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = $conn->real_escape_string($_POST['fullname']);
    $password = $conn->real_escape_string($_POST['password']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);
    $id_number = $conn->real_escape_string($_POST['id_number']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $house_number = $conn->real_escape_string($_POST['house_number']);
    $family_members = $conn->real_escape_string($_POST['family_members']);

$sql = "INSERT INTO users (username, password, email, role, created_at)
        VALUES ('$username', md5('$password'), '$email', '$role', NOW())";
            if ($conn->query($sql) === TRUE) {

                $sql1 = "SELECT * FROM users
ORDER BY created_at DESC
LIMIT 1;";

$currentDate = new DateTime();
$currentDate->modify('+3 months');
$futureDate = $currentDate->format('Y-m-d');

        $query = mysqli_query($conn,$sql1);

        if(mysqli_num_rows($query) > 0){
$row = mysqli_fetch_assoc($query);
        	$uid = $row['id'];

    if ($role == 'tenant') {

    	$sql2 = "INSERT INTO `tenants`(`user_id`, `full_name`, `phone`, `id_number`, `house_number`, `family_members`, `rent_amount`, `balance`, `lease_start_date`, `lease_end_date`) VALUES('$uid','$fname','$phone','$id_number','$house_number','$family_members','0','0',NOW(),'$futureDate')";

    if ($conn->query($sql2) === TRUE) {
        // echo "Payment recorded successfully!";
        header("Location: login.html");
    } else {
        echo "Error: " . $sql2 . "<br>" . $conn->error;
    }
}else if ($role == 'landlord') {

    	$sql3 = "INSERT INTO `landlords`(`user_id`, `full_name`, `phone`, `id_number`)  VALUES('$uid','$fname','$phone','$id_number')";

    if ($conn->query($sql3) === TRUE) {
        // echo "Payment recorded successfully!";
        header("Location: login.html");
    } else {
        echo "Error: " . $sql3 . "<br>" . $conn->error;
    }
}
}
}else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
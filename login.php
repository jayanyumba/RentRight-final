<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'rent_right_rentals');

if(isset($_POST['login']) && $_SERVER['REQUEST_METHOD'] === 'POST'){

		$email = $_POST['email'];
		$password = $_POST['password'];
		
        $sql = "SELECT * FROM `users` WHERE `email`='$email'";

        $query = mysqli_query($conn,$sql);

        if(mysqli_num_rows($query) > 0){
            $row = mysqli_fetch_assoc($query);
            $pass = $row['password'];

        if(md5($password) == $pass){

        $type = $row['role'];
        $uid = $row['id'];
        $fname = $row['username'];

                if ($type == "tenant") {
                $_SESSION['user_id'] = $uid;
                $_SESSION['role'] = $type;
                $_SESSION['username'] = $fname;
                header("Location: tenant_dashboard.php");
                // echo $_SESSION['user_id'];
                // echo $_SESSION['role'];
                }else if ($type == "landlord") {
                $_SESSION['user_id'] = $uid;
                $_SESSION['role'] = $type;
                $_SESSION['username'] = $fname;
                header("Location: landlord_dashboard.php");
                }
                
    }else{
        echo "Passwords do not match, kindly try again.";
    }
    }else{
        echo "User not found, kindly try again.";
    }

}
           
?>
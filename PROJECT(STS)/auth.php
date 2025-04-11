<?php

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "sts";  

$con = new mysqli($host, $user, $pass, $db);

if ($con->connect_error) {
    die("<script>alert('Failed to connect to DB: " . $con->connect_error . "');</script>");
}

// Sign Up Logic
if (isset($_POST['signUp'])) {
    $fullName = mysqli_real_escape_string($con, $_POST['fullname']);
    $phoneNumber = mysqli_real_escape_string($con, $_POST['number']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = md5($_POST['password']); // Encrypt password

    // Check if email OR phone number already exists
    $checkQuery = "SELECT * FROM users WHERE email='$email' OR mobile='$phoneNumber'";
    $result = mysqli_query($con, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email or Phone Number Already Exists!'); window.location.href='login.php';</script>";
    } else {
        // Insert new user
        $insertQuery = "INSERT INTO users (name, email, mobile, password)
                        VALUES ('$fullName', '$email', '$phoneNumber', '$password')";

        if (mysqli_query($con, $insertQuery)) {
            echo "<script>alert('Sign Up Successful! Redirecting to Login...'); window.location.href='login.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
        }
    }
}

// Sign In Logic
if (isset($_POST['signIn'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = md5($_POST['password']); // Encrypt password for comparison
    
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        session_start();
        $row = mysqli_fetch_assoc($result);
        $_SESSION['email'] = $row['email'];
        echo "<script>alert('Login Successful! Redirecting...'); window.location.href='home1.php';</script>";
        exit();
    } else {
        echo "<script>alert('Incorrect Email or Password'); window.location.href='login.php';</script>";
    }
}

// Close connection
mysqli_close($con);

?>

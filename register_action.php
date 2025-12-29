<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {
        echo "<script>alert('Passwords do not match'); window.location='register.php';</script>";
        exit();
    }

        echo "<script>alert('Password must be at least 6 characters'); window.location='register.php';</script>";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = mysqli_query($conn, $check_query);

        echo "<script>alert('Username or Email already exists'); window.location='register.php';</script>";
        exit();
    }
    $insert_query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    if (mysqli_query($conn, $insert_query)) {
        echo "<script>alert('Registration successful! Please login.'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Registration failed. Please try again.'); window.location='register.php';</script>";
    }

    mysqli_close($conn);
}
?>

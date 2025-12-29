<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $type = $_POST['type'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $frequency = isset($_POST['frequency']) ? $_POST['frequency'] : '';

    $query = "INSERT INTO transactions (user_id, type, category, amount, date, description, frequency) 
              VALUES ('$user_id', '$type', '$category', '$amount', '$date', '$description', '$frequency')";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add transaction']);
    }

    mysqli_close($conn);
}
?>

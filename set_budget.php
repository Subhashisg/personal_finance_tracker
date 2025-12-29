<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $month = date('n');
    $year = date('Y');

    $check = "SELECT * FROM budgets WHERE user_id = '$user_id' AND category = '$category' AND month = '$month' AND year = '$year'";
    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) > 0) {
        $update = "UPDATE budgets SET amount = '$amount' WHERE user_id = '$user_id' AND category = '$category' AND month = '$month' AND year = '$year'";
        mysqli_query($conn, $update);
    } else {
        $insert = "INSERT INTO budgets (user_id, category, amount, month, year) VALUES ('$user_id', '$category', '$amount', '$month', '$year')";
        mysqli_query($conn, $insert);
    }

    echo json_encode(['success' => true]);
    mysqli_close($conn);
}
?>

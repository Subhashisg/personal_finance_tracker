<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$type = isset($_GET['type']) ? $_GET['type'] : 'all';

if ($type == 'all') {
    $query = "SELECT * FROM transactions WHERE user_id = '$user_id' ORDER BY date DESC";
} else {
    $query = "SELECT * FROM transactions WHERE user_id = '$user_id' AND type = '$type' ORDER BY date DESC";
}

$result = mysqli_query($conn, $query);
$transactions = array();

while ($row = mysqli_fetch_assoc($result)) {
    $transactions[] = $row;
}

echo json_encode(['success' => true, 'data' => $transactions]);
mysqli_close($conn);
?>

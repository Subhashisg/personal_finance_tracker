<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$month = date('n');
$year = date('Y');

$query = "SELECT b.category, b.amount as budget, 
          COALESCE(SUM(t.amount), 0) as spent
          FROM budgets b
          LEFT JOIN transactions t ON b.category = t.category 
          AND t.user_id = b.user_id 
          AND t.type = 'expense'
          AND MONTH(t.date) = b.month 
          AND YEAR(t.date) = b.year
          WHERE b.user_id = '$user_id' 
          AND b.month = '$month' 
          AND b.year = '$year'
          GROUP BY b.category, b.amount";

$result = mysqli_query($conn, $query);
$budgets = array();

while ($row = mysqli_fetch_assoc($result)) {
    $budgets[] = $row;
}

echo json_encode(['success' => true, 'data' => $budgets]);
mysqli_close($conn);
?>

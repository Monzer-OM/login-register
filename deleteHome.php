<?php
// deleteHome.php
session_start();
require_once 'database.php';

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Check if the home ID is provided
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    header('Location: adminDashboard.php');
    exit();
}

$homeId = intval($_POST['id']);

// Delete the home from the database
$sql = "DELETE FROM homes WHERE id = $homeId";

if (mysqli_query($conn, $sql)) {
    header('Location: adminDashboard.php');
    exit();
} else {
    echo "Error deleting home: " . mysqli_error($conn);
}
?>

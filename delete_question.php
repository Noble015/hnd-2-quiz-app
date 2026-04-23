<?php
session_start();
include 'db.php';

// ✅ Check admin login
if (!isset($_SESSION['admin'])) {
    die("Access denied!");
}

// ✅ Check if ID exists
if (!isset($_GET['id'])) {
    die("No question ID provided!");
}

$id = $_GET['id'];

// ✅ Optional: Check if question exists first
$stmt = $conn->prepare("SELECT id FROM questions WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Question not found!");
}

// ✅ Delete the question
$delete = $conn->prepare("DELETE FROM questions WHERE id=?");
$delete->bind_param("i", $id);

if ($delete->execute()) {
    // Redirect back after delete
    header("Location: admin_dashboard.php");
    exit();
} else {
    echo "Error deleting question!";
}
?>
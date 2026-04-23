<?php
$conn = new mysqli("localhost", "root", "", "hnd_2_quiz_app");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
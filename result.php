<?php
session_start();
if(!isset($_SESSION['user'])){ header("Location: user_login.php"); exit(); }

$score = $_GET['score'] ?? 0;
$total = $_GET['total'] ?? 0;
?>

<h2>Quiz Completed!</h2>
<p>Your score: <?php echo $score; ?> / <?php echo $total; ?></p>
<a href="quiz.php">Back to Quiz</a>
<a href="logout.php">Logout</a>
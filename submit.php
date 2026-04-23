<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

include 'db.php';
$user_id = $_SESSION['user_id'];
$score = 0;
$total = 0;

$result = $conn->query("SELECT * FROM questions");
while($row = $result->fetch_assoc()){
    $total++;
    $qid = $row['id'];
    $correct = $row['answer'];
    if(isset($_POST['q'.$qid]) && $_POST['q'.$qid] == $correct){
        $score++;
    }
}

$stmt = $conn->prepare("INSERT INTO scores(user_id, score, total_questions) VALUES (?, ?, ?)");
$stmt->bind_param("iii", $user_id, $score, $total);
$stmt->execute();
$stmt->close();

header("Location: result.php?score=$score&total=$total");
exit();
?>
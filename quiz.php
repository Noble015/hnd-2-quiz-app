<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

include 'db.php';
$result = $conn->query("SELECT * FROM questions");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz Page</title>
    <style>
        body { font-family: Arial; background: linear-gradient(to right, purple, green); color: white; text-align: center; }
        .quiz-container { width: 60%; margin: auto; background: rgba(0,0,0,0.6); padding: 20px; border-radius: 10px; margin-top: 50px; }
        .question { text-align: left; margin-bottom: 15px; }
        input[type="submit"] { padding: 10px 20px; font-weight: bold; background: white; color: purple; border: none; border-radius: 5px; cursor: pointer; }
        input[type="submit"]:hover { background: #ddd; }
        #timer { font-size: 18px; margin-bottom: 20px; }
        .logout { float: right; color: white; text-decoration: none; font-weight: bold; }
    </style>
    <script>
        let time = 5 * 60;
        function startTimer() {
            let timer = document.getElementById('timer');
            let interval = setInterval(() => {
                let minutes = Math.floor(time / 60);
                let seconds = time % 60;
                if(seconds<10) seconds='0'+seconds;
                timer.textContent = `Time remaining: ${minutes}:${seconds}`;
                time--;
                if(time<0){ clearInterval(interval); alert('Time up!'); document.getElementById('quizForm').submit(); }
            },1000);
        }
        window.onload = startTimer;
    </script>
</head>
<body>

<div class="quiz-container">
    <a class="logout" href="logout.php">Logout</a>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>! Take the Quiz:</h2>
    <div id="timer">Time remaining: 05:00</div>

    <form id="quizForm" action="submit.php" method="post">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="question">
                    <p><strong>Q<?php echo $row['id']; ?>:</strong> <?php echo htmlspecialchars($row['question']); ?></p>
                    <input type="radio" name="q<?php echo $row['id']; ?>" value="<?php echo htmlspecialchars($row['option1']); ?>" required> <?php echo htmlspecialchars($row['option1']); ?><br>
                    <input type="radio" name="q<?php echo $row['id']; ?>" value="<?php echo htmlspecialchars($row['option2']); ?>"> <?php echo htmlspecialchars($row['option2']); ?><br>
                    <input type="radio" name="q<?php echo $row['id']; ?>" value="<?php echo htmlspecialchars($row['option3']); ?>"> <?php echo htmlspecialchars($row['option3']); ?><br>
                    <input type="radio" name="q<?php echo $row['id']; ?>" value="<?php echo htmlspecialchars($row['option4']); ?>"> <?php echo htmlspecialchars($row['option4']); ?><br>
                </div>
                <hr>
                <?php
            }
        } else { echo "<p>No questions found.</p>"; }
        ?>
        <input type="submit" value="Submit Quiz">
    </form>
</div>

</body>
</html>
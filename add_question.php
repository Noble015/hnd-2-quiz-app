<?php
session_start();
include 'db.php';

// ✅ Ensure admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// ✅ Handle form submission
if (isset($_POST['add'])) {

    // Get form data safely
    $question = $_POST['question'];
    $option1  = $_POST['option1'];
    $option2  = $_POST['option2'];
    $option3  = $_POST['option3'];
    $option4  = $_POST['option4'];
    $answer   = $_POST['answer'];

    // ✅ Insert into database (MATCHES YOUR TABLE)
    $stmt = $conn->prepare("INSERT INTO questions (question, option1, option2, option3, option4, answer) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $question, $option1, $option2, $option3, $option4, $answer);

    if ($stmt->execute()) {
        $success = "Question added successfully!";
    } else {
        $error = "Error adding question: " . $conn->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Question</title>

    <style>
        body {
            font-family: Arial;
            background: linear-gradient(to right, purple, green);
            color: white;
            text-align: center;
        }

        .container {
            width: 50%;
            margin: auto;
            background: rgba(0,0,0,0.6);
            padding: 20px;
            border-radius: 10px;
            margin-top: 50px;
        }

        input[type="text"] {
            width: 90%;
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
            border: none;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background: white;
            color: purple;
            border: none;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background: #ddd;
        }

        .msg {
            margin-top: 10px;
            font-weight: bold;
        }

        .success { color: lightgreen; }
        .error { color: red; }

        a {
            color: white;
            text-decoration: none;
            display: block;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add New Question</h2>

    <!-- ✅ Success / Error Message -->
    <?php if (isset($success)) echo "<div class='msg success'>$success</div>"; ?>
    <?php if (isset($error)) echo "<div class='msg error'>$error</div>"; ?>

    <form method="post">
        <input type="text" name="question" placeholder="Enter Question" required><br>

        <input type="text" name="option1" placeholder="Option 1" required><br>
        <input type="text" name="option2" placeholder="Option 2" required><br>
        <input type="text" name="option3" placeholder="Option 3" required><br>
        <input type="text" name="option4" placeholder="Option 4" required><br>

        <input type="text" name="answer" placeholder="Correct Answer (must match one option)" required><br>

        <input type="submit" name="add" value="Add Question">
    </form>

    <a href="questions.php">⬅ Back to Manage Questions</a>
</div>

</body>
</html>
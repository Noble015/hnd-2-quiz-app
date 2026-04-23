<?php
session_start();
include 'db.php';

// ✅ Ensure admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle Add Question
if (isset($_POST['add'])) {
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $answer = $_POST['answer'];

    $stmt = $conn->prepare("INSERT INTO questions (question, option1, option2, option3, option4, answer) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $question, $option1, $option2, $option3, $option4, $answer);
    $stmt->execute();
    $stmt->close();
    header("Location: questions.php");
    exit();
}

// Handle Edit Question
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $answer = $_POST['answer'];

    $stmt = $conn->prepare("UPDATE questions SET question=?, option1=?, option2=?, option3=?, option4=?, answer=? WHERE id=?");
    $stmt->bind_param("ssssssi", $question, $option1, $option2, $option3, $option4, $answer, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: questions.php");
    exit();
}

// Handle Delete Question
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM questions WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: questions.php");
    exit();
}

// Fetch all questions
$result = $conn->query("SELECT * FROM questions ORDER BY id ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin: Manage Questions</title>
    <style>
        body { font-family: Arial; background: #222; color: white; text-align: center; }
        table { margin: auto; width: 90%; border-collapse: collapse; }
        th, td { border: 1px solid white; padding: 8px; }
        th { background: purple; }
        input[type="text"] { width: 90%; padding: 4px; }
        input[type="submit"] { padding: 6px 12px; margin-top: 5px; }
        .logout { float: right; color: white; text-decoration: none; font-weight: bold; margin: 10px; }
    </style>
</head>
<body>

<a class="logout" href="logout.php">Logout</a>
<h2>Admin Panel: Manage Questions</h2>

<h3>Add New Question</h3>
<form method="post">
    Question: <input type="text" name="question" required><br>
    Option 1: <input type="text" name="option1" required><br>
    Option 2: <input type="text" name="option2" required><br>
    Option 3: <input type="text" name="option3" required><br>
    Option 4: <input type="text" name="option4" required><br>
    Correct Answer: <input type="text" name="answer" required><br>
    <input type="submit" name="add" value="Add Question">
</form>

<hr>
<h3>Existing Questions</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Question</th>
        <th>Options</th>
        <th>Answer</th>
        <th>Actions</th>
    </tr>
    <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['question']); ?></td>
            <td>
                1: <?php echo htmlspecialchars($row['option1']); ?><br>
                2: <?php echo htmlspecialchars($row['option2']); ?><br>
                3: <?php echo htmlspecialchars($row['option3']); ?><br>
                4: <?php echo htmlspecialchars($row['option4']); ?>
            </td>
            <td><?php echo htmlspecialchars($row['answer']); ?></td>
            <td>
                <form method="post" style="display:inline-block;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="text" name="question" value="<?php echo htmlspecialchars($row['question']); ?>" required><br>
                    <input type="text" name="option1" value="<?php echo htmlspecialchars($row['option1']); ?>" required>
                    <input type="text" name="option2" value="<?php echo htmlspecialchars($row['option2']); ?>" required>
                    <input type="text" name="option3" value="<?php echo htmlspecialchars($row['option3']); ?>" required>
                    <input type="text" name="option4" value="<?php echo htmlspecialchars($row['option4']); ?>" required><br>
                    <input type="text" name="answer" value="<?php echo htmlspecialchars($row['answer']); ?>" required><br>
                    <input type="submit" name="edit" value="Update">
                </form>
                <a href="questions.php?delete=<?php echo $row['id']; ?>" style="color:red;">Delete</a>
            </td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
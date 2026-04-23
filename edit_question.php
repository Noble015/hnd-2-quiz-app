<?php
session_start();
include 'db.php';

// ✅ Check admin login
if (!isset($_SESSION['admin'])) {
    die("Access denied!");
}

// ✅ Check if ID is passed
if (!isset($_GET['id'])) {
    die("No question ID provided!");
}

$id = $_GET['id'];

// ✅ Fetch existing question
$stmt = $conn->prepare("SELECT * FROM questions WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Question not found!");
}

$question = $result->fetch_assoc();

// ✅ Handle update
if (isset($_POST['update'])) {

    $q  = $_POST['question'];
    $o1 = $_POST['option1'];
    $o2 = $_POST['option2'];
    $o3 = $_POST['option3'];
    $o4 = $_POST['option4'];
    $ans = $_POST['answer'];

    $update = $conn->prepare("
        UPDATE questions 
        SET question=?, option1=?, option2=?, option3=?, option4=?, answer=? 
        WHERE id=?
    ");

    $update->bind_param("ssssssi", $q, $o1, $o2, $o3, $o4, $ans, $id);

    if ($update->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Failed to update question!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Question</title>

<style>
body {
    font-family: Arial;
    background: linear-gradient(135deg, purple, green);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    color: white;
}

.container {
    width: 450px;
    background: rgba(0,0,0,0.7);
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.5);
}

h2 {
    text-align: center;
    margin-bottom: 15px;
}

input, select {
    width: 100%;
    padding: 10px;
    margin: 6px 0;
    border-radius: 5px;
    border: none;
}

input:focus {
    border: 2px solid #2ecc71;
}

button {
    width: 100%;
    padding: 10px;
    background: white;
    color: purple;
    border: none;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background: #ddd;
}

.error {
    color: red;
    text-align: center;
    margin-bottom: 10px;
}

a {
    display: block;
    text-align: center;
    margin-top: 10px;
    color: white;
    text-decoration: none;
}
</style>
</head>

<body>

<div class="container">
    <h2>Edit Question</h2>

    <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="post">

        <input type="text" name="question" value="<?= htmlspecialchars($question['question']) ?>" required>

        <input type="text" name="option1" value="<?= htmlspecialchars($question['option1']) ?>" required>
        <input type="text" name="option2" value="<?= htmlspecialchars($question['option2']) ?>" required>
        <input type="text" name="option3" value="<?= htmlspecialchars($question['option3']) ?>" required>
        <input type="text" name="option4" value="<?= htmlspecialchars($question['option4']) ?>" required>

        <!-- ✅ Dropdown for correct answer -->
        <select name="answer" required>
            <option value="">Select Correct Answer</option>
            <option value="<?= htmlspecialchars($question['option1']) ?>" <?= $question['answer'] == $question['option1'] ? 'selected' : '' ?>>Option 1</option>
            <option value="<?= htmlspecialchars($question['option2']) ?>" <?= $question['answer'] == $question['option2'] ? 'selected' : '' ?>>Option 2</option>
            <option value="<?= htmlspecialchars($question['option3']) ?>" <?= $question['answer'] == $question['option3'] ? 'selected' : '' ?>>Option 3</option>
            <option value="<?= htmlspecialchars($question['option4']) ?>" <?= $question['answer'] == $question['option4'] ? 'selected' : '' ?>>Option 4</option>
        </select>

        <button type="submit" name="update">Update Question</button>

    </form>

    <a href="admin_dashboard.php">⬅ Back to Dashboard</a>
</div>

</body>
</html>
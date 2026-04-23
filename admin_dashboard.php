<?php
session_start();
include __DIR__ . '/db.php';

// ✅ Admin check (use your real admin session if available)
if (!isset($_SESSION['admin'])) {
    die("Access denied!");
}

// ✅ Fetch Users (you are using username, not email)
$usersResult = $conn->query("SELECT id, username, is_admin FROM users");
$usersData = $usersResult ? $usersResult->fetch_all(MYSQLI_ASSOC) : [];

// ✅ Fetch Scores (your column is NOT created_at, use submitted_at)
$scoresResult = $conn->query("
    SELECT users.username, scores.score, scores.total_questions, scores.submitted_at
    FROM scores
    JOIN users ON scores.user_id = users.id
    ORDER BY scores.submitted_at DESC
");
$scoresData = $scoresResult ? $scoresResult->fetch_all(MYSQLI_ASSOC) : [];

// ✅ Fetch Questions
$questionsResult = $conn->query("SELECT * FROM questions");
$questionsData = $questionsResult ? $questionsResult->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>

<style>
body {
    font-family: Arial;
    background: #f4f6f9;
    padding: 20px;
}

h1, h2 {
    color: #333;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

th {
    background: #6a0dad;
    color: white;
}

tr:nth-child(even) {
    background: #f9f9f9;
}

tr:hover {
    background: #f1f1f1;
}

a {
    text-decoration: none;
    font-weight: bold;
}

.btn {
    padding: 5px 10px;
    border-radius: 5px;
    color: white;
    font-size: 14px;
}

.btn-edit {
    background: #28a745;
}

.btn-delete {
    background: #dc3545;
}

.btn:hover {
    opacity: 0.85;
}

.nav a {
    margin-right: 15px;
    color: #6a0dad;
}
</style>
</head>

<body>

<h1>Admin Dashboard</h1>

<div class="nav">
    <a href="add_question.php">➕ Add Question</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<!-- ================= USERS ================= -->
<h2>Registered Users</h2>
<table>
<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Role</th>
</tr>

<?php foreach($usersData as $u): ?>
<tr>
    <td><?= htmlspecialchars($u['id']) ?></td>
    <td><?= htmlspecialchars($u['username']) ?></td>
    <td><?= $u['is_admin'] ? 'Admin' : 'User' ?></td>
</tr>
<?php endforeach; ?>

</table>

<!-- ================= SCORES ================= -->
<h2>Quiz Scores</h2>
<table>
<tr>
    <th>User</th>
    <th>Score</th>
    <th>Total Questions</th>
    <th>Date</th>
</tr>

<?php foreach($scoresData as $s): ?>
<tr>
    <td><?= htmlspecialchars($s['username']) ?></td>
    <td><?= htmlspecialchars($s['score']) ?></td>
    <td><?= htmlspecialchars($s['total_questions']) ?></td>
    <td><?= htmlspecialchars($s['submitted_at']) ?></td>
</tr>
<?php endforeach; ?>

</table>

<!-- ================= QUESTIONS ================= -->
<h2>Quiz Questions</h2>
<table>
<tr>
    <th>ID</th>
    <th>Question</th>
    <th>Correct Answer</th>
    <th>Actions</th>
</tr>

<?php foreach($questionsData as $q): ?>
<tr>
    <td><?= htmlspecialchars($q['id']) ?></td>
    <td><?= htmlspecialchars($q['question']) ?></td>

    <!-- ✅ FIXED HERE: answer instead of correct_option -->
    <td><?= htmlspecialchars($q['answer']) ?></td>

    <td>
        <a class="btn btn-edit" href="edit_question.php?id=<?= $q['id'] ?>">Edit</a>
        <a class="btn btn-delete" href="delete_question.php?id=<?= $q['id'] ?>" onclick="return confirm('Delete this question?')">Delete</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>
<?php
session_start();
include __DIR__ . '/db.php';

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $range = trim($_POST['range_number']);

    if (empty($range)) {
        $msg = "Please enter your range number!";
    } else {
        $stmt = $conn->prepare("SELECT id, name FROM users WHERE range_number = ?");
        $stmt->bind_param("s", $range);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $name);
        $stmt->fetch();

        if ($stmt->num_rows == 1) {
            // Login success
            $_SESSION['user_id'] = $id;
            $_SESSION['name'] = $name;
            header("Location: quiz.php");
            exit();
        } else {
            $msg = "❌ Range number not found!";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <style>
        body { font-family: Arial; background:#f4f6f9; padding:20px; }
        form { background:#fff; padding:20px; width:350px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
        input { width:100%; padding:8px; margin-bottom:15px; border-radius:5px; border:1px solid #ccc; }
        button { width:100%; padding:10px; background:#007BFF; color:white; border:none; border-radius:5px; cursor:pointer; }
        button:hover { background:#0056b3; }
        .msg { margin-top:10px; font-weight:bold; }
        .error { color:red; }
        a { display:block; margin-top:15px; color:#007BFF; text-decoration:none; }
        a:hover { text-decoration:underline; }
    </style>
</head>
<body>

<h2>User Login</h2>
<form method="post">
    <label>Range Number:</label>
    <input type="text" name="range_number" required>
    <button type="submit">Login</button>
</form>

<?php if(!empty($msg)): ?>
<p class="msg error"><?= htmlspecialchars($msg) ?></p>
<?php endif; ?>

<a href="register.php">Register a new account</a>
</body>
</html>
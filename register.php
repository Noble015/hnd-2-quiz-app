<?php
session_start();
include __DIR__ . '/db.php';

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $range = trim($_POST['range_number']);

    if (empty($name) || empty($range)) {
        $msg = "Please fill in all fields!";
    } else {
        // Check if range number already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE range_number = ?");
        $stmt->bind_param("s", $range);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $msg = "❌ This range number is already registered!";
        } else {
            // Insert user (plain text range number)
            $insert = $conn->prepare("INSERT INTO users (name, range_number) VALUES (?, ?)");
            $insert->bind_param("ss", $name, $range);

            if ($insert->execute()) {
                // Redirect to login page after registration
                header("Location: login.php");
                exit();
            } else {
                $msg = "❌ Error: " . $conn->error;
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
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

<h2>Register New User</h2>
<form method="post">
    <label>Name:</label>
    <input type="text" name="name" required>

    <label>Range Number (Unique):</label>
    <input type="text" name="range_number" required>

    <button type="submit">Register</button>
</form>

<?php if(!empty($msg)): ?>
<p class="msg error"><?= htmlspecialchars($msg) ?></p>
<?php endif; ?>

<a href="login.php">Back to Login</a>
</body>
</html>
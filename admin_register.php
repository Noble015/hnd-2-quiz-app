<?php
$conn = new mysqli("localhost", "root", "", "hnd_2_quiz_app");

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // hash password (VERY IMPORTANT)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO admin (username, password) VALUES ('$username', '$hashed_password')";

    if ($conn->query($sql)) {
        $success = "Admin registered successfully!";
    } else {
        $error = "Username already exists!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Register</title>

    <style>
        body {
            font-family: Arial;
            background: linear-gradient(to right, purple, green);
            text-align: center;
            color: white;
        }

        .box {
            background: white;
            color: black;
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            border-radius: 10px;
        }

        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
        }

        .btn {
            background: purple;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            cursor: pointer;
        }

        .btn:hover {
            background: green;
        }

        .msg {
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Admin Registration</h2>

    <?php if (isset($success)) echo "<p class='msg' style='color:green;'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p class='msg' style='color:red;'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit" name="register" class="btn">Register</button>
    </form>

    <p><a href="admin_login.php">Go to Login</a></p>
</div>

</body>
</html>
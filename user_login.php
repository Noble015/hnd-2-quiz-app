<?php
session_start();
include 'db.php';

$error = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $username;
            $_SESSION['user_id'] = $user['id'];
            header("Location: quiz.php");
            exit();
        } else {
            $error = "Wrong password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, purple, green);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 12px;
            width: 350px;
            text-align: center;
            color: white;
            box-shadow: 0 8px 25px rgba(0,0,0,0.5);
            animation: fadeIn 0.6s ease-in-out;
        }

        h2 {
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        input {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: none;
            outline: none;
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
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #ddd;
            transform: scale(1.03);
        }

        .error {
            color: #ff4d4d;
            margin-bottom: 10px;
            font-weight: bold;
        }

        a {
            display: block;
            margin-top: 15px;
            color: #ddd;
            text-decoration: none;
        }

        a:hover {
            color: white;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

<div class="login-box">
    <h2>User Login</h2>

    <?php if ($error) echo "<div class='error'>$error</div>"; ?>

    <form method="post">
        <div class="input-group">
            <input type="text" name="username" placeholder="Enter Username" required>
        </div>

        <div class="input-group">
            <input type="password" name="password" placeholder="Enter Password" required>
        </div>

        <button type="submit" name="login">Login</button>
    </form>

    <a href="user_register.php">Don't have an account? Register</a>
</div>

</body>
</html>
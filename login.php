<?php
session_start();
include 'db_connect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['name'] = $name;

            // Optional: regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            // Redirect to homepage
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Odisha Tourism</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .input-field {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .login-btn {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s;
        }
        .login-btn:hover {
            background: #0056b3;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .register-link {
            margin-top: 10px;
            font-size: 14px;
        }
        .register-link a {
            color: #007bff;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Welcome Back!</h2>
        <?php if (!empty($error)) { echo '<p class="error-message">' . $error . '</p>'; } ?>
        <form method="post">
            <input type="email" name="email" class="input-field" placeholder="Email" required>
            <input type="password" name="password" class="input-field" placeholder="Password" required>
            <button type="submit" class="login-btn">Login</button>
        </form>
        <p class="register-link">Don't have an account? <a href="register.php">Register Here</a></p>
        <p class="register-link">GOTO <a href="index.php">Landing Page</a></p>
    </div>
</body>
</html>

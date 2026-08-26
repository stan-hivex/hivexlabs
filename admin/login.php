<?php
session_start();
require_once "../config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Fetch admin
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username=? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $admin = $res->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $admin['password'])) {

            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];

            header("Location: dashboard.php");
            exit();
        } 
        else {
            $error = "❌ Incorrect password!";
        }

    } else {
        $error = "❌ Admin user not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>HiveX Labs Admin Login</title>
<meta charset="UTF-8">
<style>
body {
    font-family: Arial;
    background: #111;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
}
.login-box {
    background: #1a1a1a;
    padding: 25px;
    border-radius: 8px;
    width: 350px;
}
input {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    background: #222;
    border: 1px solid #333;
    color: #fff;
}
button {
    width: 100%;
    padding: 12px;
    background: #ff5722;
    border: none;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
}
button:hover {
    background: #e64a19;
}
.error {
    color: #ff4444;
    margin-bottom: 10px;
    font-weight: bold;
}
</style>
</head>

<body>
<div class="login-box">
    <h2>HiveX Labs Admin</h2>

    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>

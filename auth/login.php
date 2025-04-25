<?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $conn = new mysqli("localhost", "root", "", "farmiq");
        $result = $conn->query("SELECT * FROM users WHERE username = '$username' AND password = '$password'");
        if ($result->num_rows > 0) {
            $_SESSION['user_id'] = $result->fetch_assoc()['id'];
            header('Location: ../dashboard.php');
        } else {
            echo "Invalid credentials";
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login - FarmIQ</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>
        <header>
            <h1>🌱 Login to FarmIQ</h1>
        </header>
        <main>
            <form method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Login</button>
            </form>
        </main>
    </body>
    </html>
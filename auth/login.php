<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $conn = new mysqli("localhost", "root", "", "farmiq");

    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    $result = $conn->query("SELECT * FROM users WHERE username = '$username' AND password = '$password'");
    if ($result->num_rows > 0) {
        $_SESSION['user_id'] = $result->fetch_assoc()['id'];
        header('Location: ../dashboard.php');
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Handy Crofter</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-tr from-green-100 via-white to-green-50 min-h-screen flex items-center justify-center font-sans">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-xl shadow-lg border border-gray-200">
        <h1 class="text-3xl font-bold text-center text-green-700">🌾 Login to HandyCrofter</h1>

        <?php if (!empty($error)): ?>
            <div class="p-3 text-sm text-red-700 bg-red-100 rounded-md">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label for="username" class="block mb-1 text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>

            <div>
                <label for="password" class="block mb-1 text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>

            <button type="submit"
                    class="w-full py-2 font-semibold text-white bg-green-600 rounded-md hover:bg-green-700 transition duration-200">
                Login
            </button>
        </form>

        <div class="text-sm text-center text-gray-600">
            Don’t have an account? <a href="register.php" class="text-green-600 hover:underline">Register</a>
        </div>
    </div>
</body>
</html>

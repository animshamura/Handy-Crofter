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
<body class="flex items-center justify-center min-h-screen font-sans text-gray-800 dark:bg-gray-900 dark:text-gray-100">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 w-full max-w-md">
        <h1 class="text-3xl font-bold text-center text-green-700">🌾 Login to HandyCrofter</h1>

        <?php if (!empty($error)): ?>
            <div class="p-3 text-sm text-red-700 bg-red-100 rounded-md">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label for="username" class="block mb-1 text-sm font-medium ">Username</label>
                <input type="text" id="username" name="username" required
                       class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div>
                <label for="password" class="block mb-1 text-sm font-medium ">Password</label>
                <input type="password" id="password" name="password" required
                       class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <button type="submit"
                    class="w-full py-2 font-semibold text-white bg-green-600 rounded-md hover:bg-green-700 transition duration-200">
                Login
            </button>
        </form>

        <div class="text-sm text-center">
            Don’t have an account? <a href="register.php" class="text-green-600 hover:underline">Register</a>
        </div>
    </div>
</body>
</html>

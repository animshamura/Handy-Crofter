<?php
session_start();

$conn = new mysqli("localhost", "root", "", "farmiq");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT id FROM users WHERE email = '$email'");
    if ($check->num_rows > 0) {
        $error = "Email is already registered.";
    } else {
        $conn->query("INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')");
        $_SESSION['user_id'] = $conn->insert_id;
        header('Location: ../dashboard.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="bg-green-50">
<head>
    <meta charset="UTF-8">
    <title>Register - HandyCrofter</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen font-sans text-gray-800 dark:bg-gray-900 dark:text-gray-100">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 w-full max-w-md">
        <h1 class="text-2xl font-bold text-green-700 mb-6 text-center">Create an Account</h1>
        
        <?php if (isset($error)): ?>
            <div class="mb-4 text-red-500 text-sm"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium">Username</label>
                <input type="text" id="username" name="username" required class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium">Email</label>
                <input type="email" id="email" name="email" required class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium">Password</label>
                <input type="password" id="password" name="password" required class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <button type="submit" class="w-full bg-green-600 hover:bg-green-500 text-white py-2 rounded-lg font-semibold">Register</button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-600 dark:text-gray-400">
            Already have an account?
            <a href="login.php" class="text-green-700 dark:text-green-400 font-semibold hover:underline">Login here</a>.
        </p>
    </div>
</body>
</html>

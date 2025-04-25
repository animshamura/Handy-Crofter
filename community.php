<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

$conn = new mysqli("localhost", "root", "", "farmiq");
$community_posts = $conn->query("SELECT * FROM community_posts");

// Fetch user information
$user_query = $conn->query("SELECT username, email FROM users WHERE id = {$_SESSION['user_id']}");
$user = $user_query->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en" class="bg-gray-100">
<head>
    <meta charset="UTF-8">
    <title>Community Posts</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen font-sans text-gray-900 bg-gray-50">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="bg-blue-600 w-64 min-h-screen p-6 text-white">
            <h2 class="text-2xl font-bold mb-8">🌾 HandyCrofter</h2>
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="dashboard.php" class="text-white hover:underline">Dashboard</a></li>
                        <li><a href="marketplace.php" class="text-white hover:underline">Marketplace</a></li>
                        <li><a href="tasks.php" class="text-white hover:underline">Tasks</a></li>
                    </ul>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-10">
            <!-- Header -->
            <header class="bg-blue-500 text-white py-6 shadow-md">
                <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
                    <h1 class="text-3xl font-bold">Community Posts</h1>
                    <div class="flex items-center gap-6">
                        <div class="text-sm">
                            <span class="block font-semibold"><?php echo $user['username']; ?></span>
                            <span class="text-gray-200"><?php echo $user['email']; ?></span>
                        </div>
                        <a href="auth/logout.php" class="px-4 py-2 bg-red-500 rounded-lg text-white text-sm shadow-md transition duration-300 hover:bg-red-600">Logout</a>
                    </div>
                </div>
            </header>

            <main class="max-w-7xl mx-auto px-4 py-10 grid gap-8">
                <!-- Community Posts List -->
                <section class="bg-white rounded-lg shadow-md p-6 col-span-1">
                    <div class="space-y-4">
                        <?php while ($post = $community_posts->fetch_assoc()): ?>
                            <div class="border p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition duration-300">
                                <h3 class="text-lg font-bold"><?php echo $post['title']; ?></h3>
                                <p class="mt-2 text-gray-600"><?php echo $post['content']; ?></p>
                                <button class="mt-2 text-sm text-blue-600 hover:underline">👍 Like</button>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </section>
            </main>
        </main>
    </div>
</body>
</html>

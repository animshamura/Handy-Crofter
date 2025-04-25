<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

$conn = new mysqli("localhost", "root", "", "farmiq");
$marketplace_items = $conn->query("SELECT * FROM marketplace_items");

?>

<!DOCTYPE html>
<html lang="en" class="bg-gray-100">
<head>
    <meta charset="UTF-8">
    <title>Marketplace - FarmIQ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen font-sans text-gray-900 bg-gray-50">
    <div class="flex">
        <!-- Sidebar (Same as in Dashboard) -->
        <aside class="bg-blue-600 w-64 min-h-screen p-6 text-white">
            <h2 class="text-2xl font-bold mb-8">🌾 HandyCrofter</h2>
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="dashboard.php" class="text-white hover:underline">Dashboard</a></li>
                        <li><a href="community.php" class="text-white hover:underline">Community</a></li>
                        <li><a href="tasks.php" class="text-white hover:underline">Tasks</a></li>
                    </ul>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-10">
            <header class="bg-blue-500 text-white py-6 shadow-md">
                <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
                    <h1 class="text-3xl font-bold">Marketplace</h1>
                </div>
            </header>

            <section class="max-w-7xl mx-auto px-4 py-10 grid gap-8 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                <?php while ($item = $marketplace_items->fetch_assoc()): ?>
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 p-4">
                        <?php if ($item['image_url']): ?>
                            <img src="<?php echo $item['image_url']; ?>" class="w-full h-32 object-cover rounded mb-3" alt="Item Image">
                        <?php endif; ?>
                        <div class="font-medium text-lg"><?php echo $item['item_name']; ?></div>
                        <div class="text-sm text-gray-500">৳<?php echo $item['price']; ?> | Qty: <?php echo $item['quantity']; ?></div>
                    </div>
                <?php endwhile; ?>
            </section>
        </main>
    </div>
</body>
</html>

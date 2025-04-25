<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

$conn = new mysqli("localhost", "root", "", "farmiq");
$tasks = $conn->query("SELECT * FROM tasks WHERE user_id = {$_SESSION['user_id']}");
$marketplace_items = $conn->query("SELECT * FROM marketplace_items");
$community_posts = $conn->query("SELECT * FROM community_posts");
$weather = file_get_contents("http://api.weatherapi.com/v1/current.json?key=da4eabdb68fb4339b01190433252504&q=Dhaka");
$weather = json_decode($weather, true);

// Fetch user information
$user_query = $conn->query("SELECT username, email FROM users WHERE id = {$_SESSION['user_id']}");
$user = $user_query->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en" class="bg-gray-100">
<head>
    <meta charset="UTF-8">
    <title>FarmIQ Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen font-sans text-gray-900 bg-gray-50">
    <div class="flex">
       <!-- Sidebar -->
<aside class="bg-blue-600 w-64 min-h-screen p-6 text-white transition-all duration-300 ease-in-out">
    <h2 class="text-2xl font-bold mb-8">🌾 HandyCrofter</h2>
    <div class="space-y-6">
        <div>
            <h3 class="text-lg font-semibold">Quick Links</h3>
            <ul class="space-y-2 text-sm">
                <!-- Links to Marketplace and Community Posts -->
                <li><a href="marketplace.php" class="text-white hover:underline">Marketplace</a></li>
                <li><a href="community.php" class="text-white hover:underline">Community</a></li>
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
                    <h1 class="text-3xl font-bold">Dashboard</h1>
                    <div class="flex items-center gap-6">
                        <div class="text-sm">
                            <span class="block font-semibold"><?php echo $user['username']; ?></span>
                            <span class="text-gray-200"><?php echo $user['email']; ?></span>
                        </div>
                        <a href="auth/logout.php" class="px-4 py-2 bg-red-500 rounded-lg text-white text-sm shadow-md transition duration-300 hover:bg-red-600">Logout</a>
                    </div>
                </div>
            </header>

            <main class="max-w-7xl mx-auto px-4 py-10 grid gap-8 md:grid-cols-3">
                <!-- Weather -->
                <section class="bg-white rounded-lg shadow-md p-6 col-span-1">
                    <h2 class="text-xl font-semibold mb-4 text-blue-600">Current Weather</h2>
                    <div class="flex justify-between items-center">
                        <div class="text-lg font-semibold"><?php echo $weather['location']['name']; ?></div>
                        <img src="<?php echo $weather['current']['condition']['icon']; ?>" class="w-12 h-12" alt="Weather Icon">
                    </div>
                    <div class="text-sm text-gray-500"><?php echo $weather['current']['condition']['text']; ?></div>
                    <div class="font-medium text-blue-600">Temperature: <?php echo $weather['current']['temp_c']; ?>°C</div>
                    <div class="text-sm text-gray-500">Wind: <?php echo $weather['current']['wind_kph']; ?> km/h</div>
                </section>

                <!-- Tasks -->
                <section class="bg-white rounded-lg shadow-md p-6 col-span-1">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-blue-600">Your Tasks</h2>
                        <button onclick="toggleModal('taskModal')" class="px-3 py-2 bg-blue-500 text-white rounded-lg text-sm transition duration-300 hover:bg-blue-600">+ Add Task</button>
                    </div>
                    <ul id="taskList" class="space-y-4">
    <?php $index = 0; ?>
    <?php while ($task = $tasks->fetch_assoc()): ?>
        <li class="bg-blue-50 p-4 rounded-lg flex justify-between items-center shadow-md <?php echo $index > 0 ? 'hidden extra-task' : ''; ?>">
            <div>
                <div class="font-medium"><?php echo htmlspecialchars($task['task_name']); ?></div>
                <div class="text-sm text-gray-500"><?php echo date('F j, Y', strtotime($task['due_date'])); ?></div>
            </div>
            <div class="flex gap-2">
                <form action="delete_task.php" method="POST">
                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm transition duration-300">Delete</button>
                </form>
                <button onclick="openUpdateModal(<?php echo $task['id']; ?>, '<?php echo addslashes($task['task_name']); ?>', '<?php echo $task['due_date']; ?>')" class="text-blue-500 hover:text-blue-700 text-sm transition duration-300">Update</button>
            </div>
        </li>
        <?php $index++; ?>
    <?php endwhile; ?>
</ul>

<?php if ($index > 1): ?>
    <div class="mt-4">
        <a href="tasks.php" class="text-blue-600 hover:underline text-sm">View All Tasks</a>
    </div>
<?php endif; ?>

  </section>

                <!-- Marketplace -->

    <section class="bg-white rounded-lg shadow-md p-6 col-span-1">
    <h2 class="text-xl font-semibold mb-4 text-blue-600">Marketplace</h2>
    <div class="grid gap-6">
        <?php
        // Fetch a single item for display in the dashboard (first item or featured item)
        $marketplace_item = $conn->query("SELECT * FROM marketplace_items LIMIT 1")->fetch_assoc();
        if ($marketplace_item):
        ?>
            <div class="border p-4 rounded-lg hover:bg-gray-100 transition duration-300">
                <?php if ($marketplace_item['image_url']): ?>
                    <img src="<?php echo $marketplace_item['image_url']; ?>" class="w-full h-32 object-cover rounded mb-3" alt="Item Image">
                <?php endif; ?>
                <div class="font-medium text-lg"><?php echo $marketplace_item['item_name']; ?></div>
                <div class="text-sm text-gray-500">৳<?php echo $marketplace_item['price']; ?> | Qty: <?php echo $marketplace_item['quantity']; ?></div>
            </div>
        <?php endif; ?>

        <!-- Link to View More Items -->
        <div class="mt-4 text-center">
            <a href="marketplace.php" class="text-blue-600 hover:underline">View More Items</a>
        </div>
    </div>
</section>


                <!-- Community Posts -->
              <!-- Community Post (Only One Post on Dashboard) -->
<section class="bg-white rounded-lg shadow-md p-6 col-span-1">
    <h2 class="text-xl font-semibold mb-4 text-blue-600">Community Posts</h2>
    <div class="space-y-4">
        <?php
        // Fetch one community post for display on the dashboard
        $community_post = $conn->query("SELECT * FROM community_posts LIMIT 1")->fetch_assoc();
        if ($community_post):
        ?>
            <div class="border p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition duration-300">
                <h3 class="text-lg font-bold"><?php echo $community_post['title']; ?></h3>
                <p class="mt-2 text-gray-600"><?php echo $community_post['content']; ?></p>
                <button class="mt-2 text-sm text-blue-600 hover:underline">👍 Like</button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Link to View All Posts -->
    <div class="mt-4 text-center">
        <a href="community.php" class="text-blue-600 hover:underline">View All Posts</a>
    </div>
    
</section>

<!-- Add Task Modal -->
<div id="taskModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white rounded-lg p-6 shadow-md w-96">
        <h2 class="text-xl font-semibold text-blue-600 mb-4">Add New Task</h2>
        <form action="add_task.php" method="POST">
            <div class="mb-4">
                <label class="block text-sm">Task Name</label>
                <input type="text" name="task_name" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm">Due Date</label>
                <input type="date" name="due_date" class="w-full px-3 py-2 border rounded" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded">Add Task</button>
        </form>
        <button onclick="toggleModal('taskModal')" class="mt-4 text-gray-500 hover:underline w-full">Cancel</button>
        <!-- Add View Tasks Button -->
        <a href="tasks.php" class="mt-4 text-blue-500 hover:underline w-full block text-center">View All Tasks</a>
    </div>
</div>


    <!-- Script -->
    <script>
        function toggleModal(id) {
            document.getElementById(id).classList.toggle('hidden');
        }

        function openUpdateModal(taskId, taskName, dueDate) {
            document.getElementById('update_task_id').value = taskId;
            document.getElementById('update_task_name').value = taskName;
            document.getElementById('update_due_date').value = dueDate;
            toggleModal('updateTaskModal');
        }
    </script>
</body>
</html>

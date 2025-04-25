<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "farmiq");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch tasks using prepared statement
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="bg-blue-600 w-64 p-6 text-white">
        <h2 class="text-2xl font-bold mb-8">🌾 HandyCrofter</h2>
        <div class="space-y-6">
            <div>
                <h3 class="text-lg font-semibold">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="dashboard.php" class="text-white hover:underline">Dashboard</a></li>
                    <li><a href="marketplace.php" class="text-white hover:underline">Marketplace</a></li>
                    <li><a href="community.php" class="text-white hover:underline">Community</a></li>
                </ul>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 p-6">
        <h2 class="text-2xl font-semibold text-blue-600 mb-4">My Tasks</h2>
        <button onclick="toggleModal('taskModal')" class="bg-blue-500 text-white px-4 py-2 rounded mb-6">Add New Task</button>

        <!-- Task Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($task = $result->fetch_assoc()): ?>
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($task['task_name']); ?></h3>
                        <p class="mt-2 text-gray-600">Due Date: <?php echo date('F j, Y', strtotime($task['due_date'])); ?></p>
                        <div class="mt-4 flex justify-between">
                            <button onclick="openUpdateModal(<?php echo $task['id']; ?>, <?php echo json_encode($task['task_name']); ?>, '<?php echo $task['due_date']; ?>')" class="text-blue-500 hover:text-blue-700">Edit</button>
                            <form action="delete_task.php" method="POST" class="inline-block">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-gray-500 col-span-full">No tasks found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

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
    </div>
</div>

<!-- Update Task Modal -->
<div id="updateTaskModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white rounded-lg p-6 shadow-md w-96">
        <h2 class="text-xl font-semibold text-blue-600 mb-4">Update Task</h2>
        <form action="update_task.php" method="POST">
            <input type="hidden" name="task_id" id="update_task_id">
            <div class="mb-4">
                <label class="block text-sm">Task Name</label>
                <input type="text" name="task_name" id="update_task_name" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm">Due Date</label>
                <input type="date" name="due_date" id="update_due_date" class="w-full px-3 py-2 border rounded" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded">Update Task</button>
        </form>
        <button onclick="toggleModal('updateTaskModal')" class="mt-4 text-gray-500 hover:underline w-full">Cancel</button>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
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

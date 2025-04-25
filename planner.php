<?php
    session_start();
    $conn = new mysqli("localhost", "root", "", "farmiq");

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $task_name = $_POST['task-name'];
        $due_date = $_POST['due-date'];
        $priority = $_POST['priority'];
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT INTO tasks (user_id, task_name, due_date, priority) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $task_name, $due_date, $priority);
        $stmt->execute();
        $stmt->close();
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Farm Task Planner - FarmIQ</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <header>
            <h1>🌿 FarmIQ - Task Planner</h1>
        </header>
        <main>
            <form method="POST">
                <label for="task-name">Task Name:</label>
                <input type="text" id="task-name" name="task-name" required>

                <label for="due-date">Due Date:</label>
                <input type="date" id="due-date" name="due-date" required>

                <label for="priority">Priority:</label>
                <select id="priority" name="priority" required>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>

                <button type="submit">Add Task</button>
            </form>
        </main>
    </body>
    </html>
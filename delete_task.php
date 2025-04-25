<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])) {
    $task_id = $_POST['task_id'];
    
    // Database connection
    $conn = new mysqli("localhost", "root", "", "farmiq");
    
    // Check for any connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Delete the task from the database
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        // Redirect back to the dashboard
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Error deleting task: " . $conn->error;
    }

    // Close the connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>

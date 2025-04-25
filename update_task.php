<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

$conn = new mysqli("localhost", "root", "", "farmiq");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $task_name = $conn->real_escape_string($_POST['task_name']);
    $due_date = $conn->real_escape_string($_POST['due_date']);

    $sql = "UPDATE tasks SET task_name = '$task_name', due_date = '$due_date' WHERE id = $task_id AND user_id = {$_SESSION['user_id']}";

    if ($conn->query($sql) === TRUE) {
        header('Location: dashboard.php');
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

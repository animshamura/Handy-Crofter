<?php
session_start();
$conn = new mysqli("localhost", "root", "", "farmiq");
$task = $_POST['task_name'];
$due = $_POST['due_date'];
$status = $_POST['status'];
$user_id = $_SESSION['user_id'];

$conn->query("INSERT INTO tasks (user_id, task_name, due_date, status) VALUES ($user_id, '$task', '$due', '$status')");
header("Location: dashboard.php");
exit;
?>

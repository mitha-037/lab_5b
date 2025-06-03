<?php
session_start();
include 'db.php';

if (!isset($_SESSION['matric'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['matric'])) {
    die("User matric not specified.");
}

$matric = $_GET['matric'];

// Delete user
$stmt = $conn->prepare("DELETE FROM users WHERE matric = ?");
$stmt->bind_param("s", $matric);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: users.php?msg=deleted");
    exit();
} else {
    die("Failed to delete user: " . $conn->error);
}

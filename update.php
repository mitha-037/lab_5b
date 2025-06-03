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

$originalMatric = $_GET['matric'];
$error = "";
$success = "";

// Fetch current data
$stmt = $conn->prepare("SELECT matric, name, role FROM users WHERE matric = ?");
$stmt->bind_param("s", $originalMatric);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("User not found.");
}

$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newMatric = trim($_POST['matric']);
    $name = trim($_POST['name']);
    $role = $_POST['role'];

    if (empty($newMatric) || empty($name) || empty($role)) {
        $error = "Please fill in all fields.";
    } else {
        // Check if new matric already exists (and is different from original)
        if ($newMatric !== $originalMatric) {
            $checkStmt = $conn->prepare("SELECT matric FROM users WHERE matric = ?");
            $checkStmt->bind_param("s", $newMatric);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            if ($checkResult->num_rows > 0) {
                $error = "Matric number already exists. Please choose a different one.";
            }
            $checkStmt->close();
        }

        if (!$error) {
            $updateStmt = $conn->prepare("UPDATE users SET matric = ?, name = ?, role = ? WHERE matric = ?");
            $updateStmt->bind_param("ssss", $newMatric, $name, $role, $originalMatric);

            if ($updateStmt->execute()) {
                $success = "User updated successfully.";
                $originalMatric = $newMatric; // update original matric for next operations
                $user['matric'] = $newMatric;
                $user['name'] = $name;
                $user['role'] = $role;
            } else {
                $error = "Update failed: " . $conn->error;
            }
            $updateStmt->close();
        }
    }
}
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update User</title>
</head>
<body>

<h2>Update User</h2>

<?php if ($error): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color:green;"><?php echo htmlspecialchars($success); ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Matric:</label><br>
    <input type="text" name="matric" value="<?php echo htmlspecialchars($user['matric']); ?>" required><br><br>

    <label>Name:</label><br>
    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br><br>

    <label>Role:</label><br>
    <select name="role" required>
        <option value="">-- Select Role --</option>
        <option value="student" <?php if ($user['role'] == 'student') echo 'selected'; ?>>Student</option>
        <option value="lecturer" <?php if ($user['role'] == 'lecturer') echo 'selected'; ?>>Lecturer</option>
    </select><br><br>

    <input type="submit" value="Update">
</form>

<p><a href="users.php">Back to User List</a></p>

</body>
</html>

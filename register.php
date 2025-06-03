<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    if ($role == "") {
        echo "<p style='color:red;'>Please select a role.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (matric, name, password, role) VALUES (?, ?, ?, ?)");

        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ssss", $matric, $name, $password, $role);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Registration successful!</p>";
        } else {
            echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h2>User Registration</h2>
    <form method="POST" action="register.php">
        <label for="matric">Matric Number:</label><br>
        <input type="text" id="matric" name="matric" required><br><br>

        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <label for="role">Role:</label><br>
        <select id="role" name="role" required>
            <option value="">-- Please select --</option>
            <option value="Lecturer">Lecturer</option>
            <option value="Student">Student</option>
        </select><br><br>

        <input type="submit" value="Register">
    </form>

    <p><a href="login.php">Already have an account? Login here</a></p>
</body>
</html>

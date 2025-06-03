<?php
session_start();
include 'db.php';

// Block access if not logged in
if (!isset($_SESSION['matric'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User List</title>
    <style>
        table {
            border-collapse: collapse;
            width: 60%;
        }
        th, td {
            border: 1px solid #333;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>

<h2>Registered Users</h2>


    <table>
    <tr>
        <th>Matric</th>
        <th>Name</th>
        <th>Level</th>
        <th>Update</th>
        <th>Delete</th>
    </tr>

    <?php
    $sql = "SELECT matric, name, role FROM users";
    $result = $conn->query($sql);

    if ($result === false) {
        die("SQL Error: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['matric']) . "</td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['role']) . "</td>
                    <td><a href='update.php?matric=" . urlencode($row['matric']) . "'>Update</a></td>
                    <td><a href='delete.php?matric=" . urlencode($row['matric']) . "' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a></td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No users found</td></tr>";
    }
    ?>
</table>



<br>
<a href="logout.php">Logout</a>

</body>
</html>

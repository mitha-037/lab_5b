<!-- dashboard.php -->
<?php
session_start();
if (!isset($_SESSION['loggedIn'])) {
  header("Location: login.php");
  exit;
}

$conn = new mysqli("localhost", "root", "", "Lab_5b");
$result = $conn->query("SELECT id, matric, name, accessLevel FROM users");

echo "<table border='1'>
<tr><th>Matric</th><th>Name</th><th>Access Level</th><th>Actions</th></tr>";
while ($row = $result->fetch_assoc()) {
  echo "<tr>
    <td>{$row['matric']}</td>
    <td>{$row['name']}</td>
    <td>{$row['accessLevel']}</td>
    <td>
      <a href='edit.php?id={$row['id']}'>Edit</a> |
      <a href='delete.php?id={$row['id']}'>Delete</a>
    </td>
  </tr>";
}
echo "</table>";
?>

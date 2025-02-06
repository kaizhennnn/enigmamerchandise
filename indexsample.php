<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "bogart"; // Change or leave empty "" if no password
$database = "test_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['name']) || !isset($_POST['email'])) {
        die("❌ Missing form data.");
    }

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    // Debugging: Print received data
    echo "Received Data: <br>";
    echo "Name: " . htmlspecialchars($name) . "<br>";
    echo "Email: " . htmlspecialchars($email) . "<br>";

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("❌ Invalid email format.");
    }

    // Insert using prepared statements
    $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $email);

    if ($stmt->execute()) {
        echo "✅ New record created successfully!";
    } else {
        echo "❌ Error inserting data: " . $stmt->error;
    }

    $stmt->close();
} else {
    die("❌ Invalid request method.");
}

$conn->close();
?>

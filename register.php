<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "bogart"; // Update if needed
$database = "enigma_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("❌ Database Connection Failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['names']) || !isset($_POST['surnames']) || !isset($_POST['email']) || !isset($_POST['password'])) {
        die("❌ Missing form data.");
    }

    $names = trim($_POST['names']);
    $surnames = trim($_POST['surnames']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("❌ Invalid email format.");
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO login (names, surnames, email, `password`) VALUES (?, ?, ?, ?)");

    if (!$stmt) {
        die("❌ SQL Prepare Failed: " . $conn->error); // Debugging
    }

    // Bind parameters
    $stmt->bind_param("ssss", $names, $surnames, $email, $hashed_password);

    // Execute query
    if ($stmt->execute()) {
        echo "✅ New record created successfully!";
    } else {
        echo "❌ Error inserting data: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
} else {
    die("❌ Invalid request method.");
}

// Close database connection
$conn->close();
?>

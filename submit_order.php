<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "bogart"; // Update if needed
$database = "enigma_db"; // Your database name

// Create database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("❌ Database Connection Failed: " . $conn->connect_error);
} else {
    echo "✅ Database Connected Successfully!<br>";
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "✅ Form submitted successfully!<br>"; // Debugging line

    // Validate and sanitize form data
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $contact = isset($_POST['contact']) ? trim($_POST['contact']) : '';
    $location = isset($_POST['location']) ? trim($_POST['location']) : '';
    $service = isset($_POST['service']) ? trim($_POST['service']) : '';

    // Ensure that all fields are filled
    if (empty($name) || empty($email) || empty($contact) || empty($location) || empty($service)) {
        die("❌ Please fill in all fields.");
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("❌ Invalid email format.");
    }

    // Set price based on the selected service
    $price = 0;
    switch ($service) {
        case 'digitalPrinting':
            $price = 500;
            break;
        case 'silkScreen':
            $price = 700;
            break;
        case 'sublimation':
            $price = 800;
            break;
        case 'umbrella':
            $price = 1000;
            break;
        case 'bags':
            $price = 1200;
            break;
        case 'mugs':
            $price = 350;
            break;
        case 'lanyard':
            $price = 200;
            break;
        case 'pvcId':
            $price = 150;
            break;
        case 'pisoPrint':
            $price = 100;
            break;
        case 'typingJob':
            $price = 400;
            break;
        case 'tshirtPrint':
            $price = 600;
            break;
        default:
            $price = 0;
    }

    // Prepare SQL query to insert data into the database
    $stmt = $conn->prepare("INSERT INTO orders (name, email, contact, location, service, price) VALUES (?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("❌ SQL Prepare Failed: " . $conn->error);
    }

    // Bind parameters and execute the query
    $stmt->bind_param("sssssd", $name, $email, $contact, $location, $service, $price);

    if ($stmt->execute()) {
        echo "✅ New order placed successfully!<br>";
    } else {
        echo "❌ Error inserting data: " . $stmt->error . "<br>";
    }

    // Close statement
    $stmt->close();
} else {
    // Print the actual request method for debugging
    echo "❌ Invalid request method: " . $_SERVER["REQUEST_METHOD"] . "<br>"; // Debugging line
}

// Close database connection
$conn->close();
?>

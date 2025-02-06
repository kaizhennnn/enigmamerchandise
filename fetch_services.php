<?php
require "db_connect.php"; // Database connection

$sql = "SELECT id, name, price FROM services";
$result = $conn->query($sql);

$services = [];
while ($row = $result->fetch_assoc()) {
    $services[] = implode(",", [$row["id"], $row["name"], $row["price"]]);
}

echo implode("\n", $services);
$conn->close();
?>

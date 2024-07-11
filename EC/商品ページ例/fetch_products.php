<?php
$servername = "localhost";
$username = "user1";
$password = "passwordA1!";
$dbname = "ecdatabase";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT id, name, price, description, stock, image1, image2, image3 FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

$product = $result->fetch_assoc();

$conn->close();

echo json_encode($product);
?>
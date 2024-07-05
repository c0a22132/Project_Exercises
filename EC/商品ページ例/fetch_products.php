<?php
require '../login/database_config.php'; // database_config.php を読み込む

// Create connection
$conn = new mysqli(parse_url(DSN)['host'], DB_USER, DB_PASS, substr(parse_url(DSN)['path'], 1));

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
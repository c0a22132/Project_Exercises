<?php
// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Ensure the products table exists
$tableCheckQuery = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT NOT NULL,
    stock INT NOT NULL,
    image1 VARCHAR(255),
    image2 VARCHAR(255),
    image3 VARCHAR(255)
)";

if ($conn->query($tableCheckQuery) !== TRUE) {
    die("Error creating table: " . $conn->error);
}

// Handle file uploads
function uploadImage($file, $target_dir) {
    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($file["tmp_name"]);

    if ($check === false) {
        return false;
    }

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;
    } else {
        return false;
    }
}

$target_dir = "uploads/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$image1 = uploadImage($_FILES["image1"], $target_dir);
$image2 = isset($_FILES["image2"]) && !empty($_FILES["image2"]["name"]) ? uploadImage($_FILES["image2"], $target_dir) : "";
$image3 = isset($_FILES["image3"]) && !empty($_FILES["image3"]["name"]) ? uploadImage($_FILES["image3"], $target_dir) : "";

if (!$image1) {
    die("画像1のアップロードに失敗しました。");
}

$name = $_POST['name'];
$price = $_POST['price'];
$description = $_POST['description'];
$stock = $_POST['stock'];

$sql = "INSERT INTO products (name, price, description, stock, image1, image2, image3) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("sdsisss", $name, $price, $description, $stock, $image1, $image2, $image3);

if ($stmt->execute()) {
    echo "商品が登録されました。";
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>

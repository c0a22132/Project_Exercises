<?php
// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "user1";
$password = "passwordA1!";
$dbname = "ecdatabese";

// 画像フォルダーのパス
$imagePath = '商品ページ例/';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';

$sql = "SELECT id, name, price, description, stock, image1, image2, image3 FROM products WHERE name LIKE ? OR description LIKE ?";
$stmt = $conn->prepare($sql);
$searchQuery = '%' . $query . '%';
$stmt->bind_param("ss", $searchQuery, $searchQuery);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>検索結果</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
</head>
<body>
    <div class="container">
        <h2>検索結果</h2>
        <?php if (count($products) > 0): ?>
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col s4">
                        <div class="card">
                            <div class="card-image">
                                <img src="<?php echo htmlspecialchars($imagePath . $product['image1']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            </div>
                            <span class="card-title"><?php echo htmlspecialchars($product['name']); ?></span>
                            <div class="card-content">
                                <p><?php echo htmlspecialchars($product['description']); ?></p>
                                <p>価格: ¥<?php echo htmlspecialchars($product['price']); ?></p>
                                <p>在庫: <?php echo htmlspecialchars($product['stock']); ?></p>
                            </div>
                            <div class="card-action">
                                <a href="product.php?id=<?php echo htmlspecialchars($product['id']); ?>">More Info</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>該当する商品が見つかりませんでした。</p>
        <?php endif; ?>
    </div>

    <!-- Materialize JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>

<?php
// tagsテーブルが存在しない場合は作成
$createTagsTableQuery = "CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
)";
if ($conn->query($createTagsTableQuery) !== TRUE) {
    die("Error creating tags table: " . $conn->error);
}

// productsテーブルにtagsカラムを追加（既に存在する場合はスキップ）
$addColumnQuery = "ALTER TABLE products ADD COLUMN IF NOT EXISTS tags VARCHAR(255)";
if ($conn->query($addColumnQuery) !== TRUE) {
    die("Error adding tags column: " . $conn->error);
}

// タグの処理
$tagsInput = $_POST['tags'];
$tagsArray = explode(',', $tagsInput);
$tagsIds = [];

foreach ($tagsArray as $tagName) {
    $tagName = trim($tagName);
    if (!empty($tagName)) {
        // タグをtagsテーブルに挿入（既に存在する場合はそのIDを使用）
        $insertTagQuery = "INSERT INTO tags (name) VALUES (?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)";
        $stmt = $conn->prepare($insertTagQuery);
        $stmt->bind_param("s", $tagName);
        if ($stmt->execute()) {
            $tagsIds[] = $conn->insert_id;
        } else {
            die("Error inserting tag: " . $stmt->error);
        }
    }
}

// タグIDをカンマ区切りの文字列として結合
$tagsIdsString = implode(',', $tagsIds);

// 商品登録クエリの変更
$sql = "INSERT INTO products (name, price, description, stock, image1, image2, image3, tags) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sdsissss", $name, $price, $description, $stock, $image1, $image2, $image3, $tagsIdsString);

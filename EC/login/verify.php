<?php
// database_config.phpからデータベース接続設定
require 'database_config.php';

// クエリパラメータからトークンを取得
$token = $_GET['token'] ?? '';

if (!empty($token)) {
    // トークンを検証
    $sql = "SELECT user_id FROM user_verification WHERE token = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$token]);
    $userId = $stmt->fetchColumn();

    if ($userId) {
        // アカウントを有効化
        $sql = "UPDATE users SET verified = 1 WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);

        // トークンを削除
        $sql = "DELETE FROM user_verification WHERE token = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$token]);

        echo 'アカウントが正常に有効化されました。';
    } else {
        echo '無効なトークンです。';
    }
} else {
    echo 'トークンが提供されていません。';
}
?>
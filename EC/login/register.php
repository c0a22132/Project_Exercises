<?php
session_start();
require 'database_config.php'; // データベース接続情報を含むファイル


//サイトのアドレスを変数化
$site_url = "http://localhost/EC/login/";

// POSTデータを受け取る
$lastName = $_POST['last_name'] ?? '';
$firstName = $_POST['first_name'] ?? '';
$birthday = $_POST['birthday'] ?? '';
$zipcode = $_POST['zipcode'] ?? '';
$prefecture = $_POST['prefecture'] ?? '';
$city = $_POST['city'] ?? '';
$street = $_POST['street'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// データ検証（簡易的な例）
if (empty($lastName) || empty($firstName) || empty($email) || empty($password)) {
    // 必須フィールドが空の場合はエラー
    die('必須フィールドが入力されていません。');
}

// パスワードをハッシュ化
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

try {
    // データベース接続
    $pdo = new PDO(DSN, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL文を準備
    $address = $zipcode . ' ' . $prefecture . ' ' . $city . ' ' . $street;
    $sql = "INSERT INTO users (last_name, first_name, birthday, address, email, password_hash) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    // SQL文を実行
    $stmt->execute([$lastName, $firstName, $birthday, $address, $email, $passwordHash]);

    // ユーザー登録後の処理
    $userId = $pdo->lastInsertId(); // 最後に挿入された行のIDを取得

    // 確認トークンを生成
    $token = bin2hex(random_bytes(16));

    // トークンをデータベースに保存
    $sql = "INSERT INTO user_verification (user_id, token) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId, $token]);

    // 確認メールを送信
    $to = $email;
    $subject = 'アカウントの確認';
    $message = "以下のリンクをクリックしてアカウントを確認してください:\n\n$site_url/verify.php?token=$token";
    $headers = 'From: noreply@yourwebsite.com' . "\r\n" .
        'Reply-To: noreply@yourwebsite.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);

    // 完了メッセージ
    echo '登録が完了しました。';
} catch (PDOException $e) {
    die('データベースエラー: ' . $e->getMessage());
}
?>
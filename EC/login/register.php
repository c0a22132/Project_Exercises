<?php
ini_set('display_errors', "On");
error_reporting(E_ALL);
session_start();
require 'database_config.php'; // Replace with your database configuration

// Database connection
$pdo = new PDO(DSN, DB_USER, DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create or select database
$dbname = 'ecdatabase';
$pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
$pdo->exec("USE $dbname");

try {
    // Create users table
    $createUsersTable = "CREATE TABLE IF NOT EXISTS users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        last_name VARCHAR(255) NOT NULL,
        first_name VARCHAR(255) NOT NULL,
        birthday DATE NOT NULL,
        address VARCHAR(255),
        email VARCHAR(255) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL
    )";
    $pdo->exec($createUsersTable);

    // Create usertags table
    $createUserTagsTable = "CREATE TABLE IF NOT EXISTS usertags (
        tag_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        tag VARCHAR(255),
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    )";
    $pdo->exec($createUserTagsTable);

    // Create fingerprints table
    $createFingerprintsTable = "CREATE TABLE IF NOT EXISTS fingerprints (
        fingerprint_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        fingerprint VARCHAR(255),
        FOREIGN KEY (user_id) REFERENCES users(user_id)
    )";
    $pdo->exec($createFingerprintsTable);

    // Create user_verification table
    $createUserVerificationTable = "CREATE TABLE IF NOT EXISTS user_verification (
        verification_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        token VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id)
    )";
    $pdo->exec($createUserVerificationTable);

    // Handle POST data
    $lastName = $_POST['last_name'] ?? '';
    $firstName = $_POST['first_name'] ?? '';
    $birthday = $_POST['birthday'] ?? '';
    $zipcode = $_POST['zipcode'] ?? '';
    $prefecture = $_POST['prefecture'] ?? '';
    $city = $_POST['city'] ?? '';
    $street = $_POST['street'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Basic data validation
    if (empty($lastName) || empty($firstName) || empty($email) || empty($password)) {
        die('必須フィールドが入力されていません。');
    }

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement for inserting into users table
    $address = $zipcode . ' ' . $prefecture . ' ' . $city . ' ' . $street;
    $sql = "INSERT INTO users (last_name, first_name, birthday, address, email, password_hash) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    // Execute SQL statement
    $stmt->execute([$lastName, $firstName, $birthday, $address, $email, $passwordHash]);

    // Get last inserted user_id
    $userId = $pdo->lastInsertId();

    // Send confirmation email
    $subject = 'アカウントの確認';
    $message = "以下の情報で登録しました。\n\n";
    $message .= "氏名: {$lastName} {$firstName}\n";
    $message .= "メールアドレス: {$email}\n\n";

    $headers = array(
        'From' => 'test@test',
        'Reply-To' => 'test@test',
        'X-Mailer' => 'PHP/' . phpversion()
    );
    mail($email, $subject, $message, $headers);

    // Confirmation message
    $output = '登録が完了しました。';
} catch (PDOException $e) {
    die('データベースエラー: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <title>登録</title>
</head>
<body>
    <nav>
        <div class="nav-wrapper">
            <a href="#" class="brand-logo center">登録完了</a>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col s12">
                <p class="flow-text"><?php echo $output; ?></p>
                <button onclick="location.href='../index.php'">メインページへ</button>
            </div>
        </div>
    </div>
</body>
</html>

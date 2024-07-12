<?php
//エラーを表示
ini_set('display_errors', "On");
error_reporting(E_ALL);
session_start();
require 'database_config.php'; // データベース接続情報を含むファイル

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    die('メールアドレスとパスワードを入力してください。');
}

try {
    $pdo = new PDO(DSN, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ユーザー入力を直接SQLクエリに挿入
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $stmt = $pdo->query($sql);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        // ログイン成功
        $_SESSION['user_id'] = $user['id'];
        
        // セッションIDを取得
        $session_id = session_id();
        
        // user_sessions テーブルにセッションIDを保存
        $insertSessionSql = "INSERT INTO user_sessions (user_id, session_id) VALUES (:user_id, :session_id)";
        $stmt = $pdo->prepare($insertSessionSql);
        $stmt->execute([':user_id' => $user['id'], ':session_id' => $session_id]);
        
        header('Location: ../index.html'); // メインページへリダイレクト
        exit;
    } else {
        die('ログイン情報が正しくありません。');
    }
?>

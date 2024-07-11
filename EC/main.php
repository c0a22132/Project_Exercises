<!DOCTYPE html>
<html lang="ja">
<head>
	<!-- Materialize CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ショッピングサイトへようこそ</title>
</head>
<body>
	<header>
		<nav>
			<div class="nav-wrapper">
				<a href="#" class="brand-logo">ショッピングサイトへようこそ</a>
				<ul id="nav-mobile" class="right hide-on-med-and-down">
					<li><a href="main.php" class="waves-effect waves-light btn">ショッピングへ</a></li>
					<li><a href="./login/login.html" class="waves-effect waves-light btn">ログイン</a></li>
					<li><a href="./login/register.html" class="waves-effect waves-light btn">新規登録</a></li>
				</ul>
			</div>
		</nav>
	</header>
	
	<main>
		<div class="container">
			<h2>Featured Products</h2>
			<!-- Search Form -->
			<form action="search.php" method="GET">
				<div class="input-field">
					<input id="search" type="text" name="query" required>
					<label for="search">商品を検索</label>
				</div>
				<button type="submit" class="waves-effect waves-light btn">検索</button>
			</form>
			<!-- Products Display -->
			<div class="row">
				<?php
				require './login/database_config.php';
                //画像のアップロード先ディレクトリ
                $uploadDir = './商品ページ例';
                //商品ページ
                $merchandise = './商品ページ例/merchandise.html';
				$pdo = new PDO(DSN, DB_USER, DB_PASS);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$pdo->exec("USE ecdatabase");

				$stmt = $pdo->query("SELECT * FROM products");
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					echo '<div class="col s4">';
					echo '<div class="card">';
					echo '<div class="card-image">';
                    echo '<img src="' . $uploadDir . '/' . $row['image1'] . '" alt="' . $row['name'] . '">';
					echo '</div>';
					echo '<span class="card-title">' . $row['name'] . '</span>';
					echo '<div class="card-content">';
					echo $row['description'];
					echo '</div>';
					echo '<div class="card-action">';
					echo '<a href="' . $merchandise . '?id=' . $row['product_id'] . '">詳細を見る</a>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
				}
				?>
			</div>
		</div>
	</main>
	
	<footer class="page-footer">
		<!-- Footer Content -->
	</footer>

	<!-- Materialize JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
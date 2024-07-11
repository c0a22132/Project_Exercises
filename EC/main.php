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
                    <li><a href="main.html" class="waves-effect waves-light btn">ショッピングへ</a></li>
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
			<div class="row">
				<?php
				require '../login/database_config.php'; // データベース接続情報
				$pdo = new PDO(DSN, DB_USER, DB_PASS);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$pdo->exec("USE ecdatabase"); // データベース選択

				$stmt = $pdo->query("SELECT * FROM products");
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					echo '<div class="col s4">';
					echo '<div class="card">';
					echo '<div class="card-image">';
					echo '<img src="' . $row['image1'] . '" alt="' . $row['name'] . '">';
					echo '</div>';
					echo '<span class="card-title">' . $row['name'] . '</span>';
					echo '<div class="card-content">';
					echo $row['description'];
					echo '</div>';
					echo '<div class="card-action">';
					echo '<a href="#">More Info</a>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
				}
				?>
			</div>
		</div>
	</main>
	<footer class="page-footer">
        <div class="container">
            <div class="row">
                <div class="col l6 s12">
                    <h5 class="white-text">Your Shopping Site</h5>
                    <p class="grey-text text-lighten-4">ここにサイトの詳細情報を記載します。</p>
                </div>
            </div>
        </div>
        <div class="container">
            &copy; 2022 Your Shopping Site. All rights reserved.
        </div>
    </footer>

    <!-- Materialize JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.carousel');
            var instances = M.Carousel.init(elems, {
                fullWidth: true,
                indicators: true
            });

            // 自動スクロール機能
            setInterval(function() {
                var instance = M.Carousel.getInstance(elems[0]); // 最初のカーセルインスタンスを取得
                instance.next(); // 次のスライドへ移動
            }, 5000); // 5秒ごとに実行
        });
    </script>
</body>
</html>
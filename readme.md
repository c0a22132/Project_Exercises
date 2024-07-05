```
/ (root)
│
├── index.html (トップページ)
├── login/
│   ├── login.html (ログインページ)
│   └── register.html (新規登録ページ)
├── products/
│   ├── index.html (全商品一覧ページ)
│   ├── product1.html (商品1の詳細ページ)
│   ├── product2.html (商品2の詳細ページ)
│   └── ... (その他の商品ページ)
├── images/
│   ├── ... (商品の画像ファイル)
│   └── ... (その他の画像ファイル)
└── ... (その他のページ)

データベース名はecdatabese

CREATE TABLE users (
    user_id INTEGER AUTO_INCREMENT PRIMARY KEY,
    last_name VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    birthday DATE NOT NULL,
    address VARCHAR(255),
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
);
CREATE TABLE user_verification (
    verification_id INTEGER AUTO_INCREMENT PRIMARY KEY,
    user_id INTEGER NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
--------------------
TODO
--------------------
□検索バー
□ページとデータベース
□レビュー
□カード情報
□ポイント
--------------------
```

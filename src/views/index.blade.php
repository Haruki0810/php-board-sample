<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>犬の掲示板</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h1>犬の掲示板</h1>
    <h2>検索</h2>
    <aside>
        <form action="search.php" method="post">
            <input type="text" name="search_name" placeholder="名前で検索">
            <input type="submit" name="submit" value="検索">
        </form>
        
            <h2>投稿する</h2>
            <form method="post" action="">
                <p>
                    <label for="name">タイトル:</label>
                    <input type="text" id="name" name="name" required>
                </p>
                <p>
                    <label for="title">犬種:</label>
                    <input type="text" id="title" name="title" required>
                </p>
                <p>
                    <label for="content">本文:</label>
                    <textarea id="content" name="content" rows="5" required></textarea>
                </p>
                <button type="submit">投稿する</button>
            </form>
        
    </aside>

    <h2>投稿一覧</h2>
    <?php foreach ($posts as $post): ?>
    <div class="post">
        <h3>[<?php echo htmlspecialchars($post['id']); ?>] <?php echo htmlspecialchars($post['title']); ?></h3>
        <p><strong><?php echo htmlspecialchars($post['name']); ?></strong> - <?php echo htmlspecialchars($post['created_at']); ?></p>

        <!-- 本文をURLがリンクとして表示する -->
        <p>
            <?php
                $content = htmlspecialchars($post['content']);
                // URLをリンクとして変換（URL部分のみ）
                $content = preg_replace('/(https?:\/\/[a-zA-Z0-9\/?=&#_.-]+)/', '<a href="$1" target="_blank">$1</a>', $content);
                echo nl2br($content); // 改行も保持
            ?>
        </p>

        <!-- 削除ボタン -->
        <form class="deleteform" method="post" action="">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($post['id']); ?>">
            <button type="submit" onclick="return confirm('この投稿を削除してもよろしいですか？')">削除</button>
        </form>
    </div>
    <?php endforeach; ?>
</body>

</html>
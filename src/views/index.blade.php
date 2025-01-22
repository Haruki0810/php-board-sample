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
        <details>
            <summary>投稿する</summary>
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
        </details>
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

        <!-- いいねボタン表示 -->
        <?php
        // $my_like配列が有効か確認
        if (!isset($my_like)) {
            $my_like = [];
        }
        $liked_posts = array_column($my_like, 'post_id');
        $is_liked = in_array($post['id'], $liked_posts);
        $current_page = isset($page) ? htmlspecialchars($page) : 1;
        ?>

        <?php if (!$is_liked): ?>
        <a class="heart" href="index.php?like=<?php echo htmlspecialchars($post['id']); ?>&page=<?php echo $current_page; ?>">&#9825;</a>
        <?php else: ?>
        <a class="heart red" href="index.php?like=<?php echo htmlspecialchars($post['id']); ?>&page=<?php echo $current_page; ?>">&#9829;</a>
        <?php endif; ?>
        <span><?php echo !empty($post['like_cnt']) ? htmlspecialchars($post['like_cnt']) : '0'; ?> いいね</span>

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
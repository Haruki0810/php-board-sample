<?php
//3-1. メッセージ別のいいねされた件数をDBから取り出す
$posts = $db->prepare('SELECT m.name, m.picture, p.*, COUNT(l.post_id) AS like_cnt FROM members m, posts p LEFT JOIN likes l ON p.id=l.post_id WHERE m.id=p.member_id GROUP BY l.post_id ORDER BY p.created DESC LIMIT ?, 5');
$posts->bindParam(1, $start, PDO::PARAM_INT);
$posts->execute();

//1-2. いいねボタン
if (isset($_REQUEST['like'])) {

  //いいねを押したメッセージの投稿者を調べる
  $contributor = $db->prepare('SELECT member_id FROM posts WHERE id=?');
  $contributor->execute(array($_REQUEST['like']));
  $pressed_message = $contributor->fetch();

  //1-3. いいねを押した人とメッセージ投稿者が同一人物でないか確認
  if (isset($_REQUEST['like'])) {
    $contributor = $db->prepare('SELECT member_id FROM posts WHERE id=?');
    $contributor->execute(array($_REQUEST['like']));
    $pressed_message = $contributor->fetch();

    if ($_SESSION['id'] != $pressed_message['member_id']) {
        $pressed = $db->prepare('SELECT COUNT(*) AS cnt FROM likes WHERE post_id=? AND member_id=?');
        $pressed->execute(array($_REQUEST['like'], $_SESSION['id']));
        $my_like_cnt = $pressed->fetch();

        if ($my_like_cnt['cnt'] < 1) {
            $press = $db->prepare('INSERT INTO likes SET post_id=?, member_id=?, created=NOW()');
            $press->execute(array($_REQUEST['like'], $_SESSION['id']));
        } else {
            $cancel = $db->prepare('DELETE FROM likes WHERE post_id=? AND member_id=?');
            $cancel->execute(array($_REQUEST['like'], $_SESSION['id']));
        }
        header("Location: index.php?page=" . (isset($page) ? $page : 1));
        exit();
    }
}
}

//2-1. ログインしている人がいいねしたメッセージをすべて取得
$like = $db->prepare('SELECT post_id FROM likes WHERE member_id=?');
$like->execute(array($_SESSION['id']));
while ($like_record = $like->fetch()) {
  $my_like[] = $like_record;
}

  $my_like_cnt = 0;
  if (!empty($my_like)) {
    foreach ($my_like as $like_post) {
      foreach ($like_post as $like_post_id) {
        if ($like_post_id == $post['id']) {
          $my_like_cnt = 1;
        }
      }
    }
  }
  ?>

<?php if ($my_like_cnt < 1) : ?>
  <a class="heart" href="index.php?like=<?php echo h($post['id']); ?>&page=<?php echo h($page); ?>">&#9825;</a>
<?php else : ?>
  <a class="heart red" href="index.php?like=<?php echo h($post['id']); ?>&page=<?php echo h($page); ?>">&#9829;</a>
<?php endif; ?>
<span><?php echo h($post['like_cnt']); ?></span>
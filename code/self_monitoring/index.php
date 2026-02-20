<?php
session_start();
require_once('utility/common_func.php');
require_once('config/constants.php');
//ログイン認証
$userId = checkLogin();

$key = $_GET['key'] ?? '';
$msg = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);
?>

<!DOCTYPE html>
<html lang="ja">
<?php require_once('utility/head.php'); ?>

<body>
  <div class="container"> <!-- 自動的に左右に適切な余白 -->
    <div class="mb-3">
    </div>
    <?php
    try {
      //DB接続
      $dbh = getDbConnection();
      if (!empty($key)) {
        $sql = 'SELECT * FROM feeling_items WHERE user_id = ? AND (event LIKE ? OR distortion LIKE ? OR feeling LIKE ? OR awareness LIKE ?)';
        $stmt = $dbh->prepare($sql);
        $searchWord = '%' . $key . '%';
        $data = [$userId, $searchWord, $searchWord, $searchWord, $searchWord];
        $stmt->execute($data);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($items)) {
          $msg = '検索結果はありません。';
        }
      } else {
        $sql = 'SELECT * FROM feeling_items  WHERE user_id = ? ORDER BY date DESC';
        $stmt = $dbh->prepare($sql);
        $data = [$userId];
        $stmt->execute($data);

        $dbh = null;

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
      }
    } catch (Exception $e) {
      echo 'DB接続エラー: ' . $e->getMessage();
      exit;
    }
    ?>


    <header>
      <?php require_once('utility/navbar.php'); ?>
    </header>
    <h2>フィールログ一覧</h2>
    <?php if ($msg): ?>
      <p style="background-color: #cce5ff; color: #004085; padding: 10px; border-radius: 5px;"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">No.</th>
          <th scope="col">日付</th>
          <th scope="col">出来事</th>
          <th scope="col">認知の歪み</th>
          <th scope="col">感情レベル</th>
          <th scope="col">感情</th>
          <th scope="col">気づき</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <?php $row = 1;
        foreach ($items as $item): ?>
          <?php $items = escapeArrayValues($items); ?>
          <tr>
            <td><?= $row++ ?></td>
            <td><?= $item['date'] ?></td>
            <td><?= truncateWithEllipsis($item['event'], 20) ?></td>
            <td><?= truncateWithEllipsis($item['distortion'], 20) ?></td>
            <td>
              <?= FEELING_LEVELS[$item['feeling_level']]['label'] ?? '' ?>
            </td>
            <td><?= truncateWithEllipsis($item['feeling'], 20) ?></td>
            <td><?= truncateWithEllipsis($item['awareness'], 20) ?></td>
            <td><a href="detail.php?id=<?= urlencode($item['id']); ?>">詳細</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <br>
    <a href="registration.php">新規登録</a>
  </div> <!-- コンテナの終了 -->
</body>

</html>
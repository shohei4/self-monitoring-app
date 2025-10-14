<?php
// セッション開始
session_start();
require_once("utility/common_func.php");

//ログイン認証
checkLogin();

$id = $_GET["id"];

try {
    $dbh = getDbConnection();
    $sql = "SELECT * FROM feeling_items WHERE id=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$id]);
    $dbh = null;
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    $item = escapeArrayValues($item);
} catch (PDOException $e) {
    echo '接続失敗: ' . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<?php require_once('utility/head.php'); ?>
<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">

        <div class="card shadow-sm rounded">
          <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><?= $item["date"] ?> の記録</h4>
          </div>
          <div class="card-body">

            <ul class="list-group list-group-flush mb-4">
              <li class="list-group-item"><strong>出来事:</strong> <?= $item["event"] ?></li>
              <li class="list-group-item"><strong>認知の歪み:</strong> <?= $item["distortion"] ?></li>
              <li class="list-group-item"><strong>感情レベル:</strong> <?= $item["feeling_level"] ?></li>
              <li class="list-group-item"><strong>感情:</strong> <?= $item["feeling"] ?></li>
              <li class="list-group-item"><strong>気づき:</strong> <?= $item["awareness"] ?></li>
            </ul>

            <div class="d-flex justify-content-between">
              <a href="updateForm.php?id=<?= $item['id'] ?>" class="btn btn-outline-primary">編集</a>
              <a href="delete.php?id=<?= $item['id'] ?>" class="btn btn-outline-danger">削除</a>
              <a href="index.php" class="btn btn-secondary">一覧へ戻る</a>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
</body>
</html>

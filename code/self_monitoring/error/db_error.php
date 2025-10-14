<?php
require_once("../utility/common_func.php");
// セッション開始
session_start();
// POST受け取り
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']); // エラーを表示した後はクリア   
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>エラー</title>
</head>
<body>
    <p>
        <?php foreach($errors as $error => $error_msg): ?>
        <?= htmlspecialchars($error_msg, ENT_QUOTES, 'UTF-8') ?><br>
        <?php endforeach; ?>
    </p>

    <a href="/self_monitoring/index.php">トップページに戻る</a>
</body>
</html>

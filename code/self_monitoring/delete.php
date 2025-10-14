<?php
// セッション開始
session_start();
require_once("utility\common_func.php");
//ログイン認証
$userId = checkLogin();

$id = $_GET["id"];

try{
    //データベース接続
    $dbh = getDbConnection();
    $sql = "DELETE FROM feeling_items WHERE id=? AND user_id=?";
    $data = [];
    $data = [$id, $userId];
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $dbh = null;
    $_SESSION['msg'] = "記録を削除しました。";
    header("Location: index.php");
} catch(Exception $e) {
    $msg = urlencode($e->getMessage());
    header("Location: db_error.php?msg=$msg");
    exit();
}
?>
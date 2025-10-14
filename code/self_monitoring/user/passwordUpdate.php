<?php
session_start();

require_once __DIR__ . '/../utility/common_func.php';
require_once __DIR__ . '/../utility/validation.php';
// ログインチェック
$userId = checkLogin();

// POST受け取り
$password = $_POST["password"] ?? '';
$errors = [];
// ① バリデーション
addError($errors, 'password', isRequired($password, 'パスワード'));
addError($errors, 'password', isMinLength($password, 8, 'パスワード'));
addError($errors, 'password', isMaxLength($password, 64, 'パスワード'));

// ② バリデーションエラーがあればフォームに戻す
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: passwordUpdateForm.php");
    exit();
}
// ③ パスワードハッシュ化 & DB更新
try {
    $dbh = getDbConnection();
    $hash_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $dbh->prepare("UPDATE users SET password=? WHERE id=?");
    $stmt->execute([$hash_password, $userId]);
    $dbh = null;
    //　更新成功時のメッセージ
    $_SESSION['msg'] = "パスワードを更新しました。";
    // ログアウト処理
    unset($_SESSION['user_id'], $_SESSION['username']); // セション内のユーザー情報をクリア
    header("Location: loginForm.php");
    exit();
} catch (Exception $e) {
    error_log($e->getMessage());
    $_SESSION['errors'] = ['db_error' => "システムエラーが発生しました。"];
    header("Location: /self_monitoring/error/db_error.php");
    exit();
}
?>
<?php
session_start();

require_once("../utility/common_func.php");
require_once("../utility/validation.php");

// POST受け取り
$username = trim($_POST["username"] ?? '');
$email = trim($_POST["email"] ?? '');
$password = $_POST["password"] ?? '';

$errors = [];

// ① バリデーション
addError($errors, 'username', isRequired($username, 'ユーザー名'));
addError($errors, 'username', isMaxLength($username, 15, 'ユーザー名'));
addError($errors, 'email', isRequired($email, 'メールアドレス'));
addError($errors, 'email', isEmailFormat($email, 'メールアドレス'));
addError($errors, 'password', isMaxLength($email, 255, 'メールアドレス'));
addError($errors, 'password', isRequired($password, 'パスワード'));
addError($errors, 'password', isMinLength($password, 8, 'パスワード'));
addError($errors, 'password', isMaxLength($password, 64, 'パスワード'));

// DB接続してユニークチェック
try {
    $dbh = getDbConnection();
    $stmt = $dbh->prepare("SELECT COUNT(*) FROM users WHERE email=?");
    $stmt->execute([$email]);
    addError($errors, 'email', isUnique($stmt->fetchColumn(), 'メールアドレス'));
} catch (Exception $e) {
    $_SESSION['errors']['db_error'] = "システムエラーが発生しました。";
    header("Location: /self_monitoring/error/db_error.php");
    exit();
}

// ② バリデーションエラーがあればフォームに戻す
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = ['username' => $username, 'email' => $email];
    header("Location: userRegisterForm.php");
    exit();
}

// ③ パスワードハッシュ化 & DB登録
$hash_password = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $dbh->prepare("INSERT INTO users(username,email,password) VALUES (?,?,?)");
    $stmt->execute([$username, $email, $hash_password]);
    $dbh = null;
    // セッションの旧入力値をクリア
    unset($_SESSION['old']);
    // 登録完了メッセージをセッションに保存
    $_SESSION['msg'] = "ユーザー情報を登録しました。";
    header("Location: loginForm.php");
    exit();
} catch (Exception $e) {
    error_log($e->getMessage());
    $_SESSION['errors'] = ['db_error' => "システムエラーが発生しました。"];
    header("Location: /self_monitoring/error/db_error.php");
    exit();
}
?>

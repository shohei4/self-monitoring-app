<?php
session_start();
require_once("../utility/common_func.php");
require_once("../utility/validation.php");

// ログインチェック
$userId = checkLogin();
 
// POST受け取り
$username = trim($_POST["username"] ?? '');
$email = trim($_POST["email"] ?? '');

//errors配列の初期化
$errors = [];
// ① バリデーション
addError($errors, 'username', isRequired($username, 'ユーザー名'));
addError($errors, 'username', isMaxLength($username, 15, 'ユーザー名'));
addError($errors, 'email', isRequired($email, 'メールアドレス'));
addError($errors, 'email', isEmailFormat($email, 'メールアドレス'));
addError($errors, 'password', isMaxLength($email, 255, 'メールアドレス'));
try {
    // emailのユニークチェック
    $dbh = getDbConnection();
    $sql = "SELECT COUNT(*) FROM users WHERE email=? AND id != ?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$email, $userId]);
    $count = $stmt->fetchColumn();
    addError($errors, 'email', isUnique($count, 'メールアドレス'));

    // ② バリデーションエラーがあればフォームに戻す
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = ['username' => $username, 'email' => $email];
        header("Location: userUpdateForm.php");
        exit();
    }
    
} catch (Exception $e) {
    $_SESSION['errors'] = [
        'db_error' => "システムエラーが発生しました。"
    ];
    header("Location: /self_monitoring/error/db_error.php");
    exit();
}

// ユーザー情報の更新
try {
    $sql = "UPDATE users SET username=?, email=? WHERE id=?";
    $stmt = $dbh->prepare($sql);
    $data = [$username, $email, $userId];
    $stmt->execute($data);
    $dbh = null;
    // セッションの旧入力値をクリア
    unset($_SESSION['old']);
    // 更新完了メッセージをセッションに保存
    $_SESSION['msg'] = "ユーザー情報を更新しました。";
    // ログアウト処理
    unset($_SESSION['user_id'], $_SESSION['username']); // セション内のユーザー情報をクリア
    header("Location: loginForm.php");
    exit();
} catch (Exception $e) {
    $_SESSION['errors'] = [
        'db_error' => "システムエラーが発生しました。"
    ];
    header("Location: /self_monitoring/error/db_error.php");
    exit();
}

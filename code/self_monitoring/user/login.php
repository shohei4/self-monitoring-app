<?php
session_start();
require_once("../utility/common_func.php");
$email = $_POST["email"];
$password = $_POST["password"];

//バリデーション
require_once("../utility/validation.php");
$errors = [];
addError($errors, 'email', isRequired($email, 'メールアドレス'));
addError($errors, 'email', isEmailFormat($email, 'メールアドレス'));
addError($errors, 'password', isRequired($password, 'パスワード'));
addError($errors, 'password', isMinLength($password, 8, 'パスワード'));
addError($errors, 'password', isMaxLength($password, 64, 'パスワード'));
//エラーがあればログインフォームに戻す
if(!empty($errors)){
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = ['email' => $email];
    header("Location: loginForm.php");
    exit();
}

try{
    $dbh = getDbConnection();
    $sql = "SELECT * FROM users WHERE email=?";
    $data = [$email];
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $dbh = null;

    if($user && password_verify($password, $user['password'])){
        //ログイン成功
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['msg'] = "ログインに成功しました。";

        header("Location: /self_monitoring/index.php");
        exit();
    } else {
        //ログイン失敗
        $_SESSION['errors'] = ['login_error' => "Emailまたはパスワードを間違えています。"];
        $_SESSION['old'] = ['email' => $email, 'password' => $password];
        header("Location: loginForm.php");
        exit();
    }

} catch(Exception $e) {
    $_SESSION['errors']['db_error'] = "システムエラーが発生しました。";
    error_log($e->getMessage());  // エラーメッセージをログに書き込む
    header("Location: /self_monitoring/error/db_error.php");
    exit();
}
?>
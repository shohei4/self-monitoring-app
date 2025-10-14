<?php
session_start();

// セッション変数の初期化
$_SESSION = [];

//セションクッキーの削除
if(ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

//セッションの削除
session_destroy();

//ログインページへリダイレクト
$_SESSION['msg'] = "ログアウトしました。";
header("Location: loginForm.php");
exit();
?>
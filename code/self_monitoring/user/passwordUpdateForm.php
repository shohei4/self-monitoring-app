<?php
session_start();
require_once __DIR__ . '/../utility/common_func.php';
require_once __DIR__ . '/../utility/validation.php';
// ログインチェック
checkLogin();
//エラーメッセージを取得
$errors = $_SESSION['errors'] ?? [];
// セッションの破棄
unset($_SESSION['errors']);
?>

<!DOCTYPE html>
<html lang="ja">
<?php require_once("../utility/head.php"); ?>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">パスワード更新</h2>
                <form method="post" action="passwordUpdate.php" novalidate>
                    <div class="mb-3">
                        <label for="password" class="form-label">パスワード</label>
                        <input type="password" name="password" class="form-control" id="password" required minlength="8">
                        <div class="form-text">パスワードは8文字以上で入力してください。</div>
                        <?php if (isset($errors['password'])): ?>
                            <?php foreach ($errors['password'] as $error): ?>
                                <div class="alert alert-danger mt-2">
                                    <?= htmlspecialchars($error) ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">更新</button>
                </form>

                <div class="text-center mt-3">
                    <a href="loginForm.php">ログイン画面へ</a>
                    <br>
                    <button type="button" class="btn btn-secondary" onclick="history.back();">
                        戻る
                    </button>

                </div>

            </div>
        </div>
    </div>
</body>

</html>
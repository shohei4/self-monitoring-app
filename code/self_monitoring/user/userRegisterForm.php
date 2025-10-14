<?php
session_start();

$errors = $_SESSION['errors'] ?? [];
require_once("../utility/common_func.php");
$errors = escapeArrayValues($errors);
$formData = $_SESSION['old'] ?? ['username' => '', 'email' => ''];
$formData = escapeArrayValues($formData);
// セッションのエラーと旧入力値をクリア
unset($_SESSION['errors']);
?>

<!DOCTYPE html>
<html lang="ja">
<?php require_once("../utility/head.php"); ?>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <h2 class="text-center mb-4">ユーザー登録画面</h2>

            <form method="post" action="userRegister.php" novalidate>
                <div class="mb-3">
                    <label for="username" class="form-label">ユーザー名</label>
                    <input type="text" name="username" class="form-control" id="username" maxlength="15"
                           value="<?= $formData['username'] ?>" required>
                    <div class="form-text">ユーザー名は15文字以内で入力してください。</div>
                    <?php if (isset($errors['username'])): ?>
                        <?php foreach ($errors['username'] as $error): ?>
                            <div class="alert alert-danger mt-2">
                                <?= $error ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email アドレス</label>
                    <input type="email" name="email" class="form-control" id="email" maxlength="256"
                           value="<?= $formData['email'] ?>" required>
                    <div class="form-text">Emailアドレスは255文字以内で入力してください。</div>
                    <?php if (isset($errors['email'])): ?>
                        <?php foreach ($errors['email'] as $error): ?>
                            <div class="alert alert-danger mt-2">
                                <?= $error ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">パスワード</label>
                    <input type="password" name="password" class="form-control" id="password" required minlength="8">
                    <div class="form-text">パスワードは8文字以上64文字以内で入力してください。</div>
                    <?php if (isset($errors['password'])): ?>
                        <?php foreach ($errors['password'] as $error): ?>
                            <div class="alert alert-danger mt-2"> 
                                <?= $error ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary w-100">登録</button>
            </form>

            <div class="text-center mt-3">
                <a href="loginForm.php">ログイン画面へ</a>
            </div>

        </div>
    </div>
</div>
</body>
</html>

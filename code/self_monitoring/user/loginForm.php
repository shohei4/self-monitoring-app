<?php
session_start();
require_once("../utility/common_func.php");
// エラーメッセージと旧入力値の取得
$errors = $_SESSION['errors'] ?? [];
escapeArrayValues($errors); // エラーメッセージをエスケープ
$old = $_SESSION['old'] ?? ['email' => '', 'password' => ''];
escapeArrayValues($old); // 旧入力値をエスケープ
unset($_SESSION['errors'], $_SESSION['old']);

// 成功メッセージの取得
$msg = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);
?>

<!DOCTYPE html>
<html>

<?php require_once("../utility/head.php");?>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6"> <!-- 中央寄せ & 幅調整 -->

                <h2 class="text-center mb-4">ログイン画面</h2>

                <?php if (!empty($msg)): ?>
                    <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
                <?php endif; ?>

                <form action="login.php" method="post">
                    <div class="mb-3">
                        <label for="inputEmail" class="form-label">Email アドレス</label>
                        <input type="email" class="form-control" id="inputEmail" name="email"
                            value="<?= $old['email'] ?? ''?>" maxlength="256" required>
                        <div class="form-text">Emailアドレスを入力してください。</div>
                        <?php if (isset($errors['email'])): ?>
                            <?php foreach ($errors['email'] as $error): ?>
                                <div class="alert alert-danger mt-2">
                                    <?= $error ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="inputPassword" class="form-label">パスワード</label>
                        <input type="password" class="form-control" id="inputPassword" name="password" required>
                        <div class="form-text">パスワードを入力してください。</div>
                        <?php if (isset($errors['password'])): ?>
                            <?php foreach ($errors['password'] as $error): ?>
                                <div class="alert alert-danger mt-2">
                                    <?= $error ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">ログイン</button>
                </form>

                <div class="text-center mt-3">
                    <a href="userRegisterForm.php">ユーザー登録へ</a>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
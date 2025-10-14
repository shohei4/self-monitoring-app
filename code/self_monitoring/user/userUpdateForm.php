<?php
session_start();
require_once __DIR__ . '/../utility/common_func.php';
// ログインチェック
$userId = checkLogin();

// セッションからエラーメッセージと旧入力値の取得
$errors = $_SESSION['errors'] ?? [];
escapeArrayValues($errors);
$formData = $_SESSION['old'] ?? ['username' => '', 'email' => ''];

// フォーム初期値の取得
if (empty($formData)) {
    // DBからユーザー情報取得
    $dbh = getDbConnection();
    $stmt = $dbh->prepare("SELECT username, email FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $formData = $stmt->fetch(PDO::FETCH_ASSOC);
    $dbh = null;
}
escapeArrayValues($formData);
// セッションのエラーをクリア
unset($_SESSION['errors']);
?>

<!DOCTYPE html>
<html lang="ja">
<?php require_once __DIR__ . '/../utility/head.php';?>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <h2 class="text-center mb-4">ユーザー更新画面</h2>

            <form method="post" action="userUpdate.php" novalidate>
                <div class="mb-3">
                    <label for="username" class="form-label">ユーザー名</label>
                    <input type="text" name="username" class="form-control"
                           id="username" maxlength="15"
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
                    <input type="email" name="email" class="form-control"
                           id="email" maxlength="256"
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

                <button type="submit" class="btn btn-primary w-100">更新</button>
            </form>

            <div class="text-center mt-3">
                <a href="/self_monitoring/index.php">トップページへ戻る</a>
            </div>

        </div>
    </div>
</div>
</body>
</html>

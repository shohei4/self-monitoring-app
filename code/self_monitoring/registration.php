<?php
session_start();
require_once('utility/common_func.php');
require_once('config/constants.php');
//ログイン認証
checkLogin();
//エラーメッセージ、旧入力値の取得
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors']);
?>
<!DOCTYPE html>
<html>
<?php require_once('utility/head.php') ?>
<?php
//ゲット送信された日付、またはセッションから日付を取得
$date = $_GET['date'] ?? '';
if (!empty($old)) {
    $year = $old['year'];
    $month = $old['month'];
    $day = $old['day'];
    $date = DateTime::createFromFormat('Y-m-d', "$year-$month-$day");
    $event = $old['event'];
    $distortion = $old['distortion'];
    $selectedFeelingLevel = $old['selectedFeelingLevel'];
    $feeling = $old['feeling'];
    $awareness = $old['awareness'];
}

// 日付が有効な場合は分割、無効な場合は現在の日付を設定
if ($date) {
    $year = $date->format('Y');
    $month = $date->format('m');
    $day = $date->format('d');
} else {
    $date = new DateTime();
    $year = date("Y");
    $month = date("m");
    $day = date("d");
}

?>

<body>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">出来事の登録</h5>
                    </div>
                    <div class="card-body">
                        <form action="insert.php" method="post">
                            <!-- 年月日 -->
                            <div class="mb-3">
                                <label for="year" class="form-label">日付</label><br>
                                <select name="year" id="year" class="form-select d-inline w-auto me-2">
                                    <?php
                                    $currentYear = date("Y");
                                    for ($y = $currentYear; $y >= 1950; $y--) {
                                        $selected = ($y == ($year ?? '')) ? 'selected' : '';
                                        echo "<option value=\"$y\" $selected>{$y}年</option>";
                                    }
                                    ?>
                                </select>
                                <select name="month" id="month" class="form-select d-inline w-auto me-2">
                                    <?php
                                    for ($m = 1; $m <= 12; $m++) {
                                        $monthVal = str_pad($m, 2, '0', STR_PAD_LEFT);
                                        $selected = ($m == ($month ?? '')) ? 'selected' : '';
                                        echo "<option value=\"$monthVal\" $selected>{$m}月</option>";
                                    }
                                    ?>
                                </select>
                                <select name="day" id="day" class="form-select d-inline w-auto">
                                    <?php
                                    for ($d = 1; $d <= 31; $d++) {
                                        $dayVal = str_pad($d, 2, '0', STR_PAD_LEFT);
                                        $selected = ($d == ($day ?? '')) ? 'selected' : '';
                                        echo "<option value=\"$dayVal\" $selected>{$d}日</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- 出来事 -->
                            <div class="mb-3">
                                <label for="event" class="form-label">出来事</label>
                                <textarea id="event" name="event" class="form-control" rows="4"><?= isset($event) ? $event : "" ?></textarea>
                                <?php if (isset($errors['event'])): ?>
                                    <?php foreach ($errors['event'] as $error): ?>
                                        <div class="alert alert-danger mt-2">
                                            <?= htmlspecialchars($error) ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <div class="form-text">出来事は255文字以内で入力してください。</div>
                            </div>

                            <!-- 歪み -->
                            <div class="mb-3">
                                <label for="distortion" class="form-label">歪み</label>
                                <select name="distortion" id="distortion" class="form-select">
                                    <?php require_once("utility/constant.php") ?>
                                    <?php foreach ($distortion_const as $item): ?>
                                        <option value="<?= $item ?>" <?= (isset($distortion) && $distortion === $item) ? "selected" : "" ?>>
                                            <?= $item ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- 気持ちレベル -->
                            <div class="mb-3">
                                <label class="form-label">気持ち</label>

                                <div class="d-flex gap-3">
                                    <?php foreach (FEELING_LEVELS as $value => $item): ?>

                                        <input type="radio"
                                            class="btn-check"
                                            name="feelingLevel"
                                            id="feeling<?= $value ?>"
                                            value="<?= $value ?>"
                                            <?= (isset($selectedFeelingLevel) && $selectedFeelingLevel == $value) ? 'checked' : '' ?>>

                                        <label class="btn <?= $item['class'] ?>"
                                            for="feeling<?= $value ?>">
                                            <?= $item['label'] ?>
                                        </label>

                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- 気持ち -->
                            <div class="mb-3">
                                <label for="feeling" class="form-label">気持ち</label>
                                <input type="text" id="feeling" name="feeling" value="<?= isset($feeling) ? $feeling : "" ?>" class="form-control">
                                <?php if (isset($errors['feeling'])): ?>
                                    <?php foreach ($errors['feeling'] as $error): ?>
                                        <div class="alert alert-danger mt-2">
                                            <?= htmlspecialchars($error) ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <div class="form-text">気持ちは50文字以内で入力してください。</div>
                            </div>

                            <!-- 気づき -->
                            <div class="mb-3">
                                <label for="awareness" class="form-label">気づき</label>
                                <textarea id="awareness" name="awareness" class="form-control" rows="4"><?= isset($awareness) ? $awareness : "" ?></textarea>
                                <?php if (isset($errors['awareness'])): ?>
                                    <?php foreach ($errors['awareness'] as $error): ?>
                                        <div class="alert alert-danger mt-2">
                                            <?= htmlspecialchars($error) ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <div class="form-text">気づきは255文字以内で入力してください。</div>
                            </div>

                            <!-- 登録ボタン -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">登録</button>
                            </div>
                        </form>
                        <a href="index.php" class="btn btn-link mt-3">戻る</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>


</html>
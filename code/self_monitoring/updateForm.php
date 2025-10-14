<?php
session_start();
require_once('utility/common_func.php');

//ログイン認証
checkLogin();

// Getの受取り
$id = $_GET["id"];

try {
    $dbh = getDbConnection();
    $sql = "SELECT * FROM feeling_items WHERE id=?";
    $data = [];
    $data = [$id];
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $dbh = null;
    $feelingItems = $stmt->fetch(PDO::FETCH_ASSOC);
    $feelingItems = escapeArrayValues($feelingItems);
    //SQLで取得した年月日を分割
    $fullDate = $feelingItems['date'];
    $date = new DateTime($fullDate);
    $selectedYear = $date->format('Y');
    $selectedMonth = $date->format('m');
    $selectedDay = $date->format('d');

    $event = $feelingItems['event'];
    $distortion = $feelingItems['distortion'];
    $selectedFeelingLevel = $feelingItems['feeling_level'];
    $feeling = $feelingItems['feeling'];
    $awareness = $feelingItems['awareness'];

    //セッションにエラー情報があれば取得
    if (isset($_SESSION['errors'])) {
        $errors = $_SESSION['errors'];
        unset($_SESSION['errors']);
    }

    //セッションに旧入力値があれば取得
    if (isset($_SESSION['old'])) {
        $old = $_SESSION['old'];

        $selectedYear = $old['year'];
        $selectedMonth = $old['month'];
        $selectedDay = $old['day'];
        $event = $old['event'];
        $distortion = $old['distortion'];
        $selectedFeelingLevel = $old['selectedFeelingLevel'];
        $feeling = $old['feeling'];
        $awareness = $old['awareness'];
    }
} catch (PDOException $e) {
    echo '接続失敗: ' . $e->getMessage();
    exit;
}

?>
<!DOCTYPE html>
<html>
<?php require_once('utility/head.php'); ?>

<body>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">記録の更新</h5>
                    </div>
                    <div class="card-body">
                        <form action="update.php" method="post">

                            <!-- 日付 -->
                            <div class="mb-3">
                                <label for="year" class="form-label">日付</label><br>
                                <select name="year" id="year" class="form-select d-inline w-auto me-2">
                                    <?php
                                    $currentYear = date("Y");
                                    for ($y = $currentYear; $y >= 1950; $y--) {
                                        $selected = ($y == $selectedYear) ? 'selected' : '';
                                        echo "<option value=\"$y\" $selected>{$y}年</option>";
                                    }
                                    ?>
                                </select>

                                <select name="month" id="month" class="form-select d-inline w-auto me-2">
                                    <?php
                                    for ($m = 1; $m <= 12; $m++) {
                                        $month = str_pad($m, 2, '0', STR_PAD_LEFT);
                                        $selected = ($m == $selectedMonth) ? 'selected' : '';
                                        echo "<option value=\"$month\" $selected>{$month}月</option>";
                                    }
                                    ?>
                                </select>

                                <select name="day" id="day" class="form-select d-inline w-auto">
                                    <?php
                                    for ($d = 1; $d <= 31; $d++) {
                                        $day = str_pad($d, 2, '0', STR_PAD_LEFT);
                                        $selected = ($d == $selectedDay) ? 'selected' : '';
                                        echo "<option value=\"$day\" $selected>{$day}日</option>";
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
                                            <?= $error ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <div class="form-text">出来事は256文字以内で入力してください。</div>
                            </div>

                            <!-- 歪み -->
                            <div class="mb-3">
                                <label for="distortion" class="form-label">歪み</label>
                                <select name="distortion" id="distortion" class="form-select">
                                    <?php require_once("utility/constant.php") ?>
                                    <?php foreach ($distortion_const as $item): ?>
                                        <option value="<?= $item ?>" <?= $distortion === $item ? "selected" : "" ?>>
                                            <?= $item ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- 気持ちレベル -->
                            <div class="mb-3">
                                <label for="feelingLevel" class="form-label">気持ちレベル</label>
                                <select name="feelingLevel" class="form-select">
                                    <?php
                                    for ($l = 0; $l <= 10; $l++) {
                                        $feelingLevel = $l * 10;
                                        $selected = ($feelingLevel == $selectedFeelingLevel) ? 'selected' : '';
                                        echo "<option value=\"$feelingLevel\" $selected>$feelingLevel</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- 気持ち -->
                            <div class="mb-3">
                                <label for="feeling" class="form-label">気持ち</label>
                                <input type="text" id="feeling" name="feeling" value="<?= isset($feeling) ? $feeling : "" ?>" class="form-control">
                                <?php if (isset($errors['feeling'])): ?>
                                    <?php foreach ($errors['feeling'] as $error): ?>
                                        <div class="alert alert-danger mt-2">
                                            <?= $error ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <div class="form-text">気持ちは50文字以内で入力してください。</div>
                            </div>

                            <!-- 気づき -->
                            <div class="mb-3">
                                <label for="awareness" class="form-label">気づき</label>
                                <input type="text" id="awareness" name="awareness" value="<?= isset($awareness) ? $awareness : "" ?>" class="form-control">
                                <?php if (isset($errors['awareness'])): ?>
                                    <?php foreach ($errors['awareness'] as $error): ?>
                                        <div class="alert alert-danger mt-2">
                                            <?= $error ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <div class="form-text">気づきは256文字以内で入力してください。</div>
                            </div>

                            <input type="hidden" value="<?= $id ?>" name="id">

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">更新</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
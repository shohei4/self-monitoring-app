<?php
session_start();
require_once('utility/common_func.php');
require_once('utility/validation.php');
//ログイン認証
$userId = checkLogin();

// POST受け取り
if (isset($_POST['id']) && ctype_digit($_POST['id'])) {
    $id = $_POST["id"];
} else {
    // idが不正な場合はエラーページへリダイレクト
    //セションで検索対象が見つからないことを伝える
    $_SESSION['errors'] = [
        'invalid_id' => "指定された記録が見つかりません。"
    ];
    header("Location: error/index.php");
    exit();
}
$year = $_POST['year'];
$month = $_POST['month'];
$day = $_POST['day'];
$event = $_POST['event'];
$distortion = $_POST['distortion'];
$feelingLevel = $_POST['feelingLevel'];
$feeling = $_POST['feeling'];
$awareness = $_POST['awareness'];

//バリデーションチェック
$errors = [];
addError($errors, 'event', isMaxLength($event, 256, 'event', $errors));
addError($errors, 'feeling', isMaxLength($feeling, 50, 'feeling', $errors));
addError($errors, 'awareness', isMaxLength($awareness, 256, 'awareness', $errors));
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = [
        'year' => $year,
        'month' => $month,
        'day' => $day,
        'event' => $event,
        'distortion' => $distortion,
        'selectedFeelingLevel' => $feelingLevel,
        'feeling' => $feeling,
        'awareness' => $awareness
    ];
    header("Location: updateForm.php?id=$id");
    exit();
}

$date = (new DateTime("$year-$month-$day"))->format('Y-m-d');
try {
    //DB接続
    $dbh = getDbConnection();
    //SQL文
    $sql = "UPDATE feeling_items 
            SET date = ?,
                event = ?,
                distortion = ?,
                feeling_level = ?,
                feeling = ?,
                awareness = ?
            WHERE
                id = ?
            AND 
                user_id = ?";
    //プリペアードステートメント(SQL実行準備)
    $stmt = $dbh->prepare($sql);
    $data = [];
    $data = [$date, $event, $distortion, $feelingLevel, $feeling, $awareness, $id, $userId];
    $stmt->execute($data);
    $dbh = null;
    //セッションの旧入力値をクリア
    unset($_SESSION['old']);
    $_SESSION['msg'] = "記録を更新しました。";
    header("Location: index.php");
    exit();
} catch (Exception  $e) {
    $msg = urlencode($e->getMessage());
    header("Location: db_error.php?msg=$msg");
    exit();
}


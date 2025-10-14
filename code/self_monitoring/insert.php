<?php
session_start();

require_once('utility/common_func.php');
require_once('utility/validation.php');

//ログイン認証
$userId = checkLogin();

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
if(!empty($errors)){
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
  header("Location: registration.php");
  exit();
}

$date = (new DateTime("$year-$month-$day"))->format('Y-m-d');
try {
  //DB接続
  $dbh = getDbConnection();
  //SQL文
  $sql = "INSERT INTO feeling_items (user_id,date,event,distortion,feeling_level,feeling,awareness) VALUES (?,?,?,?,?,?,?)";
  //プリペアードステートメント(SQL実行準備)
  $stmt = $dbh->prepare($sql);
  $data = [];
  $data = [$userId, $date, $event, $distortion, $feelingLevel, $feeling, $awareness];
  $stmt->execute($data);
  $dbh = null;
  //セッションの旧入力値をクリア
  unset($_SESSION['old']);
  //登録完了メッセージをセッションに保存
  $_SESSION['msg'] = "記録を追加しました。";
  header("Location: index.php");
  exit();
} catch (Exception  $e) {
  $msg = urlencode($e->getMessage());
  header("Location: db_error.php?msg=$msg");
  exit();
}
?>

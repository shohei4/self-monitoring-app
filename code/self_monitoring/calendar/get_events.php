<?PHP
session_start();
require_once __DIR__ . '/../utility/common_func.php'; 

$dbh = getDbConnection();
$sql = "SELECT id, date, event FROM feeling_items WHERE user_id=? ORDER BY date ASC";
$stmt = $dbh->prepare($sql);
$data = [$_SESSION['user_id']];
$stmt->execute($data);
$dbh = null;
$events = [];
// FullCalendar用の配列に変換
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $events[] = [
        'id'    => $row['id'],       // 編集ページ遷移用
        'title' => $row['event'],    // カレンダーに表示される文字
        'start' => $row['date']      // yyyy-mm-dd形式
    ];
}

// JSONで返す
header('Content-Type: application/json; charset=UTF-8');
echo json_encode($events);

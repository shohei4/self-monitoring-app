<?php
/**
 * 配列の値をエスケープする関数
 *
 * @param [type] $data
 * @return void
 */
function escapeArrayValues($data)
{
    $escaped = [];
    foreach ($data as $key => $value) {
        //value値が配列の場合は再帰的にエスケープ
        if (is_array($value)) {
            $escaped[$key] = escapeArrayValues($value); 
        } else {
            $escaped[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
    }
    return $escaped;
}

//DB接続関数
function getDbConnection()
{
    $host = 'localhost';
    $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';
    $charset = 'utf8mb4';

    try {
        $pdo = new PDO($dsn, $user, $password);
        // エラーを例外としてスロー
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        //呼び出し元で処理させる
        throw new Exception("DB接続エラー: " . $e->getMessage());
    }
}

//ログイン認証関数
function checkLogin() {
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        $_SESSION['msg'] = "ログインしてください。";
        header("Location: loginForm.php");
        exit();
    }
    return $userId; // 後続で使える
}
?>

<?php
/**
 * 空白文字を除去して値がある場合のみ代入する関数
 *
 * @param array $source データが入った配列（例: $_SESSION['old']）
 * @param string $key チェックしたい配列のキー
 * @param mixed &$target 代入先変数の参照渡し
 */
function assignIfNotEmpty(array $source, string $key, &$target): void {
    if (isset($source[$key]) && strlen(trim($source[$key])) > 0) {
        $target = $source[$key];
    }
}

//三点リーダー形式で文字の切り落とし
function truncateWithEllipsis($text, $length = 20, $encoding = 'UTF-8') {
    if (mb_strlen($text, $encoding) > $length) {
        return mb_substr($text, 0, $length, $encoding) . '…';
    }
    return $text;
}
?>


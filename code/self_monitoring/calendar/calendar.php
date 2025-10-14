<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <title>日記カレンダー</title>
    <!-- Bootstrap CSS（必須） -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FullCalendar CSS（通常テーマ＋Bootstrapテーマ） -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/bootstrap5.min.css" rel="stylesheet">

    <!-- FullCalendar JS本体 -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

</head>

<body>
    <header>
        <?php
        //ログイン認証
        if (!isset($_SESSION['user_id'])) {
            echo "ログインが必要です。";
            header('Location: ../user/loginForm.php');
            exit;
        }
        ?>
    </header>
    <div id="calendar" class="p-4"></div>

    <div class="mb-3">
        <a href="../index.php" class="btn btn-outline-primary btn-sm">一覧表示へ</a>
        <a href="../registration.php" class="btn btn-primary btn-sm">新規登録</a>
    </div>
    <script>
        //三点リーダー形式で文字の切り落とし
        function truncateWithEllipsis(text, length = 10) {
            if (text.length > length) {
                return text.substring(0, length) + '…';
            }
            return text;
        }
        document.addEventListener('DOMContentLoaded', function() {
            let calendarEl = document.getElementById('calendar');
            let calendar = new FullCalendar.Calendar(calendarEl, {
                themeSystem: 'bootstrap5', // ★Bootstrap5テーマを使用
                initialView: 'dayGridMonth',
                events: '/self_monitoring/calendar/get_events.php',
                eventDidMount: function(info) {
                    const fullTitle = info.event.title;
                    //先頭１０文字だけ表示
                    const shortTitle = truncateWithEllipsis(fullTitle);

                    // 表示を短縮タイトルに変更
                    const titleElement = info.el.querySelector('.fc-event-title');
                    if (titleElement) {
                        titleElement.textContent = shortTitle;
                    }
                    // title属性を付けるとブラウザ標準のツールチップが表示される
                    info.el.setAttribute('title', fullTitle);
                },
                dateClick: function(info) {
                    window.location.href = `../registration.php?date=${info.dateStr}`;
                },
                eventClick: function(info) {
                    window.location.href = `../detail.php?id=${info.event.id}`;
                }
            });
            calendar.render();
        });
    </script>
</body>

</html>
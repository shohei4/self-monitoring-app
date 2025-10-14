<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">自己モニタリングアプリ</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/self_monitoring/index.php">Home</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            フィールログ
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/self_monitoring/index.php">一覧表示</a></li>
            <li><a class="dropdown-item" href="/self_monitoring/calendar/calendar.php">カレンダー</a></li>
            <li><a class="dropdown-item" href="/self_monitoring/registration.php">新規登録</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?= htmlspecialchars($_SESSION['username']) . "さん" ?>
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/self_monitoring/user/logout.php">ログアウト</a></li>
            <li><a class="dropdown-item" href="/self_monitoring/user/userUpdateForm.php">ユーザー情報更新</a></li>
            <li><a class="dropdown-item" href="/self_monitoring/user/passwordUpdateForm.php">パスワード更新</a></li>
          </ul>
        </li>
      </ul>
      <form class="d-flex" role="search" action="index.php" method="get">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="key" value="<?= htmlspecialchars($key) ?>">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
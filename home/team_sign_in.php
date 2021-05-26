<?php

require_once('../config.php');

session_start();
$empty = $error = '';

$team = $_POST['team'];
$email = $_SESSION['EMAIL'];
//DB内でメールアドレスを検索
try {
    $pdo = new PDO(DSN, DB_USER, DB_PASS);
    $stmt = $pdo->prepare('select * from teamdata where team = ? and email = ?');
    $stmt->execute([$team,$email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
    $team = 'チーム名又はパスワードが間違っています。';
}
//teamがDB内に存在しているか確認
if (!isset($row['team'])) {
    $error = 'チーム名又はパスワードが間違っています。';
} else {
    $team = $_POST['team'];
}

//ポップアップ表示
if (strcmp($error, $empty) != 0) {
    $alert =
        "<script type='text/javascript'>
    alert('" . $error . "');
    location.href = 'team.php';
    </script>";
    echo $alert;
}

//パスワード確認
if (password_verify($_POST['password'], $row['password']) && ($team == $row['team'])) {
    $_SESSION['TEAM'] = $team;
    $_SESSION['ID'] = $row['id'];
    $sign_up = 'チームの編集に移ります';
    $alert =
        "<script type='text/javascript'>
      alert('" . $sign_up . "');
      location.href = 'team_edit.php';
      </script>";
    echo $alert;
} else {
    $error = 'チーム名又はパスワードが間違っています。';
    $alert =
        "<script type='text/javascript'>
    alert('" . $error . "');
    location.href = 'index.php';
    </script>";
    echo $alert;
}

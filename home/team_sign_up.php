<?php

require_once('../config.php');
require_once('../function.php');
session_start();
//データベースへ接続
try {
  $pdo = new PDO(DSN, DB_USER, DB_PASS, ARY);
} catch (Exception $e) {
  echo $e->getMessage() . PHP_EOL;
}

//メール
$email=isset($_SESSION['EMAIL'])?$_SESSION['EMAIL']:"";

//チーム
$team=$_POST['team'];

//名前
$email=isset($_SESSION['EMAIL'])?$_SESSION['EMAIL']:"";
$sql = "select name from userdata where email=\"".$email . "\"";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$name = $row['name'];

//パスワードの正規表現
if ($_POST['password'] === $_POST['password_check']) {
    if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    } else {
        $error = 'パスワードは半角英数字をそれぞれ1文字以上含んだ8文字以上で設定してください。';
    }
} else {
    $error = 'パスワードが一致しません。';
}

$error = $empty = '';
//ポップアップ表示
if(strcmp($error,$empty) != 0){
    $alert =
    "<script type='text/javascript'>
    alert('".$error."');
    location.href = 'team.php';
    </script>";
    echo $alert;
    return false;
}

try {
    $stmt = $pdo->prepare("insert into teamdata(team, email, password) value(?, ?, ?)");
    $stmt->execute([$team, $email, $password]);
    
} catch (\Exception $e) {
    $error='作成済みのチームです。';
    $alert =
    "<script type='text/javascript'>
    alert('".$error."');
    location.href = 'team.php';
    </script>";
    echo $alert;
}
try{
    $stm = $pdo->prepare("insert into team_members(email,team,name) value(?, ?, ?)");
    $stm->execute([$email, $team, $name]);
    $sign_up = '登録完了';
    $alert =
    "<script type='text/javascript'>
    alert('".$sign_up."');
    location.href = 'index.php';
    </script>";
    echo $alert;
} catch (\Exception $e) {
    $error='作成済みのチームです。';
    $alert =
    "<script type='text/javascript'>
    alert('".$error."');
    location.href = 'team.php';
    </script>";
    echo $alert;
}

<?php
require_once('../../config.php');
require_once('../../function.php');

$error = $empty = "";
if (isset($_POST['update'])) {
    $table = $_POST['table'];
    $id = $_POST['id'];
    echo $id;
    if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $_POST['password'])) {
        $pdo = new PDO(DSN, DB_USER, DB_PASS, ARY);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "update $table set password=\"" . $password . "\" where id=\"" . $id . "\"";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $error = "パスワードを更新しました。";
    } else {
        $error = 'パスワードは半角英数字をそれぞれ1文字以上含んだ8文字以上で設定してください。';
    }
}

if (strcmp($error, $empty) != 0) {
    $alert =
        "<script type='text/javascript'>
  alert('" . $error . "');
  location.href = './$table.php';
  </script>";
    echo $alert;
    return false;
}

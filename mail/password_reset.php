<?php
require_once('../config.php');

if (!isset($_SESSION)) {
    session_start();
}

$mail_id = $_SESSION['mail_id'];
$mail_email = $_SESSION['mail_email'];
$mail_name = $_SESSION['mail_name'];
$new_pass = password_hash($_SESSION['new_pass'], PASSWORD_DEFAULT);

try{
    $pdo = new PDO(DSN, DB_USER, DB_PASS, ARY);
    $sql = $pdo->prepare("update userdata set password=? where id=? and email=? and name=?");
    $sql->execute([$new_pass, $mail_id, $mail_email, $mail_name]);
} catch (\Exception $e){
   $error = 'メールアドレスもしくは名前が間違えています。';
    $alert =
        "<script type='text/javascript'>
        alert('" . $error . "');
        location.href = './index.php';
        </script>";
    echo $alert;
}
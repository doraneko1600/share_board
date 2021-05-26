<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once('../../config.php');
require_once('../../function.php');

if (!$_SESSION['login_torken'] && !$_SESSION['admin_email']) {
    header('Location:../');
    exit;
}

if ($_GET['table'] == "userdata" && $_GET['id'] == 1) {
    $error = '管理者のアカウントです。';
    $alert =
        "<script type='text/javascript'>
    alert('" . $error . "');
    location.href = 'index.php';
    </script>";
    echo $alert;
}

$email = h($_GET['email']);
$id = h($_GET['id']);
$table = h($_GET['table']);
$command = h($_GET['command']);

$pdo = new PDO(DSN, DB_USER, DB_PASS, ARY);

if ($table == "chat_private") {
    $delete = "delete from $table where id=\"" . $id . "\" and your_email=\"" . $email . "\"";
} else {
    $delete = "delete from $table where id=\"" . $id . "\" and email=\"" . $email . "\"";
}
$sql = $pdo->prepare("$delete");
$sql->execute();
header("Location:./$table.php");
exit;

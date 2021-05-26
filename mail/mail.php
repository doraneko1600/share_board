<?php
require_once('header.php');
require_once('../config.php');
if (!isset($_SESSION)) {
    session_start();
}

// index.phpでセッションに保存された内容を変数に保存
$mail_email = $_SESSION['mail_email']; //送信先のメール
$mail_email = str_replace('@i-seifu.jp','@g.i-seifu.jp',$mail_email);

$mail_name = $_SESSION['mail_name'];

// メールのタイトル
$mail_title = "$mail_name さんのパスワードリセットについて";

// メールの中身
ob_start();
include('mail_body.php');
$mail_body = ob_get_contents();
ob_end_clean();

// メールのヘッダー
$header = "From: " . mb_encode_mimeheader("共有ボード");

session_write_close();

if (mb_send_mail("$mail_email", "$mail_title", "$mail_body", "$header")) {
    require_once('password_reset.php');
    $success = 'メールを送信しました。ご確認ください。';
    $alert =
        "<script type='text/javascript'>
        alert('" . $success . "');
        location.href = '../index.php';
        </script>";
    echo $alert;
} else {
    $error = 'メールアドレスもしくは名前が間違えています。';
    $alert =
        "<script type='text/javascript'>
        alert('" . $error . "');
        location.href = './index.php';
        </script>";
    echo $alert;
}

require_once('footer.php');
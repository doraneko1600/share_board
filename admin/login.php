<?php
session_start();
require_once('../config.php');
require_once('../function.php');
// ログインした時の時間取得
date_default_timezone_set('Asia/Tokyo');
$date = date("Y/m/d H:i:s");
$ip = $_SERVER["REMOTE_ADDR"];

if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    // 正常な処理
    $empty = $error = '';
    //POSTの確認
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error = '入力された値が不正です。';
    }
    //DB内からid=0を選ぶ
    try {
        $pdo = new PDO(DSN, DB_USER, DB_PASS);
        $stmt = $pdo->prepare("select * from userdata where id = '1'");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (\Exception $e) {
        echo $e->getMessage() . PHP_EOL;
    }

    //nameの確認
    if ($row['name'] !== $_POST['name']) {
        $error = 'メールアドレス又はパスワードが間違っています';
    }

    //emailの確認
    if ($row['email'] !== $_POST['email']) {
        $error = 'メールアドレス又はパスワードが間違っています';
    }

    //ポップアップ表示
    if (strcmp($error, $empty) != 0) {
        $alert =
            "<script type='text/javascript'>
            alert('" . $error . "');
            location.href = 'index.php';
            </script>";
        echo $alert;
    }

    //パスワード確認後sessionにメールアドレスを渡す
    if (password_verify($_POST['password'], $row['password'])) {
        $_SESSION['admin_email'] = $row['email'];
        $_SESSION['login_torken'] = $_POST['csrf_token'];

        // 管理者のメール
        $send_email = "doraneko1600@gmail.com";
        // メールタイトル
        $mail_title = "共有ボードで管理者権限へのログインを確認";
        // メール本文
        $mail_body =
            "「 $date 」にてログインを確認しました。
        IPアドレス 「 $ip 」";
        // メールのヘッダー
        $header = "From: " . mb_encode_mimeheader("共有ボード");
        // 管理者へのメール送信
        if (mb_send_mail("$send_email", "$mail_title", "$mail_body", "$header")) {

            // ポップアップ表示
            $login = 'ログインしました';
            $alert =
                "<script type='text/javascript'>
            alert('" . $login . "');
            location.href = 'home.php';
            </script>";
            echo $alert;
        } else {
            $login = 'メールアドレス又はパスワードが間違えています。';
            $alert =
                "<script type='text/javascript'>
            alert('" . $login . "');
            location.href = 'index.php';
            </script>";
        }
    } else {
        $error = 'メールアドレス又はパスワードが間違っています。';
        $alert =
            "<script type='text/javascript'>
            alert('" . $error . "');
            location.href = 'index.php';
            </script>";
        echo $alert;
    }
} else {
    // 不正な処理
    $error = "不正な処理です。";
    $alert =
        "<script type='text/javascript'>
        alert('" . $error . "');
        location.href = 'index.php';
        </script>";
    echo $alert;
}

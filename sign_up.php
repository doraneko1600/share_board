<?php

require_once('config.php');
require_once('function.php');
$pdo = new PDO(DSN, DB_USER, DB_PASS, ARY);
$empty = $error = '';

//名前
$name = $_POST['name'];
$name = t(h($name));
if (empty($name)) {
    $error = '名前を入力してください。';
}

//メールの検証
if (!$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $error = '入力された値が不正です。';
}
//パスワードの正規表現
if ($_POST['password'] === $_POST['password_check']) {
    if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    } else {
        $error = 'パスワードは半角英数字をそれぞれ1文字以上含んだ8文字以上で設定してください。( ’_’や’-’などの記号は使用不可 )';
    }
} else {
    $error = 'パスワードが一致しません。';
}
//ポップアップ表示
if (strcmp($error, $empty) != 0) {
    $alert =
        "<script type='text/javascript'>
  alert('" . $error . "');
  location.href = 'sign_up_page.php';
  </script>";
    echo $alert;
    return false;
}

//登録処理
try {
    $stmt = $pdo->prepare("insert into userdata(name, email, password) value(?, ?, ?)");
    $stmt->execute([$name, $email, $password]);
    $sign_up = '登録完了';
    $alert =
        "<script type='text/javascript'>
    alert('" . $sign_up . "');
    location.href = 'index.php';
    </script>";
    echo $alert;
} catch (\Exception $e) {
    $error = '登録済みのメールアドレスです。';
    $alert =
        "<script type='text/javascript'>
    alert('" . $error . "');
    location.href = 'sign_up_page.php';
    </script>";
    echo $alert;
}

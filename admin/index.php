<?php
session_start();
require_once('../config.php');
require_once('../function.php');
require('header.php');
// バイナリを生成し、それを16進数に変換することでASCII文字列に変換します
$token_byte = openssl_random_pseudo_bytes(16);
$csrf_token = bin2hex($token_byte);
// 生成したトークンをセッションに保存します
$_SESSION['csrf_token'] = $csrf_token;
session_write_close();
?>
<main>
    <h1>管理者用のログイン</h1>
    <form action="login.php" method="post">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        <label for="name">
            <p>名前</p>
        </label>
        <input type="name" name="name" value="" required="required">

        <br><label for="email">
            <p>メール</p>
        </label>
        <input type="email" name="email" value="" required="required">

        <br><label for="password">
            <p>パスワード</p>
        </label>
        <input type="password" name="password" value="" required="required">
        <br><button type="submit">ログイン</button>
    </form>
</main>
<?php require_once('footer.php'); ?>
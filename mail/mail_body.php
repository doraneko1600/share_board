<?php

if (!isset($_SESSION)) {
    session_start();
}

//送信先メアドで登録されている名前
$mail_name = $_SESSION['mail_name'];

// 新しいパスワードの作成
$int = random_int(8, 10);
$byte = random_bytes($int);
$new_pass = bin2hex($byte);
while (!preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $new_pass)) {
    $int = random_int(8, 10);
    $byte = random_bytes($int);
    $new_pass = bin2hex($byte);
}
$_SESSION['new_pass'] = $new_pass;

// セッションクローズ
session_write_close();
?>

<?php echo $mail_name; ?>さん。共有ボードのご利用ありがとうございます。

パスワードをリセットし、新しいパスワードを発行しました。

「<?php echo $new_pass; ?>」

次回ログイン時には上記のパスワードを入力し、ログイン後に新たなパスワードを設定して下さい。
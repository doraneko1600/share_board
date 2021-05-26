<?php
session_start();
$output = '';
if (isset($_SESSION["admin_email"])) {
    $output = 'ログアウトしました。';
} else {
    $output = 'セッションがタイムアウトしました。';
}
//セッション変数のクリア
$_SESSION = array();
//セッションクッキーも削除
if (ini_get("session.use_cookies")) {
    $admin_params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $admin_params["path"],
        $admin_params["domain"],
        $admin_params["secure"],
        $admin_params["httponly"]
    );
}
//セッションクリア
@session_destroy();

$alert =
    "<script type='text/javascript'>
alert('" . $output . "');
location.href = 'index.php';
</script>";
echo $alert;

<?php
session_start();
$output = '';
if (isset($_SESSION["EMAIL"])) {
  $output = 'ログアウトしました。';
} else {
  $output = 'セッションがタイムアウトしました。';
}
//セッション変数のクリア
$_SESSION = array();
//セッションクッキーも削除
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
//セッションクリア
@session_destroy();

$alert =
"<script type='text/javascript'>
alert('".$output."');
location.href = 'index.php';
</script>";
echo $alert;
?>
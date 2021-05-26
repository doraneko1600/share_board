<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once('../header.php');
require_once('../../config.php');

if (!$_SESSION['login_torken'] && !$_SESSION['admin_email']) {
    header('Location:../');
    exit;
}
?>
<h1>プライベートチャット</h1>
<table class="label">
    <td>id</td>
    <td>送信先</td>
    <td>送信元</td>
    <td>message</td>
    <td>created</td>
</table>

<?php
$pdo = new PDO(DSN, DB_USER, DB_PASS, ARY);

// chat_privateテーブルからすべてのデータを選択
foreach ($pdo->query("select id, your_email, my_email, message, created from chat_private") as $row) {
    echo <<< HTML
<form action="#" method="#">
    <input type="text" name="id" value="{$row['id']}" disabled="disabled">
    <input type="text" name="your_email" value="{$row['your_email']}" disabled="disabled">
    <input type="text" name="my_email" value="{$row['my_email']}" disabled="disabled">
    <input type="text" name="created" value="{$row['created']}" disabled="disabled">
        <p><a href="javascript:void(0);" onclick="var ok = confirm('削除しますか？'); if (ok) location.href='delete.php?email={$row['your_email']}&id={$row['id']}&table=chat_private'">削除</a></p>
</form>
HTML;
}
?>
<br>
<p><a href="../home.php">ホームに戻る</a></p>

<?php require_once('../footer.php'); ?>
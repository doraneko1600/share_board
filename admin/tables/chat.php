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
<h1>チャット</h1>
<table class="label">
    <td>id</td>
    <td>team</td>
    <td>email</td>
    <td>name</td>
    <td>message</td>
    <td>created</td>
</table>

<?php
$pdo = new PDO(DSN, DB_USER, DB_PASS, ARY);

// chatテーブルからすべてのデータを選択
foreach ($pdo->query("select id, team, email, c_name, message, created from chat") as $row) {
    echo <<< HTML
<form action="#" method="#">
    <input type="text" name="id" value="{$row['id']}" disabled="disabled">
    <input type="text" name="team" value="{$row['team']}" disabled="disabled">
    <input type="text" name="email" value="{$row['email']}" disabled="disabled">
    <input type="text" name="c_name" value="{$row['c_name']}" disabled="disabled">
    <input type="text" name="message" value="{$row['message']}" disabled="disabled">
    <input type="text" name="created" value="{$row['created']}" disabled="disabled">
        <p><a href="javascript:void(0);" onclick="var ok = confirm('削除しますか？'); if (ok) location.href='delete.php?email={$row['email']}&id={$row['id']}&table=chat'">削除</a></p>
</form>
HTML;
}
?>
<br>
<p><a href="../home.php">ホームに戻る</a></p>

<?php require_once('../footer.php'); ?>
<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once('../header.php');
require_once('../../config.php');
require_once('../../function.php');

if (!$_SESSION['login_torken'] && !$_SESSION['admin_email']) {
    header('Location:../');
    exit;
}

if (isset($_POST['update'])) {
    if ((isset($_REQUEST["chkno"]) == true) && (isset($_SESSION["chkno"]) == true) && ($_REQUEST["chkno"] == $_SESSION["chkno"])) {
        header('Location:./update.php');
        exit;
    }
}

//トークンの生成
$_SESSION["chkno"] = $chkno = get_csrf_token();
?>
<h1>ユーザーデータ</h1>
<table class="label">
    <td>id</td>
    <td>email</td>
    <td>name</td>
    <td>failed_count</td>
    <td>locked_time</td>
    <td>created</td>
</table>

<?php
$pdo = new PDO(DSN, DB_USER, DB_PASS, ARY);

// userdataテーブルからすべてのデータを選択
foreach ($pdo->query("select id, email, name, failed_count, locked_time, created from userdata where id!=1") as $row) {
    echo <<< HTML
<form action="update.php" method="post">
    <input type="text" name="id" value="{$row['id']}" disabled="disabled">
    <input type="text" name="email" value="{$row['email']}" disabled="disabled">
    <input type="text" name="name" value="{$row['name']}" disabled="disabled">
    <input type="text" name="failed_count" value="{$row['failed_count']}" disabled="disabled">
    <input type="text" name="locked_time" value="{$row['locked_time']}" disabled="disabled">
    <input type="text" name="created" value="{$row['created']}" disabled="disabled">
    <p>
    <input type="hidden" name="id" value="{$row['id']}">
    <input type="hidden" name="table" value="userdata">
    <input type="hidden" name="chkno" value="{$chkno}">
    <input type="text" name="password" placeholder="パスワードの変更">
    <input type="submit" name="update" value="更新">
    </p>
        <p><a href="javascript:void(0);" onclick="var ok = confirm('削除しますか？'); if (ok) location.href='delete.php?email={$row['email']}&id={$row['id']}&table=userdata'">削除</a></p>
</form>
HTML;
}
?>
<br>
<p><a href="../home.php">ホームに戻る</a></p>

<?php require_once('../footer.php'); ?>
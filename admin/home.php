<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once('header.php');
require_once('../function.php');
if (!$_SESSION['login_torken'] && !$_SESSION['admin_email']) {
    header('Location:./');
    exit;
}
?>

<h1>管理者画面にようこそ</h1>
<p><a href="logout.php">ログアウト</a></p>
<br>
<ul>テーブル一覧</ul>
<li><a href="tables/userdata.php">ユーザーデータ</a></li>
<li><a href="tables/teamdata.php">チームデータ</a></li>
<li><a href="tables/team_members.php">チームメンバー</a></li>
<li><a href="tables/todo.php">ToDoリスト</a></li>
<li><a href="tables/plan.php">予定</a></li>
<li><a href="tables/images.php">ファイル共有</a></li>
<li><a href="tables/chat.php">チャット</a></li>
<li><a href="tables/chat_private.php">プライベートチャット</a></li>

<?php require_once('footer.php'); ?>
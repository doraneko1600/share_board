<?php
session_start();
require_once('../config.php');
require_once('../function.php');
require('header.php');
$pdo = new PDO(DSN, DB_USER, DB_PASS);
$team = isset($_SESSION['TEAM']) ? $_SESSION['TEAM'] : "";
?>

<h3><?php echo $team ?>のメンバー編集</h3>

<div class="nav">
    <ul>
        <li class="current"><a href="team_edit.php">メンバーの編集</a></li>
        <li><a href="team_name.php">チーム名の変更</a></li>
        <li><a href="team_pass.php">チームのパスワード変更</a></li>
        <li><a href="team.php">編集するチームを変更する</a></li>
        <li><a href="index.php"><i class="fas fa-home"></i>ホームに戻る</a></li>
    </ul>
</div>

<table class="edit">
    <td>メール</td>
    <td>名前</td>
</table>

<?php
if (isset($_REQUEST['command'])) {
    switch ($_REQUEST['command']) {
        case 'insert':
            if (empty($_REQUEST['email'])) break;
            // フォームから入力されたメールアドレス
            $email = h(t($_REQUEST['email']));
            // userdataからメアドの一致してる名前の取得
            $sql = "select id,name from userdata where email=\"" . $email . "\"";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (empty($row['name'])) {
                $sign_up = 'メールアドレスが間違えている、もしくは登録されていません。';
                $alert =
                    "<script type='text/javascript'>
                alert('" . $sign_up . "');
                location.href = 'team_edit.php';
                </script>";
                echo $alert;
            } else {
                $name = $row['name'];
                $id = $row['id'];
            }
            // $e_nameが無ければbreak
            // team_membersへのデータ追加
            $sql = $pdo->prepare('insert into team_members(email,name,team) values(?,?,?)');
            $sql->execute([$email, $name, $team]);
            break;
        case 'delete':
            $sql = $pdo->prepare('delete from team_members where email=?');
            $sql->execute([$_REQUEST['email']]);
            header('Location:./team_edit.php');
            break;
    }
}
// 出力
foreach ($pdo->query("select email,name from team_members where team=\"" . $team . "\"") as $row) {

    echo <<<HTML
        <form action="team_edit.php" method="post">
            <input type="hidden" name="command" value="delete">
            <input type="text" name="email" value="{$row['email']}" disabled="disabled">
            <input type="text" name="name" value="{$row['name']}" disabled="disabled">
            <br>
            <a href="javascript:void(0);" onclick="var ok = confirm('削除しますか？'); if (ok) location.href='team_edit.php?email={$row['email']}&command=delete'">
            削除
            </a>
        </form>
HTML;
}
?>

<form action="team_edit.php" method="post">
    <label>追加したいメンバーのメールアドレスを入力</label>
    <br><input type="hidden" name="command" value="insert">
    <input type="text" name="email">
    <input type="submit" value="追加">
</form>


<?php require '../footer.php'; ?>
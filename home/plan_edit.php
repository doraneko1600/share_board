<?php
session_start();
require_once('../config.php');
require_once('../function.php');
require('header.php');
?>

<table class="edit">
    <td>チーム</td>
    <td>タイトル</td>
    <td>日付</td>
    <td>詳細</td>
</table>

<?php

$pdo = new PDO(DSN, DB_USER, DB_PASS);
$email = isset($_SESSION['EMAIL']) ? $_SESSION['EMAIL'] : "";

$sql = "select distinct team from team_members where email=\"" . $email . "\"";
$plan = $pdo->query($sql);

$i = 0;
while ($row = $plan->fetch(PDO::FETCH_ASSOC)) {
    foreach ($row as $select) {
        //echo '<p>'.$i.':'.$select.'</p>';
        $pull[$i] = h(trim(urldecode(mb_convert_encoding($select, 'utf-8', 'auto'))));
    }
    $i++;
}

if (isset($_REQUEST['command'])) {
    switch ($_REQUEST['command']) {
        case 'update':
            if (empty($_REQUEST['team']) || empty($_REQUEST['title']) || empty($_REQUEST['date'])) break;
            $sql = $pdo->prepare('update plan set team=?, title=?, date=?, details=? where id=?');
            $sql->execute(
                [h($_REQUEST['team']), h($_REQUEST['title']), $_REQUEST['date'], h($_REQUEST['details']), $_REQUEST['id']]
            );
            break;
        case 'delete':
            $sql = $pdo->prepare('delete from plan where id=?');
            $sql->execute([$_REQUEST['id']]);
            header('Location:./plan_edit.php');
            break;
    }
}
foreach ($pdo->query('select * from plan') as $row) {

    $e_email = $row['email'];

    if (0 === strcmp($e_email, $email)) {

        echo <<<HTML
                <form action="plan_edit.php" method="post">
                    <input type="hidden" name="command" value="update">
                    <input type="hidden" name="id" value="{$row['id']}">
                    <select name="team">
HTML;
        foreach ($pull as $v) {
            if ($v == $row['team']) {
                echo '<option value="' . $v . '"  selected>' . $v . '</option>' . "\n";
            } else {
                echo '<option value="' . $v . '">' . $v . '</option>' . "\n";
            }
        }
        echo <<<HTML
                    </select>
                    <input type="text" name="title" value="{$row['title']}">
                    <input type="date" name="date" value="{$row['date']}">
                    <input type="text" name="details" value="{$row['details']}">
                    <input type="submit" value="更新">
                </form>
                <a href="javascript:void(0);" onclick="var ok = confirm('削除しますか？'); if (ok) location.href='plan_edit.php?id={$row['id']}&command=delete'">
                削除
                </a>
                <br>
HTML;
    }
}
?>

<a href="index.php">ホームに戻る</a>

<?php require '../footer.php'; ?>
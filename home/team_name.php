<?php require 'header.php'; ?>
<?php
require_once('../config.php');
require_once('../function.php');

if (!isset($_SESSION)) {
    session_start();
}

try {
    $pdo = new PDO(DSN, DB_USER, DB_PASS, ARY);
    $email = $_SESSION['EMAIL'];
    $team = $_SESSION['TEAM'];
    $id = $_SESSION['ID'];
    $sql = "select id, team from teamdata where id=\"" . $id . "\"";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
    $error = '存在しないチームです。';
    $alert =
        "<script type='text/javascript'>
            alert('" . $error . "');
            location.href = 'team.php';
            </script>";
    echo $alert;
}

if (isset($_REQUEST['id'])) {
    try {
        $sql = $pdo->prepare("update teamdata set team=? where id=?");
        $sql->execute([h(t($_REQUEST['team'])), $_REQUEST['id']]);
        $_SESSION['TEAM'] = h(t($_REQUEST['team']));
        header('Location:./team_name.php');
        exit;
    } catch (\Exception $e) {
        $error = '作成済みのチーム名です。';
        $alert =
            "<script type='text/javascript'>
            alert('" . $error . "');
            location.href = 'team.php';
            </script>";
        echo $alert;
    }
}
?>
<h3>チーム名の変更</h3>

<div class="nav">
    <ul>
        <li><a href="team_edit.php">メンバーの編集</a></li>
        <li class="current"><a href="team_name.php">チーム名の変更</a></li>
        <li><a href="team_pass.php">チームのパスワード変更</a></li>
        <li><a href="team.php">編集するチームを変更する</a></li>
        <li><a href="index.php"><i class="fas fa-home"></i>ホームに戻る</a></li>
    </ul>
</div>

<form action="#" method="post">
    <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
    <input type="text" name="team" value="<?php echo $row['team'] ?>">
    <input type="submit" value="更新">
</form>

<?php require 'footer.php'; ?>
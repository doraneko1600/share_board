<?php require 'header.php'; ?>
<?php
require_once('../config.php');
require_once('../function.php');

if (!isset($_SESSION)) {
    session_start();
}
$id = $_SESSION['ID'];

$pdo = new PDO(DSN, DB_USER, DB_PASS, ARY);
$sql = "select id from teamdata where email=\"" . $id . "\"";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_REQUEST['password'])) {
    // 二つの入力内容の比較
    if ($_POST['password'] === $_POST['password_check']) {
        // 正規表現でパスワードチェック
        if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        } else {
            $error = 'パスワードは半角英数字をそれぞれ1文字以上含んだ8文字以上で設定してください。';
        }
    } else {
        $error = 'パスワードが一致しません。';
    }
    //ポップアップ表示
    if (strcmp($error, $empty) != 0) {
        $alert =
            "<script type='text/javascript'>
            alert('" . $error . "');
            location.href = 'team_pass.php';
            </script>";
        echo $alert;
        return false;
    }
    try {
        // パスワード変更
        $sql = $pdo->prepare("update teamdata set password=? where id=?");
        $sql->execute([$password, $_REQUEST['id']]);
        $sign_up = '変更完了';
        $alert =
            "<script type='text/javascript'>
            alert('" . $sign_up . "');
            location.href = 'team_pass.php';
            </script>";
        echo $alert;
    } catch (\Exception $e) {
        $error = 'エラーが発生しました。';
        $alert =
            "<script type='text/javascript'>
            alert('" . $error . "');
            location.href = 'password_edit.php';
            </script>";
        echo $alert;
    }
}

?>
<h3>チームのパスワード変更</h3>

<div class="nav">
    <ul>
        <li><a href="team_edit.php">メンバーの編集</a></li>
        <li><a href="team_name.php">チーム名の変更</a></li>
        <li class="current"><a href="team_pass.php">チームのパスワード変更</a></li>
        <li><a href="team.php">編集するチームを変更する</a></li>
        <li><a href="index.php"><i class="fas fa-home"></i>ホームに戻る</a></li>
    </ul>
</div>

<form action="#" method="post">
    <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
    <label>
        <p>新しいパスワード</p>
    </label>
    <input type="password" name="password" required="required">
    <label for="password">
        <p>もう一度パスワードを入力する</p>
    </label>
    <input type="password" name="password_check" required="required">
    <br><input type="submit" value="更新">
</form>

<?php require 'footer.php'; ?>
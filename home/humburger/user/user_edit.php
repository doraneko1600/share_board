<?php require 'header.php'; ?>
<?php
require_once('../../../config.php');
require_once('../../../function.php');
require_once('../humburger.php');

if (!isset($_SESSION)) {
    session_start();
}

$pdo = new PDO(DSN, DB_USER, DB_PASS, ARY);
$email = isset($_SESSION['EMAIL']) ? $_SESSION['EMAIL'] : "";

$sql = "select id, email, name from userdata where email=\"" . $email . "\"";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);


if (isset($_REQUEST['name'])) {
    if (!$email = filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {
        $sign_up = '既に登録されてあるメールアドレスか不正なメールアドレスです。';
        $alert =
            "<script type='text/javascript'>
          alert('" . $sign_up . "');
          location.href = 'user_edit.php';
          </script>";
        echo $alert;
    }
    try {
        $sql = $pdo->prepare("update userdata set name=?, email=? where id=?");
        $sql->execute([h(t($_REQUEST['name'])), h(t(filter_var($_REQUEST['email']))), $_REQUEST['id']]);
        $sql = $pdo->prepare("update team_members set name=? where email=?");
        $sql->execute([h(t($_REQUEST['name'])), h(t(filter_var($_REQUEST['email'])))]);
        $sql = $pdo->prepare("update chat set c_name=? where email=?");
        $sql->execute([h(t($_REQUEST['name'])), h(t(filter_var($_REQUEST['email'])))]);
        $_SESSION['EMAIL'] = $_REQUEST['email'];
        header('Location:./user_edit.php');
        exit;
    } catch (\Exception $e) {
        $sign_up = '既に登録されてあるメールアドレスか不正なメールアドレスです。';
        $alert =
            "<script type='text/javascript'>
          alert('" . $sign_up . "');
          location.href = 'user_edit.php';
          </script>";
        echo $alert;
    }
}

?>
<!-- 名前とメアドの変更フォーム -->
<div class="adjust">
    <form action="#" method="post">
        <input type="hidden" name="id" value="<?php echo $user['id'] ?>">
        <p>
            <h4>名前とメールアドレスの変更</h4><br>
        </p>
        <p>
            <label>名前</label>
            <input type="text" name="name" value="<?php echo $user['name'] ?>">
        </p>
        <p>
            <label>メールアドレス</label>
            <input type="text" name="email" value="<?php echo $user['email'] ?>">
        </p>
        <input type="submit" value="更新">
    </form>
</div>
<p>
    <a class="home" href="../../index.php" style="text-decoration:none;">ホームに戻る</a>
    <br>
    <form class="jump" action="password_edit.php">
        <button type="submit">パスワードの変更に移る</button>
    </form>
</p>

<?php require 'footer.php'; ?>
<?php require 'header.php'; ?>
<?php
require_once('../../../config.php');
require_once('../../../function.php');
require_once('../humburger.php');
if (!isset($_SESSION)) {
    session_start();
}
$empty = $error = '';

$pdo = new PDO(DSN, DB_USER, DB_PASS, ARY);
$email = isset($_SESSION['EMAIL']) ? $_SESSION['EMAIL'] : "";

$sql = "select password,id from userdata where email=\"" . $email . "\"";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

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
            location.href = 'password_edit.php';
            </script>";
        echo $alert;
        return false;
    }
    try {
        // パスワード変更
        $sql = $pdo->prepare("update userdata set password=? where id=?");
        $sql->execute([$password, $_REQUEST['id']]);
        $sign_up = '変更完了';
        $alert =
            "<script type='text/javascript'>
            alert('" . $sign_up . "');
            location.href = 'password_edit.php';
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

// print_r($user);

?>

<!-- パスワードの変更 -->
<div class="adjust">
    <form action="#" method="post">
        <input type="hidden" name="id" value="<?php echo $user['id'] ?>">
        <p>
            <h4>パスワードの変更</h4><br>
        </p>
        <p>
            <label>新しいパスワード</label>
            <input type="password" name="password" required="required">
        </p>
        
        <p>
            <label for="password">もう一度パスワードを入力する</label>
            <input type="password" name="password_check" required="required">
        </p>
        
        <br><input type="submit" value="更新">
    </form>
</div>

<p>
    <a class="home" href="../../index.php">ホームに戻る</a>
    <br>
    <form class="jump" action="user_edit.php">
            <button type="submit">名前、メールアドレスの変更に移る</button>
    </form>
</p>

<?php require '../../footer.php'; ?>
<?php
require_once('../function.php');
require_once('../config.php');
require_once('header.php');

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_REQUEST['name'])) {
    // emailの確認
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error = '入力された値が不正です。';
        $alert =
            "<script type='text/javascript'>
        alert('" . $error . "');
        location.href = 'index.php';
        </script>";
        echo $alert;
    }

    // nameの確認
    $name = h($_POST['name']);

    try {
        $pdo = new PDO(DSN, DB_USER, DB_PASS, ARY);
        $stmt = $pdo->prepare('select name, email, id from userdata where email=? and name=? and id !=1');
        $stmt->execute([$_POST['email'], $name]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['mail_email'] = $row['email'];
        $_SESSION['mail_name'] = $row['name'];
        $_SESSION['mail_id'] = $row['id'];
        header('Location:./mail.php');
        exit;
    } catch (\Exception $e) {
        $error = 'メールアドレスもしくは名前が間違えています。';
        $alert =
            "<script type='text/javascript'>
        alert('" . $error . "');
        location.href = 'index.php';
        </script>";
        echo $alert;
    }
}
?>
<main class="mail">
    <h1>ユーザー情報を入力してください</h1><br><br>
    <form action="#" method="post">
        <div class="reset">
            <p>
                <label for="name">
                    名前

                </label>
                <input type="name" name="name" required="required">
            </p>
            <p>
                <label for="email">
                    メール
                </label>
                <input type="email" name="email" required="required">
            </p>
        </div>
        <div class="buttom">
            <br><input type="submit" value="パスワードをリセットする">
        </div>

    </form>
    <br><br><br>
    <p><a href="../">戻る</a></p>
</main>
<?php require_once('footer.php'); ?>
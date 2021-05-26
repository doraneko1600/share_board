<?php
session_start();
require_once('create_db.php');
require_once('config.php');
require_once('function.php');
require_once('header.php');
require_once('create_table.php');
require_once('admin/admin.php');
require_once('AA.php');
$pdo = new PDO(DSN, DB_USER, DB_PASS);

//ログイン済みの場合
if (isset($_SESSION['EMAIL'])) {
    header("location:home/index.php");
    exit;
}

$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する

?>
<img src="./images/share_bord_font.png">
<main>
    <h1>ようこそ、ログインしてください。</h1><br>
    <?php
    if ($_SESSION["error_status"] == 1) {
        echo "<h2 style='color:red'>メールアドレスまたはパスワードが異なります。</h2>";
    }
    if ($_SESSION["error_status"] == 2) {
        echo "<h2 style='color:red'>不正なリクエストです。</h2>";
    }
    if ($_SESSION["error_status"] == 3) {
        echo "<h2 style='color:red'>アカウントがロックされました。時間を空けてから再度お試しください。</h2>";
    }
    //エラー情報のリセット
    $_SESSION["error_status"] = 0;
    ?>
    <form action="login.php" method="post">
        <div class="contents">
            <p>
                <label for="email">
                    メール

                </label>
                <input type="email" name="email" required="required">
            </p>
            <p>
                <label for="password">
                    パスワード
                </label>
                <input type="password" name="password" required="required">
            </p>
        </div>

        <input type="hidden" name="token" value="<?php echo h($_SESSION['token'], ENT_QUOTES, "UTF-8") ?>">
        <div class="buttom">
            <input type="submit" value="ログイン">
        </div>
    </form>
    <br><br>
    <h2>初めての方は<a href="sign_up_page.php">こちら</a></h2>
    <br>
    <p>パスワードを忘れた方は<a href="mail/index.php">こちら</a></p>
</main>
<?php require_once('footer.php'); ?>
<?php
require_once('function.php');
require_once('header.php');
?>
<img src="./images/share_bord_font.png">
<main>
    <h3>アカウント作成</h3>
    <br>
    <form action="sign_up.php" method="post">

        <p>
            <label for="name">
                名前

            </label>
            <input type="name" name="name" required="required">
        </p>
        <p><label for="email">
                メール
            </label>
            <input type="email" name="email" required="required">
        </p>
        <p><label for="password">
                パスワード
            </label>
            <span>
                <input type="password" name="password" required="required">
            </span>
        </p>
        <p><label for="password">
                もう一度パスワードを入力する
            </label>
            <input type="password" name="password_check" required="required">
        </p>
        <p><input type="submit" value="作成"></p>
    </form>
    <p>※パスワードは半角英数字をそれぞれ１文字以上含んだ、８文字以上で設定してください。</p>
    <a href="index.php">戻る</a>
</main>
<?php require_once('footer.php'); ?>
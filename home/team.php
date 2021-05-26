<?php require('header.php'); ?>
<main>
    <div class="side">
        <div class="left">
            <h2>チームを作成する</h2>
            <form action="team_sign_up.php" method="post">
                <label for="team">
                    <p>チーム名</p>
                </label>
                <input type="team" name="team" required="required">
                <br><label for="password">
                    <p>パスワード</p>
                </label>
                <input type="password" name="password" required="required">
                <br><label for="password">
                    <p>もう一度パスワードを入力する</p>
                </label>
                <input type="password" name="password_check" required="required">
                <br><input type="submit" value="作成">
            </form>
        </div>
        <div class="right">
            <h2>作成されてるチームメンバーの編集をする</h2>
            <p>※作成者のみ編集出来ます。</p>
            <form action="team_sign_in.php" method="post">
                <label for="team">
                    <p>チーム名</p>
                </label>
                <input type="team" name="team" required="required">
                <br><label for="password">
                    <p>パスワード</p>
                </label>
                <input type="password" name="password" required="required">
                <br><input type="submit" value="編集する">
            </form>
        </div>
    </div>
    <p>※パスワードは半角英数字をそれぞれ１文字以上含んだ、８文字以上で設定してください。</p>
    <br>
    <a href="index.php">ホームに戻る</a>
</main>
<?php require_once('footer.php'); ?>
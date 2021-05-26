<?php
require 'header.php';
require_once '../humburger.php';
?>

<div class="edit">
    <div class="contents">
        <ul><h3>アカウント管理</h3></ul>
        <br><br>
        <li><a href="user_edit.php">名前、メールアドレスの変更</a></li><br>
        <li><a href="password_edit.php">パスワードの変更</a></li>
    </div>
    <br><br>
    <p><a class="home" href="../../index.php" style="text-decoration:none;">ホームに戻る</a></p>
</div>

<?php require 'footer.php'; ?>
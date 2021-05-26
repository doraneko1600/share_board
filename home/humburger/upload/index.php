<?php
session_start();
require_once('functions.php');
require_once('../../../function.php');
require('header.php');
require_once('../humburger.php');

$pdo = connectDB();
$email = isset($_SESSION['EMAIL']) ? $_SESSION['EMAIL'] : "";

$sql = "select distinct team from team_members where email=\"" . $email . "\"";
$upload = $pdo->query($sql);

$i = 0;
while ($row = $upload->fetch(PDO::FETCH_ASSOC)) {
    foreach ($row as $select) {
        $pull[$i] = h(trim(urldecode(mb_convert_encoding($select, 'utf-8', 'auto'))));
    }
    $i++;
}
?>

<div class="title">
        <h1>ファイル共有</h1>
</div>
<main>
    <form class="index" action="upload.php" method="post">
        <select name="team_select">
            <?php
            foreach ($pull as $v) {
                echo '<option value="' . $v . '">' . $v . '</option>' . "\n";
            }
            ?>
        </select>
        の共有ファイルを
        <button type="submit">表示する</button>
    </form>
    <br>
    <p>
        <a  class="home" href="../../index.php" style="text-decoration:none;">ホームに戻る</a>
    </p>
</main>
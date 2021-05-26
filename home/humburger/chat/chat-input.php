<?php require 'header.php'; ?>
<?php
require_once('../../../config.php');
require_once('../../../function.php');
require_once('../humburger.php');
if (!isset($_SESSION)) {
    session_start();
}
/*二重投稿の防止
if($_SERVER['REQUEST_METHOD']==='POST'){
    header('Location:./');
}*/


echo '<div class = "flex">';
if (isset($_POST['message'])) {
    //トークン番号の判定
    if ((isset($_REQUEST["chkno"]) == true) && (isset($_SESSION["chkno"]) == true) && ($_REQUEST["chkno"] == $_SESSION["chkno"])) {
        echo '<div id = "scroll">';
        require('chat-output.php');
        echo '</div>';
    }
}

$pdo = new PDO(DSN, DB_USER, DB_PASS);
$email = isset($_SESSION['EMAIL']) ? $_SESSION['EMAIL'] : "";
$sql = "select name from userdata where email=\"" . $email . "\"";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$name = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "select distinct team from team_members where email=\"" . $email . "\"";


$plan = $pdo->query($sql);
$i = 0;
while ($row = $plan->fetch(PDO::FETCH_ASSOC)) {
    foreach ($row as $select) {
        $pull[$i] = h(trim(urldecode(mb_convert_encoding($select, 'utf-8', 'auto'))));
    }
    $i++;
}

//トークンの生成
$_SESSION["chkno"] = $chkno = get_csrf_token();
?>



<div class="form">
    <p class="line">チーム名とメッセージを入力してください</P>
    <p>※グループ名のみ入力し、<br>メッセージを空欄で送信すると、<br>チャットの履歴を閲覧できます。</p>
    <form action="#" method="post">
        <div class=team>
            <p>チーム名</p>
            <select name="team">
                <!-- <option value="-1">▼選択</option> -->
                <?php
                foreach ($pull as $k => $v) {
                    echo '<option value="' . $v . '">' . $v . '</option>' . "\n";
                }
                ?>
            </select>
        </div>
        <div class="message">
            <br>
            <p>メッセージ</p>
            <textarea name="message" rows="10" cols="20" style="font-family:sans-serif" placeholder="ここに入力してください"></textarea>
            <input name="chkno" type="hidden" value="<?php echo $chkno; ?>">
            <br><input type="submit" value="送信">
        </div>
    </form>
    <a href="../../index.php" class="home" style="text-decoration:none;">ホームに戻る</a>
</div>
</div>
<?php require '../../footer.php'; ?>
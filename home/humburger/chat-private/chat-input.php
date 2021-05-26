<?php require 'header.php'; ?>
<?php
require_once('../humburger.php');
require_once('../../../config.php');
require_once('../../../function.php');
if (!isset($_SESSION)) {
    session_start();
}

$pdo = new PDO(DSN, DB_USER, DB_PASS);
$email = isset($_SESSION['EMAIL']) ? $_SESSION['EMAIL'] : "";

echo '<div class = "flex">';
if (isset($_REQUEST['mail']) && isset($_REQUEST['send'])) {
    //トークン番号の判定
    if ((isset($_REQUEST["chkno"]) == true) && (isset($_SESSION["chkno"]) == true) && ($_REQUEST["chkno"] == $_SESSION["chkno"])) {
        if (isset($_REQUEST['name_id'])) {
            // valu値にはteam_membersのidが入っているのでそこから検索をする
            $id = $_REQUEST['name_id'];
            if ($id == "-1") {
                $sign_up = 'チームメンバーを登録してください';
                $alert =
                    "<script type='text/javascript'>
                alert('" . $sign_up . "');
                location.href = 'chat-input.php';
                </script>";
                echo $alert;
                exit;
            }
            $sql = "select email from team_members where id=\"" . $id . "\"";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $mail = $row['email'];
            if ($_REQUEST['mail']) {
                $mail = h(t($_REQUEST['mail']));
            }
        }
        $_SESSION['mail'] = $mail;
        if (isset($_REQUEST['message'])) {
            $_SESSION['message'] = $_REQUEST['message'];
        }
        echo '<div id = "scroll">';
        require('chat-output.php');
        echo '</div>';
    } else {
        header("Location: chat-input.php");
        exit;
    }
}

$sql = "select distinct team from team_members where email=\"" . $email . "\"";
$plan = $pdo->query($sql);
$i = 0;
$pull[0] = null;
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
    <p class="line">メールアドレスとメッセージを入力してください</P>
    <p>※メールアドレスのみ入力し、<br>メッセージを空欄で送信すると、<br>チャットの履歴を閲覧できます。</p>
    <form action="#" method="post">
        <div class="mail">

            <div class="tab-wrap">
                <input id="TAB-01" type="radio" name="TAB" class="tab-switch" checked="checked" /><label class="tab-label" for="TAB-01">チーム内から選ぶ</label>
                <div class="tab-content">
                    <p>送信したい先を選択</p>
                    <label>チーム名
                        <select name="team">
                            <?php
                            foreach ($pull as $k => $v) {
                                echo '<option value="' . $v . '">' . $v . '</option>' . "\n";
                            }
                            ?>
                        </select>
                    </label>
                    <br>
                    <label>
                        名前
                        <!-- ajaxを使って表示 -->
                        <select class="name" name="name_id">
                            <option>ここに表示される</option>
                        </select>
                    </label>
                </div>
                <input id="TAB-02" type="radio" name="TAB" class="tab-switch" /><label class="tab-label" for="TAB-02">メールアドレス入力</label>
                <div class="tab-content">
                    <p>メールアドレス</p>
                    <input type="email" name="mail">
                </div>
            </div>

        </div>
        <div class="message">
            <br>
            <p>メッセージ</p>
            <textarea name="message" rows="10" cols="20" style="font-family:sans-serif" placeholder="ここに入力してください"></textarea>
            <input name="chkno" type="hidden" value="<?php echo $chkno; ?>">
            <br><input type="submit" name="send" value="送信">
        </div>
    </form>
    <a href="../../index.php" class="home" style="text-decoration:none;">ホームに戻る</a>
</div>
</div>
<?php require '../../footer.php'; ?>
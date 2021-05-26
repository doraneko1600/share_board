<?php
session_start();
require_once('../config.php');
require_once('../function.php');
require('header.php');
// require('check.php');

//二重投稿の防止
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Location:./');
}
$_SESSION["error_status"] = 0;
$pdo = new PDO(DSN, DB_USER, DB_PASS);
$email = isset($_SESSION['EMAIL']) ? $_SESSION['EMAIL'] : "";
$sql = "select name from userdata where email=\"" . $email . "\"";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$name = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$name['name']) {
    //セッション変数のクリア
    $_SESSION = array();
    //セッションクッキーも削除
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    //セッションクリア
    @session_destroy();
    header('Location:../');
    exit;
}

if (isset($_POST['title'])) {
    require('plan.php');
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
?>

<!--ヘッダー↓-->
<div class="menu">
    <div class="el_humburger">
        <!--ハンバーガーボタン-->
        <div class="el_humburger_wrapper">
            <span class="el_humburger_bar top"></span>
            <span class="el_humburger_bar middle"></span>
            <span class="el_humburger_bar bottom"></span>
        </div>
    </div>
    <nav>
        <div class="navi_inner">
            <div class="navi_item"><a href="humburger/user/index.php">アカウント管理</a></div>
            <div class="navi_item"><a href="humburger/todo/index.php">ToDoリスト</a></div>
            <div class="navi_item"><a href="humburger/chat/chat-input.php">チャット</a></div>
            <div class="navi_item"><a href="humburger/chat-private/chat-input.php">プライベートチャット</a></div>
            <div class="navi_item"><a href="humburger/upload/index.php">ファイルの共有</a></div>
        </div>
    </nav>
</div>
<!--ヘッダー↑-->

<!--トップ↓-->
<div class="top">
    <p>ようこそ「<?php echo h($name['name']) ?>」さん。ログアウトは<a href="../logout.php">こちら</a></p>
    <p class="team_null">
        <?php if ($pull[0] == null) {
            echo 'チームを作成してください。';
        } ?>
    </p>
</div>
<!--トップ↑-->

<!--メイン↓-->
<main>
    <div class="side">
        <div class="left">
            <!-- クリックでチームごとに表示されるページに移動する -->
            <p>
            <form action="index_team.php" method="post">
                <select name="team_select">
                    <option value="-1">あなた</option>
                    <?php
                    foreach ($pull as $k => $v) {
                        echo '<option value="' . $v . '">' . $v . '</option>' . "\n";
                    }
                    ?>
                </select>
                の予定を
                <button type="submit">表示する</button>
            </form>
            </p>
            <a href="team.php">
                <br>
                チームを作成もしくは編集する
                <br>
            </a>

            <hr>

            <form class="plan" action="index.php" method="post">
                <!--予定入力-->
                <p>予定を入力する</p>
                <p>※必ずチームを確認してください</p>
                <p><label for="team">チーム</label>
                    <select name="team">
                        <?php
                        foreach ($pull as $k => $v) {
                            echo '<option value="' . $v . '">' . $v . '</option>' . "\n";
                        }
                        ?>
                    </select>
                </p>
                <p><label for="title">タイトル</label>
                    <input class="title" type="text" name="title" required="required">
                </p>
                <p><label for="date">日付</label>
                    <input type="date" name="date" required="required">
                </p>
                <p><label for="details">詳細</label>
                    <textarea type="details" name="details" maxlength="100" rows="5"></textarea>
                </p>
                <br><input type="submit" value="登録">
            </form>
            <br>
            <form action="plan_edit.php">
                <button type="submit">予定を編集する</button>
            </form>

        </div>
        <div class="right">
            <!--カレンダー表示-->
            <div class="tabs">

                <input id="vertical" type="radio" name="tab_item" checked>
                <label class="tab_item" for="vertical">縦型カレンダー</label>

                <input id="horizontal" type="radio" name="tab_item">
                <label class="tab_item" for="horizontal">横型カレンダー</label>

                <div class="tab_content" id="vertical_content">
                    <iframe width="100%" height="500px" src="vertical.php"></iframe>
                </div>

                <div class="tab_content" id="horizontal_content">
                    <iframe width="100%" height="500px" src="horizontal.php" scrolling="no"></iframe>
                </div>
            </div>
        </div>
    </div>
</main>
<!--メイン↑-->

<?php require_once('footer.php'); ?>
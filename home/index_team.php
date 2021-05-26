<?php
session_start();
require_once('../config.php');
require_once('../function.php');
require('header.php');
// require('check.php');

//二重投稿の防止
/*
    if($_SERVER['REQUEST_METHOD']==='POST'){
		header('Location:./index_team.php');
	}
    */
$pdo = new PDO(DSN, DB_USER, DB_PASS);
$email = isset($_SESSION['EMAIL']) ? $_SESSION['EMAIL'] : "";
$sql = "select name from userdata where email=\"" . $email . "\"";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$name = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_POST['team_select'] == "-1") {
    header('Location:./');
    exit;
} else {
    $team = $_POST['team_select'];
    $_SESSION['team_select'] = $team;
    //echo $team;
}

$sql = "select distinct team from team_members where email=\"" . $email . "\"";
$plan = $pdo->query($sql);
//$row = $plan->fetch(PDO::FETCH_ASSOC);

// print_r($sql);

$i = 0;
while ($row = $plan->fetch(PDO::FETCH_ASSOC)) {
    foreach ($row as $select) {
        //echo '<p>'.$i.':'.$select.'</p>';
        $pull[$i] = h(trim(urldecode(mb_convert_encoding($select, 'utf-8', 'auto'))));
    }
    $i++;
}
?>

<!--ヘッダー↓-->
<header>
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
</header>
<!--ヘッダー↑-->

<!--トップ↓-->
<div class="top">
    <p>ようこそ「<?php echo h($name['name']) ?>」さん。ログアウトは<a href="../logout.php">こちら</a></p>
</div>
<!--トップ↑-->

<!--メイン↓-->
<main>
    <div class="side">
        <div class="left">
            <!-- クリックでチームごとに表示されるページに移動する -->
            <p>
            <form action="index_team.php" method="POST">
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
            <p>表示中のチームは「<?php echo $team ?>」です</p>
            <br>
            <a href="team.php">
                <?php echo "チームを登録もしくは編集する"; ?>
            </a>
            <br>
            <hr>
            <form class="plan" action="index.php" method="post">
                <!--予定入力-->
                <p class="center">予定を入力する</p>
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
                    <input class="title" type="title" name="title" required="required">
                </p>
                <p><label for="date">日付</label>
                    <input type="date" name="date" required="required">
                </p>
                <p><label for="details">詳細</label>
                    <textarea type="details" name="details" maxlength="100" rows="5"></textarea>
                </p>
                <br><button type="submit">登録</button>
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
                    <iframe width="100%" height="500px" src="vertical_team.php"></iframe>
                </div>

                <div class="tab_content" id="horizontal_content">
                    <iframe width="100%" height="500px" src="horizontal_team.php" scrolling="no"></iframe>
                </div>
            </div>
        </div>
    </div>
</main>
<!--メイン↑-->

<?php require_once('footer.php'); ?>
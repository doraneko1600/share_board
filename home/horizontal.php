<?php
session_start();
require_once('../config.php');
require_once('../function.php');
require('header.php');

//データベース関連
$pdo = new PDO(DSN, DB_USER, DB_PASS);
$email = isset($_SESSION['EMAIL']) ? $_SESSION['EMAIL'] : "";

//タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

//表示させる年月を設定　↓これは現在の月
if (isset($_GET['ym'])) {
    $ym = $_GET['ym'];
    $_SESSION['ym'] = $ym;
} else {
    // 今月の年月を表示
    $ym = date('Y-m');
}
//$year = date('Y');
//$month = date('m');

// タイムスタンプを作成し、フォーマットをチェックする
$timestamp = strtotime($ym . '-01');
if ($timestamp === false) {
    $ym = date('Y-m');
    $timestamp = strtotime($ym . '-01');
}

// 前月・次月の年月を取得
// mktimeを使う mktime(hour,minute,second,month,day,year)
$back = date('Y-m', mktime(0, 0, 0, date('m', $timestamp) - 1, 1, date('Y', $timestamp)));
$next = date('Y-m', mktime(0, 0, 0, date('m', $timestamp) + 1, 1, date('Y', $timestamp)));


// カレンダーのタイトルを作成
$calender = date('Y年n月', $timestamp);

//スケジュール設定
$arySchedule = [];
$sql = "select team,email,date,title,details from plan where team in (select team from team_members where email =  \"" . $email . "\")";
$plan = $pdo->query($sql);

while ($row = $plan->fetch(PDO::FETCH_ASSOC)) {
    $p_team = $row['team'];
    $p_email = $row['email'];
    $p_date = $row['date'];
    $p_title = $row['title'];
    $p_details = $row['details'];

    if (mb_substr($p_date, 0, 7) == date("Y-m", $timestamp)) {
        if (mb_substr($p_date, 8, 1) == 0) {
            $p_date_dd = mb_substr($p_date, 9);
        } else {
            $p_date_dd = mb_substr($p_date, 8);
        }

        //echo $p_date_dd;
        $arySchedule[$p_date_dd] = "$p_title : $p_details";
    }
}
//print_r($arySchedule);
$key = array_keys($arySchedule);
//print_r ($key);
$aryCalendar = [];
$j = 0;

$aryWeek = ['日', '月', '火', '水', '木', '金', '土'];

//1日の曜日を取得
$first_week = date('w', $timestamp);

//1日開始曜日までの穴埋め
for ($i = 0; $i < $first_week; $i++) {
    $aryCalendar[$j][] = '';
}

//月末日を取得
$end_month = date('t', $timestamp);

//1日から月末日までループ
for ($i = 1; $i <= $end_month; $i++) {
    //日曜日まで進んだら改行
    if (isset($aryCalendar[$j]) && count($aryCalendar[$j]) === 7) {
        $j++;
    }
    $aryCalendar[$j][] = $i;
}

//月末曜日の穴埋め
for ($i = count($aryCalendar[$j]); $i < 7; $i++) {
    $aryCalendar[$j][] = '';
}

?>

<h2>
    <a href="?ym=<?php echo $back; ?>">&lt;</a>
    <?php echo $calender; ?>
    <a href="?ym=<?php echo $next; ?>">&gt;</a>
</h2>

<table class="calendar">
    <!-- 曜日の表示 -->
    <tr>
        <?php foreach ($aryWeek as $week) { ?>
            <th><?php echo $week ?></th>
        <?php } ?>
    </tr>
    <!-- 日数の表示 -->
    <?php foreach ($aryCalendar as $tr) { ?>
        <tr>
            <?php foreach ($tr as $td) { ?>
                <?php if ($td != date('j')) { ?>
                    <?php if (in_array($td, $key)) { ?>
                        <td class="plan"><a href="" class="widelink" id="id" data-target="modal01"><?php echo $td ?></a></td>
                    <?php } else { ?>
                        <td><?php echo $td ?></td>
                    <?php } ?>

                <?php } else { ?>
                    <?php if (in_array($td, $key)) { ?>
                        <td class="today_plan"><a href="" class="widelink" id="id" data-target="modal01"><?php echo $td ?></a></td>
                    <?php } else { ?>
                        <!-- 今日の日付 -->
                        <td class="today"><?php echo $td ?></td>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        </tr>
    <?php } ?>
</table>

<div id="modal01" class="modal js-modal">
    <div class="modal__bg js-modal-close"></div>
    <div class="modal__content">
        <!--モーダルの中身-->
        <p id="msg">ここに予定が表示される</p>
        <a class="js-modal-close" href="">閉じる</a>
    </div>
</div>
<?php require_once('footer.php'); ?>
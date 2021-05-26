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
} else {
    // 今月の年月を表示
    $ym = date('Y-m');
}


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

//月末日を取得
//$end_month = date('t', strtotime($year.$month.'01'));
$end_month = date('t', $timestamp);



// カレンダーのタイトルを作成
$calender = date('Y年n月', $timestamp);

//スケジュール設定
//1~31+予備で40個の要素を用意(初期値は'')
$arySchedule = array_pad([], 40, '');
$sql = "select team,email,date,title,details from plan where team in (select team from team_members where email =  \"" . $email . "\")";



$plan = $pdo->query($sql);
while ($row = $plan->fetch(PDO::FETCH_ASSOC)) {
    $p_team = $row['team'];
    $p_email = $row['email'];
    $p_date = $row['date'];
    $p_title = $row['title'];
    $p_details = $row['details'];

    if (mb_substr($p_date, 0, 7) == date("Y-m", $timestamp)) {

        // yyyy-mm-dd 0~9
        if (mb_substr($p_date, 8, 1) == 0) {
            $p_date_dd = mb_substr($p_date, 9);
        } else {
            $p_date_dd = mb_substr($p_date, 8);
        }

        h($arySchedule[$p_date_dd] .= "<p class=\"margin\"> [$p_team]$p_title : $p_details</p>"); //予定の表示 ( .= で配列を続けて追加  /中身が入れ替わらないように/)
    }
}

// print_r($arySchedule);

$aryCalendar = [];

//1日から月末日までループ

for ($i = 1; $i <= $end_month; $i++) {
    $aryCalendar[$i]['day'] = $i;
    $aryCalendar[$i]['week'] = date('w', mktime(0, 0, 0, date('m', $timestamp), sprintf('%02d', $i)));
    if (isset($arySchedule[$i])) {
        $aryCalendar[$i]['text'] = $arySchedule[$i];
    } else {
        $aryCalendar[$i]['text'] = '';
    }
}

// print_r($aryCalendar);
//print_r($arySchedule);

$aryWeek = ['日', '月', '火', '水', '木', '金', '土'];
?>

<h2>
    <a href="?ym=<?php echo $back; ?>">&lt;</a>
    <?php echo $calender; ?>
    <a href="?ym=<?php echo $next; ?>">&gt;</a>
</h2>


<table class="calender_column">
    <?php foreach ($aryCalendar as $value) { ?>
        <?php if ($value['day'] != date('j')) { ?>
            <tr class="week<?php echo $value['week'] ?>">
            <?php } else { ?>
            <tr class="today">
            <?php } ?>
            <td>
                <?php echo $value['day'] ?>(<?php echo h($aryWeek[$value['week']]) ?>)
            </td>
            <td>
                <?php echo $value['text'] ?>
            </td>
            </tr>
        <?php } ?>
</table>



<?php require_once('footer.php'); ?>
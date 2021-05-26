<?php
require_once('../config.php');
require_once('../function.php');
session_start();
$pdo = new PDO(DSN, DB_USER, DB_PASS);
$email=isset($_SESSION['EMAIL'])?$_SESSION['EMAIL']:"";
$team_select=isset($_SESSION['team_select'])?$_SESSION['team_select']:"";

//1~31+予備で40個の要素を用意(初期値は'')
$arySchedule = array_pad([], 40,'');
$sql = "select team,email,date,title,details from plan";
$plan = $pdo->query($sql);

//タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

//表示させる年月を設定　↓これは現在の月
if (isset($_SESSION['ym'])) {
    $ym = $_SESSION['ym'];
} else {   
    // 今月の年月を表示
    $ym = date('Y-m');
}
$timestamp = strtotime($ym . '-01');
if ($timestamp === false) {
    $ym = date('Y-m');
    $timestamp = strtotime($ym . '-01');
}

while($row = $plan->fetch(PDO::FETCH_ASSOC)){
    $p_team = $row['team'];
    $p_email = $row['email'];
    $p_date = $row['date'];
    $p_title = $row['title'];
    $p_details = $row['details'];
          
    /*
    echo $p_team;
    echo $p_email;
    echo $p_date;
    echo $p_title;
    echo $p_details;
    */
    if($p_team == $team_select){
        if(mb_substr($p_date,0,7) == date("Y-m",$timestamp)){
            if(mb_substr($p_date,8,1) == 0){
                $p_date_dd = mb_substr($p_date,9);
            } else {
                $p_date_dd = mb_substr($p_date,8);
            }
            //echo $p_date_dd;
            h($arySchedule[$p_date_dd] .="<p class=\"margin\"> [$p_team]$p_title : $p_details</p>");
            //print_r($arySchedule);
        }
    }
}

//print_r($arySchedule);

$id = 0;
if (isset($_POST["id"]) == true && $_POST["id"] != "") {
    $id = intval($_POST["id"]);
}
$strRet = "";
if (1 <= $id && $id <= 31) {
    $strRet = $arySchedule[$id];
}

// 結果を返す
//echo $id;
echo(json_encode($strRet));

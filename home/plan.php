<?php
    require_once('../config.php');
    require_once('../function.php');
    
       

    //メール
    $email=isset($_SESSION['EMAIL'])?$_SESSION['EMAIL']:"";
    //echo $email;
    
    //チーム
    $team=$_POST['team'];
    if($team == null){
        header('Location:error.php');
        exit;
    }
    //echo $team;

    //タイトル
    $title=t(h($_POST['title']));
    //echo $title;
    
    //日付 yyyy-mm-dd形式
    $date=$_POST['date'];
    //echo $date;

    //詳細
    $details=t(h($_POST['details']));
    //echo $details;  

    //例外処理
    if(empty($title) && empty($details)){
        exit;
    }
    try {
        $stmt = $pdo->prepare("insert into plan(team, email, title, date, details) value(?, ?, ?, ?, ?)");
        $stmt->execute([$team, $email, $title, $date, $details]);
      } catch (\Exception $e) {
        echo "エラー";
      }

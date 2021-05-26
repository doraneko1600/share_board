<?php require 'header.php'; ?>
<?php
if(!isset($_SESSION)){
    session_start();
}
/*データベース取得*/
require_once '../../../config.php';
/*      ↑1回だけデータ取得*/
require_once '../../../function.php';

/*二重投稿の防止
if($_SERVER['REQUEST_METHOD']==='POST'){
    header('Location:./');
}
*/

/*config内の変数を取得*/
$pdo=new PDO(DSN, DB_USER, DB_PASS);

/*データベースに登録されている名前表示*/
$email=isset($_SESSION['EMAIL'])?$_SESSION['EMAIL']:"";
     /*↑中の値を探す*/
$sql = "select name from userdata where email=\"".$email."\"";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$name=$stmt->fetch(PDO::FETCH_ASSOC); 
$c_name=($name['name']);
$team=$_REQUEST['team'];
$message=$_REQUEST['message'];
t(h($message));
/*$file=$_REQUEST['file'];*/
$rei = 0;

if($message && $rei == 0){
    try {
        $stmt = $pdo->prepare("insert into chat(team, email, c_name, message) value(?, ?, ?, ?)");
        $stmt->execute([$team, $email, $c_name, $message]);
        //通知用カウント
        $stmt = $pdo->prepare("update userdata set verify_team = verify_team + 1 where email in (select email from team_members where team = ? and email != ?)");
        $stmt->execute([$team, $email]);
    } catch (\Exception $e) {
    echo "エラー";
    }
    $sql = "select team, email, c_name, message, created from chat";
    $chat = $pdo->query($sql);

    $zero = 0;

    /* ↓  チャットの内容を繰り返し表示*/
    while($row = $chat->fetch(PDO::FETCH_ASSOC)) {
        $c_team = $row['team'];
        $c_email = $row['email'];
        $c_name = $row['c_name'];
        $c_message = $row['message'];
        $c_created = $row['created'];
        /*$c_file = $row['file'];*/

        /*チーム名が一致するものを表示
        0が1になることでチーム名の表示を一回だけにする*/
        if( 0 === strcmp($c_team,$team)) {
            if($zero == 0){
                echo '<div class = "head">';
                echo '<p>';
                echo $c_team;
                echo '</p>';
                echo '</div>';
                $zero++;
            }

            /*メールアドレスが一致するものを表示
            相手のメッセージはelse側で表示*/
            echo '<div class = "box">';
            if( 0 === strcmp($c_email,$email)) {
                echo '<div class = "right">';
                echo '<p>';
                echo $c_name, '<br>';
                echo '<span class = "me">', $c_message, '</span><br>';
                echo $c_created;
                echo '</p>';
                echo '</div>';
            } else {
                echo '<div class = "left">';
                echo '<p>';
                echo $c_name, '<br>';
                echo '<span class = "partner">', $c_message, '</span><br>';
                echo $c_created;
                echo '</p>';
                echo '</div>';
            }
            echo '</div>';
        }
    }
}else{
    $sql = "select team, email, c_name, message, created from chat";
    $chat = $pdo->query($sql);

    $zero = 0;

    /* ↓  チャットの内容を繰り返し表示*/
    while($row = $chat->fetch(PDO::FETCH_ASSOC)) {
        $c_team = $row['team'];
        $c_email = $row['email'];
        $c_name = $row['c_name'];
        $c_message = $row['message'];
        $c_created = $row['created'];

        /*チーム名が一致するものを表示
        0が1になることでチーム名の表示を一回だけにする*/
        if( 0 === strcmp($c_team,$team)) {
            if($zero == 0){
                echo '<div class = "head">';
                echo '<p>';
                echo $c_team;
                echo '</p>';
                echo '</div>';
                $zero++;
            }

            /*メールアドレスが一致するものを表示
            相手のメッセージはelse側で表示*/
            echo '<div class = "box">';
            if( 0 === strcmp($c_email,$email)) {
                echo '<div class = "right">';
                echo '<p>';
                echo $c_name, '<br>';
                echo '<span class = "me">', $c_message, '</span><br>';
                echo $c_created;
                echo '</p>';
                echo '</div>';
            } else {
                echo '<div class = "left">';
                echo '<p>';
                echo $c_name, '<br>';
                echo '<span class = "partner">', $c_message, '</span><br>';
                echo $c_created;
                echo '</p>';
                echo '</div>';
            }
            echo '</div>';
        }
    }
}
?>




    <?php require '../../footer.php'; ?>
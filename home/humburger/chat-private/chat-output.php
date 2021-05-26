<?php require 'header.php'; ?>
<?php
if (!isset($_SESSION)) {
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
$pdo = new PDO(DSN, DB_USER, DB_PASS);

/*データベースに登録されている名前表示*/
$email = isset($_SESSION['EMAIL']) ? $_SESSION['EMAIL'] : "";
/*↑中の値を探す*/
$sql = "select name from userdata where email=\"" . $email . "\"";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$name = $stmt->fetch(PDO::FETCH_ASSOC);
$c_name = ($name['name']);
$mail = $_SESSION['mail'];/* 送信先のメール */
$message = $_SESSION['message'];
$message = t(h($message));

/*メール検証*/
$empty = $error = '';
if (!$mail = filter_var($mail, FILTER_VALIDATE_EMAIL)) {
    $error = '登録されていません。ご確認下さい。';
}

$sql = "select email from userdata where email=\"" . $mail . "\" and id!=1";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$chek_mail = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$chek_mail) {
    $error = '登録されていません。ご確認下さい。';
}

//ポップアップ表示

if (strcmp($error, $empty) != 0) {
    $alert =
        "<script type='text/javascript'>
    alert('" . $error . "');
    location.href = './chat-input.php';
    </script>";
    echo $alert;
    return false;
}

$rei = 0;

if ($message && $rei == 0) {
    try {
        $stmt = $pdo->prepare("insert into chat_private(your_email, my_email, message) value(?, ?, ?)");
        $stmt->execute([$mail, $email, $message]);
        //通知用カウント
        $stmt = $pdo->prepare("update userdata set verify = verify + 1 where email = ?");
        $stmt->execute([$email]);
    } catch (\Exception $e) {
        echo "エラー";
    }

    $sql = "select my_email, your_email, message, created from chat_private where my_email in (\"" . $email . "\", \"" . $mail . "\")";
    $chat = $pdo->query($sql);

    /* 相手の名前を取得 */
    $sql1 = "select name from userdata where email=\"" . $mail . "\"";
    $stmt = $pdo->prepare($sql1);
    $stmt->execute();
    $c_name = $stmt->fetch(PDO::FETCH_ASSOC);

    /* 自分の名前を取得 */
    $sql2 = "select name from userdata where email=\"" . $email . "\"";
    $stmt = $pdo->prepare($sql2);
    $stmt->execute();
    $c_my_name = $stmt->fetch(PDO::FETCH_ASSOC);

    $zero = 0;

    /* ↓  チャットの内容を繰り返し表示*/
    while ($row = $chat->fetch(PDO::FETCH_ASSOC)) {
        $c_mail = $row['your_email'];
        $c_my_mail = $row['my_email'];
        $c_message = $row['message'];
        $c_created = $row['created'];
        /*名前が一致するものを表示
        0が1になることで名前の表示を一回だけにする*/
        //if($c_mail === $mail) {
        if ($zero == 0) {
            echo '<div class = "head">';
            echo '<p>';
            echo $c_name['name'];
            echo '</p>';
            echo '</div>';
            $zero++;
        }

        /*メールアドレスが一致するものを表示
            相手のメッセージはelse側で表示*/
        echo '<div class = "box">';
        if ($c_my_mail === $email && $c_mail === $mail) {
            echo '<div class = "right">';
            echo '<p>';
            echo $c_my_name['name'], '<br>';
            echo '<span class = "me">', $c_message, '</span><br>';
            echo $c_created;
            echo '</p>';
            echo '</div>';
        }
        if ($c_my_mail === $mail && $c_mail === $email) {
            echo '<div class = "left">';
            echo '<p>';
            echo $c_name['name'], '<br>';
            echo '<span class = "partner">', $c_message, '</span><br>';
            echo $c_created;
            echo '</p>';
            echo '</div>';
        }
        echo '</div>';
        //}
    }
} else {
    /*名前のみ送信された場合の処理*/
    $sql = "select my_email, your_email, message, created from chat_private where my_email in (\"" . $email . "\", \"" . $mail . "\")";
    $chat = $pdo->query($sql);

    /* 相手の名前を取得 */
    $sql1 = "select name from userdata where email=\"" . $mail . "\"";
    $stmt = $pdo->prepare($sql1);
    $stmt->execute();
    $c_name = $stmt->fetch(PDO::FETCH_ASSOC);

    /* 自分の名前を取得 */
    $sql2 = "select name from userdata where email=\"" . $email . "\"";
    $stmt = $pdo->prepare($sql2);
    $stmt->execute();
    $c_my_name = $stmt->fetch(PDO::FETCH_ASSOC);

    $zero = 0;

    /* ↓  チャットの内容を繰り返し表示*/
    while ($row = $chat->fetch(PDO::FETCH_ASSOC)) {
        $c_mail = $row['your_email'];
        $c_my_mail = $row['my_email'];
        $c_message = $row['message'];
        $c_created = $row['created'];

        /*名前が一致するものを表示
        0が1になることで名前の表示を一回だけにする*/
        //if( $c_mail === $mail) {
        if ($zero == 0) {
            echo '<div class = "head">';
            echo '<p>';
            echo $c_name['name'];
            echo '</p>';
            echo '</div>';
            $zero++;
        }

        /*メールアドレスが一致するものを表示
            相手のメッセージはelse側で表示*/
        echo '<div class = "box">';
        if ($c_my_mail === $email && $c_mail === $mail) {
            echo '<div class = "right">';
            echo '<p>';
            echo $c_my_name['name'], '<br>';
            echo '<span class = "me">', $c_message, '</span><br>';
            echo $c_created;
            echo '</p>';
            echo '</div>';
        }
        if ($c_my_mail === $mail && $c_mail === $email) {
            echo '<div class = "left">';
            echo '<p>';
            echo $c_name['name'], '<br>';
            echo '<span class = "partner">', $c_message, '</span><br>';
            echo $c_created;
            echo '</p>';
            echo '</div>';
        }
        echo '</div>';
        //}
    }
}
?>




    <?php require '../../footer.php'; ?>
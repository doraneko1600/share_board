<?php
require_once('config.php');
require_once('function.php');
$pdo = new PDO(DSN, DB_USER, DB_PASS, ARY);

//adminのidと名前とメアドとパス
//変更する場合はdbのuserdataからid=0を消去して最初の画面を読み込む
$id = "1"; //不変
$name = "administrator_0305";
$email = "doraneko1600@gmail.com";
$pass = "administrator_security_1600";

//ハッシュ化
$pass = password_hash($pass, PASSWORD_DEFAULT);

//id=1のselect
$id_1 = "";
$sql = "select id from userdata where id=1";
foreach ($pdo->query($sql) as $row) {
    $id_1 = $row['id'];
}

//id=1が無ければ実行
if ($id_1 === "") {
    try {
        $stmt = $pdo->prepare("insert into userdata(id, name, email, password) value(?, ?, ?, ?)");
        $stmt->execute([$id, $name, $email, $pass]);
    } catch (\Exception $e) {
        echo $e;
    }
}
?>
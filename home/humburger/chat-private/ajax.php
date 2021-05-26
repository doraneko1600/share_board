<?php
require_once('../../../config.php');
require_once('../../../function.php');
if (!isset($_SESSION)) {
    session_start();
}
$pdo = new PDO(DSN, DB_USER, DB_PASS);
$email = isset($_SESSION['EMAIL']) ? $_SESSION['EMAIL'] : "";
$team = $_POST['team'];//ajax.jsから取得した内容

$sql = "select name,id from team_members where team=\"" . $team . "\" and email!=\"" . $email . "\"";

$stmt = $pdo->prepare($sql);
$stmt->execute();
//$name = $stmt->fetch(PDO::FETCH_ASSOC);


while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $list[$row['id']] = $row['name'];//$listの中身は'name',添え字が'id'
}

//ajax内でのechoは一回のみ
//json_encodeがechoするときに必須
//echoの内容がajax.jsに渡される
echo(json_encode($list));
?>
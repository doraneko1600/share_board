<?php
require_once ('../../../config.php');

if (!isset($_SESSION)) {
    session_start();
}

$pdo = new PDO(DSN, DB_USER, DB_PASS);
$email = $_SESSION['EMAIL'];
// $email = isset($_SESSION['EMAIL']) ? $_SESSION['EMAIL'] : "";
print_r($pdo);

$task = $_POST["content"]; //内容
$rank = $_POST["priority"]; //優先度
$period = $_POST["limit"]; //期限
$team = $_POST["team"]; // チーム名
print_r($_POST);

$sql = "INSERT INTO todo SET email=:email, team=:team,task=:task, rank=:rank, period=:period";

// sql実行

try{
$sth = $pdo->prepare($sql);
$sth->bindValue(":team",$team,PDO::PARAM_STR);
$sth->bindValue(":email",$email,PDO::PARAM_STR);
$sth->bindValue(":task",$task,PDO::PARAM_STR);
$sth->bindValue(":rank",$rank,PDO::PARAM_STR);
$sth->bindValue(":period",$period,PDO::PARAM_STR);

$sth->execute();
echo "success";
}catch(Exception $e){
    echo $e;
}

print_r($sql);

header("Location: ./index.php");
?>
<?php
require_once('../config.php');
require_once('../function.php');
require('header.php');

$email = isset($_SESSION['EMAIL']) ? $_SESSION['EMAIL'] : "";

$pdo = new PDO(DSN, DB_USER, DB_PASS);

$sql = ("select verify from userdata where = ?");
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row === 0){
    echo "更新なし";
}else{
    echo "更新あり";
}

?>
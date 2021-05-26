<?php
header('Content-type: application/pdf');
require_once('header.php');
require_once('functions.php');
//接続
$pdo = connectDB();

$id = $_GET['id'];

$sql = "select image_content from images where id=\"".$id . "\"";
 
$result = $pdo->query($sql);
$result->execute();
$row = $result->fetch();
 
echo $row[0];

require_once('footer.php');
?>
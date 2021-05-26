<?php
require_once 'functions.php';

$pdo = connectDB();

$sql = 'DELETE FROM images WHERE id = :id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', (int)$_GET['id'], PDO::PARAM_INT);
$stmt->execute();

unset($pdo);
header('Location:upload.php');
exit();
?>
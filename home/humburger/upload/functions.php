<?php
ini_set ('display_errors',1);//エラー表示
//0で非表示、1で表示
// データベースに接続
function connectDB() {
    $param = 'mysql:dbname=share_board;host=localhost;charset=utf8mb4;';

    try {
        $pdo = new PDO($param, 'root', '');
        return $pdo;

    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
}

?>
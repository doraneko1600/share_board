<?php

// ini_set("memory_limit","4096M");
// ini_set("upload_max_filesize","4096M");
// ini_set("post_max_size","4096M");
// my.ini (max_allowed_packet=-1) 38L

//ユーザ定義関数

function h($s){
    return htmlspecialchars($s, ENT_QUOTES, 'utf-8');//ユーザーの入力内容の制限
}

function t($trim){
    return preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $trim);
}

/*
* CSRF トークン作成
*/
function get_csrf_token()
{
    $TOKEN_LENGTH = 16; //16*2=32byte
    $bytes = openssl_random_pseudo_bytes($TOKEN_LENGTH);
    return bin2hex($bytes);
}
/*
* PDO の接続オプション取得
*/
function get_pdo_options()
{
    return array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
        PDO::ATTR_EMULATE_PREPARES => false
    );
}
/*
* ログイン画面へのリダイレクト
*/
function redirect_to_login()
{
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: index.php");
}
/*
* 登録画面へのリダイレクト
*/
function redirect_to_register()
{
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: sign_up_page.php");
}
/*
* Welcome画面へのリダイレクト
*/
function redirect_to_welcome()
{
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: home/index.php");
}
?>
<?php
require_once('function.php');
require_once('config.php');

session_start();
$empty = $error = '';

$email = $_POST['email'];
$password = $_POST['password'];
$token = $_POST['token'];

if ($token != $_SESSION['token']) {
    $_SESSION = array();
    session_destroy();
    session_start();
    // リダイレクト
    $_SESSION["error_status"] = 2;
    redirect_to_login();
    exit();
}

//POSTの確認
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $error = '入力された値が不正です。';
}
//DB内でメールアドレスを検索
try {
    $pdo = new PDO(DSN, DB_USER, DB_PASS, get_pdo_options());

    $stmt = $pdo->prepare('select * from userdata where email = ? and id != 1');
    $stmt->execute([$_POST['email']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // ユーザー抽出
    $rows = login_get_user_rows($email, $pdo);
    // アカウントロックチェック
    if (count($rows) > 0 && !empty($rows[0]['locked_time'])) {
        $lock_time_diff = strtotime('now') - strtotime($rows[0]['locked_time']);
        // アカウントロック中
        if ($lock_time_diff < LOGIN_LOCK_PERIOD) {
            // リダイレクト
            $_SESSION["error_status"] = 3;
            redirect_to_login();
            exit();
        } else {
            // アカウントロック期間終了だったらロック解除
            unlock_login_account($email, $pdo);
        }
    }
    // ログイン認証
    // メールアドレスアンマッチ
    if (count($rows) == 0) {
        // リダイレクト
        $_SESSION["error_status"] = 1;
        redirect_to_login();
        exit();
    }
    // パスワード認証失敗
    if (!password_verify($password, $rows[0]['password'])) {
        // 失敗カウントアップ
        login_failed_count_up($email, $pdo);
        // 失敗カウント取得
        $count = get_login_failed_count($email, $pdo);
        if ($count >= LOGIN_FAILED_LIMIT
        ) {
            // アカウントロック
            lock_login_account($email, $pdo);
            // リダイレクト
            $_SESSION["error_status"] = 3;
            redirect_to_login();
            exit();
        }
        // リダイレクト
        $_SESSION["error_status"] = 1;
        redirect_to_login();
        exit();
    }
    // ログイン成功
    // アカウントロック解除
    unlock_login_account($email, $pdo);
    // セッションIDの振り直し
    session_regenerate_id(true);
    // リダイレクト
    $_SESSION['EMAIL'] = $rows[0]['email'];
    $login = 'ログインしました';
    $alert =
        "<script type='text/javascript'>
  alert('" . $login . "');
  location.href = 'home/index.php';
  </script>";
    echo $alert;
    exit();
} catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}

/*
* メールアドレスからユーザー情報を取得する
*/
function login_get_user_rows($email, $pdo)
{
    $sql = "SELECT * FROM userdata WHERE email = ?;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $email, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
/*
* ログイン失敗のカウントアップをする
*/
function login_failed_count_up($email, $pdo)
{
    $sql = "UPDATE userdata SET failed_count = failed_count + 1 WHERE email = ?;";
    $stmt = $pdo->prepare($sql);
    // トランザクションの開始
    $pdo->beginTransaction();
    try {
        $stmt->bindValue(1, $email, PDO::PARAM_STR);
        $stmt->execute();
        // コミット
        $pdo->commit();
    } catch (PDOException $e) {
        // ロールバック
        $pdo->rollBack();
        throw $e;
    }
}
/*
* ログイン失敗のカウントを取得する
*/
function get_login_failed_count($email, $pdo)
{
    $sql = "SELECT failed_count FROM userdata WHERE email = ?;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $email, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!empty($row)) {
        return $row['failed_count'];
    }
    return 0; // emailが該当しない場合は処理なし
}
/*
* アカウントロックを行う
*/
function lock_login_account($email, $pdo)
{
    $sql = "UPDATE userdata SET locked_time = ? WHERE email = ?;";
    $stmt = $pdo->prepare($sql);
    // トランザクションの開始
    $pdo->beginTransaction();
    try {
        $stmt->bindValue(1, date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(2, $email, PDO::PARAM_STR);
        $stmt->execute();
        // コミット
        $pdo->commit();
    } catch (PDOException $e) {
        // ロールバック
        $pdo->rollBack();
        throw $e;
    }
}
/*
* アカウントのアンロックを行う
*/
function unlock_login_account($email, $pdo)
{
    $sql = "UPDATE userdata SET failed_count = 0, locked_time = NULL WHERE email = ?;";
    $stmt = $pdo->prepare($sql);
    // トランザクションの開始
    $pdo->beginTransaction();
    try {
        $stmt->bindValue(1, $email, PDO::PARAM_STR);
        $stmt->execute();
        // コミット
        $pdo->commit();
    } catch (PDOException $e) {
        // ロールバック
        $pdo->rollBack();
        throw $e;
    }
}




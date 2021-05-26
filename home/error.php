<?php
$error = 'チームを選択してください';
$alert =
    "<script type='text/javascript'>
    alert('" . $error . "');
    location.href = 'index.php';
    </script>";
echo $alert;


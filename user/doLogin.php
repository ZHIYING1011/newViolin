<?php

session_start();

if (isset($_SESSION["user"])) {
    header("location:users.php");
    exit;
}

// 抓登入資訊
$account = $_POST["account"];
$password = $_POST["password"];
$captcha = $_POST["captcha"];

// 檢查是否有未填寫的欄位
if (empty($account)) {
    $_SESSION["error"]["message"] = "請輸入帳號";
    header("location:adminLogin.php");
    exit;
}

if (empty($password)) {
    $_SESSION["error"]["message"] = "請輸入密碼";
    header("location:adminLogin.php");
    exit;
}

if (empty($captcha)) {
    $_SESSION["error"]["message"] = "請輸入驗證碼";
    header("location:adminLogin.php");
    exit;
}

// // 驗證碼內容驗證
if ($captcha !== $_SESSION['captcha']) {
    $_SESSION["error"]["message"] = "驗證碼錯誤";
    header("location:adminLogin.php");
    exit;
}


require_once("../db_connect.php");

$password = md5($password);

$sql = "SELECT * FROM users WHERE account = '$account' AND password = '$password'";
$result = $conn->query($sql);
$userCount = $result->num_rows;


if ($userCount == 1) {
    // 登入成功
    unset($_SESSION["error"]);

    $user = $result->fetch_assoc();
    $_SESSION["user"] = [
        "account" => $user["account"],
        "user_name" => $user["user_name"],
    ];

    header("location:users.php");
    exit;
} else {
    // 帳號或密碼錯誤
    if (!isset($_SESSION["error"]["times"])) {
        $_SESSION["error"]["times"] = 1;
    } else {
        $_SESSION["error"]["times"]++;
    }

    $errorTimes = $_SESSION["error"]["times"];
    $acceptErrorTimes = 5;
    $remainErrorTimes = $acceptErrorTimes - $errorTimes;

    $_SESSION["error"]["message"] = "帳號或密碼錯誤，還有 $remainErrorTimes 次機會";

    header("location:adminLogin.php");
    exit;
}

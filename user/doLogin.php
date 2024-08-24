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

// 驗證碼內容驗證
if ($captcha !== $_SESSION['captcha']) {
    $_SESSION["error"]["message"] = "驗證碼錯誤";
    header("location:adminLogin.php");
    exit;
}

require_once("../db_connect.php");

$password = md5($password);

// 首先檢查帳號是否存在
$sql = "SELECT * FROM users WHERE account = '$account'";
$result = $conn->query($sql);
$userCount = $result->num_rows;

if ($userCount == 1) {
    $user = $result->fetch_assoc();

    // 檢查帳號是否被停用
    if ($user['valid'] == 0) {
        $_SESSION["error"]["message"] = "帳號已被停用，請聯絡管理員";
        header("location:adminLogin.php");
        exit;
    }

    // 檢查密碼是否正確
    if ($user['password'] == $password) {
        // 登入成功
        unset($_SESSION["error"]);
        $_SESSION["user"] = [
            "account" => $user["account"],
            "user_name" => $user["user_name"],
        ];

        header("location:users.php");
        exit;
    } else {
        // 密碼錯誤
        if (!isset($_SESSION["error"]["times"])) {
            $_SESSION["error"]["times"] = 1;
        } else {
            $_SESSION["error"]["times"]++;
        }

        $errorTimes = $_SESSION["error"]["times"];
        $acceptErrorTimes = 5;
        if ($errorTimes > $acceptErrorTimes) {
            // 錯誤次數達到上限
            $_SESSION["error"]["message"] = "密碼錯誤次數過多，請稍後再試或聯絡管理員";
            // 在此不彈出 modal 或者可以設定其他行為，例如鎖定帳號或通知管理員
        } else {
            // 錯誤次數未達上限，繼續提示剩餘次數
            $remainErrorTimes = $acceptErrorTimes - $errorTimes;
            $_SESSION["error"]["message"] = "密碼錯誤，還有 $remainErrorTimes 次機會";
        }
        header("location:adminLogin.php");
        exit;
    }
} else {
    // 帳號不存在
    $_SESSION["error"]["message"] = "帳號不存在";
    header("location:adminLogin.php");
    exit;
}

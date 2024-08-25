<?php

session_start();

// 重置錯誤次數
unset($_SESSION["error"]);

// 重定向回登入頁面
header("location:adminLogin.php");
exit;

?>

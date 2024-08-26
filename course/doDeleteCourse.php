
<?php

require_once("../db_connect.php");

if (!isset($_GET["id"])) {
    echo "請依循正常管道進入此頁";
    exit;
}

$id = $_GET["id"];

$sql = "UPDATE course SET course_valid=0 WHERE course_id=$id";

if ($conn->query($sql) === TRUE) {
    // 刪除成功，重定向到 course-list.php
    header("Location: course-list.php");
    exit; // 確保腳本在重定向後停止執行
} else {
    // 顯示錯誤訊息，但不要使用 header 重定向
    echo "刪除資料錯誤: " . $conn->error;
}

$conn->close();
?>
<?php
require_once("../db_connect.php");

if (!isset($_GET["id"])) {
    echo "請循正常管道進入此頁";
    exit;
}
$id = $_GET["id"];
$sql = "UPDATE writing SET valid = 0 WHERE id=$id";

if($conn->query($sql)===TRUE){
    echo "<script>alert('刪除成功'); window.history.back();</script>";
}else{
    echo "刪除資料錯誤: ". $conn->error;
}

$conn->close();

// header("location: writing.php");

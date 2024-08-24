<?php

$servername = "localhost";
$username = "admin";
$dbpassword = "12345";
$dbname = "violin";

// Create connection
$conn = new mysqli($servername, $username, $dbpassword, $dbname);

// 檢查連線
if ($conn->connect_error) { 
  	die("連線失敗: " . $conn->connect_error);
}else{
    // echo "連線成功";
}

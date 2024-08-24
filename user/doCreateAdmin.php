<?php

require_once("../db_connect.php");

if (!isset($_POST["account"])) {
    echo json_encode(["status" => 0, "message" => "請循正常管道進入此頁"]);
    exit;
}

// 取得表單資料
$account = $_POST["account"];
$password = $_POST["password"];
$confirm_password = $_POST["confirm_password"];
$user_name = $_POST["user_name"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$birthday = $_POST["birthday"];
$gender = isset($_POST["gender"]) ? $_POST["gender"] : null;
$address_city_id = $_POST["address_city_id"];
$address_cityarea_id = $_POST["address_cityarea_id"];
$address_street = $_POST["address_street"];
$create_date = date('Y-m-d H:i:s');


// 查詢帳號是否存在
$sqlCheck = "SELECT * FROM users WHERE account = '$account'";
$result = $conn->query($sqlCheck);
if ($result->num_rows > 0) {
    echo json_encode(["status" => 0, "message" => "該帳號已存在"]);
    exit;
}


// Hash 密碼
$password = md5($password);

// 插入資料庫
$sql = "INSERT INTO `users`(`user_name`, `type_id`, `level_id`, `account`, `password`, `phone`, `email`, `birthday`, `gender`, `address_city_id`, `address_cityarea_id`, `address_street`, `create_date`, `valid`) 
        VALUES ('$user_name', 1, 3, '$account', '$password', '$phone', '$email', '$birthday', '$gender', '$address_city_id', '$address_cityarea_id', '$address_street', '$create_date', 1)";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => 1, "message" => "帳號新增成功"]);
} else {
    echo json_encode(["status" => 0, "message" => "資料庫錯誤: " . $conn->error]);
}

$conn->close();
exit;


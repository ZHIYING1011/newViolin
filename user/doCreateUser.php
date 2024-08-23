<?php

require_once("../db_connect.php");

if (!isset($_POST["account"])) {
    // echo "請循正常管道進入此頁";
    $data = [
        "status" => 0,
        "message" => "請循正常管道進入此頁"
    ];
    echo json_encode($data);
    exit;
}

$account = $_POST["account"];
if (empty($account)) {
    // echo "帳號不能為空";
    $data = [
        "status" => 0,
        "message" => "帳號不能為空"
    ];
    echo json_encode($data);
    exit;
}

$sqlCheck = "SELECT * FROM users WHERE account = '$account'";
$result = $conn->query($sqlCheck);
$userCount = $result->num_rows;

if ($userCount > 0) {
    // echo "該帳號已存在";
    $data = [
        "status" => 0,
        "message" => "該帳號已存在"
    ];
    echo json_encode($data);
    exit;
}


$password = $_POST["password"];
if (empty($password)) {
    // echo "密碼不能為空";
    $data = [
        "status" => 0,
        "message" => "密碼不能為空"
    ];
    echo json_encode($data);
    exit;
}

$confirm_password = $_POST["confirm_password"];
if ($password != $confirm_password) {
    // echo "密碼輸入不一致";
    $data = [
        "status" => 0,
        "message" => "密碼輸入不一致"
    ];
    echo json_encode($data);
    exit;
}

$password = md5($password);
$user_name = $_POST["user_name"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$birthday = $_POST["birthday"];
$gender = isset($_POST["gender"]) ? $_POST["gender"] : null;
$address_city_id = $_POST["address_city_id"];
$address_cityarea_id = $_POST["address_cityarea_id"];
$address_street = $_POST["address_street"];
$create_date = date('Y-m-d H:i:s');


$sql = "INSERT INTO `users`( `user_name`, `user_type`, `level_id`, `account`, `password`, `phone`, `email`, `birthday`, `gender`, `address_city_id`, `address_cityarea_id`, `address_street`, `create_date`, `valid`) 
VALUES ('$user_name',2,3, '$account','$password',  '$phone', '$email', '$birthday', '$gender','$address_city_id','$address_cityarea_id','$address_street','$create_date', 1)";


if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
    // echo "新資料輸入成功, id 為 $last_id";
    header("location: users.php");

    $data = [
        "status" => 1,
        "message" => "新資料輸入成功, id 為 $last_id"
    ];
    echo json_encode($data);
    exit;
} else {

    $data = [
        "status" => 0,
        "message" => "Error: " . $sql . "<br>" . $conn->error
    ];
    echo json_encode($data);
    exit;
}

header("location: users.php");

$conn->close();

<?php

require_once("../db_connect.php");

if (!isset($_POST["id"])) {
    echo "請循正常管道進入此頁";
    exit;
}

$id = $_POST["id"];
$user_name = $conn->real_escape_string($_POST["user_name"]);
$phone = $conn->real_escape_string($_POST["phone"]);
$email = $conn->real_escape_string($_POST["email"]);
$birthday = $conn->real_escape_string($_POST["birthday"]);
$gender = isset($_POST["gender"]) ? intval($_POST["gender"]) : null;
$address_city_id = intval($_POST["address_city_id"]);
$address_cityarea_id = intval($_POST["address_cityarea_id"]);
$address_street = $conn->real_escape_string($_POST["address_street"]);
$valid = isset($_POST["valid"]) ? ($_POST["valid"]) : null;
$update_time = date('Y-m-d H:i:s');

// 密碼更新
if (!empty($_POST["password"])) {
    $password = md5($_POST["password"]);
    $sql = "UPDATE users SET 
                user_name='$user_name',
                password='$password',
                phone='$phone',
                email='$email',
                birthday='$birthday',
                gender='$gender',
                address_city_id='$address_city_id',
                address_cityarea_id='$address_cityarea_id',
                address_street='$address_street',
                valid='$valid',
                update_time='$update_time' 
            WHERE id=$id";
} else {
    $sql = "UPDATE users SET 
                user_name='$user_name',
                phone='$phone',
                email='$email',
                birthday='$birthday',
                gender='$gender',
                address_city_id='$address_city_id',
                address_cityarea_id='$address_cityarea_id',
                address_street='$address_street',
                valid='$valid',
                update_time='$update_time' 
            WHERE id=$id";
}

if ($conn->query($sql) === TRUE) {
    echo "更新成功";
    header("location: users.php");
} else {
    echo "更新資料表錯誤: " . $conn->error;
}

$conn->close();

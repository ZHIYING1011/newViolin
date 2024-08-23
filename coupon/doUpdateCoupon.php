<?php
require_once("../db_connect.php");

// $coupon_sid = $_POST["coupon_sid"];
// $coupon_name = $_POST["coupon_name"];

$id = $_POST["id"];
$coupon_info = $_POST["coupon_info"];
$coupon_rewardType = $_POST["coupon_rewardType"];
$coupon_lowPrice = $_POST["coupon_lowPrice"];
$coupon_maxUse = $_POST["coupon_maxUse"]  ?? -1;
$coupon_mode = $_POST["coupon_mode"];
$coupon_amount = $_POST["coupon_amount"] ?? -1;
$coupon_send = $_POST["coupon_send"];
$coupon_startDate = $_POST["coupon_startDate"];
$coupon_endDate = $_POST["coupon_endDate"];
$product_id = $_POST["product_id"];
$coupon_specifyDate = $_POST["coupon_specifyDate"];
$coupon_state = $_POST["coupon_state"];

$sql = "UPDATE coupon SET
coupon_info='$coupon_info', 
coupon_rewardType='$coupon_rewardType', 
coupon_lowPrice='$coupon_lowPrice', 
coupon_maxUse='$coupon_maxUse', 
coupon_mode='$coupon_mode', 
coupon_amount='$coupon_amount', 
coupon_send='$coupon_send', 
coupon_startDate='$coupon_startDate', 
coupon_endDate='$coupon_endDate', 
product_id='$product_id', 
coupon_specifyDate='$coupon_specifyDate'
coupon_state='$coupon_state'
WHERE id=$id";




echo $sql;

if ($conn->query($sql) === TRUE) {
    echo "更新成功";
} else {
    echo "更新資料錯誤：" . $conn->error;
}
//回使用者列表
header("location:couponIndex.php");

echo $sql;

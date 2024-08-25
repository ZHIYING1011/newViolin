<?php

require_once("../db_connect.php");
$id = $_GET["id"] ?? NULL;
if ($id != NULL) {
    $sql = "UPDATE coupon SET valid=0 WHERE id =" . $id;
    $result = $conn->query($sql);
    echo "123456";
}


header("location: couponIndex.php");

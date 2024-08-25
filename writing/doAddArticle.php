<?php
require_once("../db_connect.php");

if (!isset($_POST["title"])) {
    echo "請循正常管道進入此頁";
    exit;
}

$title = $_POST["title"];
if (empty($title)) {
    echo "<script>alert('標題不能為空'); window.history.back();</script>";
    exit;
}

$category = $_POST["category"];
// if (empty($category)) {
//     echo "<script>alert('類別不能為空'); window.history.back();</script>";
//     exit;
// }
$content = $_POST["content"];
$now = date('Y-m-d H:i:s');

$action = isset($_POST["action"]) ? $_POST["action"] : 'draft';
if ($action === 'draft') {
    $state = "draft";
    $sql = "INSERT INTO writing(user_id, title, category, content, created_at, state, valid)
VALUES ('1','$title','$category','$content', '$now','$state','1')";
} elseif ($action === 'scheduled') {
    $state = "scheduled";
    $posted_at = $_POST["posted_at"];
    $sql = "INSERT INTO writing(user_id, title, category, content, created_at, posted_at, state, valid)
VALUES ('1','$title','$category','$content', '$now','$posted_at','$state','1')";
} elseif ($action === 'visible') {
    $state = "visible";
    $posted_at = $now;
    $sql = "INSERT INTO writing(user_id, title, category, content, created_at, posted_at, state, valid)
VALUES ('1','$title','$category','$content', '$now','$posted_at','$state','1')";
} else {
    echo "無效的動作";
    exit;
}


// $sql = "INSERT INTO writing(user_id, title, category, content, created_at, posted_at, state)
// VALUES ('1','$title','$category','$content', '$now','$posted_at','$state')";


// echo $sql;
if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
    // echo "新資料輸入成功, id 為$last_id";
    echo "<script>alert('新增文章成功'); window.history.go(-2)</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();

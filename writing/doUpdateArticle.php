<?php
require_once("../db_connect.php");
if (!isset($_POST["id"])) {
    echo "請循正常管道進入此頁";
    exit;
}
$id = $_POST["id"];
$title = $_POST["title"];
$category = $_POST["category"];
$content = $_POST["content"];
$now = date('Y-m-d H:i:s');

$action = isset($_POST["action"]) ? $_POST["action"] : 'draft';
if ($action === 'save') {
    $state = "draft";
    $sql = "UPDATE writing SET title='$title',category='$category',content='$content', updated_at='$now',state='$state' WHERE id=$id";
} elseif ($action === 'scheduled') {
    $state = "scheduled";
    $posted_at = $_POST["posted_at"];
    $sql = "UPDATE writing SET title='$title',category='$category',content='$content', updated_at='$now',posted_at='$posted_at',state='$state' WHERE id=$id";
} elseif ($action === 'visible') {
    $state = "visible";
    $posted_at = $now;
    $sql = "UPDATE writing SET title='$title',category='$category',content='$content', updated_at='$now',posted_at='$posted_at',state='$state' WHERE id=$id";
} else {
    echo "無效的動作";
    exit;
}

// $sql = "UPDATE writing SET title='$title',category='$category',content='$content', updated_at='$now' WHERE id=$id";
if ($conn->query($sql) === TRUE) {
    echo "<script>alert('更新文章成功'); window.history.go(-2)</script>";
} else {
    echo "更新資料錯誤: " . $conn->error;
}

$conn->close();

<?php
require_once("../db_connect.php");

// 以下這段感覺是要確認是不是管理員的身分，所以應該不是coupon_code
// if (!isset($_POST["id"])) {
//     echo "請循正常管道進入此頁";
//     exit;
// }

$code = $_POST["course_code"];
if (empty($code)) {
    echo "課程代碼不能為空";
} else {
    //檢查序號是否重複，要進入DB檢查
    $sqlCheck = "SELECT * FROM course WHERE course_code ='$code'";
    $result = $conn->query($sqlCheck);
    $codeCount = $result->num_rows;
    if ($codeCount > 0) {
        $error = "該課程代碼已存在";
    }
}

if (!empty($error)) {
    echo $error;
    exit;
}


$name = $_POST["course_name"];
if (empty($name)) {
    echo "課程名稱不得為空";
    exit;
}

$type = $_POST["course_type"];
if (empty($type)) {
    echo "上課項目未選擇";
    exit;
}

$level = $_POST["course_level"];
if (empty($level)) {
    echo "上課級別未選擇";
    exit;
}

$teacher = $_POST["course_instructor"];
if (empty($teacher)) {
    echo "授課教師不得為空";
    exit;
}

$weekday = $_POST["course_weekday"];
if (empty($weekday)) {
    echo "上課時間的星期未選擇";
    exit;
}

$startTime = $_POST["course_start_time"];
if (empty($startTime)) {
    echo "上課開始時間不得為空";
    exit;
}

$endTime = $_POST["course_end_time"];
if (empty($endTime)) {
    echo "上課結束時間不得為空";
    exit;
}

$studentLimit = $_POST["course_student_limit"];
if (empty($studentLimit)) {
    echo "課程名額不得為空";
    exit;
}

$price = $_POST["course_price"];
if (empty($price)) {
    echo "課程價錢不得為空";
    exit;
}

$registrationStart = $_POST["course_registration_start"];
if (empty($registrationStart)) {
    echo "報名開始時間不得為空";
    exit;
}

$registrationEnd = $_POST["course_registration_end"];
if (empty($registrationEnd)) {
    echo "報名截止時間不得為空";
    exit;
}

$startDate = $_POST["course_start_date"];
if (empty($startDate)) {
    echo "開課日期不得為空";
    exit;
}



$statement = $_POST["course_statement"];
$classroom = $_POST["course_classroom"];
// $publishTime = $_POST["course_publish_time"];
// $removalTime = $_POST["course_removal_time"];
$today = date('Y-m-d H:i:s');



$sql = "INSERT INTO course (course_code, course_name, course_statement, course_instrument_type, course_level, course_instructor, course_student_limit, 	course_price, course_start_date, course_weekday, course_start_time, course_end_time, course_classroom, course_registration_start, course_registration_end, course_valid, course_created_at)
	VALUES ('$code','$name','$statement', '$type', '$level', '$teacher', '$studentLimit', '$price', '$startDate', '$weekday', '$startTime', '$endTime', '$classroom', '$registrationStart', '$registrationEnd',1,'$today')";

// echo $sql;





if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
    echo "新資料輸入成功， id 為 $last_id";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    exit;
};

//回課程列表
header("location:course-list.php");
$conn->close();

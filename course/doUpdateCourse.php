<?php
require_once("../db_connect.php");

// 以下這段感覺是要確認是不是管理員的身分，所以應該不是coupon_code
if (!isset($_POST["id"])) {
    echo "請循正常管道進入此頁";
    exit;
}

// 教室判斷
// $sqlcheck= "SELECT COUNT(*) as conflict_count
// FROM course c1
// WHERE EXISTS (
//     SELECT 1
//     FROM course c2
//     WHERE c1.course_id <> c2.course_id
//     AND c1.course_classroom = c2.course_classroom
//     AND c1.course_weekday = c2.course_weekday
//     AND (
//         (c1.course_start_time BETWEEN c2.course_start_time AND c2.course_end_time)
//         OR (c1.course_end_time BETWEEN c2.course_start_time AND c2.course_end_time)
//         OR (c2.course_start_time BETWEEN c1.course_start_time AND c1.course_end_time)
//     )
// )
// ";


// 執行查詢
// $stmt = $conn->prepare($sqlcheck);
// $stmt->execute(['classroom' => $classroom]);
// $result = $stmt->fetch_all(MYSQLI_ASSOC);

// // 判斷是否有衝突
// if ($result['conflict_count'] > 0) {
//     echo "教室有衝堂，請選擇其他教室";
//     exit;
// }

// $type=$_POST["course_type"];

$id=$_POST["id"];
$name=$_POST["course_name"];
$classroom=$_POST["course_classroom"];
$statement=$_POST["course_statement"];
$type=$_POST["course_type"];
$instructor=$_POST["course_instructor"];
$weekday=$_POST["course_weekday"];
$startTime=$_POST["course_start_time"];
$endTime=$_POST["course_end_time"];
$studentLimit=$_POST["course_student_limit"];
$price=$_POST["course_price"];

$registration_start_input_date=$_POST["course_registration_start"];
$registrationStart = date('Y-m-d', strtotime($registration_start_input_date)); 

$registration_end_input_date=$_POST["course_registration_end"];
$registrationEnd = date('Y-m-d', strtotime($registration_end_input_date)); 

// $startDate=$_POST["course_start_date"];
$course_start_input_date=$_POST["course_start_date"];
$startDate = date('Y-m-d', strtotime($course_start_input_date)); 

// $publishTime=$_POST["course_publish_time"];
// $removalTime=$_POST["course_removal_time"];
$today = date('Y-m-d H:i:s');
// $today=$_POST["course_created_at"];
// $image=$_POST["image"];



$sql="UPDATE course SET 
course_name='$name', 
course_statement='$statement',
course_instructor='$instructor', 
course_weekday='$weekday', 
course_start_time='$startTime', 
course_instrument_type='$type',
course_end_time='$endTime', 
course_student_limit='$studentLimit', 
course_price='$price', 
course_registration_start='$registrationStart', 
course_registration_end='$registrationEnd', 
course_start_date='$startDate', 
course_created_at='$today' 
WHERE course_id= $id";


// 照片
// $courseImages="UPDATE course_images SET file_name='$image', file_path='$' WHERE id=$id";

// echo $sql;

// $id = $conn->real_escape_string($_GET['id']);
// $newType = $conn->real_escape_string($_POST['course_type']); // 假设你从 POST 请求中获取了新的课程类型

// $sql = "UPDATE course SET course_type = '$newType' WHERE id = $id";
echo $sql;

if ($conn->query($sql) === TRUE) {
    header("Location: course-edit.php?id=$id&success=1");
} else {
    echo "更新資料錯誤: " . $conn->error;
}

$conn->close();
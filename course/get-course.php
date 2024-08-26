<?php
require_once("../db_connect.php");

$sql = "SELECT * FROM course 
LEFT JOIN course_instrument_type ON  course_instrument_type.type_id=course.course_instrument_type
LEFT JOIN course_level ON  course_level.level_id=course.course_level
LEFT JOIN course_classroom ON  course_classroom.classroom_id=course.course_classroom
LEFT JOIN course_weekday ON  course_weekday.weekday_id=course.course_weekday
LEFT JOIN course_images ON course_images.id = course.course_id"; 

$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);

// var_dump($rows);

$sqlType = "SELECT * FROM course_instrument_type ";
$resultType = $conn->query($sqlType);
$typeRows = $resultType->fetch_all(MYSQLI_ASSOC);
// var_dump($typeRows);
$sqlLevel = "SELECT * FROM course_level ";
$resultLevel = $conn->query($sqlLevel);
$levelRows = $resultLevel->fetch_all(MYSQLI_ASSOC);

$sqlClassroom = "SELECT * FROM course_classroom ";
$resultClassroom = $conn->query($sqlClassroom);
$classroomRows = $resultClassroom->fetch_all(MYSQLI_ASSOC);

$sqlWeekday = "SELECT * FROM course_weekday";
$resultWeekday = $conn->query($sqlWeekday);
$weekdayRows = $resultWeekday->fetch_all(MYSQLI_ASSOC);

$sqlImg = "SELECT * FROM course_images";
$resultImg = $conn->query($sqlImg);
$imgRows = $resultImg->fetch_all(MYSQLI_ASSOC);

?>
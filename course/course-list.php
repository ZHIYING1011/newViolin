<?php
include "../vars.php";
$cateNum = 3;
$pageTitle = "{$cate_ary[$cateNum]}";
include "../template_top.php";
include "../template_nav.php";

require_once("../db_connect.php");

$whereClause = "course_valid=1"; // Initialize with a base condition

// Status filter
if (isset($_GET["status"]) && $_GET["status"] !== "") {
    $now = date("Y-m-d H:i:s");
    $status = $_GET["status"];
    switch ($status) {
        case "NotStarted":
            $whereClause .= " AND course_registration_start > '$now'";
            break;
        case "RegistrationOpen":
            $whereClause .= " AND course_registration_start <= '$now' AND course_registration_end >= '$now' AND course_student_limit > course_registered_students";
            break;
        case "RegistrationFull":
            $whereClause .= " AND course_registration_start <= '$now' AND course_registration_end >= '$now' AND course_student_limit <= course_registered_students";
            break;
        case "RegistrationClosed":
            $whereClause .= " AND course_registration_end < '$now'";
            break;
        case 'InProgress':
            $whereClause .= " AND course_start_date <= '$now' AND course_removal_time >= '$now'";
            break;
        case 'Discontinued':
            $whereClause .= " AND course_removal_time <= '$now'";
            break;
    }
}

// Additional filters
$filters = [
    'code' => 'course_code LIKE',
    'teacher' => 'course_instructor LIKE',
    'type' => 'course.course_instrument_type =',
    'level' => 'course.course_level =',
    'classroom' => 'course.course_classroom ='
];

foreach ($filters as $param => $condition) {
    if (isset($_GET[$param]) && $_GET[$param] !== "") {
        $value = $conn->real_escape_string($_GET[$param]);
        if ($param === 'code' || $param === 'teacher') {
            $whereClause .= " AND $condition '%$value%'";
        } else {
            $whereClause .= " AND $condition '$value'";
        }
    }
}

// Registration date filters
if (isset($_GET["regDateFrom"]) && $_GET["regDateFrom"] !== "" && isset($_GET["regDateTo"]) && $_GET["regDateTo"] !== "") {
    $regDateFrom = $conn->real_escape_string($_GET["regDateFrom"]);
    $regDateTo = $conn->real_escape_string($_GET["regDateTo"]);
    $whereClause .= " AND course_registration_start >= '$regDateFrom' AND course_registration_end <= '$regDateTo'";
}

// Sorting
$orderClause = "ORDER BY ";
$orderbyMap = [
    1 => "course_created_at DESC",
    2 => "course_registration_start DESC",
    3 => "course_registration_start ASC",
    4 => "course_start_date DESC",
    5 => "course_start_date ASC"
];

if (isset($_GET["orderby"]) && array_key_exists($_GET["orderby"], $orderbyMap)) {
    $orderClause .= $orderbyMap[$_GET["orderby"]];
} else {
    $orderClause .= "course_created_at DESC";
}

// Pagination
$page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
$perPage = isset($_GET["perPage"]) ? intval($_GET["perPage"]) : 5;
$startItem = ($page - 1) * $perPage;

// Total records count
$sqlTotal = "SELECT COUNT(*) as total FROM course
LEFT JOIN course_instrument_type ON course_instrument_type.type_id=course.course_instrument_type
LEFT JOIN course_level ON course_level.level_id=course.course_level
LEFT JOIN course_classroom ON course_classroom.classroom_id=course.course_classroom
LEFT JOIN course_weekday ON course_weekday.weekday_id=course.course_weekday 
WHERE $whereClause";

$resultTotal = $conn->query($sqlTotal);
$totalResult = $resultTotal->fetch_assoc()['total'];
$totalPage = ceil($totalResult / $perPage);

// Main query
$sql = "SELECT * FROM course
LEFT JOIN course_instrument_type ON course_instrument_type.type_id=course.course_instrument_type
LEFT JOIN course_level ON course_level.level_id=course.course_level
LEFT JOIN course_classroom ON course_classroom.classroom_id=course.course_classroom
LEFT JOIN course_weekday ON course_weekday.weekday_id=course.course_weekday
LEFT JOIN course_images ON course_images.id = course.course_id 
WHERE $whereClause
$orderClause
LIMIT $startItem, $perPage";

$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);

// Fetch filter options
$sqlType = "SELECT * FROM course_instrument_type";
$resultType = $conn->query($sqlType);
$typeRows = $resultType->fetch_all(MYSQLI_ASSOC);

$sqlLevel = "SELECT * FROM course_level";
$resultLevel = $conn->query($sqlLevel);
$levelRows = $resultLevel->fetch_all(MYSQLI_ASSOC);

$sqlClassroom = "SELECT * FROM course_classroom";
$resultClassroom = $conn->query($sqlClassroom);
$classroomRows = $resultClassroom->fetch_all(MYSQLI_ASSOC);

$sqlWeekday = "SELECT * FROM course_weekday";
$resultWeekday = $conn->query($sqlWeekday);
$WeekdayRows = $resultWeekday->fetch_all(MYSQLI_ASSOC);



?>

<!doctype html>
<html lang="en">
<head>
    <title>課程列表</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <?php include("../css.php") ?>
    <link rel="stylesheet" href="style.css">
    <?php include("courseStyle.php") ?>
</head>
<body>
<main class="main-content pb-3">
    <div class="pt-3">
        <div class="p-3 bg-white shadow rounded-2 mb-4 border">
        <div class="py-2">
          <h4>課程列表</h4>
                <form method="GET" action="course-list.php">
                    
                    <div class="row g-2">
                        <div class="col-3 form-floating">
                            <input type="text" class="form-control" id="code" name="code" value="<?= isset($_GET["code"]) ? htmlspecialchars($_GET["code"]) : "" ?>">
                            <label for="code">課程代碼</label>
                        </div>
                        <div class="col-3 form-floating">
                            <input type="text" class="form-control" id="teacher" name="teacher" value="<?= isset($_GET["teacher"]) ? htmlspecialchars($_GET["teacher"]) : "" ?>">
                            <label for="teacher">授課教師</label>
                        </div>
                        <div class="col-3 form-floating">
                            <input type="date" class="form-control" id="regDateFrom" name="regDateFrom" value="<?= isset($_GET["regDateFrom"]) ? $_GET["regDateFrom"] : "" ?>">
                            <label for="regDateFrom">報名開始日期</label>
                        </div>
                        <div class="col-3 form-floating ">
                            <input type="date" class="form-control" id="regDateTo" name="regDateTo" value="<?= isset($_GET["regDateTo"]) ? $_GET["regDateTo"] : "" ?>">
                            <label for="regDateTo">報名結束日期</label>
                        </div>                        
                    </div>

                    <div class="row g-2 pt-3 align-items-center">
                        <div class="col-3 form-floating">
                            <select class="form-select" name="type">
                                <option value="">所有課程類型</option>
                                <?php foreach ($typeRows as $type) : ?>
                                    <option value="<?= $type["type_id"] ?>" <?= isset($_GET["type"]) && $_GET["type"] == $type["type_id"] ? "selected" : "" ?>><?= htmlspecialchars($type["type_name"]) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="course_state">課程狀態</label>
                        </div>
                        <div class="col-3 form-floating">
                            <select class="form-select" name="level">
                                <option value="">所有課程級別</option>
                                <?php foreach ($levelRows as $level) : ?>
                                    <option value="<?= $level["level_id"] ?>" <?= isset($_GET["level"]) && $_GET["level"] == $level["level_id"] ? "selected" : "" ?>><?= htmlspecialchars($level["level_name"]) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="course_state">課程級別</label>
                        </div>
                        <div class="col-3 form-floating">
                            <select class="form-select" name="status">
                                <option value="">所有課程狀態</option>
                                <option value="NotStarted" <?= isset($_GET["status"]) && $_GET["status"] == "NotStarted" ? "selected" : "" ?>>尚未開始</option>
                                <option value="RegistrationOpen" <?= isset($_GET["status"]) && $_GET["status"] == "RegistrationOpen" ? "selected" : "" ?>>報名中</option>
                                <option value="RegistrationFull" <?= isset($_GET["status"]) && $_GET["status"] == "RegistrationFull" ? "selected" : "" ?>>報名已滿</option>
                                <option value="RegistrationClosed" <?= isset($_GET["status"]) && $_GET["status"] == "RegistrationClosed" ? "selected" : "" ?>>報名已截止</option>
                                <option value="InProgress" <?= isset($_GET["status"]) && $_GET["status"] == "InProgress" ? "selected" : "" ?>>進行中</option>
                                <option value="Discontinued" <?= isset($_GET["status"]) && $_GET["status"] == "Discontinued" ? "selected" : "" ?>>已結束</option>
                            </select>
                            <label for="course_state">課程狀態</label>
                        </div>  
                        <div class="col-3">
                            <button type="submit" class="btn btn-primary btn-lg" >
                            <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                            <a href="course-list.php" class="btn btn-dark btn-lg" >
                            <i class="fa-solid fa-xmark"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- 排序 -->
            <!-- <div class="py-2">
                <form method="GET" action="course-list.php">
                    <div class="d-flex flex-wrap gap-2">
                        <select class="form-select" name="orderby">
                            <option value="">排序依據</option>
                            <option value="1" <?= isset($_GET["orderby"]) && $_GET["orderby"] == "1" ? "selected" : "" ?>>建立日期由新到舊</option>
                            <option value="2" <?= isset($_GET["orderby"]) && $_GET["orderby"] == "2" ? "selected" : "" ?>>報名開始由新到舊</option>
                            <option value="3" <?= isset($_GET["orderby"]) && $_GET["orderby"] == "3" ? "selected" : "" ?>>報名開始由舊到新</option>
                            <option value="4" <?= isset($_GET["orderby"]) && $_GET["orderby"] == "4" ? "selected" : "" ?>>課程開始由新到舊</option>
                            <option value="5" <?= isset($_GET["orderby"]) && $_GET["orderby"] == "5" ? "selected" : "" ?>>課程開始由舊到新</option>
                        </select>
                        <select class="form-select" name="perPage">
                            <option value="5" <?= isset($_GET["perPage"]) && $_GET["perPage"] == "5" ? "selected" : "" ?>>每頁 5 筆</option>
                            <option value="10" <?= isset($_GET["perPage"]) && $_GET["perPage"] == "10" ? "selected" : "" ?>>每頁 10 筆</option>
                            <option value="20" <?= isset($_GET["perPage"]) && $_GET["perPage"] == "20" ? "selected" : "" ?>>每頁 20 筆</option>
                        </select>
                        <input type="hidden" name="page" value="1">
                        <button class="btn btn-primary">更新</button>
                    </div>
                </form>
            </div> -->
            <div class="bg-white shadow rounded-2 border">
                <div class="table-title mb-3 d-flex justify-content-between align-items-center p-2 rounded-top">
                    <h6 class="m-0 text-primary ms-2">共有 <?= $totalResult ?> 堂課程</h6>
                    <a class="btn btn-primary me-2" href="create-course.php">新增</a>
                </div>

            <?php if(count($rows) > 0): ?>
            <div class="p-3">
                <table class="table table table-bordered p-3">
                    <thead>
                        <tr>
                            <th>課程代碼</th>
                            <th>授課項目</th>
                            <th>授課級別</th>
                            <th>授課教師</th>
                            <th>課程價格(堂)</th>
                            <th>報名人數</th>
                            <th>上課時間</th>
                            <th>課堂教室</th>
                            <th>開課時間</th>
                            <th>報名截止日</th>
                            <th>課程狀態</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row) : ?>
                            <tr>
                                <td><?= htmlspecialchars($row["course_code"]) ?></td>
                                <td><?= htmlspecialchars($row["type_name"]) ?></td>
                                <td><?= htmlspecialchars($row["level_name"]) ?></td>
                                <td><?= htmlspecialchars($row["course_instructor"]) ?></td>
                                <td class="text-end"><?= number_format($row["course_price"]) ?></td>
                                <td><?= htmlspecialchars($row["course_registered_students"]) ?></td>
                                <td>每周<?= $row["weekday_name"] ?><br><?= substr($row["course_start_time"], 0, 5) ?>~<?= substr($row["course_end_time"], 0, 5) ?></td>
                                <td><?= htmlspecialchars($row["classroom_code"]) ?></td>
                                <td><?= htmlspecialchars($row["course_start_date"]) ?></td>
                                <td><?= htmlspecialchars($row["course_registration_end"]) ?></td>
                                <td><?php 
                                    if ($row["course_registration_start"] > date("Y-m-d")) {
                                            echo "報名<br>尚未開始";
                                        } elseif ($row["course_registration_start"] <= date("Y-m-d") && $row["course_registration_end"] >= date("Y-m-d") && $row["course_student_limit"] >$row["course_registered_students"]){
                                            echo "報名<br>開放中";
                                        } elseif ($row["course_registration_start"] <= date("Y-m-d") && $row["course_registration_end"] >= date("Y-m-d") && $row["course_student_limit"] <= $row["course_registered_students"]) {
                                            echo "報名<br>已額滿";
                                        } elseif ($row["course_registration_end"] < date("Y-m-d")) {
                                            echo "報名<br>已截止";
                                        } elseif ($row["course_start_date"] <= date("Y-m-d") && $row["course_removal_time"] > date("Y-m-d") ) {
                                            echo "課程<br>進行中";
                                        } elseif($row["course_removal_time"] < date("Y-m-d")) {
                                            echo "課程<br>已下架";
                                        }
                                        ?></td>
                                <td>
                                    <div class="d-flex">
                                        
                                    <button onclick="openPopup(<?= $row['course_id'] ?>)" type="button" class="ViewCourseBtn btn" title="檢視"><i class="fa-solid fa-eye"></i></button>
                                    
                                    <a href="course-edit.php?id=<?=$row["course_id"]?>"><button class="editCourseBtn btn" data-id="<?= $row["course_id"] ?>" title="編輯"><i class="fa-solid fa-pen-to-square"></i></button></a>   
                                                                
                                    <button  type="button" data-delete-id="<?= $row["course_id"] ?>" data-course-name="<?= $row["course_name"] ?>" class="deleteCourseBtn btn" data-bs-toggle="modal" data-bs-target="#deleteModal" title="刪除"><i class=" fa-solid fa-trash"></i></button>
                                    
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

                
                 
    <div class="total-page">
    <p class="text-center text-secondary">
        第 <?= $page ?> 頁 / 共 <?= $totalPage ?> 頁
    </p>
    </div>
<!-- 頁碼 -->
<div class="mb-3">
    <nav aria-label="Page-navigation" class="d-flex justify-content-center">
        <div class="btn-group">
            <a type="button" class="btn btn-outline-secondary <?php if ($page < 2) echo "disabled" ?>" href="course-list.php?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">
                <i class="fa-solid fa-caret-left"></i>
            </a>

            <?php for ($i = 1; $i <= $totalPage; $i++) : ?>
                <a class="btn btn-outline-secondary <?= ($i == $page) ? "active" : "" ?>" href="course-list.php?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
            <?php endfor; ?>

            <a type="button" class="btn btn-outline-secondary <?php if ($page >= $totalPage) echo "disabled" ?>" href="course-list.php?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">
                <i class="fa-solid fa-caret-right"></i>
            </a>
        </div>
    </nav>
</div>

<?php else: ?>
    <h2 class="text-center mt-5">查無資料，請重新設定篩選條件</h2>
<?php endif; ?>

    </div>
    </div>
        </div>
    </main>

    <!-- 檢視課程popup --> 
    <?php foreach ($rows as $row): ?>
    <div id="overlay<?= $row['course_id'] ?>" class="overlay"></div>
    <div id="coursePopup<?= $row['course_id'] ?>" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closePopup(<?= $row['course_id'] ?>)">&times;</span>
            <h2 class="courseTitle text-center"><?= $row["course_name"] ?></h2>
            <div class="imageBox">        
                <img id="courseImage" src="./image/<?= $row["file_name"] ?>" alt="課程圖片">
            </div> 
            <div class="course-info">               
                <p><strong>授課教師：</strong><?= $row["course_instructor"] ?></p>
                <p><strong>授課項目：</strong><?= $row["type_name"] ?></p>
                <p><strong>授課級別：</strong><?= $row["level_name"] ?></p>
                <p><strong>課堂價錢：</strong>$<span class="text-end"><?= number_format($row["course_price"]) ?></span></p>
                <p><strong>上課時間：</strong>每周<?= $row["weekday_name"] ?> <?= substr($row["course_start_time"], 0, 5) ?>~<?= substr($row["course_end_time"], 0, 5) ?></p>
                <p><strong>上課教室：</strong><?= $row["classroom_code"] ?></p>
                <p><strong>課程說明：</strong><?= $row["course_statement"] ?></p>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <script>
    function openPopup(courseId) {
    document.getElementById("overlay" + courseId).style.display = "block";
    document.getElementById("coursePopup" + courseId).style.display = "block";
}

function closePopup(courseId) {
    document.getElementById("overlay" + courseId).style.display = "none";
    document.getElementById("coursePopup" + courseId).style.display = "none";
}

// 點擊彈出視窗外部時關閉視窗
window.onclick = function(event) {
    if (event.target.classList.contains('overlay')) {
        event.target.style.display = 'none';
        event.target.nextElementSibling.style.display = 'none';
    }
}
    

        // function updateCourseInfo(courseData) {
        //     document.getElementById("courseTitle").textContent = courseData.title;
        //     document.getElementById("courseImage").src = courseData.image;
        //     document.getElementById("courseDescription").textContent = courseData.description;
        //     document.getElementById("courseTeacher").textContent = courseData.teacher;
        //     document.getElementById("courseItems").textContent = courseData.items;
        //     document.getElementById("courseLevel").textContent = courseData.level;
        // }
        
    </script>



    <!-- 檢視課程modal --> 
    <!-- <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Centered Popup</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        This is a centered popup using Bootstrap Modal.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div> -->

 

    <!-- 刪除課程跳出的modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">確認刪除</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="deleteModalCourseName"></p>
                    <p id="deleteConfirmationText">確定要刪除此商品嗎？</p> <!-- 這行會被 JavaScript 動態更新 -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <a id="confirmDeleteBtn" class="btn btn-danger" href="#">確認刪除</a>
                </div>
            </div>
        </div>
    </div>
    <?php include("../js.php") ?>
    
    <script>
        // document.addEventListener('DOMContentLoaded', function() {
        //     var productModal = document.getElementById('productModal');
        //     productModal.addEventListener('show.bs.modal', function(event) {
        //         var button = event.relatedTarget;
        //         var productId = button.getAttribute('data-product-id');
        //         var brandName = button.getAttribute('data-brand-name');
        //         var productName = button.getAttribute('data-product-name');
        //         var description = button.getAttribute('data-description');

        //         document.getElementById('modalProductId').textContent = "商品編碼:  " + productId;
        //         document.getElementById('modalProductBrand').textContent = "品牌名稱: " + brandName;
        //         document.getElementById('modalProductName').textContent = "商品名稱: " + productName;
        //         document.getElementById('modalDescription').textContent = description;

    //     var deleteModal = document.getElementById('deleteModal');
    // deleteModal.addEventListener('show.bs.modal', function(event) {
    //     var button = event.relatedTarget;
    //     var courseId = button.getAttribute('data-delete-id');
    //     var courseName = button.getAttribute('data-course-name');

    //             // 更新確認訊息
    //             var confirmationMessage = "確定要刪除此商品嗎？ 即將刪除的商品: " + brandName + " - " + productName;
    //             document.getElementById('deleteConfirmationText').innerHTML = confirmationMessage;

    //             // 設置確認刪除按鈕的連結
    //             var confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    //             confirmDeleteBtn.href = "doDeleteProduct.php?id=" + courseId;
    //         });

            // 刪除課程
            var deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var courseId = button.getAttribute('data-delete-id');
                var courseName = button.getAttribute('data-course-name');


                // 更新確認訊息
                var confirmationMessage = "確定要刪除此課程嗎？<br>即將刪除的課程：" + "（" + courseName + "）" ;
                document.getElementById('deleteConfirmationText').innerHTML = confirmationMessage;

                // 設置確認刪除按鈕的連結
                var confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
                confirmDeleteBtn.href = "doDeleteCourse.php?id=" + courseId;
            });

    </script>
</body>

</html>
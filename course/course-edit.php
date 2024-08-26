<?php
include "../vars.php";
$cateNum = 3;
$pageTitle = "新增課程";
// include "../template_nav.php";
// include "../template_top.php";
// include "../template_btm.php";
include "get-course.php";

if (!isset($_GET["id"])) {
    echo "進入編輯發生錯誤";
    exit;
}

$id = $_GET["id"];

require_once("../db_connect.php");


$sql = "SELECT * FROM course WHERE course_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$courseCount = $result->num_rows;
$row = $result->fetch_assoc();

$imgsql = "SELECT course.*, course_images.file_name 
FROM course
LEFT JOIN course_images ON course.course_id = course_images.id";

$stmtImg = $conn->query($imgsql);
// $stmtImg->bind_param("i", $course_id);
// $stmtImg->execute();
// $imgResult = $stmtImg->get_result();
$imgResult = $stmtImg->fetch_assoc();

// 將資料庫的欄位更新成相對應的日期格式
$course_registration_start = date('Y-m-d', strtotime($row['course_registration_start']));
$course_registration_end = date('Y-m-d', strtotime($row['course_registration_end']));
$course_start_date = date('Y-m-d', strtotime($row['course_start_date']));


// 確認課程是否存在
if ($courseCount > 0) {
    $title = $row["course_name"];
} else {
    $title = "該課程不存在";
}


$modalMessage = isset($_GET["success"]) ? "課程更新成功！" : "";

?>



<!doctype html>
<html lang="en">

<head>
    <title><?=$title?></title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="../style.css">
    <?php include("../css.php") ?>
    <?php include("courseStyle.php") ?>
    <style>
        
        .savebtn{
            margin-left:-100px;

            .cancelbtn{
                background: #aaa;
                border-color: #aaa;
            }
            .btn-dark{
                background: #aaa;
                border-color: #aaa;
            }
        }

    </style>
</head>

<body>
    <main class="main-content pb-3">
        <div class="pt-3">
            <div class="p-3 bg-white shadow rounded-2 mb-4 border">
                <div class="row g-2 align-items-center mb-2">
                    <div class="col-auto">
                        <a class="btn btn-primary" href="course-list.php"><i class="fa-solid fa-circle-chevron-left"></i></a>
                    </div>
                    <div class="col">
                        <h4 class="m-0">編輯課程</h4>
                    </div>
                </div>
                <div class="py-2">
                <?php if($courseCount>0):?>
                    <form action="doUpdateCourse.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($row["course_id"]) ?>">
                        <div class="row g-2">
                            <!-- 左邊區塊 -->
                            <div class="col-1"></div>
                            <div class="col-4">
                                <div class="row g-2">
                                    <!--課程代碼-->
                                    <div class="col-6 form-floating pb-3">
                                        <input class="form-control" id="course_code" placeholder="course_code" name="course_code" value="<?= $row["course_code"] ?>" required>
                                        <label for="course_code"><span class="text-danger">*</span>課程代碼</label>
                                    </div>
                                    <!--上課教室-->
                                    <div class="col-6 form-floating pb-3">
                                        <select class="form-select" id="course_classroom" name="course_classroom">
                                            <option value="">請選擇</option>
                                            <?php foreach ($classroomRows as $classroom) : ?>
                                                <option value="<?= $classroom['classroom_id'] ?>" 
                                                <?= $row['course_classroom'] == $classroom['classroom_id'] ? 'selected' : '' ?>>
                                                <?= $classroom['classroom_code'] ?>
                                            </option>
                                            <?php endforeach ?>
                                        </select>
                                        <label for="course_classroom_code">上課教室</label>
                                    </div>
                                    <!--課程名稱-->
                                    <div class="col-12 form-floating pb-3">
                                        <input type="course_name" class="form-control" id="course_name" placeholder="course_name" name="course_name" value="<?= $row["course_name"] ?>" required>
                                        <label for="course_name"><span class="text-danger">*</span>課程名稱</label>
                                    </div>
                                    <!--課程說明-->
                                    <div class="col-12 form-floating pb-3">
                                        <input type="course_statement" class="form-control" id="course_statement" placeholder="course_statement" name="course_statement" value="<?=$row["course_statement"] ?>">
                                        <label for="course_statement">課程說明</label>
                                    </div>
                                    <!--課程類別-->
                                    <div class="col-12 course_type mb-3">
                                        <label for="course_type" class="form-label" required><span class="text-danger">*</span>課程類別</label>
                                        <div id="course_type" required>
                                            <?php foreach ($typeRows as $type) : ?>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="course_type" id="course_type_<?= $type["type_id"] ?>" value="<?= $type["type_id"] ?>" <?= $row['course_instrument_type'] == $type['type_id'] ? 'checked' : '' ?> required>
                                                    <label class="form-check-label" for="course_type_<?= $type["type_id"] ?>">
                                                        <?= $type["type_name"] ?>
                                                    </label>
                                                </div>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                    <!--課程級別-->
                                    <div class="col-12 course_type mb-3">
                                    <label for="course_level_name" class="form-label"required><span class="text-danger">*</span>課程級別</label>
                                    <div id="course_level" required>
                                        <?php foreach ($levelRows as $level) : ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="course_level" id="course_level_<?= $level["level_id"] ?>" value="<?= $level["level_id"] ?>" <?= $row['course_level'] == $level['level_id'] ? 'checked' : '' ?> required>
                                                <label class="form-check-label" for="course_type_<?= $level["level_id"] ?>">
                                                    <?= $level["level_name"] ?>
                                                </label>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                    </div>
                                    <!--授課教師-->
                                    <div class="col-12 form-floating pb-3">
                                        <input class="form-control" id="course_instructor" placeholder="course_instructor" name="course_instructor" value="<?=$row["course_instructor"] ?>" required>
                                        <label for="course_instructor"><span class="text-danger">*</span>授課教師</label>
                                    </div>
                                    <!--上課時間-->
                                    <h6>上課時間</h6>   
                                    <div class="col-4 form-floating pb-3">
                                        <select class="form-select" id="course_weekday" name="course_weekday" required>
                                            <option value="">請選擇</option>
                                            <?php foreach ($weekdayRows as $weekday) : ?>
                                                <option value="<?= $weekday["weekday_id"] ?>" 
                                                <?= $row['course_weekday'] == $weekday["weekday_id"] ? 'selected' : '' ?>>
                                                <?= $classroom['classroom_code'] ?>
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                        <label for="course_type_name" required><span class="text-danger">*</span>星期</label>
                                    </div>
                                    <div class="col-4 form-floating pb-3">
                                        <input class="form-control" id="course_start_time" placeholder="course_start_time" name="course_start_time" value="<?= substr($row["course_start_time"], 0, 5) ?>" required>
                                        <label for="ccourse_start_time" required><span class="text-danger">*</span>開始時間(00:00)</label>
                                    </div>
                                    <div class="col-4 form-floating pb-3">
                                        <input class="form-control" id="course_end_time" placeholder="course_end_time" name="course_end_time" value="<?= substr($row["course_end_time"], 0, 5) ?>" required>
                                        <label for="course_end_time" required><span class="text-danger">*</span>結束時間(00:00)</label>
                                    </div>
                                    <div class="col-6 form-floating pb-3">
                                        <input class="form-control" id="course_student_limit" placeholder="course_student_limit" name="course_student_limit" value="<?=$row["course_student_limit"] ?>" required>
                                        <label for="course_student_limit" required><span class="text-danger">*</span>課程名額</label>
                                    </div>
                                    <div class="col-6 form-floating pb-3">
                                        <input class="form-control" id="course_price" placeholder="course_price" name="course_price" value="<?=$row["course_price"] ?>" required>
                                        <label for="course_price" required><span class="text-danger">*</span>課程價錢</label>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-1"></div>
                            <!-- 右邊欄 -->
                            <div class="col-5">
                                <div class="row g-2">
                                    <!-- 上傳圖片 -->
                                    <div class="col-12 photo-box" >
                                    
                                        <div class="preview-container">
                                            <img id="preview" src="./image/<?= $imgResult["file_name"] ?>" alt="圖片預覽" style="display:block;">
                                            <p id="placeholder" style="display:none;">圖片預覽</p>
                                        </div>
                                        <input type="file" id="fileInput" accept="image/*" name="image">
                                        <button id="uploadBtn">上傳圖片</button>
                                        <!-- <input type="hidden" id="currentImageId" name="currentImageId" value="<?= $imgResult["id"] ?>"> -->
                                        <input type="hidden" id="currentFileName" name="currentFileName" value="<?= $imgResult["file_name"] ?>">
                                   
                                    </div>
                                <!-- <script src="script.js"></script> -->
                                    
                                    <div class="col-6 form-floating pb-3">
                                        <input type="date" class="form-control" name="course_registration_start" value="<?= ($course_registration_start && $course_registration_start != '0000-00-00') ? $course_registration_start : '' ?>" id="course_registration_start">
                                        <label for="course_registration_start" required><span class="text-danger">*</span>報名開始日期</label>
                                    </div>
                                    <div class="col-6 form-floating pb-3">
                                        <input type="date" class="form-control" name="course_registration_end" value="<?= $course_registration_end ?>" id="course_registration_end">
                                        <label for="course_registration_end" required><span class="text-danger">*</span>報名結束日期</label>
                                    </div>
                                    <div class="col-12 form-floating pb-3">
                                        <input type="date" class="form-control" name="course_start_date" value="<?= $course_start_date ?>" id="course_start_date">
                                        <label for="course_start_date" required><span class="text-danger">*</span>開課時間</label>
                                    </div>
                                    
                                    <!-- 上架設定 -->
                                    <!-- <div class="col-12 pb-3">
                                        <label for="course_setting" class="form-label" name="course_active"><span class="text-danger">*</span>上架設定</label>
                                        <div id="course_setting" required>        
                                            <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="active" id="active" value="active">
                                            <label class="form-check-label" for="active">立即上架</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inactive" id="inactive" value="inactive">
                                            <label class="form-check-label" for="inactive">暫不上架</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="removed" id="removed" value="removed">
                                            <label class="form-check-label" for="removed">立即下架</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 form-floating pb-3">
                                        <input type="date" class="form-control" name="course_publish_time" value="<?= $course_publish_time ?>" id="course_publish_time">
                                        <label for="course_publish_time">預排上架日期</label>
                                    </div>
                                    <div class="col-6 form-floating pb-3">
                                        <input type="date" class="form-control" name="course_removal_time" value="<?= $course_removal_time ?>" id="course_removal_time">
                                        <label for="course_removal_time">預排下架日期</label>
                                    </div> -->
                                </div>
                            </div>
                            <div class="col-1"></div>
                            <div class="savebtn d-flex justify-content-end pt-3">
                                <button class="cancelBtn btn btn-dark mx-3" title="取消" type="reset" >取消</button>
                                <button class="btn btn-primary" type="submit">儲存</button>
                            </div>
                        </div>
                </div>
            </div>
            </form>
            <?php else : ?>
                課程不存在
            <?php endif; ?>
        </div>
    </main>
    <!-- Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">提示</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $modalMessage ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="redirectToIndex">返回課程列表</button>
            </div>
        </div>
    </div>
</div>

<?php include("../js.php") ?>
<script>
    <?php if (!empty($modalMessage)) : ?>
        var successModal = new bootstrap.Modal(document.getElementById('successModal'), {});
        successModal.show();

        document.getElementById("redirectToIndex").addEventListener("click", function () {
            window.location.href = "course-list.php";
        });
    <?php endif; ?>
</script>


<script>
// 圖片上傳
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileInput');
    const preview = document.getElementById('preview');
    const placeholder = document.getElementById('placeholder');
    const uploadBtn = document.getElementById('uploadBtn');

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            }
            reader.readAsDataURL(file);
        }
    });

    uploadBtn.addEventListener('click', function() {
        const formData = new FormData();
        formData.append('image', fileInput.files[0]);
        formData.append('currentImageId', document.getElementById('currentImageId').value);
        formData.append('currentFileName', document.getElementById('currentFileName').value);

        fetch('upload.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('圖片上傳成功！');
                // 更新預覽圖片的src
                preview.src = `./image/${data.newFileName}`;
            } else {
                alert('圖片上傳失敗：' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('上傳時發生錯誤');
        });
    });
});
    
</script>

    
</body>

</html>
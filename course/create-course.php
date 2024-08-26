<?php
include "../vars.php";
$cateNum = 3;
$pageTitle = "新增課程";
include "../template_nav.php";
include "../template_top.php";
include "../template_btm.php";
include "get-course.php";

require_once("../db_connect.php");

?>

<!doctype html>
<html lang="en">

<head>
    <title>create user</title>
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
        }
    </style>
</head>

<body>
    <main class="main-content pb-3 px-5">
        <div class="pt-3">
            <div class="p-3 bg-white shadow rounded-2 mb-4 border">
                <div class="row g-2 align-items-center mb-2">
                    <div class="col-auto">
                        <a class="btn btn-primary" href="course-list.php"><i class="fa-solid fa-circle-left"></i></a>
                    </div>
                    <div class="col">
                        <h4 class="m-0">新增課程</h4>
                    </div>
                </div>
                <div class="py-2">
                    <form action="doCreateCourse.php" method="post">
                        <div class="row g-2">
                            <div class="col-1">                        
                            </div>
                            <div class="col-4">
                                <div class="row g-2">
                                    <div class="col-6 form-floating pb-3">
                                        <input class="form-control" id="course_code" placeholder="course_code" name="course_code" required>
                                        <label for="course_code"><span class="text-danger">*</span>課程代碼</label>
                                    </div>
                                    <div class="col-6 form-floating pb-3">
                                        <select class="form-select" id="course_classroom" name="course_classroom">
                                            <option value="">請選擇</option>
                                            <?php foreach ($classroomRows as $classroom) : ?>
                                                <option value="<?= $classroom["classroom_id"] ?>"><?= $classroom["classroom_code"] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <label for="course_classroom_code">上課教室</label>
                                    </div>
                                    <div class="col-12 form-floating pb-3">
                                        <input type="course_name" class="form-control" id="course_name" placeholder="course_name" name="course_name" required>
                                        <label for="course_name"><span class="text-danger">*</span>課程名稱</label>
                                    </div>
                                    <div class="col-12 form-floating pb-3">
                                        <input type="course_statement" class="form-control" id="course_statement" placeholder="course_statement" name="course_statement">
                                        <label for="course_statement">課程說明</label>
                                    </div>
                                    <div class="col-12 course_type mb-3">
                                        <label for="course_type" class="form-label" required><span class="text-danger">*</span>課程類別</label>
                                        <div id="course_type" required>
                                            <?php foreach ($typeRows as $type) : ?>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="course_type" id="course_type_<?= $type["type_id"] ?>" value="<?= $type["type_id"] ?>" required>
                                                    <label class="form-check-label" for="course_type_<?= $type["type_id"] ?>">
                                                        <?= $type["type_name"] ?>
                                                    </label>
                                                </div>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                    <div class="col-12 course_type mb-3">
                                    <label for="course_level_name" class="form-label"required><span class="text-danger">*</span>課程級別</label>
                                    <div id="course_level" required>
                                        <?php foreach ($levelRows as $level) : ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="course_level" id="course_level_<?= $level["level_id"] ?>" value="<?= $level["level_id"] ?>" required>
                                                <label class="form-check-label" for="course_type_<?= $level["level_id"] ?>">
                                                    <?= $level["level_name"] ?>
                                                </label>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                    </div>
                                    <div class="col-12 form-floating pb-3">
                                        <input class="form-control" id="course_instructor" placeholder="course_instructor" name="course_instructor" required>
                                        <label for="course_instructor"><span class="text-danger">*</span>授課教師</label>
                                    </div>
                                    <h6>上課時間</h6>   
                                    <div class="col-4 form-floating pb-3">
                                        <select class="form-select" id="course_weekday" name="course_weekday" required>
                                            <option value="">請選擇</option>
                                            <?php foreach ($weekdayRows as $weekday) : ?>
                                                <option value="<?= $weekday["weekday_id"] ?>"><?= $weekday["weekday_name"] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <label for="course_type_name" required><span class="text-danger">*</span>星期</label>
                                    </div>
                                    <div class="col-4 form-floating pb-3">
                                        <input class="form-control" id="course_start_time" placeholder="course_start_time" name="course_start_time" required>
                                        <label for="ccourse_start_time" required><span class="text-danger">*</span>開始時間</label>
                                    </div>
                                    <div class="col-4 form-floating pb-3">
                                        <input class="form-control" id="course_start_time" placeholder="course_end_time" name="course_end_time" required>
                                        <label for="course_end_time" required><span class="text-danger">*</span>結束時間</label>
                                    </div>
                                    <div class="col-6 form-floating pb-3">
                                        <input class="form-control" id="course_student_limit" placeholder="course_student_limit" name="course_student_limit" required>
                                        <label for="course_student_limit" required><span class="text-danger">*</span>課程名額</label>
                                    </div>
                                    <div class="col-6 form-floating pb-3">
                                        <input class="form-control" id="course_price" placeholder="course_price" name="course_price" required>
                                        <label for="course_price" required><span class="text-danger">*</span>課程價錢</label>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-1"></div>
                            <!-- 右邊欄 -->
                            <div class="col-5">
                                <div class="row g-2">
                                    <div class="col-12 photo-box" >
                                    <div class="preview-container">
                                        <img id="preview" src="#" alt="圖片預覽" style="display:none;">
                                        <p id="placeholder">圖片預覽</p>
                                    </div>
                                    <input type="file" id="fileInput" accept="image/*" name="image">
                                    <button id="uploadBtn">上傳圖片</button>
                                </div>
                                <!-- <script src="script.js"></script> -->
                                    
                                    <div class="col-6 form-floating pb-3">
                                        <input type="date" class="form-control" name="course_registration_start" value="<?= $course_registration_start ?>" id="course_registration_start">
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
                                        <label for="course_setting" class="form-label" name="course_setting"><span class="text-danger">*</span>上架設定</label>
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
                                <button class="cancelBtn btn btn-dark mx-3" title="清除" type="reset" >清除資料</button>
                                <button class="btn btn-primary" type="submit">新增課程</button>
                            </div>
                        </div>
                </div>
            </div>
            </form>
        </div>



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
                if (fileInput.files.length > 0) {
                    const formData = new FormData();
                    formData.append('image', fileInput.files[0]);

                    fetch('upload.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('圖片上傳成功！');
                        } else {
                            alert('圖片上傳失敗：' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('上傳過程中發生錯誤');
                    });
                } else {
                    alert('請先選擇一張圖片');
                }
            });
        });


            
           
        </script>
        </div>
    </main>

</body>

</html>
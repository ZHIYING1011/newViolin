<?php

// 整合頁面
include "../vars.php";
$cateNum = 1;
$pageTitle = "{$cate_ary[$cateNum]}";
include "../template_top.php";
include "../template_nav.php";

if (!isset($_GET["id"])) {
    echo "請正確帶入 get id 變數";
    header("location:users.php");
}

$id = $_GET["id"];

require_once("../db_connect.php");

$sql = "SELECT users.*, address_city.address_city_name, address_cityarea.address_cityarea_name, user_level.level_name
    FROM users
    LEFT JOIN address_city ON users.address_city_id = address_city.address_city_id
    LEFT JOIN address_cityarea ON users.address_cityarea_id = address_cityarea.address_cityarea_id
    LEFT JOIN user_level ON users.level_id = user_level.level_id
    WHERE users.id = $id";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
    exit("找不到此用戶");
}

$user = $result->fetch_assoc();
$userCount = $result->num_rows;

$title =  $user["user_name"];

// 抓取城市與區域資料
$sqlCity = "SELECT * FROM address_city";
$sqlCityArea = "SELECT * FROM address_cityarea";

$resultCity = $conn->query($sqlCity);
$resultCityArea = $conn->query($sqlCityArea);

$CityList = $resultCity->fetch_all(MYSQLI_ASSOC);
$CityAreaList = $resultCityArea->fetch_all(MYSQLI_ASSOC);

?>
<!doctype html>
<html lang="en">

<head>
    <title>會員管理/檢視</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <?php include("../css.php") ?>
    <?php include("../js.php") ?>

</head>

<body>
    <main class="main-content pb-3">
        <div class="container">

            <div class="d-flex">
                <a href="users.php" class="btn btn-dark">返回列表</a>
                <h1>會員管理/檢視</h1>
            </div>

            <?php
            $valid_class = '';
            $valid_content = '';
            switch ($user["valid"]) {
                case '0':
                    $valid_class = 'bg-danger';
                    $valid_content = '已停用';
                    break;
                case '1':
                    $valid_class = 'bg-success';
                    $valid_content = '啟用中';
                    break;
            }
            ?>
            <p class="h3"><?= $title ?><span class="ms-1 badge <?= $valid_class ?>"> <?= $valid_content ?></span></p>


            <?php if ($userCount > 0) : ?>
                <form action="doEditUser.php" method="post" class="searchbar row g-3">

                    <!-- 會員ID -->
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">

                    <!-- 會員名稱 -->
                    <div class="col-3 mb-3">
                        <label for="user_name" class="form-label">會員名稱</label>
                        <p class="form-label text-primary"><?= $user['user_name'] ?></p>
                    </div>

                    <!-- 性別 -->
                    <div class="col-3 mb-3">
                        <label for="gender" class="form-label">性別</label>
                        <?php
                        $gender = '';
                        switch ($user["gender"]) {
                            case '1':
                                $gender = '男';
                                break;
                            case '2':
                                $gender = '女';
                                break;
                            default:
                                $gender = '未填寫';
                        } ?>

                        <p class="form-label text-primary"><?= $gender ?></p>
                    </div>

                    <!-- 會員帳號 -->
                    <div class="col-3 mb-3">
                        <label for="account" class="form-label">會員帳號<span class="text-danger">*帳號註冊後無法修改！</span></label>
                        <p class="form-label text-primary"><?= $user['account'] ?></p>
                    </div>

                    <!-- 會員電話 -->
                    <div class="col-3 mb-3">
                        <label for="phone" class="form-label">會員電話</label>
                        <p class="form-label text-primary"><?= "0" . $user['phone'] ?></p>
                    </div>

                    <!-- 會員信箱 -->
                    <div class="col-3 mb-3">
                        <label for="email" class="form-label">會員信箱</label>
                        <p class="form-label text-primary"><?= $user['email'] ?></p>
                    </div>

                    <!-- 生日 -->
                    <div class="col-3 mb-3">
                        <label for="birthday" class="form-label">生日</label>
                        <p class="form-label text-primary"><?= $user['birthday'] ?></p>
                    </div>

                    <!-- 地址 -->
                    <div class="col-6 mb-3">
                        <label for="address" class="form-label">地址</label>
                        <p class="form-label text-primary"><?= $user['address_city_name'] . $user['address_cityarea_name'] . $user['address_street'] ?></p>

                    </div>

                    <!-- 創建時間 -->
                    <div class="col-3 mb-3">
                        <p class="form-label">創建時間</p>
                        <p class="form-label text-primary"><?= $user['create_date'] ?></p>
                    </div>

                    <!-- 更新時間 -->
                    <div class="col-3 mb-3">
                        <p class="form-label">更新時間</p>
                        <p class="form-label text-primary"><?= $user['update_time'] ?></p>
                    </div>

                    <!-- 帳號狀態 -->
                    <div class="col-3 mb-3">

                        <label for="valid" class="form-label">帳號狀態</label>
                        <?php
                        $valid = '';
                        switch ($user["valid"]) {
                            case '0':
                                $valid = '停用';
                                break;
                            case '1':
                                $valid = '啟用';
                                break;
                            default:
                                $valid = '未填寫';
                        } ?>
                        <p class="form-label text-primary"><?= $valid ?></p>
                    </div>

                    <!-- 按鈕 -->
                    <div class="col-12">
                        <a href="users.php" class="btn btn-dark">返回</a>
                    </div>
                </form>
            <?php else : ?>
                使用者不存在
            <?php endif; ?>
        </div>

        <script>
            document.getElementsByName('address_city_id')[0].addEventListener('change', function() {
                var cityId = this.value;

                // 使用 AJAX 請求對應城市區域資料
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'get_cityareas.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        document.getElementsByName('address_cityarea_id')[0].innerHTML = xhr.responseText;
                    }
                };
                xhr.send('city_id=' + cityId);
            });
        </script>

        <?php $conn->close(); ?>
    </main>
</body>

</html>
<?php

// 整合頁面
include "../vars.php";
$cateNum = 1;
$pageTitle = "{$cate_ary[$cateNum]}";
include "../template_top.php";
include "../template_nav.php";

require_once("../db_connect.php");

// 抓取城市與區域資料
$sqlCity = "SELECT * FROM address_city";
$sqlCityArea = "SELECT * FROM address_cityarea";

// 執行查詢
$resultCity = $conn->query($sqlCity);
$resultCityArea = $conn->query($sqlCityArea);

$CityList = $resultCity->fetch_all(MYSQLI_ASSOC);
$CityAreaList = $resultCityArea->fetch_all(MYSQLI_ASSOC);

?>
<!doctype html>
<html lang="en">

<head>
    <title>新增管理員</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <?php include("../css.php") ?>
    <?php include("../js.php") ?>

</head>

<body>
<main class="main-content pb-3">
    <div class="container">
        <div class="d-flex align-items-center">
            <h1>新增管理員</h1>
            <a href="users.php" class="btn btn-dark">返回列表</a>
        </div>

        <form action="doCreateAdmin.php" method="post" class="searchbar row g-3 justify-content-between">

            <!-- 名稱 -->
            <div class="col-3 mb-3">
                <label for="user_name" class="form-label">名稱<span class="text-danger">(必填)</span></label>
                <input type="search" class="form-control" name="user_name" id="user_name" placeholder="請輸入名稱" required>
            </div>

            <!-- 性別 -->
            <div class="col-3 mb-3">
                <label for="gender" class="form-label">性別</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="1">
                        <label class="form-check-label" for="inlineRadio1">男</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="0">
                        <label class="form-check-label" for="inlineRadio2">女</label>
                    </div>
                </div>
            </div>

            <!-- 帳號 -->
            <div class="col-3 mb-3">
                <label for="account" class="form-label">帳號<span class="text-danger">(必填) *帳號註冊後無法修改！</span></label>
                <input type="text" class="form-control" name="account" id="account" placeholder="請輸入帳號" required>
            </div>

            <!-- 密碼 -->
            <div class="col-3 mb-3">
                <label for="password" class="form-label">密碼<span class="text-danger">(必填)</span></label>
                <input type="password" class="form-control" name="password" placeholder="請輸入密碼" required>
            </div>
            <div class="col-3 mb-3">
                <label for="confirm_password" class="form-label">再次輸入密碼<span class="text-danger">(必填)</span></label>
                <input type="password" class="form-control" name="confirm_password" placeholder="請再次輸入密碼" required>
            </div>

            <!-- 電話 -->
            <div class="col-3 mb-3">
                <label for="phone" class="form-label">電話<span class="text-danger">(必填)</span></label>
                <input type="tel" class="form-control" name="phone" placeholder="請輸入電話" required>
            </div>

            <!-- 信箱 -->
            <div class="col-6 mb-3">
                <label for="email" class="form-label">信箱</label>
                <input type="email" class="form-control" name="email" placeholder="請輸入信箱">
            </div>

            <!-- 生日 -->
            <div class="col-3 mb-3">
                <label for="birthday" class="form-label">生日</label>
                <input type="date" class="form-control" name="birthday" value="">
            </div>

            <!-- 地址 -->
            <div class="col mb-3">
                <label for="address" class="form-label">地址</label>
                <div class="d-flex">
                    <select class="form-select me-1" style="flex-basis: 20%; max-width: 20%;" name="address_city_id">
                        <option selected>行政區</option>
                        <?php foreach ($CityList as $city) : ?>
                        <option value="<?=$city["address_city_id"]?>"><?=$city["address_city_name"]?></option>
                        <?php endforeach;?>
                    </select>
                    <select class="form-select me-1" style="flex-basis: 20%; max-width: 20%;" name="address_cityarea_id">
                        <option selected>鄉鎮市區</option>
                        <?php foreach ($CityAreaList as $cityArea) : ?>
                        <option value="<?=$cityArea["address_cityarea_id"]?>"><?=$cityArea["address_cityarea_name"]?></option>
                        <?php endforeach;?>
                    </select>
                    <input type="text" class="form-control" style="flex-basis: 60%; max-width: 60%;" name="address_street" placeholder="請輸入詳細地址">
                </div>
            </div>

            <!-- 按鈕 -->
            <div class="col-12">
                <button type="submit" class="btn btn-primary">確認新增</button>
                <button type="reset" class="btn btn-dark">清除內容</button>
            </div>
        </form>
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

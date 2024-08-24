<?php

// 整合頁面
include "../vars.php";
$cateNum = 1;
$pageTitle = "{$cate_ary[$cateNum]}";
include "../template_top.php";
include "../template_nav.php";

// 抓取城市與區域資料
require_once("../db_connect.php");
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
    <main class="main-content pb-3 px-5">
        <div class="pt-3">
            <div class="p-3 bg-white shadow rounded-2 mb-4 border">
                <div class="row g-2 align-items-center mb-2">
                    <div class="col-auto">
                        <a class="btn btn-primary" href="users.php">
                            <i class="fa-solid fa-circle-left"> 返回列表</i>
                        </a>
                    </div>
                    <div class="col">
                        <h1>新增管理員</h1>
                    </div>
                    <div class="py-2">
                        <form action="doCreateAdmin.php" method="post" class="searchbar row g-3 justify-content-between" onsubmit="return validateForm()">
                            <div class="row g-2">

                                <!-- 名稱 -->
                                <div class="col-6 form-floating pb-3">
                                    <input type="search" class="form-control" name="user_name" id="user_name" placeholder="請輸入名稱" required>
                                    <label for="user_name" class="form-label">名稱<span class="text-danger">(必填)</span></label>
                                </div>

                                <!-- 性別 -->
                                <div class="col-6 pb-3 d-flex align-items-center">
                                    <label for="gender" class="form-label">性別</label>
                                    <div class="form-check form-check-inline ms-3">
                                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="1">
                                        <label class="form-check-label" for="inlineRadio1">男</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="0">
                                        <label class="form-check-label" for="inlineRadio2">女</label>
                                    </div>
                                </div>

                                <!-- 帳號 -->
                                <div class="col-6 form-floating pb-3">
                                    <input type="text" class="form-control" name="account" id="account" placeholder="請輸入帳號" required>
                                    <label for="account" class="form-label">帳號<span class="text-danger">(必填) *帳號註冊後無法修改！</span></label>
                                </div>

                                <!-- 密碼 -->
                                <div class="col-6 form-floating pb-3">
                                    <input type="password" class="form-control" name="password" id="password" placeholder="請輸入密碼" required>
                                    <label for="password" class="form-label">密碼<span class="text-danger">(必填)</span></label>
                                </div>
                                <div class="col-6 form-floating pb-3">
                                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="請再次輸入密碼" required>
                                    <label for="confirm_password" class="form-label">再次輸入密碼<span class="text-danger">(必填)</span></label>
                                </div>

                                <!-- 電話 -->
                                <div class="col-6 form-floating pb-3">
                                    <input type="tel" class="form-control" name="phone" id="phone" placeholder="請輸入電話" required>
                                    <label for="phone" class="form-label">電話<span class="text-danger">(必填)</span></label>
                                </div>

                                <!-- 信箱 -->
                                <div class="col-6 form-floating pb-3">
                                    <input type="email" class="form-control" name="email" id="email" placeholder="請輸入信箱">
                                    <label for="email" class="form-label">信箱</label>
                                </div>

                                <!-- 生日 -->
                                <div class="col-6 form-floating pb-3">
                                    <input type="date" class="form-control" name="birthday" value="">
                                    <label for="birthday" class="form-label">生日</label>
                                </div>

                                <!-- 地址 -->
                                <!-- 行政區 -->
                                <div class="col-3 form-floating pb-3">
                                    <select class="form-select me-1" name="address_city_id">
                                        <option selected>行政區</option>
                                        <?php foreach ($CityList as $city) : ?>
                                            <option value="<?= $city["address_city_id"] ?>"><?= $city["address_city_name"] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="address" class="form-label">行政區</label>
                                </div>
                                <!-- 鄉鎮市區 -->
                                <div class="col-3 form-floating pb-3">
                                    <select class="form-select me-1" name="address_cityarea_id">
                                        <option selected>鄉鎮市區</option>
                                        <?php foreach ($CityAreaList as $cityArea) : ?>
                                            <option value="<?= $cityArea["address_cityarea_id"] ?>"><?= $cityArea["address_cityarea_name"] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="address" class="form-label">鄉鎮市區</label>
                                </div>
                                <!-- 街道門牌 -->
                                <div class="col-6 form-floating pb-3">
                                    <input type="text" class="form-control" name="address_street" placeholder="請輸入詳細地址">
                                    <label for="address" class="form-label">街道門牌</label>
                                </div>
                            </div>
                            <!-- 按鈕 -->
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">確認新增</button>
                                <button type="reset" class="btn btn-dark">清除內容</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                // 處理行政區選擇變更
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

                // 格式驗證與表單提交處理
                document.querySelector('form').addEventListener('submit', function(e) {
                    e.preventDefault(); // 阻止表單的默認提交行為

                    // 名稱驗證
                    var userName = document.getElementById('user_name').value;
                    if (!/^[\u4e00-\u9fa5]{1,5}$/.test(userName)) {
                        showModal("名稱必須是1至5個中文字");
                        return;
                    }

                    // 帳號驗證
                    var account = document.getElementById('account').value;
                    if (!/^[A-Za-z0-9]{1,12}$/.test(account)) {
                        showModal("帳號必須是1至12個英數字");
                        return;
                    }

                    // 密碼驗證
                    var password = document.getElementById('password').value;
                    if (!/^[A-Za-z0-9]{1,12}$/.test(password)) {
                        showModal("密碼必須是1至12個英數字");
                        return;
                    }

                    // 確認密碼一致性
                    var confirmPassword = document.getElementById('confirm_password').value;
                    if (password !== confirmPassword) {
                        showModal("密碼輸入不一致");
                        return;
                    }

                    // 電話驗證
                    var phone = document.getElementById('phone').value;
                    if (!/^09\d{8}$/.test(phone)) {
                        showModal("電話必須是09開頭的10位數字");
                        return;
                    }

                    // 信箱驗證
                    var email = document.getElementById('email').value;
                    if (email !== "" && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                        showModal("請輸入有效的信箱地址");
                        return;
                    }

                    // 當所有驗證通過後，使用 AJAX 發送表單數據
                    var formData = new FormData(this);
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'doCreateAdmin.php', true);
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (response.status === 1) {
                                showModal(response.message, true);
                            } else {
                                showModal(response.message);
                            }
                        } else {
                            showModal("伺服器錯誤，請稍後再試");
                        }
                    };
                    xhr.send(formData);
                });

                // 顯示 Modal
                function showModal(message, success = false) {
                    document.getElementById('modalMessage').innerText = message;

                    var modalFooter = document.querySelector('.modal-footer');
                    modalFooter.innerHTML = ''; // 清空 modal-footer 的內容

                    if (success) {
                        var confirmButton = document.createElement('button');
                        confirmButton.className = 'btn btn-primary';
                        confirmButton.innerText = '確認';
                        confirmButton.onclick = function() {
                            window.location.href = 'users.php';
                        };
                        modalFooter.appendChild(confirmButton);
                    } else {
                        var closeButton = document.createElement('button');
                        closeButton.className = 'btn btn-secondary';
                        closeButton.setAttribute('data-bs-dismiss', 'modal');
                        closeButton.innerText = '關閉';
                        modalFooter.appendChild(closeButton);
                    }

                    var errorModal = new bootstrap.Modal(document.getElementById('errorModal'), {
                        keyboard: false
                    });
                    errorModal.show();
                }
            </script>

            <?php $conn->close(); ?>
        </div>
    </main>

    <!-- 錯誤訊息 Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">請確認</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalMessage">
                    <!-- 錯誤訊息會顯示在這裡 -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
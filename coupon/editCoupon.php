<?php
require_once("../db_connect.php");

$id = $_GET["id"];
$sql = "SELECT * FROM `coupon` WHERE `id` = $id;";

$result = $conn->query($sql);
$couponCount = $result->num_rows;
$row = $result->fetch_assoc();

if ($couponCount > 0) {
    $title = $row["coupon_sid"];
} else {
    $title = "該序號不存在";
}

?>

<?php
include "../vars.php";
$cateNum = -1;
$pageTitle = "編輯優惠券";
include "../template_nav.php";
include "../template_top.php";
include "../template_btm.php";

?>

<!doctype html>
<html lang="en">

<head>
    <title>edit coupon</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <?php include("../css.php") ?>
</head>

<body>
    <main class="main-content pb-3 px-5">
        <div class="p-3 bg-white shadow rounded-2 mb-4 border">
            <div class="row g-2 align-items-center mb-2">
                <div class="col-auto">
                    <a class="btn btn-primary" href="couponIndex.php"><i class="fa-solid fa-circle-chevron-left"></i></a>
                </div>
                <div class="col">
                    <h4 class="m-0">編輯優惠券</h4>
                </div>
            </div>
            <div class="py-2">
                <form action="doUpdateCoupon.php" method="post">
                    <div class="row g-2">
                        <input type="hidden" name="id" value="<?= $row["id"] ?>">
                        <div class="col-6 form-floating pb-3">
                            <input class="form-control" id="coupon_sid" placeholder="coupon_sid" name="coupon_sid" value="<?= $row["coupon_sid"] ?>" disabled readonly>
                            <label for="coupon_sid"><span class="text-danger">*</span>優惠券序號</label>
                        </div>
                        <div class="col-6 form-floating pb-3">
                            <input type="coupon_name" class="form-control" id="coupon_name" placeholder="coupon_name" name="coupon_name" value="<?= $row["coupon_name"] ?>" disabled>
                            <label for="coupon_name"><span class="text-danger">*</span>優惠券名稱</label>
                        </div>
                        <div class="col-12 form-floating pb-3">
                            <input class="form-control" id="coupon_info" placeholder="coupon_info" name="coupon_info" value="<?= $row["coupon_info"] ?>">
                            <label for="coupon_info"><span class="text-danger">*</span>優惠券說明</label>
                        </div>
                        <div class="col-6 form-floating pb-3">
                            <select class="form-select" id="coupon_send" placeholder="coupon_send" name="coupon_send" value="<?= $row["coupon_send"] ?>">
                                <option value="1">全員發送</option>
                                <option value="2">生日</option>
                                <option value="3">等級</option>
                            </select>
                            <label for="coupon_send"><span class="text-danger">*</span>發放方式</label>
                        </div>
                        <div class="col-6 form-floating pb-3">
                            <input class="form-control" type="number" placeholder="coupon_lowPrice" name="coupon_lowPrice" id="coupon_lowPrice" value="<?= $row["coupon_lowPrice"] ?>">
                            <label for="form-label" for="coupon_lowPrice"><span class="text-danger">*</span>發放門檻(最低消費)</label>
                        </div>
                        <div class="col-6 form-floating pb-3">
                            <select class="form-select" id="coupon_rewardType" placeholder="coupon_rewardType" name="coupon_rewardType" value="<?= $row["coupon_rewardType"] ?>">
                                <option value="1" <?= $row["coupon_rewardType"] == 1 ? 'selected' : '' ?>>百分比</option>
                                <option value="2" <?= $row["coupon_rewardType"] == 2 ? 'selected' : '' ?>>金額</option>
                            </select>
                            <label for="coupon_rewardType"><span class="text-danger">*</span>折抵類別</label>
                        </div>
                        <div class="col-6 form-floating pb-3">
                            <input type="couponRewardType" class="form-control" id="couponRewardType" placeholder="couponRewardType" name="couponRewardType">
                            <label for="couponRewardType"><span class="text-danger">*</span>折抵</label>
                        </div>
                        <div class="col-6 form-floating pb-3">
                            <select class="form-select" id="coupon_mode" placeholder="coupon_mode" name="coupon_mode" value="<?= $row["coupon_mode"] ?>">
                                <option value="1" <?= $row["coupon_mode"] == 1 ? 'selected' : '' ?>>皆可使用</option>
                                <option value="2" <?= $row["coupon_mode"] == 2 ? 'selected' : '' ?>>指定商品可使用</option>
                                <option value="3" <?= $row["coupon_mode"] == 3 ? 'selected' : '' ?>>指定商品不可使用</option>
                            </select>
                            <label for="coupon_mode"><span class="text-danger">*</span>活動併用方式</label>
                        </div>
                        <div class="col-6 form-floating pb-3">
                            <select class="form-select" id="product_id" placeholder="product_id" name="product_id" value="<?= $row["product_id"] ?>">
                                <option value="1">撈商品id，要做dialog</option>
                            </select>
                            <label for="product_id">綁定商品標籤</label>
                        </div>
                        <div class="col-6 form-floating pb-3">
                            <input type="date" class="form-control" name="coupon_startDate" id="coupon_startDate" value="<?= $row["coupon_startDate"] ?>">
                            <label for="coupon_startDate">有效開始日期</label>
                        </div>
                        <div class="col-6 form-floating pb-3">
                            <input type="date" class="form-control" name="coupon_endDate" id="coupon_endDate" value="<?= $row["coupon_endDate"] ?>">
                            <label for="coupon_endDate">有效結束日期</label>
                        </div>
                        <div class="col-6  d-flex gap-2 align-items-center pb-3">
                            <div class="form-floating col-10">
                                <input class="form-control" type="text" placeholder="coupon_amount" name="coupon_amount" id="coupon_amount" value="<?= $row["coupon_amount"] ?>">
                                <label for="form-label" for="coupon_amount"><span class="text-danger">*</span>發放數量</label>
                            </div>
                            <div class="form-check col-2">
                                <label for="unlimitedCheckbox">
                                    <input type="checkbox" id="unlimitedCheckbox" name="unlimitedCheckbox">
                                    無上限
                                </label>
                            </div>
                        </div>
                        <div class="col-6  d-flex gap-2 align-items-center pb-3">
                            <div class="form-floating col-10">
                                <input class="form-control" type="text" placeholder="coupon_maxUse" name="coupon_maxUse" id="coupon_maxUse" value="<?= $row["coupon_maxUse"] ?>">
                                <label for="form-label" for="coupon_maxUse"><span class="text-danger">*</span>使用次數上限</label>
                            </div>
                            <div class="form-check col-2">
                                <label for="unlimitedUseCheckbox">
                                    <input type="checkbox" id="unlimitedUseCheckbox" name="unlimitedUseCheckbox">
                                    無上限
                                </label>
                            </div>
                        </div>
                        <div class="col-6  d-flex gap-2 align-items-center pb-3">
                            <div class="form-floating col-10">
                                <input type="date" class="form-control" name="coupon_specifyDate" id="coupon_specifyDate" value="<?= $row["coupon_specifyDate"] ?>">
                                <label for="coupon_specifyDate">自動發送時間</label>
                            </div>
                            <div class="form-check col-2">
                                <label for="couponSpecifyDateCheckbox">
                                    <input type="checkbox" id="couponSpecifyDateCheckbox" name="couponSpecifyDateCheckbox">
                                    即刻送
                                </label>
                            </div>
                        </div>
                        <div class="col-6 form-floating pb-3">
                            <select class="form-select" id="coupon_state" placeholder="coupon_state" name="coupon_state" value="<?= $row["coupon_state"] ?>">
                                <option value="1" <?= $row["coupon_state"] == 1 ? 'selected' : '' ?>>啟用</option>
                                <option value="2" <?= $row["coupon_state"] == 2 ? 'selected' : '' ?>>停用</option>
                            </select>
                            <label for="coupon_mode"><span class="text-danger">*</span>活動併用方式</label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center pt-3 gap-3">
                        <button class="btn btn-primary" type="submit">送出</button>
                        <button class="btn btn-dark" href="couponIndex.php">返回</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

</body>
<script>
    const coupon_amount = document.getElementById("coupon_amount");
    const unlimitedCheckbox = document.getElementById("unlimitedCheckbox");
    const coupon_maxUse = document.getElementById("coupon_maxUse");
    const unlimitedUseCheckbox = document.getElementById("unlimitedUseCheckbox");
    const coupon_specifyDate = document.getElementById("coupon_specifyDate");
    const couponSpecifyDateCheckbox = document.getElementById("couponSpecifyDateCheckbox");
    //發放數量

    unlimitedCheckbox.addEventListener('change', function() {
        if (this.checked) {
            coupon_amount.value = '無上限';
            coupon_amount.disabled = true;
            coupon_amount.classList.add('unlimited-input');
        } else {
            coupon_amount.value = '';
            coupon_amount.disabled = false;
            coupon_amount.classList.remove('unlimited-input');
        }
    });
    //使用次數上限
    unlimitedUseCheckbox.addEventListener('change', function() {
        if (this.checked) {
            coupon_maxUse.value = '無上限';
            coupon_maxUse.disabled = true;
            coupon_maxUse.classList.add('unlimitedUse-input');
        } else {
            coupon_maxUse.value = '';
            coupon_maxUse.disabled = false;
            coupon_maxUse.classList.remove('unlimitedUse-input');
        }
    });
    //指定時間
    couponSpecifyDateCheckbox.addEventListener('change', function() {
        if (this.checked) {
            coupon_specifyDate.value = new Date().format("yyyy-MM-dd");
            console.log(coupon_specifyDate.value)
            coupon_specifyDate.readOnly = true;
            coupon_specifyDate.classList.add('specifyDate-input');
        } else {
            coupon_specifyDate.value = '';
            coupon_specifyDate.readOnly = false;
            coupon_specifyDate.classList.remove('specifyDate-input');
        }
    });
</script>

</html>
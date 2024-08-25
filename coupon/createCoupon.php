<?php
include "../vars.php";
$cateNum = -1;
$pageTitle = "新增優惠券";
include "../template_nav.php";
include "../template_top.php";
include "../template_btm.php";

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
</head>

<body>
    <main class="main-content pb-3 px-5">
        <div class="pt-3">
            <div class="p-3 bg-white shadow rounded-2 mb-4 border">
                <div class="row g-2 align-items-center mb-2">
                    <div class="col-auto">
                        <a class="btn btn-primary" href="couponIndex.php"><i class="fa-solid fa-circle-left"></i></a>
                    </div>
                    <div class="col">
                        <h4 class="m-0">新增優惠券</h4>
                    </div>
                </div>
                <div class="py-2">
                    <form action="doCreateCoupon.php" method="post">
                        <div class="row g-2">
                            <div class="col-6 form-floating pb-3">
                                <input class="form-control" id="coupon_sid" placeholder="coupon_sid" name="coupon_sid">
                                <label for="coupon_sid"><span class="text-danger">*</span>優惠券序號</label>
                            </div>
                            <!-- 序號生成 -->
                            <!-- <code id="serial"></code>
                            <button id="generate" onclick="generateSerial()">Generate Serial Number</button> -->
                            <div class="col-6 form-floating pb-3">
                                <input class="form-control" id="coupon_name" placeholder="coupon_name" name="coupon_name">
                                <label for="coupon_name"><span class="text-danger">*</span>優惠券名稱</label>
                            </div>
                            <div class="col-12 form-floating pb-3">
                                <input class="form-control" id="coupon_info" placeholder="coupon_info" name="coupon_info">
                                <label for="coupon_info"><span class="text-danger">*</span>優惠券說明</label>
                            </div>
                            <div class="col-6 form-floating pb-3">
                                <select class="form-select" id="coupon_send" placeholder="coupon_send" name="coupon_send">
                                    <option value="1">全員發送</option>
                                    <option value="2">生日</option>
                                    <option value="3">等級</option>
                                </select>
                                <label for="coupon_send"><span class="text-danger">*</span>發放方式</label>
                            </div>
                            <div class="col-6 form-floating pb-3">
                                <input class="form-control" type="number" placeholder="coupon_lowPrice" name="coupon_lowPrice" id="coupon_lowPrice">
                                <label for="form-label" for="coupon_lowPrice"><span class="text-danger">*</span>發放門檻(最低消費)</label>
                            </div>
                            <div class="col-6 form-floating pb-3">
                                <select class="form-select" id="coupon_rewardType" placeholder="coupon_rewardType" name="coupon_rewardType">
                                    <option value="1">百分比</option>
                                    <option value="2">金額</option>
                                </select>
                                <label for="coupon_rewardType"><span class="text-danger">*</span>折抵類別</label>
                            </div>
                            <div class="col-6 form-floating pb-3">
                                <input type="couponRewardType" class="form-control" id="couponRewardType" placeholder="couponRewardType" name="couponRewardType">
                                <label for="couponRewardType"><span class="text-danger">*</span>折抵</label>
                            </div>
                            <div class="col-6 form-floating pb-3">
                                <select class="form-select" id="coupon_mode" placeholder="coupon_mode" name="coupon_mode">
                                    <option value="1">皆可使用</option>
                                    <option value="2">指定商品可使用</option>
                                    <option value="3">指定商品不可使用</option>
                                </select>
                                <label for="coupon_mode"><span class="text-danger">*</span>活動併用方式</label>
                            </div>
                            <div class="col-6 form-floating pb-3">
                                <select class="form-select" id="product_id" placeholder="product_id" name="product_id">
                                    <option value="1">撈商品id，要做dialog</option>
                                </select>
                                <label for="product_id">綁定商品標籤</label>
                            </div>
                            <div class="col-6 form-floating pb-3">
                                <input type="date" class="form-control" name="coupon_startDate" value="" id="coupon_startDate">
                                <label for="coupon_startDate">有效開始日期</label>
                            </div>
                            <div class="col-6 form-floating pb-3">
                                <input type="date" class="form-control" name="coupon_endDate" value="" id="coupon_endDate">
                                <label for="coupon_endDate">有效結束日期</label>
                            </div>
                            <div class="col-6  d-flex gap-2 align-items-center pb-3">
                                <div class="form-floating col-10">
                                    <input class="form-control" type="text" placeholder="coupon_amount" name="coupon_amount" id="coupon_amount">
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
                                    <input class="form-control" type="text" placeholder="coupon_maxUse" name="coupon_maxUse" id="coupon_maxUse">
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
                                    <input type="date" class="form-control" name="coupon_specifyDate" value="" id="coupon_specifyDate">
                                    <label for="coupon_specifyDate">自動發送時間</label>
                                </div>
                                <div class="form-check col-2">
                                    <label for="couponSpecifyDateCheckbox">
                                        <input type="checkbox" id="couponSpecifyDateCheckbox" name="couponSpecifyDateCheckbox">
                                        即刻送
                                    </label>
                                </div>
                            </div>
                            <div class="col-6 form-floating pb-3" hidden>
                                <select class="form-select" id="coupon_state" name="coupon_state">
                                    <option value="1" selected>啟用</option>
                                </select>
                                <label for="coupon_state">優惠券狀態</label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center pt-3">
                            <button class="btn btn-primary" type="submit">送出</button>
                        </div>
                    </form>
                </div>
            </div>
            <script>
                const coupon_amount = document.getElementById("coupon_amount");
                const unlimitedCheckbox = document.getElementById("unlimitedCheckbox");
                const coupon_maxUse = document.getElementById("coupon_maxUse");
                const unlimitedUseCheckbox = document.getElementById("unlimitedUseCheckbox");
                const coupon_specifyDate = document.getElementById("coupon_specifyDate");
                const couponSpecifyDateCheckbox = document.getElementById("couponSpecifyDateCheckbox");

                // const coupon_sid = document.getElementById("coupon_sid");
                // const serial = document.getElementById("serial");
                // const generate = document.getElementById("generate");
                // // 序號產生
                // function generateSerial() {

                //     'use strict';

                //     var chars = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
                //         serialLength = 10,
                //         randomSerial = "",
                //         i,
                //         randomNumber;

                //     for (i = 0; i < serialLength; i++) {
                //         randomNumber = Math.floor(Math.random() * chars.length);
                //         randomSerial += chars.substring(randomNumber, randomNumber + 1);
                //     }

                //     // 将生成的序号显示在 <code> 中
                //     document.getElementById('serial').innerHTML = randomSerial;

                //     // 将生成的序号填入 input 框中
                //     document.getElementById('coupon_sid').value = randomSerial;
                // }
                Date.prototype.format = function(fmt) {
                    var o = {
                        "M+": this.getMonth() + 1,
                        "d+": this.getDate(),
                        "h+": this.getHours(),
                        "m+": this.getMinutes(),
                        "s+": this.getSeconds(),
                        "q+": Math.floor((this.getMonth() + 3) / 3),
                        "S": this.getMilliseconds()
                    };
                    if (/(y+)/.test(fmt)) {
                        fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
                    }
                    for (var k in o) {
                        if (new RegExp("(" + k + ")").test(fmt)) {
                            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
                        }
                    }
                    return fmt;
                }


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
        </div>
    </main>

</body>

</html>
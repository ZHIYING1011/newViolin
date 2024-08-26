<?php
include "../vars.php";
$cateNum = 0;
$pageTitle = "{$cate_ary[$cateNum]}";
include "../template_top.php";
include "../template_nav.php";
?>

<!doctype html>
<html lang="en">

<head>
    <title>新增商品</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    
    <?php include("../css.php") ?>

</head>

<body>

<main class="main-content pb-3 px-5 pt-3">
    <div class="p-3 bg-white shadow rounded-2 mb-4 border">
        <div class="row g-2 align-items-center mb-2">
            <div class="col-auto">
                <a class="btn btn-primary" href="productIndex.php"><i class="fa-solid fa-circle-chevron-left"></i></a>
            </div>
            <div class="col">
                <h4 class="m-0">新增商品</h4>
            </div>
        </div>
        <div class="py-2">
            <form id="add-product-form" action="doCreateProduct.php" method="post" enctype="multipart/form-data">
                <div class="row g-2">
                    <!-- 商品名稱 -->
                    <div class="col-6 form-floating pb-3">
                        <input type="text" class="form-control" id="productName" name="productName" placeholder="商品名稱" required>
                        <label for="productName"><span class="text-danger">*</span>商品名稱</label>
                    </div>
                    
                    <!-- 商品編碼 -->
                    <div class="col-6 form-floating pb-3">
                        <input type="text" class="form-control" id="productId" name="productId" placeholder="商品編碼" required>
                        <label for="productId"><span class="text-danger">*</span>商品編碼</label>
                    </div>
                    
                    <!-- 商品圖片 -->
                    <div class="col-12 form-control pb-3 text-center border-0 px-1">
        <input  class="form-control form-control-lg " type="file" id="formFile" name="picture" multiple required> 
        
    </div>
                    
                    <!-- 商品品牌 -->
                    <div class="col-6 form-floating pb-3">
                        <select class="form-select" id="brand" name="brand" required>
                            <option value="" disabled selected>選擇品牌</option>
                            <option value="1">ISVA</option>
                            <option value="2">BORYA</option>
                            <option value="3">Franz Kirschnek</option>
                            <option value="4">LienViolins</option>
                            <option value="5">David Lien</option>
                            <option value="6">Peter Siegfried Heffler</option>
                            <option value="7">Rainer W. Leonhardt</option>
                            <option value="8">Heinrich Gill</option>
                        </select>
                        <label for="brand"><span class="text-danger">*</span>商品品牌</label>
                    </div>
                    
                    <!-- 商品類別 -->
                    <div class="col-6 form-floating pb-3">
                        <select class="form-select" id="category" name="category" required>
                            <option value="" disabled selected>選擇類別</option>
                            <option value="1">小提琴</option>
                            <option value="2">中提琴</option>
                            <option value="3">大提琴</option>
                            <option value="4">配件</option>
                        </select>
                        <label for="category"><span class="text-danger">*</span>商品類別</label>
                    </div>
                    
                    <!-- 商品價格 -->
                    <div class="col-6 form-floating pb-3">
                        <input type="number" class="form-control" id="price" name="price" placeholder="請輸入數字" min="0" required>
                        <label for="price"><span class="text-danger">*</span>商品價格</label>
                    </div>
                    
                    <!-- 庫存數量 -->
                    <div class="col-6 form-floating pb-3">
                        <input type="number" class="form-control" id="stock" name="stock" placeholder="請輸入數字" min="0" required>
                        <label for="stock"><span class="text-danger">*</span>庫存數量</label>
                    </div>
                    
                    <!-- 上架狀態 -->
                    <div class="col-6 form-floating pb-3">
                        <select class="form-select" id="isActive" name="isActive" required>
                            <option value="" disabled selected>選擇狀態</option>
                            <option value="1">上架</option>
                            <option value="-1">下架</option>
                        </select>
                        <label for="isActive"><span class="text-danger">*</span>上架狀態</label>
                    </div>
                    
                    <!-- 詳細說明 -->
                    <div class="col-6 form-floating pb-3">
                        <input type="text" class="form-control" id="description" name="description" placeholder="詳細說明">
                        <label for="description">詳細說明</label>
                    </div>
                    
                    <!-- 提交按鈕 -->
                    <div class="col-12 d-flex justify-content-end my-3">
                    <button type="submit" id="submit-btn" class="btn btn-primary">新增</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<!-- 成功提示框 -->
<div class="modal fade" id="success-modal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">新增商品成功</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    商品資料已成功新增。
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                </div>
            </div>
        </div>
    </div>

    <div id="error-message" class="alert alert-danger" style="display:none;"></div>

    <?php include("../js.php") ?>
    <script>
document.getElementById('add-product-form').addEventListener('submit', function(e) {
    e.preventDefault(); // 阻止表單的預設提交行為
    const form = this;
    const formData = new FormData(form);

    fetch('doCreateProduct.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === 'success') {
            // 顯示成功提示框
            var myModal = new bootstrap.Modal(document.getElementById('success-modal'));
            myModal.show();

            // 重置表單
            form.reset();

            // 延遲跳轉到 productIndex 頁面
            setTimeout(function() {
                window.location.href = 'productIndex.php';
            }, 2000); // 2秒後跳轉，可以根據需求調整時間
        } else {
            // 顯示失敗提示框，這裡可以改成顯示失敗的 modal
            alert('商品新增失敗！請稍後再試。');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const errorDiv = document.getElementById('error-message');
        if (error instanceof Response) {
            error.text().then(errorMessage => {
                errorDiv.innerHTML = '發生錯誤，請稍後再試。詳細錯誤信息：' + errorMessage;
                errorDiv.style.display = 'block';
            });
        } else {
            errorDiv.innerHTML = '發生錯誤，請稍後再試。詳細錯誤信息：' + error.message;
            errorDiv.style.display = 'block';
        }
    });
});
</script>
</body>

</html>
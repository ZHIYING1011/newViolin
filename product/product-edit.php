<?php
include "../vars.php";
$cateNum = 0;
$pageTitle = "{$cate_ary[$cateNum]}";
include "../template_top.php";
include "../template_nav.php";
?>

<?php
if (!isset($_GET["id"])) {
    echo "請正確帶入 get id 變數";
    exit;
}

$id = $_GET["id"];

require_once("../db_connect.php");

$sql = "SELECT product.*, 
       picture.picture_url AS picture_name
FROM product
LEFT JOIN picture 
    ON product.id = picture.product_id 
    AND picture.picture_url LIKE '%-1.%' 
    AND picture.valid = 1
WHERE product.id = '$id' 
  AND product.valid = 1;";

$result = $conn->query($sql);
$productCount = $result->num_rows;
$row = $result->fetch_assoc();

if ($productCount > 0) {
    $title = $row["product_name"];
} else {
    $title = "該商品不存在";
}

$modalMessage = isset($_GET["success"]) ? "商品更新成功！" : "";

?>

<!doctype html>
<html lang="en">

<head>
    <title><?= $title ?></title>
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
                    <a class="btn btn-primary" href="productIndex.php" title="回商品列表"><i class="fa-solid fa-circle-chevron-left"></i></a>
                </div>
                <div class="col">
                    <h4 class="m-0">編輯商品資訊</h4>
                </div>
            </div>
            <div class="py-2">
                <?php if ($productCount > 0) : ?>
                    <form action="doUpdateProduct.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($row["id"]) ?>">

                        <div class="row g-2">
                            <!-- 商品名稱 -->
                            <div class="col-6 form-floating pb-3">
                                <input type="text" class="form-control" id="productName" name="productName" placeholder="商品名稱" value="<?= htmlspecialchars($row["product_name"]) ?>" required>
                                <label for="productName"><span class="text-danger">*</span>商品名稱</label>
                            </div>

                            <!-- 商品編碼 -->
                            <div class="col-6 form-floating pb-3">
                                <input type="text" class="form-control" id="productId" name="productId" placeholder="商品編碼" value="<?= htmlspecialchars($row["product_id"]) ?>" required>
                                <label for="productId"><span class="text-danger">*</span>商品編碼</label>
                            </div>

                            <!-- 商品圖片 -->
                            <div class="col-12 form-control pb-3 text-center border-0 px-1">
                                <input class="form-control form-control-lg" type="file" id="formFile" name="picture">
                            </div>

                            <!-- 現有圖片的檔名 -->
                            <input type="hidden" name="existingPictureName" value="<?= htmlspecialchars($row["picture_name"]) ?>">

                            <!-- 商品品牌 -->
                            <div class="col-6 form-floating pb-3">
                                <select class="form-select" id="brand" name="brand" required>
                                    <option value="" disabled>選擇品牌</option>
                                    <option value="1" <?= $row["brand_id"] == 1 ? 'selected' : '' ?>>ISVA</option>
                                    
                                    <option value="2" <?= $row["brand_id"] == 2 ? 'selected' : '' ?>>BORYA</option>
                                    <option value="3" <?= $row["brand_id"] == 3 ? 'selected' : '' ?>>Franz Kirschnek</option>
                                    <option value="4" <?= $row["brand_id"] == 4 ? 'selected' : '' ?>>LienViolins</option>
                                    <option value="5" <?= $row["brand_id"] == 5 ? 'selected' : '' ?>>David Lien</option>
                                    <option value="6" <?= $row["brand_id"] == 6 ? 'selected' : '' ?>>Peter Siegfried Heffler</option>
                                    <option value="7" <?= $row["brand_id"] == 7 ? 'selected' : '' ?>>Rainer W. Leonhardt</option>
                                    <option value="8" <?= $row["brand_id"] == 8 ? 'selected' : '' ?>>Heinrich Gill</option>
                                </select>
                                <label for="brand"><span class="text-danger">*</span>商品品牌</label>
                            </div>

                            <!-- 商品類別 -->
                            <div class="col-6 form-floating pb-3">
                                <select class="form-select" id="category" name="category" required>
                                    <option value="" disabled>選擇類別</option>
                                    <option value="1" <?= $row["category_id"] == 1 ? 'selected' : '' ?>>小提琴</option>
                                    <option value="2" <?= $row["category_id"] == 2 ? 'selected' : '' ?>>中提琴</option>
                                    <option value="3" <?= $row["category_id"] == 3 ? 'selected' : '' ?>>大提琴</option>
                                    <option value="4" <?= $row["category_id"] == 4 ? 'selected' : '' ?>>配件</option>
                                </select>
                                <label for="category"><span class="text-danger">*</span>商品類別</label>
                            </div>

                            <!-- 商品價格 -->
                            <div class="col-6 form-floating pb-3">
                                <input type="number" class="form-control" id="price" name="price" placeholder="請輸入數字" min="0" value="<?= htmlspecialchars($row["price"]) ?>" required>
                                <label for="price"><span class="text-danger">*</span>商品價格</label>
                            </div>

                            <!-- 庫存數量 -->
                            <div class="col-6 form-floating pb-3">
                                <input type="number" class="form-control" id="stock" name="stock" placeholder="請輸入數字" min="0" value="<?= htmlspecialchars($row["stock"]) ?>" required>
                                <label for="stock"><span class="text-danger">*</span>庫存數量</label>
                            </div>

                            <!-- 上架狀態 -->
                            <div class="col-6 form-floating pb-3">
                                <select class="form-select" id="isActive" name="isActive" required>
                                    <option value="" disabled>選擇狀態</option>
                                    <option value="1" <?= $row["is_active"] == 1 ? 'selected' : '' ?>>上架</option>
                                    <option value="-1" <?= $row["is_active"] == -1 ? 'selected' : '' ?>>下架</option>
                                </select>
                                <label for="isActive"><span class="text-danger">*</span>上架狀態</label>
                            </div>

                            <!-- 詳細說明 -->
                            <div class="col-12 form-floating pb-3">
                                <input type="text" class="form-control" id="description" name="description" placeholder="詳細說明" value="<?= htmlspecialchars($row["description"]) ?>">
                                <label for="description"><span class="text-danger">*</span>詳細說明</label>
                            </div>

                            <!-- 提交按鈕 -->
                            <div class="col-12 d-flex justify-content-end pb-3">
                                <button type="submit" class="btn btn-primary btn-lg">修改完成</button>
                            </div>
                        </div>
                    </form>
                <?php else : ?>
                    商品不存在
                <?php endif; ?>
            </div>
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
                    <button type="button" class="btn btn-primary" id="redirectToIndex">返回商品列表</button>
                </div>
            </div>
        </div>
    </div>

    <?php include("../js.php") ?>
    <script>
        <?php if (!empty($modalMessage)) : ?>
            var successModal = new bootstrap.Modal(document.getElementById('successModal'), {});
            successModal.show();

            document.getElementById("redirectToIndex").addEventListener("click", function() {
                window.location.href = "productIndex.php";
            });
        <?php endif; ?>
    </script>

</body>

</html>
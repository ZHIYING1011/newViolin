<?php
include "../vars.php";
$cateNum = 2;
$pageTitle = "{$cate_ary[$cateNum]}";
include "../template_top.php";
include "../template_nav.php";
?>

<?php
require_once("../db_connect.php");

$search = isset($_GET["search"]) ? $_GET["search"] : '';
$brand = isset($_GET["brand"]) ? $_GET["brand"] : '';
$category = isset($_GET["category"]) ? $_GET["category"] : '';
$status = isset($_GET["status"]) ? $_GET["status"] : '';
$page = isset($_GET["p"]) ? (int)$_GET["p"] : 1;

$per_page = 8;
$start_item = ($page - 1) * $per_page;
$order = isset($_GET["order"]) ? (int)$_GET["order"] : 1;

$where_clauses = ["product.valid = 1"];

// 處理搜尋條件
if (!empty($search)) {
    $where_clauses[] = "(product.product_id LIKE '%$search%' OR product.product_name LIKE '%$search%')";
}

// 添加品牌條件
if (!empty($brand)) {
    $where_clauses[] = "product.brand_id = $brand";
}

// 添加類別條件
if (!empty($category)) {
    $where_clauses[] = "product.category_id = $category";
}

// 添加上架狀態條件
if (!empty($status)) {
    $where_clauses[] = "product.is_active = $status";
}

// 合併WHERE子句
$where_sql = implode(' AND ', $where_clauses);


// 基本的 SQL 語句（不包含 ORDER BY 和 LIMIT）
$sql = "SELECT product.*, 
               brand.name AS brand_name, 
               category.name AS category_name,
               picture.picture_url AS picture_name
        FROM product
        JOIN brand ON product.brand_id = brand.id
        JOIN category ON product.category_id = category.id
        LEFT JOIN picture ON product.id = picture.product_id AND picture.picture_url LIKE '%-1.%' AND picture.valid = 1
        WHERE $where_sql";

// 確定排序條件
switch ($order) {
    case 1:
        $order_clause = "ORDER BY product.id ASC";
        break;
    case 2:
        $order_clause = "ORDER BY product.id DESC";
        break;
    case 3:
        $order_clause = "ORDER BY product.price ASC";
        break;
    case 4:
        $order_clause = "ORDER BY product.price DESC";
        break;
    case 5:
        $order_clause = "ORDER BY product.stock ASC";
        break;
    case 6:
        $order_clause = "ORDER BY product.stock DESC";
        break;
    default:
        $order_clause = "ORDER BY product.id ASC"; // 預設排序
        break;
}

$sql .= " $order_clause";

// 最後加上 LIMIT 子句
$sql .= " LIMIT $start_item, $per_page";

// 執行查詢
$result = $conn->query($sql);

// 計算符合條件的商品總數
$count_sql = "SELECT COUNT(*) as total_items FROM product 
              JOIN brand ON product.brand_id = brand.id 
              JOIN category ON product.category_id = category.id 
              LEFT JOIN picture ON product.id = picture.product_id AND picture.picture_url LIKE '%-1.%' AND picture.valid = 1
              WHERE $where_sql";

$count_result = $conn->query($count_sql);
$count_row = $count_result->fetch_assoc();
$total_items = $count_row['total_items']; // 獲取總商品數量


// 計算總頁數
$total_page = ceil($total_items / $per_page);


?>

<!doctype html>
<html lang="en">

<head>
    <title>商品列表</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <?php
    include("../css.php")
    ?>

    <style>
        .form-floating {
            display: flex;
            align-items: center;
        }

        .form-floating input {
            flex: 1;
            margin-right: 0.5rem;
        }

        .form-floating button {
            flex-shrink: 0;
            height: 100%;
        }

        .product-img {
            height: 100px;
            display: block;
            margin-inline: auto;
        }

        .modal-close {
            background-color: white;
            border: none;
            font-size: 25px;

        }

        .modalProductBrand {
            margin-bottom: -20px;
        }

        .modalDescription {
            margin-top: 25px;
            margin-bottom: 25px;
        }
    </style>
</head>




<body>

    <main class="main-content pb-3">
        <div class="pt-3">
            <div class="p-3 bg-white shadow rounded-2 mb-4 border">
                <div class="py-2">
                    <h4>商品列表</h4>
                    <form action="" method="get">
                        <?php

                        $rows = $result->fetch_all(MYSQLI_ASSOC);
                        ?>
                        <div class="row g-2">
                            <div class="col-3 form-floating">
                                <input type="search" class="form-control" name="search" placeholder="搜尋關鍵字" value="<?php echo isset($_GET["search"]) ? $_GET["search"] : "" ?>">
                                <label for="search">搜尋關鍵字</label>
                            </div>
                            <div class="col-3 form-floating">
    <select class="form-select" aria-label="品牌" name="brand">
        <option value="" selected>請選擇品牌</option>
        <option value="1" <?php echo $brand == '1' ? 'selected' : '' ?>>ISVA</option>
        <option value="2" <?php echo $brand == '2' ? 'selected' : '' ?>>BORYA</option>
        <option value="3" <?php echo $brand == '3' ? 'selected' : '' ?>>Franz Kirschnek</option>
        <option value="4" <?php echo $brand == '4' ? 'selected' : '' ?>>LienViolins</option>
        <option value="5" <?php echo $brand == '5' ? 'selected' : '' ?>>David Lien</option>
        <option value="6" <?php echo $brand == '6' ? 'selected' : '' ?>>Peter Siegfried Heffler</option>
        <option value="7" <?php echo $brand == '7' ? 'selected' : '' ?>>Rainer W. Leonhardt</option>
        <option value="8" <?php echo $brand == '8' ? 'selected' : '' ?>>Heinrich Gill</option>
    </select>
    <label for="brand">品牌</label>
</div>

<div class="col-3 form-floating">
    <select class="form-select" aria-label="類別" name="category">
        <option value="" selected>請選擇類別</option>
        <option value="1" <?php echo $category == '1' ? 'selected' : '' ?>>小提琴</option>
        <option value="2" <?php echo $category == '2' ? 'selected' : '' ?>>中提琴</option>
        <option value="3" <?php echo $category == '3' ? 'selected' : '' ?>>大提琴</option>
        <option value="4" <?php echo $category == '4' ? 'selected' : '' ?>>配件</option>
    </select>
    <label for="category">類別</label>
</div>

<div class="col-3 form-floating">
    <select class="form-select" aria-label="上架狀態" name="status">
        <option value="" selected>請選擇上架狀態</option>
        <option value="1" <?php echo $status == '1' ? 'selected' : '' ?>>上架</option>
        <option value="-1" <?php echo $status == '-1' ? 'selected' : '' ?>>下架</option>
    </select>
    <label for="status">上架狀態</label>
</div>
                        </div>
                        <div class="row g-2 d-flex justify-content-between pt-3 align-items-center">
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                                <a class="btn btn-dark btn-lg mx-2" href="productIndex.php">
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white shadow rounded-2 border">
                <div class="table-title mb-3 d-flex justify-content-between align-items-center p-2 rounded-top">
                    <h6 class="m-0 text-primary ms-2"><?php echo "共有 {$total_items} 件商品"; ?></h6>
                    <a class="btn btn-primary me-2" href="create-product.php">新增商品</a>
                </div>
                <div class="p-3">
                    <table class="coupon-table table table-bordered p-3 text-center align-middle">
                        <thead>
                            <tr>
                                <th>編號
                                <div class="btn-group btn-group-sm">
                            <a class="btn btn-light
" href="productIndex.php?p=<?= $page ?>&order=1"><i class="fa-solid fa-arrow-down-1-9"></i></a>

                            <a class="btn btn-light
                <?php if ($order == 2) echo "active" ?>" href="productIndex.php?p=<?= $page ?>&order=2"><i class="fa-solid fa-arrow-down-9-1"></i></a>
                        </div>
                                </th>
                                <th>封面圖</th>
                                <th>商品編碼</th>
                                <th>品牌名稱</th>
                                <th>商品名稱</th>
                                <th>商品類別</th>
                                <th>價格
                                <div class="btn-group btn-group-sm">
                            <a class="btn btn-light
                <?php if ($order == 3) echo "active" ?>" href="productIndex.php?p=<?= $page ?>&order=3"><i class="fa-solid fa-arrow-down-1-9"></i></a>

                            <a class="btn btn-light
                <?php if ($order == 4) echo "active" ?>" href="productIndex.php?p=<?= $page ?>&order=4"><i class="fa-solid fa-arrow-down-9-1"></i></a>
                        </div>
                                </th>
                                <th>庫存數量
                                <div class="btn-group btn-group-sm">
                            <a class="btn btn-light
                <?php if ($order == 5) echo "active" ?>" href="productIndex.php?p=<?= $page ?>&order=5"><i class="fa-solid fa-arrow-down-1-9"></i></a>

                            <a class="btn btn-light
                <?php if ($order == 6) echo "active" ?>" href="productIndex.php?p=<?= $page ?>&order=6"><i class="fa-solid fa-arrow-down-9-1"></i></a>
                        </div>
                                </th>
                                <th>上架狀態</th>
                                <th>商品說明/編輯/刪除</th>
                            </tr>
                        </thead>
                        <tbody id="main_h">
                            <?php foreach ($rows as $product) : ?>
                                <tr>
                                    <td><?= $product["id"] ?></td>
                                    <td class=" ">
                                        <img class="object-fit-cover product-img" src="./<?= $product["brand_name"] ?>/<?= $product["picture_name"] ?>" alt="">
                                    </td>
                                    <td><?= $product["product_id"] ?></td>
                                    <td><?= $product["brand_name"] ?></td>
                                    <td><?= $product["product_name"] ?></td>
                                    <td><?= $product["category_name"] ?></td>
                                    <td><?= $product["price"] ?></td>
                                    <td><?= $product["stock"] ?></td>
                                    <td>
                                        <?= $product["is_active"] > 0
                                            ? '<span class="badge rounded-pill bg-success">上架</span>'
                                            : '<span class="badge rounded-pill bg-danger">下架</span>'
                                        ?>
                                    </td>
                                    <td>
                                        <a class="btn " href="#" data-bs-toggle="modal" data-bs-target="#productModal" data-product-id="<?= $product["product_id"] ?>" data-brand-name="<?= $product["brand_name"] ?>" data-product-name="<?= $product["product_name"] ?>" data-description="<?= htmlspecialchars($product["description"], ENT_QUOTES, 'UTF-8') ?>">
                                            <i class="fa-solid fa-circle-info"></i>
                                        </a>
                                        <a class="btn " href="product-edit.php?id=<?= $product["id"] ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <a class="btn" href="#" data-bs-toggle="modal" data-bs-target="#deleteModal" data-delete-id="<?= $product["id"] ?>" data-brand-name="<?= $product["brand_name"] ?>" data-product-name="<?= $product["product_name"] ?>">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center py-3">
        <?php for ($i = 1; $i <= $total_page; $i++) : ?>
            <li class="page-item <?php if ($page == $i) echo "active"; ?>">
                <a class="page-link" href="productIndex.php?p=<?= $i ?>
                    <?php if (!empty($order)) : ?>&order=<?= $order ?><?php endif; ?>
                    <?php if (!empty($search)) : ?>&search=<?= urlencode($search) ?><?php endif; ?>
                    <?php if (!empty($brand)) : ?>&brand=<?= $brand ?><?php endif; ?>
                    <?php if (!empty($category)) : ?>&category=<?= $category ?><?php endif; ?>
                    <?php if (!empty($status)) : ?>&status=<?= $status ?><?php endif; ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
        </div>
    </main>

    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">

                    <h4 class="mt-3" id="modalProductId"></h4>

                    <button type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-circle-xmark"></i></button>

                </div>
                <div class="modal-body">

                    <p class="modalProductBrand" id="modalProductBrand"></p>
                    <p class="mt-3" id="modalProductName"></p>
                    <div class="modalDescription" id="modalDescription"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">確認刪除</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="deleteModalBrandName"></p>
                    <p id="deleteModalProductName"></p>
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
        document.addEventListener('DOMContentLoaded', function() {
            var productModal = document.getElementById('productModal');
            productModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var productId = button.getAttribute('data-product-id');
                var brandName = button.getAttribute('data-brand-name');
                var productName = button.getAttribute('data-product-name');
                var description = button.getAttribute('data-description');

                document.getElementById('modalProductId').textContent = "商品編碼:  " + productId;
                document.getElementById('modalProductBrand').textContent = " 品牌名稱: " + brandName;
                document.getElementById('modalProductName').textContent = "商品名稱: " + productName;
                document.getElementById('modalDescription').textContent = description;

                // 更新確認訊息
                var confirmationMessage = "確定要刪除此商品嗎？ 即將刪除的商品: " + brandName + " - " + productName;
                document.getElementById('deleteConfirmationText').textContent = confirmationMessage;

                // 設置確認刪除按鈕的連結
                var confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
                confirmDeleteBtn.href = "doDeleteProduct.php?id=" + productId;
            });
            var deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var productId = button.getAttribute('data-delete-id');
                var brandName = button.getAttribute('data-brand-name');
                var productName = button.getAttribute('data-product-name');



                // 更新確認訊息
                var confirmationMessage = "確定要刪除此商品嗎？<br>即將刪除的商品：" + "（" + brandName + "）" + productName;
                document.getElementById('deleteConfirmationText').innerHTML = confirmationMessage;

                // 設置確認刪除按鈕的連結
                var confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
                confirmDeleteBtn.href = "doDeleteProduct.php?id=" + productId;
            });
        });
    </script>
</body>
<?php $conn->close(); ?>

</html>
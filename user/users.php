<?php

// 整合頁面
include "../vars.php";
$cateNum = 1;
$pageTitle = "{$cate_ary[$cateNum]}";
include "../template_top.php";
include "../template_nav.php";
include("../js.php");

// 抓登入資訊
// session_start();
// if (!isset($_SESSION["user"])) {
//     header("location:userLogin.php");
//     exit;
// }

require_once("../db_connect.php");

// 頁面基礎 SQL
$sql = "SELECT users.*, address_city.address_city_name, address_cityarea.address_cityarea_name,user_level.level_name,user_type.type_name
    FROM users
    LEFT JOIN address_city ON users.address_city_id = address_city.address_city_id
    LEFT JOIN address_cityarea ON users.address_cityarea_id = address_cityarea.address_cityarea_id
    LEFT JOIN user_level ON users.level_id = user_level.level_id
    LEFT JOIN user_type ON users.type_id = user_type.type_id
    WHERE is_delete=0";

// 執行
$result = $conn->query($sql);

// 檢查查詢是否成功
if (!$result) {
    die("Query failed: " . $conn->error . " SQL: " . $sql);
}

// 計算使用者數量
$userCount = $result->num_rows;

// 設定分頁
$page = isset($_GET["p"]) ? intval($_GET["p"]) : 1;
$per_page = 5;
$start_item = ($page - 1) * $per_page;

// 設定搜尋條件 SQL
$conditions = [];

// 使用者名稱
if (isset($_GET["search_name"]) && !empty(trim($_GET["search_name"]))) {
    $search_name = trim($_GET["search_name"]);
    $conditions[] = "user_name LIKE '%$search_name%'";
}

// 使用者帳號
if (isset($_GET["search_account"]) && !empty(trim($_GET["search_account"]))) {
    $search_account = trim($_GET["search_account"]);
    $conditions[] = "account LIKE '%$search_account%'";
}

// 其他欄位&關鍵字
if (isset($_GET["search_column_name"]) && isset($_GET["search_column"]) && !empty(trim($_GET["search_column"]))) {
    $search_column_name = trim($_GET["search_column_name"]);
    $search_column = trim($_GET["search_column"]);

    switch ($search_column_name) {
        case '1':
            $conditions[] = "phone LIKE '%$search_column%'";
            break;
        case '2':
            $conditions[] = "email LIKE '%$search_column%'";
            break;
        case '3':
            $conditions[] = "address_city_name LIKE '%$search_column%'";
            break;
        case '4':
            $conditions[] = "address_cityarea_name LIKE '%$search_column%'";
            break;
        case '5':
            $conditions[] = "address_street	 LIKE '%$search_column%'";
            break;
    }
}

// 使用者等級
if (isset($_GET["level_name"]) && !empty(trim($_GET["level_name"]))) {
    $level_name = trim($_GET["level_name"]);
    $conditions[] = "user_level.level_name LIKE '$level_name'";
}

// 使用者類別
if (isset($_GET["type_name"]) && !empty(trim($_GET["type_name"]))) {
    $type_name = trim($_GET["type_name"]);
    $conditions[] = "user_type.type_name = '$type_name'";
}

// 帳號狀態
if (isset($_GET["valid"]) && !empty(trim($_GET["valid"]))) {
    $valid = trim($_GET["valid"]);
    $conditions[] = "users.valid = '$valid'";
}

// 將搜尋條件加到 SQL 查詢中
if (count($conditions) > 0) {
    $sql .= " AND " . implode(" AND ", $conditions);
}

// 設定排序
$order = isset($_GET["order"]) ? intval($_GET["order"]) : 1;

switch ($order) {
    case 1:
        $sql .= " ORDER BY id DESC";
        break;
    case 2:
        $sql .= " ORDER BY user_name ASC";
        break;
    case 3:
        $sql .= " ORDER BY user_name DESC";
        break;
    case 4:
        $sql .= " ORDER BY account ASC";
        break;
    case 5:
        $sql .= " ORDER BY account DESC";
        break;
    case 6:
        $sql .= " ORDER BY phone ASC";
        break;
    case 7:
        $sql .= " ORDER BY phone DESC";
        break;
    case 8:
        $sql .= " ORDER BY email ASC";
        break;
    case 9:
        $sql .= " ORDER BY email DESC";
        break;
    default:
        header("location: users.php?p=1&order=1");
        exit;
}

// 分頁 SQL
$sql .= " LIMIT $start_item, $per_page";

// 執行
$result = $conn->query($sql);

// 檢查查詢是否成功
if (!$result) {
    die("Query failed: " . $conn->error . " SQL: " . $sql);
}

// 根據搜尋條件計算總頁數
if (count($conditions) > 0) {
    // 如果有搜尋條件，重新計算符合條件的總記錄數
    $sqlCount = "
        SELECT COUNT(*) as total 
        FROM users
        LEFT JOIN address_city ON users.address_city_id = address_city.address_city_id
        LEFT JOIN address_cityarea ON users.address_cityarea_id = address_cityarea.address_cityarea_id
        LEFT JOIN user_level ON users.level_id = user_level.level_id
        LEFT JOIN user_type ON users.type_id = user_type.type_id
        WHERE is_delete=0 AND " . implode(" AND ", $conditions);

    // 執行
    $resultCount = $conn->query($sqlCount);

    // 檢查查詢是否成功
    if (!$resultCount) {
        die("Query failed: " . $conn->error . " SQL: " . $sqlCount);
    }

    // 取得內容
    $rowCount = $resultCount->fetch_assoc();
    $userCount = $rowCount['total'];
    $total_page = ceil($userCount / $per_page);
} else {
    $userCount = $userCount;
    $total_page = ceil($userCount / $per_page);
}

// echo $sql; // 調試用，確認 SQL 查詢是否正確

?>
<!doctype html>
<html lang="en">

<head>
    <title>使用者管理</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <!-- 移除空白 -->
    <script>
        function trimInputFields() {
            const inputs = document.querySelectorAll('input[type="search"]');
            inputs.forEach(input => {
                input.value = input.value.trim();
            });
        }
    </script>

    <main class="main-content pb-3">
        <div class="pt-3">
            <div class="p-3 bg-white shadow rounded-2 mb-4 border">
                <div class="py-2">
                    <h4>使用者管理</h4>

                    <!-- 搜尋欄位 -->
                    <form action="" method="get" class="searchbar row row-cols-sm-1 row-cols-md-3 row-cols-lg-4 g-2 " onsubmit="trimInputFields()">

                        <div class="col form-floating">
                            <input type="search" class="form-control" name="search_name" placeholder="請輸入使用者名稱" value="<?= isset($_GET["search_name"]) ? trim($_GET["search_name"]) : '' ?>">
                            <label for="search_name" class="form-label">使用者名稱</label>
                        </div>

                        <div class="col form-floating">
                            <input type="search" class="form-control" name="search_account" placeholder="請輸入使用者帳號" value="<?= isset($_GET["search_account"]) ? trim($_GET["search_account"]) : '' ?>">
                            <label for="search_account" class="form-label">使用者帳號</label>
                        </div>

                        <div class="col form-floating">
                            <select class="form-select" id="floatingSelect" name="search_column_name">
                                <option value="" selected>請選擇欄位名稱</option>
                                <option value="1" <?php echo isset($_GET['search_column_name']) && $_GET['search_column_name'] == '1' ? 'selected' : ''; ?>>電話</option>
                                <option value="2" <?php echo isset($_GET['search_column_name']) && $_GET['search_column_name'] == '2' ? 'selected' : ''; ?>>信箱</option>
                                <option value="3" <?php echo isset($_GET['search_column_name']) && $_GET['search_column_name'] == '3' ? 'selected' : ''; ?>>縣市</option>
                                <option value="4" <?php echo isset($_GET['search_column_name']) && $_GET['search_column_name'] == '4' ? 'selected' : ''; ?>>行政區</option>
                                <option value="5" <?php echo isset($_GET['search_column_name']) && $_GET['search_column_name'] == '5' ? 'selected' : ''; ?>>街道</option>
                            </select>
                            <label for="search_column_name">其他欄位</label>
                        </div>

                        <div class="col form-floating">
                            <input type="search" class="form-control" name="search_column" placeholder="請輸入關鍵字" value="<?= isset($_GET["search_column"]) ? trim($_GET["search_column"]) : '' ?>">
                            <label class="form-label">關鍵字</label>
                        </div>

                        <!-- 使用者等級 -->
                        <div class="col my-3">
                            <p class="form-label">使用者等級</p>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="level_name" id="inlineRadio1" value="金" <?php echo (isset($_GET['level_name']) && $_GET['level_name'] === '金') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="inlineRadio1">金</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="level_name" id="inlineRadio2" value="銀" <?php echo (isset($_GET['level_name']) && $_GET['level_name'] === '銀') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="inlineRadio2">銀</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="level_name" id="inlineRadio3" value="銅" <?php echo (isset($_GET['level_name']) && $_GET['level_name'] === '%銅%') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="inlineRadio3">銅</label>
                                </div>
                            </div>
                        </div>

                        <!-- 使用者類別 -->
                        <div class="col my-3">
                            <p class="form-label">使用者類別</p>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type_name" id="inlineRadio4" value="系統管理員"
                                        <?php if (isset($_GET['type_name']) && $_GET['type_name'] === '系統管理員') echo 'checked'; ?>>
                                    <label class="form-check-label" for="inlineRadio4">系統管理員</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type_name" id="inlineRadio5" value="一般會員"
                                        <?php if (isset($_GET['type_name']) && $_GET['type_name'] === '一般會員') echo 'checked'; ?>>
                                    <label class="form-check-label" for="inlineRadio5">一般會員</label>
                                </div>
                            </div>
                        </div>

                        <!-- 帳號狀態 -->
                        <div class="col my-3">
                            <p class="form-label">帳號狀態</p>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="valid" id="inlineRadio6" value="Y" <?php if (isset($_GET['valid']) && $_GET['valid'] == '1') echo 'checked'; ?>>
                                    <label class="form-check-label" for="inlineRadio6">啟用</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="valid" id="inlineRadio7" value="N" <?php if (isset($_GET['valid']) && $_GET['valid'] == '0') echo 'checked'; ?>>
                                    <label class="form-check-label" for="inlineRadio7">停用</label>
                                </div>
                            </div>
                        </div>

                        <!-- 搜尋按鈕 -->
                        <div class="align-self-center">
                            <button type="submit" class="btn btn-primary">搜尋</button>
                            <a href="users.php" class="btn btn-dark">清除</a>

                        </div>
                    </form>
                </div>
            </div>

            <?php if ($userCount > 0) :
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                $index = $start_item + 1;
            ?>
                <!-- 使用者列表 -->
                <div class="bg-white shadow rounded-2 border">
                    <div class="table-title mb-3 d-flex justify-content-between align-items-center p-2 rounded-top">
                        <h6 class="m-0 text-primary ms-2">使用者列表 <span>(共有 <?= $userCount ?> 個使用者)</span></h6>
                        <a class="btn btn-primary me-2" href="adminAdd.php">新增管理員</a>
                    </div>
                    <div class="p-3">
                        <table class="coupon-table table table-bordered p-3">
                            <thead>
                                <tr>
                                    <th>編號</th>
                                    <th>使用者類別</th>
                                    <th>使用者等級</th>
                                    <th>帳號狀態</th>
                                    <th>使用者名稱
                                        <div class="btn-group">
                                            <a
                                                class="text-secondary ms-1 <?php if ($order == 2) echo "active" ?>"
                                                href="users.php?p=<?= $page ?>&order=2&search_name=<?= isset($_GET['search_name']) ? $_GET['search_name'] : '' ?>
                                &search_account=<?= isset($_GET['search_account']) ? $_GET['search_account'] : '' ?>
                                &search_column_name=<?= isset($_GET['search_column_name']) ? $_GET['search_column_name'] : '' ?>
                                &search_column=<?= isset($_GET['search_column']) ? $_GET['search_column'] : '' ?>
                                &level_name=<?= isset($_GET['level_name']) ? $_GET['level_name'] : '' ?>">
                                                <i class="fa-solid fa-sort-down"></i>
                                            </a>
                                            <a
                                                class="text-secondary ms-1 <?php if ($order == 3) echo "active" ?>"
                                                href="users.php?p=<?= $page ?>&order=3&search_name=<?= isset($_GET['search_name']) ? $_GET['search_name'] : '' ?>
                                &search_account=<?= isset($_GET['search_account']) ? $_GET['search_account'] : '' ?>
                                &search_column_name=<?= isset($_GET['search_column_name']) ? $_GET['search_column_name'] : '' ?>
                                &search_column=<?= isset($_GET['search_column']) ? $_GET['search_column'] : '' ?>
                                &level_name=<?= isset($_GET['level_name']) ? $_GET['level_name'] : '' ?>">
                                                <i class="fa-solid fa-sort-up"></i>
                                            </a>
                                        </div>
                                    </th>
                                    <th>使用者帳號
                                        <!-- 排序按鈕 -->
                                        <div class="btn-group">
                                            <a
                                                class="text-secondary ms-1 <?php if ($order == 4) echo "active" ?>"
                                                href="users.php?p=<?= $page ?>&order=4&search_name=<?= isset($_GET['search_name']) ? $_GET['search_name'] : '' ?>
                                &search_account=<?= isset($_GET['search_account']) ? $_GET['search_account'] : '' ?>
                                &search_column_name=<?= isset($_GET['search_column_name']) ? $_GET['search_column_name'] : '' ?>
                                &search_column=<?= isset($_GET['search_column']) ? $_GET['search_column'] : '' ?>
                                &level_name=<?= isset($_GET['level_name']) ? $_GET['level_name'] : '' ?>">
                                                <i class="fa-solid fa-sort-down"></i>
                                            </a>
                                            <a
                                                class="text-secondary ms-1 <?php if ($order == 5) echo "active" ?>"
                                                href="users.php?p=<?= $page ?>&order=5&search_name=<?= isset($_GET['search_name']) ? $_GET['search_name'] : '' ?>
                                &search_account=<?= isset($_GET['search_account']) ? $_GET['search_account'] : '' ?>
                                &search_column_name=<?= isset($_GET['search_column_name']) ? $_GET['search_column_name'] : '' ?>
                                &search_column=<?= isset($_GET['search_column']) ? $_GET['search_column'] : '' ?>
                                &level_name=<?= isset($_GET['level_name']) ? $_GET['level_name'] : '' ?>">
                                                <i class="fa-solid fa-sort-up"></i>
                                            </a>
                                        </div>
                                    </th>
                                    <th>使用者電話
                                        <!-- 排序按鈕 -->
                                        <div class="btn-group">
                                            <a
                                                class="text-secondary ms-1 <?php if ($order == 6) echo "active" ?>"
                                                href="users.php?p=<?= $page ?>&order=6&search_name=<?= isset($_GET['search_name']) ? $_GET['search_name'] : '' ?>
                                &search_account=<?= isset($_GET['search_account']) ? $_GET['search_account'] : '' ?>
                                &search_column_name=<?= isset($_GET['search_column_name']) ? $_GET['search_column_name'] : '' ?>
                                &search_column=<?= isset($_GET['search_column']) ? $_GET['search_column'] : '' ?>
                                &level_name=<?= isset($_GET['level_name']) ? $_GET['level_name'] : '' ?>">
                                                <i class="fa-solid fa-sort-down"></i>
                                            </a>
                                            <a
                                                class="text-secondary ms-1 <?php if ($order == 7) echo "active" ?>"
                                                href="users.php?p=<?= $page ?>&order=7&search_name=<?= isset($_GET['search_name']) ? $_GET['search_name'] : '' ?>
                                &search_account=<?= isset($_GET['search_account']) ? $_GET['search_account'] : '' ?>
                                &search_column_name=<?= isset($_GET['search_column_name']) ? $_GET['search_column_name'] : '' ?>
                                &search_column=<?= isset($_GET['search_column']) ? $_GET['search_column'] : '' ?>
                                &level_name=<?= isset($_GET['level_name']) ? $_GET['level_name'] : '' ?>">
                                                <i class="fa-solid fa-sort-up"></i>
                                            </a>
                                        </div>
                                    </th>
                                    <th>使用者信箱
                                        <!-- 排序按鈕 -->
                                        <div class="btn-group">
                                            <a
                                                class="text-secondary ms-1 <?php if ($order == 8) echo "active" ?>"
                                                href="users.php?p=<?= $page ?>&order=8&search_name=<?= isset($_GET['search_name']) ? $_GET['search_name'] : '' ?>
                                &search_account=<?= isset($_GET['search_account']) ? $_GET['search_account'] : '' ?>
                                &search_column_name=<?= isset($_GET['search_column_name']) ? $_GET['search_column_name'] : '' ?>
                                &search_column=<?= isset($_GET['search_column']) ? $_GET['search_column'] : '' ?>
                                &level_name=<?= isset($_GET['level_name']) ? $_GET['level_name'] : '' ?>">
                                                <i class="fa-solid fa-sort-down"></i>
                                            </a>
                                            <a
                                                class="text-secondary ms-1 <?php if ($order == 9) echo "active" ?>"
                                                href="users.php?p=<?= $page ?>&order=9&search_name=<?= isset($_GET['search_name']) ? $_GET['search_name'] : '' ?>
                                &search_account=<?= isset($_GET['search_account']) ? $_GET['search_account'] : '' ?>
                                &search_column_name=<?= isset($_GET['search_column_name']) ? $_GET['search_column_name'] : '' ?>
                                &search_column=<?= isset($_GET['search_column']) ? $_GET['search_column'] : '' ?>
                                &level_name=<?= isset($_GET['level_name']) ? $_GET['level_name'] : '' ?>">
                                                <i class="fa-solid fa-sort-up"></i>
                                            </a>
                                        </div>
                                    </th>
                                    <th>使用者地址</th>
                                    <th>功能項目</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php foreach ($rows as $user) : ?>
                                        <td><?= $index++ ?></td>
                                        <?php
                                        $type_class = '';
                                        switch ($user["type_name"]) {
                                            case '系統管理員':
                                                $type_class = 'badge bg-warning';  // 系統管理員
                                                break;
                                            case '一般會員':
                                                $type_class = 'badge bg-secondary';  // 一般會員
                                                break;
                                            default:
                                                $type_class = 'badge bg-dark';  // 黑
                                        }
                                        ?>
                                        <td><span class="<?= $type_class ?>"><?= $user["type_name"] ?></span></td>
                                        <?php
                                        $level_class = '';
                                        switch ($user["level_name"]) {
                                            case '金':
                                                $level_class = 'badge bg-warning text-dark';  // 金
                                                break;
                                            case '銀':
                                                $level_class = 'badge bg-secondary';  // 銀
                                                break;
                                            case '銅':
                                                $level_class = 'badge bg-info';  // 銅
                                                break;
                                            default:
                                                $level_class = 'badge bg-dark';  // 黑
                                        }
                                        ?>
                                        <td><span class="<?= $level_class ?>"><?= $user["level_name"] ?></span></td>
                                        <?php
                                        $valid_class = '';
                                        $valid_content = '';
                                        switch ($user["valid"]) {
                                            case 'N':
                                                $valid_class = 'badge bg-danger';
                                                $valid_content = '停用';
                                                // 停用
                                                break;
                                            case 'Y':
                                                $valid_class = 'badge bg-success';
                                                $valid_content = '啟用';
                                                // 啟用
                                                break;
                                            default:
                                                $valid_class = 'badge bg-dark';
                                                $valid_content = '尚未設定';
                                                // 黑
                                        }
                                        ?>
                                        <td><span class="<?= $valid_class ?>"><?= $valid_content ?></span></td>
                                        <td><?= $user["user_name"] ?></td>
                                        <td><?= $user["account"] ?></td>
                                        <td><?= "0" . $user["phone"] ?></td>
                                        <td><?= $user["email"] ?></td>

                                        <!-- 地址 -->
                                        <?php
                                        // 組合完整的地址
                                        $city_name = isset($user["address_city_name"]) ? $user["address_city_name"] : '尚未設定';
                                        $area_name = isset($user["address_cityarea_name"]) ? $user["address_cityarea_name"] : '';
                                        $street = isset($user["address_street"]) ? $user["address_street"] : '';
                                        $address = $city_name . $area_name . $street;
                                        ?>
                                        <td><?= $address ?></td>

                                        <!-- 功能項目 -->
                                        <td class="d-flex justify-content-evenly">
                                            <a class="text-dark view-user-btn" href="#" data-bs-toggle="modal" data-bs-target="#viewUserModal" data-id="<?= $user["id"] ?>">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a class="text-dark" href="userEdit.php?id=<?= $user["id"] ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <a class="text-dark" href="#" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $user["id"] ?>" data-name="<?= $user["user_name"] ?>" data-account="<?= $user["account"] ?>"><i class="fa-solid fa-trash-can"></i></a>
                                        </td>


                                </tr>
                            <?php endforeach; ?>

                            </tbody>
                        </table>

                        <!-- 檢視視窗 -->
                        <div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="viewUserModalLabel">使用者管理/檢視</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- 這裡將動態載入 userView.php 的內容 -->
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- 刪除確認視窗 -->
                        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel">確認刪除</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- 這裡的內容將由 JavaScript 動態設置 -->
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                        <button type="button" class="btn btn-danger" id="confirmDelete">確認刪除</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 分頁 -->
                        <?php if ($total_page > 1) : ?>
                            <nav aria-label="Page navigation example">
                                <ul class="pagination">
                                    <?php for ($i = 1; $i <= $total_page; $i++) : ?>
                                        <li class="page-item <?php if ($page == $i) echo "active" ?>">
                                            <a class="page-link" href="users.php?p=<?= $i ?>&order=<?= $order ?>
            &search_name=<?= urlencode($_GET['search_name'] ?? '') ?>
            &search_account=<?= urlencode($_GET['search_account'] ?? '') ?>
            &search_column_name=<?= urlencode($_GET['search_column_name'] ?? '') ?>
            &search_column=<?= urlencode($_GET['search_column'] ?? '') ?>
            &level_name=<?= urlencode($_GET['level_name'] ?? '') ?>
            &type_name=<?= urlencode($_GET['type_name'] ?? '') ?>
            &valid=<?= urlencode($_GET['valid'] ?? '') ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    <?php else : ?>
                        <p>無相符使用者資料</p>
                    <?php endif; ?>
                    </div>
                </div>
        </div>

    </main>

    <script>
        document.querySelectorAll('.view-user-btn').forEach(button => {
            button.addEventListener('click', function() {
                var userId = this.getAttribute('data-id');

                // 發送 AJAX 請求獲取 userView.php 的內容
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'userView.php?id=' + userId, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        document.querySelector('#viewUserModal .modal-body').innerHTML = xhr.responseText;
                    } else {
                        document.querySelector('#viewUserModal .modal-body').innerHTML = '<p class="text-danger">無法加載內容，請重試。</p>';
                    }
                };
                xhr.send();
            });
        });
    </script>

    <script>
        var deleteUser = '';

        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var userId = button.getAttribute('data-id');
            var userName = button.getAttribute('data-name');
            var userAccount = button.getAttribute('data-account');

            deleteUser = 'doDeleteUser.php?id=' + userId;

            // 動態設置 Modal 內的使用者名稱和帳號
            var modalBody = deleteModal.querySelector('.modal-body');
            modalBody.textContent = `確定要刪除此使用者 [${userName} (${userAccount})] 嗎？`;
        });

        document.getElementById('confirmDelete').addEventListener('click', function() {
            window.location.href = deleteUser;
        });
    </script>

</body>

<?php $conn->close(); ?>

</html>
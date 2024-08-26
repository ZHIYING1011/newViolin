<?php
include "../vars.php";
$cateNum = 5;
$pageTitle = "{$cate_ary[$cateNum]}";
include "../template_top.php";
include "../template_nav.php"; ?>
<?php
if (!isset($_GET["id"])) {
    echo "請正確帶入文章的 get id 變數";
    exit;
}


$id = $_GET["id"];
require_once("../db_connect.php");

$sql = "SELECT writing.*, users.user_name 
               FROM writing 
               JOIN users ON writing.user_id = users.id
WHERE writing.id='$id'";
$result = $conn->query($sql);
$articleCount = $result->num_rows;
$row = $result->fetch_assoc();

if ($articleCount > 0) {
    $title = $row["title"];
} else {
    $title = "該文章不存在";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <?php include("./css.php") ?>

</head>

<body>
    <main class="main-content pb-3">
    <div class="pt-3">
        <div>
            <button class="btn btn-primary" onclick="window.history.back()">
                <i class="fa-solid fa-angle-left"></i>
                返回上一頁
            </button>
        </div>
        <h3 class="mt-3 mb-3">檢視部落格</h3>
        <?php if ($articleCount > 0): ?>
            <div class="card mt-3">
                <div class="card-body">
                    <h2 class="card-title text-center"><?= $row["title"] ?></h2>
                    <h6 class="card-title text-secondary text-end">發佈者：<?= $row["user_name"] ?></h6>
                    <p class="mt-3 card-text"><?php echo nl2br($row["content"]); ?></p>
                </div>
            </div>
        <?php endif; ?>

    </div>
</main>
    <!-- 引入Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- 引入Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- 引入flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#timePicker", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
            });
        });
    </script>
</body>

<?php $conn->close(); ?>

</html>
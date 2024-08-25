<?php
if (!isset($_GET["id"])) {
    echo "請正確帶入文章的 get id 變數";
    exit;
}


$id = $_GET["id"];
require_once("../db_connect.php");

$sql = "SELECT id, title, category, content FROM writing
WHERE id='$id'";
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
    <div class="container my-4">
        <div class="">

            <button class="btn btn-primary" onclick="confirmBack()">
                <i class="fa-solid fa-angle-left"></i>
                返回上一頁
            </button>

        </div>
        <h3 class="mt-3 mb-3">編輯文章</h3>
        <?php if ($articleCount > 0): ?>
            <form action="doUpdateArticle.php" method="post">
                <div class="card mt-3">
                    <div class="card-body">
                        <table class="table table-borderless">
                            <input type="hidden" name="id" value="<?= $row["id"] ?>">
                            <tr>
                                <th class="col-1">標題</th>
                                <td><input type="text" class="form-control" name="title" value="<?= $row["title"] ?>"></td>
                            </tr>
                            <tr>
                                <th class="col-1">類別</th>
                                <td><input type="text" class="form-control" name="category" value="<?= $row["category"] ?>"></td>
                            </tr>
                            <tr>
                                <th class="col-1">內容</th>
                                <td><textarea type="text" class="form-control" name="content" rows="8"><?= $row["content"] ?></textarea></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="mt-3 mb-3 d-flex justify-content-end">
                    <!-- <input name="posted_at" type="text" id="timePicker" value=""> -->
                    <!-- <button class="btn btn-outline-primary ms-1" type="submit" name="action" value="scheduled">設定排程</button> -->
                    <button class="btn btn-outline-primary ms-3" type="submit" name="action" value="save">儲存</button>
                    <button class="btn btn-primary ms-3" type="submit" name="action" value="visible">立即發佈</button>
                </div>


            </form>
        <?php endif; ?>

    </div>

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
    <script>
        function confirmBack() {
            if (confirm('尚未儲存，確定要返回上一頁嗎？')) {
                history.back();
            }
        }
    </script>
</body>

<?php $conn->close(); ?>

</html>
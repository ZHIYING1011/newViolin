<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>文章&部落格管理</title>
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
        <h3 class="mt-3 mb-3">新增文章</h3>
        <form action="doAddArticle.php" method="post">
            <div class="form-floating mb-3">
                <input type="text" name="title" class="form-control">
                <label for="floating"><span class="text-danger">*</span>標題</label>
            </div>
            <div class="form-floating mb-3">
                <!-- <div class="input-group-text">類別</div> -->
                <input type="text" name="category" class="form-control">
                <label for="floating">類別</label>
            </div>
            <div class="mb-3">
                <textarea type="text" name="content" class="form-control" placeholder="請輸入內容" rows="8"></textarea>
            </div>
            <!-- <div class="mb-3 d-flex justify-content-end">
                <button class="btn btn-secondary ms-3" type="submit" name="action" value="draft">儲存為草稿</button>
            </div> -->
            <div class="mb-3 d-flex justify-content-end">
                <!-- <input name="posted_at" type="text" id="timePicker" placeholder="選擇日期與時間" value="2024-08-16 23:03">
                <button class="btn btn-outline-primary ms-1" type="submit" name="action" value="scheduled">設定排程</button> -->
                <button class="btn btn-secondary ms-3" type="submit" name="action" value="draft">儲存為草稿</button>
                <button class="btn btn-primary ms-3" type="submit" name="action" value="visible">立即發佈</button>
            </div>
        </form>

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
            // 顯示確認對話框
            if (confirm('確定要返回上一頁嗎？')) {
                // 如果用戶點擊確認，返回上一頁
                history.back();
            }
        }
    </script>
</body>

</html>
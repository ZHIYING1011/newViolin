<?php
include "../vars.php";
$cateNum = 5;
$pageTitle = "{$cate_ary[$cateNum]}";
include "../template_top.php";
include "../template_nav.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>文章&部落格管理</title>
    <?php include("./css.php") ?>
    <?php include("./writingCatch.php") ?>
    <link rel="stylesheet" href="../style.css">
    <style>
        a {
            text-decoration: none;
        }
    </style>

</head>

<body>
    <main class="main-content pb-3">
        <div class="pt-3">
            <h3 class="">文章&部落格管理</h3>
            <div class="mt-3 card text-center">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                        <li class="nav-item tabcss" role="presentation">
                            <button class="nav-link <?= ($tabId === 'article' || $tabId === null) ? 'active' : '' ?> " id="article-tab" data-bs-toggle="tab" data-bs-target="#article"
                                type="button" role="tab" aria-controls="article" aria-selected="true">文章列表</button>
                        </li>
                        <li class="nav-item tabcss" role="presentation">
                            <button class="nav-link <?= ($tabId === 'blog') ? 'active' : '' ?>" id="blog-tab" data-bs-toggle="tab" data-bs-target="#blog" type="button"
                                role="tab" aria-controls="blog" aria-selected="false">部落格列表</button>
                        </li>
                    </ul>
                </div>


                <div class="card-body">
                    <div class="tab-content mt-3" id="myTabContent">
                        <!-- 文章列表 -->
                        <div class="tab-pane fade show active" id="article" role="tabpanel" aria-labelledby="article-tab">
                            <!-- 文章列表 Tab -->
                            <form class="row g-3" action="" method="get">
                                <input type="hidden" name="tab" value="article">
                                <input type="hidden" name="pArticle" value="<?= $pageArticle ?>">
                                <div class="col-3 form-floating">
                                    <input type="search" class="form-control" placeholder="搜尋文章標題" name="searchArticleTitle" value="<?php echo isset($_GET["searchArticleTitle"]) ? $_GET["searchArticleTitle"] : "" ?>">
                                    <label for="floating">標題</label>
                                </div>
                                <div class="col-3 form-floating">
                                    <input type="search" class="form-control" placeholder="搜尋文章類別" name="searchArticleCategory" value="<?php echo isset($_GET["searchArticleCategory"]) ? $_GET["searchArticleCategory"] : "" ?>">
                                    <label for="floating">類別</label>
                                </div>
                                <!-- <div class="col-3 form-floating">
                                <input type="search" class="form-control" placeholder="userName" name="userName">
                                <label for="floating">發佈者</label>
                            </div> -->
                                <!-- flatpickr range 日曆 待試-->
                                <!-- <div class="col-3 form-floating">
                                <input type="date" class="form-control" name="start" value="<?= $start ?>">
                                <label for="floatingCouponSid">建立時間</label>
                            </div>
                            <div class="col-3 form-floating">
                                <input type="date" class="form-control" name="end" value="<?= $end ?>">
                                <label for="floatingCouponSid">修改時間</label>
                            </div>
                            <div class="col-3 form-floating">
                                <input type="date" class="form-control" name="end" value="<?= $end ?>">
                                <label for="floatingCouponSid">發佈時間</label>
                            </div> -->
                                <!-- <div class="col-md-3 form-floating">
                                <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                                    <option selected>請選擇狀態</option>
                                    <option value="1">草稿</option>
                                    <option value="2">已發布</option>
                                    <option value="3">已排程</option>
                                </select>
                                <label for="floatingSelect">狀態</label>
                            </div> -->
                                <div class="col d-flex justify-content-end">
                                    <button class="ms-2 btn btn-primary my-2 my-sm-0" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                                    <!-- <button class="ms-2 btn btn-danger my-2 my-sm-0" type="">清除</button> -->
                                </div>
                            </form>
                            <div class="d-flex mt-5 justify-content-end">
                                <a href="./addArticle.php"><button class="btn btn-primary">新增文章</button></a>
                                <!-- <button class="btn btn-danger">垃圾桶</button> -->
                            </div>
                            <?php if ($articleCountAll > 0):
                                $rows = $resultArticle->fetch_all(MYSQLI_ASSOC);
                            ?>
                                <div class="d-flex mt-3 justify-content-end">
                                    1-10 列 (共 <?= $articleCountAll ?> 列)
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered mt-3 table-hover ">
                                        <thead>
                                            <tr>
                                                <!-- <th scope="col" class="text-nowrap"><input type="checkbox" class="text-nowrap"></th> -->
                                                <th scope="col" class="text-nowrap">文章標題</th>
                                                <th scope="col" class="text-nowrap">類別</th>
                                                <!-- <th scope="col" class="text-nowrap">發佈者</th> -->
                                                <th scope="col" class="text-nowrap">建立時間</th>
                                                <th scope="col" class="text-nowrap">修改時間</th>
                                                <th scope="col" class="text-nowrap">發布時間</th>
                                                <th scope="col" class="text-nowrap">狀態</th>
                                                <th scope="col" class="text-nowrap">操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($rows as $article) : ?>
                                                <tr>
                                                    <!-- <td><input type="checkbox"></td> -->
                                                    <td><?= $article["title"] ?></td>
                                                    <td><?= $article["category"] ?></td>
                                                    <!-- <td><?= $article["user_name"] ?></td> -->
                                                    <td>
                                                        <?php if ($article["created_at"] == NULL) : ?>
                                                            -
                                                        <?php else: ?>
                                                            <?= $article["created_at"] ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($article["updated_at"] == NULL) : ?>
                                                            -
                                                        <?php else: ?>
                                                            <?= $article["updated_at"] ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($article["posted_at"] == NULL) : ?>
                                                            -
                                                        <?php else: ?>
                                                            <?= $article["posted_at"] ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        switch ($article["state"]) {
                                                            case "visible":
                                                                echo '<span class="badge bg-success">已發佈</span>';
                                                                break;
                                                                // case "scheduled":
                                                                //     echo '<span class="badge bg-warning">已排程</span>';
                                                                //     break;
                                                            case "draft":
                                                                echo '<span class="badge bg-secondary">草稿</span>';
                                                                break;
                                                            default:
                                                                echo '<span class="badge bg-danger">已刪除</span>';
                                                                break;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-nowrap">
                                                        <a href="article.php?id=<?= $article["id"] ?>">
                                                            <button class="btn btn-sm btncss"><i class="fa-solid fa-eye"></i></button>
                                                        </a>
                                                        <?php if ($article["state"] == "draft" || $article["state"] == "scheduled"): ?>
                                                            <a href="editArticle.php?id=<?= $article["id"] ?>">
                                                                <button class="btn btn-sm btncss"><i class="fa-solid fa-pen"></i></button>
                                                            </a>
                                                        <?php else: ?>
                                                            <button class="btn btn-sm btncss disabled"><i class="fa-solid fa-pen"></i></button>
                                                        <?php endif; ?>
                                                        <a class="delete-link" href="doSoftDeleteArticle.php?id=<?= $article["id"] ?>">
                                                            <button class="btn btn-sm btncss"><i class="fa-solid fa-trash"></i></button>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <table class="table table-bordered mt-3 table-hover ">
                                        <thead>
                                            <tr>
                                                <!-- <th scope="col" class="text-nowrap"><input type="checkbox" class="text-nowrap"></th> -->
                                                <th scope="col" class="text-nowrap">文章標題</th>
                                                <th scope="col" class="text-nowrap">類別</th>
                                                <!-- <th scope="col" class="text-nowrap">發佈者</th> -->
                                                <th scope="col" class="text-nowrap">建立時間</th>
                                                <th scope="col" class="text-nowrap">修改時間</th>
                                                <th scope="col" class="text-nowrap">發布時間</th>
                                                <th scope="col" class="text-nowrap">狀態</th>
                                                <th scope="col" class="text-nowrap">操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="9" class="text-center">目前沒有文章</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                                </div>

                                <?php if ($total_pageArticle > 0): ?>
                                    <nav aria-label="Page navigation example mt-3">
                                        <ul class="pagination justify-content-center">
                                            <?php for ($i = 1; $i <= $total_pageArticle; $i++): ?>
                                                <li class="page-item <?= ($pageArticle == $i) ? 'active' : '' ?>">
                                                    <a class="page-link" href="?tab=article&pArticle=<?= $i ?>&searchArticleTitle=<?= urlencode($searchArticleTitle) ?>"><?= $i ?></a>
                                                </li>
                                            <?php endfor; ?>
                                        </ul>
                                    </nav>
                                <?php endif; ?>
                        </div>
                        <!-- 部落格列表 -->
                        <div class="tab-pane fade" id="blog" role="tabpanel" aria-labelledby="blog-tab">
                            <!-- 部落格列表 Tab -->
                            <form class="row g-3" action="" method="get">
                                <div class="col-3 form-floating">
                                    <input type="hidden" name="tab" value="blog">
                                    <input type="hidden" name="pBlog" value="<?= $pageBlog ?>">
                                    <input type="search" class="form-control" placeholder="搜尋部落格標題" name="searchBlogTitle" value="<?php echo isset($_GET["searchBlogTitle"]) ? $_GET["searchBlogTitle"] : "" ?>">
                                    <label for="floating">標題</label>
                                </div>
                                <div class="col-3 form-floating">
                                    <input type="search" class="form-control" placeholder="articleName" name="searchBlogContent" value="<?php echo isset($_GET["searchBlogContent"]) ? $_GET["searchBlogContent"] : "" ?>">
                                    <label for="floating">內容</label>
                                </div>
                                <div class="col-3 form-floating">
                                    <input type="search" class="form-control" placeholder="userName" name="searchBlogUsername" value="<?php echo isset($_GET["searchBlogUsername"]) ? $_GET["searchBlogUsername"] : "" ?>">
                                    <label for="floating">發佈者</label>
                                </div>
                                <!-- flatpickr range 日曆 待試-->
                                <!-- <div class="col-3 form-floating">
                                <input type="date" class="form-control" name="searchBlogPostedAt" value="<?php echo isset($_GET["searchBlogPostedAt"]) ? $_GET["searchBlogPostedAt"] : "" ?>">
                                <label for="floatingCouponSid">發佈時間</label>
                            </div> -->
                                <div class="col d-flex justify-content-end">
                                    <button class="ms-2 btn btn-primary my-2 my-sm-0" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                                    <!-- <button class="ms-2 btn btn-danger my-2 my-sm-0" type="submit">清除</button> -->
                                </div>
                            </form>
                            <?php if ($blogCountAll > 0):
                                $rows = $resultBlog->fetch_all(MYSQLI_ASSOC);
                            ?>
                                <div class="d-flex mt-5 justify-content-end">
                                    1-10 列 (共 <?= $blogCountAll ?> 列)
                                </div>
                                <div class="table-responsive-xl">
                                    <table class="table table-bordered mt-3 table-hover">
                                        <thead>
                                            <tr>
                                                <!-- <th scope="col" class="text-nowrap"><input type="checkbox"></th> -->
                                                <th scope="col" class="text-nowrap">部落格標題</th>
                                                <th scope="col" class="text-nowrap">部落格內容</th>
                                                <th scope="col" class="text-nowrap">發佈者</th>
                                                <th scope="col" class="text-nowrap">發布時間</th>
                                                <th scope="col" class="text-nowrap">操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($rows as $blog) : ?>
                                                <tr>
                                                    <!-- <td><input type="checkbox"></td> -->
                                                    <td><?= $blog["title"] ?></td>
                                                    <td><?= $blog["content"] ?></td>
                                                    <td><?= $blog["user_name"] ?></td>
                                                    <td><?= $blog["posted_at"] ?></td>
                                                    <td class="text-nowrap">
                                                        <a href="blog.php?id=<?= $blog["id"] ?>">
                                                            <button class="btn btn-sm btncss"><i class="fa-solid fa-eye"></i></button>
                                                        </a>
                                                        <a class="delete-link" href="doSoftDeleteBlog.php?id=<?= $blog["id"] ?>">
                                                            <button class="btn btn-sm btncss"><i class="fa-solid fa-trash"></i></button>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <table class="table table-bordered mt-3 table-hover ">
                                        <thead>
                                            <tr>
                                                <!-- <th scope="col" class="text-nowrap"><input type="checkbox"></th> -->
                                                <th scope="col" class="text-nowrap">文章標題</th>
                                                <th scope="col" class="text-nowrap">留言內容</th>
                                                <th scope="col" class="text-nowrap">發佈者</th>
                                                <th scope="col" class="text-nowrap">發布時間</th>
                                                <th scope="col" class="text-nowrap">操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="9" class="text-center">目前沒有部落格</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                                </div>

                                <!-- <div class="d-flex justify-content-end mt-3">
                                <button class="btn btn-danger">垃圾桶</button>
                            </div> -->
                                <?php if ($total_pageBlog > 0): ?>
                                    <nav aria-label="Page navigation example mt-3">
                                        <ul class="pagination justify-content-center">
                                            <?php for ($i = 1; $i <= $total_pageBlog; $i++): ?>
                                                <li class="page-item <?= ($pageBlog == $i) ? 'active' : '' ?>">
                                                    <a class="page-link" href="?tab=blog&pBlog=<?= $i ?>&searchBlogTitle=<?= urlencode($searchBlogTitle) ?>"><?= $i ?></a>
                                                </li>
                                            <?php endfor; ?>
                                        </ul>
                                    </nav>
                                <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- 引入Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- 引入Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script>
  document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab') || 'article';
    const searchArticleTitle = urlParams.get('searchArticleTitle') || '';
    const searchBlogTitle = urlParams.get('searchBlogTitle') || '';

    // 激活正確的標籤頁
    const tabTrigger = document.querySelector(`#${activeTab}-tab`);
    if (tabTrigger) {
        const tab = new bootstrap.Tab(tabTrigger);
        tab.show();
    }

    // 監聽標籤頁切換事件
    const tabList = document.querySelectorAll('.nav-tabs .nav-link');
    tabList.forEach(tab => {
        tab.addEventListener('click', function() {
            const tabId = this.getAttribute('id').replace('-tab', '');
            const newUrl = new URL(window.location.href);
            newUrl.searchParams.set('tab', tabId);

            // 移除不相關的分頁參數
            if (tabId === 'article') {
                newUrl.searchParams.delete('pBlog');
            } else if (tabId === 'blog') {
                newUrl.searchParams.delete('pArticle');
            }

            // 更新 URL 並刷新頁面
            window.history.pushState({}, '', newUrl);
            window.location.reload();
        });
    });

    // 處理分頁連結點擊事件
    const pageLinks = document.querySelectorAll('.pagination .page-link');
    pageLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // 防止默認行為
            const page = this.getAttribute('href').split(/pArticle=|pBlog=/)[1];
            const tab = urlParams.get('tab') || 'article';
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('tab', tab);

            if (tab === 'article') {
                currentUrl.searchParams.set('pArticle', page);
            } else {
                currentUrl.searchParams.set('pBlog', page);
            }

            if (searchArticleTitle) {
                currentUrl.searchParams.set('searchArticleTitle', searchArticleTitle);
            }
            if (searchBlogTitle) {
                currentUrl.searchParams.set('searchBlogTitle', searchBlogTitle);
            }

            window.location.href = currentUrl;
        });
    });

    // 處理刪除連結
    const deleteLinks = document.querySelectorAll('.delete-link');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            const confirmed = confirm('確定要刪除嗎？');
            if (!confirmed) {
                event.preventDefault(); // 取消默認行為
            }
        });
    });
});

    </script>

</body>

</html>
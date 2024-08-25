<?php
require_once("../db_connect.php");

// 設置每頁顯示的資料數量
$per_page = 10;

// 初始化頁數和起始項目
$pageArticle = isset($_GET["pArticle"]) ? (int)$_GET["pArticle"] : 1;
$pageBlog = isset($_GET["pBlog"]) ? (int)$_GET["pBlog"] : 1;

// 確保頁數不小於1
$pageArticle = max($pageArticle, 1);
$pageBlog = max($pageBlog, 1);

$startItemArticle = ($pageArticle - 1) * $per_page;
$startItemBlog = ($pageBlog - 1) * $per_page;

// 處理搜尋條件
$searchArticleTitle = isset($_GET["searchArticleTitle"]) ? $_GET["searchArticleTitle"] : '';
$searchArticleCategory = isset($_GET["searchArticleCategory"]) ? $_GET["searchArticleCategory"] : '';
$searchBlogTitle = isset($_GET["searchBlogTitle"]) ? $_GET["searchBlogTitle"] : '';
$searchBlogContent = isset($_GET["searchBlogContent"]) ? $_GET["searchBlogContent"] : '';
$searchBlogUsername = isset($_GET["searchBlogUsername"]) ? $_GET["searchBlogUsername"] : '';

// 搜尋及查詢文章
$sqlArticle = "SELECT writing.*, users.user_name 
               FROM writing 
               JOIN users ON writing.user_id = users.id
               WHERE users.user_type = 1 AND writing.valid=1 
               AND writing.title LIKE '%$searchArticleTitle%' AND writing.category LIKE '%$searchArticleCategory%'
               ORDER BY writing.created_at DESC
               LIMIT $startItemArticle, $per_page";
$sqlArticleCount = "SELECT COUNT(*) as count 
                    FROM writing 
                    JOIN users ON writing.user_id = users.id
                    WHERE users.user_type = 1 AND writing.valid=1 
                    AND writing.title LIKE '%$searchArticleTitle%' AND writing.category LIKE '%$searchArticleCategory%'";

$resultArticle = $conn->query($sqlArticle);
$articleCountAll = $conn->query($sqlArticleCount)->fetch_assoc()['count'];
$total_pageArticle = ceil($articleCountAll / $per_page);

// 搜尋及查詢部落格
$sqlBlog = "SELECT writing.*, users.user_name 
            FROM writing 
            JOIN users ON writing.user_id = users.id
            WHERE users.user_type = 2 AND writing.valid=1 
            AND writing.title LIKE '%$searchBlogTitle%' AND writing.content LIKE '%$searchBlogContent%' AND users.user_name LIKE '%$searchBlogUsername%'
            ORDER BY writing.posted_at DESC
            LIMIT $startItemBlog, $per_page";
$sqlBlogCount = "SELECT COUNT(*) as count 
                 FROM writing 
                 JOIN users ON writing.user_id = users.id
                 WHERE users.user_type = 2 AND writing.valid=1 
                 AND writing.title LIKE '%$searchBlogTitle%' AND writing.content LIKE '%$searchBlogContent%' AND users.user_name LIKE '%$searchBlogUsername%'";

$resultBlog = $conn->query($sqlBlog);
$blogCountAll = $conn->query($sqlBlogCount)->fetch_assoc()['count'];
$total_pageBlog = ceil($blogCountAll / $per_page);

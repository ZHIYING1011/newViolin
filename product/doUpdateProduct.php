<?php

require_once("../db_connect.php");

if (!isset($_POST["id"])) {
    echo "請循正常管道進入此頁";
    exit;
}

$id = $_POST["id"];
$productName = $_POST["productName"];
$productId = $_POST["productId"];
$price = $_POST["price"];
$brand = $_POST["brand"];
$stock = $_POST["stock"];
$isActive = $_POST["isActive"];
$category = $_POST["category"];
$description = $_POST["description"];
$existingPictureName = $_POST["existingPictureName"];

// 更新產品資料
$sql = "UPDATE product 
        SET product_name='$productName', product_id='$productId', price='$price', brand_id='$brand', stock='$stock', is_active='$isActive', category_id='$category', description='$description' 
        WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    // 處理圖片
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        // 將舊圖片標記為無效
        if (!empty($existingPictureName)) {
            $updatePicSql = "UPDATE picture SET valid = 0 WHERE picture_url = '$existingPictureName' AND product_id = '$id'";
            if ($conn->query($updatePicSql) !== TRUE) {
                echo "錯誤: " . $conn->error;
                $conn->close();
                exit;
            }
        }

        // 設定目標資料夾路徑
        $brandNames = [
            '1' => 'ISVA',
            '2' => 'BORYA',
            '3' => 'Franz Kirschnek',
            '4' => 'LienViolins',
            '5' => 'David Lien',
            '6' => 'Peter Siegfried Heffler',
            '7' => 'Rainer W. Leonhardt',
            '8' => 'Heinrich Gill'
        ];
        $brandName = $brandNames[$brand];
        $targetDir = __DIR__ . "/../product/" . $brandName . "/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileTmpPath = $_FILES['picture']['tmp_name'];
        $fileName = $_FILES['picture']['name'];
        $fileInfo = pathinfo($fileName);
        $fileExtension = strtolower($fileInfo['extension']);
        
        // 檢查檔案類型
        $allowedExtensions = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($fileExtension, $allowedExtensions)) {
            echo "只允許上傳 JPG, JPEG, PNG, GIF 檔案";
            $conn->close();
            exit;
        }

        // 檢查檔案是否已存在，若存在則在檔名前加上時間戳
        if (file_exists($targetDir . $fileName)) {
            $fileName = $fileInfo['filename'] . '_' . time() . '.' . $fileInfo['extension'];
        }

        $destination = $targetDir . $fileName;

        // 移動檔案
        if (move_uploaded_file($fileTmpPath, $destination)) {
            // 將新圖片資訊寫入 picture 資料表
            $pictureSql = "INSERT INTO picture (product_id, picture_url, valid) VALUES ('$id', '$fileName', 1)";
            if ($conn->query($pictureSql) !== TRUE) {
                echo "錯誤: " . $conn->error;
                $conn->close();
                exit;
            }
        } else {
            echo "圖片上傳失敗";
            $conn->close();
            exit;
        }
    } else {
        // 如果沒有上傳新圖片，僅更新現有圖片為有效
        if (!empty($existingPictureName)) {
            $updatePicSql = "UPDATE picture SET valid = 1 WHERE picture_url = '$existingPictureName' AND product_id = '$id'";
            if ($conn->query($updatePicSql) !== TRUE) {
                echo "錯誤: " . $conn->error;
                $conn->close();
                exit;
            }
        }
    }

    header("Location: product-edit.php?id=$id&success=true");
} else {
    echo "錯誤: " . $conn->error;
}

$conn->close();
?>
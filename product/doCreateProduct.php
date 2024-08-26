<?php
require_once("../db_connect.php");

if (!isset($_POST["productName"])) {
    echo "請循正常管道進入此頁";
    exit;
}

$productName = $_POST["productName"];
$productId = $_POST["productId"];
$price = $_POST["price"];
$stock = $_POST["stock"];
$isActive = $_POST["isActive"];
$brand = $_POST["brand"];
$category = $_POST["category"];
$description = $_POST["description"];

// 將商品資料寫入product資料表
$sql = "INSERT INTO product (product_name, product_id, brand_id, category_id, price, stock, is_active, description, valid)
    VALUES ('$productName', '$productId', '$brand', '$category', '$price', '$stock', '$isActive', '$description', 1)";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => "success"]);
    $product_id = $conn->insert_id;
} else {
    echo json_encode(["status" => "error", "message" => "Error: " . $sql . "<br>" . $conn->error]);
}

// 將品牌數值轉換為品牌名稱
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

// 設定目標資料夾路徑並檢查是否存在
$targetDir = __DIR__ . "/../product/" . $brandName . "/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// 處理圖片上傳
if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['picture']['tmp_name'];
    $fileName = $_FILES['picture']['name'];
    
    // 檢查檔名是否已存在，若存在則在檔名前加上時間戳
    if (file_exists($targetDir . $fileName)) {
        $fileInfo = pathinfo($fileName);
        $fileName = $fileInfo['filename'] . '_' . time() . '.' . $fileInfo['extension'];
    }

    $destination = $targetDir . $fileName;
    
    if (move_uploaded_file($fileTmpPath, $destination)) {
        // 將圖片資訊寫入 picture 資料表
        $pictureUrl = $fileName;
        
        
        $sqlPicture = "INSERT INTO picture (product_id, picture_url) VALUES ('$product_id', '$pictureUrl')";
        if ($conn->query($sqlPicture) !== TRUE) {
            echo json_encode(["status" => "error", "message" => "Error: " . $sqlPicture . "<br>" . $conn->error]);
            $conn->close();
            exit;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "圖片上傳失敗"]);
        $conn->close();
        exit;
    }
}



$conn->close();
?>
<?php
require_once("../db_connect.php");

$response = array('success' => false, 'message' => '', 'newFileName' => '');

if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $tempName = $_FILES['image']['tmp_name'];
    $originalName = $_FILES['image']['name'];
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $newFileName = uniqid() . '.' . $extension;
    $uploadPath = './image/' . $newFileName;

    if (move_uploaded_file($tempName, $uploadPath)) {
        $currentImageId = $_POST['currentImageId'];
        $currentFileName = $_POST['currentFileName'];

        // 更新資料庫
        $stmt = $conn->prepare("UPDATE course_images SET file_name = ? WHERE id = ?");
        $stmt->bind_param("si", $newFileName, $currentImageId);

        if ($stmt->execute()) {
            // 刪除舊圖片
            if (file_exists('./image/' . $currentFileName)) {
                unlink('./image/' . $currentFileName);
            }

            $response['success'] = true;
            $response['message'] = '圖片上傳成功';
            $response['newFileName'] = $newFileName;
        } else {
            $response['message'] = '資料庫更新失敗';
        }

        $stmt->close();
    } else {
        $response['message'] = '圖片上傳失敗';
    }
} else {
    $response['message'] = '檔案上傳錯誤：' . $_FILES['image']['error'];
}

$conn->close();

echo json_encode($response);
?>
?>
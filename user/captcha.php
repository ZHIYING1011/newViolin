<?php
session_start();

function rand_str($length) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $str = '';
    $chars_length = strlen($chars);
    for ($i = 0; $i < $length; $i++) {
        $str .= $chars[mt_rand(0, $chars_length - 1)];
    }
    return $str;
}

function rand_color($image){
    return imagecolorallocate($image, rand(127, 255), rand(127, 255), rand(127, 255));
}

$image = imagecreate(220, 85);
imagecolorallocate($image, 0, 0, 0);

for ($i = 0; $i <= 9; $i++) {
    imageline($image, rand(0, 200), rand(0, 100), rand(0, 200), rand(0, 100), rand_color($image));
}

for ($i = 0; $i <= 100; $i++) {
    imagesetpixel($image, rand(0, 200), rand(0, 100), rand_color($image));
}

$length = 4; // 驗證碼長度
$str = rand_str($length); // 獲取驗證碼
$_SESSION['captcha'] = $str; // 存儲驗證碼到SESSION

$font = __DIR__ . '/simhei.ttf'; // 使用相對路徑
if (!file_exists($font)) {
    die('字型文件不存在');
}

for ($i = 0; $i < $length; $i++) {
    imagettftext($image, rand(20, 38), rand(-30, 30), $i * 50 + 25, rand(40, 70), rand_color($image), $font, $str[$i]);
}

header('Content-type:image/jpeg');
imagejpeg($image);
imagedestroy($image);

?>

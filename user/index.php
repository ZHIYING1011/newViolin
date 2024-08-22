<?php
include "../vars.php";
$cateNum = 1;
$cssList = ["../user/cate2.css"];
$jsList = ["../user/cate2.js"];
$pageTitle = "{$cate_ary[$cateNum]}";
include "../template_top.php";
include "../template_nav.php";
?>
<div class="myBtn">按鈕</div>
<?php
include "../template_btm.php";
?>
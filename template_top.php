<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <?php

  session_start();

  if (isset($cssList)) {
    foreach ($cssList as $css) {
  ?>
      <link rel="stylesheet" href="<?= $css ?>">
  <?php
    }
  }
  ?>
  <title><?= $pageTitle ?></title>
  <link rel="stylesheet" href="../style.css ">
</head>

<body>

  <div class="ps-md-3 ps-lg-5 mt-3 me-5">
    <header class="main-header d-flex justify-content-between bg-white align-items-center fixed-top shadow-sm">
      <a class="brand p-3 text-white text-decoration-none">Trivago</a>
      <!-- 跑版先註解 -->
      <!-- <div class="d-flex align-items-center">
        <p class="h5 ">
          <span>
            <?= $_SESSION["user"]["user_name"] . "(" . $_SESSION["user"]["account"] . ")" ?>
          </span> 您好
        </p>
        <a href="doLogout.php" class="btn btn-dark ms-3 me-3"><i class="fa-solid fa-right-from-bracket me-2 fa-fw"></i>登出</a>
      </div> -->
    </header>
    <h1><?= $pageTitle ?></h1>
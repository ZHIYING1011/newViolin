<?php

session_start();
if (isset($_SESSION["user"])) {
    header("location:users.php");
    exit;
}

if (isset($_SESSION["error"]["times"]) && $_SESSION["error"]["times"] > 5) {
    // 設置15秒的倒計時
    $countdown = 5;
}

?>
<!doctype html>
<html lang="en">

<head>
    <title>系統管理員登入</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <?php include("../css.php") ?>
    <?php include("../js.php") ?>


    <style>
        body {
            background: darkblue;
        }

        .sign-in-panel {
            width: 280px;
        }

        .logo {
            height: 64px;
        }

        h1 {
            color: white;
            text-shadow: black 0.1em 0.1em 0.2em
        }

        .input-area {
            .form-control:focus {
                position: relative;
                z-index: 1;
            }
        }
    </style>
</head>

<body>
    <div class="vh-100 d-flex justify-content-center align-items-center">
        <div class="sign-in-panel">

            <!-- 錯誤次數太多時顯示倒計時 -->
            <?php if (isset($_SESSION["error"]["times"]) && $_SESSION["error"]["times"] > 5) : ?>
                <h1>錯誤次數太多，請稍後再嘗試</h1>
                <p class="text-white">請稍等 <span id="countdown"><?= $countdown ?></span> 秒後重試</p>
                <script>
                    var countdownElement = document.getElementById('countdown');
                    var countdown = <?= $countdown ?>;
                    var interval = setInterval(function() {
                        countdown--;
                        countdownElement.textContent = countdown;
                        if (countdown <= 0) {
                            clearInterval(interval);
                            // 清除錯誤次數並重新導向登入畫面
                            window.location.href = 'resetErrorTimes.php';
                        }
                    }, 1000);
                </script>

            <?php else : ?>

                <h1 class="text-white">系統管理員登入</h1>

                <!-- 輸入欄位 -->
                <form action="doLogin.php" method="post">
                    <div class="input-area">

                        <!-- 帳號 -->
                        <div class="form-floating">
                            <input type="text" class="form-control" id="floatingInput" placeholder="請輸入帳號" name="account">
                            <label for="floatingInput">帳號</label>
                        </div>

                        <!-- 密碼 -->
                        <div class="form-floating">
                            <input type="password" class="form-control" id="floatingPassword" placeholder="請輸入密碼" name="password">
                            <label for="floatingPassword">密碼</label>
                        </div>

                        <!-- 驗證碼 -->
                        <div class="form-floating">
                            <input type="text" class="form-control" id="captcha" placeholder="請輸入驗證碼" name="captcha">
                            <label for="captcha">驗證碼</label>
                            <img class="mt-2 rounded" src="captcha.php" alt="驗證碼" onclick="this.src='captcha.php?'+Math.random();" title="點擊圖片刷新驗證碼">
                            <p class="text-white">*點擊圖片刷新驗證碼</p>
                        </div>
                    </div>

                    <!-- Sign in 按鈕 -->
                    <div class="d-grid">
                        <button class="btn btn-warning" type="submit">登入</button>
                    </div>
                </form>
            <?php endif; ?>

            <!-- copy right -->
            <div class="copy-right mt-4 text-light text-center">© 2017–2024</div>
        </div>
    </div>

    <!-- 錯誤訊息 Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">錯誤</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?= isset($_SESSION["error"]["message"]) ? $_SESSION["error"]["message"] : '' ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION["error"]["message"])) : ?>
        <script>
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'), {
                keyboard: false
            });
            errorModal.show();
        </script>
        <?php unset($_SESSION["error"]["message"]); ?>
    <?php endif; ?>

</body>

</html>

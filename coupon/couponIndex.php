<?php
include "../vars.php";
$cateNum = 0;
$pageTitle = "{$cate_ary[$cateNum]}";
include "../template_top.php";
include "../template_nav.php";
require_once("../db_connect.php");
?>

<head>
  <title>couponIndex</title>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="../style.css">

</head>

<body>
  <main class="main-content pb-3">
    <div class="pt-3">
      <div class="p-3 bg-white shadow rounded-2 mb-4 border">
        <div class="py-2">
          <h4>優惠券列表</h4>
          <form action="">
            <?php
            $today = date('Y-m-d');
            $coupon_startDate = isset($_GET["coupon_startDate"]) ? $_GET["coupon_startDate"] :
              $today;
            $coupon_endDate = isset($_GET["coupon_endDate"]) ? $_GET["coupon_endDate"] : $today;
            ?>
            <div class="row g-2">
              <div class="col-3 form-floating">
                <input type="coupon_sid" class="form-control" id="coupon_sid" placeholder="coupon_sid" name="coupon_sid">
                <label for="coupon_sid">優惠券序號</label>
              </div>
              <div class="col-3 form-floating">
                <input type="coupon_name" class="form-control" id="coupon_name" placeholder="coupon_name" name="coupon_name">
                <label for="coupon_name">優惠券名稱</label>
              </div>
              <div class="col-3 form-floating">
                <input type="date" class="form-control" name="coupon_startDate" id="coupon_startDate" value="<?= $coupon_startDate ?>">
                <label for="coupon_startDate">開始日期</label>
              </div>
              <div class="col-3 form-floating">
                <input type="date" class="form-control" name="coupon_endDate" id="coupon_endDate" value="<?= $coupon_endDate ?>">
                <label for="coupon_endDate">結束日期</label>
              </div>
            </div>
            <div class="row g-2 d-flex justify-content-between pt-3 align-items-center">
              <div class="col-3 form-floating">
                <select class="form-select" id="coupon_state" placeholder="coupon_state" name="coupon_state">
                  <option value=0>所有狀態</option>
                  <option value=1>啟用</option>
                  <option value=2>停用</option>
                  <option value=3>已失效</option>
                </select>
                <label for="coupon_state">優惠券狀態</label>
              </div>
              <div class="col-auto">
                <button type="button" class="btn btn-primary btn-lg" onclick="show()">
                  <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <button type="button" class="btn btn-dark btn-lg" onclick="clean()">
                  <i class="fa-solid fa-xmark"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="bg-white shadow rounded-2 border">
        <div class="table-title mb-3 d-flex justify-content-between align-items-center p-2 rounded-top">
          <h6 class="m-0 text-primary ms-2">查詢結果</h6>
          <a class="btn btn-primary me-2" href="createCoupon.php">新增</a>
        </div>
        <div class="p-3">
          <table class="coupon-table table table-bordered p-3">
            <thead>
              <tr>
                <th>編號</th>
                <th>優惠券序號</th>
                <th>優惠券名稱</th>
                <th>數量</th>
                <th>發放方式</th>
                <th>最低消費</th>
                <th>折抵類別</th>
                <!-- <th>折抵</th> -->
                <th>有效時間起迄</th>
                <th>優惠券狀態</th>
                <!-- <th>自動發送時間</th> -->
                <th>活動併用方式</th>
                <th>建立時間</th>
                <th>功能項目</th>
              </tr>
            </thead>
            <tbody id="main_h">

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
</body>
<!-- Bootstrap JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

<script>
  const coupon_sid = document.getElementById("coupon_sid");
  const coupon_name = document.getElementById("coupon_name");
  const coupon_startDate = document.getElementById("coupon_startDate");
  const coupon_endDate = document.getElementById("coupon_endDate");
  <?php
  $sql = "SELECT * FROM coupon";
  $result = $conn->query($sql);
  $rowsJ = json_encode($result->fetch_all(MYSQLI_ASSOC));
  ?>
  let r = <?= $rowsJ ?>;
  //table內的int轉文字
  let sendInt = {
    1: "全員發送",
    2: "生日",
    3: "等級",
  };
  let rewardTypeInt = {
    1: "百分比",
    2: "金額",
  };
  let stateInt = {
    1: "啟用",
    2: "停用",
    3: "已失效",
  };
  let modeInt = {
    1: "皆可使用",
    2: "指定商品可使用",
    3: "指定商品不可使用",
  };

  function show() {
    let name = document.getElementById("coupon_name").value
    let sid = document.getElementById("coupon_sid").value
    let startDate = document.getElementById("coupon_startDate").value
    let endDate = document.getElementById("coupon_endDate").value
    let states = document.getElementById("coupon_state").value
    rendering(name, sid, startDate, endDate, states)
  }

  function clean() {
    document.getElementById("coupon_name").value = ""
    document.getElementById("coupon_sid").value = ""
    document.getElementById("coupon_startDate").value = ""
    document.getElementById("coupon_endDate").value = ""
    document.getElementById("coupon_state").value = 0
    rendering()
  }

  function addTd(name) {
    let newtd = document.createElement('td');
    newtd.innerHTML = name
    return newtd
  }
  rendering()

  function rendering(name = "", sid = "", startDate = undefined, endDate = undefined, states = 0) {
    let s = document.getElementById("main_h")
    s.innerHTML = '';

    for (let i of r) {
      if (name && !i.coupon_name.includes(name)) continue;
      if (sid && !i.coupon_sid.includes(sid)) continue;
      if (startDate && new Date(i.coupon_startDate) < new Date(startDate)) continue;
      if (endDate && new Date(i.coupon_endDate) > new Date(endDate)) continue;
      if (states && i.coupon_state !== states) continue;

      let newtr = document.createElement('tr');

      newtr.appendChild(addTd(i.id));
      newtr.appendChild(addTd(i.coupon_sid));
      newtr.appendChild(addTd(i.coupon_name));
      let amount = i.coupon_amount == -1 ? "無上限" : i.coupon_amount
      let maxUse = i.coupon_maxUse == -1 ? "無上限" : i.coupon_maxUse
      newtr.appendChild(addTd(`發放數量:${amount}<br>使用次數上限:${maxUse}`));
      newtr.appendChild(addTd(sendInt[i.coupon_send]));
      newtr.appendChild(addTd(i.coupon_lowPrice));
      newtr.appendChild(addTd(rewardTypeInt[i.coupon_rewardType]));
      newtr.appendChild(addTd(`${i.coupon_startDate}~<br>${i.coupon_endDate}`));
      newtr.appendChild(addTd(stateInt[i.coupon_state]));
      // newtr.appendChild(addTd(i.coupon_specifyDate));
      // let specifyDate = i.coupon_specifyDate == -1 ? "即刻送" : i.coupon_specifyDate;
      newtr.appendChild(addTd(modeInt[i.coupon_mode]));
      newtr.appendChild(addTd(i.coupon_createAt));

      //功能項目
      let editDOM = addTd("")
      editDOM.classList.add("p-3")

      let edit = document.createElement('a');
      edit.classList.add("fa-solid")
      edit.classList.add("fa-pen")
      edit.classList.add("me-1")
      edit.href = `editCoupon.php?id=${i.id}`;
      edit.style.textDecoration = "none"


      let eye = document.createElement('a');
      eye.classList.add("fa-solid")
      eye.classList.add("fa-eye")
      eye.classList.add("me-1")
      eye.href = "you url"
      eye.style.textDecoration = "none";

      let trash = document.createElement('a');
      trash.classList.add("fa-solid")
      trash.classList.add("fa-trash")
      trash.href = "you url"
      trash.style.textDecoration = "none";

      editDOM.appendChild(edit)
      editDOM.appendChild(eye)
      editDOM.appendChild(trash)
      newtr.appendChild(editDOM)
      s.appendChild(newtr);
    }
  }
</script>
<?php
include "../template_btm.php";
?>
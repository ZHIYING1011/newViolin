<?php
include "../vars.php";
$cateNum = 0;
$pageTitle = "{$cate_ary[$cateNum]}";
include "../template_top.php";
include "../template_nav.php";
require_once("../db_connect.php");
?>
<!-- 篩選日期-->
<?php
if (isset($_GET["date"])) {
  $date = $_GET["date"];
  $title = $date;
  $whereClause = "WHERE coupon.coupon_createAt = '$date'";
} elseif (isset($_GET["start"]) && isset($_GET["end"])) {
  $start = $_GET["start"];
  $end = $_GET["end"];
  $title = "$start ~ $end";
  $whereClause = "WHERE coupon.coupon_createAt BETWEEN '$start' AND '$end'";
} else {
  $title = "";
  $whereClause = "";
}
$sql = "SELECT * FROM coupon
        $whereClause
        ORDER BY coupon.coupon_createAt DESC";
$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);
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
            $coupon_startDate = isset($_GET["coupon_startDate"]);
            $coupon_endDate = isset($_GET["coupon_endDate"]);
            ?>
            <div class="row g-2">
              <div class="col-3 form-floating">
                <input class="form-control" id="coupon_sid" placeholder="coupon_sid" name="coupon_sid">
                <label for="coupon_sid">優惠券序號</label>
              </div>
              <div class="col-3 form-floating">
                <input class="form-control" id="coupon_name" placeholder="coupon_name" name="coupon_name">
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
                <!-- <th>發放方式</th> -->
                <th>最低消費</th>
                <th>折抵類別</th>
                <!-- <th>折抵</th> -->
                <th>有效時間起迄</th>
                <th>優惠券狀態</th>
                <!-- <th>自動發送時間</th> -->
                <th>活動併用方式</th>
                <!-- <th>建立時間</th> -->
                <th>功能項目</th>
              </tr>
            </thead>
            <tbody id="main_h">

            </tbody>
          </table>
          <!-- 頁碼 -->
          <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center" id="mainPage"></ul>
          </nav>

        </div>
      </div>
    </div>
    <!-- Modal -->
    <div class="modal" tabindex="-1" id="modal_info">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">詳細資料</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="diologTemp">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-dark" data-bs-dismiss="modal">關閉</button>
            </div>
          </div>
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
  $sql = "SELECT * FROM coupon WHERE valid != 0 ORDER BY id ASC ";
  $result = $conn->query($sql);
  $rows = json_encode($result->fetch_all(MYSQLI_ASSOC));
  ?>
  let r = <?= $rows ?>;
  //頁碼
  let MaxPage = 10
  let page = {
    max: 0,
    Now: 0,
    Total: 0
  }
  page.max = Math.ceil(parseInt(r.length / MaxPage))

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
  rendering()

  //切換分頁
  function pageChange(index) {
    page.Now = index
    rendering()
    renderingPage()
  }
  //點選查詢
  function show() {
    let sid = document.getElementById("coupon_sid").value
    let name = document.getElementById("coupon_name").value
    let startDate = document.getElementById("coupon_startDate").value
    let endDate = document.getElementById("coupon_endDate").value
    let states = parseInt(document.getElementById("coupon_state").value)
    page.Now = 0;
    rendering(sid, name, startDate, endDate, states)

  }
  //點選清除
  function clean() {
    document.getElementById("coupon_sid").value = ""
    document.getElementById("coupon_name").value = ""
    document.getElementById("coupon_startDate").value = ""
    document.getElementById("coupon_endDate").value = ""
    document.getElementById("coupon_state").value = 0
    page.Now = 0;
    rendering()
  }
  //重製分頁數量判斷
  function rePage() {
    page.max = Math.ceil(parseInt(page.Total / MaxPage))
  }
  //新增一個元件
  function addTd(name, tag = 'td') {
    let newtd = document.createElement(tag);
    newtd.innerHTML = name
    return newtd
  }
  //渲染分頁
  function renderingPage() {
    let s = document.getElementById("mainPage")
    s.innerHTML = '';
    for (let i = 0; i <= page.max; i++) {

      let nts = addTd("", "li")
      let nt = addTd(i + 1, "a")
      nts.classList.add("page-item")
      nt.classList.add("page-link")
      if (i == page.Now) nt.classList.add("active")
      else {
        nts.onclick = () => {
          pageChange(i)
        }
      }

      nts.appendChild(nt)
      s.appendChild(nts)
    }
  }
  //渲染整個下麵那邊的東西
  function rendering(sid = "", name = "", startDate = undefined, endDate = undefined, states = 0) {
    let s = document.getElementById("main_h")
    s.innerHTML = '';
    let index = 0
    for (let i of r) {
      if (name && !i.coupon_name.includes(name)) continue;
      if (sid && !i.coupon_sid.includes(sid)) continue;
      if (startDate && new Date(i.coupon_startDate) < new Date(startDate)) continue;
      if (endDate && new Date(i.coupon_endDate) > new Date(endDate)) continue;
      if (states && parseInt(i.coupon_state) !== states) continue;
      index++;

      if (index <= (page.Now) * MaxPage || index > (page.Now + 1) * MaxPage) continue;

      let newtr = document.createElement('tr');

      newtr.appendChild(addTd(i.id));
      newtr.appendChild(addTd(i.coupon_sid));
      newtr.appendChild(addTd(i.coupon_name));
      let amount = i.coupon_amount == -1 ? "無上限" : i.coupon_amount
      let maxUse = i.coupon_maxUse == -1 ? "無上限" : i.coupon_maxUse
      newtr.appendChild(addTd(`發放數量: ${amount}<br>使用次數上限: ${maxUse}`));
      // newtr.appendChild(addTd(sendInt[i.coupon_send]));
      newtr.appendChild(addTd(i.coupon_lowPrice));
      newtr.appendChild(addTd(rewardTypeInt[i.coupon_rewardType]));
      newtr.appendChild(addTd(`${i.coupon_startDate}~<br>${i.coupon_endDate}`));
      newtr.appendChild(addTd(stateInt[i.coupon_state]));
      // newtr.appendChild(addTd(i.coupon_specifyDate));
      // let specifyDate = i.coupon_specifyDate == -1 ? "即刻送" : i.coupon_specifyDate;
      newtr.appendChild(addTd(modeInt[i.coupon_mode]));
      // newtr.appendChild(addTd(i.coupon_createAt));

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
      eye.style.textDecoration = "none";
      eye.addEventListener('click', async function() {
        var myModal = new bootstrap.Modal(document.getElementById('modal_info'));
        myModal.show();

        const html = await (await fetch("../coupon/modal.html")).text()
        const Dom = new DOMParser().parseFromString(html, 'text/html');
        const template = await Dom.querySelector('template');

        // html dom show
        let dom = await document.importNode(new DOMParser().parseFromString(template.innerHTML, "text/html").documentElement, true)
        let s = document.getElementById("diologTemp")
        s.innerHTML = '';
        s.appendChild(dom);
        //script import 
        const scriptContent = await Dom.querySelector('script').textContent;
        const blob = new Blob([scriptContent], {
          type: 'application/javascript'
        });
        const url = URL.createObjectURL(blob);
        const module = await import(url);
        URL.revokeObjectURL(url);
        module.setIdInfo(i)

      });

      let trash = document.createElement('a');
      trash.classList.add("fa-solid")
      trash.classList.add("fa-trash")
      trash.style.textDecoration = "none";
      trash.href = `./couponDelet.php?id=${i.id}`;
      trash.addEventListener('click', async function() {
        this.location = `./couponDelet.php?id=${i.id}`;
        console.log(this.location)
      })

      editDOM.appendChild(edit)
      editDOM.appendChild(eye)
      editDOM.appendChild(trash)
      newtr.appendChild(editDOM)
      s.appendChild(newtr);
    }
    page.Total = index
    rePage()
    renderingPage()
  }
</script>
<?php
include "../template_btm.php";
?>
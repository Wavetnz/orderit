<?php
session_start();
require_once 'config/db.php';
require_once 'template/navbar.php';

if (isset($_SESSION['admin_log'])) {
  $admin = $_SESSION['admin_log'];
  $sql = "SELECT * FROM admin WHERE username = :admin";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(":admin", $admin);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
}
if (!isset($_SESSION["admin_log"])) {
  $_SESSION["warning"] = "กรุณาเข้าสู่ระบบ";
  header("location: login.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php bs5() ?>
  <title>ตรวจสอบ | IT ORDER PRO</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>

  <?php navbar();
  ?>

  <?php if (!isset($_GET['numberWork'])) { ?>
    <div class="container mt-5">
      <div class="container mt-5">
        <?php if (isset($_SESSION['error'])) { ?>
          <div class="alert alert-danger" role="alert">
            <?php
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
          </div>
        <?php } ?>

        <?php if (isset($_SESSION['warning'])) { ?>
          <div class="alert alert-warning" role="alert">
            <?php
            echo $_SESSION['warning'];
            unset($_SESSION['warning']);
            ?>
          </div>
        <?php } ?>

        <?php if (isset($_SESSION['success'])) { ?>
          <div class="alert alert-success" role="alert">
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
          </div>
        <?php } ?>
        <form action="" method="GET">
          <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-6">
              <div class="row">
                <div class="col-sm-12">
                  <div class="mb-3">
                    <label class="form-label" for="inputGroupSelect01">หมายเลขออกงาน</label>
                    <?php
                    $sql = "SELECT * FROM orderdata ORDER BY id ASC";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $d = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <select class="form-select" name="numberWork">
                      <?php foreach ($d as $row) { ?>
                        <option value="<?= $row['id'] ?>">
                          <?= $row['numberWork'] ?>
                        </option>
                      <?php } ?>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-primary mb-3 p-3">ดูข้อมูล</button>
        </form>
      </div>
      <?php
      $sql = "SELECT status, COUNT(*) as count FROM orderdata GROUP BY status";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $statusCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $statusOptions = array(
        1 => array(
          'text' => "รอรับเอกสารจากหน่วยงาน",
          'color' => "#FF5733"
        ),
        2 => array(
          'text' => "รอส่งเอกสารไปพัสดุ",
          'color' => "#1CD23C"
        ),
        3 => array(
          'text' => "รอพัสดุสั่งของ",
          'color' => "#5733FF"
        ),
        4 => array(
          'text' => "รอหมายเลขครุภัณฑ์",
          'color' => "#FF33C7"
        ),
        5 => array(
          'text' => "ปิดงาน",
          'color' => "#33C7FF"
        ),
        6 => array(
          'text' => "ยกเลิก",
          'color' => "#C733FF"
        )
      );

      foreach ($statusCounts as $statusCount) {
        $status = $statusCount['status'];
        $count = $statusCount['count'];

        $textS = isset($statusOptions[$status]['text']) ? $statusOptions[$status]['text'] : "ไม่ระบุสถานะ";
        $color = isset($statusOptions[$status]['color']) ? $statusOptions[$status]['color'] : sprintf('#%06X', rand(0, 0xFFFFFF));

      ?>
        <div class="col-sm-6">
          <div class="card text-white me-3 mb-3" style="max-width: 18rem; background-color: <?= $color ?>">
            <div class="card-header">
              <ion-icon name="people-outline"></ion-icon>
              <?= $textS ?>
            </div>
            <div class="card-body">
              <h5 class="card-title"><?= $count ?></h5>
              <p class="card-text">
                <a href="checkStatus.php?status=<?= $status ?>" class="text-white" style="text-decoration: none;"> รายละเอียดเพิ่มเติม</a>
              </p>
            </div>
          </div>
        </div>
      <?php
      }
      ?>
    </div>
    </div>
    <div class="col-sm-12 col-lg-6 col-md-12">
      <?php
      $columns = [];

      for ($i = 1; $i <= 15; $i++) {
        $columns[] = "`list$i`, `quality$i`, `amount$i`, `price$i` , `unit$i`";
      }
      $columnString = implode(", ", $columns);

      $sql = "SELECT $columnString FROM `orderdata`";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      $sum = 0;

      for ($i = 1; $i <= 15; $i++) {
        $list = $result["list$i"];
        $quality = $result["quality$i"];
        $amount = $result["amount$i"];
        $price = $result["price$i"];
        $amount = intval($amount);
        $price = intval($price);
        // คำนวณ $sum
        $currentSum = $amount * $price;

        $sum += intval($currentSum);
        // ตรวจสอบว่า $currentSum เป็น 0 หรือไม่
        if ($currentSum == "-" || $currentSum == 0) {
          $currentSum = ""; // กำหนดให้ $currentSum เป็นค่าว่าง
        }
        if ($result["list$i"] == "-" || $result["quality$i"] == "-" || $result["amount$i"] == "-" || $result["price$i"] == "-" || $result["unit$i"] == "-") {
          $list = "";
          $quality = "";
          $amount = "";
          $price = "";
          $unit = "";
        }
      }

      $sql = "SELECT od.*, dp.depart_name, lw.work_name, dv.device_name, ad.fname, ad.lname";

      for ($i = 1; $i <= 15; $i++) {
        $sql .= ", dm{$i}.models_name AS model{$i}";
      }

      $sql .= " FROM orderdata AS od
  INNER JOIN depart AS dp ON od.refDepart = dp.depart_id
  INNER JOIN listwork AS lw ON od.refWork = lw.work_id
  INNER JOIN device AS dv ON od.refDevice = dv.device_id
  INNER JOIN admin AS ad ON od.refUsername = ad.username";

      for ($i = 1; $i <= 15; $i++) {
        $sql .= " LEFT JOIN device_models AS dm{$i} ON od.list{$i} = dm{$i}.models_id";
      }
      $sql .= " ORDER BY od.numberWork ASC";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $data = $stmt->fetch(PDO::FETCH_ASSOC);

      $Device2 = "";
      $Device3 = "";

      if ($data['numberDevice2'] == "-" || $data['numberDevice2'] == "") {
        $Device2 = "";
      } else {
        $Device2 = ', ' . $data["numberDevice2"];
      }
      if ($data['numberDevice3'] == "-" || $data['numberDevice3'] == "") {
        $Device3 = "";
      } else {
        $Device3 = ', ' . $data["numberDevice3"];
      }

      function toMonthThai($m)
      {
        $monthNamesThai = array(
          "",
          "มกราคม",
          "กุมภาพันธ์",
          "มีนาคม",
          "เมษายน",
          "พฤษภาคม",
          "มิถุนายน",
          "กรกฎาคม",
          "สิงหาคม",
          "กันยายน",
          "ตุลาคม",
          "พฤศจิกายน",
          "ธันวาคม"
        );
        return $monthNamesThai[$m];
      }

      function formatDateThai($date)
      {
        if ($date == null || $date == "") {
          return ""; // ถ้าวันที่เป็นค่าว่างให้คืนค่าว่างเปล่า
        }

        // แปลงวันที่ในรูปแบบ Y-m-d เป็น timestamp
        $timestamp = strtotime($date);

        // ดึงปีไทย
        $yearThai = date('Y', $timestamp);

        // ดึงเดือน
        $monthNumber = date('n', $timestamp);

        // แปลงเดือนเป็นภาษาไทย
        $monthThai = toMonthThai($monthNumber);

        // ดึงวันที่
        $day = date('d', $timestamp);

        // สร้างรูปแบบวันที่ใหม่
        $formattedDate = "$day $monthThai $yearThai";

        return $formattedDate;
      }

      $dateWithdrawFromDB = $data['dateWithdraw'];
      $receiptDateFromDB = $data['receiptDate'];
      $deliveryDateFromDB = $data['deliveryDate'];
      $closeDateFromDB = $data['closeDate'];

      // แปลงวันที่ในรูปแบบ Y-m-d เป็นรูปแบบไทย
      $dateWithdrawThai = formatDateThai($dateWithdrawFromDB);
      $receiptThai = formatDateThai($receiptDateFromDB);
      $deliveryThai = formatDateThai($deliveryDateFromDB);
      $closeThai = formatDateThai($closeDateFromDB);


      ?>



      <div class="d-flex justify-content-between">

        <p style="text-align:right;line-height:16pt">
          <?= $dateWithdrawThai ?>
        </p>
        <p style="text-align:left;line-height:16pt"><b>ผู้รับเรื่อง :</b> <?= $data['fname'] . ' ' . $data['lname'] ?></p>
      </div>
      <div class="d-flex justify-content-between">

        <p style="text-align:left;line-height:16pt">
          <b>ส่งซ่อมอุปกรณ์ คอมพิวเตอร์ :</b> <?= $data['device_name'] ?>
        </p>

        <p style="text-align:right;line-height:16pt">
          <b>หมายเลขพัสดุ / ครุภัณฑ์ :</b> <?= $data['numberDevice1'] . $Device2 . $Device3 ?>
        </p>

      </div>
      <div class="d-flex justify-content-between">

        <p style="text-align:left;line-height:16pt"><b>อาการที่รับแจ้ง :</b> <?= $data['report'] ?></p>
        <p style="text-align:left; line-height:16pt"><b>รายละเอียด :</b> <?= $data['reason'] ?></p>
        <p style="text-align:left; line-height:16pt"><b>หน่วยงานที่แจ้ง :</b> <?= $data['depart_name'] ?></p>
      </div>
      <?php
      $statusOptions = array(
        1 => "รอรับเอกสารจากหน่วยงาน",
        2 => "รอส่งเอกสารไปพัสดุ",
        3 => "รอพัสดุสั่งของ",
        4 => "รอหมายเลขครุภัณฑ์",
        5 => "ปิดงาน",
        6 => "ยกเลิก"
      );

      $dataStatus = $data['status'];


      // ตรวจสอบว่าค่าที่ได้จาก $dataStatus มีอยู่ใน $statusOptions หรือไม่
      if (array_key_exists($dataStatus, $statusOptions)) {
        $statusText = $statusOptions[$dataStatus];
      } else {
        $statusText = "ไม่ระบุสถานะ"; // หรือข้อความที่คุณต้องการเมื่อไม่พบสถานะที่ระบุ
      }


      ?>
      <form action="system/update.php" method="POST">
        <b>สถานะ :</b>
        <select disabled required class="form-select mb-3" name="status" id="statusD">
          <?php
          $options = [
            1 => 'รอรับเอกสารจากหน่วยงาน',
            2 => 'รอส่งเอกสารไปพัสดุ',
            3 => 'รอพัสดุสั่งของ',
            4 => 'รอหมายเลขครุภัณฑ์',
            5 => 'ปิดงาน',
            6 => 'ยกเลิก',
          ];

          foreach ($options as $value => $text) {
            // Check if $dataStatus is set and equals the current option value
            $selected = (isset($dataStatus) && $dataStatus == $value) ? 'selected' : '';

            echo '<option value="' . $value . '" ' . $selected . '>' . $text . '</option>';
          }
          ?>
        </select>
        <div class="row">
          <div class="col-sm-4">
            <div class="mb-3">
              <input type="checkbox" disabled name="show_receipt_date" class="form-checkbox" onclick="toggleDateInput('receipt_date')"> วันที่รับเอกสาร :
              <p class="thaiDATE"><?= $receiptThai ?></p>
              <input type="hidden" value="<?= $data['receiptDate'] ?>" name="receipt_date" class="form-control inputDate thaiDateInput" value="<?= $receiptThai ?>">

            </div>
          </div>
          <div class="col-sm-4">
            <div class="mb-3">
              <input type="checkbox" disabled name="show_delivery_date" class="form-checkbox" onclick="toggleDateInput('delivery_date')"> วันที่รับเอกสาร :
              <p class="thaiDATE"><?= $deliveryThai ?></p>
              <input type="hidden" value="<?= $data['deliveryDate'] ?>" name="delivery_date" class="form-control thaiDATE1 thaiDateInput" value="<?= $deliveryThai ?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="mb-3">
              <input type="checkbox" disabled name="show_close_date" class="form-checkbox" onclick="toggleDateInput('close_date')"> วันที่ปิดงาน :
              <p class="thaiDATE"><?= $closeThai ?></p>
              <input type="hidden" value="<?= $data['closeDate'] ?>" name="close_date" class="form-control thaiDATE1 thaiDateInput" value="<?= $closeThai ?>">
            </div>
          </div>
        </div>

        <table id="pdf" class="table table-hover mt-3 table-bordered border-secondary">
          <thead>
            <tr class="text-center">
              <th scope="col">ลำดับ</th>
              <th scope="col">รายการ</th>
              <th scope="col">คุณสมบัติ</th>
              <th scope="col">จำนวน</th>
              <th scope="col">ราคา</th>
              <th scope="col">รวม</th>
              <th scope="col">หน่วย</th>
            </tr>
          </thead>
          <tbody class="text-center">
            <?php


            $sum = 0;

            for ($i = 1; $i <= 15; $i++) {
              $list = $result["list$i"];
              $quality = $result["quality$i"];
              $amount = $result["amount$i"];
              $price = $result["price$i"];
              $unit = $result["unit$i"];
              // คำนวณ $sum
              $amount = intval($amount);
              $price = intval($price);
              $currentSum = $amount * $price;

              $sum += intval($currentSum);

              // ตรวจสอบว่า $currentSum เป็น 0 หรือไม่
              if ($currentSum == "-" || $currentSum == 0) {
                $currentSum = ""; // กำหนดให้ $currentSum เป็นค่าว่าง
              }
              if ($result["list$i"] == "-" || $result["quality$i"] == "-" || $result["amount$i"] == "-" || $result["price$i"] == "-" || $result["list$i"] == "" || $result["quality$i"] == "" || $result["amount$i"] == "" || $result["price$i"] == "" && $result["unit$i"] == "" && $result["unit$i"] == "-") {
                $list = "";
                $quality = "";
                $amount = "";
                $price = "";
                $unit = "";
              }
              if (!empty($list)) {
                $sql = "SELECT models_name FROM device_models WHERE models_id = :modelsId";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(":modelsId", $list);
                $stmt->execute();
                $modelName = $stmt->fetchColumn();
              } else {
                $modelName = "";
              }
            ?>
              <tr class="empty-row">
                <th style="font-weight: normal;" class="arabicNumber" scope="row"><?= $i; ?></th>
                <td>
                  <select disabled style="width: 120px;" class="form-select" name="list<?= $i ?>">
                    <option value=""></option>
                    <?php
                    // ดึงข้อมูลจากตาราง device_models
                    $sql = "SELECT models_id, models_name FROM device_models";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $deviceModels = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($deviceModels as $deviceModel) {
                      $selected = ($deviceModel['models_id'] == $list) ? 'selected' : '';
                      echo "<option value='{$deviceModel['models_id']}' {$selected}>{$deviceModel['models_name']}</option>";
                    }
                    ?>
                  </select>
                </td>

                <td><textarea disabled rows="2" maxlength="60" name="quality<?= $i ?>" class="limitedTextarea"><?= $quality ?></textarea></td>
                <td><input disabled value="<?= $amount ?>" style="width: 2rem;" type="text" name="amount<?= $i ?>"></td>
                <td><input disabled value="<?= $price ?>" style="width: 4rem;" type="text" name="price<?= $i ?>"></td>
                <td><input disabled readonly value="<?= $currentSum ?>" style="width: 4rem;"></td>
                <td><input disabled value="<?= $unit ?>" style="width: 4rem;" type="text" name="unit<?= $i ?>"></td>
              </tr>
            <?php
            }

            ?>
          </tbody>
        </table>
      </form>
    </div>
    </div>
    </div>
    </div>

  <?php    }
  ?>


  <?php
  if (isset($_GET['numberWork'])) {
    $numberWork = $_GET['numberWork'];
    $columns = [];

    for ($i = 1; $i <= 15; $i++) {
      $columns[] = "`list$i`, `quality$i`, `amount$i`, `price$i` , `unit$i`";
    }
    $columnString = implode(", ", $columns);

    $sql = "SELECT $columnString FROM `orderdata` WHERE id = :numberWork";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":numberWork", $numberWork);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $sum = 0;

    for ($i = 1; $i <= 15; $i++) {
      $list = $result["list$i"];
      $quality = $result["quality$i"];
      $amount = $result["amount$i"];
      $price = $result["price$i"];
      $amount = intval($amount);
      $price = intval($price);
      // คำนวณ $sum
      $currentSum = $amount * $price;

      $sum += intval($currentSum);
      // ตรวจสอบว่า $currentSum เป็น 0 หรือไม่
      if ($currentSum == "-" || $currentSum == 0) {
        $currentSum = ""; // กำหนดให้ $currentSum เป็นค่าว่าง
      }
      if ($result["list$i"] == "-" || $result["quality$i"] == "-" || $result["amount$i"] == "-" || $result["price$i"] == "-" || $result["unit$i"] == "-") {
        $list = "";
        $quality = "";
        $amount = "";
        $price = "";
        $unit = "";
      }
    }

    $sql = "SELECT od.*, dp.depart_name, lw.work_name, dv.device_name, ad.fname, ad.lname";

    for ($i = 1; $i <= 15; $i++) {
      $sql .= ", dm{$i}.models_name AS model{$i}";
    }

    $sql .= " FROM orderdata AS od
    INNER JOIN depart AS dp ON od.refDepart = dp.depart_id
    INNER JOIN listwork AS lw ON od.refWork = lw.work_id
    INNER JOIN device AS dv ON od.refDevice = dv.device_id
    INNER JOIN admin AS ad ON od.refUsername = ad.username";

    for ($i = 1; $i <= 15; $i++) {
      $sql .= " LEFT JOIN device_models AS dm{$i} ON od.list{$i} = dm{$i}.models_id";
    }
    $sql .= " WHERE od.id = :numberWork";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":numberWork", $numberWork);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    $Device2 = "";
    $Device3 = "";

    if ($data['numberDevice2'] == "-" || $data['numberDevice2'] == "") {
      $Device2 = "";
    } else {
      $Device2 = ', ' . $data["numberDevice2"];
    }
    if ($data['numberDevice3'] == "-" || $data['numberDevice3'] == "") {
      $Device3 = "";
    } else {
      $Device3 = ', ' . $data["numberDevice3"];
    }

    function toMonthThai($m)
    {
      $monthNamesThai = array(
        "",
        "มกราคม",
        "กุมภาพันธ์",
        "มีนาคม",
        "เมษายน",
        "พฤษภาคม",
        "มิถุนายน",
        "กรกฎาคม",
        "สิงหาคม",
        "กันยายน",
        "ตุลาคม",
        "พฤศจิกายน",
        "ธันวาคม"
      );
      return $monthNamesThai[$m];
    }

    function formatDateThai($date)
    {
      if ($date == null || $date == "") {
        return ""; // ถ้าวันที่เป็นค่าว่างให้คืนค่าว่างเปล่า
      }

      // แปลงวันที่ในรูปแบบ Y-m-d เป็น timestamp
      $timestamp = strtotime($date);

      // ดึงปีไทย
      $yearThai = date('Y', $timestamp);

      // ดึงเดือน
      $monthNumber = date('n', $timestamp);

      // แปลงเดือนเป็นภาษาไทย
      $monthThai = toMonthThai($monthNumber);

      // ดึงวันที่
      $day = date('d', $timestamp);

      // สร้างรูปแบบวันที่ใหม่
      $formattedDate = "$day $monthThai $yearThai";

      return $formattedDate;
    }

    $dateWithdrawFromDB = $data['dateWithdraw'];
    $receiptDateFromDB = $data['receiptDate'];
    $deliveryDateFromDB = $data['deliveryDate'];
    $closeDateFromDB = $data['closeDate'];

    // แปลงวันที่ในรูปแบบ Y-m-d เป็นรูปแบบไทย
    $dateWithdrawThai = formatDateThai($dateWithdrawFromDB);
    $receiptThai = formatDateThai($receiptDateFromDB);
    $deliveryThai = formatDateThai($deliveryDateFromDB);
    $closeThai = formatDateThai($closeDateFromDB);


  ?>


    <div class="container mt-5">
      <div class="container mt-5">
        <div class="row">
          <div class="col-sm-12 col-md-12 col-lg-6">
            <div class="row">
              <div class="col-sm-12">
                <form action="" method="GET">
                  <div class="mb-3">
                    <label class="form-label" for="inputGroupSelect01">หมายเลขออกงาน</label>
                    <?php
                    $sql = "SELECT * FROM orderdata";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $d = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <select class="form-select" name="numberWork">
                      <?php foreach ($d as $row) { ?>
                        <option value="<?= $row['id'] ?>" <?php echo ($numberWork == $row['id']) ? 'selected' : ''; ?>>
                          <?= $row['numberWork'] ?>
                        </option>
                      <?php } ?>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-primary mb-3 p-3">ดูข้อมูล</button>
                </form>
              </div>
              <?php
              $sql = "SELECT status, COUNT(*) as count FROM orderdata GROUP BY status";
              $stmt = $conn->prepare($sql);
              $stmt->execute();
              $statusCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

              $statusOptions = array(
                1 => array(
                  'text' => "รอรับเอกสารจากหน่วยงาน",
                  'color' => "#FF5733"
                ),
                2 => array(
                  'text' => "รอส่งเอกสารไปพัสดุ",
                  'color' => "#1CD23C"
                ),
                3 => array(
                  'text' => "รอพัสดุสั่งของ",
                  'color' => "#5733FF"
                ),
                4 => array(
                  'text' => "รอหมายเลขครุภัณฑ์",
                  'color' => "#FF33C7"
                ),
                5 => array(
                  'text' => "ปิดงาน",
                  'color' => "#33C7FF"
                ),
                6 => array(
                  'text' => "ยกเลิก",
                  'color' => "#C733FF"
                )
              );
              foreach ($statusCounts as $statusCount) {
                $status = $statusCount['status'];
                $count = $statusCount['count'];

                $textS = isset($statusOptions[$status]['text']) ? $statusOptions[$status]['text'] : "ไม่ระบุสถานะ";
                $color = isset($statusOptions[$status]['color']) ? $statusOptions[$status]['color'] : sprintf('#%06X', rand(0, 0xFFFFFF));

              ?>
                <div class="col-sm-6">
                  <div class="card text-white me-3 mb-3" style="max-width: 18rem; background-color: <?= $color ?>">
                    <div class="card-header">
                      <ion-icon name="people-outline"></ion-icon>
                      <?= $textS ?>
                    </div>
                    <div class="card-body">
                      <h5 class="card-title"><?= $count ?></h5>
                      <p class="card-text">
                        <a href="checkStatus.php?status=<?= $status ?>" class="text-white" style="text-decoration: none;"> รายละเอียดเพิ่มเติม</a>
                      </p>
                    </div>
                  </div>
                </div>
              <?php
              }
              ?>
            </div>
          </div>
          <div class="col-sm-12 col-md-12 col-lg-6">
            <div class="d-flex justify-content-between">

              <p style="text-align:right;line-height:16pt">
                <?= $dateWithdrawThai ?>
              </p>
              <p style="text-align:left;line-height:16pt"><b>ผู้รับเรื่อง :</b> <?= $data['fname'] . ' ' . $data['lname'] ?></p>
            </div>
            <div class="d-flex justify-content-between">

              <p style="text-align:left;line-height:16pt">
                <b>ส่งซ่อมอุปกรณ์ คอมพิวเตอร์ :</b> <?= $data['device_name'] ?>
              </p>

              <p style="text-align:right;line-height:16pt">
                <b>หมายเลขพัสดุ / ครุภัณฑ์ :</b> <?= $data['numberDevice1'] . $Device2 . $Device3 ?>
              </p>

            </div>
            <div class="d-flex justify-content-between">

              <p style="text-align:left;line-height:16pt"><b>อาการที่รับแจ้ง :</b> <?= $data['report'] ?></p>
              <p style="text-align:left; line-height:16pt"><b>รายละเอียด :</b> <?= $data['reason'] ?></p>
              <p style="text-align:left; line-height:16pt"><b>หน่วยงานที่แจ้ง :</b> <?= $data['depart_name'] ?></p>
            </div>
            <?php
            $statusOptions = array(
              1 => "รอรับเอกสารจากหน่วยงาน",
              2 => "รอส่งเอกสารไปพัสดุ",
              3 => "รอพัสดุสั่งของ",
              4 => "รอหมายเลขครุภัณฑ์",
              5 => "ปิดงาน",
              6 => "ยกเลิก"
            );

            $dataStatus = $data['status'];


            // ตรวจสอบว่าค่าที่ได้จาก $dataStatus มีอยู่ใน $statusOptions หรือไม่
            if (array_key_exists($dataStatus, $statusOptions)) {
              $statusText = $statusOptions[$dataStatus];
            } else {
              $statusText = "ไม่ระบุสถานะ"; // หรือข้อความที่คุณต้องการเมื่อไม่พบสถานะที่ระบุ
            }


            ?>
            <form action="system/update.php" method="POST">
              <b>สถานะ :</b>
              <select disabled required class="form-select mb-3" name="status" id="statusD">
                <?php
                $options = [
                  1 => 'รอรับเอกสารจากหน่วยงาน',
                  2 => 'รอส่งเอกสารไปพัสดุ',
                  3 => 'รอพัสดุสั่งของ',
                  4 => 'รอหมายเลขครุภัณฑ์',
                  5 => 'ปิดงาน',
                  6 => 'ยกเลิก',
                ];

                foreach ($options as $value => $text) {
                  // Check if $dataStatus is set and equals the current option value
                  $selected = (isset($dataStatus) && $dataStatus == $value) ? 'selected' : '';

                  echo '<option value="' . $value . '" ' . $selected . '>' . $text . '</option>';
                }
                ?>
              </select>
              <div class="row">
                <div class="col-sm-4">
                  <div class="mb-3">
                    <input type="checkbox" disabled name="show_receipt_date" class="form-checkbox" onclick="toggleDateInput('receipt_date')"> วันที่รับเอกสาร :
                    <p class="thaiDATE"><?= $receiptThai ?></p>
                    <input type="hidden" value="<?= $data['receiptDate'] ?>" name="receipt_date" class="form-control inputDate thaiDateInput" value="<?= $receiptThai ?>">

                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="mb-3">
                    <input type="checkbox" disabled name="show_delivery_date" class="form-checkbox" onclick="toggleDateInput('delivery_date')"> วันที่รับเอกสาร :
                    <p class="thaiDATE"><?= $deliveryThai ?></p>
                    <input type="hidden" value="<?= $data['deliveryDate'] ?>" name="delivery_date" class="form-control thaiDATE1 thaiDateInput" value="<?= $deliveryThai ?>">
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="mb-3">
                    <input type="checkbox" disabled name="show_close_date" class="form-checkbox" onclick="toggleDateInput('close_date')"> วันที่ปิดงาน :
                    <p class="thaiDATE"><?= $closeThai ?></p>
                    <input type="hidden" value="<?= $data['closeDate'] ?>" name="close_date" class="form-control thaiDATE1 thaiDateInput" value="<?= $closeThai ?>">
                  </div>
                </div>
              </div>
              <div class="d-flex justify-content-center mb-3">
                <input type="hidden" name="numberWork" value="<?= $numberWork ?>">
                <button id="editData" class="btn btn-warning p-2 me-3">แก้ไข</button>
                <a href="index.php?id=<?= $numberWork ?>" class="btn btn-secondary p-2 me-3">คัดลอก</a>
                <button type="submit" disabled name="updateData" class="btn btn-success p-2 me-3">บันทึก</button>
              </div>


              <a href="แบบฟอร์มคำขอส่งซ่อมบำรุงอุปกรณ์คอมพิวเตอร์.php?workid=<?= $numberWork ?>" target="_blank" class="btn btn-primary p-2">พิมพ์ใบซ่อม</a>
              <a target="_blank" href="ใบเบิก.php?workid=<?= $numberWork ?>" class="btn btn-primary p-2">พิมพ์ใบเบิก</a>
              <a href="พิมพ์ใบครุภัณฑ์.php?workid=<?= $numberWork ?>" target="_blank" class="btn btn-primary p-2">พิมพ์ใบครุภัณฑ์</a>
              <a href="เอกสารคณะกรรมการ.php?workid=<?= $numberWork ?>" target="_blank" class="btn btn-primary p-2">เอกสารคณะกรรมการ</a>
              <table id="pdf" class="table table-hover mt-3 table-bordered border-secondary">
                <thead>
                  <tr class="text-center">
                    <th scope="col">ลำดับ</th>
                    <th scope="col">รายการ</th>
                    <th scope="col">คุณสมบัติ</th>
                    <th scope="col">จำนวน</th>
                    <th scope="col">ราคา</th>
                    <th scope="col">รวม</th>
                    <th scope="col">หน่วย</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  <?php


                  $sum = 0;

                  for ($i = 1; $i <= 15; $i++) {
                    $list = $result["list$i"];
                    $quality = $result["quality$i"];
                    $amount = $result["amount$i"];
                    $price = $result["price$i"];
                    $unit = $result["unit$i"];
                    // คำนวณ $sum
                    $amount = intval($amount);
                    $price = intval($price);
                    $currentSum = $amount * $price;

                    $sum += intval($currentSum);

                    // ตรวจสอบว่า $currentSum เป็น 0 หรือไม่
                    if ($currentSum == "-" || $currentSum == 0) {
                      $currentSum = ""; // กำหนดให้ $currentSum เป็นค่าว่าง
                    }
                    if ($result["list$i"] == "-" || $result["quality$i"] == "-" || $result["amount$i"] == "-" || $result["price$i"] == "-" || $result["list$i"] == "" || $result["quality$i"] == "" || $result["amount$i"] == "" || $result["price$i"] == "" && $result["unit$i"] == "" && $result["unit$i"] == "-") {
                      $list = "";
                      $quality = "";
                      $amount = "";
                      $price = "";
                      $unit = "";
                    }
                    if (!empty($list)) {
                      $sql = "SELECT models_name FROM device_models WHERE models_id = :modelsId";
                      $stmt = $conn->prepare($sql);
                      $stmt->bindParam(":modelsId", $list);
                      $stmt->execute();
                      $modelName = $stmt->fetchColumn();
                    } else {
                      $modelName = "";
                    }
                  ?>
                    <tr class="empty-row">
                      <th style="font-weight: normal;" class="arabicNumber" scope="row"><?= $i; ?></th>
                      <td>
                        <select disabled style="width: 120px;" class="form-select" name="list<?= $i ?>">
                          <option value=""></option>
                          <?php
                          // ดึงข้อมูลจากตาราง device_models
                          $sql = "SELECT models_id, models_name FROM device_models";
                          $stmt = $conn->prepare($sql);
                          $stmt->execute();
                          $deviceModels = $stmt->fetchAll(PDO::FETCH_ASSOC);

                          foreach ($deviceModels as $deviceModel) {
                            $selected = ($deviceModel['models_id'] == $list) ? 'selected' : '';
                            echo "<option value='{$deviceModel['models_id']}' {$selected}>{$deviceModel['models_name']}</option>";
                          }
                          ?>
                        </select>
                      </td>

                      <td><textarea disabled rows="2" maxlength="60" name="quality<?= $i ?>" class="limitedTextarea"><?= $quality ?></textarea></td>
                      <td><input disabled value="<?= $amount ?>" style="width: 2rem;" type="text" name="amount<?= $i ?>"></td>
                      <td><input disabled value="<?= $price ?>" style="width: 4rem;" type="text" name="price<?= $i ?>"></td>
                      <td><input disabled readonly value="<?= $currentSum ?>" style="width: 4rem;"></td>
                      <td><input disabled value="<?= $unit ?>" style="width: 4rem;" type="text" name="unit<?= $i ?>"></td>
                    </tr>
                  <?php
                  }

                  ?>
                </tbody>
              </table>
            </form>
          </div>
        </div>
      </div>
    </div>



    <script>
      document.addEventListener("DOMContentLoaded", function() {
        var editButton = document.getElementById("editData");
        editButton.addEventListener("click", function(event) {
          event.preventDefault(); // ป้องกัน default behavior ของการ click ลิงก์หรือ submit form
          enableInputs();
        });
      });

      function enableInputs() {
        var inputNames = ["list", "quality", "amount", "price", "unit"];
        for (var i = 1; i <= 15; i++) {
          inputNames.forEach(function(name) {
            var input = document.querySelector("input[name='" + name + i + "']");
            if (input) {
              input.disabled = false;
            }
            var textarea = document.querySelector("textarea[name='" + name + i + "']");
            if (textarea) {
              textarea.disabled = false;
            }
            var select = document.querySelector("select[name='" + name + i + "']");
            if (select) {
              select.disabled = false;
            }
          });
          var status = document.getElementById("statusD");
          if (status) {
            status.disabled = false;
          }
          var saveButton = document.querySelector("button[name='updateData']");
          if (saveButton) {
            saveButton.disabled = false;
          }
          var show_receipt_date = document.querySelector("input[name='show_receipt_date']");
          if (show_receipt_date) {
            show_receipt_date.disabled = false;
          }
          var show_delivery_date = document.querySelector("input[name='show_delivery_date']");
          if (show_delivery_date) {
            show_delivery_date.disabled = false;
          }
          var show_close_date = document.querySelector("input[name='show_close_date']");
          if (show_close_date) {
            show_close_date.disabled = false;
          }

          var currentSumInput = document.querySelector("input[name='currentSum" + i + "']");
          if (currentSumInput) {
            currentSumInput.readOnly = false;
          }
        }
      }
    </script>
  <?php } ?>



  <script>
    document.addEventListener('input', function(event) {
      if (event.target && event.target.classList.contains('limitedTextarea')) {
        const lines = event.target.value.split('\n');
        const maxRows = 2; // จำนวนแถวสูงสุดที่ต้องการ
        if (lines.length > maxRows) {
          event.target.value = lines.slice(0, maxRows).join('\n');
        }
      }
    });
  </script>



  <script>
    // ฟังก์ชันสำหรับแปลงปีคริสต์ศักราชเป็นปีพุทธศักราช
    function convertToBuddhistYear(englishYear) {
      return englishYear + 543;
    }

    // ชื่อเดือนภาษาไทย
    const thaiMonthNames = [
      'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
      'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
    ];

    function toggleDateInput(inputName) {
      const checkbox = document.querySelector(`[name="show_${inputName}"]`);
      const input = document.querySelector(`[name="${inputName}"]`);
      const thaiDateElement = input.parentElement.querySelector('.thaiDATE');

      if (checkbox.checked) {
        // ดึงอินพุทธศักราชปัจจุบัน
        const currentGregorianYear = new Date().getFullYear();
        const currentBuddhistYear = convertToBuddhistYear(currentGregorianYear);

        // แปลงปีปัจจุบันเป็นปีพุทธศักราชแล้วกำหนดค่าให้กับ input
        const currentDate = new Date();
        const thaiDate = currentBuddhistYear + '-' +
          ('0' + (currentDate.getMonth() + 1)).slice(-2) + '-' +
          ('0' + currentDate.getDate()).slice(-2);
        input.value = thaiDate; // เพิ่มบรรทัดนี้เพื่อกำหนดค่าลงใน input
        // แปลงชื่อเดือนเป็นภาษาไทย
        const thaiMonth = thaiMonthNames[currentDate.getMonth()];

        // แสดงค่าวันที่และปีพุทธศักราชใน element p
        const formattedDate = currentDate.toLocaleDateString('th-TH', {
          day: 'numeric',
          month: 'long',
          year: 'numeric'
        });
        thaiDateElement.textContent = formattedDate;
      } else {
        input.value = '';
        thaiDateElement.textContent = '';
      }
    }
  </script>




  <?php SC5() ?>
</body>

</html>
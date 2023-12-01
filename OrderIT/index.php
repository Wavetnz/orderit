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

if (isset($_GET['id'])) {
  $id = $_GET['id'];
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php bs5() ?>
  <title>สร้าง | IT ORDER PRO</title>
  <link rel="stylesheet" href="css/style.css">

</head>

<body>

  <?php navbar(); ?>

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

      <?php if (isset($id)) {
      ?>
        <form action="system/insert.php" method="post">
          <div class="row"">
            <div class=" col-sm-12 col-lg-6 col-md-12">
            <div class="row">
              <div class="col-sm-3">
                <div class="mb-3">
                  <label class="form-label" for="inputGroupSelect01">หมายเลขออกงาน</label>
                  <?php
                  $sql = "SELECT numberWork FROM orderdata";
                  $stmt = $conn->prepare($sql);
                  $stmt->execute();
                  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                  ?>
                  <select required class="form-select" name="numberWork">
                    <?php
                    if (!$result) { ?>
                      <option value="1/67">1/67</option>
                    <?php   } else {
                      foreach ($result as $row) {
                        $selectedValue = $row['numberWork'];
                        list($numerator, $denominator) = explode('/', $selectedValue);

                        $currentDate = new DateTime();

                        // Set $october10 to be October 10 of the current year
                        $october10 = new DateTime($currentDate->format('Y') . '-10-10');

                        // Check if the current date is after October 10
                        if ($currentDate > $october10) {
                          // Add 1 to the numerator and set the denominator to 1
                          $newNumerator = intval($numerator) + 1;
                          $newDenominator = 67; // เริ่มต้นที่ 1 ในปีถัดไป
                        } else {
                          // Keep the numerator and increment the denominator
                          $newNumerator = intval($numerator);
                          $newDenominator = intval($denominator) + 1;
                        }

                        $newValueToCheck = $newNumerator . '/' . $newDenominator;
                      } ?>
                      <option value="<?= $newValueToCheck ?>"><?= $newValueToCheck ?></option>
                    <?php  }
                    ?>
                  </select>
                </div>
              </div>
              <?php
              $sql = "SELECT od.*, dp.*
              FROM orderdata as od
              INNER JOIN depart as dp ON od.refDepart = dp.depart_id
              WHERE id = :id";
              $stmt = $conn->prepare($sql);
              $stmt->bindParam(":id", $id);
              $stmt->execute();
              $data = $stmt->fetch(PDO::FETCH_ASSOC);
              ?>
              <div class="col-sm-3">
                <div class="mb-3">
                  <span class="form-label" id="basic-addon1">วันที่ออกใบเบิก</span>
                  <input required type="date" value="<?= $data['dateWithdraw'] ?>" name="dateWithdraw" class="form-control thaiDateInput">
                </div>
              </div>


              <div class="col-sm-3">
                <div class="mb-3">
                  <label class="form-label" for="inputGroupSelect01">ประเภทการเบิก</label>
                  <select required class="form-select" name="ref_withdraw" id="inputGroupSelect01">
                    <?php
                    $sql = 'SELECT * FROM withdraw';
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($result as $row) { ?>
                      <option value="<?= $row['withdraw_id'] ?>" <?php echo ($data['refWithdraw'] == $row['withdraw_id']) ? 'selected' : ''; ?>>
                        <?= $row['withdraw_name'] ?>
                      </option> <?php   }
                                ?>

                  </select>
                </div>
              </div>

              <div class="col-sm-3">

                <div class="mb-3">
                  <label class="form-label" for="inputGroupSelect01">ประเภทงาน</label>
                  <select required class="form-select" name="ref_work" id="inputGroupSelect01">
                    <?php
                    $sql = 'SELECT * FROM listwork';
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($result as $row) { ?>
                      <option value="<?= $row['work_id'] ?>" <?php echo ($data['refWork'] == $row['work_id']) ? 'selected' : ''; ?>>
                        <?= $row['work_name'] ?>
                      </option>
                    <?php   }

                    ?>

                  </select>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="mb-3">
                  <label class="form-label" for="inputGroupSelect01">รายการอุปกรณ์</label>
                  <select required class="form-select" name="ref_device" id="inputGroupSelect01">
                    <?php
                    $sql = 'SELECT * FROM device';
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($result as $row) { ?>
                      <option value="<?= $row['device_id'] ?>" <?php echo ($data['refDevice'] == $row['device_id']) ? 'selected' : ''; ?>>
                        <?= $row['device_name'] ?>
                      </option>
                    <?php   }
                    ?>

                  </select>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="mb-3">
                  <label class="form-label" id="basic-addon1">หมายเลขครุภัณฑ์</label>
                  <input required type="text" value="<?= $data['numberDevice1'] ?>" name="number_device_1" class="form-control mb-2">
                  <input type="text" value="<?= $data['numberDevice2'] ?>" name="number_device_2" class="form-control mb-2">
                  <input type="text" value="<?= $data['numberDevice3'] ?>" name="number_device_3" class="form-control">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="mb-3">
                  <label class="form-label" id="basic-addon1">อาการรับแจ้ง</label>
                  <input required type="text" value="<?= $data['report'] ?>" name="report" class="form-control">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="mb-3">
                  <label class="form-label" id="basic-addon1">เหตุผลและความจำเป็น</label>
                  <input required type="text" value="<?= $data['reason'] ?>" name="reason" class="form-control">
                </div>
              </div>
              <div class="col-sm-3">
                <div class="mb-3">
                  <label class="form-label" for="departInput">หน่วยงาน</label>
                  <input type="text" value="<?= $data['depart_name'] ?>" class="form-control" id="departInput" name="ref_depart">
                  <input type="hidden" value="<?= $data['depart_id'] ?>" id="departId" name="depart_id"> <!-- ใช้เก็บค่า ID ที่ถูกเลือก -->
                </div>

                <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
                <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
                <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

                <script>
                  $(function() {
                    $("#departInput").autocomplete({
                      source: function(request, response) {
                        $.ajax({
                          url: "autocomplete.php",
                          dataType: "json",
                          data: {
                            term: request.term
                          },
                          success: function(data) {
                            response(data);
                          }
                        });
                      },
                      minLength: 2,
                      select: function(event, ui) {
                        $("#departInput").val(ui.item.label);
                        $("#departId").val(ui.item.value);
                        return false;
                      }
                    });
                  });
                </script>
              </div>

              <div class="col-sm-3">
                <div class="mb-3">
                  <label class="form-label" for="inputGroupSelect01">ผู้รับเรื่อง
                  </label>
                  <input required type="text" name="ref_username" class="form-control" value="<?= $admin ?>" readonly>
                </div>
              </div>

              <div class="col-sm-3">
                <div class="mb-3">
                  <label class="form-label" for="inputGroupSelect01">ร้านที่เสนอราคา
                  </label>
                  <select required class="form-select" name="ref_offer" id="inputGroupSelect01">
                    <?php
                    $sql = 'SELECT * FROM offer';
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) { ?>
                      <option value="<?= $row['offer_id'] ?>" <?php echo ($data['refOffer'] == $row['offer_id']) ? 'selected' : ''; ?>>
                        <?= $row['offer_name'] ?>
                      </option>
                    <?php   }
                    ?>

                  </select>
                </div>
              </div>

              <div class="col-sm-3">
                <div class="mb-3">
                  <label class="form-label" for="inputGroupSelect01">เลขที่ใบเสนอราคา
                  </label>
                  <input required type="text" value="<?= $data['quotation'] ?>" name="quotation" class="form-control">
                </div>
              </div>

              <div class="col-sm-6">

                <div class="mb-3">
                  <label class="form-label" for="inputGroupSelect01">หมายเหตุ
                  </label>
                  <input required type="text" value="<?= $data['note'] ?>" name="note" class="form-control">
                </div>
              </div>

              <div class="col-sm-6">

                <div class="mb-3">
                  <label class="form-label" for="inputGroupSelect01">สถานะ
                  </label>
                  <select required class="form-select" name="status" id="inputGroupSelect01">
                    <option value="1" <?php echo ($data['status'] == 1) ? 'selected' : ''; ?>>รอรับเอกสารจากหน่วยงาน</option>
                    <option value="2" <?php echo ($data['status'] == 2) ? 'selected' : ''; ?>>รอส่งเอกสารไปพัสดุ</option>
                    <option value="3" <?php echo ($data['status'] == 3) ? 'selected' : ''; ?>>รอพัสดุสั่งของ</option>
                    <option value="4" <?php echo ($data['status'] == 4) ? 'selected' : ''; ?>>รอหมายเลขครุภัณฑ์</option>
                    <option value="5" <?php echo ($data['status'] == 5) ? 'selected' : ''; ?>>ปิดงาน</option>
                    <option value="6" <?php echo ($data['status'] == 6) ? 'selected' : ''; ?>>ยกเลิก</option>
                  </select>
                </div>
              </div>


              <button type="submit" name="submit" class="btn btn-primary p-3">บันทึกข้อมูล</button>

            </div>
          </div>


          <div class="col-sm-12 col-lg-6 col-md-12">

            <table id="pdf" style="width: 100%;" class="table table-hover table-bordered border-secondary">
              <thead>
                <tr class="text-center">
                  <th style="text-align:center;width: 10%;">ลำดับ</th>
                  <th style="text-align:center;width: 10%;">รายการ</th>
                  <th style="text-align:center;width: 20%;">คุณสมบัติ</th>
                  <th style="text-align:center;width: 10%;">จำนวน</th>
                  <th style="text-align:center; width: 10%;">ราคา</th>
                  <th style="text-align:center; width: 10%;">หน่วย</th>
                </tr>
              </thead>
              <tbody>
                <?php
                for ($i = 1; $i <= 15; $i++) { ?>
                  <tr class="text-center">
                    <th scope="row"><?= $i ?></th>
                    <td>
                      <select style="width: 120px" class="form-select" name="list<?= $i ?>" id="inputGroupSelect01">
                        <option selected value="" disabled>เลือกรายการอุปกรณ์</option>
                        <?php
                        $sql = "SELECT * FROM device_models";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        $selectedValues = array();


                        for ($j = 1; $j <= 15; $j++) {
                          $listKey = 'list' . $j;
                          $amount = 'amount' . $j;
                          $price = 'price' . $j;
                          $unit = 'unit' . $j;
                          if (isset($data[$listKey])) {
                            $selectedValues[] = $data[$listKey];
                          }
                        }

                        foreach ($result as $d) {
                        ?>
                          <option value="<?= $d['models_id'] ?>" <?php echo ($data['list' . $i] == $d['models_id']) ? 'selected' : ''; ?>>
                            <?= $d['models_name'] ?>
                          </option>
                        <?php

                        }
                        ?>
                      </select>
                    </td>
                    <td><textarea rows="2" maxlength="60" name="quality<?= $i ?>" class="limitedTextarea"><?= $data['quality' . $i] ?></textarea></td>
                    <td><input value="<?= $data['amount' . $i] ?>" style="width: 2rem;" type="text" name="amount<?= $i ?>"></td>
                    <td><input value="<?= $data['price' . $i] ?>" style="width: 4rem;" type="text" name="price<?= $i ?>"></td>
                    <td><input value="<?= $data['unit' . $i] ?>" style="width: 4rem;" type="text" name="unit<?= $i ?>"></td>

                  </tr>
                <?php } ?>

              </tbody>
            </table>

          <?php } else { ?>


            <form action="system/insert.php" method="post">
              <div class="row">
                <div style="height:100%; padding: 24px; border-radius: 20px; background-color: white; box-shadow: 2px 4px 14px 1px rgba(0,0,0,0.29);
-webkit-box-shadow: 2px 4px 14px 1px rgba(0,0,0,0.29);
-moz-box-shadow: 2px 4px 14px 1px rgba(0,0,0,0.29);" class="col-sm-12 col-lg-6 col-md-12">
                  <div class="row">
                    <div class="col-sm-3">
                      <div class="mb-3">
                        <label class="form-label" for="inputGroupSelect01">หมายเลขออกงาน</label>
                        <?php
                        $sql = "SELECT numberWork FROM orderdata";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        ?>
                        <select required class="form-select" name="numberWork">
                          <?php
                          if (!$result) { ?>
                            <option value="1/67">1/67</option>
                          <?php   } else {
                            foreach ($result as $row) {
                              $selectedValue = $row['numberWork'];
                              list($numerator, $denominator) = explode('/', $selectedValue);

                              $currentDate = new DateTime();

                              // Set $october10 to be October 10 of the current year
                              $october10 = new DateTime($currentDate->format('Y') . '-10-10');

                              // Check if the current date is after October 10
                              if ($currentDate > $october10) {
                                // Add 1 to the numerator and set the denominator to 1
                                $newNumerator = intval($numerator) + 1;
                                $newDenominator = 67; // เริ่มต้นที่ 1 ในปีถัดไป
                              } else {
                                // Keep the numerator and increment the denominator
                                $newNumerator = intval($numerator);
                                $newDenominator = intval($denominator) + 1;
                              }

                              $newValueToCheck = $newNumerator . '/' . $newDenominator;
                            } ?>
                            <option value="<?= $newValueToCheck ?>"><?= $newValueToCheck ?></option>
                          <?php  }
                          ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="mb-3">
                        <span class="form-label" id="basic-addon1">วันที่ออกใบเบิก</span>
                        <input required type="date" name="dateWithdraw" class="form-control thaiDateInput">
                      </div>
                    </div>


                    <div class="col-sm-3">
                      <div class="mb-3">
                        <label class="form-label" for="inputGroupSelect01">ประเภทการเบิก</label>
                        <select required class="form-select" name="ref_withdraw" id="inputGroupSelect01">
                          <?php
                          $sql = 'SELECT * FROM withdraw';
                          $stmt = $conn->prepare($sql);
                          $stmt->execute();
                          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                          foreach ($result as $row) { ?>

                            <option value="<?= $row['withdraw_id'] ?>"><?= $row['withdraw_name'] ?></option>
                          <?php   }
                          ?>

                        </select>
                      </div>
                    </div>

                    <div class="col-sm-3">

                      <div class="mb-3">
                        <label class="form-label" for="inputGroupSelect01">ประเภทงาน</label>
                        <select required class="form-select" name="ref_work" id="inputGroupSelect01">
                          <?php
                          $sql = 'SELECT * FROM listwork';
                          $stmt = $conn->prepare($sql);
                          $stmt->execute();
                          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                          foreach ($result as $row) { ?>

                            <option value="<?= $row['work_id'] ?>"><?= $row['work_name'] ?></option>
                          <?php   }
                          ?>

                        </select>
                      </div>
                    </div>

                    <div class="col-sm-6">
                      <div class="mb-3">
                        <label class="form-label" for="inputGroupSelect01">รายการอุปกรณ์</label>
                        <select required class="form-select" name="ref_device" id="inputGroupSelect01">
                          <?php
                          $sql = 'SELECT * FROM device';
                          $stmt = $conn->prepare($sql);
                          $stmt->execute();
                          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                          foreach ($result as $row) { ?>
                            <option value="<?= $row['device_id'] ?>"><?= $row['device_name'] ?></option>
                          <?php   }
                          ?>

                        </select>
                      </div>
                    </div>

                    <div class="col-sm-6">
                      <div class="mb-3">
                        <label class="form-label" id="basic-addon1">หมายเลขครุภัณฑ์</label>
                        <input required type="text" name="number_device_1" class="form-control mb-2">
                        <input type="text" name="number_device_2" class="form-control mb-2">
                        <input type="text" name="number_device_3" class="form-control">
                      </div>
                    </div>

                    <div class="col-sm-6">
                      <div class="mb-3">
                        <label class="form-label" id="basic-addon1">อาการรับแจ้ง</label>
                        <input required type="text" name="report" class="form-control">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="mb-3">
                        <label class="form-label" id="basic-addon1">เหตุผลและความจำเป็น</label>
                        <input required type="text" name="reason" class="form-control">
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="mb-3">
                        <label class="form-label" for="departInput">หน่วยงาน</label>
                        <input type="text" class="form-control" id="departInput" name="ref_depart">
                        <input type="hidden" id="departId" name="depart_id">
                      </div>

                      <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
                      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
                      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

                      <script>
                        $(function() {
                          $("#departInput").autocomplete({
                            source: function(request, response) {
                              $.ajax({
                                url: "autocomplete.php",
                                dataType: "json",
                                data: {
                                  term: request.term
                                },
                                success: function(data) {
                                  response(data);
                                }
                              });
                            },
                            minLength: 2,
                            select: function(event, ui) {
                              $("#departInput").val(ui.item.label);
                              $("#departId").val(ui.item.value);
                              return false;
                            }
                          });
                        });
                      </script>
                    </div>

                    <div class="col-sm-3">
                      <div class="mb-3">
                        <label class="form-label" for="inputGroupSelect01">ผู้รับเรื่อง
                        </label>
                        <input required type="text" name="ref_username" class="form-control" value="<?= $admin ?>" readonly>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="mb-3">
                        <label class="form-label" for="inputGroupSelect01">ร้านที่เสนอราคา
                        </label>
                        <select required class="form-select" name="ref_offer" id="inputGroupSelect01">
                          <?php
                          $sql = 'SELECT * FROM offer';
                          $stmt = $conn->prepare($sql);
                          $stmt->execute();
                          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                          foreach ($result as $row) { ?>
                            <option value="<?= $row['offer_id'] ?>"><?= $row['offer_name'] ?></option>
                          <?php   }
                          ?>

                        </select>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="mb-3">
                        <label class="form-label" for="inputGroupSelect01">เลขที่ใบเสนอราคา
                        </label>
                        <input required type="text" name="quotation" class="form-control">
                      </div>
                    </div>

                    <div class="col-sm-6">

                      <div class="mb-3">
                        <label class="form-label" for="inputGroupSelect01">หมายเหตุ
                        </label>
                        <input required type="text" name="note" class="form-control">
                      </div>
                    </div>

                    <div class="col-sm-6">

                      <div class="mb-3">
                        <label class="form-label" for="inputGroupSelect01">สถานะ
                        </label>
                        <select required class="form-select" name="status" id="inputGroupSelect01">
                          <option value="1">รอรับเอกสารจากหน่วยงาน</option>
                          <option value="2">รอส่งเอกสารไปพัสดุ</option>
                          <option value="3">รอพัสดุสั่งของ</option>
                          <option value="4">รอหมายเลขครุภัณฑ์</option>
                          <option value="5">ปิดงาน</option>
                          <option value="6">ยกเลิก</option>
                        </select>
                      </div>
                    </div>


                    <button type="submit" name="submit" class="btn btn-primary p-3">บันทึกข้อมูล</button>

                  </div>
                </div>


                <div class="col-sm-12 col-lg-6 col-md-12">
                  <table id="pdf" style="width: 100%;" class="table table-hover table-bordered border-secondary">
                    <thead>
                      <tr class="text-center">
                        <th style="text-align:center;width: 10%;">ลำดับ</th>
                        <th style="text-align:center;width: 10%;">รายการ</th>
                        <th style="text-align:center;width: 20%;">คุณสมบัติ</th>
                        <th style="text-align:center;width: 10%;">จำนวน</th>
                        <th style="text-align:center; width: 10%;">ราคา</th>
                        <!-- <th style="text-align:center; width: 10%;">รวม</th> -->
                        <th style="text-align:center; width: 10%;">หน่วย</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      for ($i = 1; $i <= 15; $i++) { ?>
                        <tr class="text-center">
                          <th scope="row"><?= $i ?></th>
                          <td>
                            <select style="width: 120px" class="form-select" name="list<?= $i ?>" id="inputGroupSelect01">
                              <option selected value="" disabled>เลือกรายการอุปกรณ์</option>
                              <?php
                              $sql = "SELECT * FROM device_models";
                              $stmt = $conn->prepare($sql);
                              $stmt->execute();
                              $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                              foreach ($result as $d) {

                              ?>
                                <option value="<?= $d['models_id'] ?>"><?= $d['models_name'] ?></option>
                              <?php
                              }
                              ?>
                            </select>
                          </td>
                          <td><textarea rows="2" maxlength="60" name="quality<?= $i ?>" class="limitedTextarea"></textarea></td>
                          <td><input value="" style="width: 2rem;" type="text" name="amount<?= $i ?>"></td>
                          <td><input value="" style="width: 4rem;" type="text" name="price<?= $i ?>"></td>
                          <td><input value="" style="width: 4rem;" type="text" name="unit<?= $i ?>"></td>
                        </tr>
                      <?php    }
                      ?>
            </form>
            </tbody>
            </table>
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



          </div>

    </div>
  </div>
  <br>






  <script>
    // ฟังก์ชันสำหรับแปลงปีคริสต์ศักราชเป็นปีพุทธศักราช
    function convertToBuddhistYear(englishYear) {
      return englishYear + 543;
    }

    // ดึงอินพุทธศักราชปัจจุบัน
    const currentGregorianYear = new Date().getFullYear();
    const currentBuddhistYear = convertToBuddhistYear(currentGregorianYear);

    // หากคุณมีหลาย input ที่ต้องการกำหนดค่า
    const thaiDateInputs = document.querySelectorAll('.thaiDateInput');

    thaiDateInputs.forEach((input) => {
      // แปลงปีปัจจุบันเป็นปีพุทธศักราชแล้วกำหนดค่าให้กับ input
      const currentDate = new Date();
      input.value = currentBuddhistYear + '-' +
        ('0' + (currentDate.getMonth() + 1)).slice(-2) + '-' +
        ('0' + currentDate.getDate()).slice(-2);
    });
  </script>



  <?php SC5() ?>
</body>

</html>
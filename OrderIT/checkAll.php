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
    <title>สร้างใบเบิกประจำสัปดาห์ | IT ORDER PRO</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
</head>

<body>

    <?php navbar();
    ?>

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
            <?php }
            ?>
        </div>
        <div class="row">


            <div class="col-sm-6" style="margin-top: 70px">
                <?php
                $sql = "SELECT od.*, dm1.models_name AS model1, dm2.models_name AS model2, dm3.models_name AS model3, dm4.models_name AS model4, dm5.models_name AS model5, dm6.models_name AS model6, dm7.models_name AS model7, dm8.models_name AS model8, dm9.models_name AS model9, dm10.models_name AS model10, dm11.models_name AS model11, dm12.models_name AS model12, dm13.models_name AS model13, dm14.models_name AS model14, dm15.models_name AS model15,
od.quality1, od.quality2, od.quality3, od.quality4, od.quality5, od.quality6, od.quality7, od.quality8, od.quality9, od.quality10, od.quality11, od.quality12, od.quality13, od.quality14, od.quality15,
od.amount1, od.amount2, od.amount3, od.amount4, od.amount5, od.amount6, od.amount7, od.amount8, od.amount9, od.amount10, od.amount11, od.amount12, od.amount13, od.amount14, od.amount15,
od.price1, od.price2, od.price3, od.price4, od.price5, od.price6, od.price7, od.price8, od.price9, od.price10, od.price11, od.price12, od.price13, od.price14, od.price15
FROM orderdata AS od
LEFT JOIN device_models AS dm1 ON od.list1 = dm1.models_id
LEFT JOIN device_models AS dm2 ON od.list2 = dm2.models_id
LEFT JOIN device_models AS dm3 ON od.list3 = dm3.models_id
LEFT JOIN device_models AS dm4 ON od.list4 = dm4.models_id
LEFT JOIN device_models AS dm5 ON od.list5 = dm5.models_id
LEFT JOIN device_models AS dm6 ON od.list6 = dm6.models_id
LEFT JOIN device_models AS dm7 ON od.list7 = dm7.models_id
LEFT JOIN device_models AS dm8 ON od.list8 = dm8.models_id
LEFT JOIN device_models AS dm9 ON od.list9 = dm9.models_id
LEFT JOIN device_models AS dm10 ON od.list10 = dm10.models_id
LEFT JOIN device_models AS dm11 ON od.list11 = dm11.models_id
LEFT JOIN device_models AS dm12 ON od.list12 = dm12.models_id
LEFT JOIN device_models AS dm13 ON od.list13 = dm13.models_id
LEFT JOIN device_models AS dm14 ON od.list14 = dm14.models_id
LEFT JOIN device_models AS dm15 ON od.list15 = dm15.models_id
WHERE od.status = 2
";

                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <form action="system/update.php" method="POST">
                    <table id="example" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">หมายเลขงาน</th>
                                <th class="text-center">รายการ</th>
                                <th class="text-center">คุณสมบัติ</th>
                                <th class="text-center">จำนวน</th>
                                <th class="text-center">ราคา</th>
                                <th class="text-center">ปุ่ม</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $result) : ?>
                                <?php for ($i = 1; $i <= 15; $i++) : ?>
                                    <?php
                                    // ตรวจสอบว่า model, quality, amount, price ไม่ใช่ค่าว่าง
                                    if ($result["model$i"] !== null && $result["quality$i"] !== null && $result["amount$i"] !== null && $result["price$i"] !== null) :
                                    ?>
                                        <tr class="text-center">
                                            <td><?= $result["numberWork"] ?></td>
                                            <td><?= $result["model$i"] ?></td>
                                            <td><?= $result["quality$i"] ?></td>
                                            <td><?= $result["amount$i"] ?></td>
                                            <td><?= $result["price$i"] ?></td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" name="selectedRow<?= $i ?>" value="<?= $result["id"] ?>">
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="d-grid gap-3 mt-3">
                        <button class="btn btn-primary" type="submit" name="updateStatus">อัพเดท</button>
                    </div>
                </form>


            </div>
            <div class="col-sm-6">
                <form action="system/insert.php" method="POST">


                    <table id="example" class="table table-striped table-bordered">
                        <thead>
                            <?php
                            $sql = "SELECT numberWork FROM orderdata";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if (!$result) {
                                $numberWork = '1/67';
                            } else {
                                foreach ($result as $row) {
                                    $selectedValue = $row['numberWork']; // หรือ $_GET ตามที่คุณใช้งาน
                                    // แยกเลขด้านหน้า (1/67, 2/67, 3/67, 4/67) เพื่อให้ได้เฉพาะเลขด้านหน้า
                                    list($numerator, $denominator) = explode('/', $selectedValue);
                                    // บวกเพิ่มทีละหนึ่ง
                                    $newNumerator = intval($numerator) + 1;
                                    // สร้างค่าใหม่ที่จะตรวจสอบและบันทึกลงในฐานข้อมูล
                                    $newValueToCheck = $newNumerator . '/' . $denominator;
                                }
                            }
                            ?>
                            <tr style="border: none;" class="text-center">
                                <div class="d-flex justify-content-between mb-3">
                                    <th style="border: none;" colspan="3">
                                        <h5 style="font-weight: bold"><?= $newValueToCheck ?></h5>

                                    </th>
                                    <th style="border: none;" colspan="1">

                                        <button type="submit" name="CheckAll" class="btn btn-primary">บันทึกข้อมูล</button>
                                    </th>
                                </div>
                                <input type="hidden" name="dateWithdraw" class="form-control thaiDateInput">
                                <input type="hidden" name="numberWork" value="<?= $newValueToCheck ?>">
                                <input type="hidden" name="username" value="<?= $admin ?>">
                            </tr>
                            <tr>
                                <th class="text-center">รายการ</th>
                                <th class="text-center">คุณสมบัติ</th>
                                <th class="text-center">จำนวน</th>
                                <th class="text-center">ราคา</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($i = 1; $i <= 15; $i++) { ?>
                                <tr class="text-center">
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
                                    <td><input class="form-control" type="text" name="amount<?= $i ?>"></td>
                                    <td><input class="form-control" type="text" name="price<?= $i ?>"></td>
                                </tr>
                            <?php }
                            ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        new DataTable('#example');
    </script>

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
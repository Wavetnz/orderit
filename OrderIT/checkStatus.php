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
    <title>New | IT ORDER PRO</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
</head>

<body>

    <?php navbar();
    ?>

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
        <table id="example" class="table table-hover table-bordered border-secondary">
            <thead>
                <tr class="text-center">
                    <th scope="col">หมายเลขออกงาน</th>
                    <th scope="col">ประเภทการเบิก</th>
                    <th scope="col">รายการอุปกรณ์</th>
                    <th scope="col">วันที่ออกใบเบิก</th>
                    <th scope="col">เหตุผลและความจำเป็น</th>
                    <th scope="col">หมายเหตุ</th>
                    <th scope="col">วันที่รับส่งเอกสาร</th>
                    <th scope="col">สถานะปัจจุบัน</th>
                    <th scope="col">ดูข้อมูล</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php
                $status = $_GET['status'];
                $sql = "SELECT od.*,dv.device_name,dw.withdraw_name 
                FROM orderdata AS od
                INNER JOIN device AS dv ON od.refDevice = dv.device_id
                INNER JOIN withdraw AS dw ON od.refWithdraw = dw.withdraw_id
                WHERE status = :status";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(":status", $status);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
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



                foreach ($result as $row) {
                    $receiptDateFromDB = $row['receiptDate'];
                    $deliveryDateFromDB = $row['deliveryDate'];
                    $receiptThai = formatDateThai($receiptDateFromDB);
                    $deliveryThai = formatDateThai($deliveryDateFromDB);

                    $options = [
                        1 => 'รอรับเอกสารจากหน่วยงาน',
                        2 => 'รอส่งเอกสารไปพัสดุ',
                        3 => 'รอพัสดุสั่งของ',
                        4 => 'รอหมายเลขครุภัณฑ์',
                        5 => 'ปิดงาน',
                        6 => 'ยกเลิก',
                    ];

                    $status = $row['status'];

                    if (isset($options[$status])) {
                        $statusName = $options[$status];
                    } else {
                        $statusName = "ไม่ระบุสถานะ";
                    }
                ?>
                    <tr class="text-center">
                        <td>
                            <?= $row['numberWork'] ?>
                        </td>
                        <td>
                            <?= $row['withdraw_name'] ?>
                        </td>
                        <td>
                            <?= $row['device_name'] ?>
                        </td>
                        <td class="thaiDateInput">
                            <?= $receiptThai ?>
                        </td>
                        <td>
                            <?= $row['reason'] ?>
                        </td>
                        <td>
                            <?= $row['note'] ?>
                        </td>
                        <td>
                            <?= $deliveryThai  ?>
                        </td>
                        <td>
                            <?= $statusName ?>
                        </td>

                        <td>
                            <a class="btn btn-primary" href="check.php?numberWork=<?= $row['id'] ?>">ดูข้อมูล</a>
                        </td>
                    </tr>
                <?php    }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        new DataTable('#example');
    </script>





    <?php SC5() ?>
</body>

</html>
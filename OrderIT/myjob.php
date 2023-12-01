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
<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.2.1 -->
    <?php bs5() ?>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

</head>

<body>
    <?php navbar() ?>
    <div class="table-responsive">
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

        <hr>
        <table id="dataAll" class="table table-light">
            <thead>
                <tr class="text-center">
                    <th scope="col">หมายเลขงาน</th>
                    <th scope="col">วันที่</th>
                    <th scope="col">เวลาแจ้ง</th>
                    <th scope="col">อุปกรณ์</th>
                    <th scope="col">หมายเลขครุภัณฑ์</th>
                    <th scope="col">อาการเสีย</th>
                    <th scope="col">ผู้แจ้ง</th>
                    <th scope="col">หน่วยงาน</th>
                    <th scope="col">เบอร์ติดต่อกลับ</th>
                    <th scope="col">สถานะ</th>
                    <th scope="col">ปุ่มงาน</th>
                </tr>
            </thead>
            <tbody>
                <?php
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

                $sql = "SELECT dp.*,dt.depart_name 
                    FROM data_report as dp
                    INNER JOIN depart as dt ON dp.department = dt.depart_id
                    WHERE dp.username = :username
                    ";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(":username", $admin);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $i = 0;
                foreach ($result as $row) {
                    $i++;
                    $dateWithdrawFromDB = $row['date_report'];

                    $dateWithdrawThai = formatDateThai($dateWithdrawFromDB);

                ?>
                    <tr style="text-align: center;" class="text-center">
                        <td scope="row"><?= $row['id'] ?></td>
                        <td scope="row"><?= $dateWithdrawThai ?></td>
                        <td><?= $row['time_report'] ?> น.</td>
                        <td><?= $row['device'] ?></td>
                        <td><?= $row['number_device'] ?></td>
                        <td><?= $row['report'] ?></td>
                        <td><?= $row['reporter'] ?></td>
                        <td><?= $row['depart_name'] ?></td>
                        <td><?= $row['tel'] ?></td>
                        <?php
                        if ($row['status'] == 1) {
                            $statusText = "ยังไม่ได้ดำเนินการ";
                        } else if ($row['status'] == 2) {
                            $statusText = "กำลังดำเนินการ";
                        } else if ($row['status'] == 3) {
                            $statusText = "รออะไหล่";
                        } else {
                            $statusText = "เสร็จสิ้น";
                        }
                        ?>
                        <?php if ($row['status'] == 4) { ?>

                            <td style="background-color: green;color:white"><?= $statusText ?></td>
                        <?php  } else if ($row['status'] == 3) { ?>
                            <td style="background-color: blue;color:white"><?= $statusText ?></td>
                        <?php } else if ($row['status'] == 2) { ?>
                            <td style="background-color: orange;color:white"><?= $statusText ?></td>
                        <?php } else if ($row['status'] == 1) { ?>
                            <td style="background-color: red;color:white"><?= $statusText ?></td>

                        <?php } else { ?>
                            <td><?= $statusText ?></td>
                        <?php    } ?>
                        <form action="system/insert.php" method="post">

                            <td>
                                <?php if ($row['status'] == 1) { ?>
                                    <button type="submit" name="inTime" style=" background-color: orange;color:white;border: 1px solid orange" class="btn mb-3 btn-primary">เริ่มดำเนินการ</button>
                                <?php } else if ($row['status'] == 2) { ?>
                                    <button type="button" style="background-color: orange;color:white;border: 1px solid orange" class="btn mb-3 btn-primary" data-bs-toggle="modal" data-bs-target="#workflow<?= $i ?>">กำลังดำเนินการ</button>
                                <?php   } else if ($row['status'] == 3) { ?>
                                    <button type="button" style=" background-color: orange;color:white;border: 1px solid orange" class="btn mb-3 btn-primary" data-bs-toggle="modal" data-bs-target="#workflow<?= $i ?>">กำลังดำเนินการ</button>
                                <?php  } ?>


                                <input type="hidden" name="id" value="<?= $row['id'] ?>">


                                <div class="modal fade" id="workflow<?= $i ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">รายละเอียดงาน</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">

                                                <div class="table-responsive">
                                                    <table class="table table-primary">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col"></th>
                                                                <th scope="col"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="">
                                                                <td scope="row">หมายเลขงาน</td>
                                                                <td scope="row"><?= $row['id'] ?></td>
                                                            </tr>
                                                            <tr class="">
                                                                <td scope="row">เวลาแจ้ง</td>
                                                                <td scope="row"><?= $row['time_report'] ?></td>
                                                            </tr>
                                                            <tr class="">
                                                                <td scope="row">อุปกรณ์</td>
                                                                <td scope="row"><?= $row['device'] ?></td>
                                                            </tr>
                                                            <tr class="">
                                                                <td scope="row">หมายเลขครุภัณฑ์ (ถ้ามี)</td>
                                                                <td scope="row"><?= $row['number_device'] ?></td>
                                                            </tr>
                                                            <tr class="">
                                                                <td scope="row">หมายเลข IP addrees</td>
                                                                <td scope="row"><?= $row['ip_address'] ?></td>
                                                            </tr>
                                                            <tr class="">
                                                                <td scope="row">อาการเสีย</td>
                                                                <td scope="row"><?= $row['report'] ?></td>
                                                            </tr>
                                                            <tr class="">
                                                                <td scope="row">ผู้แจ้ง</td>
                                                                <td scope="row"><?= $row['reporter'] ?></td>
                                                            </tr>
                                                            <tr class="">
                                                                <td scope="row">หน่วยงาน</td>
                                                                <td scope="row"><?= $row['depart_name'] ?></td>
                                                            </tr>
                                                            <tr class="">
                                                                <td scope="row">เบอร์ติดต่อกลับ</td>
                                                                <td scope="row"><?= $row['tel'] ?></td>
                                                            </tr>
                                                            <tr class="">
                                                                <td scope="row">เวลารับงาน</td>
                                                                <td scope="row"><?= $row['take'] ?></td>
                                                            </tr>
                                                            <form action="system/insert.php" method="POST">
                                                                <tr class="">
                                                                    <td scope="row">ปัญหาเกี่ยวกับ</td>
                                                                    <td scope="row">
                                                                        <input value="<?= $row['problem'] ?>" required type="text" class="form-control" name="problem">
                                                                    </td>
                                                                <tr class="">
                                                                    <td scope="row">รายละเอียด</td>
                                                                    <td scope="row">
                                                                        <input value="<?= $row['description'] ?>"  required type="text" class="form-control" name="description">
                                                                    </td>
                                                                </tr>
                                                                <tr class="">
                                                                    <td scope="row">เบิกอะไหล่</td>
                                                                    <td scope="row">
                                                                        <input value="<?= $row['withdraw'] ?>"  required type="text" class="form-control" name="withdraw">
                                                                    </td>
                                                                </tr>
                                                                <tr class="">
                                                                    <td scope="row">เวลาปิดงาน (ถ้ามี)</td>
                                                                    <td scope="row">
                                                                        <input value="<?= $row['close_date'] ?>"  type="time" class="form-control" id="time_report" name="close_date">
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                                <button type="submit" name="withdrawSubmit" class="btn btn-primary">เบิกอะไหล่</button>
                                                <button type="submit" name="CloseSubmit" class="btn btn-success">ปิดงาน</button>
                                            </div>
                        </form>
    </div>
    </div>
    </div>


    </td>
    </form>
    </tr>
<?php
                }
?>
</tbody>
</table>

</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    new DataTable('#dataAll');
    new DataTable('#dataAllTAKE');
</script>
<?php SC5() ?>
</body>

</html>
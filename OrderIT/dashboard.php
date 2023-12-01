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
    <link rel="stylesheet" href="css/style.css">

    <!-- Bootstrap CSS v5.2.1 -->
    <?php bs5() ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script>
        // Function to reload the page
        function refreshPage() {
            location.reload();
        }

        // Set timeout to refresh the page every 1 minute (60000 milliseconds)
        setTimeout(refreshPage, 30000);
    </script>
</head>

<body>
    <?php navbar() ?>
    <div class="container">
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
        <div class="row">
            <div class="col-sm-12 col-lg-6 col-md-12">
                <h1 class="text-center">สรุปยอดจำนวนงาน</h1>
                <div class="d-flex justify-content-center" style="width:100%; height: 450px">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
            <div class="col-sm-12 col-lg-6 col-md-12">
                <div class="table-responsive">
                    <h1 class="mb-3 mt-3">กำลังดำเนินการ</h1>
                    <table id="inTime" class="table table-light">
                        <thead>
                            <tr>
                                <th scope="col">หมายเลขงาน</th>
                                <th scope="col">ผู้ซ่อม</th>
                                <th scope="col">อุปกรณ์</th>
                                <th scope="col">อาการเสีย</th>
                                <th scope="col">หน่วยงาน</th>
                                <th scope="col">เวลาแจ้ง</th>
                                <th scope="col">เวลารับงาน</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT dp.id, dp.device, dp.report, dp.time_report, dp.take, dt.depart_name, adm.fname, adm.lname
        FROM data_report as dp
        INNER JOIN depart as dt ON dp.department = dt.depart_id 
        INNER JOIN admin as adm ON dp.username = adm.username
        WHERE dp.status = 2
        ORDER BY dp.id DESC";

                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($result as $row) {
                            ?>
                                <tr class="text-center">
                                    <td><?= $row['id'] ?></td>
                                    <td><?= $row['fname'] . ' ' . $row['lname'] ?></td>
                                    <td><?= $row['device'] ?></td>
                                    <td><?= $row['report'] ?></td>
                                    <td><?= $row['depart_name'] ?></td>
                                    <td><?= $row['time_report'] ?></td>
                                    <td><?= $row['take'] ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div style="margin-top: -120px;" class="col-sm-12 col-lg-12 col-md-12">
                <h1 class="mb-3 mt-3">งานที่ยังไม่ได้รับ</h1>
                <hr>
                <div class="table-responsive">
                    <form action="system/insert.php" method="post">
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
                                    <th scope="col">ปุ่มรับงาน</th>
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
                    ";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                $i = 0;
                                foreach ($result as $row) {
                                    $i++;
                                    $dateWithdrawFromDB = $row['date_report'];

                                    $dateWithdrawThai = formatDateThai($dateWithdrawFromDB);
                                    if ($row['status'] == 0) {
                                ?>
                                        <tr class="text-center">
                                            <td scope="row"><?= $row['id'] ?></td>
                                            <td scope="row"><?= $dateWithdrawThai ?></td>
                                            <td><?= $row['time_report'] ?> น.</td>
                                            <td><?= $row['device'] ?></td>
                                            <td><?= $row['number_device'] ?></td>
                                            <td><?= $row['report'] ?></td>
                                            <td><?= $row['reporter'] ?></td>
                                            <td><?= $row['depart_name'] ?></td>
                                            <td><?= $row['tel'] ?></td>
                                            <td>
                                                <?php
                                                if (!$row['username']) { ?>

                                                    <button type="submit" name="takeaway" class="btn btn-primary">รับงาน</button>
                                                    <input type="hidden" name="username" value="<?= $admin ?>">
                                                    <input type="hidden" name="take" class="time_report" value="<?= $currentTime ?>">
                                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <?php  } else { ?>
                                                    <button type="button" disabled class="btn btn-primary">รับงาน</button>

                                                <?php  }
                                                ?>
                                            </td>
                                        </tr>
                                <?php }
                                }
                                ?>
                    </form>
                    </tbody>
                    </table>
                </div>
            </div>


            <div class="col-sm-12 col-lg-6 col-md-12">
                <div class="table-responsive">
                    <h1 class="mb-3 mt-3">รออะไหล่</h1>
                    <hr>
                    <table id="wait" class="table table-light">
                        <thead>
                            <tr>
                                <th scope="col">หมายเลขงาน</th>
                                <th scope="col">ผู้ซ่อม</th>
                                <th scope="col">อุปกรณ์</th>
                                <th scope="col">อาการเสีย</th>
                                <th scope="col">หน่วยงาน</th>
                                <th scope="col">เวลาแจ้ง</th>
                                <th scope="col">เวลารับงาน</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT dp.*,dt.depart_name 
                    FROM data_report as dp
                    INNER JOIN depart as dt ON dp.department = dt.depart_id
                    ";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($result as $row) {

                                if ($row['status'] == 3) {
                            ?>
                                    <tr class="text-center">
                                        <td><?= $row['id'] ?></td>
                                        <td>
                                            <?php
                                            $sql = "SELECT * FROM admin";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();
                                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            foreach ($result as $row2) {
                                                if ($row['username'] == $row2['username']) {
                                                    echo $row2['fname'] . ' ' . $row2['lname'];
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td><?= $row['device'] ?></td>
                                        <td><?= $row['report'] ?></td>
                                        <td><?= $row['depart_name'] ?></td>
                                        <td><?= $row['time_report'] ?></td>
                                        <td><?= $row['take'] ?></td>

                                    </tr>
                            <?php }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <hr>
            </div>
            <div class="col-sm-12 col-lg-6 col-md-12">
                <div class="table-responsive">
                    <h1 class="mb-3 mt-3">งานที่เสร็จ</h1>
                    <hr>
                    <table id="success" class="table table-light">
                        <thead>
                            <tr>
                                <th scope="col">หมายเลขงาน</th>
                                <th scope="col">ผู้ซ่อม</th>
                                <th scope="col">อุปกรณ์</th>
                                <th scope="col">อาการเสีย</th>
                                <th scope="col">หน่วยงาน</th>
                                <th scope="col">เวลารับงาน</th>
                                <th scope="col">เวลาปิดงาน</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT dp.*,dt.depart_name 
                    FROM data_report as dp
                    INNER JOIN depart as dt ON dp.department = dt.depart_id
                    ";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($result as $row) {

                                if ($row['status'] == 4) {
                            ?>
                                    <tr class="text-center">
                                        <td><?= $row['id'] ?></td>
                                        <td>
                                            <?php
                                            $sql = "SELECT * FROM admin";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();
                                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            foreach ($result as $row2) {
                                                if ($row['username'] == $row2['username']) {
                                                    echo $row2['fname'] . ' ' . $row2['lname'];
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td><?= $row['device'] ?></td>
                                        <td><?= $row['report'] ?></td>
                                        <td><?= $row['depart_name'] ?></td>
                                        <td><?= $row['take'] ?></td>
                                        <td><?= $row['close_date'] ?></td>

                                    </tr>
                            <?php }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('myChart');
                <?php
                $sql_statuses = "SELECT status, COUNT(*) as count FROM data_report GROUP BY status";
                $stmt_statuses = $conn->prepare($sql_statuses);
                $stmt_statuses->execute();
                $statuses = $stmt_statuses->fetchAll(PDO::FETCH_ASSOC);

                // แปลง labels และ counts จาก status
                $status_labels = ['งานที่ยังไม่ได้รับ', 'กำลังดำเนินการ', 'รออะไหล่', 'งานที่เสร็จ'];
                $status_counts = [0, 0, 0, 0]; // สร้างตัวแปรเริ่มต้นที่มีค่าเป็น 0


                // นับจำนวนของแต่ละ status
                foreach ($statuses as $status) {
                    $status_code = $status['status'];
                    $count = $status['count'];

                    // กำหนดค่าในตำแหน่งที่ถูกต้องใน $status_counts
                    $status_counts[$status_code - 1] = $count;
                }

                // แปลงอาร์เรย์ counts เป็นสตริงที่คั่นด้วยเครื่องหมายจุลภาค
                $status_counts_str = implode(', ', $status_counts);
                ?>
                // Data from PHP
                const data = {
                    labels: <?= json_encode($status_labels) ?>,
                    datasets: [{
                        label: 'จำนวนงาน',
                        data: [<?= $status_counts_str ?>],

                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                        ],
                        borderWidth: 1
                    }]
                };

                const options = {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                };

                new Chart(ctx, {
                    type: 'bar',
                    data: data,
                    options: options
                });
            });
        </script>
        <hr>
    </div>
    <br>
    <script>
        // Get the current date and time
        const now = new Date();

        // Format the time as HH:mm
        const currentTime = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;

        // Set the current time as the default value for the input fields
        const timeReportInputs = document.querySelectorAll('.time_report');
        timeReportInputs.forEach(input => input.value = currentTime);
    </script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataAll').DataTable({
                order: [
                    [0, 'desc']
                ] // assuming you want to sort the first column in ascending order
            });

            $('#dataAllTAKE').DataTable({
                order: [
                    [0, 'desc']
                ] // adjust the column index as needed
            });

            $('#inTime').DataTable({
                order: [
                    [0, 'desc']
                ] // adjust the column index as needed
            });

            $('#wait').DataTable({
                order: [
                    [0, 'desc']
                ] // adjust the column index as needed
            });

            $('#success').DataTable({
                order: [
                    [0, 'desc']
                ] // adjust the column index as needed
            });
        });
    </script>
    <?php SC5() ?>
</body>

</html>
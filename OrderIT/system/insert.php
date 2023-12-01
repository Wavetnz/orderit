<?php
session_start();
require_once '../config/db.php';

if (isset($_POST['addUsers'])) { // เพิ่ม Admin
    $username = $_POST['username'];
    $password = $_POST['password'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    try {
        $sql = "SELECT * FROM admin WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            if ($result['username'] == $username) {
                $_SESSION['error'] = 'ชื่อผู้ใช้นี้มีอยู่แล้ว';
                header('location: ../insertData.php');
            }
        } else if (!isset($_SESSION['error'])) {
            $passwordhash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO admin VALUES(:username,:password,:fname,:lname)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":password", $passwordhash);
            $stmt->bindParam(":fname", $fname);
            $stmt->bindParam(":lname", $lname);

            if ($stmt->execute()) {
                $_SESSION["success"] = "เพิ่มข้อมูลสำเร็จ";
                header("location: ../insertData.php");
            }
        }
    } catch (PDOException $e) {
        echo '' . $e->getMessage() . '';
    }
}
if (isset($_POST['addWithdraw'])) { // เพิ่ม ประเภทการเบิก
    $withdraw_name = $_POST['withdraw_name'];
    try {
        $sql = "SELECT * FROM withdraw WHERE withdraw_name = :withdraw_name";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":withdraw_name", $withdraw_name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            if ($result['withdraw_name'] == $withdraw_name) {
                $_SESSION['error'] = 'มีรายการนี้อยู่แล้ว';
                header('location: ../insertData.php');
            }
        } else if (!isset($_SESSION['error'])) {
            $sql = "INSERT INTO withdraw(withdraw_name) VALUES(:withdraw_name)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":withdraw_name", $withdraw_name);

            if ($stmt->execute()) {
                $_SESSION["success"] = "เพิ่มข้อมูลสำเร็จ";
                header("location: ../insertData.php");
            }
        }
    } catch (PDOException $e) {
        echo '' . $e->getMessage() . '';
    }
}
if (isset($_POST['addListWork'])) { // เพิ่ม ประเภทงาน
    $work_name = $_POST['work_name'];
    try {
        $sql = "SELECT * FROM listwork WHERE work_name = :work_name";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":work_name", $work_name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            if ($result['work_name'] == $work_name) {
                $_SESSION['error'] = 'มีรายการนี้อยู่แล้ว';
                header('location: ../insertData.php');
            }
        } else if (!isset($_SESSION['error'])) {
            $sql = "INSERT INTO listwork(work_name) VALUES(:work_name)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":work_name", $work_name);

            if ($stmt->execute()) {
                $_SESSION["success"] = "เพิ่มข้อมูลสำเร็จ";
                header("location: ../insertData.php");
            }
        }
    } catch (PDOException $e) {
        echo '' . $e->getMessage() . '';
    }
}
if (isset($_POST['addDevice'])) { // เพิ่ม รายการอุปกรณ์
    $device_name = $_POST['device_name'];
    try {
        $sql = "SELECT * FROM device WHERE device_name = :device_name";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":device_name", $device_name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            if ($result['device_name'] == $device_name) {
                $_SESSION['error'] = 'มีรายการนี้อยู่แล้ว';
                header('location: ../insertData.php');
            }
        } else if (!isset($_SESSION['error'])) {
            $sql = "INSERT INTO device(device_name) VALUES(:device_name)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":device_name", $device_name);

            if ($stmt->execute()) {
                $_SESSION["success"] = "เพิ่มข้อมูลสำเร็จ";
                header("location: ../insertData.php");
            }
        }
    } catch (PDOException $e) {
        echo '' . $e->getMessage() . '';
    }
}
if (isset($_POST['addmodels'])) { // เพิ่ม รายการอุปกรณ์
    $models_name = $_POST['models_name'];
    try {
        $sql = "SELECT * FROM device_models WHERE models_name = :models_name";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":models_name", $models_name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            if ($result['models_name'] == $models_name) {
                $_SESSION['error'] = 'มีรายการนี้อยู่แล้ว';
                header('location: ../insertData.php');
            }
        } else if (!isset($_SESSION['error'])) {
            $sql = "INSERT INTO device_models(models_name) VALUES(:models_name)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":models_name", $models_name);

            if ($stmt->execute()) {
                $_SESSION["success"] = "เพิ่มข้อมูลสำเร็จ";
                header("location: ../insertData.php");
            }
        }
    } catch (PDOException $e) {
        echo '' . $e->getMessage() . '';
    }
}
if (isset($_POST['addDepart'])) { // เพิ่ม ประเภทงาน
    $depart_name = $_POST['depart_name'];
    try {
        $sql = "SELECT * FROM depart WHERE depart_name = :depart_name";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":depart_name", $depart_name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            if ($result['depart_name'] == $depart_name) {
                $_SESSION['error'] = 'มีรายการนี้อยู่แล้ว';
                header('location: ../insertData.php');
            }
        } else if (!isset($_SESSION['error'])) {
            $sql = "INSERT INTO depart(depart_name) VALUES(:depart_name)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":depart_name", $depart_name);

            if ($stmt->execute()) {
                $_SESSION["success"] = "เพิ่มข้อมูลสำเร็จ";
                header("location: ../insertData.php");
            }
        }
    } catch (PDOException $e) {
        echo '' . $e->getMessage() . '';
    }
}
if (isset($_POST['addOffer'])) { // เพิ่ม ร้านที่เสนอราคา
    $offer_name = $_POST['offer_name'];
    try {
        $sql = "SELECT * FROM offer WHERE offer_name = :offer_name";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":offer_name", $offer_name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            if ($result['offer_name'] == $offer_name) {
                $_SESSION['error'] = 'มีรายการนี้อยู่แล้ว';
                header('location: ../insertData.php');
            }
        } else if (!isset($_SESSION['error'])) {
            $sql = "INSERT INTO offer(offer_name) VALUES(:offer_name)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":offer_name", $offer_name);

            if ($stmt->execute()) {
                $_SESSION["success"] = "เพิ่มข้อมูลสำเร็จ";
                header("location: ../insertData.php");
            }
        }
    } catch (PDOException $e) {
        echo '' . $e->getMessage() . '';
    }
}

// ตรวจสอบว่ามีการส่งข้อมูลมาจากฟอร์มหรือไม่
if (isset($_POST['submit'])) {
    // รับข้อมูลจากฟอร์ม
    $numberWork = $_POST["numberWork"];
    $dateWithdraw = $_POST["dateWithdraw"];
    $refWithdraw = $_POST["ref_withdraw"];
    $refWork = $_POST["ref_work"];
    $refDevice = $_POST["ref_device"];
    $numberDevice1 = $_POST["number_device_1"];
    $numberDevice2 = $_POST["number_device_2"];
    $numberDevice3 = $_POST["number_device_3"];
    $reason = $_POST["reason"];
    $report = $_POST["report"];
    $refDepart = $_POST["depart_id"];
    $refUsername = $_POST["ref_username"];
    $refOffer = $_POST["ref_offer"];
    $quotation = $_POST["quotation"];
    // $receiptDate = $_POST["receipt_date"];
    // $deliveryDate = $_POST["delivery_date"];
    // $closeDate = $_POST["close_date"];
    $note = $_POST["note"];
    $status = $_POST["status"];

    $list1 = $_POST["list1"];

    // ตรวจสอบว่า list1 มีค่าว่างหรือไม่
    if (empty($list1)) {
        $_SESSION["error"] = "กรุณาเลือกรายการ";
        header("Location: ../index.php");
    } else if ($list1 == "") {
        $_SESSION["error"] = "กรุณาเลือกรายการ";
        header("Location: ../index.php");
    }
    for ($i = 1; $i <= 15; $i++) {
        ${"list$i"} = $_POST["list$i"];
        ${"quality$i"} = $_POST["quality$i"];
        ${"amount$i"} = $_POST["amount$i"];
        ${"price$i"} = $_POST["price$i"];
        ${"unit$i"} = $_POST["unit$i"];
    }


    try {
        $sql = "INSERT INTO orderdata (numberWork, dateWithdraw, refWithdraw, refWork, refDevice, ";
        for ($i = 1; $i <= 15; $i++) {
            $sql .= "list$i, quality$i, amount$i, price$i, unit$i";
            if ($i < 15) {
                $sql .= ", ";
            }
        }
        $sql .= ", reason, report, refDepart, refUsername, refOffer, quotation, note, status,numberDevice1,numberDevice2,numberDevice3)
                VALUES (:numberWork, :dateWithdraw, :refWithdraw, :refWork, :refDevice, ";
        for ($i = 1; $i <= 15; $i++) {
            $sql .= ":list$i, :quality$i, :amount$i, :price$i, :unit$i";
            if ($i < 15) {
                $sql .= ", ";
            }
        }
        $sql .= ", :reason, :report, :refDepart, :refUsername, :refOffer, :quotation, :note, :status,:numberDevice1,:numberDevice2,:numberDevice3)";

        // เตรียมและสร้าง statement
        $stmt = $conn->prepare($sql);

        // ผูกค่าข้อมูล
        $stmt->bindParam(':numberWork', $numberWork);
        $stmt->bindParam(':dateWithdraw', $dateWithdraw);
        $stmt->bindParam(':refWithdraw', $refWithdraw);
        $stmt->bindParam(':refWork', $refWork);
        $stmt->bindParam(':refDevice', $refDevice);
        for ($i = 1; $i <= 15; $i++) {
            $stmt->bindParam(":list$i", ${"list$i"});
            $stmt->bindParam(":quality$i", ${"quality$i"});
            $stmt->bindParam(":amount$i", ${"amount$i"});
            $stmt->bindParam(":price$i", ${"price$i"});
            $stmt->bindParam(":unit$i", ${"unit$i"});
        }
        $stmt->bindParam(':reason', $reason);
        $stmt->bindParam(':report', $report);
        $stmt->bindParam(':refDepart', $refDepart);
        $stmt->bindParam(':refUsername', $refUsername);
        $stmt->bindParam(':refOffer', $refOffer);
        $stmt->bindParam(':quotation', $quotation);
        // $stmt->bindParam(':receiptDate', $receiptDate);
        // $stmt->bindParam(':deliveryDate', $deliveryDate);
        // $stmt->bindParam(':closeDate', $closeDate);
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':numberDevice1', $numberDevice1);
        $stmt->bindParam(':numberDevice2', $numberDevice2);
        $stmt->bindParam(':numberDevice3', $numberDevice3);

        // ทำการเพิ่มข้อมูล
        if ($stmt->execute()) {
            $_SESSION["success"] = "เพิ่มข้อมูลสำเร็จ";
            header("location: ../index.php");
        } else {
            $_SESSION["error"] = "พบข้อผิดพลาด";
            header("location: ../index.php");
        }
    } catch (PDOException $e) {
        echo "" . $e->getMessage() . "";
    }
}
if (isset($_POST['CheckAll'])) {

    $numberWork = $_POST["numberWork"];
    $dateWithdraw = $_POST["dateWithdraw"];
    $refWithdraw = 23;
    $refWork = 10;
    $refDevice = 17;
    $numberDevice1 = "-";
    $numberDevice2 = "-";
    $numberDevice3 = "-";
    $reason = "เบิกอะไหล่รายสัปดาห์ตามเอกสารแนบ";
    $report = "-";
    $refDepart = 3;
    $refUsername = $_POST["username"];
    $refOffer = 1;
    $quotation = "-";
    $receiptDate = $_POST["dateWithdraw"];
    $deliveryDate = $_POST["dateWithdraw"];
    $closeDate = $_POST["dateWithdraw"];
    $note = "-";
    $status = 5;

    for ($i = 1; $i <= 15; $i++) {
        ${"list$i"} = $_POST["list$i"];
        ${"quality$i"} = $_POST["quality$i"];
        ${"amount$i"} = $_POST["amount$i"];
        ${"price$i"} = $_POST["price$i"];
        ${"unit$i"} = "";
    }


    try {
        $sql = "INSERT INTO orderdata (numberWork, dateWithdraw, refWithdraw, refWork, refDevice, ";
        for ($i = 1; $i <= 15; $i++) {
            $sql .= "list$i, quality$i, amount$i, price$i, unit$i";
            if ($i < 15) {
                $sql .= ", ";
            }
        }
        $sql .= ", reason, report, refDepart, refUsername, refOffer, quotation, receiptDate, deliveryDate, closeDate, note, status, numberDevice1, numberDevice2, numberDevice3) ";
        $sql .= "VALUES (:numberWork, :dateWithdraw, :refWithdraw, :refWork, :refDevice, ";
        for ($i = 1; $i <= 15; $i++) {
            $sql .= ":list$i, :quality$i, :amount$i, :price$i, :unit$i";
            if ($i < 15) {
                $sql .= ", ";
            }
        }
        $sql .= ", :reason, :report, :refDepart, :refUsername, :refOffer, :quotation, :receiptDate, :deliveryDate, :closeDate, :note, :status,:numberDevice1,:numberDevice2,:numberDevice3)";

        // เตรียมและสร้าง statement
        $stmt = $conn->prepare($sql);

        // ผูกค่าข้อมูล
        $stmt->bindParam(':numberWork', $numberWork);
        $stmt->bindParam(':dateWithdraw', $dateWithdraw);
        $stmt->bindParam(':refWithdraw', $refWithdraw);  // เพิ่มบรรทัดนี้
        $stmt->bindParam(':refWork', $refWork);          // เพิ่มบรรทัดนี้
        $stmt->bindParam(':refDevice', $refDevice);
        for ($i = 1; $i <= 15; $i++) {
            $stmt->bindParam(":list$i", ${"list$i"});
            $stmt->bindParam(":quality$i", ${"quality$i"});
            $stmt->bindParam(":amount$i", ${"amount$i"});
            $stmt->bindParam(":price$i", ${"price$i"});
            $stmt->bindParam(":unit$i", ${"unit$i"});
        }
        $stmt->bindParam(':reason', $reason);
        $stmt->bindParam(':report', $report);
        $stmt->bindParam(':refDepart', $refDepart);
        $stmt->bindParam(':refUsername', $refUsername);
        $stmt->bindParam(':refOffer', $refOffer);
        $stmt->bindParam(':quotation', $quotation);
        $stmt->bindParam(':receiptDate', $receiptDate);
        $stmt->bindParam(':deliveryDate', $deliveryDate);
        $stmt->bindParam(':closeDate', $closeDate);
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':numberDevice1', $numberDevice1);
        $stmt->bindParam(':numberDevice2', $numberDevice2);
        $stmt->bindParam(':numberDevice3', $numberDevice3);

        // ทำการเพิ่มข้อมูล
        if ($stmt->execute()) {
            $_SESSION["success"] = "เพิ่มข้อมูลสำเร็จ";
            header("location: ../checkAll.php");
        } else {
            $_SESSION["error"] = "พบข้อผิดพลาด";
            header("location: ../checkAll.php");
        }
    } catch (PDOException $e) {
        echo "" . $e->getMessage() . "";
    }
}
if (isset($_POST['takeaway'])) { // เพิ่ม รายการอุปกรณ์
    $id = $_POST['id'];
    $username = $_POST['username'];
    $take = $_POST['take'];
    $status = 2;
    try {
        $sql = "UPDATE data_report SET username = :username, take = :take, status = :status WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":take", $take);
        $stmt->bindParam(":id", $id);

        if ($stmt->execute()) {
            $_SESSION["success"] = "รับงานเรียบร้อยแล้ว";
            header("location: ../dashboard.php");
        } else {
            $_SESSION["error"] = "พบข้อผิดพลาด";
            header("location: ../dashboard.php");
        }
    } catch (PDOException $e) {
        echo '' . $e->getMessage() . '';
    }
}
if (isset($_POST['inTime'])) { // เพิ่ม รายการอุปกรณ์
    $id = $_POST['id'];

    $status = 2;
    try {
        $sql = "UPDATE data_report SET status = :status WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id);

        if ($stmt->execute()) {
            $_SESSION["success"] = "กำลังดำเนินการ";
            header("location: ../myjob.php");
        } else {
            $_SESSION["error"] = "พบข้อผิดพลาด";
            header("location: ../myjob.php");
        }
    } catch (PDOException $e) {
        echo '' . $e->getMessage() . '';
    }
}
if (isset($_POST['withdrawSubmit'])) { // เพิ่ม รายการอุปกรณ์
    $id = $_POST['id'];
    $problem = $_POST['problem'];
    $description = $_POST['description'];
    $withdraw = $_POST['withdraw'];
    $close_date = $_POST['close_date'];
    $status = 3;
    try {
        $sql = "UPDATE data_report SET problem = :problem, description = :description , withdraw = :withdraw, close_date = :close_date , status = :status WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":problem", $problem);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":withdraw", $withdraw);
        $stmt->bindParam(":close_date", $close_date);
        $stmt->bindParam(":id", $id);

        if ($stmt->execute()) {
            $_SESSION["success"] = "เพิ่มอะไหล่";
            header("location: ../index.php");
        } else {
            $_SESSION["error"] = "พบข้อผิดพลาด";
            header("location: ../myjob.php");
        }
    } catch (PDOException $e) {
        echo '' . $e->getMessage() . '';
    }
}
if (isset($_POST['CloseSubmit'])) {
    $id = $_POST['id'];
    $problem = $_POST['problem'];
    $description = $_POST['description'];
    $withdraw = $_POST['withdraw'];
    $close_date = $_POST['close_date'];
    $status = 4;
    try {
        $sql = "UPDATE data_report SET problem = :problem, description = :description , withdraw = :withdraw, close_date = :close_date , status = :status WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":problem", $problem);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":withdraw", $withdraw);
        $stmt->bindParam(":close_date", $close_date);
        $stmt->bindParam(":id", $id);

        if ($stmt->execute()) {
            $_SESSION["success"] = "เสร็จงานเรียบร้อยแล้ว";
            header("location: ../myjob.php");
        } else {
            $_SESSION["error"] = "พบข้อผิดพลาด";
            header("location: ../myjob.php");
        }
    } catch (PDOException $e) {
        echo '' . $e->getMessage() . '';
    }
}

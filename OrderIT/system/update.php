<?php
session_start();
require_once '../config/db.php';

if (isset($_POST['withdraw'])) {
    $withdraw_name = $_POST['withdraw_name'];
    $withdraw_id = $_POST['withdraw_id'];
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
            $sql = "UPDATE withdraw SET withdraw_name = :withdraw_name WHERE withdraw_id = :withdraw_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":withdraw_id", $withdraw_id);
            $stmt->bindParam(":withdraw_name", $withdraw_name);

            if ($stmt->execute()) {
                $_SESSION["success"] = "อัพเดทข้อมูลเรียบร้อยแล้ว";
                header("location: ../insertData.php");
            }
        }
    } catch (PDOException $e) {
        echo '' . $e->getMessage() . '';
    }
}
if (isset($_POST['work'])) {
    $work_name = $_POST['work_name'];
    $work_id = $_POST['work_id'];
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
            $sql = "UPDATE listwork SET work_name = :work_name WHERE work_id = :work_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":work_id", $work_id);
            $stmt->bindParam(":work_name", $work_name);

            if ($stmt->execute()) {
                $_SESSION["success"] = "อัพเดทข้อมูลเรียบร้อยแล้ว";
                header("location: ../insertData.php");
            }
        }
    } catch (PDOException $e) {
        echo '' . $e->getMessage() . '';
    }
}
if (isset($_POST['device'])) {
    $device_name = $_POST['device_name'];
    $device_id = $_POST['device_id'];
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
            $sql = "UPDATE device SET device_name = :device_name WHERE device_id = :device_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":device_id", $device_id);
            $stmt->bindParam(":device_name", $device_name);

            if ($stmt->execute()) {
                $_SESSION["success"] = "อัพเดทข้อมูลเรียบร้อยแล้ว";
                header("location: ../insertData.php");
            }
        }
    } catch (PDOException $e) {
        echo '' . $e->getMessage() . '';
    }
}
if (isset($_POST['models'])) {
    $models_name = $_POST['models_name'];
    $models_id = $_POST['models_id'];
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
            $sql = "UPDATE device_models SET models_name = :models_name WHERE models_id = :models_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":models_id", $models_id);
            $stmt->bindParam(":models_name", $models_name);

            if ($stmt->execute()) {
                $_SESSION["success"] = "อัพเดทข้อมูลเรียบร้อยแล้ว";
                header("location: ../insertData.php");
            }
        }
    } catch (PDOException $e) {
        echo '' . $e->getMessage() . '';
    }
}
if (isset($_POST['depart'])) {
    $depart_name = $_POST['depart_name'];
    $depart_id = $_POST['depart_id'];
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
            $sql = "UPDATE depart SET depart_name = :depart_name WHERE depart_id = :depart_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":depart_id", $depart_id);
            $stmt->bindParam(":depart_name", $depart_name);

            if ($stmt->execute()) {
                $_SESSION["success"] = "อัพเดทข้อมูลเรียบร้อยแล้ว";
                header("location: ../insertData.php");
            }
        }
    } catch (PDOException $e) {
        echo '' . $e->getMessage() . '';
    }
}
if (isset($_POST['offer'])) {
    $offer_name = $_POST['offer_name'];
    $offer_id = $_POST['offer_id'];
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
            $sql = "UPDATE offer SET offer_name = :offer_name WHERE offer_id = :offer_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":offer_id", $offer_id);
            $stmt->bindParam(":offer_name", $offer_name);

            if ($stmt->execute()) {
                $_SESSION["success"] = "อัพเดทข้อมูลเรียบร้อยแล้ว";
                header("location: ../insertData.php");
            }
        }
    } catch (PDOException $e) {
        echo '' . $e->getMessage() . '';
    }
}
if (isset($_POST['updateData'])) {
    $id = $_POST['numberWork'];
    $status = $_POST['status'];
    $receiptDate = $_POST["receipt_date"];
    $deliveryDate = $_POST["delivery_date"];
    $closeDate = $_POST["close_date"];
    // สร้าง SQL UPDATE statement
    $sql = "UPDATE orderdata SET
            list1 = :list1, quality1 = :quality1, amount1 = :amount1, price1 = :price1, unit1 = :unit1,
            list2 = :list2, quality2 = :quality2, amount2 = :amount2, price2 = :price2, unit2 = :unit2,
            list3 = :list3, quality3 = :quality3, amount3 = :amount3, price3 = :price3, unit3 = :unit3,
            list4 = :list4, quality4 = :quality4, amount4 = :amount4, price4 = :price4, unit4 = :unit4,
            list5 = :list5, quality5 = :quality5, amount5 = :amount5, price5 = :price5, unit5 = :unit5,
            list6 = :list6, quality6 = :quality6, amount6 = :amount6, price6 = :price6, unit6 = :unit6,
            list7 = :list7, quality7 = :quality7, amount7 = :amount7, price7 = :price7, unit7 = :unit7,
            list8 = :list8, quality8 = :quality8, amount8 = :amount8, price8 = :price8, unit8 = :unit8,
            list9 = :list9, quality9 = :quality9, amount9 = :amount9, price9 = :price9, unit9 = :unit9,
            list10 = :list10, quality10 = :quality10, amount10 = :amount10, price10 = :price10, unit10 = :unit10,
            list11 = :list11, quality11 = :quality11, amount11 = :amount11, price11 = :price11, unit11 = :unit11,
            list12 = :list12, quality12 = :quality12, amount12 = :amount12, price12 = :price12, unit12 = :unit12,
            list13 = :list13, quality13 = :quality13, amount13 = :amount13, price13 = :price13, unit13 = :unit13,
            list14 = :list14, quality14 = :quality14, amount14 = :amount14, price14 = :price14, unit14 = :unit14,
            list15 = :list15, quality15 = :quality15, amount15 = :amount15, price15 = :price15, unit15 = :unit15,
            receiptDate = :receiptDate,deliveryDate = :deliveryDate,closeDate = :closeDate,
            status = :status
            WHERE id = :id";

    // เตรียมและ execute statement
    $stmt = $conn->prepare($sql);

    // Manually bind parameters (without using a loop)
    for ($i = 1; $i <= 15; $i++) {
        $stmt->bindParam(":list$i", $_POST["list$i"]);
        $stmt->bindParam(":quality$i", $_POST["quality$i"]);
        $stmt->bindParam(":amount$i", $_POST["amount$i"]);
        $stmt->bindParam(":price$i", $_POST["price$i"]);
        $stmt->bindParam(":unit$i", $_POST["unit$i"]);
    }

    // bind id parameter
    $stmt->bindParam(":status", $status);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(':receiptDate', $receiptDate);
    $stmt->bindParam(':deliveryDate', $deliveryDate);
    $stmt->bindParam(':closeDate', $closeDate);
    // execute statement
    $stmt->execute();

    $_SESSION["success"] = "อัพเดทข้อมูลเรียบร้อยแล้ว";
    header("location: ../check.php");
}
if (isset($_POST['updateStatus'])) {
    $selectedRows = array();

    // Collect all selected rows in an array
    for ($i = 1; $i <= 15; $i++) {
        if (isset($_POST["selectedRow$i"])) {
            $selectedRows[] = intval($_POST["selectedRow$i"]);
            $receiptDate = $_POST["receipt_date"];
            $deliveryDate = $_POST["delivery_date"];
            $closeDate = $_POST["close_date"];
        }
    }

    // Check if any checkboxes are checked
    if (empty($selectedRows)) {
        $_SESSION["error"] = "ไม่พบข้อมูลที่เลือก";
        header("location: ../checkAll.php");
        exit(); // Added exit to stop further execution
    }
    // Update the database with STATUS = 3 for each selectedRow
    $sql = "UPDATE orderdata SET receiptDate = :receiptDate,deliveryDate = :deliveryDate,closeDate = :closeDate, status = 3 WHERE id = :id";
    $stmt = $conn->prepare($sql);

    foreach ($selectedRows as $selectedRow) {
        // Bind parameter
        $stmt->bindParam(':id', $selectedRow, PDO::PARAM_INT);
        $stmt->bindParam(':receiptDate', $receiptDate);
        $stmt->bindParam(':deliveryDate', $deliveryDate);
        $stmt->bindParam(':closeDate', $closeDate);
        // Execute the SQL statement for each selectedRow
        if ($stmt->execute()) {
            $_SESSION["success"] = "อัพเดทข้อมูลเรียบร้อยแล้ว";
        } else {
            $_SESSION["error"] = "พบข้อผิดพลาด";
        }
    }

    // Redirect after all updates are done
    header("location: ../checkAll.php");
    exit(); // Added exit to stop further execution
}

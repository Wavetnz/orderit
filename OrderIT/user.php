<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบรับแจ้งซ่อมคอมพิวเตอร์</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            background-color: #FDFEFE;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 5px 5px 10px #888888;
            max-width: 800px;
            margin: 0 auto;
        }

        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('ms.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            filter: blur(8px);
            z-index: -1;
        }

        .container {
            text-align: left;
            color: black;
            z-index: 1;
        }

        .content {
            background-color: rgba(255, 255, 255, 0.7);
            padding: 20px;
            border-radius: 10px;
        }
        @media only screen and (max-width: 768px) {
            /* ปรับแต่งสไตล์สำหรับแพลตฟอร์มมือถือ */
            .container {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container mt-3">
        <center><img src="logo.png" style="width:10%"></center>
        <h1 class="text-center mb-4">ศูนย์รับซ่อมคอมพิวเตอร์</h1>
        <form method="post" action="insert.php">
            <div class="row">
                <div class="col-sm-3 col-lg-3 col-md-6">
                    <label for="device">อุปกรณ์:</label>
                    <input type="text" id="device" name="device" class="form-control" required>
                </div>
                <div class="col-sm-3 col-lg-3 col-md-6">

                    <label for="number_device">หมายเลขครุภัณฑ์ (ถ้ามี):</label>
                    <input type="text" id="number_device" name="number_device" class="form-control">
                </div>
                <div class="col-sm-3 col-lg-3 col-md-6">

                    <label for="ip_address">หมายเลข IP Address:</label>
                    <input type="text" id="ip_address" name="ip_address" class="form-control" required>
                </div>
                <div class="col-sm-3 col-lg-3 col-md-6">

                    <label for="report">อาการเสีย:</label>
                    <input type="text" id="report" name="report" class="form-control" required>
                </div>
                <div class="col-sm-3 col-lg-3 col-md-6">
                    <label for="reporter">ผู้แจ้ง:</label>
                    <input type="text" id="reporter" name="reporter" class="form-control" required>
                </div>
                <div class="col-sm-3 col-lg-3 col-md-6">

                    <label for="department">หน่วยงาน:</label>
                    <input type="text" id="department" name="department" class="form-control" required>
                </div>
                <div class="col-sm-3 col-lg-3 col-md-6">

                    <label for="tel">เบอร์ติดต่อกลับ:</label>
                    <input type="text" id="tel" name="tel" class="form-control" required>
                </div>
                <div class="col-sm-3 col-lg-3 col-md-6">

                <button type="submit" class="btn btn-primary mt-3">ส่งแจ้ง</button>
                </div> 
            </div>
    </div>
    </div>
    </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
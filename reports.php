<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/favicon.jpg" type="image/x-icon">
    <title>Download Excel Sheet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        /* .container {
            max-width: 600px;
            margin: 100px auto;
            text-align: center;
        } */

        h1 {
            color: #333;
        }

        .buttons {
            margin-top: 30px;
        }

        button {
            padding: 10px 20px;
            margin: 0 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Download Excel Sheet</h1>
        <div class="buttons">
            <button onclick="downloadExcel('products')">Products</button>
            <button onclick="downloadExcel('orders')">Orders</button>
            <button onclick="downloadExcel('users')">Users</button>
        </div>
    </div>

    <script src="PHPExcel.php"></script>
    <script>
        function downloadExcel(table) {
            // Redirect to download.php with the table name as a query parameter
            window.location.href = `download.php?table=${table}`;
        }
    </script>

    <?php
    if (isset($_GET['table'])) {
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "project1";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get the table name from the query parameter
        $table = $_GET['table'];

        // Fetch data from the specified table
        $sql = "SELECT * FROM $table";
        $result = $conn->query($sql);

        // Create a new PHPExcel object
        require_once 'PHPExcel.php';
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Your Name")
                                     ->setLastModifiedBy("Your Name")
                                     ->setTitle("$table Data")
                                     ->setSubject("$table Data")
                                     ->setDescription("Data from the $table table")
                                     ->setKeywords("$table")
                                     ->setCategory("Data");

        // Add data to the Excel sheet
        $row = 1;
        while($row_data = $result->fetch_assoc()) {
            $col = 0;
            foreach($row_data as $key => $value) {
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValueByColumnAndRow($col, $row, $value);
                $col++;
            }
            $row++;
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle("$table Data");

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$table.'_data.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
    ?>
</body>
</html>

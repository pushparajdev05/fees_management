<?php
include "./backend/db/defaulters/db_connection.php";
$class_array = ["LKG", "UKG", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X","XIAC","XIDE", "XIIAC","XIIDE"];
$class_out = [];
foreach($class_array as $value)
{
    $sql = "select * from overall where class = '{$value}'";
    $res = $con->query($sql);
    $class_out["$value"] = $res->fetch_all();
}
$json_array_out = json_encode($class_out);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/navbars.css">
    <link rel="stylesheet" href="./css/defaulter/underline.css">
    <link rel="stylesheet" href="./css/defaulter/defaulters.css">
    <link rel="stylesheet" href="./css/defaulter/form_overall.css">
    <link rel="stylesheet" href="./css/defaulter/animation.css">
    <link rel="stylesheet" href="./asset/sweetalert/sweetalert2.min.css">
    <!-- <link rel="stylesheet" href="./css/transform.css"> -->
         <link rel="stylesheet" href="./datatable/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="./datatable/css/buttons.dataTables.css" />
    <title>Document</title>
</head>
<body>
        <?php
        include "./component/header.html";
    ?>
     <section id="overall_table_form">
        <?php
        include "./component/form_overall.php";
        ?>
    </section>
    <section id="datatable">
        <div id="heading">
            <h1 class="h1">Overall details of students</h1>
        </div>
        <div id="options">
            <div class="option">
                <div class="file_selection">
                    <label for="csv_file" class="label">Load Overall Sheet :</label><br>
                    <input type="file" name="csv_file" id="overall_csv" class="csv_file">
                </div>
                <div class="mode_selection">
                    <label for="csv_file" class="label">Mode :</label>
                    <select name="mode" id="mode">
                        <option value="replace">Replace</option>
                        <option value="append">Append</option>
                    </select>
                    </div>
                    <div class="file_save">
                        <button type="button" class="download-btn pixel-corners" id="save_csv">
                            <div class="button-content">
                                <div class="svg-container">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgb(255, 255, 255);"><path d="M5 21h14a2 2 0 0 0 2-2V8a1 1 0 0 0-.29-.71l-4-4A1 1 0 0 0 16 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2zm10-2H9v-5h6zM13 7h-2V5h2zM5 5h2v4h8V5h.59L19 8.41V19h-2v-5a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v5H5z"></path></svg>
                                </div>
                                <div class="text-container">
                                <div class="text">Save</div>
                                </div> 
                            </div>
                        </button>
                    </div>
                </div>
                <div class="option">
                    <div id="calc_option" class="option_btn">
                        <select name="calc_select" id="calc_select">
                            <option value="Class">Class</option>
                            <option value="Term I">Term I</option>
                            <option value="Term II">Term II</option>
                            <option value="Term III">Term III</option>
                            <option value="Class & Sec">Class & Sec</option>
                        </select>
                    </div>
                    <div id="calc_btn">
                        <div id="calc" class="option_btn">
                            <button type="submit" id="calc_" class="download-btn pixel-corners">
                                <div class="button-content">
                                    <div class="svg-container" style="color:white;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calculator"><rect width="16" height="20" x="4" y="2" rx="2"/><line x1="8" x2="16" y1="6" y2="6"/><line x1="16" x2="16" y1="14" y2="18"/><path d="M16 10h.01"/><path d="M12 10h.01"/><path d="M8 10h.01"/><path d="M12 14h.01"/><path d="M8 14h.01"/><path d="M12 18h.01"/><path d="M8 18h.01"/></svg>
                                    </div>
                                    <div class="text-container">
                                    <div class="text">calc</div>
                                    </div> 
                                </div>
                            </button>
                        </div>
                    </div>
                    <div id="new_btn">
                        <div id="n1" class="option_btn">
                            <?php
                            include "./component/add.html";
                            ?>
                        </div>
                    </div>
                    <div id="select" key="0">
                        <label for="class"  class="label">View in standard : </label>
                        <div id="select1" class="select">
                            <select name="class" id="class_types" required>   
                                <option value="LKG">LKG</option>        
                                <option value="UKG">UKG</option>
                                <option value="I">I</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                                <option value="V">V</option>
                                <option value="VI">VI</option>
                                <option value="VII">VII</option>
                                <option value="VIII">VIII</option>
                                <option value="IX">IX</option>
                                <option value="X">X</option>
                                <option value="XIAC">XIAC</option>
                                <option value="XIDE">XIDE</option>
                                <option value="XIIAC">XIIAC</option>
                                <option value="XIIDE">XIIDE</option>
                            </select>
                        </div>
                    </div>
                </div>
        </div>
        <div id="table-content">
            <div id="cheque_table" class="table-cash term">
                <h1 class="h1">Total Fees Collection</h1>
                <table id="myTable1" class="display">
    <thead>
        <tr>
            <th>sno</th>
            <th style="white-space: nowrap;">Admission No</th>
            <th>Name</th>
            <th>Class</th>
            <th>Section</th>
            <th style="white-space: nowrap;">Term I</th>
            <th style="white-space: nowrap;">Term II</th>
            <th style="white-space: nowrap;">Term III</th>
            <th style="white-space: nowrap;">Paid Date</th>
            <th>ScholarShip</th>
            <th>ScholarShip Amount</th>
            <th style="white-space: nowrap;">Pending</th>
            <th style="white-space: nowrap;">Write Off</th>
            <th>Total Receivable</th>
            <th>Total Received</th>
            <th>Balance receivable</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="tbody1">
    </tbody>
</table> 
        </div>
    </section>
    <div id="defaulter_table">
        <div id="defaults_close" class="close_form">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </div>
        <div class="defaults_con">
            <h1 id="default_heading" style="text-align:center;">Total Fees Collection and Defaulters</h1>
            <table id="defaulters_list">
                <thead id="defaulter_head">
                </thead>
                <tbody id="defaulter_body">
                </tbody>
            </table>
        </div>
            
    </div>
    <script src="./asset/sweetalert/sweetalert2.all.min.js"></script>
    <script src="./javascript/jquery-3.7.1.js"></script>
    <script type="module" src="./javascript/overall.js"></script>
    <script src="./javascript/overall_basic.js"></script>
    <script src="./datatable/javascript/datatable.js"></script>
    <script src="./datatable/javascript/buttons.dataTables.js"></script>
    <script src="./datatable/javascript/dataTables.buttons.js"></script>
    <script src="./datatable/javascript/jszip.min.js"></script>
    <script src="./datatable/javascript/pdfmake.min.js"></script>
    <script src="./datatable/javascript/vfs_fonts.js"></script>
    <script src="./datatable/javascript/buttons.html5.min.js"></script>  
    <script>
        window.resultSet=<?php echo $json_array_out?>;
    </script>
</body>
</html>
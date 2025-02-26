<?php
session_start();
if(!isset($_SESSION["user"]))
{
    header("location: index.php");
}
include "./backend/db/defaulters/db_connection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/navbars.css">
    <link rel="stylesheet" href="./css/transfer/underline.css">
    <link rel="stylesheet" href="./css/transfer/transfer.css">
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
        include "./component/header.php";
    ?>
     <section id="overall_table_form">
        <?php
        include "./component/form_transfer.php";
        ?>
    </section>
    <section id="datatable">
        <div id="heading">
            <h1 class="h1">Passed out student Lists</h1>
        </div>
        <div id="options">
            <div class="option">
                <div class="transfer_option">
                   <input type="number" name="pass_year" id="pass_year" placeholder="Year">
                   <button type="button" class="download-btn pixel-corners" id="transaction_btn">
                            <div class="button-content">
                                <div class="svg-container">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgb(255, 255, 255);"><path d="M5 21h14a2 2 0 0 0 2-2V8a1 1 0 0 0-.29-.71l-4-4A1 1 0 0 0 16 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2zm10-2H9v-5h6zM13 7h-2V5h2zM5 5h2v4h8V5h.59L19 8.41V19h-2v-5a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v5H5z"></path></svg>
                                </div>
                                <div class="text-container">
                                <div class="text">Transaction</div>
                                </div> 
                            </div>
                        </button>
                   <button type="button" class="download-btn pixel-corners" id="transfer_btn">
                            <div class="button-content">
                                <div class="svg-container">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgb(255, 255, 255);"><path d="M5 21h14a2 2 0 0 0 2-2V8a1 1 0 0 0-.29-.71l-4-4A1 1 0 0 0 16 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2zm10-2H9v-5h6zM13 7h-2V5h2zM5 5h2v4h8V5h.59L19 8.41V19h-2v-5a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v5H5z"></path></svg>
                                </div>
                                <div class="text-container">
                                <div class="text">Transfer</div>
                                </div> 
                            </div>
                        </button>
                        <a href="./backend/db/transfer/overall_data.xlsx" download id="excel" style="display:none"></a>
                        <div id="new_btn">
                        <div id="n1" class="option_btn">
                            <?php
                            include "./component/add.html";
                            ?>
                        </div>
                    </div>
                    <button type="button" class="download-btn pixel-corners" id="user_show">
                            <div class="button-content">
                                <div class="svg-container">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgb(255, 255, 255);"><path d="M5 21h14a2 2 0 0 0 2-2V8a1 1 0 0 0-.29-.71l-4-4A1 1 0 0 0 16 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2zm10-2H9v-5h6zM13 7h-2V5h2zM5 5h2v4h8V5h.59L19 8.41V19h-2v-5a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v5H5z"></path></svg>
                                </div>
                                <div class="text-container">
                                <div class="text">Users</div>
                                </div> 
                            </div>
                        </button>
                </div>
                </div>
        </div>
        <div id="table-content">
            <div id="cheque_table" class="table-cash term">
                <h1 class="h1"></h1>
                <table id="myTable1" class="display">
    <thead>
        <tr>
            <th>sno</th>
            <th style="white-space: nowrap;">Admission No</th>
            <th>Name</th>
            <th>Class</th>
            <th>Section</th>
            <th style="white-space: nowrap;">PassedOut</th>
            <th>Pending</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="tbody1">
        <?php
                $i = 0;
                $sql="select * from passedout";
                $res=$con->query($sql);
                if($res->num_rows>0)
                {
                    while($row=$res->fetch_assoc())
                    {	
                        echo "<tr>
                            <td>".++$i."</td>
                            <td>{$row["admission"]}</td>
                            <td style='white-space:nowrap;'>{$row["name"]}</td>
                            <td style='width:130px;white-space:nowrap;'>{$row["class"]}</td>
                            <td>{$row["section"]}</td>
                            <td>{$row["passed_year"]}</td>
                            <td>{$row["pending"]}</td>
                            <td>
                            <div id='action'>
                                <button type='button' style='height:35px;width:70px;' class='download-btn pixel-corners delete' ad={$row['sno']}>
                            <div class='button-content' style=''>
                                <div class='svg-container'>
                                <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 700 700' height='24' widht='24' style='fill:white;margin-bottom:3px;margin-left:8px;'><path d='M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z'/></svg>
                                </div>
                                <div class='text-container' style='height:37px'>
                                <div class='text'>Delete</div>
                                </div> 
                            </div>
                            </button>
                            <button typr='submit' style='height:35px;width:70px;' class='download-btn pixel-corners update' ad={$row['sno']}>
                            <div class='button-content' style=''>
                                <div class='svg-container'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' style='fill: rgb(255, 255, 255);margin-bottom: 10px;'><path d='M11 15h2V9h3l-4-5-4 5h3z'></path><path d='M20 18H4v-7H2v7c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2v-7h-2v7z'></path></svg>
                                </div>
                                <div class='text-container' style='height:37px'>
                                <div class='text'>Update</div>
                                </div> 
                            </div>
                            </button>
                            </div></td></tr>";
                    }
                }
            ?>
    </tbody>
</table> 
        </div>
    </section>
    <div id="user_table" class="popUp">
        <div id="user_close" class="close_form">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </div>
        <div class="defaults_con">
            <h1 class="default_heading" style="text-align:center;">Administrators and Staffs</h1>
            <table id="user_list">
                <thead id="user_head">
                    <tr>
                        <th>Sno</th>
                        <th>User Id</th>
                        <th>role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="user_body">
                <?php
                $j = 0;
                $sql="select * from users";
                $res=$con->query($sql);
                if ($res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        echo "<tr>
                            <td style='text-align:center'>" . ++$j . "</td>
                            <td>{$row["uname"]}</td>
                            <td>{$row["role"]}</td>
                            <td>
                            <div id='action'>
                                <button type='button' style='height:35px;width:70px;' class='download-btn pixel-corners user_del' >
                            <div class='button-content' style=''>
                                <div class='svg-container'>
                                <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 700 700' height='24' widht='24' style='fill:white;margin-bottom:3px;margin-left:8px;'><path d='M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z'/></svg>
                                </div>
                                <div class='text-container' style='height:37px'>
                                <div class='text'>Delete</div>
                                </div> 
                            </div>
                            </button>
                            </div></td></tr>";
                    }
                }
            ?>
                </tbody>
            </table>
        </div>
    <div id="defaulter_table" class="popUp">
        <div id="defaults_close" class="close_form">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </div>
        <div class="defaults_con">
            <h1 id="default_heading" style="text-align:center;">Transaction of the given year</h1>
            <table id="defaulters_list">
                <thead id="defaulter_head">
                </thead>
                <tbody id="defaulter_body">
                
                </tbody>
            </table>
        </div>
    <script>
            window.user="<?= $_SESSION["user"]?>";
    </script>
    <script src="./asset/sweetalert/sweetalert2.all.min.js"></script>
    <script src="./javascript/basic_transfer.js"></script>
    <script src="./javascript/jquery-3.7.1.js"></script>
    <script src="./datatable/javascript/datatable.js"></script>
    <script src="./datatable/javascript/buttons.dataTables.js"></script>
    <script src="./datatable/javascript/dataTables.buttons.js"></script>
    <script src="./datatable/javascript/jszip.min.js"></script>
    <script src="./datatable/javascript/pdfmake.min.js"></script>
    <script src="./datatable/javascript/vfs_fonts.js"></script>
    <script src="./datatable/javascript/buttons.html5.min.js"></script>  
    <script type="module" src="./javascript/transfer_data.js"></script>
    <script src ="./javascript/logout.js"></script>
</body>
</html>
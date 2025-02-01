<?php
include "./backend/db/collection/db_connection.php";
$class_array = ["LKG", "UKG", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X","XIAC","XIDE", "XIIAC","XIIDE"];
$class_out = [];
$term = [];
foreach($class_array as $value)
{
    $sql = "select types,$value from fees_table";
    $res = $con->query($sql);
    // echo json_decode($res);
    $class_out["$value"] = $res->fetch_all(MYSQLI_ASSOC);
}
$term_sql = "select types from fees_table where types like 'term%'";
$overall_admission = "select admission from overall order by admission";
$overall_sql = "select admission,name,class,section from overall";
$res_admission = $con->query($overall_admission);
$res_overall_sql = $con->query($overall_sql);
if($res_overall_sql->num_rows > 0)
{
    $admission_result = json_encode($res_admission->fetch_all());
    $overall_result = json_encode($res_overall_sql->fetch_all(MYSQLI_ASSOC));
}
$res_term = $con->query($term_sql);
if($res_term->num_rows > 0)
{
    $in = 0;
    while($row=$res_term->fetch_assoc())
    {
        if($in < 3)
        {
            $term[] = $row["types"];
        }
        else{
            break;
        }
        $in++;
    }
}
$json_array_out = json_encode($class_out);
$json_term = json_encode($term);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/navbars.css">
    <link rel="stylesheet" href="./css/collection/underline.css">
    <link rel="stylesheet" href="./css/collection/table_print.css">
    <link rel="stylesheet" href="./css/collection/collection.css">
    <link rel="stylesheet" href="./css/collection/form_collection.css">
    <link rel="stylesheet" href="./asset/sweetalert/sweetalert2.min.css">
    <link rel="stylesheet" href="./css/collection/animation.css">
    <link rel="stylesheet" href="./datatable/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="./datatable/css/buttons.dataTables.css" />
    <style>
        tr td .button-content
        {
            transform: translateY(-50px);
        }
        table.dataTable td.dt-type-numeric,table.dataTable th.dt-type-numeric{
            text-align: left;
        }
        .dt-layout-table
        {
            overflow-x: auto;
        }
    </style>
    
    <title>Document</title>
</head>
<body>
       <?php
        include "./component/header.html";
    ?> 
    <section id="collection-form">
        <?php
        include "./component/forms_collection.php";
        ?>
    </section>
    <div id="fees_structure" class="fees_structure">
        <div id="fees_define">
            <div id="table_close">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M11.9997 10.5865L16.9495 5.63672L18.3637 7.05093L13.4139 12.0007L18.3637 16.9504L16.9495 18.3646L11.9997 13.4149L7.04996 18.3646L5.63574 16.9504L10.5855 12.0007L5.63574 7.05093L7.04996 5.63672L11.9997 10.5865Z"></path></svg>
            </div>
            <table id="fees_table">
                <thead>
                    <tr>
                        <th>Types</th>
                        <th>LKG</th>
                        <th>UKG</th>
                        <th>I</th>
                        <th>II</th>
                        <th>III</th>
                        <th>IV</th>
                        <th>V</th>
                        <th>VI</th>
                        <th>VII</th>
                        <th>VIII</th>
                        <th>IX</th>
                        <th>X</th>
                        <th>XIAC</th>
                        <th>XIDE</th>
                        <th>XIIAC</th>
                        <th>XIIDE</th>
                    </tr>
                </thead>     
                <tbody>
                <?php
                $fees_sql = "select * from fees_table";
                $res = $con->query($fees_sql);
                $fees_name = [];
                if ($res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        $fees_name[]=$row['types'];
                        echo " <tr>
                        <td>{$row['types']}</td>
                        <td>{$row['LKG']}</td>
                        <td>{$row['UKG']}</td>
                        <td>{$row['I']}</td>
                        <td>{$row['II']}</td>
                        <td>{$row['III']}</td>
                        <td>{$row['IV']}</td>
                        <td>{$row['V']}</td>
                        <td>{$row['VI']}</td>
                        <td>{$row['VII']}</td>
                        <td>{$row['VIII']}</td>
                        <td>{$row['IX']}</td>
                        <td>{$row['X']}</td>
                        <td>{$row['XIAC']}</td>
                        <td>{$row['XIDE']}</td>
                        <td>{$row['XIIAC']}</td>
                        <td>{$row['XIIDE']}</td>
                    </tr>";
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <section id="datatable">
        <div id="head_part">
            <!-- <div id="heading">
                <h1 class="h1">DAILY CASH COLLECTION</h1>
            </div> -->
            <div id="options">
                <div class="option opt1">
                    <div class="opt left">
                        <label for="filter_date">
                            Date :
                        </label>
                        <input type="date" name="filter_date" id="filter_date">
                        <div class="print"> 
                            <?php
                                include "./component/print_btn.html";
                            ?>  
                        </div>
                        <div class="filter_date"> 
                            <?php
                                include "./component/filter.html";
                            ?>
                        </div>
                    </div>
                    <div class="opt right">
                        <div id="switch-new">
                            <div id="switch">
                                <div id="s1" class="">
                                    <?php
                                    include "./component/switch_btn.html";
                                    ?>
                                </div>
                            </div>
                            <div id="new">
                                <div id="n1">
                                    <?php
                                    include "./component/new.html";
                                    ?>
                                </div>
                            </div>
                        </div>
                    <div id="select" key="0">
                        <div id="select2" class="select">
                            <select name="" id="filter2" required>
                                <option value="">--select--</option>
                                <?php
                                foreach ($fees_name as $types) {
                                        echo " <option value='$types'>$types</option> ";
                                }
                                ?>
                            </select>
                            <?php
                            include "./component/filter.html";
                            ?>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="option opt2">
                    <div class="opt left">
                        <label for="tooltip_option">Types of fees :</label>
                        <div class="tooltip_option" id="tooltip_option">
                            <?php
                            include "./component/tooltip_option.html";
                            ?>
                        </div>
                        <div id="csv_submit">
                            <?php
                            include "./component/save_btn.html";
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="table-content">
            <div id="cash_table" class="table-cash term">
                <h1 class="h1">Daily Cash</h1>
                <table id="myTable1" class="display">
    <thead>
        <tr>
            <th>S.No</th>
            <th style="white-space: nowrap;">Receipt No</th>
            <th style="white-space: nowrap;">Admission No</th>
            <th>name</th>
            <th>Class</th>
            <th>Section</th>
            <th>Mode</th>
            <th>Amounts</th>
            <th>total</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="tbody1">
               <?php
            $i = 0;
            $sql="select * from cash";
            $res=$con->query($sql);
            if($res->num_rows>0)
            {
                while($row=$res->fetch_assoc())
                {	
                    echo"<tr>
                        <td>".++$i."</td>
                        <td>{$row["sno"]}</td>
                        <td>{$row["admission"]}</td>
                        <td style='white-space:nowrap;'>{$row["name"]}</td>
                        <td style='width:130px;white-space:nowrap;'>{$row["class"]}</td>
                        <td>{$row["section"]}</td>
                        <td>{$row["type"]}</td>
                        <td>{$row["amount"]}</td>
                        <td>{$row["total"]}</td>
                        <td>{$row["date"]}</td>
                        <td>
                        <div id='action'>
                            <button type='button' style='height:35px;width:70px;' class='download-btn pixel-corners delete' ad={$row['sno']}>
                        <div class='button-content' style=''>
                            <div class='svg-container'>
                            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 700 700' height='24' widht='24' style='fill:white;margin-bottom:3px;margin-left:8px;'><path d='M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z'/></svg>
                            </div>
                            <div class='text-container'>
                            <div class='text'>Delete</div>
                            </div> 
                        </div>
                        </button>
                        <button typr='submit' style='height:35px;width:70px;' class='download-btn pixel-corners update' ad={$row['sno']}>
                        <div class='button-content' style=''>
                            <div class='svg-container'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' style='fill: rgb(255, 255, 255);margin-bottom: 10px;'><path d='M11 15h2V9h3l-4-5-4 5h3z'></path><path d='M20 18H4v-7H2v7c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2v-7h-2v7z'></path></svg>
                            </div>
                            <div class='text-container'>
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
        <div id="cheque_table" class="table-cash">
            <h1 class="h1">Daily Cheque/online</h1>
            <table id="myTable2" class="display">
    <thead>
        <tr>
            <th>S.No</th>
            <th style="white-space: nowrap;">Admission No</th>
            <th style="white-space: nowrap;">Receipt No</th>
            <th>name</th>
            <th>Class</th>
            <th>Section</th>
            <th>Mode</th>
            <th>Amounts</th>
            <th>total</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="tbody2">
         <?php
            $j = 0;
            $sql="select * from cheque_online";
            $res=$con->query($sql);
            if($res->num_rows>0)
            {
                while($row=$res->fetch_assoc())
                {	
                    echo"<tr>
                        <td>".++$j."</td>
                        <td>{$row["sno"]}</td>
                        <td>{$row["admission"]}</td>
                        <td style='white-space:nowrap;'>{$row["name"]}</td>
                        <td style='white-space:nowrap;'>{$row["class"]}</td>
                        <td>{$row["section"]}</td>
                        <td>{$row["type"]}</td>
                        <td>{$row["amount"]}</td>
                        <td>{$row["total"]}</td>
                        <td>{$row["date"]}</td>
                        <td>
                        <div id='action'>
                            <button type='button' style='height:35px;width:70px;' class='download-btn pixel-corners delete' ad={$row['sno']}>
                        <div class='button-content' style=''>
                            <div class='svg-container'>
                            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 700 700' height='24' widht='24' style='fill:white;margin-bottom:3px;margin-left:8px;'><path d='M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z'/></svg>
                            </div>
                            <div class='text-container'>
                            <div class='text'>Delete</div>
                            </div> 
                        </div>
                        </button>
                        <button typr='submit' style='height:35px;width:70px;' class='download-btn pixel-corners update' ad={$row['sno']}>
                        <div class='button-content' style=''>
                            <div class='svg-container'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' style='fill: rgb(255, 255, 255);margin-bottom: 10px;'><path d='M11 15h2V9h3l-4-5-4 5h3z'></path><path d='M20 18H4v-7H2v7c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2v-7h-2v7z'></path></svg>
                            </div>
                            <div class='text-container'>
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
        </div>
    </section>
        <div id="table_print">

        </div>
        <div class="invoice_print">
            <?php
            include "./component/invoice.html";
            ?>
        </div>
    <script src="./javascript/jquery-3.7.1.js"></script>
    <script src="./javascript/form_collection.js"></script>
    <script src="./asset/sweetalert/sweetalert2.all.min.js"></script>
    <script src="./datatable/javascript/datatable.js"></script>
    <script src="./datatable/javascript/buttons.dataTables.js"></script>
    <script src="./datatable/javascript/dataTables.buttons.js"></script>
    <script src="./datatable/javascript/jszip.min.js"></script>
    <script src="./datatable/javascript/pdfmake.min.js"></script>
    <script src="./datatable/javascript/vfs_fonts.js"></script>
    <script src="./datatable/javascript/buttons.html5.min.js"></script>
    <script type="module" src="./backend/db/collection/collection_data.js"></script>
    <script type="module" src="./javascript/btn_event.js"></script>
    <script type="module" src="./javascript/collection_data.js"></script>
    <script src="./javascript/printout.js"></script>
    <script type="module" src="./javascript/print_daily_invoice.js"></script>
    <script>
        const resultSet=<?php echo $json_array_out?>;
        window.term_array=<?php echo $json_term?>;
        console.log(window.term_array);
        const sort_in_class= document.getElementById("class");
        const fees= document.getElementById("type");
        const total_amt= document.getElementById("amt");
        sort_in_class.addEventListener("change",function()
        {
            console.log("change event triggered");
            let selected_option=sort_in_class.value;
            const sorted_data=resultSet[selected_option];
            const selected_fees=[];
            for(let i=0; i < fees.options.length; i++)
            {
                if(fees.options[i].selected)
                {
                    selected_fees.push(fees.options[i].value);
                }
            }
            let options="";
            sorted_data.forEach(arr => {
            options+=`
            <option value='${arr['types']}' amount='${arr[selected_option]}'>${arr['types']}</option>
                    `;
                });
            if(options=="")
            {
                fees.innerHTML="<option value=' style='border: none;'>select the class to get fees type</option>";
            }
            else
            {
                fees.innerHTML=options;
                let total_amount=0;
                for (let i = 0; i < fees.options.length; i++) 
                { 

                    if(selected_fees.includes(fees.options[i].value))
                    {
                        fees.options[i].selected = true;
                        total_amount+=parseInt(fees.options[i].getAttribute("amount"));
                    }

                 }
                 total_amt.value=total_amount;
            }

        });

    </script>
    <script>
        const admission_result=<?php echo $admission_result ?>.flat();
        const overall_result=<?php echo $overall_result ?>;
        const admission_input=document.getElementById("admin");
        const input_name=document.getElementById("std");
        const input_class=document.getElementById("class");
        const input_section=document.getElementById("section");
        const changeEvent= new Event("change");
        admission_input.addEventListener("blur",()=>{
            let admin_value=admission_input.value;
            console.log(admission_result);
            let admission_index=admission_result.indexOf(admin_value);
             if(admission_index != -1)
             {
                const row =overall_result[admission_index];
                input_name.value=row["name"];
                input_class.value=row["class"];
                input_section.value=row["section"];
                input_class.dispatchEvent(changeEvent);
             }
             else 
             {
                console.log("nothing found");
             }

        });
    </script>
</body>
</html>
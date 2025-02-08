<?php
if (($_SERVER["REQUEST_METHOD"] === "POST") && isset($_POST["decide"])) {
    include ("./db_connection.php");
    $decide = trim( $_POST["decide"]);
    $index = trim($_POST["arr_index"]);
    $admission = trim( $_POST["admission"]);
    $name = trim( $_POST["stu_name"]);
    $class = strtoupper( trim( $_POST["class"]));
    $section = strtoupper(trim( $_POST["section"]));
    $term1 = trim( $_POST["term1"]);
    $term2 = trim( $_POST["term2"]);
    $term3 = trim( $_POST["term3"]);
    $paid_date = trim( $_POST["formatted_date"]);
    $scholarship = trim( $_POST["scholarship"]);
    $scholarship_amt = trim( $_POST["scholarship_amt"]);
    $write_off = trim( $_POST["write_off"]);
    $pending = trim( $_POST["pending"]);
    $fees_total_sql = "select sum($class) as total_receivable from fees_table where types like 'term%'";
    $result_fees_total = $con->query($fees_total_sql);
    $total_receivable = $result_fees_total->fetch_assoc()["total_receivable"] ?? 0;
    $total_receivable += $pending;
    
    if ($decide == "0") {
        $sno = ((int) $index + 1);
        $total_received = $term1 + $term2 + $term3 + $write_off + $scholarship_amt;
        $balance_receivable = $total_receivable - $total_received;
        $sql = "INSERT INTO overall (admission,name,class,section,term1,term2,term3,date,scholarship,scholarship_amount,pending,writeoff,total_receivable,total_received,balance_receivable) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        try {
            $stmt = $con->prepare($sql);
            echo $con->error;
            $stmt->bind_param("isssiiissiiiiii", $admission, $name, $class, $section, $term1, $term2, $term3, $paid_date, $scholarship,$scholarship_amt,$pending, $write_off, $total_receivable, $total_received, $balance_receivable);
            if ($stmt->execute()) {
                $id = mysqli_insert_id($con);
                $array_val = array(
                    $sno,
                    $admission,
                    $name,
                    $class,
                    $section,
                    $term1,
                    $term2,
                    $term3,
                    $paid_date,
                    $scholarship,
                    $scholarship_amt,
                    $pending,
                    $write_off,
                    $total_receivable,
                    $total_received,
                    $balance_receivable,
                    "<div id='action'>
                            <button type='button' style='height:35px;width:70px;' class='download-btn pixel-corners delete' ad='$admission' index=$index>
                        <div class='button-content' style=''>
                            <div class='svg-container'>
                            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 700 700' height='24' widht='24' style='fill:white;margin-bottom:3px;margin-left:8px;'><path d='M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z'/></svg>
                            </div>
                            <div class='text-container' style='height:37px'>
                            <div class='text'>Delete</div>
                            </div> 
                        </div>
                        </button>
                        <button typr='submit' style='height:35px;width:70px;' class='download-btn pixel-corners update' ad='$admission' index=$index>
                            <div class='button-content' style=''>
                                <div class='svg-container'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' style='fill: rgb(255, 255, 255);margin-bottom: 10px;'><path d='M11 15h2V9h3l-4-5-4 5h3z'></path><path d='M20 18H4v-7H2v7c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2v-7h-2v7z'></path></svg>
                                </div>
                                <div class='text-container' style='height:37px'>
                                <div class='text'>Update</div>
                                </div> 
                            </div>
                        </button>
                    </div>",
                );
                $response_array = array(1, $array_val);
                echo json_encode($response_array);
            }
            else{
                echo json_encode([404, "there is an error that might be admission number already exists or others"]);
        }
        }
        catch(Exception $e)
        {
            // echo json_encode([404, $e->getMessage()]);
            echo $stmt->error;
        }
    } else {
        $sno = $_POST["sno"];
        $total_received = $term1 + $term2 + $term3 + $write_off + $scholarship_amt;
        // echo "update";
        $balance_receivable = $total_receivable - $total_received;
        $sql = "update overall set admission = ? ,name = ? ,class = ? ,section = ? ,term1 = ? ,term2 = ? ,term3 = ? ,date = ? ,scholarship = ?,scholarship_amount = ? ,writeoff = ?,pending= ?  ,total_receivable = ? ,total_received = ? ,balance_receivable = ?  where admission = ?";

        try{
        $stmt=$con->prepare($sql);
        if($stmt == false)
        {
            echo $con->error;
        }
        $stmt->bind_param("isssiiissiiiiiii",$admission,$name,$class,$section,$term1,$term2,$term3,$paid_date,$scholarship,$scholarship_amt,$write_off,$pending,$total_receivable,$total_received,$balance_receivable,$decide);
        if ($stmt->execute()) {
                $array_val = array(
                    $sno,
                    $admission,
                    $name,
                    $class,
                    $section,
                    $term1,
                    $term2,
                    $term3,
                    $paid_date,
                    $scholarship,
                    $scholarship_amt,
                    $pending,
                    $write_off,
                    $total_receivable,
                    $total_received,
                    $balance_receivable,
                    "<div id='action'>
                            <button type='button' style='height:35px;width:70px;' class='download-btn pixel-corners delete' ad='$admission' index=$index>
                        <div class='button-content' style=''>
                            <div class='svg-container'>
                            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 700 700' height='24' widht='24' style='fill:white;margin-bottom:3px;margin-left:8px;'><path d='M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z'/></svg>
                            </div>
                            <div class='text-container' style='height:37px'>
                            <div class='text'>Delete</div>
                            </div> 
                        </div>
                        </button>
                        <button typr='submit' style='height:35px;width:70px;' class='download-btn pixel-corners update' ad='$admission' index=$index>
                        <div class='button-content' style=''>
                            <div class='svg-container'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' style='fill: rgb(255, 255, 255);margin-bottom: 10px;'><path d='M11 15h2V9h3l-4-5-4 5h3z'></path><path d='M20 18H4v-7H2v7c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2v-7h-2v7z'></path></svg>
                            </div>
                            <div class='text-container' style='height:37px'>
                            <div class='text'>Update</div>
                            </div> 
                        </div>
                        </button>
                        </div>"
                );
            $response_array = array(1,$array_val);
            echo json_encode($response_array);
            
        }
        else{
             echo json_encode([404, "The data is not updated in overall table"]);

        }
    }
    catch(Exception $e)
    {
            echo $stmt->error;
    }

    }
    $con->close();
}
else
{
    echo "<tr>server is not responded</tr";
}
?>
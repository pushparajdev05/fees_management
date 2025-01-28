<?php
include("../db_connection.php");
if (isset($_POST["decide"])) {
    $decide = trim($_POST["decide"]);
    $admission = trim($_POST["admission"]);
    $name = trim($_POST["std"]);
    $class = trim($_POST["class"]);
    $section = strtoupper(trim($_POST["section"]));
    
    // print_r($term);
    if ($decide == "0") {
        $type = trim($_POST["types"]);
        $total = trim($_POST["amt"]);
        $amt = trim($_POST["cashList"]);
        $insert_sno1 = ((int) trim($_POST["insert_sno1"])) + 1;
        $today = date("d/m/Y");
        $paid_date = $today;
        $action = false;
        $fees_term = [];
        $term_values = [];
        $stored_term = [];
        $column = [];
        $split_cash = explode(',', $amt);
        $split_fees = explode(',', $type);
        $total_receivable = 0;
        $balance_receivable = 0;
        $total_received = 0;
        $old_total = 0;
        $write_off = 0;
        $scholarship = "no";
        $i = 0;
        $j = 0;
        $term1 = 0;
        $term2 = 0;
        $term3 = 0;
        $old_term = [];
        $term = [];
        $fees_sql = "select types,$class from fees_table where types like 'term%'";
        $fees_res = $con->query($fees_sql);
        if ($fees_res->num_rows > 0) {
            while ($row = $fees_res->fetch_assoc()) {
                $fees_term[] = $row['types'];
                $term["{$row['types']}"] = $row["$class"];
                $old_total += $row["$class"];
            }
            ksort($fees_term);
        }
        while ($i < count($split_fees)) {
            $stored_fees["{$split_fees[$i]}"] = $split_cash[$i];
            $i++;
        }
        while ($j < count($fees_term)) {
            $term_values[] = $stored_fees["{$fees_term[$j]}"] ?? "null";
            $j++;
        }
        $overall_sql = "select term1,term2,term3,total_receivable,writeoff from overall where admission = {$admission}";
        $overall_res = $con->query($overall_sql);
        if ($overall_res->num_rows > 0) {
            $action = true;
            while ($row = $overall_res->fetch_assoc()) {
                $old_term[0] = $row['term1'];
                $old_term[1] = $row['term2'];
                $old_term[2] = $row['term3'];
                $total_receivable = $row["total_receivable"];
                $write_off = $row["writeoff"];
            }
        }
        $sql = "insert into cash (admission,name,class,section,type,amount,total,date) values (?,?,?,?,?,?,?,?)";
        $stmt = $con->prepare($sql);

        // echo $action;
        if ($action == true) {
            $a = 0;
            $term_column = ["term1", "term2", "term3"];
            $column = [];
            while ($a < count($term_values)) {
                if ($term_values[$a] != "null") {
                    // echo "the total count not null";
                    $cal_amount = $old_term[$a] + $term_values[$a];
                    if (($cal_amount <= $term["$fees_term[$a]"]) && ($term["{$fees_term[$a]}"] > $old_term[$a])) {
                        $total_received += $cal_amount;
                        $column[] = "{$term_column[$a]} = $cal_amount";
                    } else {
                        $del = 0;
                        foreach ($split_fees as $value) {
                            if ($value == $fees_term[$a]) {
                                unset($split_fees[$del]);
                                $split_fees = array_values($split_fees);
                                unset($split_cash[$del]);
                                $split_cash = array_values($split_cash);
                                break;
                            }
                            $del++;
                        }
                        $term_rep = $a + 1;
                        $total_received += $old_term[$a];
                        // echo "term $term_rep fees exceeds the limit of payment";
                    }
                } else {
                    // echo "the total count null";
                    $total_received += $old_term[$a];
                }
                $a++;
            }
            $type = implode(",", $split_fees);
            $amt = implode(",", $split_cash);
            $total = array_sum($split_cash);
            if ($type != "") {
                $stmt->bind_param("isssssis", $admission, $name, $class, $section, $type, $amt, $total, $today);
                if ($stmt->execute()) {
                    // print_r($term_values);
                    // print_r($old_term);
                    // print_r($term);
                    // echo "the total received $total_received";
                    $balance_receivable = abs($total_received - $total_receivable - $write_off);
                    $cat_column = implode(",", $column);
                    if (!(empty($cat_column))) {
                        $cat_column .= ",";
                    }
                    // print_r($column);
                    // echo "this is cat_column $cat_column";
                    $update_sql = "update overall set $cat_column date = ?,total_received = ? ,balance_receivable = ? where admission = ?";
                    $update_stmt = $con->prepare($update_sql);
                    // echo $update_sql;
                    $update_stmt->bind_param("siii", $paid_date, $total_received, $balance_receivable, $admission);
                    if ($update_stmt->execute()) {
                        $message = [101, "The Transaction has inserted in Daily and updated in Overall table"];
                    } else {
                        $message = [404, "The Transaction has inserted in Daily and not updated in Overall table"];
                    }
                    //TODO: collection insertion execution
                    $id = $stmt->insert_id;
                    $table_tr = [
                        $insert_sno1,
                        $admission,
                        $name,
                        $class,
                        $section,
                        $type,
                        $amt,
                        $total,
                        $today,
                        "<div id='action'>
                            <button type='button' style='height:35px;width:70px;' class='download-btn pixel-corners delete' ad={$id}>
                                <div class='button-content'>
                                    <div class='svg-container'>
                                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 700 700' height='24' widht='24' style='fill:white;margin-top:8px;margin-left:8px;'><path d='M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z'/></svg>
                                    </div>
                                    <div class='text-container'>
                                    <div class='text'>Delete</div>
                                    </div> 
                                </div>
                                </button>
                                <button typr='submit' style='height:35px;width:70px;' class='download-btn pixel-corners update' ad={$id}>
                                <div class='button-content'>
                                    <div class='svg-container'>
                                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' style='fill: rgb(255, 255, 255);'><path d='M11 15h2V9h3l-4-5-4 5h3z'></path><path d='M20 18H4v-7H2v7c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2v-7h-2v7z'></path></svg>
                                    </div>
                                    <div class='text-container'>
                                    <div class='text'>Update</div>
                                    </div> 
                                </div>
                                </button>
                                </div>"
                    ];
                    echo json_encode(array("message" => $message, "row" => [0, $table_tr]));

                } else {
                    echo $con->error;
                }
            } else {
                echo json_encode(["message" => [505, "Terms fee that given in the form exceeds the fee limits according to class.In addition, could not find any other fees so the insert operation is not performed"]]);
            }


        } else {
            $stmt->bind_param("isssssis", $admission, $name, $class, $section, $type, $amt, $total, $today);
            if ($stmt->execute()) {
                $b = 0;
                while ($b < count($term_values)) {
                    if ($term_values[$b] != "null") {
                        $stored_term[] = $term_values[$b];
                    } else {
                        $stored_term[] = 0;
                    }
                    $b++;
                }
                $term1 = $stored_term[0];
                $term2 = $stored_term[1];
                $term3 = $stored_term[2];
                // print_r($stored_term);
                $total_received = array_sum($stored_term);
                $total_receivable = $old_total;
                $balance_receivable = abs($total_receivable - $total_received - $write_off);
                $insert_sql = "INSERT INTO overall (admission,name,class,section,term1,term2,term3,date,scholarship,writeoff,total_receivable,total_received,balance_receivable) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $insert_stmt = $con->prepare($insert_sql);
                $insert_stmt->bind_param("isssiiissiiii", $admission, $name, $class, $section, $term1, $term2, $term3, $paid_date, $scholarship, $write_off, $total_receivable, $total_received, $balance_receivable);
                if ($insert_stmt->execute()) {
                    $message = [101, "The Transaction has inserted in Daily and Overall table"];
                } else {
                    $message = [404, "The Transaction has inserted in Daily and not in Overall table"];

                }
                //TODO: collection insertion execution
                $id = $stmt->insert_id;
                $table_tr = [
                    $insert_sno1,
                    $admission,
                    $name,
                    $class,
                    $section,
                    $type,
                    $amt,
                    $total,
                    $today,
                    "<div id='action'>
                <button type='button' style='height:35px;width:70px;' class='download-btn pixel-corners delete' ad={$id}>
                    <div class='button-content'>
                        <div class='svg-container'>
                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 700 700' height='24' widht='24' style='fill:white;margin-top:8px;margin-left:8px;'><path d='M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z'/></svg>
                        </div>
                        <div class='text-container'>
                        <div class='text'>Delete</div>
                        </div> 
                    </div>
                    </button>
                    <button typr='submit' style='height:35px;width:70px;' class='download-btn pixel-corners update' ad={$id}>
                    <div class='button-content'>
                        <div class='svg-container'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' style='fill: rgb(255, 255, 255);'><path d='M11 15h2V9h3l-4-5-4 5h3z'></path><path d='M20 18H4v-7H2v7c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2v-7h-2v7z'></path></svg>
                        </div>
                        <div class='text-container'>
                        <div class='text'>Update</div>
                        </div> 
                    </div>
                    </button>
                    </div>"
                ];
                echo json_encode(array("message" => $message, "row" => [0, $table_tr]));
            } else {
                echo $con->error;
            }

        }
    } 
     else {
        $old_admission = trim($_POST["old_ad"]);
        $sno1 = trim($_POST["sno1"]);
        $today = trim($_POST["date"]);
        $sql= "update cash set name= ?,class= ?,section= ? where sno= ? ";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssi",$name,$class,$section,$old_admission);
        $overall_update="update overall set name= ?,class= ?,section= ? where admission= ?";
        $overall_stmt = $con->prepare($overall_update);
        $overall_stmt->bind_param("sssi", $name, $class, $section,$admission);
        if ($stmt->execute()) {
            $message = "";
            if($overall_stmt->execute())
            {
                $table_tr = [
                    $name,
                    $class,
                    $section,
                ];
                 $message = "The Transaction has updated in Daily Collection and overall table";
            }
            else{
                $message = "The Transaction has updated in Daily Collection and not updated in overall table, reason could be (not found student record related to admission no)";
            }
            echo json_encode(["row"=>[0, $table_tr],"message"=>[101,$message]]);
        } else {
                       echo json_encode(["message"=>[404,"The transaction has not updated in Daily Collection"]]);

        }
    }
    $con->close();
}
?>
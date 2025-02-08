<?php
include './db_connection.php';

$csvFile = 'overall_csv.csv';
$uploaded = false;

if (isset($_FILES['csv_file'])) {
    $file_name = $_FILES["csv_file"]["name"];
    $target_file = basename($file_name);
    $uploadOk = 1;
    $FileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if ($FileType != "csv") {
        // echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
        echo "file does not seem to be csv file type";
        exit();
    }
    if ($uploadOk == 0) {

            echo "something went wrong,CSV have not uploaded on server ";

    } else {
        if (move_uploaded_file($_FILES["csv_file"]["tmp_name"],$csvFile)) {
            $uploaded = true;
        } else {
            // echo "CSV file cannot move to server";
            echo "sorry there is a problem to move file to server folder";
        }
    }
}
else
{
    echo "CSV File not Found";
}


// Path to the CSV file

if($uploaded==true)
{
    if (file_exists($csvFile)) {
        $action=$_POST["action"];
        $class_array = ["sum(LKG) as LKG", "sum(UKG) as UKG", "sum(I) as I", "sum(II) as II", "sum(III) as III", "sum(IV) as IV", "sum(V) as V", "sum(VI) as VI", "sum(VII) as VII", "sum(VIII) as VIII", "sum(IX) as IX", "sum(X) as X", "sum(XIAC) as XIAC","sum(XIDE) as XIDE", "sum(XIIAC) as XIIAC", "sum(XIIDE) as XIIDE"];
        $class_string = implode(",", $class_array);
        $fees_sql = "select $class_string from fees_table where types like 'term%'";
        // echo $fees_sql;
        $res_fees = $con->query($fees_sql);
        if($res_fees->num_rows > 0)
        {
            $fees_term_total = $res_fees->fetch_all(MYSQLI_ASSOC)[0];
        }
        // print_r($fees_term_total);
        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            $header = fgetcsv($handle, 1000, ",");
            $new_fees = false;
            $values = [];
            $values_old = [];
            $insertSql = "INSERT INTO overall (admission,name,class,section,term1,term2,term3,date,scholarship,scholarship_amount,pending,writeoff,total_receivable,total_received,balance_receivable) VALUES ";
            while (($data = fgetcsv($handle, 1200, ",")) !== FALSE) 
            {
                $admission = (int)trim($data[0]);
                $name = trim($data[1]);
                $class = strtoupper(trim($data[2]));
                // echo empty($admission) . !(is_int($admission)) . empty($name) . empty($class);
                if((empty($admission) || !(is_int($admission))) || empty($name) || empty($class))
                {
                    // echo "the loop is executed";
                    continue;
                }
                $section = strtoupper(trim($data[3]));
                $date = trim($data[7]);
                $scholar = strtolower(trim($data[8]));
                $scholar_amt = trim($data[9]);
                $pending = trim($data[10]);
                $writeoff = trim($data[11]);
                $total_receivable = $fees_term_total["$class"] ?? 0;
                $term1 = trim($data[4]);
                $term2 = trim($data[5]);
                $term3 = trim($data[6]);
                if(empty($section))
                {
                    $section = "nil";
                }
                if(empty($date))
                {
                    $date = "nil";
                }
                if(empty($scholar))
                {
                    $scholar = "no";
                }
                if(empty($writeoff) || !(is_int((int)$writeoff)))
                {
                    $writeoff = 0;
                }
                if(empty($pending) || !(is_int((int)$pending)))
                {
                    $pending = 0;
                }
                if(empty($scholar_amt) || !(is_int((int)$scholar_amt)))
                {
                    $scholar_amt = 0;
                }
                if(!(is_int((int)$term1)) || empty($term1))
                {
                    $term1 = 0;
                }
                if(!(is_int((int)$term2)) || empty($term2))
                {
                    $term2 = 0;
                }
                if(!(is_int((int)$term3)) || empty($term3))
                {
                    $term3 = 0;
                }
                $total_receivable += $pending;
                $total_received = $term1 + $term2 + $term3 + $writeoff + $scholar_amt;
                $balance_receivable = $total_receivable - $total_received ;
                $values[] = "($admission,'$name','$class','$section',$term1,$term2,$term3,'$date','$scholar',$scholar_amt,$pending,$writeoff,$total_receivable,$total_received,$balance_receivable)";
            }
            $cat_values = implode(", ", $values);
            if(empty($cat_values))
            {
                echo "even single record of them don't have suitable format so it did not push into overall table";
            }
             else
              {
                $insertSql .= $cat_values;
                if($action == "append")
                {
                    if ($con->query($insertSql)) {
                        echo "the data has appended successfully";
                    }
                    else
                    {
                        echo "the data has not appended";
                    }
                }
                else
                {
                $con->begin_transaction();
                $truncateSql = "delete from overall";
                if ($con->query($truncateSql)) {
                    if ($con->query($insertSql)) {
                        // echo "commit";
                        $con->commit();
                        echo 1;
                    } else {
                        // echo "rollback";
                        echo $con->error;
                        $con->rollback();
                        // echo "overall csv file has not been push into database";
                        /*  if($res->num_rows>0)
                         {
                             while($row=$res->fetch_assoc())
                             {
                                 $old_types = $row['types'];
                                 $old_amount = $row['amount'];
                                 $values_old[] = "('$old_types',$old_amount)";
                             }
                             $insertSql = "INSERT INTO fees_table (types, amount) VALUES ";
                             $insertSql .= implode(", ", $values_old);
                             if($con->query($insertSql))
                             {
                                 echo "new fees amount did not push into table so it has reset to old fees";
                             }
                             else{
                                 echo "new and old fees amount has failed to be pushed or reset ";
                             }
                         } */
                    }
                } else {
                    echo "overall csv file has not been push into database since did not delete the old data in table";
                }
            }
        }
            fclose($handle);
            unlink($csvFile);
        }
         else
        { 
            echo "Error opening the csv file";
        }
    } else {
        echo "CSV file not Found";
    }
}
else
{
    echo "\nCSV file has not uploaded";
}

?>
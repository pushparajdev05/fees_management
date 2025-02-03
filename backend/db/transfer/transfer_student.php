<?php
include "./db_connection.php";
require "vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$class_arr = ["LKG", "UKG", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X","XIAC","XIDE", "XIIAC","XIIDE"];
$con->begin_transaction();
function transfer_student($con, $class_arr)
{
    $index = 1;
    $class_data = [];
    $higher_class = [["XIIAC", "XIIDE"], ["XIAC", "XIDE"]];
    $select_sql1 = "select * from overall where";
    $resultset1 = $con->query($select_sql1);
    if ($resultset1->num_rows > 0) {
        $class_data = $resultset1->fetch_all(MYSQLI_ASSOC);
    }
    for ($j = 0; $j < count($class_arr); $j++) {
        $class = $class_arr[$j];
        $update_sql1 = "update overall set class = '$index' where class='$class'";
        if ($j < 12) {
            if ($con->query($update_sql1)) {
                echo "<p style='font-size:18px'>successfully indexed</p>";
            } else {
                echo "<p style='font-size:18px'>failed to indexing..</p>";
                return [false, []];
            }
        }
        $index++;
    }
    for ($i = 0; $i < 12; $i++) {
        $class_index = $i + 1;
        if ($class_index == 12) {
            $update_sql2 = "update overall set class = 'XI' where class='$class_index'";
        } else {
            $update_sql2 = "update overall set class = '{$class_arr[$class_index]}' where class='$class_index'";

        }
        if ($con->query($update_sql2)) {
            echo "<p style='font-size:18px'>successfully transferred student from LKG to X</p>";
        } else {
            echo "<p style='font-size:18px'>failed to transfer student from LKg to X</p>";
            return [false, []];

        }
    }

    $passed_year = date("Y");
    for ($a = 0; $a < count($higher_class[0]); $a++) {
        $higher = $higher_class[0][$a];
        $higher_sql = "insert into passedout (admission,name,class,section,passed_year,pending) select admission,name,class,section,$passed_year as passed_year ,pending from overall where class = '$higher' ";
        if ($con->query($higher_sql)) {
            echo "<p style='font-size:18px'>successfully transferred student from $higher to Passed Out</p>";
            $del_sql = "delete from overall where class= '$higher'";
            if ($con->query($del_sql)) {
                $higher2 = $higher_class[1][$a];
                $higher_update = "update overall set class = '$higher' where class= '$higher2'";
                if ($con->query($higher_update)) {
                    echo "<p style='font-size:18px'>student are transferred from $higher2 to $higher</p>";
                } else {
                    echo "<p style='font-size:18px'>failed to transfer the student from $higher2 to $higher</p>";
                    return [false, []];
                }
            }
        } else {
            echo "<p style='font-size:18px'>falied to tranfer XII student to passed out table</p>";
            return [false, []];
        }
    }
    return [true, $class_data];
}
function history_copy($con)
{
    $passed_year = date("Y");
    $data = [];
    $create_history_sql = "create table {$passed_year}_ (admission mediumint,name text,class text,section text,type text,amount text,total mediumint,date varchar(12),mode text)";
    if ($con->query($create_history_sql)) {
        $mode = ["cash", "cheque"];
        foreach ($mode as $value) {
            $copy_transaction_sql = "insert into {$passed_year}_ (admission,name,class,section,type,amount,total,date,mode) select admission,name,class,section,type,amount,total,date,'$value' as mode from $value ";
            if ($con->query($copy_transaction_sql)) {
                echo "<p style='font-size:18px'>the transaction of $value copied to the maintance history table</p>";
            } else {
                echo "<p style='font-size:18px'>Failed to copy the $value to the maintance history table</p>";
                return [false,$data];
            }
            $history_sql = "select * from {$passed_year}_";
            $result_history = $con->query($history_sql);
            if($result_history->num_rows > 0)
            {
                $data = $result_history->fetch_all(MYSQLI_ASSOC);
            }
        }
        $created_date = date("d/m/Y");
        $transaction_detail_sql = "insert into transfer_details(table_name,date) values({$passed_year}_,$created_date)";
        if ($con->query($transaction_detail_sql)) {
            echo "<p style='font-size:18px'>the copied transaction's futher details are stored</p>";
        }
    }
    else
    {
        echo "<p style='font-size:18px'>Failed to create a table for $passed_year to store the transaction</p>";
        return [false,$data];
    }
    return [true,$data];
}
function addDataToSheet($spreadsheet, $sheetIndex, $sheetName, $data)
{
    $spreadsheet->createSheet($sheetIndex);
    $spreadsheet->setActiveSheetIndex($sheetIndex);
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle($sheetName);
    // Fetch the field names
    if (!empty($data)) {
        $fields = array_keys($data[0]);
        $col = 'A';
        foreach ($fields as $field) {
            $sheet->setCellValue($col . '1', $field);
            $col++;
        }

        // Add data to the spreadsheet
        $row = 2;
        foreach ($data as $row_data) {
            $col = 'A';
            foreach ($row_data as $value) {
                $sheet->setCellValue($col . $row, $value);
                $col++;
            }
            $row++;
        }
        return true;
    } else {
        return false;
    }

}
// transfer student from LKG to UKG
$transfer_ = transfer_student($con,$class_arr);
$save_decision = $transfer_[0];
$class_data = $transfer[1];

// copy the transaction of the year to year table
$history_ = history_copy($con);
$save_decision = $history_[0];
$history_data = $history[1];
if($save_decision)
{    // Create a new spreadsheet
$spreadsheet = new Spreadsheet();
    $current_date = date("Y");
    $sheet1=addDataToSheet( $spreadsheet,0,"overall_sheet",$class_data );
    $sheet2=addDataToSheet( $spreadsheet,1,"transaction of the {$current_date} year",$history_data );
    if ($sheet1 || $sheet2) {
        $filePath = './overall_data.xlsx';
        // Create a writer and save the file
        $writer = new Xlsx($spreadsheet);
        if(file_exists($file_path))
        {
            if(unlink($filePath))
            {
                $writer->save($filePath);
                $con->commit();
            }
        }
        else{
            $writer->save($filePath);
            $con->commit();

        }
    }
    else
    {
        echo "<p style='font-size:18px'> Failed to download the excel sheet data for overall and history transaction</p>";
        $con->rollback();
    }
// Function to add data to a sheet

}
else
{
    $con->rollback();
}

?>

<?php
include './db_connection.php';

$csvFile = 'load_csv.csv';
$uploaded = false;

if (isset($_FILES['csv'])) {
    $file_name = $_FILES["csv"]["name"];
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
        if (move_uploaded_file($_FILES["csv"]["tmp_name"],$csvFile)) {
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
        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            $header = fgetcsv($handle, 1000, ",");
            $new_fees = false;
            $values = [];
            $values_old = [];
            $insertSql = "INSERT INTO fees_table (types,LKG,UKG,I,II,III,IV,V,VI,VII,VIII,IX,X,XI,XII) VALUES ";
            while (($data = fgetcsv($handle, 1200, ",")) !== FALSE) 
            {
                $fees_types = strtolower(trim($data[0]));
                $LKG =trim($data[1]);
                $UKG = trim($data[2]);
                $I = trim($data[3]);
                $II = trim($data[4]);
                $III=trim($data[5]);
                $IV=trim($data[6]);
                $V=trim($data[7]);
                $VI=trim($data[8]);
                $VII=trim($data[9]);
                $VIII=trim($data[10]);
                $IX=trim($data[11]);
                $X=trim($data[12]);
                $XI=trim($data[13]);
                $XII=trim($data[14]);
                $values[] = "('{$fees_types}',$LKG,$UKG,$I,$II,$III,$IV,$V,$VI,$VII,$VIII,$IX,$X,$XI,$XII)";
            }
            $insertSql .= implode(", ", $values);
            $con->begin_transaction();
            $truncateSql = "delete from fees_table";
            if($con->query($truncateSql))
            {
                if ($con->query($insertSql)) 
                {
                    echo 1;
                    $con->commit();
                }
                else
                {
                    $con->rollback();
                    echo "new fees amount did not push into table so it has reset to old fees";
                }
            }
            else{
                echo "fees table does not delete the data to push new fees";
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
    echo "csv file has not uploaded";
}

?>
<?php
include "./db_connection.php";
if(isset($_POST["year"]))
{
    $year = $_POST["year"];
    $table_name = "{$_POST['year']}_";
    $details_sql = "select * from transfer_details where table_name = '$table_name'";
    $details_result = $con->query($details_sql);
    if($details_result->num_rows > 0)
    {
        $thead = "
                            <tr>
                                <th>Sno</th>
                                <th>Admission no</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Fees Type</th>
                                <th>Amounts</th>
                                <th>Total</th>
                                <th>Date</th>
                                <th>Mode</th>
                            </tr>
        ";
        $tr = "";
        $tbody = "";
        $transaction_sql = "select * from $table_name";
        $sno = 1;
        $transaction_result=$con->query($transaction_sql);
        if($transaction_result->num_rows > 0)
        {
            while($row=$transaction_result->fetch_assoc())
            {
                 $tr .= "
                                     <tr>
                                        <td>$sno</td>
                                        <td>{$row['admission']}</td>
                                        <td>{$row['name']}</td>
                                        <td>{$row['class']}</td>
                                        <td>{$row['section']}</td>
                                        <td>{$row['type']}</td>
                                        <td>{$row['amount']}</td>
                                        <td>{$row['total']}</td>
                                        <td>{$row['date']}</td>
                                        <td>{$row['mode']}</td>
                                    </tr>";
                $sno++;
            }
            $tbody .= $tr;
            echo json_encode([200,$thead,$tbody]);
        }
        else{
            echo json_encode([200, $thead, $tbody]);            
        }
    }
    else
    {
        echo json_encode([404,"The transaction of $year not found"]);
    }
}
?>
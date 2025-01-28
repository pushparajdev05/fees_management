<?php

// use function PHPSTORM_META\type;

include "./db_connection.php";

if(isset($_POST["collection_date"]))
{
    $ondate=$_POST["collection_date"];
    $cash_sql = "select * from cash where date = '{$ondate}'";
    $cheque_sql = "select * from cheque_online where date = '{$ondate}'";
    $fees_table = "select * from fees_table";
    $cash_res = $con->query($cash_sql);
    $cheque_res=$con->query($cheque_sql);
    $res_fees_table = $con->query($fees_table);
    $fees_types=[];
    // $fees_amount = [];
    $cash_data = array();
    // echo "$ondate";
    //TODO:fees_table data seperation

    if($res_fees_table->num_rows>0)
    {
        while($row=$res_fees_table->fetch_assoc())
        {
            $fees_types[] = $row["types"];
        }
    }
    foreach($fees_types as $type)
    {
        $cash_data["$type"] = array();
    }

    //TODO:cash collection separation code below

    if($cash_res->num_rows>0)
    {
        while($row=$cash_res->fetch_assoc())
        {
            $ad = $row["admission"];
            $name = $row["name"];
            $cls = $row["class"];
            $sec = $row["section"];
            $tp = $row["type"];
            $amt = $row["amount"];
            $splited_tp = explode(",", $tp);
            $splited_amt = explode(",", $amt);
            $count=0;
            foreach($splited_tp as $new_type)
            {
                foreach($fees_types as $old_type)
                {
                    if($old_type == $new_type)
                    {
                        $cash_data["$old_type"][] = array($ad,$name,$cls,$sec,$old_type,$splited_amt[$count]);
                        $count += 1;
                        break;
                    }
                }
            }
        }
    }

    //table design in collection cash and cheque

    $table_start = "
        <h1>
        DAILY CASH FEES COLLECTION STATEMENT
    </h1>
     <div id='daily_cash'>
        <table id='cash'>
         
            <tr>
                <th>Sno</th> 
                <th>Admission No</th>
                <th>Name</th>
                <th>Class</th>
                <th>Section</th>
                <th>Fees Type</th>
                <th>Amount</th>
            </tr>
    ";
    $terms = [];
    $others = [];
    foreach($fees_types as $type)
    {
        if(preg_match("/term/i",$type))
        {
            $terms[] = $type;
        }
        else
        {
            $others[] = $type;
        }
    }
    $term_tr="";
    $term_total = 0;
    $cash_total = [];
    // echo count($terms);
    if (!(empty($terms))) {
        $table_start .= "<tr>
                <th class='head' colspan='3'></th>
                <th class='head'>Term Fees</th>
                <th class='head' colspan='3'></th>
            </tr>";
        $sno = 0;
        foreach ($terms as $term) {
            $length = count($cash_data["$term"]);

            for ($i = 0; $i < $length; $i++) {
                $sno += 1;
                $row = $cash_data["$term"][$i];
                $term_total += $row[5];
                $term_tr .= "
                        <tr>
                        <td>$sno</td>
                        <td>$row[0]</td>
                        <td>$row[1]</td>
                        <td>$row[2]</td>
                        <td>$row[3]</td>
                        <td>$row[4]</td>
                        <td>$row[5]</td>
                    </tr>";
            }

        }
        //TODO: total of cash start from here
        $cash_total["term_fees"] = $term_total;
        $term_tr .= "<tr>
                    <th style='text-align:right;padding-right:20px;' colspan='6'>Total</th>
                    <th>$term_total</th>
                </tr>";
    }
    $other_tr = "" ;
    foreach($others as $type)
    {
        $others_total = 0;
        $sno = 1;
        $other_tr .="<tr>
                <th class='head' colspan='3'></th>
                <th class='head'>$type</th>
                <th class='head' colspan='3'></th>
            </tr>";
        $length = count($cash_data["$type"]);
        for ($i = 0; $i < $length; $i++) {
            $sno += $i;
            $row = $cash_data["$type"][$i];
            $others_total += $row[5];
            $other_tr .= "
                    <tr>
                    <td>$sno</td>
                    <td>$row[0]</td>
                    <td>$row[1]</td>
                    <td>$row[2]</td>
                    <td>$row[3]</td>
                    <td>$row[4]</td>
                    <td>$row[5]</td>
                </tr>";
        }
        $other_tr .= "<tr>
                <th style='text-align:right;padding-right:20px;' colspan='6'>Total</th>
                <th>$others_total</th>
            </tr>";
        $cash_total["$type"] = $others_total;
    }
    $cash_grand_total = 0;
    foreach($cash_total as $key => $value)
    {
        $cash_grand_total += $value;
    }
    $table_end = " <tr>
                <th style='text-align:right;padding-right:20px;' colspan='6'>Grand Total</th>
                <th>$cash_grand_total</th>
            </tr>
        </table>
     </div>";

    //TODO: Daily cash table concatenation

    $daily_cash = $table_start . $term_tr . $other_tr . $table_end;

    //TODO: daily cheque/online table start from here
    $cheque_start = "
    <div id='cheque'>
        <h1>
            DAILY CHEQUE/ONLINE COLLECTION STATEMENT
        </h1>
        <table id='online'>
         <tr>
                <th>Sno</th>
                <th>Admission No</th>
                <th>Name</th>
                <th>Class</th>
                <th>Section</th>
                <th>Fees Type</th>
                <th>Amount</th>
            </tr> ";
    $cheque_tr="";
    $cheque_grand_total = 0;
    if ($cheque_res->num_rows > 0) {
        $sno = 0;
        while ($row = $cheque_res->fetch_assoc()) {
            $ad = $row["admission"];
            $name = $row["name"];
            $cls = $row["class"];
            $sec = $row["section"];
            $tp = $row["type"];
            $amt = $row["amount"];
            $splited_tp = explode(",", $tp);
            $splited_amt = explode(",", $amt);
            $count = 0;
            foreach ($splited_tp as $new_type) {
                $sno++;
                $cheque_tr .= "
                <tr>
                <td>$sno</td>
                <td>$ad</td>
                <td>$name</td>
                <td>$cls</td>
                <td>$sec</td>
                <td>$new_type</td>
                <td>{$splited_amt[$count]}</td>
            </tr>";
                $cheque_grand_total += $splited_amt[$count];
                $count++;
            }
        }
    }
    $cheque_end = "
            <tr>
                <th style='text-align:right;padding-right:20px;' colspan='6'>Grand Total</th>
                <th>$cheque_grand_total</th>
            </tr> 
        </table>
     </div>";
    $daily_cheque_online = $cheque_start . $cheque_tr . $cheque_end;

    //TODO:end of cheque/online table collection

    //TODO: total of daily collection of cash and cheque/online collection
    $total_table_start = "
    <div id='Total'>
        <h1>
            TOTAL OF DAILY CASH AND CHEQUE/ONLINE COLLECTION
        </h1>
        <table id='total_cash_online'>
        <tr>
            <th class='head' colspan='2'>Cash Collection</th>
        </tr>
         <tr>
                <th>Fees Type</th>
                <th>Amount</th>
            </tr> ";
    $total_table_body="";
    if (!(empty($terms))) {        
        $total_table_body .= " <tr>
                    <td>Term Fees</td>
                    <td>{$cash_total['term_fees']}</td>
                </tr>";
        }
        
    foreach($others as $type)
    {
            $total_table_body .= " <tr>
                <td>$type</td>
                <td>".$cash_total["$type"]."</td>
            </tr>";
    }
    $total_collection = "
    <tr style='border:none'> 
                <th style='border:none'>&nbsp;</th>
                <th style='border:none'>&nbsp;</th>
            </tr>
            <tr>
                <th>Total of Cash Collection</th>
                <th>$cash_grand_total</th>
            </tr>";
    $overall_total_cash = $cash_grand_total + $cheque_grand_total;
    $total_collection .= "
            <tr>
                <th>Total of Cheque/Online Collection</th>
                <th>$cheque_grand_total</th>
            </tr>
            <tr>
                <th>Grand Total</th>
                <th>$overall_total_cash</th>
            </tr> 
        </table>
     </div>";
    $total_daily_collection_table = $total_table_start . $total_table_body . $total_collection;
    
    //overall table design here

    $overall_table_structure = $daily_cash . $daily_cheque_online . $total_daily_collection_table;

    echo $overall_table_structure;
}
?>
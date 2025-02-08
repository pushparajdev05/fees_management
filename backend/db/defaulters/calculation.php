<?php 
include ("./db_connection.php");
if (isset($_POST["option"])) {
    $class_array = ["LKG", "UKG", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XIAC", "XIDE", "XIIAC", "XIIDE"];
    $table = "";
    if ($_POST["option"] == "Class") {
        $no_stud = [];
        $terms_receivable = [];
        $term_total_receivable = [];
        $terms_received = [];
        $total_writeoff = [];
        $total_term_received = [];
        $scholarship_count = [];
        $total_defaults = [];
        $table_head = "
                    <tr>
                        <td>No of Students</td>
                        <td>class</td>
                        <td>First Quarter Receivables</td>
                        <td>Second Quarter Receivables</td>
                        <td>Third Quarter Receivables</td>
                        <td>Total</td>
                        <td>First Quarter Received</td>
                        <td>Second Quarter Received</td>
                        <td>Third Quarter Received</td>
                        <td>Scholarship</td>
                        <td>Scholarship Amount</td>
                        <td>Pending</td>
                        <td>Write Off</td>
                        <td>Total Received and Other Adjustments</td>
                        <td>defaulters</td>
                    </tr>
        ";
        foreach ($class_array as $value) {
            $no_stud_sql = "select count(admission) as total from overall where class= '$value'";
            $no_stud_res = $con->query($no_stud_sql);
            if ($no_stud_res->num_rows > 0) {
                while ($row = $no_stud_res->fetch_assoc()) {
                    $no_stud["$value"] = $row["total"];
                }
            }

            $term_sql = "select $value from fees_table where types like 'term%'";
            $term_res = $con->query($term_sql);
            if ($term_res->num_rows > 0) {
                while ($row = $term_res->fetch_assoc()) {
                    $terms_receivable["$value"][] = $row["$value"] * $no_stud["$value"];
                }
                $term_total_receivable["$value"] = $terms_receivable["$value"][0] + $terms_receivable["$value"][1] + $terms_receivable["$value"][2];
            }
            $term_rec_sql = "select SUM(term1) as term1, SUM(term2) as term2, SUM(term3) as term3 ,SUM(scholarship_amount) as scholar_amt,SUM(pending) as pending, SUM(writeoff) as writeoff from overall where class = '$value'";
            $term_rec_res = $con->query($term_rec_sql);
            if ($term_rec_res->num_rows > 0) {
                while ($row = $term_rec_res->fetch_assoc()) {
                    $terms_received["$value"][] = $row["term1"];
                    $terms_received["$value"][] = $row["term2"];
                    $terms_received["$value"][] = $row["term3"];
                    $total_scholar_amt["$value"] = $row["scholar_amt"];
                    $total_writeoff["$value"] = $row["writeoff"];
                    $total_pending["$value"] = $row["pending"];
                    $total_term_received["$value"] = $terms_received["$value"][0] + $terms_received["$value"][1] + $terms_received["$value"][2] + $row["writeoff"] + $row["scholar_amt"];
                    $total_defaults["$value"] = $total_pending["$value"] + $term_total_receivable["$value"] - $total_term_received["$value"];
                }
            }
            $scholarship_sql = "select count(scholarship) as total_count from overall where class = '$value ' and scholarship = 'yes'";
            $scholarship_res = $con->query($scholarship_sql);
            if ($scholarship_res->num_rows > 0) {
                while ($row = $scholarship_res->fetch_assoc()) {
                    $scholarship_count["$value"] = $row["total_count"];
                }
            }
        }

        $table_tr = "";
        foreach ($class_array as $value) {
            $table_tr .= "<tr>
                    <td>$no_stud[$value]</td>
                    <td>$value</td>
                    <td>{$terms_receivable[$value][0]}</td>
                    <td>{$terms_receivable[$value][1]}</td>
                    <td>{$terms_receivable[$value][2]}</td>
                    <td>{$term_total_receivable[$value]}</td>
                    <td>{$terms_received[$value][0]}</td>
                    <td>{$terms_received[$value][1]}</td>
                    <td>{$terms_received[$value][2]}</td>
                    <td>{$scholarship_count[$value]}</td>
                    <td>{$total_scholar_amt[$value]}</td>
                    <td>{$total_pending[$value]}</td>
                    <td>{$total_writeoff[$value]}</td>
                    <td>{$total_term_received[$value]}</td>
                    <td>{$total_defaults[$value]}
                    </tr>
                    ";
        }
        $col1 = array_sum($no_stud);
        $col3 = 0;
        $col4 = 0;
        $col5 = 0;
        $col7 = 0;
        $col8 = 0;
        $col9 = 0;

        foreach ($terms_receivable as $value) {
            $col3 += $value[0];
            $col4 += $value[1];
            $col5 += $value[2];
        }
        $col6 = array_sum($term_total_receivable);
        foreach ($terms_received as $value) {
            $col7 += $value[0];
            $col8 += $value[1];
            $col9 += $value[2];
        }
        $col10 = array_sum($scholarship_count);
        $col11 = array_sum($total_scholar_amt);
        $col12 = array_sum($total_pending);
        $col13 = array_sum($total_writeoff);
        $col14 = array_sum($total_term_received);
        $col15 = array_sum($total_defaults);
        $table_tr .= '<tr>
                        <td style="border:none"></td>
                        <td style="border:none"></td>
                        <td style="border:none"></td>
                        <td style="border:none"></td>
                        <td style="border:none">TOTAL</td>
                        <td style="border:none">OF</td>
                        <td style="border:none">THE</td>
                        <td style="border:none">ABOVE</td>
                        <td style="border:none">FIELDS</td>
                        <td style="border:none"></td>
                        <td style="border:none"></td>
                        <td style="border:none"></td>
                        <td style="border:none"></td>
                        <td style="border:none"></td>
                        <td style="border:none"></td>
                    </tr>';
        $table_tr .= "<tr> 
            <td>$col1</td>
            <td></td>
            <td>$col3</td>
            <td>$col4</td>
            <td>$col5</td>
            <td>$col6</td>
            <td>$col7</td>
            <td>$col8</td>
            <td>$col9</td>
            <td>$col10</td>
            <td>$col11</td>
            <td>$col12</td>
            <td>$col13</td>
            <td>$col14</td>
            <td>$col15</td>
            </tr>";
        $response = [200, $table_head, $table_tr];
        echo json_encode($response);
    } else if (preg_match("/Term/", $_POST["option"]) || ($_POST["option"] == "Class & Sec")) {
        $term_fees = [];
        foreach ($class_array as $class_) {
            $term_sql = "select $class_ from fees_table where types like 'term%'";
            $term_res = $con->query($term_sql);
            if ($term_res->num_rows > 0) {
                while ($row = $term_res->fetch_assoc()) {
                    $term_fees[$class_][] = $row[$class_];
                }
            }
        }
        // print_r($term_fees);
        if (count($term_fees["LKG"]) >= 3) {
            $tbody = "";
            if ($_POST["option"] == "Term I") {
                $class_sec = [];
                $thead = "
                            <tr>
                                <th>Sno</th>
                                <th>Admission no</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Term I receivable</th>
                            </tr>
                            ";
                $grand_total = 0;
                for ($i = 0; $i < count($class_array); $i++) {
                    $class = $class_array[$i];
                    $tr = "";
                    $section_sql = "select section from overall where class = '{$class_array[$i]}'";
                    $section_result = $con->query($section_sql);
                    if ($section_result->num_rows > 0) {
                        $class_sec[$class] = call_user_func_array("array_merge", $section_result->fetch_all());
                        $class_sec[$class] = array_values(array_unique($class_sec[$class]));
                        foreach ($class_sec[$class] as $section) {
                            $term1_sql = "select admission,name,class,section,({$term_fees[$class][0]} - term1) as term1_receivable from overall where class = '{$class}' and section = '{$section}' and term1 < {$term_fees[$class][0]}";
                            $sno = 1;
                            $term1_res = $con->query($term1_sql);
                            $total_section_amt = 0;
                            if ($term1_res->num_rows > 0) {
                                while ($row = $term1_res->fetch_assoc()) {
                                    $tr .= "
                                     <tr>
                                        <td>$sno</td>
                                        <td>{$row["admission"]}</td>
                                        <td>{$row["name"]}</td>
                                        <td>{$row["class"]}</td>
                                        <td>{$row["section"]}</td>
                                        <td>{$row["term1_receivable"]}</td>
                                    </tr>";
                                    $total_section_amt += $row["term1_receivable"];
                                    $sno++;
                                }
                                $tr .= "<tr>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td>Total</td>
                                <td>$total_section_amt</td>
                                </tr>";
                                $grand_total += $total_section_amt;
                            } else {
                                $tr .= "
                                     <tr>
                                        <td>$sno</td>
                                        <td style='border-right:none'></td>
                                        <td style='border-left:none'></td>
                                        <td>{$class}</td>
                                        <td>{$section}</td>
                                        <td>0</td>
                                    </tr>";
                                $tr .= "<tr>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td>Total</td>
                                <td>$total_section_amt</td>
                                </tr>";
                            }
                        }
                    }
                    $tbody .= $tr;
                }
                $tbody .= "
                                <tr>
                                <td style='border-right:none;border-left:none;'></td>
                                <td style='border-right:none;border-left:none;'></td>
                                <td style='border-right:none;border-left:none;'></td>
                                <td style='border-right:none;border-left:none;'></td>
                                <td style='border-right:none;border-left:none;'></td>
                                <td style='border-right:none;border-left:none;'></td>
                                </tr>
                                <tr>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td>Grand Total</td>
                                <td>{$grand_total}</td>
                                </tr>
                                ";
                echo json_encode([201, $thead, $tbody]);
            } else if ($_POST["option"] == "Term II") {
                $class_sec = [];
                $grand_total = 0;
                $thead = "
                            <tr>
                                <th>Sno</th>
                                <th>Admission no</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Term II receivable</th>
                            </tr>
                            ";

                for ($i = 0; $i < count($class_array); $i++) {
                    $class = $class_array[$i];
                    $tr = "";
                    $section_sql = "select section from overall where class = '{$class_array[$i]}'";
                    $section_result = $con->query($section_sql);
                    if ($section_result->num_rows > 0) {
                        $class_sec[$class] = call_user_func_array("array_merge", $section_result->fetch_all());
                        $class_sec[$class] = array_values(array_unique($class_sec[$class]));
                        foreach ($class_sec[$class] as $section) {
                            $term1_sql = "select admission,name,class,section,({$term_fees[$class][1]} - term2) as term2_receivable from overall where class = '{$class}' and section = '{$section}' and term2 < {$term_fees[$class][1]}";
                            $sno = 1;
                            $term1_res = $con->query($term1_sql);
                            $total_section_amt = 0;
                            if ($term1_res->num_rows > 0) {
                                while ($row = $term1_res->fetch_assoc()) {
                                    $tr .= "
                                     <tr>
                                        <td>$sno</td>
                                        <td>{$row["admission"]}</td>
                                        <td>{$row["name"]}</td>
                                        <td>{$row["class"]}</td>
                                        <td>{$row["section"]}</td>
                                        <td>{$row["term2_receivable"]}</td>
                                    </tr>";
                                    $total_section_amt += $row["term2_receivable"];
                                    $sno++;
                                }
                                $tr .= "<tr>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td>Total</td>
                                <td>$total_section_amt</td>
                                </tr>";
                                $grand_total += $total_section_amt;
                            } else {
                                $tr .= "
                                     <tr>
                                        <td>$sno</td>
                                        <td style='border-right:none'></td>
                                        <td style='border-left:none'></td>
                                        <td>{$class}</td>
                                        <td>{$section}</td>
                                        <td>0</td>
                                    </tr>";
                                $tr .= "<tr>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td>Total</td>
                                <td>$total_section_amt</td>
                                </tr>";
                            }
                        }
                    }
                    $tbody .= $tr;
                }
                $tbody .= "
                                <tr>
                                <td style='border-right:none;border-left:none;'></td>
                                <td style='border-right:none;border-left:none;'></td>
                                <td style='border-right:none;border-left:none;'></td>
                                <td style='border-right:none;border-left:none;'></td>
                                <td style='border-right:none;border-left:none;'></td>
                                <td style='border-right:none;border-left:none;'></td>
                                </tr>
                                <tr>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td>Grand Total</td>
                                <td>{$grand_total}</td>
                                </tr>
                                ";
                echo json_encode([202, $thead, $tbody]);
            } else if ($_POST["option"] == "Term III") {
                $class_sec = [];
                $grand_total = 0;
                $thead = "
                            <tr>
                                <th>Sno</th>
                                <th>Admission no</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Term III receivable</th>
                            </tr>
                            ";

                for ($i = 0; $i < count($class_array); $i++) {
                    $class = $class_array[$i];
                    $tr = "";
                    $section_sql = "select section from overall where class = '{$class_array[$i]}'";
                    $section_result = $con->query($section_sql);
                    if ($section_result->num_rows > 0) {
                        $class_sec[$class] = call_user_func_array("array_merge", $section_result->fetch_all());
                        $class_sec[$class] = array_values(array_unique($class_sec[$class]));
                        foreach ($class_sec[$class] as $section) {
                            $term1_sql = "select admission,name,class,section,({$term_fees[$class][2]} - term3) as term3_receivable from overall where class = '{$class}' and section = '{$section}' and term3 < {$term_fees[$class][2]}";
                            $sno = 1;
                            $term1_res = $con->query($term1_sql);
                            $total_section_amt = 0;
                            if ($term1_res->num_rows > 0) {
                                while ($row = $term1_res->fetch_assoc()) {
                                    $tr .= "
                                     <tr>
                                        <td>$sno</td>
                                        <td>{$row["admission"]}</td>
                                        <td>{$row["name"]}</td>
                                        <td>{$row["class"]}</td>
                                        <td>{$row["section"]}</td>
                                        <td>{$row["term3_receivable"]}</td>
                                    </tr>";
                                    $total_section_amt += $row["term3_receivable"];
                                    $sno++;
                                }
                                $tr .= "<tr>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td>Total</td>
                                <td>$total_section_amt</td>
                                </tr>";
                                $grand_total += $total_section_amt;
                            } else {
                                $tr .= "
                                     <tr>
                                        <td>$sno</td>
                                        <td style='border-right:none'></td>
                                        <td style='border-left:none'></td>
                                        <td>{$class}</td>
                                        <td>{$section}</td>
                                        <td>0</td>
                                    </tr>";
                                $tr .= "<tr>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td>Total</td>
                                <td>$total_section_amt</td>
                                </tr>";
                            }
                        }
                    }
                    $tbody .= $tr;
                }
                $tbody .= "
                                <tr>
                                <td style='border-right:none;border-left:none;'></td>
                                <td style='border-right:none;border-left:none;'></td>
                                <td style='border-right:none;border-left:none;'></td>
                                <td style='border-right:none;border-left:none;'></td>
                                <td style='border-right:none;border-left:none;'></td>
                                <td style='border-right:none;border-left:none;'></td>
                                </tr>
                                <tr>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td style='border:none'></td>
                                <td>Grand Total</td>
                                <td>{$grand_total}</td>
                                </tr>
                                ";
                echo json_encode([203, $thead, $tbody]);
            } else if ($_POST["option"] == "Class & Sec") {
                $class_sec = [];
                $grand_total = [0, 0, 0, 0];
                $thead = "
                            <tr>
                                <th>Sno</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Term I</th>
                                <th>Term II</th>
                                <th>Term III</th>
                                <th>Balance Receivable Fee</th>
                            </tr>
                            ";
                $sno = 1;
                for ($i = 0; $i < count($class_array); $i++) {
                    $class = $class_array[$i];
                    $tr = "";
                    $term_rec = [];
                    $section_sql = "select section from overall where class = '{$class_array[$i]}'";
                    $section_result = $con->query($section_sql);
                    if ($section_result->num_rows > 0) {
                        $class_sec[$class] = call_user_func_array("array_merge", $section_result->fetch_all());
                        $class_sec[$class] = array_values(array_unique($class_sec[$class]));
                        // print_r($class_sec[$class]);
                        foreach ($class_sec[$class] as $section) {
                            $term1_sql = "select sum({$term_fees[$class][0]} - term1) as term1_receivable from overall where class = '{$class}' and section = '{$section}' and term1 < {$term_fees[$class][0]}";
                            $term2_sql = "select sum({$term_fees[$class][1]} - term2) as term2_receivable from overall where class = '{$class}' and section = '{$section}' and term2 < {$term_fees[$class][1]}";
                            $term3_sql = "select sum({$term_fees[$class][2]} - term3) as term3_receivable from overall where class = '{$class}' and section = '{$section}' and term3 < {$term_fees[$class][2]}";
                            $term1_res = $con->query($term1_sql);
                            $term2_res = $con->query($term2_sql);
                            $term3_res = $con->query($term3_sql);
                            $total_receivable = 0;
                            if ($term1_res->num_rows > 0) {
                                $row = $term1_res->fetch_assoc();
                                $term_rec[0] = $row["term1_receivable"] ?? 0;
                                $grand_total[0] += $term_rec[0];
                            }
                            if ($term2_res->num_rows > 0) {
                                $row = $term2_res->fetch_assoc();
                                $term_rec[1] = $row["term2_receivable"] ?? 0;
                                $grand_total[1] += $term_rec[1];

                            }
                            if ($term3_res->num_rows > 0) {
                                $row = $term3_res->fetch_assoc();
                                $term_rec[2] = $row["term3_receivable"] ?? 0;
                                $grand_total[2] += $term_rec[2];

                            }
                            $total_receivable = array_sum($term_rec);
                            $grand_total[3] += $total_receivable;
                            $tr .= "
                                <tr>
                                    <td>$sno</td>
                                    <td>$class</td>
                                    <td>$section</td>
                                    <td>{$term_rec[0]}</td>
                                    <td>{$term_rec[1]}</td>
                                    <td>{$term_rec[2]}</td>
                                    <td>$total_receivable</td>
                                </tr>
                            
                            ";
                            $sno++;
                        }
                    }
                    $tbody .= $tr;
                }
                $tbody .= "
                                <tr>
                                <td style='border-right:none;border-left:none;'></td>
                                <td style='border-right:none;border-left:none;'></td>
                                <td style='border-right:none;border-left:none;'></td>
                                <td style='border-right:none;border-left:none;'></td>
                                <td style='border-right:none;border-left:none;'></td>
                                <td style='border-right:none;border-left:none;'></td>
                                <td style='border-right:none;border-left:none;'></td>
                                </tr>
                                <tr>
                                <td style='border-right:none;border-left:none;'></td>
                                <td style='border-right:none;border-left:none;'>Grand Total</td>
                                <td style='border-right:none;border-left:none;'></td>
                                <td>{$grand_total[0]}</td>
                                <td>{$grand_total[1]}</td>
                                <td>{$grand_total[2]}</td>
                                <td>{$grand_total[3]}</td>
                                </tr>
                                ";
                echo json_encode([204, $thead, $tbody]);

            }

        }
    } else if ($_POST["option"] == "Overall Total") {
        $grand_types = ["students","Term I receivable","Term II receivable","Term III receivable","Term I received","Term II received","Term III received","schlorship","pending","writeoff(Other Adjustment)","Total received","Balance Receivable"];
        $term_fees = [];
        $student_count = [];
        $term_total_class = [];
        $term_grand_total = [];
        foreach ($class_array as $class_) {
            $term_sql = "select $class_ from fees_table where types like 'term%'";
            $term_res = $con->query($term_sql);
            if ($term_res->num_rows > 0) {
                while ($row = $term_res->fetch_assoc()) {
                    $term_fees[$class_][] = $row[$class_];
                }
            }
        }
        foreach($class_array as $class_)
        {
            $stud_sql = "select count(admission) as count from overall where class = '$class_'";
            $stud_result = $con->query($stud_sql);
            if($stud_result->num_rows > 0)
            {
                while($row=$stud_result->fetch_assoc())
                {
                    $student_count[$class_] = $row["count"];
                    for($j=0; $j < 3;$j++)
                    {
                        $term_total_class[$class_][] = $term_fees[$class_][$j] * $student_count[$class_];
                    }
                }
            }
        }
        for ($a = 0; $a < 3; $a++) {
            $grand_tot = 0;
            foreach ($class_array as $class_) {
                $grand_tot += $term_total_class[$class_][$a];

            }
            $term_grand_total[] = $grand_tot;
        }
        $grand_tot_sql = "select count(admission) as students,sum(term1) as term1,sum(term2) as term2,sum(term3) as term3,sum(scholarship_amount) as scholarship_amount,sum(pending) as pending,sum(writeoff) as writeoff,sum(total_received) as total_received,sum(balance_receivable) as balance_receivable from overall where class != 'XI'";
        $result_grand_tot = $con->query($grand_tot_sql);
        $grand_tot_others = $result_grand_tot->fetch_all(MYSQLI_ASSOC)[0];
        // print_r($grand_tot_others);  
        $thead = "
                            <tr>
                                <th>Grand Total</th>
                                <th>Amount</th>
                            </tr>
                            ";
        $tbody= "
                <tr>
                        <td>{$grand_types[0]}</td>
                        <td>{$grand_tot_others['students']}</td>
                </tr
                <tr>
                        <td>{$grand_types[1]}</td>
                        <td>{$term_grand_total[0]}</td>
                </tr
                <tr>
                        <td>{$grand_types[2]}</td>
                        <td>{$term_grand_total[1]}</td>
                </tr
                <tr>
                        <td>{$grand_types[3]}</td>
                        <td>{$term_grand_total[2]}</td>
                </tr>
                <tr>
                        <td>{$grand_types[4]}</td>
                        <td>{$grand_tot_others['term1']}</td>
                </tr>
                <tr>
                        <td>{$grand_types[5]}</td>
                        <td>{$grand_tot_others['term2']}</td>
                </tr>
                <tr>
                        <td>{$grand_types[6]}</td>
                        <td>{$grand_tot_others['term3']}</td>
                </tr>
                <tr>
                        <td>{$grand_types[7]}</td>
                        <td>{$grand_tot_others['scholarship_amount']}</td>
                </tr>
                <tr>
                        <td>{$grand_types[8]}</td>
                        <td>{$grand_tot_others['pending']}</td>
                </tr>
                <tr>
                        <td>{$grand_types[9]}</td>
                        <td>{$grand_tot_others['writeoff']}</td>
                </tr>
                <tr>
                        <td>{$grand_types[10]}</td>
                        <td>{$grand_tot_others['total_received']}</td>
                </tr>
                <tr>
                        <td>{$grand_types[11]}</td>
                        <td>{$grand_tot_others['balance_receivable']}</td>
                </tr>
                ";
        echo json_encode([205, $thead, $tbody]);
    } else {
        echo "you selected option is valid one";
    }
}
?>
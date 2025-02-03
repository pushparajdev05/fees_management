<?php
include "./db_connection.php";
if(isset($_POST['class']))
{
    $class = $_POST['class'];
    $del_sql = "delete from overall where class = '$class'";
    if($con->query($del_sql))
    {
        echo 0;
    }
    else{
        echo 1;
    }
}
?>
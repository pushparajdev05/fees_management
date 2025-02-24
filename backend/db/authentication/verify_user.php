<?php
include './db_connection.php';
if (isset($_GET["token"])) {
    $token = $_GET["token"];
    $user = $_GET["user"];
    $sql="select * from temp_user where role = '$user' and hash='{$token}'";
    $res = $con->query($sql);
     if ($res->num_rows > 0)
     {           
        if($row = $res->fetch_assoc())
        {
            $staff_email = $row["uname"];
            $staff_pwd = $row["pwd"];
            $sql3 = "delete from users where role = '$user' and uname = '{$staff_email}'";
            $con->query($sql3);
            $sql1 = "insert into users(uname,pwd,role) values('{$staff_email}','{$staff_pwd}','$user')";
            if($con->query($sql1))
            {
                $sql2 = "delete from temp_user where role = '$user' and hash='{$token}'";
                $con->query($sql2);
                echo "$user successfully verified and approved";
            }
        }

        
     }
     else{
                echo "$user has already verified by administrator";
     }
 
} else {
    echo  "Email verification has failed";
}
?>
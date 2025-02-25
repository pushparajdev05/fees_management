<?php
session_start();
if(isset($_POST["user"]))
{
    unset($_SESSION["user"]);
    echo json_encode([100,$_POST["user"]]);
}
else
{
    echo json_encode([200,$_POST["user"]]);
}
?>
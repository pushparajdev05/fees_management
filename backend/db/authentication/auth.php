<?php
session_start();
include "./db_connection.php"; 

//phpmailer required classes and file 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (isset($_POST["action"])) {
    $user = $_POST["user"];
    $action = $_POST["action"];
    $uname = mysqli_real_escape_string($con, $_POST["uname"]);
    $user_pwd = mysqli_real_escape_string($con, $_POST["user_pwd"]);
    if ($action == "in") {
        $user_sql = "select uname from users where role = '$user' and uname = '$uname' and pwd = '$user_pwd'";
        $result_user = $con->query($user_sql);
        if ($result_user->num_rows > 0) {
            $_SESSION["user"] = $user;
            echo json_encode([100, "$user has identified and verified"]);
        } else {
           echo json_encode([200, "Email and password is not valid, try to give valid user and password"]);
        }
    } else {
        $token = bin2hex(random_bytes(16));
        $admin_sql = "select uname from users where role = 'admin' ";
        $result_admin = $con->query($admin_sql);
        if ($result_admin->num_rows > 0) {
            $sql1 = "insert into temp_user(uname,pwd,role,hash,expired_date) values('{$uname}','{$user_pwd}','{$user}','$token',NOW())";
            if ($con->query($sql1)) {
                $subject = "verfication request from $user";
                $message = "<h1>New $user tried to sign up to fees management site with identity of {$uname}<h1>
        <a href='http://localhost/fees_management/backend/db/authentication/verify_user.php?token={$token}&user=$user'>Click on to Verify Login</a>";
                    $mail = new PHPMailer(true);

                    try {
                        //Server settings
                        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
                        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
                        $mail->Username = 'pushparajdev05@gmail.com';                     //SMTP username
                        $mail->Password = 'hftq mixq elwb bpmi';                               //SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption - use 465 port if set to SMTPSecure = PHPMailer::ENCRYPTION_SMTPS
                        $mail->Port = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                        //Recipients
                        $mail->setFrom('pushparajdev05@gmail.com', 'Measi IT');
                        while ($row = $result_admin->fetch_assoc()) {
                            $admin_email = $row["uname"];
                            $mail->addAddress($admin_email, 'Admin');
                        }
                        //Add a recipient


                        //Content
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = $subject;
                        $mail->Body = $message;

                        $mail->send();
                        echo json_encode([100,"verification link has sent to administrators and kindly contact them"]);
                    } catch (Exception $e) {
                        $sql3 = "delete from temp_user where hash='{$token}'";
                        if ($con->query($sql3)) {
                            echo json_encode([200,"Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
                        }

                    }

                } 
            } else {
           echo json_encode([100, "There are no Administrators"]);
        }
        }
    }
?>
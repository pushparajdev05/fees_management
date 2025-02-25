<?php
session_start();
if(!isset($_SESSION["user"]))
{
    header("location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/navbars.css">
    <link rel="stylesheet" href="./css//index/nav_trans.css">
    <link rel="stylesheet" href="./css/index/transform.css">
    <link rel="stylesheet" href="./css/index/indexcss.css">
    <link rel="stylesheet" href="./css/index/underline.css">
    <link rel="stylesheet" href="./asset/sweetalert/sweetalert2.min.css">
    <title>Dashboard</title>
</head>
<body>
    <div id="header">
        <?php
        include "./component/header.php";
    ?>
    <section class="one">
        <div class="text">
            <div class="img">
                <img src="./images/poster.png" alt="no image" >
            </div>
            <button type="button" onClick="redirectTo()">
                Get Stated
                <div class="arrow-wrapper">
                <div class="arrow"></div>
                </div>
            </button>
        </div>
    <div class="lottie">
        <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module">
        </script><dotlottie-player src="https://lottie.host/dac8dc7c-2a71-47ee-a6b8-7c2bbbd5a5e3/iJfJBzV2tB.json" background="transparent" speed="1" style="width: 550px; height: 450px" direction="1" playMode="normal" loop autoplay></dotlottie-player>
    </div>
    </section>
    </div>
    <section class="second">
        <div class="sub">
            <div class="img">
                <div class="part">
                    <img src="./images/invoice.png" alt="no image"><br>
                    <div class="desc">
                        <span>INVOICE</span>
                        <p>Streamlines making bills</p>   
                    </div>
                    
                </div>
            </div>
            <div class="img2 img">
                <div class="part">
                    <img src="./images/maintains.png" alt="no image"><br>
                    <div class="desc">
                        <span>MAINTAIN</span>
                        <p>Can get and store data with secure</p>
                    </div>                
                </div>
            </div>
            <div class="img">
                <div class="part">
                    <img src="./images/generator.png" alt="no image"><br>
                    <div class="desc">
                        <span>GENERATE</span>
                        <p>Automate to generate the fees and defaulter details</p>   
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- <script src="./javascript/visited.js"></script> -->
     <script>
            window.user="<?= $_SESSION["user"]?>";

     </script>
    <script src="./javascript/jquery-3.7.1.js"></script>
    <script src="./asset/sweetalert/sweetalert2.all.min.js"></script>
         <script>
        function redirectTo()
        {
            location.href="./collection.php";
            console.log(location.href);
        }
    </script>
    <script src ="./javascript/logout.js"></script>

</body>
</html>
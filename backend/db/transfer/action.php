<?php
if (($_SERVER["REQUEST_METHOD"] === "POST") && isset($_POST["decide"])) {
    include ("./db_connection.php");
    $decide = trim( $_POST["decide"]);
    $admission = trim( $_POST["admission"]);
    $name = trim( $_POST["stu_name"]);
    $class = strtoupper( trim( $_POST["class"]));
    $section = strtoupper(trim( $_POST["section"]));
    $year = trim( $_POST["year"]);
    $pending = trim( $_POST["pending"]);
    if ($decide == "0") {
        $index = trim($_POST["arr_index"]);
        $sno = ((int) $index + 1);
        $sql = "INSERT INTO passedOut (admission,name,class,section,passed_year,pending) VALUES(?,?,?,?,?,?)";
        try {
            $stmt = $con->prepare($sql);
            $stmt->bind_param("isssii", $admission, $name, $class, $section, $year, $pending);
            if ($stmt->execute()) {
                $id = mysqli_insert_id($con);
                $array_val = array(
                    $sno,
                    $admission,
                    $name,
                    $class,
                    $section,
                    $year,
                    $pending,
                    "<div id='action'>
                            <button type='button' style='height:35px;width:70px;' class='download-btn pixel-corners delete' ad='$id'>
                        <div class='button-content' style=''>
                            <div class='svg-container'>
                            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 700 700' height='24' widht='24' style='fill:white;margin-bottom:3px;margin-left:8px;'><path d='M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z'/></svg>
                            </div>
                            <div class='text-container' style='height:37px'>
                            <div class='text'>Delete</div>
                            </div> 
                        </div>
                        </button>
                        <button typr='submit' style='height:35px;width:70px;' class='download-btn pixel-corners update' ad='$id'>
                            <div class='button-content' style=''>
                                <div class='svg-container'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' style='fill: rgb(255, 255, 255);margin-bottom: 10px;'><path d='M11 15h2V9h3l-4-5-4 5h3z'></path><path d='M20 18H4v-7H2v7c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2v-7h-2v7z'></path></svg>
                                </div>
                                <div class='text-container' style='height:37px'>
                                <div class='text'>Update</div>
                                </div> 
                            </div>
                        </button>
                    </div>",
                );
                $response_array = array(1, $array_val);
                echo json_encode($response_array);
            }
            else{
                echo json_encode([404, "there is an error that might be something related to insertion"]);
        }
        }
        catch(Exception $e)
        {
            // echo json_encode([404, $e->getMessage()]);
            echo $stmt->error;
        }
    } else {
        $sno = $_POST["sno"];
        $sql = "update passedOut set admission = ? ,name = ? ,class = ? ,section = ? ,passed_year = ? ,pending = ? where sno = ?";

        try{
        $stmt=$con->prepare($sql);
        if($stmt == false)
        {
            echo $con->error;
        }
        $stmt->bind_param("isssiii",$admission,$name,$class,$section,$year,$pending,$decide);
        if ($stmt->execute()) {
                $array_val = array(
                    $sno,
                    $admission,
                    $name,
                    $class,
                    $section,
                    $year,
                    $pending,
                    "<div id='action'>
                            <button type='button' style='height:35px;width:70px;' class='download-btn pixel-corners delete' ad='$decide'>
                        <div class='button-content' style=''>
                            <div class='svg-container'>
                            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 700 700' height='24' widht='24' style='fill:white;margin-bottom:3px;margin-left:8px;'><path d='M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z'/></svg>
                            </div>
                            <div class='text-container' style='height:37px'>
                            <div class='text'>Delete</div>
                            </div> 
                        </div>
                        </button>
                        <button typr='submit' style='height:35px;width:70px;' class='download-btn pixel-corners update' ad='$decide'>
                        <div class='button-content' style=''>
                            <div class='svg-container'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' style='fill: rgb(255, 255, 255);margin-bottom: 10px;'><path d='M11 15h2V9h3l-4-5-4 5h3z'></path><path d='M20 18H4v-7H2v7c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2v-7h-2v7z'></path></svg>
                            </div>
                            <div class='text-container' style='height:37px'>
                            <div class='text'>Update</div>
                            </div> 
                        </div>
                        </button>
                        </div>"
                );
            $response_array = array(1,$array_val);
            echo json_encode($response_array);
            
        }
        else{
             echo json_encode([404, "The data is not updated in overall table"]);

        }
    }
    catch(Exception $e)
    {
            echo $stmt->error;
    }

    }
    $con->close();
}
else
{
    echo "<tr>server is not responded</tr";
}
?>
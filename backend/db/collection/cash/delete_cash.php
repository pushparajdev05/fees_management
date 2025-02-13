<?php 
include ("../db_connection.php");
if(isset($_POST["admin"]))
{
	$ad=$_POST["admin"];
	$admission = $_POST["admission"];
	$revert_term = $_POST["revertTerm"];
	$revert_pending = $_POST["revert_pending"];
	// echo "the revert pending is $revert_pending";
	$sql = "delete from cash where sno='{$ad}'";
	$stored_term = [];
	$update_arr = [];
	$class = "";
	$term_sql = "select class,term1,term2,term3 from overall where admission = $admission";
	$res_term=$con->query($term_sql);
	if($res_term->num_rows > 0)
	{
		while($row=$res_term->fetch_assoc())
		{
			$class = $row["class"];
			$stored_term[0] = $row["term1"];
			$stored_term[1] = $row["term2"];
			$stored_term[2] = $row["term3"];
		}
	}
	for ($i = 0; $i < count($revert_term); $i++)
	{
		$num = $i;
		if($revert_term[$i]!="null")
		{
			$num += 1;
			$revert_term[$i] = abs($revert_term[$i] - $stored_term[$i]);
			$update_arr[] = "term$num = {$revert_term[$i]}";
		}
	}
	$message = [];
	$update_exe = true;
	if(!(empty($update_arr)))
	{
		$cat_update = implode(",",$update_arr);
		$overall_update = "update overall set $cat_update , pending = pending + $revert_pending , total_receivable = total_receivable + $revert_pending , total_received = term1 + term2 + term3 , balance_receivable = total_receivable - total_received  where admission = $admission";
		$update_exe=$con->query($overall_update);
	}
	else
	{
		if($revert_pending > 0)
		{
			$overall_update = "update overall set pending = pending + $revert_pending , total_receivable = total_receivable + $revert_pending , balance_receivable = total_receivable - total_received  where admission = $admission";
			$update_exe = $con->query($overall_update);
		}
	}
	if($update_exe)
	{
		if ($con->query($sql)) {
				echo json_encode(array("message"=>[101,"The transaction has deleted in Daily Collection"]));
		} else {
			echo json_encode(["error"=>404,"The transaction has not deleted in Daily Collection"]);
		}
	}
}
?>
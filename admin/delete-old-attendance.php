<?php
include($_SERVER['DOCUMENT_ROOT'].'/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'].'/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'].'/includes/a-left.php');

$date_err = '';

if(isset($_POST["before"]))
{
	$before = $_POST["before"];
	if ($before!='')
	{
		$conn->query("DELETE FROM s_atd WHERE year<'".date('Y', strtotime($before))."' OR (year='".date('Y', strtotime($before))."' AND month<".date('j', strtotime($before)).")");
		$conn->query("DELETE FROM t_atd WHERE year<'".date('Y', strtotime($before))."' OR (year='".date('Y', strtotime($before))."' AND month<".date('j', strtotime($before)).")");
	}
	else
		$date_err="<small><i><font color='red'>Please Select a Date.</font></i></small>";
}
$atd = $conn->query("SELECT DISTINCT month, year FROM s_atd ORDER BY year, month");

?>
			<div class="rightcolumn">
				<div class="card" style="padding: 25px 100px 100px;">
					<center>
						<h1>Delete Old Attendance</h1>
					</center>
					<form id="delb" method="post">
						Delete Attendance Before:
						<input type="date" name="before" id="before">
						<?php echo $date_err; ?><br><br>
					</form>
						<input type="submit" onClick="if(confirm('Warning..!\nAll attendance before '+document.getElementById('before').value+' will be deleted.')) document.getElementById('delb').submit();" value="Delete">
					
				</div>
			</div>

<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/bottom-section.php'); ?>
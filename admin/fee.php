<?php
include($_SERVER['DOCUMENT_ROOT'].'/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'].'/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'].'/includes/a-left.php');


$classes = $conn->query( "SELECT cls_id, cls_name, cls_section FROM classes" );
$cls_id='';

$sql = "SELECT std_id FROM student WHERE std_id='-1'";

$paystatus = -1;
if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
	
	$sql = "SELECT s.std_id, s.rollno, s.name, s.father_name, c.cls_name, c.cls_section, f.paydate FROM student s";
	$paystatus=$_POST["paystatus"];
	switch ($paystatus)
	{
		case 0:
			$sql.=" LEFT JOIN fee f ON";
			break;
		case 1:
			$sql.=" INNER JOIN fee f ON";
			break;
		default:
			$sql.=" LEFT JOIN fee f ON";
			break;
	}
	$sql.=" s.std_id=f.std_id AND f.year='".date("Y")."' AND f.month='".date("m")."' INNER JOIN classes c ON c.cls_id=s.cls_id";
	
	if ( isset( $_POST[ "cls" ] ) )
	{
		$cls_id=$_POST["cls"];
		if ($cls_id>=0)
			$sql.=" AND s.cls_id='".$cls_id."'";
		if ($_POST["paystatus"] == 0)
			$sql.=" WHERE f.paydate IS NULL";
		if ($cls_id>=0)
			$sql.=" ORDER BY s.rollno";
		else
			$sql.=" ORDER BY c.cls_name, s.rollno";
	}
}
$std = $conn->query($sql);

$collected = $conn->query("SELECT SUM(amount_paid) AS total FROM fee WHERE year='".date("Y")."' AND month='".date("m")."'")->fetch_assoc();

?>
			<div class="rightcolumn">
				<div class="card" style="padding: 25px 100px 100px;">
					<center>
						<h1>Fee Information</h1>
					</center>
					<font size="+1"><b>Total Fee Collected in this month:</b> <?php echo $collected["total"]; ?></font><br><br>
					<form method="post">
						Select Class: 
						<select onChange="this.form.submit()" name="cls">
							<option value="-1" selected>All Classes</option>
							<?php if ( $std->num_rows == 0 ) echo'<option selected></option>'; ?>
							<?php if($classes->num_rows > 0) while ($x = $classes->fetch_assoc())
	if ($cls_id == $x["cls_id"])
								echo '<option value="'.$x["cls_id"].'" selected>'.$x["cls_name"].' ('.$x["cls_section"].')</option>';
							else
								echo '<option value="'.$x["cls_id"].'">'.$x["cls_name"].' ('.$x["cls_section"].')</option>';
 ?>
						</select>
						Fee Status: 
						<select onChange="this.form.submit()" name="paystatus">
							<option value="-1" <?php if ($paystatus == -1) echo 'selected';?>>All</option>
							<option value="1" <?php if ($paystatus == 1) echo 'selected';?>>Paid</option>
							<option value="0" <?php if ($paystatus == 0) echo 'selected';?>>Unpaid</option>
						</select>
					</form><br>
					<input name="sname" type="text" id="myInput" onkeyup="searchColumn(2)" placeholder="Search by name..">
					<?php
						if ( $std->num_rows > 0 ){
							echo'
					<table  id="myTable" border="1">
						<tr>
							<th>Sr. No.</th>
							<th onclick="sortNumColumn(1)" style="cursor:pointer;">&#x21C5; Roll No.</th>
							<th onclick="sortColumn(2)" style="cursor:pointer;">&#x21C5; Name</th>
							<th onclick="sortColumn(3)" style="cursor:pointer;">&#x21C5; Father\'s Name</th>
							<th onclick="sortColumn(4)" style="cursor:pointer;">&#x21C5; Class</th>
							<th onclick="sortColumn(5)" style="cursor:pointer;">&#x21C5; Fee Status</th>
							<th>Detail</th>
						</tr>';
						 while ($x = $std->fetch_assoc())
						 {
							 echo '
						<tr>
							<td class="counterCell"></td>
							<td>'.$x["rollno"].'</td>
							<td>'.$x["name"].'</td>
							<td>'.$x["father_name"].'</td>
							<td>'.$x["cls_name"].' ('.$x["cls_section"].')</td>
							<td>';
							if (isset ($x["paydate"]))
								 echo '<font color="green">Paid</font>';
							else
								 echo '<font color="red">Unpaid</font>';
							echo '</td>
							<td><a href="student-fee?id='.$x["std_id"].'">View Fee Slip</a></td>
						</tr>';
						 }
					echo '</table>';
						}
						?>
				</div>
			</div>

<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/bottom-section.php'); ?>
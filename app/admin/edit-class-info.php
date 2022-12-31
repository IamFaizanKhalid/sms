<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-left.php');

$cls_id=$_GET["id"];


if( isset( $_POST["save"] ))
{
	$cls_name = $_POST["cls_name"];
	$incharge = $_POST["incharge"];
	$cls_section = $_POST["cls_section"];
	$cls_fee = $_POST["cls_fee"];
	$conn->query("UPDATE classes SET cls_name='".$cls_name."', incharge='".$incharge."', cls_section='".$cls_section."', cls_fee='".$cls_fee."' WHERE cls_id='".$cls_id."'");
	
	$temp = $conn->query("SELECT sub_id FROM subject");
	$t_sub_all = array();
	while ($x = $temp->fetch_assoc())
		$t_sub_all[] = $x["sub_id"];
	
	$temp = $conn->query("SELECT sub_id FROM teacherAssigned WHERE cls_id='".$cls_id."'");
	$t_sub = array();
	while ($x = $temp->fetch_assoc())
		$t_sub[] = $x["sub_id"];
	
	$addsql = "";
	$addsub = false;
	$delsql = "";
	
	foreach($t_sub_all as $sub_id)
	{
		if (isset($_POST[$sub_id]))
		{
			if (!in_array($sub_id, $t_sub))
			{
				if ($addsub)
					$addsql.=", ('".$cls_id."', '".$sub_id."')";
				else
					$addsql.=" ('".$cls_id."', '".$sub_id."')";
				$addsub = true;
			}
				
		}
		else
			$delsql .= ", '".$sub_id."'";
	}
	
	$conn->query("DELETE FROM result WHERE sub_dtl_id IN (SELECT id FROM teacherAssigned WHERE cls_id='".$cls_id."' AND sub_id IN ('-1'".$delsql."))");
	$conn->query("DELETE FROM teacherAssigned WHERE cls_id='".$cls_id."' AND sub_id IN ( '-1'".$delsql." )");
	if($addsub)
		$conn->query('INSERT INTO teacherAssigned (cls_id, sub_id) VALUES '.$addsql);

	header("Location: class-info?id=".$cls_id);
}

$classinfo = $conn->query("SELECT c.*, t.name FROM classes c, teacher t WHERE c.incharge = t.t_id AND c.cls_id='".$cls_id."' ORDER BY c.cls_name, c.cls_section")->fetch_assoc();


$subjects = $conn->query("SELECT s.sub_id, s.sub_name FROM teacherAssigned t INNER JOIN subject s ON t.sub_id=s.sub_id WHERE cls_id='".$cls_id."'");

$temp = $conn->query("SELECT * FROM subject");
$allsubjects = array();

while ($x = $temp->fetch_assoc())
	$allsubjects[$x["sub_id"]]=$x["sub_name"];



$temp = $conn->query("SELECT t_id, name FROM teacher");
$teachers = array();

while ($x = $temp->fetch_assoc())
	$teachers[$x["t_id"]]=$x["name"];



?>
			<div class="rightcolumn">
				<div class="card" style="padding: 25pclassinfo 100pclassinfo 100pclassinfo;">
					<center>
						<h1><?php echo $classinfo["cls_name"].' ('.$classinfo["cls_section"].')'; ?></h1>
					</center>
					<!--<input name="sname" type="teclassinfot" id="myInput" onkeyup="searchTable()" placeholder="Search by name..">-->
					<form id="std" method="post" action="students"><input type="hidden" name="cls" value="<?php echo $cls_id; ?>" ></form>
					<form method="post" id="clsform">
					<table border="1">
						<tr>
							<th>Class</th>
							<td><input name="cls_name" type="text" value="<?php echo $classinfo["cls_name"]; ?>"></td>
							<th>Incharge</th>
							<td><select name="incharge">
								<?php
							if (isset($classinfo["name"]))
							{
								foreach ($teachers as $t_id=>$tname)
									if ($t_id == $classinfo["u_id"])
								 		echo '<option value="'.$t_id.'" selected>'.$tname.'</option>';
							 		else
										echo '<option value="'.$t_id.'">'.$tname.'</option>';
							}
							else
							{
								 echo '<option value="-1" selected></option>';
								 foreach ($teachers as $t_id=>$tname)
								 	echo '<option value="'.$t_id.'">'.$tname.'</option>';
							}
								?>
						</tr>
						<tr>
							<th>Section</th>
							<td><input name="cls_section" type="text" value="<?php echo $classinfo["cls_section"]; ?>"></td>
							<th>Fee</th>
							<td><input name="cls_fee" type="number" value="<?php echo $classinfo["cls_fee"]; ?>"></td>
						</tr>
						<tr>
							<td colspan="4">
								<center><h2>Subjects</h2></center>
								
					<table id="myTable" border="1">
						<tr>
							<th><input type="checkbox" onClick="toggleCheckBox(this)"></th>
							<th>Subject</th>
						</tr>
						<?php
						 while ($x = $subjects->fetch_assoc())
						 {
							 echo '
						<tr>
							<td><input type="checkbox" name="'.$x["sub_id"].'" checked></td>
							<td>'.$x["sub_name"].'</td>
						</tr>';
							 unset($allsubjects[$x["sub_id"]]);
						 }
						
						 foreach ($allsubjects as $sub_id=>$sub_name)
							 echo '
						<tr>
							<td><input type="checkbox" name="'.$sub_id.'"></td>
							<td>'.$sub_name.'</td>
						</tr>';
						?>
							</table></td>
						</tr>
						</table><br>
					<input name="save" type="hidden" value="Save">
						</form>
			<input onClick="if(confirm('Warning!\nIf unselected, results related to a subject will be deleted.')) document.getElementById('clsform').submit();" type="submit" value="Save"><br>
<br><br>
				</div>
			</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/bottom-section.php'); ?>
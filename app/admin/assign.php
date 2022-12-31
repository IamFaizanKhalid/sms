<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-left.php');


$classes = $conn->query( "SELECT cls_id, cls_name, cls_section FROM classes" );

$cls_id=-1;
if ( isset( $_POST[ "cls" ] ) )
	$cls_id=$_POST["cls"];


if (isset($_POST["assign"]))
{
	$temp = $conn->query("SELECT id FROM teacherAssigned WHERE cls_id='".$cls_id."'");
	while ($x = $temp->fetch_assoc())
	{
		$t_id = $_POST[$x["id"]];
		if ($t_id >= 0)
			$conn->query("UPDATE teacherAssigned SET t_id = '".$t_id."' WHERE id='".$x["id"]."'");
	}
}


$temp = $conn->query("SELECT t_id, name FROM teacher");
$teachers = array();

while ($x = $temp->fetch_assoc())
	$teachers[$x["t_id"]]=$x["name"];


$subjects = $conn->query("SELECT a.id, t.t_id, t.name, s.sub_id, s.sub_name FROM teacherAssigned a INNER JOIN subject s ON a.sub_id=s.sub_id LEFT JOIN teacher t ON a.t_id=t.t_id WHERE cls_id='".$cls_id."'");

?>
			<div class="rightcolumn">
				<div class="card" style="padding: 25px 100px 100px;">
					<center>
						<h1>Assign Teachers</h1>
					</center>
					<form method="post">
						Select Class: 
						<select onChange="this.form.submit()" name="cls">
							<?php if ( $cls_id < 0 ) echo'<option value="-1" selected></option>'; ?>
							<?php if($classes->num_rows > 0) while ($x = $classes->fetch_assoc())
	if ($cls_id == $x["cls_id"])
								echo '<option value="'.$x["cls_id"].'" selected>'.$x["cls_name"].' ('.$x["cls_section"].')</option>';
							else
								echo '<option value="'.$x["cls_id"].'">'.$x["cls_name"].' ('.$x["cls_section"].')</option>';
 ?>
						</select>
					</form><br>
					<input name="sname" type="text" id="myInput" onkeyup="searchColumn(2)" placeholder="Search by name..">
					<?php
						if ( $subjects->num_rows > 0 ){
							echo'
					<form method="post"><table id="myTable" border="1">
						<tr>
							<th><input type="checkbox" onClick="toggleCheckBox(this)"></th>
							<th>Sr. No.</th>
							<th onclick="sortColumn(2)" style="cursor:pointer;">&#x21C5; Subject</th>
							<th onclick="sortColumn(3)" style="cursor:pointer;">&#x21C5; Teacher</th>
						</tr>';
						 while ($x = $subjects->fetch_assoc())
						 {
							 echo '
						<tr>
							<td><input type="checkbox"></td>
							<td class="counterCell"></td>
							<td>'.$x["sub_name"].'</td>
							<td><select name="'.$x["id"].'">';
							if (isset($x["t_id"]))
							{
								foreach ($teachers as $t_id=>$tname)
									if ($t_id == $x["t_id"])
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
							echo '</select></td>
						</tr>';
						 }
					echo '</table><br>
					<input type="hidden" name="cls" value="'.$cls_id.'">
					<input name="assign" type="submit" value="Save">
					</form>';
						}
						?>
					
				</div>
			</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/bottom-section.php'); ?>
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-left.php');

$cls_name = $cls_section = $cls_fee = '';
$incharge = -1;
$err_name = $err_incharge = $err_section = $err_fee = '';

if ( isset( $_POST[ "save" ] ) ) {
		$cls_name = $_POST[ "cls_name" ];
		$incharge = $_POST[ "incharge" ];
		$cls_section = $_POST[ "cls_section" ];
		$cls_fee = $_POST[ "cls_fee" ];
	$ok = true;
	if ( $cls_name == '' ) {
		$err_name = '<br><small><font color="red">This field is required.</font></small>';
		$ok = false;
	}
	if ( $incharge == -1 ) {
		$err_incharge = '<br><small><font color="red">This field is required.</font></small>';
		$ok = false;
	}
	if ( $cls_section == '' ) {
		$err_section = '<br><small><font color="red">This field is required.</font></small>';
		$ok = false;
	}
	if ( $cls_fee == '' ) {
		$err_fee = '<br><small><font color="red">This field is required.</font></small>';
		$ok = false;
	}
	if ( $ok ) {
		$conn->query( "INSERT INTO classes(cls_name, incharge, cls_section, cls_fee) VALUES('" . $cls_name . "', '" . $incharge . "', '" . $cls_section . "', '" . $cls_fee . "')" );
		$cls_id = $conn->insert_id;

		$temp = $conn->query( "SELECT sub_id FROM subject" );

		$addsql = "INSERT INTO teacherAssigned (cls_id, sub_id) VALUES ";
		$addsub = false;

		while ( $subject = $temp->fetch_assoc() ) {
			$sub_id = $subject[ "sub_id" ];
			if ( isset( $_POST[ $sub_id ] ) ) {
				if ( $addsub )
					$addsql .= ", ('" . $cls_id . "', '" . $sub_id . "')";
				else {
					$addsql .= " ('" . $cls_id . "', '" . $sub_id . "')";
					$addsub = true;
				}
			}
		}
		if ( $addsub )
			$conn->query( $addsql );

		header( "Location: classes" );
	}
}


?>
			<div class="rightcolumn">
				<div class="card" style="padding: 25pclassinfo 100pclassinfo 100pclassinfo;">
					<center>
						<h1>Add Class</h1>
					</center>
					<form method="post">
					<table border="1">
						<tr>
							<th>Class</th>
							<td><input name="cls_name" type="text" value="<?php echo $cls_name; ?>">
								<?php echo $err_name; ?></td>
							<th>Incharge</th>
							<td><select name="incharge" value="<?php echo $incharge; ?>">
								<?php
								$teachers = $conn->query("SELECT t_id, name FROM teacher");
								if ($incharge<0)
								 echo '<option value="-1" selected></option>';
								 while ($x = $teachers->fetch_assoc())
									 if ($incharge == $x["u_id"])
								 	echo '<option value="'.$x["t_id"].'" selected>'.$x["name"].'</option>';
								else
								 	echo '<option value="'.$x["t_id"].'">'.$x["name"].'</option>';
								?>
								</select>
								<?php echo $err_incharge; ?>
							</td>
						</tr>
						<tr>
							<th>Section</th>
							<td><input name="cls_section" type="text" value="<?php echo $cls_section; ?>">
								<?php echo $err_section; ?></td>
							<th>Fee</th>
							<td><input name="cls_fee" type="number" value="<?php echo $cls_fee; ?>">
								<?php echo $err_fee; ?></td>
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
						 $subjects = $conn->query("SELECT * FROM subject");
						 while ($x = $subjects->fetch_assoc())
						 {
							 echo '
						<tr>
							<td><input type="checkbox" name="'.$x["sub_id"].'"></td>
							<td>'.$x["sub_name"].'</td>
						</tr>';
						 }
						?>
							</table></td>
						</tr>
						</table><br>
					<input name="save" type="submit" value="Add">
						</form>
<br><br>
				</div>
			</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/bottom-section.php'); ?>
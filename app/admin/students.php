<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-left.php');


$classes = $conn->query( "SELECT cls_id, cls_name, cls_section FROM classes" );
$cls_id = '';

$sql = "SELECT std_id FROM student WHERE std_id='-1' LIMIT 0";


if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
	$sql = "SELECT s.std_id, s.rollno, s.name, s.father_name, c.cls_name, c.cls_section FROM student s, classes c WHERE c.cls_id=s.cls_id";


	if ( isset( $_POST[ "cls" ] ) ) {
		$cls_id = $_POST[ "cls" ];
		if ( $cls_id >= 0 )
			$sql .= " AND s.cls_id='" . $cls_id . "' ORDER BY s.rollno";
		else
			$sql .= " ORDER BY c.cls_name, s.rollno";
	}


	if ( isset( $_POST[ "dels" ] ) ) {
		$temp = $conn->query( "SELECT std_id FROM student" );
		$ex = '';
		while ( $x = $temp->fetch_assoc() )
			if ( isset( $_POST[ $x[ "std_id" ] ] ) )
				$ex .= ", '" . $x[ "std_id" ] . "'";
		if ( $ex != '' ) {
			$pics = $conn->query( "SELECT name, pic FROM student WHERE std_id IN( '-1'" . $ex . " )" );
			$conn->query( "DELETE FROM student WHERE std_id IN( '-1'" . $ex . " )" );
			$conn->query( "DELETE FROM s_atd WHERE std_id IN( '-1'" . $ex . " )" );
			$conn->query( "DELETE FROM fee WHERE std_id IN( '-1'" . $ex . " )" );
			$conn->query( "DELETE FROM result WHERE std_id IN( '-1'" . $ex . " )" );
			$conn->query( "DELETE FROM resultTotal WHERE std_id IN( '-1'" . $ex . " )" );
			$conn->query( "DELETE FROM login WHERE u_type=1 AND u_id IN( '-1'".$ex." )" );

			echo '<small><i><font color="red">';
			while ( $x = $pics->fetch_assoc() )
				if ( $x[ "pic" ] != NULL )
					if ( !unlink( $_SERVER[ 'DOCUMENT_ROOT' ] . '/images/student/' . $x[ "pic" ] ) )
						echo 'Image of ' . $x[ "name" ] . 'Cannot be deleted.<br>';
			echo '</font></i></small>';
		}
	}
}
$std = $conn->query( $sql );



?>
<script>//dropdown = document.getElementsByClassName("dropdown-btn")[0].click();</script>
<div class="rightcolumn">
	<div class="card" style="padding: 25px 100px 100px;">
		<center>
			<h1>Students</h1>
		</center>
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
		</form><br>
		<input name="sname" type="text" id="myInput" onkeyup="searchColumn(4)" placeholder="Search by name..">
		<a href="add-student"><input type="submit" value="Add Student"></a><br><br>

		<?php
		if ( $std->num_rows > 0 )
			echo '<form method="post" id="delform">
					<table id="myTable" border="1">
						<tr>
							<th><input type="checkbox" onClick="toggleCheckBox(this)"></th>
							<th>Sr. No.</th>
							<th onclick="sortColumn(2)" style="cursor:pointer;">&#x21C5; Class</th>
							<th onclick="sortNumColumn(3)" style="cursor:pointer;">&#x21C5; Roll No.</th>
							<th onclick="sortColumn(4)" style="cursor:pointer;">&#x21C5; Name</th>
							<th onclick="sortColumn(5)" style="cursor:pointer;">&#x21C5; Father\'s Name</th>
							<th>Details</th>
							<th>Attendance</th>
							<th>Results</th>
							<th>Reset Password</th>
						</tr>';
		while ( $x = $std->fetch_assoc() )
			echo '
						<tr>
							<td><input type="checkbox" name="' . $x[ "std_id" ] . '"></td>
							<td class="counterCell"></td>
							<td>' . $x[ "cls_name" ] . ' (' . $x[ "cls_section" ] . ')</td>
							<td>' . $x[ "rollno" ] . '</td>
							<td>' . $x[ "name" ] . '</td>
							<td>' . $x[ "father_name" ] . '</td>
							<td><a href="student-info?id=' . $x[ "std_id" ] . '">Details</a></td>
							<td><a href="student-attendance?id=' . $x[ "std_id" ] . '">Attendance</a></td>
							<td><a href="student-result?id=' . $x[ "std_id" ] . '">Results</a></td>
							<td><a href="reset-password?id=' . $x[ "std_id" ] . '&type=1">Reset Password</a></td>
						</tr>';
		if ( $std->num_rows > 0 )
			echo '
					</table>
					<input type="hidden" name="cls" value="' . $cls_id . '"><input type="hidden" name="dels">
					</form>
						<input onClick="if(confirm(\'Warning!\nThis will delete all data of selected students.\')) document.getElementById(\'delform\').submit();" type="submit" value="Delete"><br>';
		?>
	</div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/bottom-section.php'); ?>
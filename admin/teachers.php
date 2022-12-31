<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/globalVars.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/a-top-section.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/a-left.php' );


if ( isset( $_POST[ "delt" ] ) ) {
	$temp = $conn->query( "SELECT t_id FROM teacher" );
	$ex = '';
	while ( $x = $temp->fetch_assoc() )
		if ( isset( $_POST[ $x[ "t_id" ] ] ) )
			$ex .= ", '" . $x[ "t_id" ] . "'";
	if ( $ex != '' ) {
		$pics = $conn->query( "SELECT name, pic FROM user WHERE u_id IN( '-1'" . $ex . " )" );
		$conn->query( "DELETE FROM teacher WHERE t_id IN( '-1'" . $ex . " )" );
		$conn->query( "DELETE FROM t_atd WHERE t_id IN( '-1'" . $ex . " )" );
		$conn->query( "UPDATE teacherAssigned SET t_id=NULL WHERE t_id IN( '-1'" . $ex . " )" );
		$conn->query( "DELETE FROM login WHERE u_type=2 AND u_id IN( '-1'".$ex." )" );
		
		echo '<small><i><font color="red">';
		while ( $x = $pics->fetch_assoc() )
			if ( !unlink( $_SERVER[ 'DOCUMENT_ROOT' ] . '/images/teacher/' . $x[ "pic" ] ) )
				echo 'Image of ' . $x[ "name" ] . 'Cannot be deleted.<br>';
		echo '</font></i></small>';
	}
}

$std = $conn->query( "SELECT t.t_id, t.name, t.cnic, t.phone1, c.incharge FROM teacher t LEFT JOIN (SELECT DISTINCT incharge FROM classes) c ON t.t_id=c.incharge" );



?>
<div class="rightcolumn">
	<div class="card" style="padding: 25px 100px 100px;">
		<center>
			<h1>Teachers</h1>
		</center>
		<input name="sname" type="text" id="myInput" onkeyup="searchColumn(2)" placeholder="Search by name.."><br><br>
		<a href="add-teacher"><input type="submit" value="Add Teacher"></a><br><br>
		<form method="post" id="delform">
			<?php
			if ( $std->num_rows > 0 ) {
				echo '
					<table  id="myTable" border="1">
						<tr>
							<th><input type="checkbox" onClick="toggleCheckBox(this)"></th>
							<th>Sr. No.</th>
							<th onclick="sortColumn(2)" style="cursor:pointer;">&#x21C5; Name</th>
							<th>Details</th>
							<th>Attendance</th>
							<th>Results</th>
							<th>Reset Password</th>
						</tr>';
				while ( $x = $std->fetch_assoc() ) {
					echo '
						<tr>
							<td><input type="checkbox" name="' . $x[ "t_id" ] . '"';
					if ( $x[ "incharge" ] != NULL )echo ' disabled';
					echo '></td>
							<td class="counterCell"></td>
							<td>' . $x[ "name" ] . '</td>
							<td><a href="teacher-info?id=' . $x[ "t_id" ] . '">Details</a></td>
							<td><a href="teacher-attendance?id=' . $x[ "t_id" ] . '">Attendance</a></td>
							<td><a href="teacher-result?id=' . $x[ "t_id" ] . '">Results</a></td>
							<td><a href="reset-password?id=' . $x[ "t_id" ] . '&type=2">Reset Password</a></td>
						</tr>';
				}
				if ( $std->num_rows > 0 )
					echo '</table>';
			}
			?>
			<input type="hidden" name="delt">
		</form>
			<input onClick="if(confirm('Warning!\nThis will delete all data of selected teachers.')) document.getElementById('delform').submit();" type="submit" value="Delete"><br>
			<small><i>* A teacher profile cannot be deleted if he/she is incharge of any class.</i></small>
	</div>
</div>


<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/bottom-section.php'); ?>
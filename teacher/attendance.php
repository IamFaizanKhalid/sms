<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/globalVars.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/t-top-section.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/t-left.php' );



$incharge = $conn->query( "SELECT cls_id, cls_name, cls_section FROM classes WHERE incharge='" . $u_id . "'" );



$cls_id = 0;
if ( isset( $_POST[ "cls" ] ) )
	$cls_id = $_POST[ "cls" ];
else {
	$cls_id = $conn->query( "SELECT cls_id FROM classes WHERE incharge='" . $u_id . "' ORDER BY cls_id DESC LIMIT 1" )->fetch_assoc();
	$cls_id = $cls_id[ "cls_id" ];
}



if ( isset( $_POST[ "mark-atd" ] ) ) {

	$temp = $conn->query( "SELECT std_id, rollno, name FROM student WHERE cls_id='" . $cls_id . "' " );

	while ( $x = $temp->fetch_assoc() ) {
		if ( isset( $_POST[ $x[ "std_id" ] ] ) )
			$conn->query( "UPDATE s_atd SET `" . idate( 'd' ) . "`='P', present=present+1 WHERE year='" . date( 'Y' ) . "' AND month='" . date( 'm' ) . "' AND std_id='" . $x[ "std_id" ] . "'" );
		else
			$conn->query( "UPDATE s_atd SET `" . idate( 'd' ) . "`='A', absent=absent+1 WHERE year='" . date( 'Y' ) . "' AND month='" . date( 'm' ) . "' AND std_id='" . $x[ "std_id" ] . "' AND `" . idate( 'd' ) . "` IS NULL" );
	}
}
$std = $conn->query( "SELECT std_id, rollno, name FROM student WHERE cls_id='" . $cls_id . "' " );



?>
<div class="rightcolumn">
	<div class="card" style="padding: 25px 100px 100px;">
		<center>
			<h1>Attendance</h1>
		</center>
		<form method="post">
			Select Class:
			<select onChange="this.form.submit()" name="cls">
				<?php if($incharge->num_rows > 0) while ($x = $incharge->fetch_assoc())
	if ($cls_id == $x["cls_id"])
								echo '<option value="'.$x["cls_id"].'" selected>'.$x["cls_name"].' ('.$x["cls_section"].')</option>';
							else
								echo '<option value="'.$x["cls_id"].'">'.$x["cls_name"].' ('.$x["cls_section"].')</option>';
 ?>
			</select><br><br>
		</form><br><br><br>
		<form method="post">
			<table border="1">
				<tr>
					<th>Sr. No.</th>
					<th>Roll No.</th>
					<th>Name</th>
					<?php for ($i=1;$i<=date('d');$i++) echo '<th>'.$i.'</th>'; ?>
					<th>Total</th>
				</tr>
				<?php
				$sr = 0;
				$marked = true;
				while ( $x = $std->fetch_assoc() ) {
					echo '<tr>
							<td>' . ++$sr . '</td>
							<td>' . $x[ "rollno" ] . '</td>
							<td>' . $x[ "name" ] . '</td>';

					$atd = $conn->query( "SELECT * FROM s_atd WHERE year=" . date( 'Y' ) . " AND month=" . date( 'm' ) . " AND std_id='" . $x[ "std_id" ] . "' LIMIT 1" );
					if ( $atd->num_rows < 1 ) {
						$conn->query( "INSERT INTO s_atd(std_id,year,month) VALUES(" . $x[ "std_id" ] . ", " . date( 'Y' ) . ", " . date( 'm' ) . ")" );
						$atd = $conn->query( "SELECT * FROM s_atd WHERE year=" . date( 'Y' ) . " AND month=" . date( 'm' ) . " AND std_id='" . $x[ "std_id" ] . "' LIMIT 1" );
					}
					$atd = $atd->fetch_assoc();

					$d = idate( 'd' );

					for ( $i = 1; $i < $d; $i++ )
						if ( $atd[ $i ] == 'P' )echo '<td><font color="green">' . $atd[ $i ] . '</font></td>';
						else if ( $atd[ $i ] == 'A' )echo '<td><font color="red">' . $atd[ $i ] . '</font></td>';
					else if ( $atd[ $i ] == 'L' )echo '<td><font color="blue">' . $atd[ $i ] . '</font></td>';
					else echo '<td>-</td>';

					if ( $atd[ $d ] == 'P' )echo '<td><font color="green">' . $atd[ $d ] . '</font></td>';
					else if ( $atd[ $d ] == 'A' )echo '<td><font color="red">' . $atd[ $d ] . '</font></td>';
					else if ( $atd[ $d ] == 'L' )echo '<td><font color="blue">' . $atd[ $d ] . '</font></td>';
					else {
						echo '<td><input type="checkbox" name="' . $x[ "std_id" ] . '"></td>';
						$marked = false;
					}

					echo '<td><font color="green">Present</font>: ' . $atd[ "present" ] . ' / <font color="red">Absent</font>: ' . $atd[ "absent" ] . ' / <font color="blue">Leave</font>: ' . $atd[ "leaves" ] . '</td></tr>';
				}
				?>
			</table>
			<input type="hidden" name="cls" value="<?php echo $cls_id ?>">
			<?php if(!$marked) echo '<input type="submit" name="mark-atd" value="Mark Attendance" >'; ?>
		</form>
	</div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/bottom-section.php'); ?>
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/t-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/t-left.php');

$passing_marks = $conn->query( "SELECT value FROM controlVars WHERE var='passing_marks' LIMIT 1" )->fetch_assoc();
$passing_marks = $passing_marks[ "value" ];

$term = $conn->query( "SELECT value FROM controlVars WHERE var='term_start_month' LIMIT 1" )->fetch_assoc();
$term = $term[ "value" ];

if ( date( 'm' ) < $term )
	$term = ( date( 'Y' ) - 1 ) . '-' . $term . '-1';
else
	$term = date( 'Y' ) . '-' . $term . '-1';

$exams = $conn->query( "SELECT ex_id, ex_name FROM exams WHERE added BETWEEN '" . $term . "' AND '" . date( 'Y-m-d', strtotime( $term . '+1 year -1 day' ) ) . "'" );
$ex_id = 0;
if ( isset( $_POST[ "ex" ] ) )
	$ex_id = $_POST[ "ex" ];
else {
	$ex_id = $conn->query( "SELECT MAX(ex_id) AS ex FROM exams WHERE added>'" . strtotime( $term ) . "'" )->fetch_assoc();
	$ex_id = $ex_id[ "ex" ];
}

$assigned = $conn->query( "SELECT t.id, c.cls_id, c.cls_name, c.cls_section, s.sub_name FROM teacherAssigned t, classes c, subject s WHERE t.t_id='" . $u_id . "' AND c.cls_id=t.cls_id AND s.sub_id=t.sub_id ORDER BY c.cls_name ASC" );
$ass_id = 0;
if ( isset( $_POST[ "cls" ] ) )
	$ass_id = $_POST[ "cls" ];
else {
	$ass_id = $conn->query( "SELECT id FROM teacherAssigned WHERE t_id='" . $u_id . "' AND t_id='" . $u_id . "'" )->fetch_assoc();
	$ass_id = $ass_id[ "id" ];
}


$std = $conn->query( "SELECT s.std_id, s.rollno, s.name FROM student s, teacherAssigned t WHERE s.cls_id=t.cls_id AND t.id='" . $ass_id . "'" );

$total = '';
if ( !isset( $_POST[ "total" ] ) ) {
	$total = $conn->query( "SELECT total FROM result WHERE sub_dtl_id='" . $ass_id . "' AND ex_id='" . $ex_id . "' LIMIT 1" )->fetch_assoc();
	$total = $total[ "total" ];
}


if ( isset( $_POST[ "update-marks" ] ) )
{
	if (  $_POST[ "total" ] )
		$total = $_POST[ "total" ];
	else
		$total = 0;
	
	$tmp = $conn->query( "SELECT s.std_id FROM student s, teacherAssigned t WHERE s.cls_id=t.cls_id AND t.id='" . $ass_id . "'" );

	while ( $x = $tmp->fetch_assoc() ) {
		$conn->query( "UPDATE result SET total='" . $total . "', obtained='" . $_POST[ $x[ "std_id" ] ] . "' WHERE std_id='" . $x[ "std_id" ] . "' AND sub_dtl_id='" . $ass_id . "' AND ex_id='" . $ex_id . "'" );
	}

}
?>
<div class="rightcolumn">
	<div class="card" style="padding: 25px 100px 100px;">
		<center>
			<h1>Result</h1>
		</center>
		<form method="post">
			Select Class:
			<select onChange="this.form.submit()" name="cls">
				<?php if($assigned->num_rows > 0) while ($x = $assigned->fetch_assoc())
	if ($ass_id == $x["id"])
								echo '<option value="'.$x["id"].'" selected>'.$x["cls_name"].' ('.$x["cls_section"].') - '.$x["sub_name"].'</option>';
							else
								echo '<option value="'.$x["id"].'">'.$x["cls_name"].' ('.$x["cls_section"].') - '.$x["sub_name"].'</option>';
 ?>
			</select><br> Select Exam:
			<select onChange="this.form.submit()" name="ex">
				<?php if($exams->num_rows > 0) while ($x = $exams->fetch_assoc())
	if ($x["ex_id"] == $ex_id)
						echo '<option value="'.$x["ex_id"].'" selected>'.$x["ex_name"].'</option>';
							else
						echo '<option value="'.$x["ex_id"].'">'.$x["ex_name"].'</option>';
 ?>
			</select><br><br>
		</form><br><br><br>
		<form method="post">
			Total Marks: <input type="number" name="total" value="<?php echo $total;?>">
			<table border="1">
				<tr>
					<th>Sr. No.</th>
					<th>Roll No.</th>
					<th>Name</th>
					<th>Marks</th>
				</tr>
				<?php
				$sr = 0;
				while ( $x = $std->fetch_assoc() ) {
					$marks = $conn->query( "SELECT obtained FROM result WHERE std_id='" . $x[ "std_id" ] . "' AND sub_dtl_id='" . $ass_id . "' AND ex_id='" . $ex_id . "' LIMIT 1" );
					if ( $marks->num_rows < 1 ) {
						$conn->query( "INSERT INTO result(std_id, sub_dtl_id, ex_id, total) VALUES('" . $x[ "std_id" ] . "', '" . $ass_id . "', '" . $ex_id . "', '" . $total . "')" );
						$marks = $conn->query( "SELECT obtained FROM result WHERE std_id='" . $x[ "std_id" ] . "' AND sub_dtl_id='" . $ass_id . "' AND ex_id='" . $ex_id . "' LIMIT 1" );
					}
					$marks = $marks->fetch_assoc();
					echo '<tr>
							<td>' . ++$sr . '</td>
							<td>' . $x[ "rollno" ] . '</td>
							<td>' . $x[ "name" ] . '</td>
							<td><input name="o_' . $x[ "std_id" ] . '" type="hidden" value="' . $marks[ "obtained" ] . '" >
							<input name="' . $x[ "std_id" ] . '" type="number" value="' . $marks[ "obtained" ] . '" ></td>
							</tr>';

				}
				?>
			</table>
			<input type="hidden" name="cls" value="<?php echo $ass_id ?>">
			<input type="hidden" name="ex" value="<?php echo $ex_id ?>">
			<input type="hidden" name="prevtotal" value="<?php echo $total ?>">
			<input type="submit" name="update-marks" value="Update Marks">
		</form>
	</div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/bottom-section.php'); ?>
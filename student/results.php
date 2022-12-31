<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/globalVars.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/s-top-section.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/std-left.php' );

$passing_marks = $conn->query( "SELECT value FROM controlVars WHERE var='passing_marks' LIMIT 1" )->fetch_assoc();
$passing_marks = $passing_marks[ "value" ];

$term = $conn->query( "SELECT value FROM controlVars WHERE var='term_start_month' LIMIT 1" )->fetch_assoc();
$term = $term[ "value" ];

if ( date( 'm' ) < $term )
	$term = ( date( 'Y' ) - 1 ) . '-' . $term . '-1';
else
	$term = date( 'Y' ) . '-' . $term . '-1';
$exams = $conn->query( "SELECT ex_id, ex_name, ex_year FROM exams WHERE added BETWEEN '" . $term . "' AND '" . date( 'Y-m-d', strtotime( $term . '+1 year -1 day' ) ) . "'" );

$ex_id = 0;
if ( isset( $_POST[ "ex" ] ) )
	$ex_id = $_POST[ "ex" ];
else {
	$ex_id = $conn->query( "SELECT MAX(ex_id) AS ex FROM exams WHERE added>'" . strtotime( $term ) . "'" )->fetch_assoc();
	$ex_id = $ex_id[ "ex" ];
}
$result = $conn->query( "SELECT r.total, r.obtained, s.sub_name FROM result r, teacherAssigned x, subject s WHERE r.std_id='" . $u_id . "' AND r.ex_id='" . $ex_id . "' AND r.sub_dtl_id=x.id AND x.sub_id=s.sub_id" );

$resultTotal = $conn->query( "SELECT SUM(total) total, SUM(obtained) obtained FROM result WHERE std_id='" . $u_id . "' AND ex_id='" . $ex_id . "'" )->fetch_assoc();
if ( $resultTotal[ "total" ] )
	$percentage = round( 100 * $resultTotal[ "obtained" ] / $resultTotal[ "total" ], 2 );
else
	$percentage = 0;
if ( $percentage >= 80 )$grade = 'A+';
else if ( $percentage >= 70 )$grade = 'A';
else if ( $percentage >= 60 )$grade = 'B';
else if ( $percentage >= 50 )$grade = 'C';
else if ( $percentage >= 40 )$grade = 'D';
else if ( $percentage >= 33 )$grade = 'E';
else $grade = 'F';

$stdinfo = $conn->query( "SELECT s.name, s.rollno, c.cls_name, c.cls_section FROM student s INNER JOIN classes c ON s.cls_id=c.cls_id WHERE s.std_id=$u_id LIMIT 1" )->fetch_assoc();

?>
<div id="feeslip" class="rightcolumn">
	<div class="card" style="padding: 25px 100px 100px;">
		<center>
			<h1>Result</h1>
		</center>
		<form method="post">
			Select Exam:
			<select onChange="this.form.submit()" name="ex">
				<?php if($exams->num_rows > 0) while ($x = $exams->fetch_assoc())
	if ($x["ex_id"] == $ex_id)
						echo '<option value="'.$x["ex_id"].'" selected>'.$x["ex_name"].'</option>';
							else
						echo '<option value="'.$x["ex_id"].'">'.$x["ex_name"].'</option>';
 ?>
			</select>
		</form><br><br><br>
		<div id="slip">
			<table>
				<tr>
					<th>Name</th>
					<td>
						<?php echo $stdinfo["name"]; ?>
					</td>
					<th>Exam</th>
					<td>
						<?php  mysqli_data_seek($exams,0); while ($x = $exams->fetch_assoc()) if ($x["ex_id"] == $ex_id) { echo $x["ex_name"].' ('.$x["ex_year"].')'; break; } ?>
					</td>
				</tr>
				<tr>
					<th>Class</th>
					<td>
						<?php echo $stdinfo["cls_name"].' ('.$stdinfo["cls_section"].')'; ?>
					</td>
					<th>Roll No.</th>
					<td>
						<?php echo $stdinfo["rollno"]; ?>
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<table style="border: 3px solid black">
							<tr>
								<th class="thead" rowspan="2" style="border-bottom: 3px solid black">Subject</th>
								<th class="thead" colspan="2">Marks</th>
							</tr>
							<tr>
								<th class="thead" style="border-bottom: 3px solid black">Total</th>
								<th class="thead" style="border-bottom: 3px solid black">Obtained</th>
							</tr>
							<?php if($result->num_rows > 0) while ($x = $result->fetch_assoc())
	if (100*$x["obtained"]/$x["total"] < $passing_marks)
						echo '<tr>
							<td class="tsub">'.$x["sub_name"].'</td>
							<td class="tresult">'.$x["total"].'</td>
							<td class="tresult"><font color="red">'.$x["obtained"].'</font></td>
						</tr>';
						else
						echo '<tr>
							<td class="tsub">'.$x["sub_name"].'</td>
							<td class="tresult">'.$x["total"].'</td>
							<td class="tresult">'.$x["obtained"].'</td>
						</tr>';
 ?>
							<tr>
								<th class="tsub" style="border-top: 3px solid black; border-bottom: 3px solid black;">Grand Total</th>
								<td class="tresult" style="border-top: 3px solid black; border-bottom: 3px solid black;">
									<?php echo $resultTotal["total"]; ?>
								</td>
								<td class="tresult" style="border-top: 3px solid black; border-bottom: 3px solid black;">
									<?php if ($resultTotal["total"]) if (100*$resultTotal["obtained"]/$resultTotal["total"] < $passing_marks) echo '<font color="red">'.$resultTotal["obtained"].'</font>'; else echo $resultTotal["obtained"];?>
								</td>
							</tr>
							<tr>
								<th class="tsub">Percentage</th>
								<td class="tresult" colspan="2">
									<?php if($percentage < $passing_marks) echo '<font color="red">'.$percentage.' %</font>'; else echo $percentage.' %'; ?>
								</td>
							</tr>
							<tr>
								<th class="tsub">Grade</th>
								<td class="tresult" colspan="2">
									<?php if($grade == 'F' ) echo '<font color="red">F</font>'; else echo $grade; ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		<br><input onClick="printSlip()" type="submit" value="Print Result">
	</div>
</div>

<script type="text/javascript">
	function printSlip() {
		myWindow = window.open( '', '', 'width=1280,height=720' );
		myWindow.document.write( '<link rel="stylesheet" href="<?php echo $style_sheet; ?>"/><div ><div style="float: left; width: 150px;"><img src="<?php echo $school_logo; ?>" widtd="100" height="100" title="<?php echo $school_name ?>" alt="School Logo"/></div><div style="white-space: nowrap;"><h1><br><?php echo $school_name ?></h1></div></div><br><div class="card"><center><h1>Result</h1></center>' + document.getElementById( 'slip' ).innerHTML + '<p style="float: right;"><br>Signature:<br>_________________</p></div>' );

		myWindow.document.close(); //missing code

		myWindow.focus();
		myWindow.print();
		myWindow.close();
	}
</script>

<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/bottom-section.php'); ?>
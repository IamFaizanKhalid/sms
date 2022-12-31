<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/globalVars.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/a-top-section.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/a-left.php' );

$passing_marks = $conn->query( "SELECT value FROM controlVars WHERE var='passing_marks' LIMIT 1" )->fetch_assoc();
$passing_marks = $passing_marks[ "value" ];
$term = $conn->query( "SELECT value FROM controlVars WHERE var='term_start_month' LIMIT 1" )->fetch_assoc();
$term = $term[ "value" ];

if ( date( 'm' ) < $term )
	$term = ( date( 'Y' ) - 1 ) . '-' . $term . '-1';
else
	$term = date( 'Y' ) . '-' . $term . '-1';


$classes = $conn->query( "SELECT * FROM classes" );
if ( isset( $_POST[ "cls" ] ) )
	$cls_id = $_POST[ "cls" ];
else
	$cls_id = -1;


$exams = $conn->query( "SELECT ex_id, ex_name FROM exams WHERE added BETWEEN '" . $term . "' AND '" . date( 'Y-m-d', strtotime( $term . '+1 year -1 day' ) ) . "'" );
$ex_id = 0;
if ( isset( $_POST[ "ex" ] ) )
	$ex_id = $_POST[ "ex" ];
else {
	$ex_id = $conn->query( "SELECT MAX(ex_id) AS ex FROM exams WHERE added>'" . strtotime( $term ) . "'" )->fetch_assoc();
	$ex_id = $ex_id[ "ex" ];
}


$subjects = $conn->query( "SELECT t.id, s.sub_name FROM teacherAssigned t, subject s WHERE s.sub_id=t.sub_id AND t.cls_id='" . $cls_id . "'" );
if ( isset( $_POST[ "sub" ] ) )
	$sub_id = $_POST[ "sub" ];
else
	$sub_id = -1;

$std = $conn->query( "SELECT s.name, s.rollno, r.obtained FROM result r, student s, teacherAssigned a WHERE r.std_id=s.std_id AND r.sub_dtl_id='" . $sub_id . "' AND a.id=r.sub_dtl_id AND a.cls_id=s.cls_id AND r.ex_id='" . $ex_id . "' ORDER BY s.rollno" );

$info = $conn->query( "SELECT r.total, t.name FROM result r, teacherAssigned a, teacher t WHERE a.id='" . $sub_id . "' AND a.t_id=t.t_id AND r.sub_dtl_id='" . $sub_id . "' AND r.ex_id='" . $ex_id . "'" )->fetch_assoc();


$total = $info[ "total" ];
$teacher = $info[ "name" ];

?>
<div class="rightcolumn">
	<div class="card" style="padding: 25px 100px 100px;">
		<center>
			<h1>Result</h1>
		</center>
		<form method="post">
			Select Class:
			<select onChange="this.form.submit()" name="cls">
				<?php
				if ( $cls_id < 0 )echo '<option value="-1" selected></option>';
				if ( $classes->num_rows > 0 )
					while ( $x = $classes->fetch_assoc() )
						if ( $cls_id == $x[ "cls_id" ] )
							echo '<option value="' . $x[ "cls_id" ] . '" selected>' . $x[ "cls_name" ] . ' (' . $x[ "cls_section" ] . ')</option>';
				else
					echo '<option value="' . $x[ "cls_id" ] . '">' . $x[ "cls_name" ] . ' (' . $x[ "cls_section" ] . ')</option>';
				?>
			</select><br> Select Exam:
			<select onChange="this.form.submit()" name="ex">
				<?php if($exams->num_rows > 0) while ($x = $exams->fetch_assoc())
	if ($x["ex_id"] == $ex_id)
						echo '<option value="'.$x["ex_id"].'" selected>'.$x["ex_name"].'</option>';
							else
						echo '<option value="'.$x["ex_id"].'">'.$x["ex_name"].'</option>';
 ?>
			</select><br>
		</form>
		<form method="post">
			<input type="hidden" name="cls" value="<?php echo $cls_id ?>">
			<input type="hidden" name="ex" value="<?php echo $ex_id ?>"> Select Subject:
			<select onChange="this.form.submit()" name="sub">
				<?php if ($sub_id<0) echo '<option value="-1" selected></option>';
							if($subjects->num_rows > 0) while ($x = $subjects->fetch_assoc())
	if ($x["id"] == $sub_id)
						echo '<option value="'.$x["id"].'" selected>'.$x["sub_name"].'</option>';
							else
						echo '<option value="'.$x["id"].'">'.$x["sub_name"].'</option>';
 ?>
			</select><br><br>
		</form>
		<form method="post">
			<table border="1">
				<tr>
					<th>Teacher</th>
					<td>
						<?php echo $teacher;?>
					</td>
					<th>Total Marks</th>
					<td>
						<?php echo $total;?>
					</td>
				</tr>
			</table><br>
			<table id="myTable" border="1">
				<tr>
					<th>Sr. No.</th>
					<th onclick="sortColumn(1)" style="cursor:pointer;">&#x21C5; Roll Number</th>
					<th onclick="sortColumn(2)" style="cursor:pointer;">&#x21C5; Name</th>
					<th onclick="sortNumColumn(3)" style="cursor:pointer;">&#x21C5; Marks</th>
				</tr>
				<?php
				while ( $x = $std->fetch_assoc() ) {
					echo '<tr>
							<td class="counterCell"></td>
							<td>' . $x[ "rollno" ] . '</td>
							<td>' . $x[ "name" ] . '</td>
							<td>' . $x[ "obtained" ] . '</td>
							</tr>';

				}
				?>
			</table>
		</form>
	</div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/bottom-section.php'); ?>
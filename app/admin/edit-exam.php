<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-left.php');

if ( isset( $_POST[ "save" ] ) ) {
	$ex_id = $_POST[ "ex_id" ];
	$ex_name = $_POST[ "ex_name" ];
	$ex_month = $_POST[ "ex_month" ];
	$ex_year = $_POST[ "ex_year" ];
	$ex_fee = $_POST[ "ex_fee" ];
	if ( $ex_name == '' || $ex_month < 0 || $ex_year == '' || $ex_fee == '' )
		$err_name = '<br><small><font color="red">All fields are required.</font></small>';
	else {
		$conn->query( "UPDATE exams SET ex_name='" . $ex_name . "',  ex_month='" . $ex_month . "',  ex_year='" . $ex_year . "',  ex_fee='" . $ex_fee . "' WHERE ex_id='$ex_id'" );
		header( "Location: exams" );
	}
} else if ( isset( $_GET[ "id" ] ) ) {

	$ex_id = $_GET[ "id" ];
	$exams = $conn->query( "SELECT * FROM exams WHERE ex_id='$ex_id' LIMIT 1" )->fetch_assoc();
	$ex_id = $exams[ "ex_id" ];
	$ex_name = $exams[ "ex_name" ];
	$ex_month = $exams[ "ex_month" ];
	$ex_year = $exams[ "ex_year" ];
	$ex_fee = $exams[ "ex_fee" ];
	$err_name = '';
} else {
	$ex_id = -1;
	$ex_name = '';
	$ex_month = -1;
	$ex_year = '';
	$ex_fee = '';
	$err_name = '';

}

?>
			<div class="rightcolumn">
				<div class="card" style="padding: 25pclassinfo 100pclassinfo 100pclassinfo;">
					<center>
						<h1>Edit Exam Details</h1>
					</center>
					<form method="post">
					<fieldset id="newsub">
							<legend><h3>Exam Detail</h3></legend>
						<b>Name</b>
							<input name="ex_name" type="text" value="<?php echo $ex_name; ?>"><br><br>
						<b>Month</b>
						<select name="ex_month">
							<?php
							if ($ex_month < 0) echo '<option value="-1" selected></option>';
							for ($i=1;$i<=12;$i++){
								echo '<option value="'.$i.'" ';
								if ($ex_month==$i)
									echo 'selected';
								echo '>'.date('F', mktime(0, 0, 0, $i, 10)).'</option>';
							}
							?>
						</select><br><br>
						<b>Year</b>
							<input name="ex_year" type="number" value="<?php echo $ex_year; ?>"><br><br>
						<b>Fee</b>
							<input name="ex_fee" type="number" value="<?php echo $ex_fee; ?>">
							<input name="ex_id" type="hidden" value="<?php echo $ex_id; ?>">
					</fieldset><br>
								<?php echo $err_name; ?>
					<input name="subs" id="subs" type="hidden" value="1">
					<input name="save" type="submit" value="Save">
						</form>
				</div>
			</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/bottom-section.php'); ?>
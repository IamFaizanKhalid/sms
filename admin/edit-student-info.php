<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/globalVars.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/a-top-section.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/a-left.php' );

$name = $rollno = $roll_err = $father_name = $dob = $bform = $father_cnic = $board_reg_no = $religion = $nationality = $blood_group = $phone1 = $phone2 = $address = $pic_err = $std_id = $opic = '';
$name_err = $dob_err = $gender_err = $cls_err = false;
$gender = 'X';
$married = -1;
$pic = 'default.jpg';

$classes = $conn->query( "SELECT cls_id, cls_name, cls_section FROM classes" );
$cls_id = '';

if ( isset( $_POST[ "save" ] ) ) {
	$std_id = $_POST[ "std_id" ];
	$name = $_POST[ "name" ];
	$father_name = $_POST[ "fname" ];
	$dob = $_POST[ "dob" ];
	$rollno = $_POST[ "rollno" ];
	$bform = $_POST[ "bform" ];
	$father_cnic = $_POST[ "father_cnic" ];
	$board_reg_no = $_POST[ "board_reg_no" ];
	$gender = $_POST[ "gender" ];
	$religion = $_POST[ "rel" ];
	$nationality = $_POST[ "ntn" ];
	$blood_group = $_POST[ "bld" ];
	$cls_id = $_POST[ "cls" ];
	$phone1 = $_POST[ "num1" ];
	$phone2 = $_POST[ "num2" ];
	$address = $_POST[ "addr" ];
	$ok = true;
	if ( $name == '' ) {
		$name_err = true;
		$ok = false;
	}
	if ( $dob == '' ) {
		$dob_err = true;
		$ok = false;
	}
	if ( $rollno == '' ) {
		$roll_err = '<small><i><font color="red">* This feild is required.</font></i></small>';
		$ok = false;
	}
	if ( $gender == 'X' ) {
		$gender_err = true;
		$ok = false;
	}
	if ( $cls_id == '' ) {
		$cls_err = true;
		$ok = false;
	}
	$imageFileType = strtolower( pathinfo( $_FILES[ "pic" ][ "name" ], PATHINFO_EXTENSION ) );
	if ( !empty( $_FILES[ "pic" ][ "name" ] ) ) {
		if ( !getimagesize( $_FILES[ "pic" ][ "tmp_name" ] ) ) {
			$pic_err = '* Please upload a valid picture.';
			$ok = false;
		} else if ( $_FILES[ "pic" ][ "size" ] > 5000000 ) {
			$pic_err = '* Image size should be <5MB';
			$ok = false;
		} else if ( $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" &&
			$imageFileType != "gif" ) {
			$pic_err = '* Image should be in .jpg, .jpeg, .png or .gif format.';
			$ok = false;
		}
	}
	if ($conn->query("SELECT name FROM student WHERE cls_id=$cls_id AND rollno='$rollno' AND std_id<>$std_id")->num_rows){
		$roll_err = '<small><i><font color="red">* A student with roll number '.$rollno.' already exists.</font></i></small>';
		$ok = false;
	}
	if ( $ok ) {
		$pic_sql = '';
		if ( !empty( $_FILES[ "pic" ][ "name" ] ) ) {
			if ( $opic != '' )
				if ( !unlink( $_SERVER[ 'DOCUMENT_ROOT' ] . '/images/student/' . $opic ) )
					echo "Sorry, there was an error deleting old image.";
			$pic = $_FILES[ "pic" ][ "name" ];
			$pic = substr( $pic, 0, 16 );
			while ( file_exists( $_SERVER[ 'DOCUMENT_ROOT' ] . '/images/student/' . $pic . '.' . $imageFileType ) ){
				$pic = substr( $pic, 0, 14 );
				$pic = ( rand() % 100 ) . $pic;
			}
			$pic = $pic . '.' . $imageFileType;


			if ( !move_uploaded_file( $_FILES[ "pic" ][ "tmp_name" ], $_SERVER[ 'DOCUMENT_ROOT' ] . '/images/student/' . $pic ) )
				echo "Sorry, there was an error uploading your image.";
			$pic_sql = ", pic='" . $pic . "'";
		}

		$conn->query( "UPDATE student SET name='$name', gender='$gender', dob='$dob', religion='$religion', nationality='$nationality', blood_group='$blood_group', cnic='$bform', address='$address', phone1='$phone1', phone2='$phone2'$pic_sql, rollno='$rollno', father_name='$father_name', father_cnic='$father_cnic', board_reg_no='$board_reg_no', cls_id='$cls_id' WHERE std_id=$std_id" );

		header( "Location: student-info?id=" . $std_id );
		//exit();

	}
} else if ( isset( $_GET[ "id" ] ) && $_GET[ "id" ] != '' ) {
	$std_id = $_GET[ "id" ];
	$data = $conn->query( "SELECT * FROM student s, classes c WHERE s.std_id='" . $std_id . "' AND c.cls_id=s.cls_id LIMIT 1" )->fetch_assoc();
	$name = $data[ "name" ];
	$dob = $data[ "dob" ];
	$cnic = $data[ "cnic" ];
	$gender = $data[ "gender" ];
	$religion = $data[ "religion" ];
	$nationality = $data[ "nationality" ];
	$blood_group = $data[ "blood_group" ];
	$phone1 = $data[ "phone1" ];
	$phone2 = $data[ "phone2" ];
	$address = $data[ "address" ];
	$opic = $pic = $data[ "pic" ];


	$father_name = $data[ "father_name" ];
	$rollno = $data[ "rollno" ];
	$bform = $data[ "cnic" ];
	$father_cnic = $data[ "father_cnic" ];
	$board_reg_no = $data[ "board_reg_no" ];
	$cls_id = $data[ "cls_id" ];
}

?>

<div class="rightcolumn">
	<div class="card" style="padding: 25px 100px 100px;">
		<center>
			<h1>Edit Details</h1>
		</center>
		<form method="post" enctype="multipart/form-data">
			<table>
				<tr>
					<td class="thead">Name
						<font color="red">*</font>
					</td>
					<td class="tdata">
						<input type="text" name="name" value="<?php echo $name;?>">
						<?php if ($name_err) echo '<small><i><font color="red">* This feild is required.</font></i></small>'; ?>
					</td>
					<td colspan="2" rowspan="4">
						<img class="imag" name="imag" height="200" width="175" src="/images/student/<?php if ($pic) echo $pic; else if ($gender=='M') echo 'male.jpg'; else if ($gender=='F') echo 'female.jpg'; else echo 'default.jpg'; ?>" id="blah" onClick="document.getElementById('imgsel').click()"/><br>
						<input id="imgsel" style="visibility:hidden" type="file" name="pic" value="<?php echo $pic; ?>" onChange="PreviewImage(this, 'blah')">
						<br><small><i><font color="red"><?php echo $pic_err; ?></font></i></small>
						<input type="hidden" name="opic" value="<?php echo $opic; ?>">
					</td>
				</tr>
				<tr>
					<td class="thead">Gender
						<font color="red">*</font>
					</td>
					<td class="tdata">
						<select name="gender">
							<option value="X" <?php if ($gender=='X' ) echo 'selected';?>>-</option>
							<option value="M" <?php if ($gender=='M' ) echo 'selected';?>>Male</option>
							<option value="F" <?php if ($gender=='F' ) echo 'selected';?>>Female</option>
							<option value="O" <?php if ($gender=='O' ) echo 'selected';?>>Other</option>
						</select>
						<?php if ($gender_err) echo '<small><i><font color="red">* This feild is required.</font></i></small>'; ?>
					</td>
				</tr>
				<tr>
					<td class="thead">Date of Birth
						<font color="red">*</font>
					</td>
					<td class="tdata">
						<input type="date" name="dob" value="<?php echo $dob; ?>">
						<?php if ($dob_err) echo '<small><i><font color="red">* This feild is required.</font></i></small>'; ?>
					</td>
				</tr>
				<tr>
					<td class="thead">B.Form #</td>
					<td class="tdata">
						<input type="number" name="bform" value="<?php echo $bform; ?>">
					</td>
				</tr>
				<tr>
					<td class="thead">Board Registration Number</td>
					<td class="tdata">
						<input type="number" name="board_reg_no" value="<?php echo $board_reg_no; ?>">
					</td>
					<td class="thead">Class
						<font color="red">*</font>
					</td>
					<td class="tdata">
						<select name="cls">
							<?php if ( $cls_id == '' ) echo'<option value="" selected></option>'; ?>
							<?php if($classes->num_rows > 0) while ($x = $classes->fetch_assoc())
	if ($cls_id == $x["cls_id"])
								echo '<option value="'.$x["cls_id"].'" selected>'.$x["cls_name"].' ('.$x["cls_section"].')</option>';
							else
								echo '<option value="'.$x["cls_id"].'">'.$x["cls_name"].' ('.$x["cls_section"].')</option>';
 ?>
						</select>
						<?php if ($cls_err) echo '<small><i><font color="red">* This feild is required.</font></i></small>'; ?>
					</td>
				</tr>
				<tr>
					<td class="thead">Father's Name</td>
					<td class="tdata">
						<input type="text" name="fname" value="<?php echo $father_name; ?>">
					</td>
					<td class="thead">Roll Number
						<font color="red">*</font>
					</td>
					<td class="tdata">
						<input type="text" name="rollno" value="<?php echo $rollno; ?>">
						<?php echo $roll_err; ?>
					</td>
				</tr>
				<tr>
					<td class="thead">Father's CNIC</td>
					<td class="tdata">
						<input type="number" name="father_cnic" value="<?php echo $father_cnic; ?>">
					</td>
					<td class="thead">Nationality</td>
					<td class="tdata">
						<input type="text" name="ntn" value="<?php echo $nationality; ?>">
					</td>
				</tr>
				<tr>
					<td class="thead">Phone Number</td>
					<td class="tdata">
						<input type="number" name="num1" value="<?php echo $phone1; ?>">
					</td>
					<td class="thead">Religion</td>
					<td class="tdata">
						<input type="text" name="rel" value="<?php echo $religion; ?>">
					</td>
				</tr>
				<tr>
					<td class="thead">Alternative</td>
					<td class="tdata">
						<input type="number" name="num2" value="<?php echo $phone2; ?>">
					</td>
					<td class="thead">Blood Group</td>
					<td class="tdata">
						<input type="text" name="bld" value="<?php echo $blood_group; ?>">
					</td>
				</tr>
				<tr>
					<td class="thead">Address</td>
					<td class="tdata" colspan="4">
						<input type="text" name="addr" value="<?php echo $address; ?>">
					</td>
				</tr>
			</table>
			<input type="hidden" name="std_id" value="<?php echo $std_id; ?>">
			<input type="submit" name="save" value="Save">
		</form>
		<small><i>* Required feilds.</i></small>
	</div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/bottom-section.php'); ?>
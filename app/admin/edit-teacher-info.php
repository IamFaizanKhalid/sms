<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-left.php');

$name = $uname = $uname_err = $dob = $cnic = $degree = $religion = $nationality = $blood_group = $phone1 = $phone2 = $address = $sal = $pic_err = $t_id = $opic = '';
$name_err = $dob_err = $gender_err = $cnic_err = $sal_err = $married_err = false;
$gender = 'X';
$married = -1;
$pic = 'default.jpg';

if ( isset( $_POST[ "save" ] ) ) {
	$t_id = $_POST[ "t_id" ];
	$name = $_POST[ "name" ];
	$uname = $_POST[ "uname" ];
	$dob = $_POST[ "dob" ];
	$cnic = $_POST[ "cnic" ];
	$degree = $_POST[ "qual" ];
	$gender = $_POST[ "gender" ];
	$religion = $_POST[ "rel" ];
	$nationality = $_POST[ "ntn" ];
	$blood_group = $_POST[ "bld" ];
	$married = $_POST[ "married" ];
	$sal = $_POST[ "sal" ];
	$phone1 = $_POST[ "num1" ];
	$phone2 = $_POST[ "num2" ];
	$address = $_POST[ "addr" ];
	$opic = $_POST[ "opic" ];
	$ok = true;
	if ( $name == '' ) {
		$name_err = true;
		$ok = false;
	}
	if ( $dob == '' ) {
		$dob_err = true;
		$ok = false;
	}
	if ( $gender == 'X' ) {
		$gender_err = true;
		$ok = false;
	}
	if ( $cnic == '' ) {
		$cnic_err = true;
		$ok = false;
	}
	if ( $sal == '' ) {
		$sal_err = true;
		$ok = false;
	}
	if ( $married < 0 ) {
		$married_err = true;
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
	if ( $ok ) {
		$pic_sql = '';
		if ( !empty( $_FILES[ "pic" ][ "name" ] ) ) {
			if ( $opic != '' )
				if ( !unlink( $_SERVER[ 'DOCUMENT_ROOT' ] . '/images/teacher/' . $opic ) )
					echo "Sorry, there was an error deleting old image.";
			$pic = $_FILES[ "pic" ][ "name" ];
			$pic = substr( $pic, 0, 16 );
			while ( file_exists( $_SERVER[ 'DOCUMENT_ROOT' ] . '/images/student/' . $pic . '.' . $imageFileType ) ){
				$pic = substr( $pic, 0, 14 );
				$pic = ( rand() % 100 ) . $pic;
			}
			$pic = $pic . '.' . $imageFileType;


			if ( !move_uploaded_file( $_FILES[ "pic" ][ "tmp_name" ], $_SERVER[ 'DOCUMENT_ROOT' ] . '/images/teacher/' . $pic ) )
				echo "Sorry, there was an error uploading your image.";
			$pic_sql = ", pic='" . $pic . "'";
		}

		
		$conn->query( "UPDATE teacher SET name='$name', gender='$gender', dob='$dob', religion='$religion', nationality='$nationality', blood_group='$blood_group', cnic='$cnic', address='$address', phone1='$phone1', phone2='$phone2'$pic_sql, sal='$sal', degree='$degree', married='$married' WHERE t_id=$t_id" );
		header( "Location: teacher-info?id=" . $t_id );

	}
} else if ( isset( $_GET[ "id" ] ) && $_GET[ "id" ] != '' ) {
	$t_id = $_GET[ "id" ];
	$data = $conn->query( "SELECT * FROM teacher t WHERE t_id='" . $t_id . "' LIMIT 1" )->fetch_assoc();
	$name = $data[ "name" ];
	$dob = $data[ "dob" ];
	$cnic = $data[ "cnic" ];
	$degree = $data[ "degree" ];
	$gender = $data[ "gender" ];
	$religion = $data[ "religion" ];
	$nationality = $data[ "nationality" ];
	$blood_group = $data[ "blood_group" ];
	$married = $data[ "married" ];
	$sal = $data[ "sal" ];
	$phone1 = $data[ "phone1" ];
	$phone2 = $data[ "phone2" ];
	$address = $data[ "address" ];
	$opic = $pic = $data[ "pic" ];
}

?>

<div class="rightcolumn">
	<div class="card" style="padding: 25px 100px 100px;">
		<center>
			<h1>Add Teacher</h1>
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
						<img class="imag" name="imag" height="200" width="175" src="/images/teacher/<?php if ($pic) echo $pic; else if ($gender=='M') echo 'male.jpg'; else if ($gender=='F') echo 'female.jpg'; else echo 'default.jpg'; ?>" id="blah" onClick="document.getElementById('imgsel').click()"/><br>
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
					<td class="thead">CNIC #
						<font color="red">*</font>
					</td>
					<td class="tdata">
						<input type="number" name="cnic" value="<?php echo $cnic; ?>">
						<?php if ($cnic_err) echo '<small><i><font color="red">* This feild is required.</font></i></small>'; ?>
					</td>
				</tr>
				<tr>
					<td class="thead">Qualification</td>
					<td class="tdata">
						<input type="text" name="qual" value="<?php echo $degree; ?>">
					</td>
					<td class="thead">Salary
						<font color="red">*</font>
					</td>
					<td class="tdata">
						<input type="number" name="sal" value="<?php echo $sal; ?>">
						<?php if ($sal_err) echo '<small><i><font color="red">* This feild is required.</font></i></small>'; ?>
					</td>
				</tr>
				<tr>
					<td class="thead">Religion</td>
					<td class="tdata">
						<input type="text" name="rel" value="<?php echo $religion; ?>">
					</td>
					<td class="thead">Blood Group</td>
					<td class="tdata">
						<input type="text" name="bld" value="<?php echo $blood_group; ?>">
					</td>
				</tr>
				<tr>
					<td class="thead">Nationality</td>
					<td class="tdata">
						<input type="text" name="ntn" value="<?php echo $nationality; ?>">
					</td>
					<td class="thead">Marital Status
						<font color="red">*</font>
					</td>
					<td class="tdata">
						<select name="married">
							<option value="-1" <?php if ($married<0) echo 'selected'; ?>>-</option>
							<option value="1" <?php if ($married>0) echo 'selected'; ?>>Married</option>
							<option value="0" <?php if (!$married) echo 'selected'; ?>>Single</option>
						</select>
						<?php if ($married_err) echo '<small><i><font color="red">* This feild is required.</font></i></small>'; ?>
					</td>
				</tr>
				<tr>
					<td class="thead">Phone Number</td>
					<td class="tdata">
						<input type="number" name="num1" value="<?php echo $phone1; ?>">
					</td>
				</tr>
				<tr>
					<td class="thead">Alternative</td>
					<td class="tdata">
						<input type="number" name="num2" value="<?php echo $phone2; ?>">
					</td>
				</tr>
				<tr>
					<td class="thead">Address</td>
					<td class="tdata" colspan="4">
						<input type="text" name="addr" value="<?php echo $address; ?>">
					</td>
				</tr>
			</table>
			<input type="hidden" name="t_id" value="<?php echo $t_id; ?>">
			<input type="submit" name="save" value="Save">
		</form>
		<small><i>* Required feilds.</i></small>
	</div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/bottom-section.php'); ?>
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/s-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/std-left.php');

$data = $conn->query( "SELECT * FROM student s INNER JOIN classes c ON s.cls_id=c.cls_id WHERE s.std_id=$u_id LIMIT 1" )->fetch_assoc();
?>

<div class="rightcolumn">
	<div class="card" style="padding: 25px 100px 100px;">
		<center>
			<h1>Student Information</h1>
		</center>
		<table>
			<tr>
				<td class="thead">Name</td>
				<td class="tdata">
					<?php echo $data["name"]; ?>
				</td>
				<td colspan="2" rowspan="4">
					<img class="imag" height="200" width="175" src="/images/student/<?php if ($data["pic"]) echo $data["pic"]; else if ($data["gender"]=='M') echo 'male.jpg'; else if ($data["gender"]=='F') echo 'female.jpg'; else echo 'default.jpg'; ?>"/>
				</td>
			</tr>
			<tr>
				<td class="thead">B-Form No.</td>
				<td class="tdata">
					<?php echo $data["cnic"]; ?>
				</td>
			</tr>
			<tr>
				<td class="thead">Gender</td>
				<td class="tdata">
					<?php if ($data["gender"]=='M') echo 'Male'; else if ($data["gender"]=='F') echo 'Female'; else echo 'Other'; ?>
				</td>
			</tr>
			<tr>
				<td class="thead">Date of Birth</td>
				<td class="tdata">
					<?php echo $data["dob"]; ?>
				</td>
			</tr>
			<tr>
				<td class="thead">Board Registration Number</td>
				<td class="tdata">
					<?php echo $data["board_reg_no"]; ?>
				</td>
				<td class="thead">Class</td>
				<td class="tdata">
					<?php echo $data["cls_name"].' ('.$data["cls_section"].')'; ?>
				</td>
			</tr>
			<tr>
				<td class="thead">Father's Name</td>
				<td class="tdata">
					<?php echo $data["father_name"]; ?>
				</td>
				<td class="thead">Nationality</td>
				<td class="tdata">
					<?php echo $data["nationality"]; ?>
				</td>
			</tr>
			<tr>
				<td class="thead">Father's CNIC</td>
				<td class="tdata">
					<?php echo $data["father_cnic"]; ?>
				</td>
				<td class="thead">Religion</td>
				<td class="tdata">
					<?php echo $data["religion"]; ?>
				</td>
			</tr>
			<tr>
				<td class="thead">Phone Number</td>
				<td class="tdata">
					<?php echo $data["phone1"]; ?>
				</td>
				<td class="thead">Blood Group</td>
				<td class="tdata">
					<?php echo $data["blood_group"]; ?>
				</td>
			</tr>
			<tr>
				<td class="thead">Alternative</td>
				<td class="tdata">
					<?php echo $data["phone2"]; ?>
				</td>
			</tr>
			<tr>
				<td class="thead">Address</td>
				<td class="tdata" colspan="4">
					<?php echo $data["address"]; ?>
				</td>
			</tr>
		</table>
	</div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/bottom-section.php'); ?>
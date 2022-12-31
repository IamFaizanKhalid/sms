<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/t-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/t-left.php');

$data = $conn->query( "SELECT * FROM teacher t WHERE t.t_id='" . $u_id . "' LIMIT 1" )->fetch_assoc();

?>

<div class="rightcolumn">
	<div class="card" style="padding: 25px 100px 100px;">
		<center>
			<h1>Teacher Information</h1>
		</center>
		<table>
			<tr>
				<td class="thead">Name</td>
				<td class="tdata">
					<?php echo $data["name"]; ?>
				</td>
				<td colspan="2" rowspan="4">
					<img class="imag" height="200" width="175" src="/images/teacher/<?php if ($data["pic"]) echo $data["pic"]; else if ($data["gender"]=='M') echo 'male.jpg'; else if ($data["gender"]=='F') echo 'female.jpg'; else echo 'default.jpg'; ?>"/>
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
				<td class="thead">CNIC #</td>
				<td class="tdata">
					<?php echo $data["cnic"]; ?>
				</td>
			</tr>
			<tr>
				<td class="thead">Qualification</td>
				<td class="tdata">
					<?php echo $data["degree"]; ?>
				</td>
				<td class="thead">Salary</td>
				<td class="tdata">
					<?php echo $data["sal"]; ?>
				</td>
			</tr>
			<tr>
				<td class="thead">Religion</td>
				<td class="tdata">
					<?php echo $data["religion"]; ?>
				</td>
				<td class="thead">Blood Group</td>
				<td class="tdata">
					<?php echo $data["blood_group"]; ?>
				</td>
			</tr>
			<tr>
				<td class="thead">Nationality</td>
				<td class="tdata">
					<?php echo $data["nationality"]; ?>
				</td>
				<td class="thead">Marital Status</td>
				<td class="tdata">
					<?php if ($data["married"]) echo 'Married'; else echo 'Unmarried'; ?>
				</td>
			</tr>
			<tr>
				<td class="thead">Phone Number</td>
				<td class="tdata">
					<?php echo $data["phone1"]; ?>
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
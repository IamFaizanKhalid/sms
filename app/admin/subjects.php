<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-left.php');


if (isset($_POST["delsub"]))
{
	$temp = $conn->query("SELECT sub_id FROM subject");
	$sub = '';
	while ($x = $temp->fetch_assoc())
		if (isset($_POST[$x["sub_id"]]))
			$sub.=", '".$x["sub_id"]."'";
	$conn->query("DELETE FROM subject WHERE sub_id IN( '-1'".$sub." )");
	$conn->query("DELETE t, r FROM teacherAssigned t LEFT JOIN result r ON t.id=r.sub_dtl_id WHERE t.sub_id IN( '-1'".$sub." )");
}

$subjects = $conn->query("SELECT * FROM subject");
?>
			<div class="rightcolumn">
				<div class="card" style="padding: 25px 100px 100px;">
					<center>
						<h1>Subjects</h1>
					</center>
					<!--<input name="sname" type="text" id="myInput" onkeyup="searchTable()" placeholder="Search by name..">-->
					<a href="add-subject"><input type="submit" value="Add Subjects"></a><br><br>
					<form method="post" id="delform">
					<table  id="myTable" border="1">
						<tr>
							<th><input type="checkbox" onClick="toggleCheckBox(this)"></th>
							<th>Sr. No.</th>
							<th onclick="sortColumn(2)" style="cursor:pointer;">&#x21C5; Subject Name</th>
							<th></th>
						</tr>
					<?php
						 while ($x = $subjects->fetch_assoc())
						 {
							 echo '
						<tr>
							<td><input type="checkbox" name="'.$x["sub_id"].'"></td>
							<td class="counterCell"></td>
							<td>'.$x["sub_name"].'</td>
							<td><a href="edit-subject?id='.$x["sub_id"].'">Edit</a></td>
						</tr>';
						 }
						?>
						</table>
						<input type="hidden" id="delsub" name="delsub">
					</form>
			<input onClick="if(confirm('Warning!\nSelected subjects will be deleted.')) document.getElementById('delform').submit();" type="submit" value="Delete"><br>
				</div>
			</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/bottom-section.php'); ?>
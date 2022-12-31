<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-left.php');


if (isset($_POST["delex"]))
{
	$temp = $conn->query("SELECT ex_id FROM exams");
	$ex = '';
	while ($x = $temp->fetch_assoc())
		if (isset($_POST[$x["ex_id"]]))
			$ex.=", '".$x["ex_id"]."'";
	$conn->query("DELETE FROM exams WHERE ex_id IN( '-1'".$ex." )");
	$conn->query("DELETE FROM result WHERE ex_id IN( '-1'".$ex." )");
	$conn->query("DELETE FROM resultTotal WHERE ex_id IN( '-1'".$ex." )");
}

$exams = $conn->query("SELECT * FROM exams");
?>
			<div class="rightcolumn">
				<div class="card" style="padding: 25px 100px 100px;">
					<center>
						<h1>Exams</h1>
					</center>
					<!--<input name="sname" type="text" id="myInput" onkeyup="searchTable()" placeholder="Search by name..">-->
					<a href="add-exam"><input type="submit" value="Add Exam"></a><br><br>
					<form method="post" id="delform">
					<table  id="myTable" border="1">
						<tr>
							<th><input type="checkbox" onClick="toggleCheckBox(this)"></th>
							<th>Sr. No.</th>
							<th onclick="sortColumn(2)" style="cursor:pointer;">&#x21C5; Exam</th>
							<th onclick="sortNumColumn(4)" style="cursor:pointer;">&#x21C5; Fee</th>
							<th></th>
						</tr>
					<?php
						 while ($x = $exams->fetch_assoc())
						 {
							 echo '
						<tr>
							<td><input type="checkbox" name="'.$x["ex_id"].'"></td>
							<td class="counterCell"></td>
							<td>'.$x["ex_name"].' ('.date('F', mktime(0,0,0,(int)$x["ex_month"],10)).' '.$x["ex_year"].')</td>
							<td>'.$x["ex_fee"].'</td>
							<td><a href="edit-exam?id='.$x["ex_id"].'">Edit</a></td>
						</tr>';
						 }
						?>
						</table>
						<input type="hidden" id="delex" name="delex" value="Confirm Delete">
					</form><br>
			<input onClick="if(confirm('Warning!\nAll data of selected exams will be deleted.')) document.getElementById('delform').submit();" type="submit" value="Delete"><br>
				</div>
			</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/bottom-section.php'); ?>
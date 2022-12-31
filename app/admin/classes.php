<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-left.php');



if (isset($_POST["delcls"]))
{
	$temp = $conn->query("SELECT c.cls_id, IFNULL(s.num_std, 0) num_std FROM classes c LEFT JOIN (SELECT cls_id, COUNT(*) num_std FROM student GROUP BY cls_id) s ON s.cls_id=c.cls_id WHERE s.num_std IS NULL");
	$cls = '';
	while ($x = $temp->fetch_assoc())
		if (isset($_POST[$x["cls_id"]]))
			$cls.=", '".$x["cls_id"]."'";
	
	$conn->query("DELETE FROM classes WHERE cls_id IN( '-1'".$cls." )");
	$conn->query("DELETE FROM teacherAssigned WHERE cls_id IN( '-1'".$cls." )");
}

$classes = $conn->query("SELECT c.*, IFNULL(s.num_std, 0) num_std FROM classes c LEFT JOIN (SELECT cls_id, COUNT(*) num_std FROM student GROUP BY cls_id) s ON s.cls_id=c.cls_id ORDER BY cls_name, cls_section");
?>
			<div class="rightcolumn">
				<div class="card" style="padding: 25px 100px 100px;">
					<center>
						<h1>Classes</h1>
					</center>
					<!--<input name="sname" type="text" id="myInput" onkeyup="searchTable()" placeholder="Search by name..">-->
					<a href="add-class"><input type="submit" value="Add Class"></a><br><br>
					<form method="post" id="delform">
					<?php
						if ( $classes->num_rows > 0 ){
							echo'
					<table  id="myTable" border="1">
						<tr>
							<th><input type="checkbox" onClick="toggleCheckBox(this)"></th>
							<th>Sr. No.</th>
							<th onclick="sortColumn(2)" style="cursor:pointer;">&#x21C5; Class</th>
							<th onclick="sortColumn(3)" style="cursor:pointer;">&#x21C5; Section</th>
							<th onclick="sortNumColumn(4)" style="cursor:pointer;">&#x21C5; Students</th>
							<th style="cursor:pointer;">Details</th>
						</tr>';
						 while ($x = $classes->fetch_assoc())
						 {
							 echo '
						<tr>
							<td><input type="checkbox" name="'.$x["cls_id"].'"';if ($x["num_std"]) echo' disabled';echo'></td>
							<td class="counterCell"></td>
							<td>'.$x["cls_name"].'</td>
							<td>'.$x["cls_section"].'</td>
							<td>'.$x["num_std"].'</td>
							<td><a href="class-info?id='.$x["cls_id"].'">View / Edit</a></td>
						</tr>';
						 }
					echo '</table><small><i>* There must be no students in a class to delete it.</i></small>';
						}
						?>
						<input type="hidden" id="delcls" name="delcls">
					</form>
			<input onClick="if(confirm('Warning!\nSelected classes will be deleted.')) document.getElementById('delform').submit();" type="submit" value="Delete"><br>
				</div>
			</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/bottom-section.php'); ?>
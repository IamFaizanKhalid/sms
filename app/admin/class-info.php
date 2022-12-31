<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-left.php');

$cls_id=$_GET["id"];

$classinfo = $conn->query("SELECT c.*, t.name, IFNULL(s.num_std, 0) num_std FROM classes c LEFT JOIN (SELECT cls_id, COUNT(*) num_std FROM student GROUP BY cls_id) s ON s.cls_id=c.cls_id, teacher t WHERE c.incharge = t.t_id AND c.cls_id='".$cls_id."' ORDER BY c.cls_name, c.cls_section")->fetch_assoc();

$subjects = $conn->query("SELECT t.t_id, t.name, s.sub_name FROM teacherAssigned a INNER JOIN subject s ON a.sub_id=s.sub_id LEFT JOIN teacher t ON a.t_id=t.t_id WHERE cls_id='".$cls_id."'");


?>
			<div class="rightcolumn">
				<div class="card" style="padding: 25pclassinfo 100pclassinfo 100pclassinfo;">
					<center>
						<h1><?php echo $classinfo["cls_name"].' ('.$classinfo["cls_section"].')'; ?></h1>
					</center>
					<!--<input name="sname" type="teclassinfot" id="myInput" onkeyup="searchTable()" placeholder="Search by name..">-->
					<form id="std" method="post" action="students"><input type="hidden" name="cls" value="<?php echo $cls_id; ?>" ></form>
					<table border="1">
						<tr>
							<th>Class</th>
							<td><?php echo $classinfo["cls_name"]; ?></td>
							<th>Incharge</th>
							<td><a href="teacher-info?id=<?php echo $classinfo["incharge"]; ?>"><?php echo $classinfo["name"]; ?></a></td>
						</tr>
						<tr>
							<th>Section</th>
							<td><?php echo $classinfo["cls_section"]; ?></td>
							<th>Fee</th>
							<td><?php echo $classinfo["cls_fee"]; ?></td>
						</tr>
						<tr>
							<th><a href="#" onclick="document.getElementById('std').submit();">Students</a></th>
							<td><?php echo $classinfo["num_std"]; ?>
							</td>
						</tr>
						<tr>
							<td colspan="4">
								<center><h2>Subjects (<?php echo $subjects->num_rows; ?>)</h2></center>
								<?php
						if ( $subjects->num_rows > 0 ){
							echo'
					<table id="myTable" border="1">
						<tr>
							<th>Sr. No.</th>
							<th onclick="sortColumn(1)" style="cursor:pointer;">&#x21C5; Subject</th>
							<th onclick="sortColumn(2)" style="cursor:pointer;">&#x21C5; Teacher</th>
						</tr>';
						 while ($x = $subjects->fetch_assoc())
						 {
							 echo '
						<tr>
							<td class="counterCell"></td>
							<td>'.$x["sub_name"].'</td>
							<td><a href="teacher-info?id='.$x["t_id"].'">'.$x["name"].'</a></td>
						</tr>';
						 }
					echo '</table>';
						}
						?>
							</td>
						</tr>
						</table><br>
					<a href="edit-class-info?id=<?php echo $cls_id; ?>"><input type="submit" value="Edit"></a>
				</div>
			</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/bottom-section.php'); ?>
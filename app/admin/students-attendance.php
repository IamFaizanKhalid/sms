<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-left.php');

$month = date('m');
$year = date('Y');

$classes = $conn->query("SELECT cls_id, cls_name, cls_section FROM classes");
if (isset($_POST["cls"]))
	$cls_id=$_POST["cls"];
else
	$cls_id = -1;	


$std = $conn->query("SELECT std_id, rollno, name FROM student WHERE cls_id='".$cls_id."' ORDER BY rollno");

$months = $conn->query("SELECT DISTINCT month FROM s_atd");
$years = $conn->query("SELECT DISTINCT year FROM s_atd");

if (!$months->num_rows){
	$months = $conn->query("SELECT MONTH(CURRENT_DATE()) month FROM DUAL");
	$years = $conn->query("SELECT YEAR(CURRENT_DATE()) year FROM DUAL");
}

if (isset($_POST["month"]))
	$month = $_POST["month"];
if (isset($_POST["year"]))
	$year = $_POST["year"];


?>
			<div class="rightcolumn">
				<div class="card" style="padding: 25px 100px 100px;">
					<center>
						<h1>Attendance</h1>
					</center>				
					<form method="post">
						Select Month: 
						<select onChange="this.form.submit()" name="month">
							<?php
							if($months->num_rows > 0) while ($x = $months->fetch_assoc())
	if ($month == $x["month"])
								echo '<option value="'.$x["month"].'" selected>'.date('F', mktime(0, 0, 0, $x["month"], 10)).'</option>';
							else
								echo '<option value="'.$x["month"].'">'.date('F', mktime(0, 0, 0, $x["month"], 10)).'</option>';
 ?>
						</select>
						Select Year: 
						<select onChange="this.form.submit()" name="year">
							<?php
							if($years->num_rows > 0) while ($x = $years->fetch_assoc())
	if ($year == $x["year"])
								echo '<option value="'.$x["year"].'" selected>'.$x["year"].'</option>';
							else
								echo '<option value="'.$x["year"].'">'.$x["year"].'</option>';
 ?>
						</select>
						Select Class: 
						<select onChange="this.form.submit()" name="cls">
							<?php if ($cls_id<0) echo '<option value="-1" selected>--</option>';
							if($classes->num_rows > 0) while ($x = $classes->fetch_assoc())
	if ($cls_id == $x["cls_id"])
								echo '<option value="'.$x["cls_id"].'" selected>'.$x["cls_name"].' ('.$x["cls_section"].')</option>';
							else
								echo '<option value="'.$x["cls_id"].'">'.$x["cls_name"].' ('.$x["cls_section"].')</option>';
 ?>
						</select><br><br><br>
					</form>
					<input name="sname" type="text" id="myInput" onkeyup="searchColumn(1)" placeholder="Search by name.."><br><br><br>
					<table id="myTable" border="1">
						<tr>
							<th onClick="sortNumColumn(0)" style="cursor:pointer;">&#x21C5; Roll No.</th>
							<th onClick="sortColumn(1)" style="cursor:pointer;">&#x21C5; Name</th>
							<?php for ($i=1;$i<=date('d');$i++) echo '<th>'.$i.'</th>'; ?>
							<th>Total</th>
						</tr>
						<?php
						$sr=0;
						while($x = $std->fetch_assoc())
						{
							echo '<tr>
							<td>'.$x["rollno"].'</td>
							<td>'.$x["name"].'</td>';
							
							$atd = $conn->query("SELECT * FROM s_atd WHERE year=".$year." AND month=".$month." AND std_id='".$x["std_id"]."' LIMIT 1");
							if ($atd->num_rows < 1)
							{
								$conn->query("INSERT INTO s_atd(std_id,year,month) VALUES(".$x["std_id"].", ".$year.", ".$month.")");
								$atd = $conn->query("SELECT * FROM s_atd WHERE year=".$year." AND month=".$month." AND std_id='".$x["std_id"]."' LIMIT 1");
							}
							$atd=$atd->fetch_assoc();
							
							$d=idate('d');
							
							for ($i=1;$i<$d;$i++) if ($atd[$i] == 'P') echo '<td><font color="green">'.$atd[$i].'</font></td>'; else if ($atd[$i] == 'A') echo '<td><font color="red">'.$atd[$i].'</font></td>'; else if ($atd[$i] == 'L') echo '<td><font color="blue">'.$atd[$i].'</font></td>'; else echo '<td>-</td>';
							
							if ($atd[$d] == 'P') echo '<td><font color="green">'.$atd[$d].'</font></td>'; else if ($atd[$d] == 'A') echo '<td><font color="red">'.$atd[$d].'</font></td>'; else if ($atd[$d] == 'L') echo '<td><font color="blue">'.$atd[$d].'</font></td>'; else echo '<td>-</td>';
							
							echo '<td><font color="green">Present</font>: '.$atd["present"].' / <font color="red">Absent</font>: '.$atd["absent"].' / <font color="blue">Leave</font>: '.$atd["leaves"].'</td></tr>';
						}
						?>
					</table>
					
				</div>
			</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/bottom-section.php'); ?>
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-left.php');

$month = date('m');
$year = date('Y');

$months = $conn->query("SELECT DISTINCT month FROM t_atd");
$years = $conn->query("SELECT DISTINCT year FROM t_atd");

if (!$months->num_rows){
	$months = $conn->query("SELECT MONTH(CURRENT_DATE()) month FROM DUAL");
	$years = $conn->query("SELECT YEAR(CURRENT_DATE()) year FROM DUAL");
}
if (isset($_POST["month"]))
	$month = $_POST["month"];
if (isset($_POST["year"]))
	$year = $_POST["year"];

$teachers = $conn->query("SELECT t_id, name FROM teacher t");

if (isset($_POST["mark-atd"]))
{
	
$temp = $conn->query("SELECT t_id, name FROM teacher t");

	while($x = $temp->fetch_assoc())
	{
		if (isset($_POST[$x["t_id"]]))
			$conn->query("UPDATE t_atd SET `".idate('d')."`='P', present=present+1 WHERE year='".date('Y')."' AND month='".date('m')."' AND t_id='".$x["t_id"]."'");
		else
			$conn->query("UPDATE t_atd SET `".idate('d')."`='A', absent=absent+1 WHERE year='".date('Y')."' AND month='".date('m')."' AND t_id='".$x["t_id"]."' AND `".idate('d')."` IS NULL");
		
	}
}

?>
			<div class="rightcolumn">
				<div class="card" style="padding: 25px 100px 100px;">
					<center>
						<h1>Teacher Attendance</h1>
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
					</form><br><br><br>
					<input name="sname" type="text" id="myInput" onkeyup="searchColumn(1)" placeholder="Search by name.."><br><br>
					<form method="post">
					<table id="myTable" border="1">
						<tr>
							<th>Sr. No.</th>
							<th>Name</th>
							<?php for ($i=1;$i<=date('d');$i++) echo '<th>'.$i.'</th>'; ?>
							<th>Total</th>
						</tr>
						<?php
						$marked=true;
						while($x = $teachers->fetch_assoc())
						{
							echo '<tr>
							<td class="counterCell"></td>
							<td>'.$x["name"].'</td>';
							
							$atd = $conn->query("SELECT * FROM t_atd  WHERE year=".$year." AND month=".$month." AND t_id='".$x["t_id"]."' LIMIT 1");
							if ($atd->num_rows < 1)
							{
								$conn->query("INSERT INTO t_atd(t_id,year,month) VALUES(".$x["t_id"].", ".$year.", ".$month.")");
								$atd = $conn->query("SELECT * FROM t_atd  WHERE year=".$year." AND month=".$month." AND t_id='".$x["t_id"]."' LIMIT 1");
							}
							$atd=$atd->fetch_assoc();
							
							$d=idate('d');
							
							for ($i=1;$i<$d;$i++) if ($atd[$i] == 'P') echo '<td><font color="green">'.$atd[$i].'</font></td>'; else if ($atd[$i] == 'A') echo '<td><font color="red">'.$atd[$i].'</font></td>'; else if ($atd[$i] == 'L') echo '<td><font color="blue">'.$atd[$i].'</font></td>'; else echo '<td>-</td>';
							
							if ($atd[$d] == 'P') echo '<td><font color="green">'.$atd[$d].'</font></td>'; else if ($atd[$d] == 'A') echo '<td><font color="red">'.$atd[$d].'</font></td>'; else if ($atd[$d] == 'L') echo '<td><font color="blue">'.$atd[$d].'</font></td>'; else {echo '<td><input type="checkbox" name="'.$x["t_id"].'"></td>'; $marked=false; }
							
							echo '<td><font color="green">Present</font>: '.$atd["present"].' / <font color="red">Absent</font>: '.$atd["absent"].' / <font color="blue">Leave</font>: '.$atd["leaves"].'</td></tr>';
						}
						?>
					</table>
						<input type="hidden" name="cls" value="<?php echo $cls_id ?>" >
					<?php if(!$marked) echo '<input type="submit" name="mark-atd" value="Mark Attendance" >'; ?>
					</form>
				</div>
			</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/bottom-section.php'); ?>
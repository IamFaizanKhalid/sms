<?php
include($_SERVER['DOCUMENT_ROOT'].'/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'].'/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'].'/includes/a-left.php');


$classes = $conn->query( "SELECT cls_id, cls_name, cls_section FROM classes" );
if ( isset( $_POST[ "cls" ] ) )
	$cls_id = $_POST[ "cls" ];
else
	$cls_id = -1;


$std = $conn->query("SELECT std_id, name FROM student WHERE cls_id='".$cls_id."' ORDER BY rollno");
if (isset($_POST["std_id"]))
	$std_id=$_POST["std_id"];
else
	$std_id=-1;



if (isset($_POST["mark-atd"]))
{
	$i=idate('d');
	$d=idate('t');
		$lc=0;
	$sql_d= '';
	$sql = "UPDATE s_atd SET ";
		for (;$i<=$d;$i++)
		{
			if (isset($_POST[$i]))
			{
				$sql_d.= ", `".$i."` = 'L'";
				$lc++;
			}
		}
		$sql.=" leaves=leaves+$lc $sql_d WHERE std_id='".$std_id."' AND year='".date('Y')."' AND month='".date('m')."'";
		$conn->query($sql);
			
}

$atd = $conn->query( "SELECT * FROM s_atd WHERE year=" . date( 'Y' ) . " AND month=" . date( 'm' ) . " AND std_id='" . $std_id . "' LIMIT 1" );
if ( $atd->num_rows < 1 ) {
	$conn->query( "INSERT INTO s_atd(std_id,year,month) VALUES(" . $std_id . ", " . date( 'Y' ) . ", " . date( 'm' ) . ")" );
	$atd = $conn->query( "SELECT * FROM s_atd WHERE year=" . date( 'Y' ) . " AND month=" . date( 'm' ) . " AND std_id='" . $std_id . "' LIMIT 1" );
}
$atd = $atd->fetch_assoc();


?>
			<div class="rightcolumn">
				<div class="card" style="padding: 25px 100px 100px;">
					<center>
						<h1>Student Leave</h1>
					</center>
					
					<form method="post">
						Select Class: 
						<select onChange="this.form.submit()" name="cls">
							<?php if ( $std->num_rows == 0 ) echo'<option selected></option>'; ?>
							<?php if($classes->num_rows > 0) while ($x = $classes->fetch_assoc())
	if ($cls_id == $x["cls_id"])
								echo '<option value="'.$x["cls_id"].'" selected>'.$x["cls_name"].' ('.$x["cls_section"].')</option>';
							else
								echo '<option value="'.$x["cls_id"].'">'.$x["cls_name"].' ('.$x["cls_section"].')</option>';
 ?>
						</select>
						Select Student: 
						<select onChange="this.form.submit()" name="std_id">
							<?php if ($std_id<0) echo '<option value="-1"></option>';
							if($std->num_rows > 0) while ($x = $std->fetch_assoc())
	if ($std_id == $x["std_id"])
								echo '<option value="'.$x["std_id"].'" selected>'.$x["name"].'</option>';
							else
								echo '<option value="'.$x["std_id"].'">'.$x["name"].'</option>';
 ?>
						</select><br><br>
					</form><br><br><br>
 
					<form method="post">
					<table border="1">
						<tr>
							<th>Date</th>
							<th>Leave</th>
						</tr>
						
						<?php
						if ($std_id != -1)
						{
							$i=idate('d');
							$d=idate('t');
							for (;$i<=$d;$i++)
							{
								echo '
								<tr>
									<th>'.$i.'</th>';
								if ($atd[$i] == 'L') echo '<td><font color="blue">L</font></td>'; else if ($atd[$i] == 'P') echo '<td><font color="green">P</font></td>'; else echo '<td><input type="checkbox" name="'.$i.'"></td>';
								
								echo '</tr>';
							}
						
							echo '
					</table><br><font color="green">Present</font>: '.$atd["present"].' / <font color="red">Absent</font>: '.$atd["absent"].' / <font color="blue">Leave</font>: '.$atd["leaves"];
						}
						else echo '</table>';
						?>
						<input type="hidden" name="cls" value="<?php echo $cls_id ?>" >
						<input type="hidden" name="std_id" value="<?php echo $std_id ?>" >
					<?php if ($std_id != -1) echo '<input type="submit" name="mark-atd" value="Mark Leave" >'; ?>
					</form>
				</div>
			</div>

<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/bottom-section.php'); ?>
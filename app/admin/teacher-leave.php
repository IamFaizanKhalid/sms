<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-left.php');


$teachers = $conn->query("SELECT t_id, name FROM teacher");

if (isset($_POST["t_id"]))
	$t_id=$_POST["t_id"];
else
	$t_id=-1;


if (isset($_POST["mark-atd"]))
{
	$i=idate('d');
	$d=idate('t');
		$lc=0;
	$sql_d= '';
	$sql = "UPDATE t_atd SET ";
		for (;$i<=$d;$i++)
		{
			if (isset($_POST[$i]))
			{
				$sql_d.= ", `".$i."` = 'L'";
				$lc++;
			}
		}
		$sql.=" leaves=leaves+$lc $sql_d WHERE t_id='".$t_id."' AND year='".date('Y')."' AND month='".date('m')."'";
		$conn->query($sql);
			
}


$atd = $conn->query( "SELECT * FROM t_atd WHERE year=" . date( 'Y' ) . " AND month=" . date( 'm' ) . " AND t_id='" . $t_id . "' LIMIT 1" );
if ( $atd->num_rows < 1 ) {
	$conn->query( "INSERT INTO t_atd(t_id,year,month) VALUES(" . $t_id . ", " . date( 'Y' ) . ", " . date( 'm' ) . ")" );
	$atd = $conn->query( "SELECT * FROM t_atd WHERE year=" . date( 'Y' ) . " AND month=" . date( 'm' ) . " AND t_id='" . $t_id . "' LIMIT 1" );
}
$atd = $atd->fetch_assoc();


?>
			<div class="rightcolumn">
				<div class="card" style="padding: 25px 100px 100px;">
					<center>
						<h1>Teacher Leave</h1>
					</center>
					
					<form method="post">
						Select Teacher: 
						<select onChange="this.form.submit()" name="t_id">
							<?php if ($t_id<0) echo '<option value="-1"></option>';
							if($teachers->num_rows > 0) while ($x = $teachers->fetch_assoc())
	if ($t_id == $x["t_id"])
								echo '<option value="'.$x["t_id"].'" selected>'.$x["name"].'</option>';
							else
								echo '<option value="'.$x["t_id"].'">'.$x["name"].'</option>';
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
						if ($t_id != -1)
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
						<input type="hidden" name="t_id" value="<?php echo $t_id ?>" >
					<?php if ($t_id != -1) echo '<input type="submit" name="mark-atd" value="Mark Leave" >'; ?>
					</form>
				</div>
			</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/bottom-section.php'); ?>
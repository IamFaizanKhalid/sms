<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-left.php');

if ( !isset( $_GET[ "id" ] ) || $_GET[ "id" ] == '' )
	header( "Location: teacher" );

$t_id = $_GET[ "id" ];


$month = date('j');
$year = date('Y');

$months = $conn->query("SELECT DISTINCT month FROM t_atd");
$years = $conn->query("SELECT DISTINCT year FROM t_atd");

if (isset($_POST["month"]))
	$month = $_POST["month"];
if (isset($_POST["year"]))
	$year = $_POST["year"];


$atd = $conn->query( "SELECT * FROM t_atd WHERE t_id='" . $t_id . "' AND year='" . $year . "' AND month='" . $month . "' LIMIT 1" )->fetch_assoc();

?>
<div id="feeslip" class="rightcolumn">
	<div class="card" style="padding: 25px 100px 100px;">
		<center>
			<h1>Teacher's Attendance</h1>
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
		<center>
			<table border="1" style="text-align: center;">
				<tr>
					<th width="150">Date</th>
					<th width="50">Attendance</th>
				</tr>
				<?php
				for ( $i = 1; $i <= 31; $i++ ) {
					if ( $atd[ $i ] ) {
						if ( $atd[ $i ] == 'P' )
							echo '<tr>
							<td>' . str_pad( $i, 2, '0', STR_PAD_LEFT ) . date( "-m-Y" ) . '</td>
							<td><font color="green">' . $atd[ $i ] . '</font></td>
						</tr>';
						else if ( $atd[ $i ] == 'A' )
							echo '<tr>
							<td>' . str_pad( $i, 2, '0', STR_PAD_LEFT ) . date( "-m-Y" ) . '</td>
							<td><font color="red">' . $atd[ $i ] . '</font></td>
						</tr>';
						else if ( $atd[ $i ] == 'L' )
							echo '<tr>
							<td>' . str_pad( $i, 2, '0', STR_PAD_LEFT ) . date( "-m-Y" ) . '</td>
							<td><font color="blue">' . $atd[ $i ] . '</font></td>
						</tr>';
					}
				}
				?>
			</table><br>
			<table>
				<tr>
					<th width="100">Total:-</th>
					<td>Present:</td>
					<td width="50">
						<font color="green">
							<?php echo $atd["present"] ?>
						</font>
					</td>
					<td>Absent:</td>
					<td width="50">
						<font color="red">
							<?php echo $atd["absent"] ?>
						</font>
					</td>
					<td>Leave:</td>
					<td width="50">
						<font color="blue">
							<?php echo $atd["leaves"] ?>
						</font>
					</td>
				</tr>
			</table>
		</center>
	</div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/bottom-section.php'); ?>
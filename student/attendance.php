<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/globalVars.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/s-top-section.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/std-left.php' );


$atd = $conn->query( "SELECT * FROM s_atd WHERE std_id='" . $u_id . "' AND year='" . date( "Y" ) . "' AND month='" . date( "m" ) . "' LIMIT 1" )->fetch_assoc();

?>
<div id="feeslip" class="rightcolumn">
	<div class="card" style="padding: 25px 100px 100px;">
		<center>
			<h1>Attendance</h1>
			<table border="1" style="text-align: center;">
				<tr>
					<th width="150">Date</th>
					<th width="50">Attendance</th>
				</tr>
				<?php
				$today = idate( 'd' );
				for ( $i = 1; $i <= $today; $i++ ) {
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

<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/bottom-section.php'); ?>
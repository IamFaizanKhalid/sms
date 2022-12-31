<?php
include($_SERVER['DOCUMENT_ROOT'].'/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'].'/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'].'/includes/a-left.php');

$t_id = $_GET["id"];

$sal = $conn->query("SELECT t.t_id, t.name, t.sal, a.absent FROM teacher t LEFT JOIN t_atd a ON a.t_id=t.t_id AND a.year='".date("Y", strtotime("last month"))."' AND a.month='".date("m", strtotime("last month"))."' WHERE t.t_id='".$t_id."' LIMIT 1")->fetch_assoc();

$vars = $conn->query("SELECT * FROM controlVars WHERE var IN('t_a_fine', 't_a_fine_amnt')");
while ($x = $vars->fetch_assoc())
	${$x["var"]} = $x["value"];

if ($t_a_fine)
	$fine = $sal["absent"] * $t_a_fine_amnt;
else
	$fine = 0;

$subtotal = $sal["sal"]-$fine;
?>
			<div class="rightcolumn">
				<div class="card" style="padding: 25px 100px 100px;">
					<center>
						<h1>Salary Slip</h1>
					</center>
					<?php
/*						if ( $sal->num_rows > 0 ){
							echo'
					<table  id="myTable" border="1">
						<tr>
							<th><input type="checkbox" onClick="toggleCheckBox(this)"></th>
							<th>Sr. No.</th>
							<th onclick="sortColumn(2)" style="cursor:pointer;">&#x21C5; Name</th>
							<th onclick="sortColumn(3)" style="cursor:pointer;">&#x21C5; Salary</th>
							<th onclick="sortColumn(4)" style="cursor:pointer;">&#x21C5; Absents</th>
							<th>Detail</th>
						</tr>';
						 while ($x = $sal->fetch_assoc())
						 {
							 echo '
						<tr>
							<td><input type="checkbox"></td>
							<td class="counterCell"></td>
							<td>'.$x["name"].'</td>
							<td>'.$x["sal"].'</td>
							<td>'.$x["absent"].'</td>
							<td><a href="salary-slip?id='.$x["t_id"].'">View Detail</a></td>
						</tr>';
						 }
					echo '</table>';
						}
*/						?>
					<form id="slip">
						<table>
						<tr>
							<td class="thead-fee">Name</td>
							<td>
								<?php echo $sal["name"]; ?>
							</td>
							<td class="thead-fee">Month:</td>
							<td>
								<?php echo date("F", strtotime("last month")); ?>
							</td>
						</tr>
						<tr>
							<td class="thead-fee">Date</td>
							<td colspan="3">
								<?php echo date('d-m-Y'); ?>
							</td>
							
						</tr>
						<tr>
							<td colspan="5" style="padding: 15px 5px 5px;">
								<table  style="border: 3px solid black">
									<tr>
										<td class="thead-fee2">Salary</td>
										<td class="tdata-fee2">
											<?php echo $sal["sal"]; ?>
										</td>
									</tr>
									<tr>
										<td class="thead-fee2">Absence Fine <?php echo '( '.$sal["absent"].' days x '; if ($t_a_fine) echo $t_a_fine_amnt.' )'; else echo '0 )'; ?></td>
										<td class="tdata-fee2">
											<?php echo $fine; ?>
										</td>
									</tr>
									<tr>
										<th class="thead-fee2" style="border-top: 3px solid black">Subtotal</th>
										<th class="tdata-fee2" style="border-top: 3px solid black">
											<?php echo $subtotal; ?>
										</th>
									</tr>
									<tr>
										<td class="thead-fee2">Miscellaneous</td>
										<td class="tdata-fee2" id="misc">
											0
										</td>
									</tr>
									<tr>
										<th class="thead-fee2" style="border-top: 3px solid black">Total</th>
										<th name="total" id="total" class="tdata-fee2" style="border-top: 3px solid black">
											<?php echo $subtotal; ?>
										</th>
											<input name="total" type="hidden" value="<?php echo $subtotal; ?>" id="totalNew" />
											<input type="hidden" value="<?php echo $subtotal; ?>" id="totalOld" />
									</tr>
								</table>
								<small><i>* Above values are shown in PKR</i></small>
							</td>
						</tr>
					</table>
					</form><input type="submit" name="paid" onClick="printSlip()" value="Print Slip">
					<br><br>Adjustment / Miscellaneous:
					<input onChange="updateTotal(this.value)" onKeyUp="updateTotal(this.value)" name="misc" type="number" value="0">
					
				</div>
			</div>

<script type="text/javascript">
	function updateTotal(misc)
	{
		var total = eval(document.getElementById('totalOld').value) + eval(misc);
		document.getElementById('totalNew').value = total;
		document.getElementById('misc').innerHTML = misc;
		document.getElementById('total').innerHTML = total;
		
	}
	function printSlip()
	{
    	myWindow=window.open('','','width=1280,height=720');
    	myWindow.document.write('<link rel="stylesheet" href="<?php echo $style_sheet; ?>"/><div ><div style="float: left; width: 150px;"><img src="<?php echo $school_logo; ?>" widtd="100" height="100" title="<?php echo $school_name ?>" alt="School Logo"/></div><div style="white-space: nowrap;"><h1><br><?php echo $school_name ?></h1></div></div><br><div class="card"><center><h1>Salary Slip</h1></center>'+document.getElementById('slip').innerHTML+'<p style="float: right;"><br><br>Reciever\'s Signature:<br><br><br>___________________</p></div>');
		
    	myWindow.document.close(); //missing code
		
		myWindow.focus();
		myWindow.print(); 
		myWindow.close(); 
	}
</script>


<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/bottom-section.php'); ?>
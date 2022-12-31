<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-left.php');

$std_id= $_GET["id"];

if (isset($_POST["paid"]))
{
	$conn->query("INSERT INTO fee(std_id, year, month, amount_paid) VALUES('".$std_id."','".date('Y')."','".date('m')."','".$_POST["total"]."')") or die(mysqli_error($conn));
}

$data = $conn->query("SELECT * FROM student s, classes c WHERE s.std_id='".$std_id."' AND s.cls_id=c.cls_id LIMIT 1")->fetch_assoc();
$fee = $conn->query("SELECT * FROM fee WHERE std_id='".$std_id."' AND year='".date("Y")."' AND month='".date("m")."' LIMIT 1")->fetch_assoc();
$exam = $conn->query("SELECT ex_fee FROM exams WHERE ex_year='".date("Y")."' AND ex_month='".date("m")."' LIMIT 1")->fetch_assoc();
$atd = $conn->query("SELECT present, absent, leaves FROM s_atd  WHERE std_id='".$std_id."' AND year='".date("Y", strtotime("last month"))."' AND month='".date("m", strtotime("last month"))."' LIMIT 1")->fetch_assoc();
$result = $conn->query("SELECT * FROM controlVars WHERE var IN('admission_fee', 'atd_fine', 'atd_fine_amnt', 'fee_fine_after', 'fee_fine_amnt', 'fee_fine_daily')");//->fetch_all();
$fine = array();
while($row = $result->fetch_assoc())
	$fine[$row['var']] = $row['value'];


$absentfine = $atd["absent"]*$fine["atd_fine_amnt"]*$fine["atd_fine"];

if ( $fee[ "paydate" ] )
	$dayslate = date( 'd', strtotime( $fee[ "paydate" ] ) )  - $fine[ "fee_fine_after" ];
else
	$dayslate = date( 'd' ) - $fine[ "fee_fine_after" ];
if ($dayslate < 0 || !$fine[ "fee_fine_daily" ])
	$dayslate = 0;
$latefeefine = $fine[ "fee_fine_amnt" ] * $dayslate;

$misc = 0;
$admfee = 0;
if (date('m', strtotime($data["added"])) == date('m') && date('Y', strtotime($data["added"])) == date('Y'))
	$admfee = $fine["admission_fee"];

$subtotal=$data["cls_fee"] + $admfee + $exam["ex_fee"] +$absentfine;
if ( $fee[ "paydate" ] )
	$total=$fee["amount_paid"];
else
	$total=$subtotal + $latefeefine;
if ( $fee[ "paydate" ] )
	$misc = $fee["amount_paid"] - ($subtotal + $latefeefine);



?>
			<div id="feeslip" class="rightcolumn">
				<div class="card" style="padding: 25px 100px 100px;">
					<center>
						<h1>Fee Slip</h1>
					</center>
					<form method="post" id="slip">
						<table>
						<tr>
							<td class="thead-fee">Serial No.</td>
							<td>
								<?php if($fee["paydate"]) echo str_pad($fee["sr_no"], 4, '0', STR_PAD_LEFT); else echo '-'; ?>
							</td>
							
							<td class="thead-fee">Date:</td>
							<td>
								<?php if($fee["paydate"]) echo date('d-m-Y', strtotime($fee["paydate"])); else echo '-'; ?>
							</td>
						</tr>
						<tr>
							<td class="thead-fee">Name</td>
							<td>
								<?php echo $data["name"]; ?>
							</td>
							
							<td class="thead-fee">Father's Name</td>
							<td>
								<?php echo $data["father_name"]; ?>
							</td>
						</tr>
						<tr>
							<td class="thead-fee">Class</td>
							<td>
								<?php echo $data["cls_name"].' ('.$data["cls_section"].')'; ?>
							</td>
							
							<td class="thead-fee">Roll No.</td>
							<td>
								<?php echo $data["rollno"]; ?>
							</td>
						</tr>
						<tr>
							<td class="thead-fee">Status:</td>
							<td>
								<?php if($fee["paydate"]) echo 'Paid'; else echo 'Unpaid'; ?>
							</td>
							
							<td class="thead-fee">Amount Paid:</td>
							<td>
								<?php if($fee["paydate"]) echo $fee["amount_paid"]; else echo '-'; ?>
							</td>
						</tr>
						<tr>
							<td class="thead-fee">Month:</td>
							<td>
								<?php echo date("F") ?>
							</td>
							
							<td class="thead-fee">Due Date:</td>
							<td>
								<?php echo $fine["fee_fine_after"].date("-m-Y") ?>
							</td>
						</tr>
						<tr>
							<td colspan="5" style="padding: 15px 5px 5px;">
								<table  style="border: 3px solid black">
									<tr>
										<td class="thead-fee2">Admission Fee</td>
										<td class="tdata-fee2">
											<?php echo $admfee; ?>
										</td>
									</tr>
									<tr>
										<td class="thead-fee2">Tuition Fee</td>
										<td class="tdata-fee2">
											<?php echo $data["cls_fee"]; ?>
										</td>
									</tr>
									<tr>
										<td class="thead-fee2">Examination Fee</td>
										<td class="tdata-fee2">
											<?php if($exam["ex_fee"]!='') echo $exam["ex_fee"]; else echo '0'; ?>
										</td>
									</tr>
									<tr>
										<td class="thead-fee2">Absence Fine <?php if ($absentfine) echo '( '.$atd["absent"].' days x '.$fine["atd_fine_amnt"].' )'; ?></td>
										<td class="tdata-fee2">
											<?php echo $absentfine; ?>
										</td>
									</tr>
									<tr>
										<th class="thead-fee2" style="border-top: 3px solid black">Subtotal</th>
										<th class="tdata-fee2" style="border-top: 3px solid black">
											<?php echo $subtotal; ?>
										</th>
									</tr>
									<tr>
										<td class="thead-fee2">Late Payment Fine <?php if($latefeefine) echo '( '.$dayslate.' days x '.$fine["fee_fine_amnt"] .' )'; ?></td>
										<td class="tdata-fee2">
											<?php echo $latefeefine; ?>
										</td>
									</tr>
									<tr>
										<td class="thead-fee2">Adjustment / Miscellaneous</td>
										<td class="tdata-fee2">
											<?php if($fee["paydate"]) echo $misc; else echo '<input onChange="updateTotal(this.value)" onKeyUp="updateTotal(this.value)" name="misc" type="number" value="'.$misc.'">'; ?>
										</td>
									</tr>
									<tr>
										<th class="thead-fee2" style="border-top: 3px solid black">Total</th>
										<th name="total" id="total" class="tdata-fee2" style="border-top: 3px solid black">
											<?php echo $total; ?>
										</th>
											<input name="total" type="hidden" value="<?php echo $total; ?>" id="totalNew" />
											<input type="hidden" value="<?php echo $total; ?>" id="totalOld" />
									</tr>
								</table>
								<small><i>* Above values are shown in PKR</i></small>
							</td>
						</tr>
					</table><input type="hidden" name="paid" value="Paid">
					</form>
					<?php if(!$fee["paydate"]) echo '<input onClick="if(confirm(\'Change status to paid?\')) document.getElementById(\'slip\').submit();" type="submit" name="paid" value="Paid"> ';
					else echo '<input onClick="printSlip()" type="submit" value="Print Slip"> '; ?>
				</div>
			</div>

<script type="text/javascript">
	function updateTotal(misc)
	{
		var total = eval(document.getElementById('totalOld').value) + eval(misc);
		document.getElementById('totalNew').value = total;
		document.getElementById('total').innerHTML = total;
		
	}
	function printSlip()
	{
    	myWindow=window.open('','','width=1280,height=720');
    	myWindow.document.write('<link rel="stylesheet" href="<?php echo $style_sheet; ?>"/><div ><div style="float: left; width: 150px;"><img src="<?php echo $school_logo; ?>" widtd="100" height="100" title="<?php echo $school_name ?>" alt="School Logo"/></div><div style="white-space: nowrap;"><h1><br><?php echo $school_name ?></h1></div></div><br><div class="card"><center><h1>Fee Slip</h1></center>'+document.getElementById('slip').innerHTML+'<p style="float: right;"><br>Signature:<br>_________________</p></div>');
		
    	myWindow.document.close(); //missing code
		
		myWindow.focus();
		myWindow.print(); 
		myWindow.close(); 
	}
</script>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/bottom-section.php'); ?>
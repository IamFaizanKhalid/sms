<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/globalVars.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/s-top-section.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/std-left.php' );



$data = $conn->query( "SELECT * FROM student s, classes c WHERE s.std_id='" . $u_id . "' AND s.cls_id=c.cls_id LIMIT 1" )->fetch_assoc();
$fee = $conn->query( "SELECT * FROM fee WHERE std_id='" . $u_id . "' AND year='" . date( "Y" ) . "' AND month='" . date( "m" ) . "' LIMIT 1" )->fetch_assoc();
$exam = $conn->query( "SELECT ex_fee FROM exams WHERE ex_year='" . date( "Y" ) . "' AND ex_month='" . date( "m" ) . "' LIMIT 1" )->fetch_assoc();
$atd = $conn->query( "SELECT * FROM s_atd  WHERE std_id='" . $u_id . "' AND year='" . date( "Y", strtotime( "last month" ) ) . "' AND month='" . date( "m", strtotime( "last month" ) ) . "' LIMIT 1" )->fetch_assoc();
$result = $conn->query( "SELECT * FROM controlVars WHERE var IN('admission_fee', 'atd_fine', 'atd_fine_amnt', 'fee_fine_after', 'fee_fine_amnt', 'fee_fine_daily')" ); //->fetch_all();
$fine = array();
while ( $row = $result->fetch_assoc() )
	$fine[ $row[ 'var' ] ] = $row[ 'value' ];


$absentfine = $atd[ "absent" ] * $fine[ "atd_fine_amnt" ] * $fine[ "atd_fine" ];

if ( $fee[ "paydate" ] )
	$dayslate = date( 'd', strtotime( $fee[ "paydate" ] ) ) - $fine[ "fee_fine_after" ];
else
	$dayslate = date( 'd' ) - $fine[ "fee_fine_after" ];
if ( $dayslate < 0 || !$fine[ "fee_fine_daily" ] )
	$dayslate = 0;
$latefeefine = $fine[ "fee_fine_amnt" ] * $dayslate;

$misc = 0;
$admfee = 0;
if ( date( 'm', strtotime( $data[ "added" ] ) ) == date( 'm' ) && date( 'Y', strtotime( $data[ "added" ] ) ) == date( 'Y' ) )
	$admfee = $fine[ "admission_fee" ];

$subtotal = $data[ "cls_fee" ] + $admfee + $exam[ "ex_fee" ] + $absentfine;
if ( $fee[ "paydate" ] )
	$total = $fee[ "amount_paid" ];
else
	$total = $subtotal + $latefeefine;
if ( $fee[ "paydate" ] )
	$misc = $fee[ "amount_paid" ] - ( $subtotal + $latefeefine );
?>
<div id="feeslip" class="rightcolumn">
	<div class="card" style="padding: 25px 100px 100px;">
		<center>
			<h1>Fee Information</h1>
		</center>
		<table>
			<tr>
				<td class="thead-fee">Serial No.</td>
				<td>
					<?php if($fee["paydate"]) echo str_pad($fee["sr_no"], 4, '0', STR_PAD_LEFT); else echo '-'; ?>
				</td>
				<td></td>
				<td class="thead-fee">Date:</td>
				<td>
					<?php if($fee["paydate"]) echo date('d-m-Y', strtotime($fee["paydate"])); else echo '-'; ?>
				</td>
			</tr>
			<tr>
				<td class="thead-fee">Status:</td>
				<td>
					<?php if($fee["paydate"]) echo 'Paid'; else echo 'Unpaid'; ?>
				</td>
				<td></td>
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
				<td></td>
				<td class="thead-fee">Due Date:</td>
				<td>
					<?php echo $fine["fee_fine_after"].date("-m-Y") ?>
				</td>
			</tr>
			<tr>
				<td colspan="5" style="padding: 15px 5px 5px;">
					<table style="border: 3px solid black">
						<tr>
							<td class="thead-fee2">Admission Fee</td>
							<td class="tdata-fee2">
								<?php echo $admfee; ?>
							</td>
						</tr>
						<tr>
							<td class="thead-fee2">Tuition Fee</td>
							<td class="tdata-fee2">
								<?php echo $data["cls_fee"] ?>
							</td>
						</tr>
						<tr>
							<td class="thead-fee2">Examination Fee</td>
							<td class="tdata-fee2">
								<?php echo $exam["ex_fee"] ?>
							</td>
						</tr>
						<tr>
							<td class="thead-fee2">Absence Fine
								<?php if ($absentfine) echo '( '.$atd["absent"].' days x '.$fine["atd_fine_amnt"].' )'; ?>
							</td>
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
							<td class="thead-fee2">Late Payment Fine
								<?php if($latefeefine) echo '( '.$dayslate.' days x '.$fine["fee_fine_amnt"] .' )'; ?>
							</td>
							<td class="tdata-fee2">
								<?php echo $latefeefine; ?>
							</td>
						</tr>
						<tr>
							<td class="thead-fee2">Adjustment / Miscellaneous</td>
							<td class="tdata-fee2">
								<?php echo $misc; ?>
							</td>
						</tr>
						<tr>
							<th class="thead-fee2" style="border-top: 3px solid black">Total</th>
							<th class="tdata-fee2" style="border-top: 3px solid black">
								<?php echo $total; ?>
							</th>
						</tr>
					</table>
					<small><i>* Above values are shown in PKR</i></small>
				</td>
			</tr>
		</table>
	</div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/bottom-section.php'); ?>
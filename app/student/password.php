<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/s-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/std-left.php');


$opErr = $npErr = '';
if ( isset( $_POST[ "chpass" ] ) ) {
	$opass = $_POST[ "curpass" ];
	$npass = $_POST[ "newpass" ];
	if ( $opass != '' && $npass != '' ) {
		$rp = $conn->query( "SELECT passwd FROM login WHERE u_id='" . $_SESSION[ "u_id" ] . "' AND u_type=1 LIMIT 1" )->fetch_assoc();
		if ( $rp[ "passwd" ] == $opass ) {
			if ( strlen( $npass ) < 5 || strlen( $npass ) > 16 )
				$npErr = '* Password must be between 5-16 characters.';
			else {
				$conn->query( "UPDATE login SET passwd='" . $npass . "' WHERE u_id='" . $_SESSION[ "u_id" ] . "' AND u_type=1" );
				$npErr = 'Password Updated Successfully.';
			}
		} else
			$opErr = '* Incorrect Password.';
	} else
		$npErr = '* Both fields are required.';

}

?>

<div class="rightcolumn">
	<div class="card" style="padding: 25px 100px 100px;">
		<center>
			<h1>Change Password</h1>
		</center>
		<form action="" method="post">
			<b>Current Password:</b><br>
			<input type="password" name="curpass" placeholder="Current Password">
			<small class="err">
				<font color="red">
					<?php echo $opErr; ?>
				</font>
			</small><br><br>
			<b>New Password:</b><br>
			<input type="password" name="newpass" placeholder="New Password">
			<small class="err">
				<font color="red">
					<?php echo $npErr; ?>
				</font>
			</small><br><br>

			<input type="submit" name="chpass" value="Change Password">
		</form>

	</div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/bottom-section.php'); ?>
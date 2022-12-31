<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-left.php');

if ( !isset( $_GET[ "id" ] ) || $_GET[ "id" ] == '' )
	header( "Location: /admin/" );

if ( !isset( $_GET[ "type" ] ) || $_GET[ "type" ] == '' )
	header( "Location: /admin/" );

if ( $_GET[ "type" ] == 0 && $_GET[ "id" ] == 0 )
	header( "Location: /admin/" );

$u_type = $_GET[ "type" ];
$u_id = $_GET[ "id" ];

$uname = $conn->query("SELECT username FROM login WHERE u_id=$u_id AND u_type=$u_type");
if ($uname->num_rows == 0)
	header("Location: /admin/");
$uname = $uname->fetch_assoc();
$uname = $uname["username"];

if (isset ($_GET["yes"]))
{
	switch ($u_type)
	{
		case 1:
			$default = $conn->query("SELECT value FROM controlVars WHERE var='default_pass_s'")->fetch_assoc();
			break;
		case 2:
			$default = $conn->query("SELECT value FROM controlVars WHERE var='default_pass_t'")->fetch_assoc();
			break;
		default:
			$default = $conn->query("SELECT value FROM controlVars WHERE var='default_pass_a'")->fetch_assoc();
	}
	$default = $default["value"];
	$conn->query("UPDATE login SET access_token=NULL, passwd='$default' WHERE u_id=$u_id AND u_type=$u_type");
}

?>

			<div class="rightcolumn">
				<div class="card" style="padding: 25px 100px 100px;">
					<center><h1>Reset Password</h1></center>
					<?php 
					if (isset ($_GET["yes"]))
						echo '<br><i>Password reset successful. New password for <b>'.$uname.'</b> is <b>'.$default.'</b>.</i>';
					else
						echo 'Are you sure you want to reset password for <i><b>'.$uname.'</b></i>?
					<form method="get">
						<input type="hidden" name="id" value="'.$u_id.'">
						<input type="hidden" name="type" value="'.$u_type.'">
						<input type="submit" name="yes" value="Reset Password">
					</form>';
					?>
				</div>
			</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/bottom-section.php'); ?>
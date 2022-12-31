<?php

function myRedirect($u_type)
{
	switch ( $u_type ) {
		case 1:
			header( "Location: /student" );
			break;
		case 2:
			header( "Location: /teacher" );
			break;
		default:
			header( "Location: /admin" );
	}
	die();
}

session_start();
$conn = new mysqli( $db_host, $db_user, $db_pass, $db )or die( "Connect failed: %s\n" . $conn->error );
$controlVars = $u_id = $u_type = $username = '';
if ( isset( $_SESSION[ "u_type" ] ) ) {
	if ( $_SESSION[ "u_type" ] != 2 )
		myRedirect( $_SESSION[ "u_type" ] );
	$u_id = $_SESSION[ "u_id" ];
	$u_type = $_SESSION[ "u_type" ];
	$username = $_SESSION[ "username" ];
} else {
	header( "Location: /" );
	die();
}

if ( isset( $_POST[ "logout" ] ) ) {
	setcookie( "access_token", "", time() - 3600 );
	$conn->query( "UPDATE login SET access_token=NULL WHERE u_id='" . $u_id . "' AND u_type=2" );
	session_unset();
	session_destroy();
	header( "Location: /" );
	die();
}
?>

<!DOCTYPE html>

<head>
	<title>SMS -
		<?php echo $school_name; ?>
	</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="<?php echo $style_sheet; ?>"/>
	<link rel="shortcut icon" href="<?php echo $favicon; ?>" </head>

	<body>

		<div class="header">
			<div class="header-logo">
				<img src="<?php echo $school_logo; ?>" widtd="100" height="100" title="<?php echo $school_name ?>" alt="School Logo"/>
			</div>
			<div class="header-text">
				<h1>
					<?php echo $school_name ?>
				</h1>
			</div>
		</div>
		<div class="topnav">

			<div style="width: 20%; float:right">
				<form method="post" name="logoutForm"><input type="hidden" name="logout" value="true"/>
					<a style="float:right" href="#" onClick="logoutForm.submit()">Logout</a>
				</form>
				<p style="float: right; color: #f2f2f2; border-right: 1px solid #f2f2f2;">&nbsp;&nbsp; Logged in as
					<font color="#F2E4BE">
						<?php echo $username; ?>
					</font>
					&nbsp;&nbsp;</p>
			</div>

			<div style="float: right; color: #f2f2f2; border-right: 1px solid #f2f2f2; width: 80%;">
				<marquee onMouseOver="this.stop();" onMouseOut="this.start();">
					<p class="marquee">
						<?php $nav_news = $conn->query("SELECT value FROM controlVars WHERE var='nav_news'")->fetch_assoc(); echo $nav_news["value"]; ?>
					</p>
				</marquee>
			</div>

		</div>


		<div class="row">
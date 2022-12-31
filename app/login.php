<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');

function getToken() {
	$str = "";
	for ( $i = 0; $i < 15; $i++ ) {
		$sel = mt_rand( 0, 2 );
		switch ( $sel ) {
			case 1:
				$str .= chr( mt_rand( 97, 122 ) );
				break;
			case 2:
				$str .= chr( mt_rand( 65, 90 ) );
				break;
			default:
				$str .= chr( mt_rand( 48, 57 ) );
		}
	}
	return $str;
}

function myRedirect( $u_type ) {
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
$conn = new mysqli( $db_host, $db_user, $db_pass, $db )or die( "Connection failed: %s\n" . $conn->error );
$user = $pass = $userErr = $passErr = $access_token = "";

if ( isset( $_SESSION[ "u_type" ] ) ) {
	myRedirect( $_SESSION[ "u_type" ] );
} else if ( isset( $_COOKIE[ "access_token" ] ) ) {
	$access_token = $_COOKIE[ "access_token" ];

	$sql = "SELECT * FROM login WHERE access_token='" . $access_token . "' LIMIT 1";

	$data = $conn->query( $sql )->fetch_assoc();

	$_SESSION[ "u_id" ] = $data[ "u_id" ];
	$_SESSION[ "u_type" ] = $data[ "u_type" ];
	$_SESSION[ "username" ] = $data[ "username" ];

	myRedirect( $_SESSION[ "u_type" ] );

} else if ( isset( $_POST[ "login" ] ) ) {

	$user = $_POST[ "user" ];
	if ( $user == "" )
		$userErr = "* Username is required.";
	$pass = $_POST[ "pass" ];
	if ( $pass == "" )
		$passErr = "* Password is required.";

	if ( $user != "" && $pass != "" ) {
		$data = $conn->prepare( "SELECT * FROM login WHERE username=? AND passwd=? LIMIT 1" );
		$data->bind_param( 'ss', $user, $pass );
		$data->execute();
		$data = $data->get_result();
		//$sql = "SELECT * FROM login WHERE username='" . $user . "' AND passwd='" . $pass . "' LIMIT 1";
		//$data = $conn->query( $sql );

		if ( $data->num_rows > 0 ) {
			$data = $data->fetch_assoc();
			$access_token = getToken();
			if ( isset( $_POST[ "remember" ] ) )
				setcookie( "access_token", $access_token, time() + ( 86400 * 30 ), "/" );
			$sql = "UPDATE login SET access_token='" . $access_token . "' WHERE u_id='" . $data[ "u_id" ] . "' AND u_type='" . $data[ "u_type" ] . "'";
			$conn->query( $sql );

			$_SESSION[ "u_id" ] = $data[ "u_id" ];
			$_SESSION[ "u_type" ] = $data[ "u_type" ];
			$_SESSION[ "username" ] = $data[ "username" ];

			myRedirect( $_SESSION[ "u_type" ] );
		} else
			$passErr = "*incorrect credentials";

	}
}
?>
<!DOCTYPE html>

<head>
	<title>SMS - <?php echo $school_name; ?></title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="<?php echo $style_sheet; ?>"/>
	<link rel="shortcut icon" href="<?php echo $favicon; ?>" </head>
	<!--<script>
		function normalBg( bdy ) {
			bdy.style = "background: url(images/bg.png) no-repeat center center fixed; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;";
		}

		function darkBg( bdy ) {
			bdy.style = "background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(images/bg.png) no-repeat center center fixed; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;";
		}
	</script>-->

	<body style="background: url(images/bg.png) no-repeat center center fixed; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;" onMouseOut="" onMouseOver="">

		<center>
			<div class="row">
				<img src="<?php echo $school_logo; ?>" width="100" height="100" title="<?php echo $school_name ?>" alt="School Logo"/>
				<h1 style="color: white; text-shadow: 3px 3px 0px #111;">
					<?php echo $school_name; ?>
				</h1>
				<div class="card" style="border: 1px solid black; border-radius: 10px; width: 300px; height: 200px;">
					<form action="" method="post">
						<table class="loginTable">
							<tr>
								<td><b>Username:</b>
								</td>
								<td>
									<input type="text" value="<?php echo $user; ?>" name="user" placeholder="Username">
									<small class="err">
										<font color="red">
											<?php echo $userErr; ?>
										</font>
									</small>
								</td>
							</tr>
							<tr>
								<td><b>Password:</b>
								</td>
								<td>
									<input type="password" name="pass" placeholder="Password">
									<small class="err">
										<font color="red">
											<?php echo $passErr; ?>
										</font>
									</small>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<input type="checkbox" name="remember"> Remember me <input type="submit" name="login" value="Login">
								</td>
							</tr>
						</table>


					</form>
				</div>
			</div>
		</center>

	</body>

	</html>
	<?php  $conn->close(); ?>
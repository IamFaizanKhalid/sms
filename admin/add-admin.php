<?php
include($_SERVER['DOCUMENT_ROOT'].'/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'].'/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'].'/includes/a-left.php');

if ( $_SESSION[ "u_id" ] != 0)
		header("Location: /admin");

$username = $err = '';

if ( isset( $_POST[ "save" ] ) )
{
	$username = $_POST["uname"];
	if ($username == '')
		$err="* This field is required.";
	else if (!preg_match('/^[a-zA-Z]+[a-zA-Z_]*[0-9]*$/', $username))
		$err ='* Username must start with an English alphabet,<br>&nbsp;&nbsp;may contian an underscore \'_\'<br>&nbsp;&nbsp;and may conatain a number at the end.';
	else
	{
		if ($conn->query("SELECT u_id FROM login WHERE u_type=0 AND username='$username' LIMIT 1")->num_rows > 0)
			$err = "* Username '$username' already exist.";
		else{
			$lastid = $conn->query("SELECT MAX(u_id)+1 AS u_id FROM login WHERE u_type=0")->fetch_assoc();
			$lastid = $lastid["u_id"];
			$conn->query("INSERT INTO login VALUES($lastid, 0, '$username', (SELECT value FROM controlVars WHERE var='default_pass_a'), NULL)");
			header("Location: admins");
		}
	}
}

?>
			<div class="rightcolumn">
				<div class="card" style="padding: 25px 100px 100px;">
					<center>
						<h1>Add Admin</h1>
					</center>
					<br>
					<form method="post">
						Username:
						<input type="text" name="uname" value="<?php echo $username; ?>">
						<small><i><font color="red"><?php echo $err; ?></font></i></small>
						<input type="hidden" name="a_id" value="<?php echo $a_id; ?>"><br><br>
						<input type="submit" name="save" value="Save"><br>
					</form>
				</div>
			</div>


<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/bottom-section.php'); ?>
<?php
include($_SERVER['DOCUMENT_ROOT'].'/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'].'/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'].'/includes/a-left.php');

if ( $_SESSION[ "u_id" ] != 0)
		header("Location: /admin");

$a_id = $username = $err = '';

if ( isset( $_POST[ "save" ] ) )
{
	$a_id = $_POST["a_id"];
	$username = $_POST["uname"];
	if ($username == '')
		$err="* This field is required.";
	else if (!preg_match('/^[a-zA-Z]+[a-zA-Z_]*[0-9]*$/', $username))
		$err ='* Username must start with an English alphabet,<br>&nbsp;&nbsp;may contian an underscore \'_\'<br>&nbsp;&nbsp;and may conatain a number at the end.';
	else
	{
		if ($conn->query("SELECT u_id FROM login WHERE u_type=0 AND u_id<>$a_id AND username='$username' LIMIT 1")->num_rows > 0)
			$err = "* Username '$username' already exist.";
		else{
			$conn->query("UPDATE login SET username='$username' WHERE u_type=0 AND u_id=$a_id");
			header("Location: admins");
		}
	}
}
else if (isset($_GET["id"]) && $_GET["id"]!=''){
	$a_id = $_GET["id"];
	$data = $conn->query("SELECT username FROM login WHERE u_type=0 AND u_id=$a_id LIMIT 1")->fetch_assoc();
	$username = $data["username"];
}

?>
			<div class="rightcolumn">
				<div class="card" style="padding: 25px 100px 100px;">
					<center>
						<h1>Edit Admin</h1>
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
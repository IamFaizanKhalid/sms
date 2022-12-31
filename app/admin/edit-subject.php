<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-left.php');

if (!isset($_GET["id"]))
	header( "Location: subjects" );
if ($_GET["id"]=='')
	header( "Location: subjects" );
$sub_id = $_GET["id"];
$err_name = '';

if ( isset( $_POST[ "save" ] ) ) {
		$sub_name = $_POST[ "sub_name" ];
	if ( $sub_name == '' )
		$err_name = '<br><small><font color="red">This field is required.</font></small>';
	else {
		$conn->query( "UPDATE subject SET sub_name='" . $sub_name . "' WHERE sub_id=".$sub_id );
		header( "Location: subjects" );
	}
}
else
{
	$sub_name = $conn->query("SELECT * FROM subject WHERE sub_id=".$sub_id)->fetch_assoc();
	$sub_name =$sub_name["sub_name"];
}


?><script>x=2;</script>
			<div class="rightcolumn">
				<div class="card" style="padding: 25pclassinfo 100pclassinfo 100pclassinfo;">
					<center>
						<h1>Edit Subject</h1>
					</center>
					<form method="post">
					<fieldset id="newsub">
							<legend>Subject Name</legend>
							<input name="sub_name" type="text" value="<?php echo $sub_name; ?>">
								<?php echo $err_name; ?>
					</fieldset><br>
					<input name="save" type="submit" value="Save">
						</form>
				</div>
			</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/bottom-section.php'); ?>
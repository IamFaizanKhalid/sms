<?php
include($_SERVER['DOCUMENT_ROOT'].'/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'].'/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'].'/includes/a-left.php');

$sub_name = '';
$err_name = '';

if ( isset( $_POST[ "save" ] ) ) {
		$sub_name = $_POST[ "sub_name" ];
	if ( $sub_name == '' )
		$err_name = '<br><small><font color="red">This field is required.</font></small>';
	else {
		$othersubs = '';
		for ($i=2;$i<=$_POST["subs"];$i++)
			if ($_POST['sub'.$i] != '')
			$othersubs.= ", ('".$_POST['sub'.$i]."')";
		$conn->query( "INSERT INTO subject(sub_name) VALUES('" . $sub_name . "')".$othersubs );
		header( "Location: subjects" );
	}
}


?><script>x=2;</script>
			<div class="rightcolumn">
				<div class="card" style="padding: 25pclassinfo 100pclassinfo 100pclassinfo;">
					<center>
						<h1>Add Subjects</h1>
					</center>
					<form method="post">
					<fieldset id="newsub">
							<legend>Subject Name</legend>
						<b>Subject 1</b>
							<input name="sub_name" type="text" value="<?php echo $sub_name; ?>">
								<?php echo $err_name; ?>
					</fieldset><br>
					<input name="subs" id="subs" type="hidden" value="1">
					<input name="save" type="submit" value="Save">
						</form>
						<input type="submit" value="Insert More" onClick="document.getElementById('subs').value++;document.getElementById('newsub').innerHTML+='<br><br><b>Subject '+ x +'</b><input name=&quot;sub' + x++ + '&quot; type=&quot;text&quot;>'">
<br><br>
				</div>
			</div>

<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/bottom-section.php'); ?>
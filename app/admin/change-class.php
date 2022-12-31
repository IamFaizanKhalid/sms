<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-left.php');

$from_id = $to_id = -1;
$err= '';
if (isset($_POST["chng"]))
{
	$from_id = $_POST["from_id"];
	$to_id = $_POST["to_id"];
	if ($from_id < 0 || $to_id < 0)
		$err = 'Please select both feilds.';
	else if ($from_id == $to_id)
		$err = 'You can\'t move to same class.';
	else
	{
		$conn->query("UPDATE student SET cls_id='".$to_id."' WHERE cls_id='".$from_id."'");
		$err = 'Moved Successfully.';	
	}
}

$classes = $conn->query("SELECT c.cls_id, c.cls_name, c.cls_section, IFNULL(s.num_std, 0) num_std FROM classes c LEFT JOIN (SELECT cls_id, COUNT(*) num_std FROM student GROUP BY cls_id) s ON s.cls_id=c.cls_id ORDER BY c.cls_name, c.cls_section");
?>
			<div class="rightcolumn">
				<div class="card" style="padding: 25px 100px 100px;">
					<center>
						<h1>Move Students</h1>
					</center>
					<form method="post" id="moveform">
						From Class: 
						<select name="from_id" id="from_id">
							<?php if ( $from_id < 0 ) echo'<option value="-1" selected></option>';
							if($classes->num_rows > 0) while ($x = $classes->fetch_assoc())
	if ($from_id == $x["cls_id"])
								echo '<option value="'.$x["cls_id"].'" selected>'.$x["cls_name"].' ('.$x["cls_section"].')</option>';
							else
								echo '<option value="'.$x["cls_id"].'">'.$x["cls_name"].' ('.$x["cls_section"].')</option>';
 ?>
						</select><br><br>
						To Class: 
						<select name="to_id" id="to_id">
							<?php $classes->data_seek(0);
							if ( $to_id < 0 ) echo'<option value="-1" selected></option>';
							if($classes->num_rows > 0) while ($x = $classes->fetch_assoc())
								if ($x["num_std"] == 0)
	if ($to_id == $x["cls_id"])
								echo '<option value="'.$x["cls_id"].'" selected>'.$x["cls_name"].' ('.$x["cls_section"].')</option>';
							else
								echo '<option value="'.$x["cls_id"].'">'.$x["cls_name"].' ('.$x["cls_section"].')</option>';
 ?>
						</select>
						<small><i>* There should be no student in target class.</i></small><br><br>
						<input type="hidden" id="chng" name="chng" value="Confirm Move">
					</form><br><small><font color="red"><?php echo $err; ?></font></small>
					<input type="submit" onClick="myFun()" value="Move">
				</div>
			</div>
<script>
	function myFun()
	{
		var text = 'Use with Caution..!\n\nThis will move all the students from ';
		var sel = document.getElementById("from_id");
		text += sel.options[sel.selectedIndex].text;
		text += ' to ';
		sel = document.getElementById("to_id");
		text += sel.options[sel.selectedIndex].text;
		text += '.';
		if(confirm(text))
			document.getElementById("moveform").submit();
	}

</script>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/bottom-section.php'); ?>
<?php
include($_SERVER['DOCUMENT_ROOT'].'/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'].'/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'].'/includes/a-left.php');

$msg = '';

if (isset($_POST["update-settings"]))
{
	
	$sql = "UPDATE controlVars SET value = CASE var";
	
	if (!$_SESSION["u_type"])
		$sql.= "
			WHEN 'default_pass_a' THEN '".$_POST["default_pass_a"]."'
		";
	
	$sql.=" WHEN 'admission_fee' THEN '".$_POST["admission_fee"]."' 
            WHEN 'atd_fine' THEN '".((isset($_POST["atd_fine"]))?1:0)."' 
            WHEN 'atd_fine_amnt' THEN '".$_POST["atd_fine_amnt"]."' 
			WHEN 'fee_fine_after' THEN '".$_POST["fee_fine_after"]."' 
            WHEN 'fee_fine_amnt' THEN '".$_POST["fee_fine_amnt"]."' 
            WHEN 'fee_fine_daily' THEN '".((isset($_POST["fee_fine_daily"]))?1:0)."' 
            WHEN 'nav_news' THEN '".$_POST["nav_news"]."' 
            WHEN 'passing_marks' THEN '".$_POST["passing_marks"]."'
            WHEN 'term_start_month' THEN '".$_POST["term_start_month"]."' 
            WHEN 't_a_fine' THEN '".((isset($_POST["t_a_fine"]))?1:0)."' 
            WHEN 't_a_fine_amnt' THEN '".$_POST["t_a_fine_amnt"]."'  
            WHEN 'default_pass_t' THEN '".$_POST["default_pass_t"]."' 
            WHEN 'default_pass_s' THEN '".$_POST["default_pass_s"]."' 
            END
			WHERE var IN(";
	if (!$_SESSION["u_type"])
		$sql.=" 'default_pass_a',";
	
	$sql.=" 'admission_fee', 'atd_fine', 'atd_fine_amnt', 'fee_fine_after', 'fee_fine_amnt', 'fee_fine_daily', 'nav_news', 'passing_marks', 'term_start_month', 't_a_fine', 't_a_fine_amnt', 'default_pass_t', 'default_pass_s')";
	//echo $sql;
	if ($conn->query($sql))
		$msg = "<b>Settings Updated.</b>";
	else
		$msg = "<font color='red'>Error Saving Settings..</font>";
	
	/*
	if (!$_SESSION["u_type"])
		$sql.= "WHEN 'default_pass_a' THEN '".$_POST["default_pass_a"]."'";
	*//*
	$conn->query("UPDATE controlVars
   SET value = CASE var 
                      WHEN 'admission_fee' THEN '".$_POST["admission_fee"]."' 
                      WHEN 'atd_fine' THEN '".((isset($_POST["atd_fine"]))?1:0)."' 
                      WHEN 'atd_fine_amnt' THEN '".$_POST["atd_fine_amnt"]."' 
                      WHEN 'fee_fine_after' THEN '".$_POST["fee_fine_after"]."' 
                      WHEN 'fee_fine_amnt' THEN '".$_POST["fee_fine_amnt"]."' 
                      WHEN 'fee_fine_daily' THEN '".((isset($_POST["fee_fine_daily"]))?1:0)."' 
                      WHEN 'nav_news' THEN '".$_POST["nav_news"]."' 
                      WHEN 'passing_marks' THEN '".$_POST["passing_marks"]."'
                      WHEN 'term_start_month' THEN '".$_POST["term_start_month"]."' 
                      WHEN 't_a_fine' THEN '".((isset($_POST["t_a_fine"]))?1:0)."' 
                      WHEN 't_a_fine_amnt' THEN '".$_POST["t_a_fine_amnt"]."'  
                      WHEN 'default_pass_t' THEN '".$_POST["default_pass_t"]."' 
                      WHEN 'default_pass_s' THEN '".$_POST["default_pass_s"]."' 
                      END
 WHERE var IN('admission_fee', 'atd_fine', 'atd_fine_amnt', 'fee_fine_after', 'fee_fine_amnt', 'fee_fine_daily', 'nav_news', 'passing_marks', 'term_start_month', 't_a_fine', 't_a_fine_amnt', 'default_pass_t', 'default_pass_a', 'default_pass_s')");
	*/
}

$controlVars = $conn->query( "SELECT * FROM controlVars" );
$settings = array();
while($row = $controlVars->fetch_assoc())
	$settings[$row['var']] = $row['value'];
echo '<script>document.getElementsByClassName("marquee")[0].innerHTML="'.$settings["nav_news"].'"; </script>';
?>
			<div class="rightcolumn">
				<div class="card" style="padding: 25px 100px 100px;">
					<center>
						<h1>Settings</h1>
					</center>
					<small><i><?php echo $msg; ?></i></small>
					<form method="post">
						<input type="submit" name="update-settings" value="Save"><br><br><br>
						<fieldset>
							<legend> Admission </legend>
							Admission Fee: <input name="admission_fee" type="number" value="<?php echo $settings["admission_fee"]; ?>" >
						</fieldset><br>
						<fieldset>
							<legend> Teacher Attendance </legend>
							Absence Fine: <input name="t_a_fine" type="checkbox" <?php if ($settings["t_a_fine"]) echo 'checked'; ?>><br>
							Amount: <input name="t_a_fine_amnt" type="number" value="<?php echo $settings["t_a_fine_amnt"]; ?>" >
						</fieldset><br>
						<fieldset>
							<legend> Student Attendance </legend>
							Absence Fine: <input name="atd_fine" type="checkbox" <?php if ($settings["atd_fine"]) echo 'checked'; ?>><br>
							Amount: <input name="atd_fine_amnt" type="number" value="<?php echo $settings["atd_fine_amnt"]; ?>" >
						</fieldset><br>
						<fieldset>
							<legend> Late Fee Surcharge </legend>
							Charge After: <input name="fee_fine_after" type="number" min="5" max="20" value="<?php echo $settings["fee_fine_after"]; ?>" ><br>
							Amount: <input name="fee_fine_amnt" type="number" value="<?php echo $settings["fee_fine_amnt"]; ?>" >
							Daily: <input name="fee_fine_daily" type="checkbox" <?php if ($settings["fee_fine_daily"]) echo 'checked'; ?>>
						</fieldset><br>
						<fieldset>
							<legend> Announcement </legend>
							<input name="nav_news" type="text" value="<?php echo $settings["nav_news"]; ?>" >
						</fieldset><br>
						<fieldset>
							<legend> Examination </legend>
							Passing Percentage: <input name="passing_marks" type="number" min="20" max="80" value="<?php echo $settings["passing_marks"]; ?>" >
						</fieldset><br>
						<fieldset>
							<legend> Default Passwords </legend>
							Students: <input name="default_pass_s" type="text" value="<?php echo $settings["default_pass_s"]; ?>" ><br><br>
							Teachers: <input name="default_pass_t" type="text" value="<?php echo $settings["default_pass_t"]; ?>" ><br><br>
						<?php if (!$_SESSION["u_type"]) echo 'Admins: <input name="default_pass_a" type="text" value="'.$settings["default_pass_a"].'" ><br><br>'; ?>
						</fieldset><br>
						<fieldset>
							<legend> Session </legend>
							Begins:
						<select name="term_start_month">
							<?php
							for ($i=1;$i<=12;$i++)
							{
							echo '
							<option value="'.$i.'" ';
							if ($settings["term_start_month"]==$i) echo 'selected';
							echo '>'.date('F', mktime(0, 0, 0, $i, 10)).'</option>';
							}
							?>
						</select>
						</fieldset><br>
						<input type="submit" name="update-settings" value="Save">
					</form>
					
				</div>
			</div>


<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/bottom-section.php'); ?>
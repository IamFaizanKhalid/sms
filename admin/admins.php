<?php
include($_SERVER['DOCUMENT_ROOT'].'/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'].'/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'].'/includes/a-left.php');

if ( $u_id != 0)
		header("Location: /admin");

if ( isset( $_POST[ "dela" ] ) )
{
	$temp = $conn->query("SELECT u_id FROM login WHERE u_type=0");
	$ex = '';
	while ($x = $temp->fetch_assoc())
		if (isset($_POST[$x["u_id"]]))
			$ex.=", '".$x["u_id"]."'";
	if ($ex != '')
		$conn->query("DELETE FROM login WHERE u_id IN( '-1'".$ex." )");
}

$std = $conn->query("SELECT u_id, username FROM login WHERE u_type=0 AND u_id <> 0 ORDER BY u_id ASC");

?>
			<div class="rightcolumn">
				<div class="card" style="padding: 25px 100px 100px;">
					<center>
						<h1>Admins</h1>
					</center>
					<input name="sname" type="text" id="myInput" onkeyup="searchColumn(2)" placeholder="Search by name.."><br><br>
					<a href="add-admin"><input type="submit" value="Add Admin"></a><br><br>
					<form method="post" id="delform">
					<?php
						if ( $std->num_rows > 0 ){
							echo'
					<table  id="myTable" border="1">
						<tr>
							<th><input type="checkbox" onClick="toggleCheckBox(this)"></th>
							<th onclick="sortColumn(1)" style="cursor:pointer;">&#x21C5; ID</th>
							<th onclick="sortColumn(2)" style="cursor:pointer;">&#x21C5; Username</th>
							<th>Password</th>
						</tr>';
						 while ($x = $std->fetch_assoc())
						 {
							 echo '
						<tr>
							<td><input type="checkbox" name="'.$x["u_id"].'"></td>
							<td>'.$x["u_id"].'</td>
							<td><a href="edit-admin?id='.$x["u_id"].'">'.$x["username"].'</a></td>
							<td><a href="reset-password?id=' . $x[ "u_id" ] . '&type=0">Reset Password</a></td>
						</tr>';
						 }if ( $std->num_rows > 0 )
					echo '</table><input type="hidden" name="dela">';
						}
						?>
					</form>
			<input onClick="if(confirm('Warning!\nSelected admins will be removed.')) document.getElementById('delform').submit();" type="submit" value="Delete"><br>
				</div>
			</div>


<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/bottom-section.php'); ?>
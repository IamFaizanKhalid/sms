<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/globalVars.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-top-section.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/a-left.php');


$sal = $conn->query("SELECT t.t_id, t.name, t.sal, IFNULL(a.absent,'0') absent FROM teacher t LEFT JOIN t_atd a ON a.t_id=t.t_id AND a.year='".date("Y", strtotime("last month"))."' AND a.month='".date("m", strtotime("last month"))."'");


$max = $conn->query("SELECT SUM(sal) AS sal FROM teacher")->fetch_assoc();
$max = $max["sal"];


?>
			<div class="rightcolumn">
				<div class="card" style="padding: 25px 100px 100px;">
					<center>
						<h1>Teachers' Salary</h1>
					</center>
					<font size="+1"><b>Total Salary of All Teachers:</b> <?php echo $max; ?></font><br><br>
					<input name="sname" type="text" id="myInput" onkeyup="searchColumn(2)" placeholder="Search by name.."><br><br><br>
					<?php
						if ( $sal->num_rows > 0 ){
							echo'
					<table  id="myTable" border="1">
						<tr>
							<th><input type="checkbox" onClick="toggleCheckBox(this)"></th>
							<th>Sr. No.</th>
							<th onclick="sortColumn(2)" style="cursor:pointer;">&#x21C5; Name</th>
							<th onclick="sortNumColumn(3)" style="cursor:pointer;">&#x21C5; Salary</th>
							<th onclick="sortNumColumn(4)" style="cursor:pointer;">&#x21C5; Absents</th>
							<th>Detail</th>
						</tr>';
						 while ($x = $sal->fetch_assoc())
						 {
							 echo '
						<tr>
							<td><input type="checkbox"></td>
							<td class="counterCell"></td>
							<td>'.$x["name"].'</td>
							<td>'.$x["sal"].'</td>
							<td>'.$x["absent"].'</td>
							<td><a href="salary-slip?id='.$x["t_id"].'">Get Salary Slip</a></td>
						</tr>';
						 }
					echo '</table>';
						}
						?>
					
				</div>
			</div>


<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/bottom-section.php'); ?>
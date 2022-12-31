<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/globalVars.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/a-top-section.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/a-left.php' );


?>

<div class="rightcolumn">
	<center>
		<h1>Admin Panel</h1>
	</center>
	<div class="card" style="display: flex;">
		<fieldset class="card" style="background-color:#F0F0F0; flex: 1; border: 1px solid black; border-radius: 5px; margin: 10px; line-height: 1.7em;">
			<legend>
				<h3>Management</h3>
			</legend>
			<ul style="list-style-type:square;">
				<li><a href="students">Students</a>
				</li>
				<li><a href="teachers">Teachers</a>
				</li>
				<?php if (!$_SESSION["u_id"]) echo '<li><a href="admins">Admins</a></li>'; ?>
				<li><a href="classes">Classes</a>
				</li>
				<li><a href="subjects">Subjects</a>
				</li>
			</ul>

		</fieldset>
		<fieldset class="card" style="background-color:#F0F0F0; flex: 1; border: 1px solid black; border-radius: 5px; margin: 10px; line-height: 1.7em;">
			<legend>
				<h3>Attendance</h3>
			</legend>
			<ul style="list-style-type:square;">
				<li><a href="students-attendance">Students' Attendance</a>
				</li>
				<li><a href="student-leave">Student Leave</a>
				</li>
				<li><a href="teachers-attendance">Teachers' Attendance</a>
				</li>
				<li><a href="teacher-leave">Teacher Leave</a>
				</li>
				<li><a href="delete-old-attendance">Delete Old Attendance</a>
				</li>
			</ul>

		</fieldset>
		<fieldset class="card" style="background-color:#F0F0F0; flex: 1; border: 1px solid black; border-radius: 5px; margin: 10px; line-height: 1.7em;">
			<legend>
				<h3>Examination</h3>
			</legend>
			<ul style="list-style-type:square;">
				<li><a href="exams">Exams</a>
				</li>
				<li><a href="results">Results</a>
				</li>
			</ul>

		</fieldset>
	</div>
	<div class="card" style="display: flex;">
		<fieldset class="card" style="background-color:#F0F0F0; flex: 1; border: 1px solid black; border-radius: 5px; margin: 10px; line-height: 1.7em;">
			<legend>
				<h3>Finance</h3>
			</legend>
			<ul style="list-style-type:square;">
				<li><a href="fee">Students' Fee</a>
				</li>
				<li><a href="salary">Teachers' Salary</a>
				</li>
			</ul>

		</fieldset>
		<fieldset class="card" style="background-color:#F0F0F0; flex: 1; border: 1px solid black; border-radius: 5px; margin: 10px; line-height: 1.7em;">
			<legend>
				<h3>Advance</h3>
			</legend>
			<ul style="list-style-type:square;">
				<li><a href="change-class">Move Students to Other Class</a>
				</li>
				<li><a href="settings">More Settings</a>
				</li>
				<li><a href="password">Change Password</a>
				</li>
			</ul>

		</fieldset>
		<fieldset class="card" style="flex: 1; border: none;">

		</fieldset>
	</div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/bottom-section.php'); ?>
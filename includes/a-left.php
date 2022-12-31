<style>
	
.home {
  padding: 6px 8px 6px 16px;
  text-decoration: none;
  font-size: 20px;
  display: block;
  border: none;
  width:100%;
  text-align: left;
  cursor: pointer;
  outline: none;
  background-color: #ddd;
	color: #555;
}

.home:hover {
  background-color: #555;
  color: #fff;
}
	
/* Style the sidenav links and the dropdown button */
.dropdown-btn {
  padding: 6px 8px 6px 16px;
  text-decoration: none;
  font-size: 20px;
  display: block;
  border: none;
  width:100%;
  text-align: left;
  cursor: pointer;
  outline: none;
  background-color: #ddd;
}


/* On mouse-over */
.dropdown-btn:hover {
  background-color:#555;
  color: #fff;
}

/* Dropdown container (hidden by default). Optional: add a lighter background color and some left padding to change the design of the dropdown content */
.dropdown-container {
  display: block;
  padding-left: 8px;
}

/* Optional: Style the caret down icon */
.fa-caret-down {
  float: right;
  padding-right: 8px;
}

</style>
			<div class="leftcolumn">
				<div class="card">
					<div class="sidenav">
						<ul>
							<a class="home" href="./"><li>Home</li></a>
<button class="dropdown-btn">Management
    <i class="fa fa-caret-down"></i>
  </button>
  <div class="dropdown-container">
							<li><a href="students">Students</a>
							</li>
							<li><a href="teachers">Teachers</a>
							</li>
							<?php
	  ob_start();
								if (!$_SESSION["u_id"])
									echo '<li><a href="admins">Admins</a>
							</li>
							';
							?><li><a href="classes">Classes</a>
							</li>
							<li><a href="subjects">Subjects</a>
							</li>
							<li><a href="assign">Assign Teachers</a>
							</li>
  </div>
<button class="dropdown-btn">Attendance
    <i class="fa fa-caret-down"></i>
  </button>
  <div class="dropdown-container">
							<li><a href="students-attendance">Students Attendance</a>
							</li>
							<li><a href="teachers-attendance">Teachers Attendance</a>
							</li>
							<li><a href="student-leave">Student Leave</a>
							</li>
							<li><a href="teacher-leave">Teacher Leave</a>
							</li>
							<li><a href="delete-old-attendance">Delete Old Attendance</a>
							</li>
  </div>
<button class="dropdown-btn">Examination
    <i class="fa fa-caret-down"></i>
  </button>
  <div class="dropdown-container">
							<li><a href="exams">Exams</a>
							</li>
							<li><a href="results">Results</a>
							</li>
  </div>
<button class="dropdown-btn">Finance
    <i class="fa fa-caret-down"></i>
  </button>
  <div class="dropdown-container">
							<li><a href="fee">Students' Fee</a>
							</li>
							<li><a href="salary">Teachers' Salary</a>
							</li>
  </div>
<button class="dropdown-btn">Advance
    <i class="fa fa-caret-down"></i>
  </button>
  <div class="dropdown-container">
							<li><a href="change-class">Advance to Next Class</a>
							</li>
							<li><a href="settings">More Settings</a>
							</li>
							<li><a href="password">Change Password</a>
							</li>
  </div>
						</ul>
					</div>
				</div>
			</div>



<script>
//* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
var dropdown = document.getElementsByClassName("dropdown-btn");
var i;

for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var dropdownContent = this.nextElementSibling;
    if (dropdownContent.style.display === "block") {
      dropdownContent.style.display = "none";
      this.style.background = "#ddd";
      this.style.color = "#555";
    } else {
      dropdownContent.style.display = "block";
      this.style.background = "#555";
      this.style.color = "#fff";
    }
  });
} 
</script>

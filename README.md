# School Management System

## Pre-requisites
- `docker-compose`

## Getting started

- Edit _includes/globalVars.php_ to set different options like school name and database config.
- Run `docker-compose up -d --build` to set up database and Apache HTTP Server.
- Visit http://localhost:8080
- Login with `admin` as the username and the password.
- All set. Enjoy!


### Notes

- These can be added independently:
	`Teachers`, `Admins`, `Subjects`, `Exams`
- Class depends on an in-charge (teacher). 
- Student depends on a class.

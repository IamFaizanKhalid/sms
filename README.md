# School Management System

## Pre-requisites
- php
- docker-compose

## Getting started

- Run `docker-compose up -d` to setup database.
- Edit _includes/globalVars.php_ to set different options like school name and database config.
- Run the project
- Login with `admin` as the username and the password.
- All set. Enjoy!


### Notes

- These can be added independently:
	`Teachers`, `Admins`, `Subjects`, `Exams`
- Classes must have an incharge (teacher). 
- Student must be in a classes.

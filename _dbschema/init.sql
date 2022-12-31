/*DROP DATABASE sms;*/
CREATE DATABASE sms;
USE sms;

CREATE TABLE login (
  u_id int(10) NOT NULL,
  u_type int(2) NOT NULL,
  username varchar(16) NOT NULL,
  passwd varchar(60) NOT NULL,
  access_token varchar(15) DEFAULT NULL,
  PRIMARY KEY (u_id,u_type),
  UNIQUE KEY (username)
);
INSERT INTO login (u_id, u_type, username, passwd) VALUES
(0, 0, 'admin', 'admin');


CREATE TABLE teacher (
  t_id int(10) NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  gender varchar(1) NOT NULL,
  dob date NOT NULL,
  religion varchar(10) DEFAULT NULL,
  nationality varchar(10) DEFAULT NULL,
  blood_group varchar(3) DEFAULT NULL,
  cnic varchar(13) DEFAULT NULL,
  pic varchar(20) DEFAULT NULL,
  address varchar(100) DEFAULT NULL,
  phone1 varchar(11) DEFAULT NULL,
  phone2 varchar(11) DEFAULT NULL,
  added timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  sal int(11) NOT NULL,
  degree varchar(50) DEFAULT NULL,
  married tinyint(1) NOT NULL,
  PRIMARY KEY (t_id)
);


CREATE TABLE classes (
  cls_id int(11) NOT NULL AUTO_INCREMENT,
  cls_name varchar(10) NOT NULL,
  cls_section varchar(5) NOT NULL,
  cls_fee int(11) NOT NULL,
  incharge int(10) NOT NULL,
  PRIMARY KEY (cls_id),
  FOREIGN KEY (incharge) REFERENCES teacher(t_id)
);


CREATE TABLE student (
  std_id int(10) NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  gender varchar(1) NOT NULL,
  dob date NOT NULL,
  religion varchar(10) DEFAULT NULL,
  nationality varchar(10) DEFAULT NULL,
  blood_group varchar(3) DEFAULT NULL,
  cnic varchar(13) DEFAULT NULL,
  pic varchar(20) DEFAULT NULL,
  address varchar(100) DEFAULT NULL,
  phone1 varchar(11) DEFAULT NULL,
  phone2 varchar(11) DEFAULT NULL,
  added timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  rollno varchar(10) NOT NULL,
  father_name varchar(50) DEFAULT NULL,
  father_cnic varchar(13) DEFAULT NULL,
  board_reg_no varchar(15) DEFAULT NULL,
  cls_id int(11),
  PRIMARY KEY (std_id),
  FOREIGN KEY (cls_id) REFERENCES classes(cls_id)
);


CREATE TABLE exams (
  ex_id int(10) NOT NULL AUTO_INCREMENT,
  ex_name varchar(50) NOT NULL,
  ex_month varchar(2) NOT NULL,
  ex_year year(4) NOT NULL,
  ex_fee int(11) NOT NULL,
  added timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (ex_id)
);


CREATE TABLE fee (
  sr_no int(10) NOT NULL AUTO_INCREMENT,
  std_id int(10) NOT NULL,
  year year(4) NOT NULL,
  month varchar(2) NOT NULL,
  paydate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  amount_paid int(11) NOT NULL,
  PRIMARY KEY (sr_no),
  FOREIGN KEY (std_id) REFERENCES student(std_id)
);


CREATE TABLE subject (
  sub_id int(10) NOT NULL AUTO_INCREMENT,
  sub_name varchar(20) NOT NULL,
  PRIMARY KEY (sub_id)
);


CREATE TABLE teacherAssigned (
  id int(11) NOT NULL AUTO_INCREMENT,
  cls_id int(11) NOT NULL,
  t_id int(11) DEFAULT NULL,
  sub_id int(11) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (cls_id) REFERENCES classes(cls_id),
  FOREIGN KEY (t_id) REFERENCES teacher(t_id),
  FOREIGN KEY (sub_id) REFERENCES subject(sub_id)
);


CREATE TABLE result (
  std_id int(10) NOT NULL,
  sub_dtl_id int(10) NOT NULL,
  ex_id int(10) NOT NULL,
  total int(11) NOT NULL,
  obtained int(11) NOT NULL,
  FOREIGN KEY (std_id) REFERENCES student(std_id),
  FOREIGN KEY (sub_dtl_id) REFERENCES teacherAssigned(id),
  FOREIGN KEY (ex_id) REFERENCES exams(ex_id),
  PRIMARY KEY (std_id,sub_dtl_id,ex_id)
);

/*
CREATE TABLE resulttotal (
  std_id int(10) NOT NULL,
  ex_id int(10) NOT NULL,
  total int(11) NOT NULL,
  obtained int(11) NOT NULL,
  percentage double NOT NULL,
  grade varchar(2) NOT NULL,
  FOREIGN KEY (std_id) REFERENCES student(std_id),
  FOREIGN KEY (ex_id) REFERENCES exams(ex_id),
  PRIMARY KEY (std_id,ex_id)
);
*/

CREATE TABLE s_atd (
  std_id int(10) NOT NULL,
  year year(4) NOT NULL,
  month varchar(2) NOT NULL,
  `1` varchar(1) DEFAULT NULL,
  `2` varchar(1) DEFAULT NULL,
  `3` varchar(1) DEFAULT NULL,
  `4` varchar(1) DEFAULT NULL,
  `5` varchar(1) DEFAULT NULL,
  `6` varchar(1) DEFAULT NULL,
  `7` varchar(1) DEFAULT NULL,
  `8` varchar(1) DEFAULT NULL,
  `9` varchar(1) DEFAULT NULL,
  `10` varchar(1) DEFAULT NULL,
  `11` varchar(1) DEFAULT NULL,
  `12` varchar(1) DEFAULT NULL,
  `13` varchar(1) DEFAULT NULL,
  `14` varchar(1) DEFAULT NULL,
  `15` varchar(1) DEFAULT NULL,
  `16` varchar(1) DEFAULT NULL,
  `17` varchar(1) DEFAULT NULL,
  `18` varchar(1) DEFAULT NULL,
  `19` varchar(1) DEFAULT NULL,
  `20` varchar(1) DEFAULT NULL,
  `21` varchar(1) DEFAULT NULL,
  `22` varchar(1) DEFAULT NULL,
  `23` varchar(1) DEFAULT NULL,
  `24` varchar(1) DEFAULT NULL,
  `25` varchar(1) DEFAULT NULL,
  `26` varchar(1) DEFAULT NULL,
  `27` varchar(1) DEFAULT NULL,
  `28` varchar(1) DEFAULT NULL,
  `29` varchar(1) DEFAULT NULL,
  `30` varchar(1) DEFAULT NULL,
  `31` varchar(1) DEFAULT NULL,
  present int(11) NOT NULL DEFAULT '0',
  absent int(11) NOT NULL DEFAULT '0',
  leaves int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (std_id,year,month),
  FOREIGN KEY (std_id) REFERENCES student(std_id)
);


CREATE TABLE t_atd (
  t_id int(10) NOT NULL,
  year year(4) NOT NULL,
  month varchar(2) NOT NULL,
  `1` varchar(1) DEFAULT NULL,
  `2` varchar(1) DEFAULT NULL,
  `3` varchar(1) DEFAULT NULL,
  `4` varchar(1) DEFAULT NULL,
  `5` varchar(1) DEFAULT NULL,
  `6` varchar(1) DEFAULT NULL,
  `7` varchar(1) DEFAULT NULL,
  `8` varchar(1) DEFAULT NULL,
  `9` varchar(1) DEFAULT NULL,
  `10` varchar(1) DEFAULT NULL,
  `11` varchar(1) DEFAULT NULL,
  `12` varchar(1) DEFAULT NULL,
  `13` varchar(1) DEFAULT NULL,
  `14` varchar(1) DEFAULT NULL,
  `15` varchar(1) DEFAULT NULL,
  `16` varchar(1) DEFAULT NULL,
  `17` varchar(1) DEFAULT NULL,
  `18` varchar(1) DEFAULT NULL,
  `19` varchar(1) DEFAULT NULL,
  `20` varchar(1) DEFAULT NULL,
  `21` varchar(1) DEFAULT NULL,
  `22` varchar(1) DEFAULT NULL,
  `23` varchar(1) DEFAULT NULL,
  `24` varchar(1) DEFAULT NULL,
  `25` varchar(1) DEFAULT NULL,
  `26` varchar(1) DEFAULT NULL,
  `27` varchar(1) DEFAULT NULL,
  `28` varchar(1) DEFAULT NULL,
  `29` varchar(1) DEFAULT NULL,
  `30` varchar(1) DEFAULT NULL,
  `31` varchar(1) DEFAULT NULL,
  present int(11) NOT NULL DEFAULT '0',
  absent int(11) NOT NULL DEFAULT '0',
  leaves int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (t_id,year,month),
  FOREIGN KEY (t_id) REFERENCES teacher(t_id)
);

 
CREATE TABLE controlVars (
  var varchar(50) NOT NULL,
  value varchar(150) DEFAULT NULL,
  PRIMARY KEY (var)
 );
INSERT INTO controlVars (var, value) VALUES
('admission_fee', '500'),
('atd_fine', '1'),
('atd_fine_amnt', '10'),
('default_pass_a', 'admin'),
('default_pass_s', 'student'),
('default_pass_t', 'teacher'),
('fee_fine_after', '10'),
('fee_fine_amnt', '50'),
('fee_fine_daily', '0'),
('nav_news', 'This can be changed in settings...'),
('passing_marks', '33'),
('term_start_month', '4'),
('t_a_fine', '0'),
('t_a_fine_amnt', '50');

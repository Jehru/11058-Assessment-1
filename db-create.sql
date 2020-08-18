CREATE DATABASE project_1;

use project_1;

CREATE TABLE works (
	id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	taskname VARCHAR(30) NOT NULL,
	duedate VARCHAR(50) NOT NULL,
	status VARCHAR(30),
    assignee VARCHAR(30),
    priority VARCHAR(30),
    notes VARCHAR(100),
	date TIMESTAMP
);

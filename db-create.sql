CREATE DATABASE project_1;

use project_1;

CREATE TABLE tasks ( 
    taskid INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    userid INT(10) UNSIGNED, 
	taskname VARCHAR(30) NOT NULL,
	duedate VARCHAR(50) NOT NULL,
	status VARCHAR(30),
    priority VARCHAR(30),
    priorityindex INT (30),
    notes VARCHAR(100),
	date TIMESTAMP
);

CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE DATABASE IF NOT EXISTS tynime;
use tynime;
CREATE TABLE IF NOT EXISTS  users(
	userId int unsigned PRIMARY KEY AUTO_INCREMENT,
	username varchar(50),
	password varchar(255),
	email varchar(255)
);

CREATE TABLE IF NOT EXISTS series(
	seriesId int unsigned PRIMARY KEY AUTO_INCREMENT,
	seriesName varchar(255)
);

CREATE TABLE IF NOT EXISTS videos(
	videoId int unsigned PRIMARY KEY AUTO_INCREMENT,
	seriesId int unsigned,
	views int unsigned,
	name varchar(255),
	seriesPos int unsigned,
	FOREIGN KEY (seriesId) REFERENCES series(seriesId)
);

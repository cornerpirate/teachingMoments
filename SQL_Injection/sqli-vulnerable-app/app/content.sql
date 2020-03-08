create database if not exists appdb;
use appdb;
# Remove tables and drop their content
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS people;
DROP TABLE IF EXISTS secretdata;

# Create users table
CREATE TABLE if not exists users (
  id int NOT NULL AUTO_INCREMENT,
  username varchar(100) NOT NULL,
  password varchar(100) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Insert default users
INSERT INTO users (id, username, password) VALUES (1, 'admin', 'secure');
INSERT INTO users (id, username, password) VALUES (2, 'aaa', 'aaaaaa');
INSERT INTO users (id, username, password) VALUES (3, 'bbb', 'bbbbbb');
INSERT INTO users (id, username, password) VALUES (4, 'ccc', 'cccccc');

# Create people table
CREATE TABLE if not exists people (
  id int NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL,
  age int NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Insert default people
INSERT INTO people (id, name, age) VALUES (1, 'Autry', 33);
INSERT INTO people (id, name, age) VALUES (2, 'Tab', 43);
INSERT INTO people (id, name, age) VALUES (3, 'Lila', 25);

# Create secretdata table
CREATE TABLE if not exists secretdata    (
  id int NOT NULL AUTO_INCREMENT,
  secret varchar(100) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Insert default people
INSERT INTO secretdata (id, secret) VALUES (1, 'Drink more water, you will feel great');
INSERT INTO secretdata (id, secret) VALUES (2, 'Sleep more, you will feel great');
INSERT INTO secretdata (id, secret) VALUES (3, 'Be excellent to each other');


# Create appuser for PHP to login as
DROP USER IF EXISTS 'appuser'@'localhost';
FLUSH PRIVILEGES;

CREATE USER 'appuser'@'localhost' IDENTIFIED BY 'appuserpassword';
GRANT ALL PRIVILEGES ON appdb.* TO 'appuser'@'localhost';
GRANT FILE ON *.* TO 'appuser'@'localhost';
FLUSH PRIVILEGES;


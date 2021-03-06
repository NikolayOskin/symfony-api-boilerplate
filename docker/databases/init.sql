# create databases
CREATE DATABASE IF NOT EXISTS `dev`;
CREATE DATABASE IF NOT EXISTS `test`;

# create root user and grant rights
CREATE USER 'root'@'localhost' IDENTIFIED BY 'local';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%';
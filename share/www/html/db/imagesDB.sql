CREATE DATABASE IF NOT EXISTS `images_db`;

USE `images_db`;

CREATE TABLE IF NOT EXISTS `images` (
  `image_id`  INT NOT NULL AUTO_INCREMENT,
  `name`  VARCHAR(60) NOT NULL,
  `file_name` VARCHAR(60) NOT NULL,
  `description` VARCHAR(255) DEFAULT '',
  `tags` VARCHAR(255) DEFAULT '',
  PRIMARY KEY (`image_id`)
) ENGINE=INNODB  DEFAULT CHARSET=latin1;
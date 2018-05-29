CREATE DATABASE IF NOT EXISTS `images_db`;

USE `images_db`;

CREATE TABLE IF NOT EXISTS `images` (
  `image_id`  VARCHAR(255) NOT NULL,
  `name`  VARCHAR(60) NOT NULL,
  `file_name` VARCHAR(60) NOT NULL,
  `description` VARCHAR(255) DEFAULT '',
  `tags` VARCHAR(255) DEFAULT '',
  PRIMARY KEY (`image_id`)
) ENGINE=INNODB  DEFAULT CHARSET=latin1;
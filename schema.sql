CREATE DATABASE YetiCave
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

USE YetiCave;

CREATE TABLE `YetiCave`.`category` ( `character_coder` VARCHAR(45) NOT NULL PRIMARY KEY,
                                     `category_title` VARCHAR(45) NOT NULL);

CREATE TABLE `YetiCave`.`lot` ( `lot_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                `date_create` TIMESTAMP NOT NULL,
                                `lot_title` VARCHAR(128) NOT NULL,
                                `lot_description` VARCHAR(255) NOT NULL,
                                `lot_image` VARCHAR(255) NOT NULL,
                                `starting_price` INT(11) NOT NULL,
                                `date_of_completion` TIMESTAMP NOT NULL,
                                `bid_step` INT(11) NOT NULL);

CREATE TABLE `YetiCave`.`rate` ( `rate_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                 `rate_starting` TIMESTAMP NOT NULL,
                                 `rate_price` INT(11) NOT NULL);

CREATE TABLE `YetiCave`.`user` ( `user_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                 `date_of_registration` TIMESTAMP NOT NULL,
                                 `user_email` VARCHAR(45) NOT NULL,
                                 `user_name` VARCHAR(45) NOT NULL,
                                 `user_password` VARCHAR(45) NOT NULL,
                                 `user_contacts` VARCHAR(128) NOT NULL);

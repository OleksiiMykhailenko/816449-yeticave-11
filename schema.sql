CREATE DATABASE YetiCave
CHARACTER SET utf8 COLLATE utf8_unicode_ci;

USE YetiCave;

CREATE TABLE `category` ( `id` INT AUTO_INCREMENT PRIMARY KEY,
                                  `character_code` VARCHAR(45) NOT NULL UNIQUE,
                                  `title` VARCHAR(45) NOT NULL
                                  );

CREATE TABLE `users` ( `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                 `date_of_registration` TIMESTAMP NOT NULL,
                                 `name` VARCHAR(45) NOT NULL,
                                 `password` VARCHAR(120) NOT NULL,
                                 `email` VARCHAR(45) NOT NULL UNIQUE,
                                 `contacts` VARCHAR(128) NOT NULL
                                 );

CREATE TABLE `lots` ( `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                 `date_create` TIMESTAMP NOT NULL,
                                 `title` VARCHAR(128) NOT NULL,
                                 `description` VARCHAR(500) NOT NULL,
                                 `image` VARCHAR(255) NOT NULL,
                                 `starting_price` INT(11) NOT NULL,
                                 `date_of_completion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
                                 `bid_step` INT(11) NOT NULL,
                                 `user_id` INT(11) NOT NULL,
                                 `winner_id` INT(11) DEFAULT NULL,
                                 `category_id` INT(11) NOT NULL,
                                 `is_closed` boolean NOT NULL DEFAULT 0,
                                 FOREIGN KEY (user_id) REFERENCES users (id),
                                 FOREIGN KEY (winner_id) REFERENCES users (id),
                                 FOREIGN KEY (category_id) REFERENCES category (id)
                                 );

CREATE TABLE `rates` ( `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                 `date_starting_rate` TIMESTAMP NOT NULL,
                                 `price` INT(11) NOT NULL,
                                 `user_id` INT(11) NOT NULL,
                                 `lot_id` INT(11) NOT NULL,
                                 `is_winner` boolean NOT NULL DEFAULT 0,
                                 FOREIGN KEY (user_id) REFERENCES users (id),
                                 FOREIGN KEY (lot_id) REFERENCES lots (id)
                                 );

CREATE FULLTEXT INDEX lot_search ON lots(title, description);

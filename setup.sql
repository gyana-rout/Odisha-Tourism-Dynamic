-- Database: odisha_tourism
CREATE DATABASE IF NOT EXISTS `odisha_tourism`;
USE `odisha_tourism`;

-- Admin Table
CREATE TABLE IF NOT EXISTS `admin` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Users Table
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Districts Table
CREATE TABLE IF NOT EXISTS `districts` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `culture` TEXT,
  `festivals` TEXT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tourist Places Table
CREATE TABLE IF NOT EXISTS `tourist_places` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `description` TEXT NOT NULL,
  `media` VARCHAR(255) NOT NULL,
  `photo` VARCHAR(255) NOT NULL,
  `latitude` DECIMAL(10,8),
  `longitude` DECIMAL(11,8),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Guides Table
CREATE TABLE IF NOT EXISTS `guides` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `contact` VARCHAR(20) NOT NULL,
  `language` VARCHAR(100),
  `availability` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bookings Table
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `place_id` INT(11) NOT NULL,
  `booking_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `username` VARCHAR(255) NOT NULL,
  `status` ENUM('Pending','Approved') DEFAULT 'Pending',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`place_id`) REFERENCES `tourist_places`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Guide Bookings Table
CREATE TABLE IF NOT EXISTS `guide_bookings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `guide_id` INT(11) NOT NULL,
  `booking_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`guide_id`) REFERENCES `guides`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

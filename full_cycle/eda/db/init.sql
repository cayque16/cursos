CREATE DATABASE IF NOT EXISTS `wallet`;
USE `wallet`;

DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (id varchar(255), name varchar(255), email varchar(255), created_at date);

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (id varchar(255), client_id varchar(255), balance int, created_at date);

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (id varchar(255), account_id_from varchar(255), account_id_to varchar(255), amount int, created_at date);
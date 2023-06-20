CREATE DATABASE IF NOT EXISTS `wallet`;
USE `wallet`;

DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (id varchar(255), name varchar(255), email varchar(255), created_at date);

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (id varchar(255), client_id varchar(255), balance int, created_at date);

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (id varchar(255), account_id_from varchar(255), account_id_to varchar(255), amount int, created_at date);

INSERT INTO `clients` (`id`,`name`,`email`,`created_at`) VALUES ('4bea56fb-4494-43e5-b653-7a526de21880','Jon Snow','jon.snow@winterfell.uk','2023-06-19');
INSERT INTO `clients` (`id`,`name`,`email`,`created_at`) VALUES ('89a45be4-5425-4efd-b134-2b246350ac25','Daenerys Targaryen','daenerys.targaryen@dragonstone.uk','2023-06-19');
INSERT INTO `clients` (`id`,`name`,`email`,`created_at`) VALUES ('c23f5698-cdbb-472b-a419-059fb2b491a9','Cersei Lannister','cersei.lannister@casterlyrock.uk','2023-06-19');

INSERT INTO `accounts` (`id`,`client_id`,`balance`,`created_at`) VALUES ('920cee42-1054-495d-9f55-df2d771847eb','4bea56fb-4494-43e5-b653-7a526de21880',50,'2023-06-19');
INSERT INTO `accounts` (`id`,`client_id`,`balance`,`created_at`) VALUES ('7b49be5e-11fe-4b64-8247-bc1cb2e57178','89a45be4-5425-4efd-b134-2b246350ac25',1000,'2023-06-19');
INSERT INTO `accounts` (`id`,`client_id`,`balance`,`created_at`) VALUES ('2470622b-eebc-422f-8fc6-567c4d2e4a6a','c23f5698-cdbb-472b-a419-059fb2b491a9',10000,'2023-06-19');

CREATE DATABASE IF NOT EXISTS `balances`;
USE `balances`;

DROP TABLE IF EXISTS `balance`;
CREATE TABLE `balance` (id varchar(255), account_id varchar(255), balance int, created_at date);

INSERT INTO `balance` (`id`,`account_id`,`balance`,`created_at`) VALUES ('cdcb72d0-0fc4-11ee-be56-0242ac120002','4bea56fb-4494-43e5-b653-7a526de21880',50,'2023-06-19');
INSERT INTO `balance` (`id`,`account_id`,`balance`,`created_at`) VALUES ('cdcb75c8-0fc4-11ee-be56-0242ac120002','89a45be4-5425-4efd-b134-2b246350ac25',1000,'2023-06-19');
INSERT INTO `balance` (`id`,`account_id`,`balance`,`created_at`) VALUES ('cdcb776c-0fc4-11ee-be56-0242ac120002','c23f5698-cdbb-472b-a419-059fb2b491a9',10000,'2023-06-19');
CREATE DATABASE IF NOT EXISTS `nodedb`;
USE `nodedb`;

DROP TABLE IF EXISTS `people`;
CREATE TABLE `people` (
    `id` int(11) NOT NULL AUTO_INCREMENT,    
    `name` varchar(250) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `id_UNIQUE` (`id`)
)
ALTER TABLE `#__categoryblock` CHANGE `recursive` `recursive` smallint(6) NOT NULL DEFAULT '0';
ALTER TABLE `#__categoryblock` ADD COLUMN `categorytitlecssstyle` varchar(255) NOT NULL;
ALTER TABLE `#__categoryblock` ADD COLUMN `categorydescriptioncssstyle` varchar(255) NOT NULL;
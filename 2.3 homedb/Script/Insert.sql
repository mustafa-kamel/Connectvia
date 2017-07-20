/*
-- Query: 
-- Date: 2017-04-19 23:06
*/
-- -----------------------------------------------------
-- Data for table `homedb`.`sensors`
-- -----------------------------------------------------
START TRANSACTION;
USE `homedb`;
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (1, 'light_1', 1, 1, NULL, NULL, 'light');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (2, 'fire_1', 1, 1, NULL, NULL, 'alarm');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (3, 'temperature_1', 1, 1, NULL, NULL, 'temp');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (4, 'PIR_1 motion', 1, 1, NULL, NULL, 'alarm');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (5, 'home door', 1, 1, NULL, NULL, 'appliances');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (6, 'TV_1', 1, 1, NULL, NULL, 'appliances');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (7, 'light_2', 1, 2, NULL, NULL, 'light');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (8, 'fire_2', 1, 2, NULL, NULL, 'alarm');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (9, 'gasleakage', 1, 2, NULL, NULL, 'alarm');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (10, 'waterleakage_1', 1, 2, NULL, NULL, 'alarm');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (11, 'fridge', 1, 2, NULL, NULL, 'appliances');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (12, 'light_3', 1, 3, NULL, NULL, 'light');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (13, 'fire_3', 1, 3, NULL, NULL, 'alarm');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (14, 'PIR_2 motion', 1, 3, NULL, NULL, 'alarm');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (15, 'garage door', 1, 3, NULL, NULL, 'appliances');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (16, 'light_4', 2, 4, NULL, NULL, 'light');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (17, 'fire_4', 2, 4, NULL, NULL, 'alarm');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (18, 'temperature_4', 2, 4, NULL, NULL, 'temp');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (19, 'TV_4', 2, 4, NULL, NULL, 'appliances');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (20, 'light_5', 2, 5, NULL, NULL, 'light');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (21, 'fire_5', 2, 5, NULL, NULL, 'alarm');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (22, 'waterleakage_2', 2, 5, NULL, NULL, 'alarm');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (23, 'washer', 2, 5, NULL, NULL, 'appliances');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (24, 'light_6', 3, 6, NULL, NULL, 'light');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (25, 'fire_6', 3, 6, NULL, NULL, 'alarm');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (26, 'temperature_6', 3, 6, NULL, NULL, 'temp');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (27, 'TV_6', 3, 6, NULL, NULL, 'appliances');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (28, 'light_7', 3, 7, NULL, NULL, 'light');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (29, 'fire_7', 3, 7, NULL, NULL, 'alarm');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (30, 'temperature_7', 3, 7, NULL, NULL, 'temp');
INSERT INTO `homedb`.`sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES (31, 'TV_7', 3, 7, NULL, NULL, 'appliances');

COMMIT;
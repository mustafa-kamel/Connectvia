SET
  @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS,
  UNIQUE_CHECKS = 0;
SET
  @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS,
  FOREIGN_KEY_CHECKS = 0;
SET
  @OLD_SQL_MODE = @@SQL_MODE,
  SQL_MODE = 'TRADITIONAL,ALLOW_INVALID_DATES';
DROP DATABASE IF EXISTS
  `mydb`;
CREATE DATABASE `mydb` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
SHOW WARNINGS;
USE
  `mydb`;




-- -----------------------------------------------------
-- Table `mydb`.`sensors`
-- -----------------------------------------------------
DROP TABLE IF EXISTS
  `mydb`.`sensors`;
SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `mydb`.`sensors`(
  `sid` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(30) NULL,
  `fid` INT NOT NULL,
  `rid` INT NOT NULL,
  `xLoc` INT NULL,
  `yLoc` INT NULL,
  `sensorType` VARCHAR(25) NOT NULL,
  PRIMARY KEY(`sid`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;
SHOW WARNINGS;



-- -----------------------------------------------------
-- Table `mydb`.`light`
-- -----------------------------------------------------
DROP TABLE IF EXISTS
  `mydb`.`light`;
SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `mydb`.`light`(
  `sensors_sid` INT UNSIGNED NOT NULL,
  `state` BOOLEAN NOT NULL DEFAULT 0,
  `curVal` INT NULL,
  `maxVal` INT NULL DEFAULT 3400,
  PRIMARY KEY(`sensors_sid`),
  CONSTRAINT `fk_light_sensors` FOREIGN KEY(`sensors_sid`) REFERENCES `mydb`.`sensors`(`sid`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;
SHOW WARNINGS;



-- -----------------------------------------------------
-- Table `mydb`.`fire`
-- -----------------------------------------------------
DROP TABLE IF EXISTS
  `mydb`.`fire`;
SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `mydb`.`fire`(
  `sensors_sid` INT UNSIGNED NOT NULL,
  `state` BOOLEAN NOT NULL DEFAULT 0,
  PRIMARY KEY(`sensors_sid`),
  CONSTRAINT `fk_fire_sensors1` FOREIGN KEY(`sensors_sid`) REFERENCES `mydb`.`sensors`(`sid`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;
SHOW WARNINGS;



-- -----------------------------------------------------
-- Table `mydb`.`safety`
-- -----------------------------------------------------
DROP TABLE IF EXISTS
  `mydb`.`safety`;
SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `mydb`.`safety`(
  `sensors_sid` INT UNSIGNED NOT NULL,
  `state` BOOLEAN NOT NULL DEFAULT 0,
  PRIMARY KEY(`sensors_sid`),
  CONSTRAINT `fk_safety_sensors1` FOREIGN KEY(`sensors_sid`) REFERENCES `mydb`.`sensors`(`sid`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;
SHOW WARNINGS;



-- -----------------------------------------------------
-- Table `mydb`.`temp`
-- -----------------------------------------------------
DROP TABLE IF EXISTS
  `mydb`.`temp`;
SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `mydb`.`temp`(
  `sensors_sid` INT UNSIGNED NOT NULL,
  `state` BOOLEAN NOT NULL DEFAULT 0,
  `curVal` DECIMAL(2) NULL DEFAULT 27,
  `preVal` DECIMAL(2) NULL DEFAULT 24,
  PRIMARY KEY(`sensors_sid`),
  CONSTRAINT `fk_currtemp_sensors1` FOREIGN KEY(`sensors_sid`) REFERENCES `mydb`.`sensors`(`sid`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;
SHOW WARNINGS;



-- -----------------------------------------------------
-- Table `mydb`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS
  `mydb`.`users`;
SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `mydb`.`users`(
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(60) NOT NULL,
  `password` VARCHAR(30) NOT NULL,
  `phone` INT(20) NOT NULL,
  `token` VARCHAR(25) NULL,
  `is_admin` BOOLEAN NULL DEFAULT 0,
  `is_approved` BOOLEAN NULL DEFAULT 0,
  `createTime` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY(`id`),
  UNIQUE INDEX `email_UNIQUE`(`email` ASC),
  UNIQUE INDEX `username_UNIQUE`(`username` ASC)
);
SHOW WARNINGS;



-- -----------------------------------------------------
-- Table `mydb`.`updateq`
-- -----------------------------------------------------
DROP TABLE IF EXISTS
  `mydb`.`updateq`;
SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `mydb`.`updateq`(
  `sid` INT NOT NULL,
  `state` BOOLEAN NOT NULL DEFAULT 0,
  `val` DECIMAL NULL DEFAULT 24,
  `curTime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
SHOW WARNINGS;



-- -----------------------------------------------------
-- Table `mydb`.`audit`
-- -----------------------------------------------------
DROP TABLE IF EXISTS
  `mydb`.`audit`;
SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `mydb`.`audit`(
  `sid` INT NOT NULL,
  `state` BOOLEAN NOT NULL,
  `val` DECIMAL NULL,
  `curTime` DATETIME NOT NULL
) ENGINE = InnoDB;
SHOW WARNINGS;
USE
  `mydb`;
DELIMITER $$
USE
  `mydb` $$
DROP TRIGGER IF EXISTS
  `mydb`.`light_AFTER_UPDATE` $$
SHOW WARNINGS $$
USE
  `mydb` $$
CREATE DEFINER = CURRENT_USER TRIGGER `mydb`.`light_AFTER_UPDATE` AFTER
UPDATE ON
  `light` FOR EACH ROW
BEGIN
  IF
    (
      NEW.state = 1 && NEW.curVal >= NEW.maxVal
    ) THEN
  INSERT
INTO
  `mydb`.`updateq`(sid,
  state,
  val)
VALUES(NEW.sensors_sid, 0, NULL) ; ELSE
INSERT
INTO
  `mydb`.`updateq`(sid,
  state,
  val)
VALUES(
  NEW.sensors_sid,
  NEW.state,
  NULL
) ;
END IF ;
END $$
SHOW WARNINGS $$
USE
  `mydb` $$
DROP TRIGGER IF EXISTS
  `mydb`.`fire_AFTER_UPDATE` $$
SHOW WARNINGS $$
USE
  `mydb` $$
CREATE DEFINER = CURRENT_USER TRIGGER `mydb`.`fire_AFTER_UPDATE` AFTER
UPDATE ON
  `fire` FOR EACH ROW
BEGIN
INSERT
INTO
  `mydb`.`updateq`(sid,
  state,
  val)
VALUES(
  NEW.sensors_sid,
  NEW.state,
  NULL
) ;
END $$
SHOW WARNINGS $$
USE
  `mydb` $$
DROP TRIGGER IF EXISTS
  `mydb`.`safety_AFTER_UPDATE` $$
SHOW WARNINGS $$
USE
  `mydb` $$
CREATE DEFINER = CURRENT_USER TRIGGER `mydb`.`safety_AFTER_UPDATE` AFTER
UPDATE ON
  `safety` FOR EACH ROW
BEGIN
INSERT
INTO
  `mydb`.`updateq`(sid,
  state,
  val)
VALUES(
  NEW.sensors_sid,
  NEW.state,
  NULL
) ;
END $$
SHOW WARNINGS $$
USE
  `mydb` $$
DROP TRIGGER IF EXISTS
  `mydb`.`temp_AFTER_UPDATE` $$
SHOW WARNINGS $$
USE
  `mydb` $$
CREATE DEFINER = CURRENT_USER TRIGGER `mydb`.`temp_AFTER_UPDATE` AFTER
UPDATE ON
  `temp` FOR EACH ROW
BEGIN
INSERT
INTO
  `mydb`.`updateq`(sid,
  state,
  val)
VALUES(
  NEW.sensors_sid,
  NEW.state,
  NEW.preVal
) ;
END $$
SHOW WARNINGS $$
USE
  `mydb` $$
DROP TRIGGER IF EXISTS
  `mydb`.`updateq_BEFORE_DELETE` $$
SHOW WARNINGS $$
USE
  `mydb` $$
CREATE DEFINER = CURRENT_USER TRIGGER `mydb`.`updateq_BEFORE_DELETE` BEFORE
DELETE ON
  `updateq` FOR EACH ROW
BEGIN
INSERT
INTO
  `mydb`.`audit`(sid,
  state,
  val,
  CURTIME)
VALUES(
  OLD.sid,
  OLD.state,
  OLD.val,
  OLD.curTime
) ;
END $$
SHOW WARNINGS $$
USE
  `mydb` $$
CREATE DEFINER = CURRENT_USER TRIGGER `mydb`.`sensors_AFTER_INSERT` AFTER
INSERT ON
  `sensors` FOR EACH ROW
BEGIN
SET @table = NEW.sensorType;
SET @id = NEW.sid;

IF (@table = "light") THEN
INSERT INTO `light`(sensors_sid) VALUES (@id);
END IF;

IF (@table = "fire") THEN
INSERT INTO `fire`(sensors_sid) VALUES (@id);
END IF;

IF (@table = "safety") THEN
INSERT INTO `safety`(sensors_sid) VALUES (@id);
END IF;

IF (@table = "temp") THEN
INSERT INTO `temp`(sensors_sid) VALUES (@id);
END IF;
END $$
SHOW WARNINGS $$
DELIMITER ;
SET
  SQL_MODE = @OLD_SQL_MODE;
SET
  FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS;
SET
  UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS;
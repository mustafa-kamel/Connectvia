-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2017 at 11:13 PM
-- Server version: 5.7.17-log
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit`
--

CREATE TABLE `audit` (
  `sid` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `val` decimal(10,0) DEFAULT NULL,
  `curTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fire`
--

CREATE TABLE `fire` (
  `sensors_sid` int(10) UNSIGNED NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fire`
--

INSERT INTO `fire` (`sensors_sid`, `state`) VALUES
(2, 0),
(8, 0),
(13, 0),
(17, 0),
(21, 0),
(26, 0),
(30, 0);

--
-- Triggers `fire`
--
DELIMITER $$
CREATE TRIGGER `fire_AFTER_UPDATE` AFTER UPDATE ON `fire` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `light`
--

CREATE TABLE `light` (
  `sensors_sid` int(10) UNSIGNED NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `curVal` int(11) DEFAULT NULL,
  `maxVal` int(11) DEFAULT '3400'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `light`
--

INSERT INTO `light` (`sensors_sid`, `state`, `curVal`, `maxVal`) VALUES
(1, 0, NULL, 3400),
(7, 0, NULL, 3400),
(12, 0, NULL, 3400),
(16, 0, NULL, 3400),
(20, 0, NULL, 3400),
(24, 0, NULL, 3400),
(29, 0, NULL, 3400);

--
-- Triggers `light`
--
DELIMITER $$
CREATE TRIGGER `light_AFTER_UPDATE` AFTER UPDATE ON `light` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `safety`
--

CREATE TABLE `safety` (
  `sensors_sid` int(10) UNSIGNED NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `safety`
--

INSERT INTO `safety` (`sensors_sid`, `state`) VALUES
(4, 0),
(5, 0),
(6, 0),
(9, 0),
(10, 0),
(11, 0),
(14, 0),
(15, 0),
(19, 0),
(22, 0),
(23, 0),
(28, 0),
(32, 0);

--
-- Triggers `safety`
--
DELIMITER $$
CREATE TRIGGER `safety_AFTER_UPDATE` AFTER UPDATE ON `safety` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sensors`
--

CREATE TABLE `sensors` (
  `sid` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `fid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `xLoc` int(11) DEFAULT NULL,
  `yLoc` int(11) DEFAULT NULL,
  `sensorType` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sensors`
--

INSERT INTO `sensors` (`sid`, `name`, `fid`, `rid`, `xLoc`, `yLoc`, `sensorType`) VALUES
(1, 'light_1', 1, 1, NULL, NULL, 'light'),
(2, 'fire_1', 1, 1, NULL, NULL, 'fire'),
(3, 'temperature_1', 1, 1, NULL, NULL, 'temp'),
(4, 'PIR_1 motion', 1, 1, NULL, NULL, 'safety'),
(5, 'home door', 1, 1, NULL, NULL, 'safety'),
(6, 'TV_1', 1, 1, NULL, NULL, 'safety'),
(7, 'light_2', 1, 2, NULL, NULL, 'light'),
(8, 'fire_2', 1, 2, NULL, NULL, 'fire'),
(9, 'gasleakage', 1, 2, NULL, NULL, 'safety'),
(10, 'waterleakage_1', 1, 2, NULL, NULL, 'safety'),
(11, 'fridge', 1, 2, NULL, NULL, 'safety'),
(12, 'light_3', 1, 3, NULL, NULL, 'light'),
(13, 'fire_3', 1, 3, NULL, NULL, 'fire'),
(14, 'PIR_2 motion', 1, 3, NULL, NULL, 'safety'),
(15, 'garage door', 1, 3, NULL, NULL, 'safety'),
(16, 'light_4', 2, 4, NULL, NULL, 'light'),
(17, 'fire_4', 2, 4, NULL, NULL, 'fire'),
(18, 'temperature_4', 2, 4, NULL, NULL, 'temp'),
(19, 'TV_4', 2, 4, NULL, NULL, 'safety'),
(20, 'light_5', 2, 5, NULL, NULL, 'light'),
(21, 'fire_5', 2, 5, NULL, NULL, 'fire'),
(22, 'waterleakage_2', 2, 5, NULL, NULL, 'safety'),
(23, 'washer', 2, 5, NULL, NULL, 'safety'),
(24, 'light_6', 3, 6, NULL, NULL, 'light'),
(26, 'fire_6', 3, 6, NULL, NULL, 'fire'),
(27, 'temperature_6', 3, 6, NULL, NULL, 'temp'),
(28, 'TV_6', 3, 6, NULL, NULL, 'safety'),
(29, 'light_7', 3, 7, NULL, NULL, 'light'),
(30, 'fire_7', 3, 7, NULL, NULL, 'fire'),
(31, 'temperature_7', 3, 7, NULL, NULL, 'temp'),
(32, 'TV_7', 3, 7, NULL, NULL, 'safety');

--
-- Triggers `sensors`
--
DELIMITER $$
CREATE TRIGGER `sensors_AFTER_INSERT` AFTER INSERT ON `sensors` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `temp`
--

CREATE TABLE `temp` (
  `sensors_sid` int(10) UNSIGNED NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `curVal` decimal(2,0) DEFAULT '27',
  `preVal` decimal(2,0) DEFAULT '24'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `temp`
--

INSERT INTO `temp` (`sensors_sid`, `state`, `curVal`, `preVal`) VALUES
(3, 0, '27', '24'),
(18, 0, '27', '24'),
(27, 0, '27', '24'),
(31, 0, '27', '24');

--
-- Triggers `temp`
--
DELIMITER $$
CREATE TRIGGER `temp_AFTER_UPDATE` AFTER UPDATE ON `temp` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `updateq`
--

CREATE TABLE `updateq` (
  `sid` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `val` decimal(10,0) DEFAULT '24',
  `curTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `updateq`
--
DELIMITER $$
CREATE TRIGGER `updateq_BEFORE_DELETE` BEFORE DELETE ON `updateq` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(30) NOT NULL,
  `phone` int(20) NOT NULL,
  `token` varchar(25) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT '0',
  `is_approved` tinyint(1) DEFAULT '0',
  `createTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fire`
--
ALTER TABLE `fire`
  ADD PRIMARY KEY (`sensors_sid`);

--
-- Indexes for table `light`
--
ALTER TABLE `light`
  ADD PRIMARY KEY (`sensors_sid`);

--
-- Indexes for table `safety`
--
ALTER TABLE `safety`
  ADD PRIMARY KEY (`sensors_sid`);

--
-- Indexes for table `sensors`
--
ALTER TABLE `sensors`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `temp`
--
ALTER TABLE `temp`
  ADD PRIMARY KEY (`sensors_sid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sensors`
--
ALTER TABLE `sensors`
  MODIFY `sid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `fire`
--
ALTER TABLE `fire`
  ADD CONSTRAINT `fk_fire_sensors1` FOREIGN KEY (`sensors_sid`) REFERENCES `sensors` (`sid`);

--
-- Constraints for table `light`
--
ALTER TABLE `light`
  ADD CONSTRAINT `fk_light_sensors` FOREIGN KEY (`sensors_sid`) REFERENCES `sensors` (`sid`);

--
-- Constraints for table `safety`
--
ALTER TABLE `safety`
  ADD CONSTRAINT `fk_safety_sensors1` FOREIGN KEY (`sensors_sid`) REFERENCES `sensors` (`sid`);

--
-- Constraints for table `temp`
--
ALTER TABLE `temp`
  ADD CONSTRAINT `fk_currtemp_sensors1` FOREIGN KEY (`sensors_sid`) REFERENCES `sensors` (`sid`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

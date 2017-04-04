-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 24, 2017 at 10:54 AM
-- Server version: 5.5.54-0+deb8u1
-- PHP Version: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `war`
--
CREATE DATABASE IF NOT EXISTS `war` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `war`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `characterID` int(11) NOT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `allwars`
--

CREATE TABLE IF NOT EXISTS `allwars` (
`ID` int(11) NOT NULL,
  `WarID` int(11) NOT NULL,
  `AgrGroupID` int(11) NOT NULL,
  `AgrGroupName` mediumtext COLLATE utf8_bin NOT NULL,
  `AgrGroupType` int(11) NOT NULL,
  `DefGroupID` int(11) NOT NULL,
  `DefGroupName` mediumtext COLLATE utf8_bin NOT NULL,
  `DefGroupType` int(11) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4285744 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `characters`
--

CREATE TABLE IF NOT EXISTS `characters` (
  `mainCharID` int(11) NOT NULL,
  `mainCharName` mediumtext COLLATE utf8_bin NOT NULL,
  `mainCharGroupID` int(11) NOT NULL,
  `characterID` int(11) NOT NULL,
  `characterName` mediumtext COLLATE utf8_bin NOT NULL,
  `refreshToken` text COLLATE utf8_bin NOT NULL,
  `changed` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `groupID` int(11) NOT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `hostilealts`
--
CREATE TABLE `hostilealts` (
  `characterID` int(11) NOT NULL,
  `characterName` mediumtext NOT NULL,
  `tag` varchar(20) DEFAULT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `addedbyCharacterID` int(11) NOT NULL,
  `groupID` int(11) NOT NULL,
  UNIQUE KEY `characterID_groupID` (`characterID`,`groupID`) USING BTREE );
  
  
--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
 ADD PRIMARY KEY (`characterID`);

--
-- Indexes for table `allwars`
--
ALTER TABLE `allwars`
 ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `uq_allwars` (`WarID`,`DefGroupID`), ADD KEY `WarID` (`WarID`), ADD KEY `WarID_2` (`WarID`);

--
-- Indexes for table `characters`
--
ALTER TABLE `characters`
 ADD PRIMARY KEY (`characterID`), ADD UNIQUE KEY `characterID` (`characterID`);

--
-- Indexes for table `group`
--
ALTER TABLE `group`
 ADD PRIMARY KEY (`groupID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `allwars`
--
ALTER TABLE `allwars`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4285744;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

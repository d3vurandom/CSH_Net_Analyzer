-- phpMyAdmin SQL Dump
-- version 2.10.1
-- http://www.phpmyadmin.net
-- 
-- Host: db.csh.rit.edu
-- Generation Time: Dec 11, 2010 at 01:58 AM
-- Server version: 5.0.51
-- PHP Version: 5.2.6-1+lenny9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Database: `devurandom`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `smartStatus`
-- 

CREATE TABLE `smartStatus` (
  `driveID` int(11) NOT NULL auto_increment,
  `serverID` int(11) NOT NULL,
  `deviceName` varchar(8) NOT NULL,
  `status` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `Raw_Read_Error_Rate` int(11) NOT NULL,
  `Spin_Up_Time` int(11) NOT NULL,
  `Start_Stop_Count` int(11) NOT NULL,
  `Reallocated_Sector_Ct` int(11) NOT NULL,
  `Seek_Error_Rate` int(11) NOT NULL,
  `Power_On_Hours` int(11) NOT NULL,
  `Spin_Retry_Count` int(11) NOT NULL,
  `Calibration_Retry_Count` int(11) NOT NULL,
  `Power_Cycle_Count` int(11) NOT NULL,
  `Power-Off_Retract_Count` int(11) NOT NULL,
  `Load_Cycle_Count` int(11) NOT NULL,
  `Temperature_Celsius` int(11) NOT NULL,
  `Reallocated_Event_Count` int(11) NOT NULL,
  `Current_Pending_Sector` int(11) NOT NULL,
  `Offline_Uncorrectable` int(11) NOT NULL,
  `UDMA_CRC_Error_Count` int(11) NOT NULL,
  `Multi_Zone_Error_Rate` int(11) NOT NULL,
  `crcResetOffset` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`driveID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `smartStatus`
-- 


-- phpMyAdmin SQL Dump
-- version 2.10.1
-- http://www.phpmyadmin.net
-- 
-- Host: db.csh.rit.edu
-- Generation Time: Dec 10, 2010 at 04:51 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.6-1+lenny9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Database: `devurandom`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `server`
-- 

CREATE TABLE `server` (
  `serverID` int(11) NOT NULL auto_increment,
  `hostname` varchar(200) NOT NULL,
  `primaryNicMac` varchar(40) NOT NULL,
  `comment` varchar(200) NOT NULL,
  `localIP` varchar(40) NOT NULL,
  `remoteIP` varchar(40) NOT NULL,
  `uptime` int(40) NOT NULL,
  `lastSeen` datetime NOT NULL,
  `serverKey` varchar(100) NOT NULL,
  `serverOwnerEmail` varchar(100) NOT NULL,
  `wirelessTxtEmail` varchar(100) NOT NULL,
  `lastAlert` datetime NOT NULL,
  `alertCount` int(10) NOT NULL default '0',
  PRIMARY KEY  (`serverID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `server`
-- 


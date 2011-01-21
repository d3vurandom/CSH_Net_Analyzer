-- phpMyAdmin SQL Dump
-- version 2.10.1
-- http://www.phpmyadmin.net
-- 
-- Host: db.csh.rit.edu
-- Generation Time: Dec 10, 2010 at 04:54 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.6-1+lenny9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Database: `devurandom`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `stats`
-- 

CREATE TABLE `stats` (
  `index` int(50) NOT NULL auto_increment,
  `scanID` int(50) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `mac` varchar(100) NOT NULL,
  `openPorts` varchar(100) NOT NULL,
  `hostname` varchar(100) NOT NULL,
  `hops` varchar(100) NOT NULL,
  `os` varchar(100) NOT NULL,
  PRIMARY KEY  (`index`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4616 ;

-- 
-- Dumping data for table `stats`
-- 


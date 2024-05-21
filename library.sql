-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 10 Novermber 2023 at 04:13 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library`
--

-- --------------------------------------------------------

--
-- Table structure for available students to register in the library --
CREATE TABLE Students (
    StudentID VARCHAR(20) PRIMARY KEY,
    FirstName VARCHAR(255),
    LastName VARCHAR(255),
    DateOfBirth DATE,
    Gender VARCHAR(10),
    ProgramCourse VARCHAR(255),
    YearOfStudy INT,
    INDEX(YearOfStudy)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping the values of the Students table --
INSERT INTO Students (`StudentID`,`FirstName`,`LastName`,`DateOfBirth`,`Gender`,`ProgramCourse`,`YearOfStudy`) VALUES
    ('CT205/187645/21', 'John', 'Doe', '2005-05-15', 'Male', 'Computer Science', 1),
    ('ED205/5463/23', 'Jane', 'Smith', '2002-08-22', 'Female', 'English Literature', 2),
    ('EG204/8742/22', 'Robert', 'Johnson', '2004-03-10', 'Male', 'Mechanical Engineering', 1),
    ('EG301/35678/20', 'Emily', 'Williams', '2001-11-28', 'Female', 'Civil Engineering', 3),
    ('CT201/98321/21', 'Michael', 'Anderson', '2004-06-18', 'Male', 'Information Technology', 1),
    ('CT203/12345/19', 'Samantha', 'Taylor', '2000-09-02', 'Female', 'Business Information Technology', 4),
    ('CT206/56789/24', 'Daniel', 'Martinez', '2001-02-08', 'Male', 'Computer Security and Forensics', 3),
    ('HS/11111/18', 'Olivia', 'Brown', '1999-04-30', 'Female', 'Clinical Medicine', 4);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `FullName` varchar(100) DEFAULT NULL,
  `AdminEmail` varchar(120) DEFAULT NULL,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Status` varchar(50) DEFAULT NULL, -- Add the Status column
  `updationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Create tblauthors table
CREATE TABLE `tblauthors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `AuthorName` varchar(159) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create tblcategory table
CREATE TABLE `tblcategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(150) DEFAULT NULL,
  `Status` int(1) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create tblbooks table
CREATE TABLE `tblbooks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `BookName` varchar(255) DEFAULT NULL,
  `CatId` int(11) DEFAULT NULL,
  `AuthorId` int(11) DEFAULT NULL,
  `ISBNNumber` int(19) DEFAULT NULL,
  `AvailableCopies` int(11) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_Author_Book` FOREIGN KEY (`AuthorId`) REFERENCES `tblauthors` (`id`),
  CONSTRAINT `FK_Category_Book` FOREIGN KEY (`CatId`) REFERENCES `tblcategory` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Other tables remain the same

-- --------------------------------------------------------

--
-- Table structure for table `tblissuedbookdetails`
--

CREATE TABLE `tblissuedbookdetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `BookId` int(11) DEFAULT NULL,
  `StudentID` varchar(150) DEFAULT NULL,
  `IssuesDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ReturnDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `ReturnStatus` int(1) DEFAULT NULL,
  `fine` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
-- Table structure for table `tblstudents`
--

-- Table structure for table `tblstudents`
CREATE TABLE `tblstudents` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `StudentId` varchar(100) DEFAULT NULL,
    `EmailId` varchar(120) DEFAULT NULL,
    `MobileNumber` varchar(15) DEFAULT NULL,
    `Password` varchar(120) DEFAULT NULL,
    `Status` enum('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    `RegDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `StudentId` (`StudentId`), -- Add this line to create an index on StudentId
    INDEX (`StudentId`)  -- Add this line as well if UNIQUE KEY alone is not enough
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


-- Create relationships for tblissuedbookdetails table
ALTER TABLE `tblissuedbookdetails`
  ADD CONSTRAINT `FK_Book_IssuedBook` FOREIGN KEY (`BookId`) REFERENCES `tblbooks` (`id`),
  ADD CONSTRAINT `FK_Student_IssuedBook` FOREIGN KEY (`StudentID`) REFERENCES `tblstudents` (`StudentId`);
--

-- --------------------------------------------------------

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

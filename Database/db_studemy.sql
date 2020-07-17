-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2019 at 04:52 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 5.6.39

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_studemy`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessments`
--

CREATE TABLE `assessments` (
  `assessmentId` int(11) UNSIGNED NOT NULL,
  `assessmentName` varchar(40) NOT NULL,
  `instituteNumber` int(4) UNSIGNED ZEROFILL DEFAULT NULL,
  `courseId` int(4) UNSIGNED ZEROFILL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `assessments`
--

INSERT INTO `assessments` (`assessmentId`, `assessmentName`, `instituteNumber`, `courseId`) VALUES
(4, 'Exp Exam', 0003, 0006),
(5, 'TARGET Exam', 0004, 0007),
(6, 'Exp 2', 0003, 0006),
(7, 'Assessment', 0003, 0008);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `courseId` int(4) UNSIGNED ZEROFILL NOT NULL,
  `instituteNumber` int(4) UNSIGNED ZEROFILL DEFAULT NULL,
  `courseName` varchar(100) NOT NULL,
  `courseCode` varchar(5) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `teacher` int(11) DEFAULT NULL,
  `subjects` varchar(11) DEFAULT NULL,
  `description` longtext,
  `file` mediumblob,
  `amount` int(5) DEFAULT NULL,
  `dateUploaded` date DEFAULT NULL,
  `isActivate` tinyint(3) UNSIGNED DEFAULT '0',
  `isApproved` tinyint(1) UNSIGNED DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`courseId`, `instituteNumber`, `courseName`, `courseCode`, `link`, `teacher`, `subjects`, `description`, `file`, `amount`, `dateUploaded`, `isActivate`, `isApproved`) VALUES
(0006, 0003, 'Experimental Learning with innostud', 'EL121', 'https://www.youtube.com/embed/ilNum35pqK4', 24, 'Physics', 'Experimental Learning', 0x62636339373661663139636236663131342e7a6970, 500, '2019-06-04', 0, 0),
(0007, 0004, 'Physics on the wheels', 'AA12', 'https://www.youtube.com/embed/ilNum35pqK4', 28, 'Science', 'Physic on the wheels', 0x37346231626438663738626562396365622e7a6970, 500, '2019-06-04', 0, 0),
(0008, 0003, 'Today\'s Course', 'CR212', 'https://www.youtube.com/embed/ilNum35pqK4', 24, 'Mathematics', 'deljakjd alkjsdl lkjasdl jkasdljalksdj', 0x62636365643139393965643130353065342e7a6970, 2121, '2019-06-10', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `enrollment`
--

CREATE TABLE `enrollment` (
  `id` int(11) NOT NULL,
  `studentId` int(11) DEFAULT NULL,
  `courseId` int(4) UNSIGNED ZEROFILL NOT NULL,
  `custom1` int(11) DEFAULT NULL,
  `custom2` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `enrollment`
--

INSERT INTO `enrollment` (`id`, `studentId`, `courseId`, `custom1`, `custom2`) VALUES
(1, 5, 0006, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `institutes`
--

CREATE TABLE `institutes` (
  `instituteNumber` int(4) UNSIGNED ZEROFILL NOT NULL,
  `instituteName` varchar(100) NOT NULL,
  `instituteCode` varchar(6) NOT NULL,
  `phone` bigint(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pincode` mediumint(7) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL,
  `ownerName` varchar(40) DEFAULT NULL,
  `ownerPhone` bigint(20) DEFAULT NULL,
  `ownerEmail` varchar(100) DEFAULT NULL,
  `adminName` varchar(40) DEFAULT NULL,
  `adminPhone` bigint(20) DEFAULT NULL,
  `adminEmail` varchar(100) DEFAULT NULL,
  `subjects` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `institutes`
--

INSERT INTO `institutes` (`instituteNumber`, `instituteName`, `instituteCode`, `phone`, `email`, `pincode`, `city`, `state`, `ownerName`, `ownerPhone`, `ownerEmail`, `adminName`, `adminPhone`, `adminEmail`, `subjects`) VALUES
(0003, 'INNOSTUD ACADEMY', 'IN1212', 9156814800, 'prashikchitkesiwar@gmail.com', 442401, 'Chnadrapur', 'Maharashtra', 'Prashik Chitkesiwar', 9156814800, 'prashikchitkesiwar@gmail.com', 'Prashanjeet', 915684100, 'prashanjeet@gmail.com', 'Physics'),
(0004, 'TARGET INSTITUTE', 'TG1221', 8308801510, 'target@gmail.com', 442420, 'NAGPUR', 'MAHARASHTRA', 'JAVED', 915625844, 'target@gmail.com', 'ASIF', 915626243, 'target@gmail.com', NULL),
(0006, 'INSPIRE', 'CH5454', 98563256896, 'inspire@support.com', 442012, 'Chandrapur', 'Maharashtra', 'Sikender', 9865895698, 'siklender@me.com', 'Amit', 568659865, 'amit@support.com', '3'),
(0007, 'test innostud', 'inere', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `membership_cache`
--

CREATE TABLE `membership_cache` (
  `request` varchar(100) NOT NULL,
  `request_ts` int(11) DEFAULT NULL,
  `response` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `membership_cache`
--

INSERT INTO `membership_cache` (`request`, `request_ts`, `response`) VALUES
('70ac1b8345686f127df7a48b129e5cec', 1559564328, '<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n<html>\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n    <title>The URL you requested has been blocked</title>\n    <style type=\"text/css\">\n        html, body { margin: 0; padding: 0; font-family: Verdana, Arial, sans-serif; font-size: 10pt; }\n        h1, h2 { height: 82px; text-indent: -999em; margin: 0; padding: 0; margin: 0; }\n        div { margin: 0; padding: 0; }\n        div.header { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGOGFGEH) 0 0 repeat-x; height: 82px; }\n        div.header h1 { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGHGFHBGCHEGPFHHGG) 0 0 no-repeat; }\n        div.header h2 { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGOGFGEH) 0 -82px no-repeat; width: 160px; float: right; }\n        div.sidebar { width: 195px; height: 200px; float: left; }\n        div.main { padding: 5px; margin-left: 195px; }\n        div.buttons { margin-top: 30px; text-align: right; }\n        h3 { margin: 36px 0; font-size: 16pt; }\n        .blocked      h3 { color: #c00; }\n        .authenticate h3 { color: #36c; }\n        h2.fgd_icon { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGOGFGEH) 0 -166px repeat-x; width: 90px; height: 92px; margin: 48px auto; }\n        .blocked      h2.fgd_icon { background-position: 0 -166px; }\n        .authenticate h2.fgd_icon { background-position: -89px -166px; }\n        form { width: 300px; margin: 30px 0; }\n        label { display: block; width: 300px; margin: 5px 0; line-height: 25px; }\n        label input { width: 200px; border: 1px solid #7f9db9; height: 20px; float: right; }\n    </style>\n</head>\n<body class=\"block\">\n    <div class=\"header\">\n        <h2>Powered By Fortinet</h2>\n        <h1>FortiGuard Web Filtering</h1>\n    </div>\n    <div class=\"sidebar\">\n        <h2 class=\"fgd_icon\">block</h2>\n    </div>\n    <div class=\"main\">\n<h3>Web Page Blocked!</h3>\n<div class=\"notice\">\n    <p>The page you have requested has been blocked, because the URL is\nbanned.</p>\n    <p>\n        URL: http://www.youtube.com/oembed?url=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3DlTTajzrSkCw&amp;maxwidth=480&amp;maxheight=360&amp;format=json<br />\n        <br/>Client IP: 10.23.3.55\n        <br/>Server IP: 172.217.26.238\n        <br/>User name: \n        <br/>Group name: \n    </p>\n    <p>\n        \n    </p>\n</div>\n    </div>\n</body>\n</html>\r\n'),
('82b7e6170da1988cbb1c01c8d36b7e19', 1559564328, '<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n<html>\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n    <title>The URL you requested has been blocked</title>\n    <style type=\"text/css\">\n        html, body { margin: 0; padding: 0; font-family: Verdana, Arial, sans-serif; font-size: 10pt; }\n        h1, h2 { height: 82px; text-indent: -999em; margin: 0; padding: 0; margin: 0; }\n        div { margin: 0; padding: 0; }\n        div.header { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGOGFGEH) 0 0 repeat-x; height: 82px; }\n        div.header h1 { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGHGFHBGCHEGPFHHGG) 0 0 no-repeat; }\n        div.header h2 { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGOGFGEH) 0 -82px no-repeat; width: 160px; float: right; }\n        div.sidebar { width: 195px; height: 200px; float: left; }\n        div.main { padding: 5px; margin-left: 195px; }\n        div.buttons { margin-top: 30px; text-align: right; }\n        h3 { margin: 36px 0; font-size: 16pt; }\n        .blocked      h3 { color: #c00; }\n        .authenticate h3 { color: #36c; }\n        h2.fgd_icon { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGOGFGEH) 0 -166px repeat-x; width: 90px; height: 92px; margin: 48px auto; }\n        .blocked      h2.fgd_icon { background-position: 0 -166px; }\n        .authenticate h2.fgd_icon { background-position: -89px -166px; }\n        form { width: 300px; margin: 30px 0; }\n        label { display: block; width: 300px; margin: 5px 0; line-height: 25px; }\n        label input { width: 200px; border: 1px solid #7f9db9; height: 20px; float: right; }\n    </style>\n</head>\n<body class=\"block\">\n    <div class=\"header\">\n        <h2>Powered By Fortinet</h2>\n        <h1>FortiGuard Web Filtering</h1>\n    </div>\n    <div class=\"sidebar\">\n        <h2 class=\"fgd_icon\">block</h2>\n    </div>\n    <div class=\"main\">\n<h3>Web Page Blocked!</h3>\n<div class=\"notice\">\n    <p>The page you have requested has been blocked, because the URL is\nbanned.</p>\n    <p>\n        URL: http://www.youtube.com/oembed?url=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3DlTTajzrSkCw&amp;maxwidth=&amp;maxheight=&amp;format=json<br />\n        <br/>Client IP: 10.23.3.55\n        <br/>Server IP: 172.217.26.238\n        <br/>User name: \n        <br/>Group name: \n    </p>\n    <p>\n        \n    </p>\n</div>\n    </div>\n</body>\n</html>\r\n'),
('d2ac4d73873e77e3f8764503e7b0ec1d', 1559564204, '<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n<html>\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n    <title>The URL you requested has been blocked</title>\n    <style type=\"text/css\">\n        html, body { margin: 0; padding: 0; font-family: Verdana, Arial, sans-serif; font-size: 10pt; }\n        h1, h2 { height: 82px; text-indent: -999em; margin: 0; padding: 0; margin: 0; }\n        div { margin: 0; padding: 0; }\n        div.header { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGOGFGEH) 0 0 repeat-x; height: 82px; }\n        div.header h1 { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGHGFHBGCHEGPFHHGG) 0 0 no-repeat; }\n        div.header h2 { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGOGFGEH) 0 -82px no-repeat; width: 160px; float: right; }\n        div.sidebar { width: 195px; height: 200px; float: left; }\n        div.main { padding: 5px; margin-left: 195px; }\n        div.buttons { margin-top: 30px; text-align: right; }\n        h3 { margin: 36px 0; font-size: 16pt; }\n        .blocked      h3 { color: #c00; }\n        .authenticate h3 { color: #36c; }\n        h2.fgd_icon { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGOGFGEH) 0 -166px repeat-x; width: 90px; height: 92px; margin: 48px auto; }\n        .blocked      h2.fgd_icon { background-position: 0 -166px; }\n        .authenticate h2.fgd_icon { background-position: -89px -166px; }\n        form { width: 300px; margin: 30px 0; }\n        label { display: block; width: 300px; margin: 5px 0; line-height: 25px; }\n        label input { width: 200px; border: 1px solid #7f9db9; height: 20px; float: right; }\n    </style>\n</head>\n<body class=\"block\">\n    <div class=\"header\">\n        <h2>Powered By Fortinet</h2>\n        <h1>FortiGuard Web Filtering</h1>\n    </div>\n    <div class=\"sidebar\">\n        <h2 class=\"fgd_icon\">block</h2>\n    </div>\n    <div class=\"main\">\n<h3>Web Page Blocked!</h3>\n<div class=\"notice\">\n    <p>The page you have requested has been blocked, because the URL is\nbanned.</p>\n    <p>\n        URL: http://www.youtube.com/oembed?url=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3DyplNGkMhZls&amp;maxwidth=&amp;maxheight=&amp;format=json<br />\n        <br/>Client IP: 10.23.3.55\n        <br/>Server IP: 172.217.26.238\n        <br/>User name: \n        <br/>Group name: \n    </p>\n    <p>\n        \n    </p>\n</div>\n    </div>\n</body>\n</html>\r\n'),
('d7fda1bced6e22f51c0fcca09b15bc55', 1559566277, '<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n<html>\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n    <title>The URL you requested has been blocked</title>\n    <style type=\"text/css\">\n        html, body { margin: 0; padding: 0; font-family: Verdana, Arial, sans-serif; font-size: 10pt; }\n        h1, h2 { height: 82px; text-indent: -999em; margin: 0; padding: 0; margin: 0; }\n        div { margin: 0; padding: 0; }\n        div.header { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGOGFGEH) 0 0 repeat-x; height: 82px; }\n        div.header h1 { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGHGFHBGCHEGPFHHGG) 0 0 no-repeat; }\n        div.header h2 { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGOGFGEH) 0 -82px no-repeat; width: 160px; float: right; }\n        div.sidebar { width: 195px; height: 200px; float: left; }\n        div.main { padding: 5px; margin-left: 195px; }\n        div.buttons { margin-top: 30px; text-align: right; }\n        h3 { margin: 36px 0; font-size: 16pt; }\n        .blocked      h3 { color: #c00; }\n        .authenticate h3 { color: #36c; }\n        h2.fgd_icon { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGOGFGEH) 0 -166px repeat-x; width: 90px; height: 92px; margin: 48px auto; }\n        .blocked      h2.fgd_icon { background-position: 0 -166px; }\n        .authenticate h2.fgd_icon { background-position: -89px -166px; }\n        form { width: 300px; margin: 30px 0; }\n        label { display: block; width: 300px; margin: 5px 0; line-height: 25px; }\n        label input { width: 200px; border: 1px solid #7f9db9; height: 20px; float: right; }\n    </style>\n</head>\n<body class=\"block\">\n    <div class=\"header\">\n        <h2>Powered By Fortinet</h2>\n        <h1>FortiGuard Web Filtering</h1>\n    </div>\n    <div class=\"sidebar\">\n        <h2 class=\"fgd_icon\">block</h2>\n    </div>\n    <div class=\"main\">\n<h3>Web Page Blocked!</h3>\n<div class=\"notice\">\n    <p>The page you have requested has been blocked, because the URL is\nbanned.</p>\n    <p>\n        URL: http://www.youtube.com/oembed?url=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3DyplNGkMhZls&amp;maxwidth=750&amp;maxheight=1000&amp;format=json<br />\n        <br/>Client IP: 10.23.3.55\n        <br/>Server IP: 172.217.27.206\n        <br/>User name: \n        <br/>Group name: \n    </p>\n    <p>\n        \n    </p>\n</div>\n    </div>\n</body>\n</html>\r\n'),
('dcc9d7b53b3a764a5b380cbb38912f86', 1559567877, '<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n<html>\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n    <title>The URL you requested has been blocked</title>\n    <style type=\"text/css\">\n        html, body { margin: 0; padding: 0; font-family: Verdana, Arial, sans-serif; font-size: 10pt; }\n        h1, h2 { height: 82px; text-indent: -999em; margin: 0; padding: 0; margin: 0; }\n        div { margin: 0; padding: 0; }\n        div.header { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGOGFGEH) 0 0 repeat-x; height: 82px; }\n        div.header h1 { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGHGFHBGCHEGPFHHGG) 0 0 no-repeat; }\n        div.header h2 { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGOGFGEH) 0 -82px no-repeat; width: 160px; float: right; }\n        div.sidebar { width: 195px; height: 200px; float: left; }\n        div.main { padding: 5px; margin-left: 195px; }\n        div.buttons { margin-top: 30px; text-align: right; }\n        h3 { margin: 36px 0; font-size: 16pt; }\n        .blocked      h3 { color: #c00; }\n        .authenticate h3 { color: #36c; }\n        h2.fgd_icon { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGOGFGEH) 0 -166px repeat-x; width: 90px; height: 92px; margin: 48px auto; }\n        .blocked      h2.fgd_icon { background-position: 0 -166px; }\n        .authenticate h2.fgd_icon { background-position: -89px -166px; }\n        form { width: 300px; margin: 30px 0; }\n        label { display: block; width: 300px; margin: 5px 0; line-height: 25px; }\n        label input { width: 200px; border: 1px solid #7f9db9; height: 20px; float: right; }\n    </style>\n</head>\n<body class=\"block\">\n    <div class=\"header\">\n        <h2>Powered By Fortinet</h2>\n        <h1>FortiGuard Web Filtering</h1>\n    </div>\n    <div class=\"sidebar\">\n        <h2 class=\"fgd_icon\">block</h2>\n    </div>\n    <div class=\"main\">\n<h3>Web Page Blocked!</h3>\n<div class=\"notice\">\n    <p>The page you have requested has been blocked, because the URL is\nbanned.</p>\n    <p>\n        URL: http://www.youtube.com/oembed?url=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3DlTTajzrSkCw&amp;maxwidth=750&amp;maxheight=1000&amp;format=json<br />\n        <br/>Client IP: 10.23.3.55\n        <br/>Server IP: 172.217.27.206\n        <br/>User name: \n        <br/>Group name: \n    </p>\n    <p>\n        \n    </p>\n</div>\n    </div>\n</body>\n</html>\r\n'),
('f5aaab31969f1a1679bddc90f9d1736c', 1559564204, '<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n<html>\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n    <title>The URL you requested has been blocked</title>\n    <style type=\"text/css\">\n        html, body { margin: 0; padding: 0; font-family: Verdana, Arial, sans-serif; font-size: 10pt; }\n        h1, h2 { height: 82px; text-indent: -999em; margin: 0; padding: 0; margin: 0; }\n        div { margin: 0; padding: 0; }\n        div.header { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGOGFGEH) 0 0 repeat-x; height: 82px; }\n        div.header h1 { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGHGFHBGCHEGPFHHGG) 0 0 no-repeat; }\n        div.header h2 { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGOGFGEH) 0 -82px no-repeat; width: 160px; float: right; }\n        div.sidebar { width: 195px; height: 200px; float: left; }\n        div.main { padding: 5px; margin-left: 195px; }\n        div.buttons { margin-top: 30px; text-align: right; }\n        h3 { margin: 36px 0; font-size: 16pt; }\n        .blocked      h3 { color: #c00; }\n        .authenticate h3 { color: #36c; }\n        h2.fgd_icon { background: url(http://www.youtube.com:8008/XX/YY/ZZ/CI/MGPGHGPGPFGHCDPFGGOGFGEH) 0 -166px repeat-x; width: 90px; height: 92px; margin: 48px auto; }\n        .blocked      h2.fgd_icon { background-position: 0 -166px; }\n        .authenticate h2.fgd_icon { background-position: -89px -166px; }\n        form { width: 300px; margin: 30px 0; }\n        label { display: block; width: 300px; margin: 5px 0; line-height: 25px; }\n        label input { width: 200px; border: 1px solid #7f9db9; height: 20px; float: right; }\n    </style>\n</head>\n<body class=\"block\">\n    <div class=\"header\">\n        <h2>Powered By Fortinet</h2>\n        <h1>FortiGuard Web Filtering</h1>\n    </div>\n    <div class=\"sidebar\">\n        <h2 class=\"fgd_icon\">block</h2>\n    </div>\n    <div class=\"main\">\n<h3>Web Page Blocked!</h3>\n<div class=\"notice\">\n    <p>The page you have requested has been blocked, because the URL is\nbanned.</p>\n    <p>\n        URL: http://www.youtube.com/oembed?url=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3DyplNGkMhZls&amp;maxwidth=480&amp;maxheight=360&amp;format=json<br />\n        <br/>Client IP: 10.23.3.55\n        <br/>Server IP: 172.217.166.46\n        <br/>User name: \n        <br/>Group name: \n    </p>\n    <p>\n        \n    </p>\n</div>\n    </div>\n</body>\n</html>\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `membership_grouppermissions`
--

CREATE TABLE `membership_grouppermissions` (
  `permissionID` int(10) UNSIGNED NOT NULL,
  `groupID` int(11) DEFAULT NULL,
  `tableName` varchar(100) DEFAULT NULL,
  `allowInsert` tinyint(4) DEFAULT NULL,
  `allowView` tinyint(4) NOT NULL DEFAULT '0',
  `allowEdit` tinyint(4) NOT NULL DEFAULT '0',
  `allowDelete` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `membership_grouppermissions`
--

INSERT INTO `membership_grouppermissions` (`permissionID`, `groupID`, `tableName`, `allowInsert`, `allowView`, `allowEdit`, `allowDelete`) VALUES
(1, 2, 'assessments', 1, 3, 3, 3),
(2, 2, 'courses', 1, 3, 3, 3),
(3, 2, 'institutes', 1, 3, 3, 3),
(4, 2, 'students', 1, 3, 3, 3),
(5, 2, 'teachers', 1, 3, 3, 3),
(44, 2, 'membership_cache', 1, 3, 3, 3),
(45, 2, 'modules', 1, 3, 3, 3),
(46, 2, 'questions', 1, 3, 3, 3),
(54, 2, 'subjects', 1, 3, 3, 3),
(447, 15, 'assessments', 1, 3, 1, 1),
(448, 15, 'courses', 1, 3, 1, 1),
(449, 15, 'institutes', 1, 3, 0, 0),
(450, 15, 'modules', 1, 3, 1, 1),
(451, 15, 'questions', 1, 3, 1, 1),
(452, 15, 'students', 1, 3, 1, 1),
(453, 15, 'subjects', 1, 3, 1, 1),
(454, 15, 'teachers', 1, 3, 1, 1),
(543, 50, 'assessments', 0, 3, 0, 0),
(544, 50, 'courses', 0, 3, 0, 0),
(545, 50, 'institutes', 0, 3, 0, 0),
(546, 50, 'modules', 0, 3, 0, 0),
(547, 50, 'questions', 0, 3, 0, 0),
(548, 50, 'students', 0, 3, 0, 0),
(549, 50, 'subjects', 0, 3, 0, 0),
(550, 50, 'teachers', 0, 3, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `membership_groups`
--

CREATE TABLE `membership_groups` (
  `groupID` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `allowSignup` tinyint(4) DEFAULT NULL,
  `needsApproval` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `membership_groups`
--

INSERT INTO `membership_groups` (`groupID`, `name`, `description`, `allowSignup`, `needsApproval`) VALUES
(1, 'anonymous', 'Anonymous group created automatically on 2019-06-03', 0, 0),
(2, 'Admins', 'Admin group created automatically on 2019-06-03', 0, 1),
(15, 'Institute', 'All the Coaching Center comes under the group Institutes. Created by Admin only.', 1, 1),
(50, 'student', 'Student group for all the institutes', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `membership_userpermissions`
--

CREATE TABLE `membership_userpermissions` (
  `permissionID` int(10) UNSIGNED NOT NULL,
  `memberID` varchar(100) NOT NULL,
  `tableName` varchar(100) DEFAULT NULL,
  `allowInsert` tinyint(4) DEFAULT NULL,
  `allowView` tinyint(4) NOT NULL DEFAULT '0',
  `allowEdit` tinyint(4) NOT NULL DEFAULT '0',
  `allowDelete` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `membership_userrecords`
--

CREATE TABLE `membership_userrecords` (
  `recID` bigint(20) UNSIGNED NOT NULL,
  `tableName` varchar(100) DEFAULT NULL,
  `pkValue` varchar(255) DEFAULT NULL,
  `memberID` varchar(100) DEFAULT NULL,
  `dateAdded` bigint(20) UNSIGNED DEFAULT NULL,
  `dateUpdated` bigint(20) UNSIGNED DEFAULT NULL,
  `groupID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `membership_userrecords`
--

INSERT INTO `membership_userrecords` (`recID`, `tableName`, `pkValue`, `memberID`, `dateAdded`, `dateUpdated`, `groupID`) VALUES
(1, 'institutes', '21', 'admin', 1559563735, 1559563735, 2),
(2, 'courses', '1', 'admin', 1559564204, 1559564204, 2),
(3, 'courses', '2', 'admin', 1559564328, 1559564328, 2),
(4, 'teachers', '21', 'admin', 1559564782, 1559564782, 2),
(5, 'assessments', 'asdasdas', 'admin', 1559565138, 1559565138, 2),
(6, 'students', '1', 'admin', 1559565202, 1559565202, 2),
(7, 'institutes', '22', 'admin', 1559565774, 1559565774, 2),
(8, 'institutes', '1', 'admin', 1559637227, 1559637227, 2),
(18, 'questions', '1', 'admin', 1559644656, 1559715740, 2),
(23, 'institutes', '0003', 'innostud', 1559647685, 1559903324, 15),
(24, 'institutes', '0004', 'target', 1559647796, 1559903333, 15),
(25, 'students', '4', 'innostud', 1559648074, 1559902664, 15),
(26, 'students', '5', 'target', 1559648241, 1559903298, 15),
(27, 'teachers', '24', 'innostud', 1559648402, 1559903211, 15),
(30, 'teachers', '28', 'target', 1559648827, 1559903244, 15),
(31, 'courses', '0006', 'innostud', 1559649258, 1559972772, 15),
(32, 'courses', '0007', 'target', 1559649336, 1559902925, 15),
(33, 'modules', '4', 'innostud', 1559649514, 1559902740, 15),
(34, 'modules', '5', 'target', 1559649546, 1559903144, 15),
(36, 'assessments', '4', 'innostud', 1559651231, 1559903182, 15),
(37, 'questions', '2', 'admin', 1559651377, 1559651377, 2),
(38, 'assessments', '5', 'target', 1559651627, 1559903192, 15),
(39, 'questions', '3', 'admin', 1559651683, 1559651683, 2),
(40, 'modules', '6', 'innostud', 1559652582, 1559902740, 15),
(41, 'assessments', '6', 'innostud', 1559715591, 1559903182, 15),
(42, 'questions', '4', 'admin', 1559715713, 1559715713, 2),
(43, 'subjects', '1', 'admin', 1559715881, 1559715881, 2),
(44, 'subjects', '2', 'innostud', 1559715888, 1559983585, 15),
(45, 'subjects', '3', 'admin', 1559715895, 1559715895, 2),
(46, 'teachers', '29', 'innostud', 1559715972, 1559903211, 15),
(47, 'institutes', '0006', 'admin', 1559796566, 1559796566, 2),
(48, 'teachers', '30', 'admin', 1559796713, 1559796713, 2),
(49, 'subjects', '4', 'admin', 1559816068, 1559816068, 2),
(53, 'institutes', '0007', 'innostud', 1559972252, 1559972252, 15),
(54, 'modules', '7', 'admin', 1560170941, 1560170941, 2),
(55, 'courses', '0008', 'admin', 1560171387, 1560171387, 2),
(56, 'assessments', '7', 'admin', 1560255801, 1560255801, 2),
(57, 'modules', '8', 'admin', 1560255873, 1560255873, 2),
(58, 'questions', '5', 'admin', 1560255930, 1560255930, 2);

-- --------------------------------------------------------

--
-- Table structure for table `membership_users`
--

CREATE TABLE `membership_users` (
  `memberID` varchar(100) NOT NULL,
  `passMD5` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `signupDate` date DEFAULT NULL,
  `groupID` int(10) UNSIGNED DEFAULT NULL,
  `isBanned` tinyint(4) DEFAULT NULL,
  `isApproved` tinyint(4) DEFAULT NULL,
  `custom1` text,
  `custom2` text,
  `custom3` text,
  `custom4` text,
  `comments` text,
  `pass_reset_key` varchar(100) DEFAULT NULL,
  `pass_reset_expiry` int(10) UNSIGNED DEFAULT NULL,
  `flags` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `membership_users`
--

INSERT INTO `membership_users` (`memberID`, `passMD5`, `email`, `signupDate`, `groupID`, `isBanned`, `isApproved`, `custom1`, `custom2`, `custom3`, `custom4`, `comments`, `pass_reset_key`, `pass_reset_expiry`, `flags`) VALUES
('admin', '$2y$10$s9qfco9GUG2xa1fc.FsM9OJKkeVGHcD27iXPyvzd9I0gzvDWFv0Am', 'admin@support.com', '2019-06-03', 2, 0, 1, NULL, NULL, NULL, NULL, 'Admin member created automatically on 2019-06-03\nRecord updated automatically on 2019-06-06', NULL, NULL, NULL),
('amit', '$2y$10$Qu/t6rq3KWU.IHmqa1d4euWqcB25QssrAr8SsW3vc1g0SVPp3baLu', 'amibhadana@gmail.com', '2019-06-08', 50, 0, 1, 'Amit Bhadana', 'mumbai', 'Mumbai', 'Maharashtra', 'member signed up through the registration form.', NULL, NULL, NULL),
('guest', NULL, NULL, '2019-06-03', 1, 0, 1, NULL, NULL, NULL, NULL, 'Anonymous member created automatically on 2019-06-03', NULL, NULL, NULL),
('innostud', '$2y$10$n0WKSQfUiPGxME14Mhavzu3sJJD1X1NPOlwQwiY4dcNARcZDIxL7y', 'innostud@support.com', '2019-06-06', 15, 0, 1, 'Prashik', 'Innostud', 'Chandrapur', 'Maharashtra', 'member signed up through the registration form.', NULL, NULL, NULL),
('target', '$2y$10$3ZxN8utRb99j2irBZPvJAeP/tRD4HTd.MADibpd6podpseHaBF6Re', 'target@support.com', '2019-06-07', 15, 0, 1, 'Target', 'chandrapur', 'chandrapur', 'maharashtra', 'member signed up through the registration form.', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `membership_usersessions`
--

CREATE TABLE `membership_usersessions` (
  `memberID` varchar(100) NOT NULL,
  `token` varchar(100) NOT NULL,
  `agent` varchar(100) NOT NULL,
  `expiry_ts` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `membership_usersessions`
--

INSERT INTO `membership_usersessions` (`memberID`, `token`, `agent`, `expiry_ts`) VALUES
('admin', '3bNHQDpHdbK8rdCi9O4ZMPndgSHzdi', 'q09tKYdluHDNSNo4gxlvdHEbZraTAW', 1562157585);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `moduleId` int(11) UNSIGNED NOT NULL,
  `instituteNumber` int(4) UNSIGNED ZEROFILL DEFAULT NULL,
  `courseId` int(4) UNSIGNED ZEROFILL DEFAULT NULL,
  `assessmentId` int(11) UNSIGNED DEFAULT NULL,
  `moduleName` varchar(40) DEFAULT NULL,
  `link` varchar(100) DEFAULT NULL,
  `description` longtext,
  `file` mediumblob
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`moduleId`, `instituteNumber`, `courseId`, `assessmentId`, `moduleName`, `link`, `description`, `file`) VALUES
(4, 0003, 0006, 4, 'EXP123', 'https://www.youtube.com/embed/25otS93ZKx8', '																Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\r\n', 0x34373136643637643862336434363661322e7a6970),
(5, 0004, 0007, 5, 'POTW', 'https://www.youtube.com/embed/25otS93ZKx8', 'POTW', 0x63663666386361653930623438306363332e7a6970),
(6, 0003, 0006, 4, 'first module', 'https://www.youtube.com/embed/25otS93ZKx8', NULL, 0x61393264653137366336616631373230632e7a6970),
(7, 0003, 0006, 6, 'Today\'s Module', 'https://www.youtube.com/embed/25otS93ZKx8', 'today\'s module description.', 0x39353133373666323635383235343237382e7a6970),
(8, 0003, 0008, 7, 'Take assessment', 'https://www.youtube.com/embed/ilNum35pqK4', 'Module Description', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `courseId` int(4) UNSIGNED ZEROFILL DEFAULT NULL,
  `moduleId` int(11) UNSIGNED DEFAULT NULL,
  `assessmentId` int(11) UNSIGNED DEFAULT NULL,
  `question` varchar(255) DEFAULT NULL,
  `option1` varchar(100) DEFAULT NULL,
  `option2` varchar(100) DEFAULT NULL,
  `option3` varchar(100) DEFAULT NULL,
  `option4` varchar(100) DEFAULT NULL,
  `answer` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `courseId`, `moduleId`, `assessmentId`, `question`, `option1`, `option2`, `option3`, `option4`, `answer`) VALUES
(1, 0006, 4, 4, 'test question', 'sdfdf', 'sdfsdfdf', 'sdfdasdasdf', 'asdadafasdsd', 1),
(2, 0006, 4, 4, 'what is your city name ?', 'chand', 'nag', NULL, NULL, 1),
(3, 0007, 5, 5, 'What is color of sky?', 'blue', 'red', NULL, NULL, 1),
(4, 0006, 4, 4, 'what is today\'s date?', 'lkj', 'jlkj', NULL, NULL, 1),
(5, 0008, 8, 7, 'what is today\'s date?', '12', '11', '31', '22', 1);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `instituteNumber` int(4) UNSIGNED ZEROFILL DEFAULT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `firstname` varchar(20) DEFAULT NULL,
  `middlename` varchar(20) DEFAULT NULL,
  `lastname` varchar(20) DEFAULT NULL,
  `gender` varchar(6) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` bigint(15) UNSIGNED DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `signupDate` datetime DEFAULT NULL,
  `city` varchar(40) DEFAULT NULL,
  `state` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `instituteNumber`, `username`, `password`, `firstname`, `middlename`, `lastname`, `gender`, `email`, `phone`, `dob`, `signupDate`, `city`, `state`) VALUES
(4, 0003, 'IN123', '123456', 'MADHAV', NULL, 'JOSHI', 'Male', 'innostud@gmail.com', 915681548, '1994-10-15', '2019-06-04 00:00:00', 'chandrapur', 'maharashtra'),
(5, 0004, 'amit', '123456', 'Amit', 'P', 'Bhadana', 'Male', 'amitbhadana@gmail.com', 9998874512, '1979-03-16', '2019-06-04 00:00:00', 'Mumbai', 'Maharashtra');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subjectid` int(11) NOT NULL,
  `subjectName` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subjectid`, `subjectName`) VALUES
(1, 'Physics'),
(2, 'Mathematics');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `firstname` varchar(40) NOT NULL,
  `middlename` varchar(20) DEFAULT NULL,
  `lastname` varchar(40) DEFAULT NULL,
  `instituteNumber` int(4) UNSIGNED ZEROFILL DEFAULT NULL,
  `phone` bigint(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pincode` int(7) DEFAULT NULL,
  `city` varchar(40) DEFAULT NULL,
  `state` varchar(40) DEFAULT NULL,
  `subjects` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `firstname`, `middlename`, `lastname`, `instituteNumber`, `phone`, `email`, `pincode`, `city`, `state`, `subjects`) VALUES
(24, 'Vandana', 'Vijay', 'Chitkesiwar', 0003, 9420222555, 'vandana@gmail.com', 442400, 'Chandrapur', 'Maharashtra', 'Physics'),
(28, 'Varsha', 'H', 'Yerawar', 0004, 954321158, 'varsha@gmail.com', 442510, 'Chamorshi', 'Maharashtra', '1'),
(29, 'calorine', NULL, 'mathew', 0003, 9865986598, 'cornie.17@jilossesq.com', 986545, 'asdf', 'Adzsf', '3'),
(30, 'Sikender', 'Ranugopal', 'Reddy', 0006, 42352345, 'sikender@support.com', 9865298, 'Chandrapur', 'Maharashtra', '3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessments`
--
ALTER TABLE `assessments`
  ADD PRIMARY KEY (`assessmentId`),
  ADD UNIQUE KEY `assessmentId` (`assessmentId`),
  ADD KEY `courseId` (`courseId`),
  ADD KEY `instituteNumber` (`instituteNumber`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`courseId`),
  ADD UNIQUE KEY `courseCode` (`courseCode`),
  ADD KEY `instituteNumber` (`instituteNumber`),
  ADD KEY `teacher` (`teacher`),
  ADD KEY `subject` (`subjects`);

--
-- Indexes for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `institutes`
--
ALTER TABLE `institutes`
  ADD PRIMARY KEY (`instituteNumber`),
  ADD UNIQUE KEY `id` (`instituteNumber`),
  ADD UNIQUE KEY `instituteCode_unique` (`instituteCode`),
  ADD KEY `instituteName` (`instituteName`),
  ADD KEY `instituteCode` (`instituteCode`),
  ADD KEY `subjects` (`subjects`(255));

--
-- Indexes for table `membership_cache`
--
ALTER TABLE `membership_cache`
  ADD PRIMARY KEY (`request`);

--
-- Indexes for table `membership_grouppermissions`
--
ALTER TABLE `membership_grouppermissions`
  ADD PRIMARY KEY (`permissionID`),
  ADD UNIQUE KEY `groupID_tableName` (`groupID`,`tableName`);

--
-- Indexes for table `membership_groups`
--
ALTER TABLE `membership_groups`
  ADD PRIMARY KEY (`groupID`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `membership_userpermissions`
--
ALTER TABLE `membership_userpermissions`
  ADD PRIMARY KEY (`permissionID`),
  ADD UNIQUE KEY `memberID_tableName` (`memberID`,`tableName`);

--
-- Indexes for table `membership_userrecords`
--
ALTER TABLE `membership_userrecords`
  ADD PRIMARY KEY (`recID`),
  ADD UNIQUE KEY `tableName_pkValue` (`tableName`,`pkValue`),
  ADD KEY `pkValue` (`pkValue`),
  ADD KEY `tableName` (`tableName`),
  ADD KEY `memberID` (`memberID`),
  ADD KEY `groupID` (`groupID`);

--
-- Indexes for table `membership_users`
--
ALTER TABLE `membership_users`
  ADD PRIMARY KEY (`memberID`),
  ADD KEY `groupID` (`groupID`);

--
-- Indexes for table `membership_usersessions`
--
ALTER TABLE `membership_usersessions`
  ADD UNIQUE KEY `memberID_token_agent` (`memberID`,`token`,`agent`),
  ADD KEY `memberID` (`memberID`),
  ADD KEY `expiry_ts` (`expiry_ts`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`moduleId`),
  ADD KEY `courseId` (`courseId`),
  ADD KEY `assessmentId` (`assessmentId`),
  ADD KEY `instituteNumber` (`instituteNumber`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `moduleId` (`moduleId`),
  ADD KEY `courseId` (`courseId`),
  ADD KEY `assessmentId` (`assessmentId`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_unique` (`username`),
  ADD UNIQUE KEY `username` (`username`,`email`),
  ADD KEY `instituteNumber` (`instituteNumber`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subjectid`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instituteNumber` (`instituteNumber`),
  ADD KEY `subjects` (`subjects`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessments`
--
ALTER TABLE `assessments`
  MODIFY `assessmentId` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `courseId` int(4) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `enrollment`
--
ALTER TABLE `enrollment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `institutes`
--
ALTER TABLE `institutes`
  MODIFY `instituteNumber` int(4) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `membership_grouppermissions`
--
ALTER TABLE `membership_grouppermissions`
  MODIFY `permissionID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=615;

--
-- AUTO_INCREMENT for table `membership_groups`
--
ALTER TABLE `membership_groups`
  MODIFY `groupID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `membership_userpermissions`
--
ALTER TABLE `membership_userpermissions`
  MODIFY `permissionID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `membership_userrecords`
--
ALTER TABLE `membership_userrecords`
  MODIFY `recID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `moduleId` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subjectid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

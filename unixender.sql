-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2018 at 05:12 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `unixender`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachment`
--

CREATE TABLE `attachment` (
  `id` int(11) NOT NULL,
  `msg_uid` varchar(200) NOT NULL,
  `file_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attachment`
--

INSERT INTO `attachment` (`id`, `msg_uid`, `file_name`) VALUES
(1, '5b3a8dbde51f4', 'files/5b3a8dbde5207-2018-07-02-10-07-29-0.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `detail`
--

CREATE TABLE `detail` (
  `id` varchar(100) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` text,
  `category` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detail`
--

INSERT INTO `detail` (`id`, `username`, `password`, `category`) VALUES
('mgt_chigo', 'Chigo', 'c2b72a708add6386b7484bdf1ed8edf1', 'mgt'),
('mgt_vc', 'Vc', 'f33d16cbc57349d96e5b79bcb4e8bde0', 'mgt'),
('std_kelechi', 'kelechi', '71c6fef157e7e90ede53edb01276e233', 'std'),
('stf_okey', 'okey', 'cdfd74d61f4f07b3487be7356f9636cb', 'stf');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(10) NOT NULL,
  `msg_uid` varchar(200) NOT NULL,
  `msg_sender` varchar(100) NOT NULL,
  `msg_receiver` varchar(100) NOT NULL,
  `msg_key_dec_key` varchar(200) NOT NULL,
  `msg_title` varchar(100) NOT NULL,
  `msg_body` varchar(1000) NOT NULL,
  `date_sent` datetime NOT NULL,
  `date_rec` date NOT NULL,
  `category` varchar(100) NOT NULL,
  `msg_type` varchar(100) NOT NULL,
  `msg_status` varchar(20) NOT NULL DEFAULT 'unread'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msg_id`, `msg_uid`, `msg_sender`, `msg_receiver`, `msg_key_dec_key`, `msg_title`, `msg_body`, `date_sent`, `date_rec`, `category`, `msg_type`, `msg_status`) VALUES
(2, '5b3a97451ed1f', 'Chi', 'mgt_chigo', '1', '\0 *§¯Näþš…câBãáþQÐÖO×DCàL', 'f84 ègÁùV)0E©+ª¡:¸Óöë·š§Ó*|$—´Ê7à½ûN´#hUK=_¬ï@^R›¬tÔ', '2018-07-02 14:21:09', '0000-00-00', 'std', 'Message', 'unread'),
(3, '5b3ae83202e98', 'Vc', 'mgt_chigo', '4', 'Bd{·Nf ZXmø@³WPnAëQ`', 'Y¾©1@Ã\0M‡SQ¾\ZÎŠ†¼†‹ssvóïˆ', '2018-07-02 20:06:26', '0000-00-00', 'mgt', 'Message', 'read'),
(4, '5b3ae90700695', 'chigo', 'mgt_vc', '1', 'SîŸcA‚2Žð9>p®è.£•MþÀ', 'å¼l`às>NÔ°9Ž²üŸ…éý°Ûó \ru‹Òz³\rÛÛG', '2018-07-02 20:09:59', '0000-00-00', 'mgt', 'Memo', 'unread'),
(5, '5b47d7df7891f', 'chigo', 'std_kelechi', '1', 'ñ–Ö©U¹Mm %g…d£!ÛXäÈ', '<åO€SÊtÐÞÀY]W–5Î3´h™¼…a8¢öš„Ñ×|jñ£µ®öU•-H»4kÇ eð{‡', '2018-07-12 15:36:15', '0000-00-00', 'mgt', 'Message', 'read');

-- --------------------------------------------------------

--
-- Table structure for table `msg_keys`
--

CREATE TABLE `msg_keys` (
  `aid` int(11) NOT NULL,
  `id` varchar(200) NOT NULL,
  `msg_dspkey` varchar(100) DEFAULT NULL,
  `msg_keydec_key` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `msg_keys`
--

INSERT INTO `msg_keys` (`aid`, `id`, `msg_dspkey`, `msg_keydec_key`) VALUES
(4, 'mgt_chigo', '1', '1'),
(5, 'std_chi', '1', '1'),
(6, 'mgt_sam', '3', '3'),
(7, 'mgt_vc', '4', '4'),
(8, 'std_kelechi', '2', '2'),
(9, 'stf_okey', '3', '3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachment`
--
ALTER TABLE `attachment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail`
--
ALTER TABLE `detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `msg_keys`
--
ALTER TABLE `msg_keys`
  ADD PRIMARY KEY (`aid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attachment`
--
ALTER TABLE `attachment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `msg_keys`
--
ALTER TABLE `msg_keys`
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

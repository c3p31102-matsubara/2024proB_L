-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost
-- 生成日時: 2024 年 6 月 06 日 08:58
-- サーバのバージョン： 11.3.2-MariaDB
-- PHP のバージョン: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `probc2024`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `affiliation`
--

CREATE TABLE `affiliation` (
  `ID` int(10) NOT NULL,
  `Faculty` varchar(10) DEFAULT NULL,
  `department` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `affiliation`
--

INSERT INTO `affiliation` (`ID`, `Faculty`, `department`) VALUES
(1, '情報学部', '情報システム学科');

-- --------------------------------------------------------

--
-- テーブルの構造 `discovery`
--

CREATE TABLE `discovery` (
  `ID` int(10) NOT NULL,
  `userID` int(10) NOT NULL,
  `color` varchar(10) DEFAULT NULL,
  `features` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `datetime` datetime(6) DEFAULT NULL,
  `place` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `discovery`
--

INSERT INTO `discovery` (`ID`, `userID`, `color`, `features`, `category`, `datetime`, `place`) VALUES
(1, 1, '赤', '青いバック', 'バック', '2024-06-08 15:17:37.000000', '7号館');

-- --------------------------------------------------------

--
-- テーブルの構造 `lost`
--

CREATE TABLE `lost` (
  `ID` int(10) NOT NULL,
  `userID` int(10) NOT NULL,
  `color` varchar(10) DEFAULT NULL,
  `features` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `datetime` datetime(6) DEFAULT NULL,
  `place` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `lost`
--

INSERT INTO `lost` (`ID`, `userID`, `color`, `features`, `category`, `datetime`, `place`) VALUES
(1, 1, '赤', '赤いバック', 'バック', '2024-06-08 15:17:37.000000', '3号館');

-- --------------------------------------------------------

--
-- テーブルの構造 `management`
--

CREATE TABLE `management` (
  `ID` int(10) NOT NULL,
  `lostID` int(10) NOT NULL,
  `discoveryID` int(10) NOT NULL,
  `changedate` datetime(6) NOT NULL,
  `changedetail` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `management`
--

INSERT INTO `management` (`ID`, `lostID`, `discoveryID`, `changedate`, `changedetail`) VALUES
(1, 1, 1, '2024-06-08 15:19:37.000000', 'いろいろ');

-- --------------------------------------------------------

--
-- テーブルの構造 `user`
--

CREATE TABLE `user` (
  `ID` int(10) NOT NULL,
  `attribute` enum('student','teacher') NOT NULL,
  `number` varchar(10) NOT NULL,
  `AffiliationID` int(10) NOT NULL,
  `emailaddress` varchar(50) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `user`
--

INSERT INTO `user` (`ID`, `attribute`, `number`, `AffiliationID`, `emailaddress`, `telephone`, `name`) VALUES
(1, 'student', 'c3p11111', 1, 'ccccccccc@bunkyo.ac.jp', '000000000', 'hogehoge');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `affiliation`
--
ALTER TABLE `affiliation`
  ADD PRIMARY KEY (`ID`);

--
-- テーブルのインデックス `discovery`
--
ALTER TABLE `discovery`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `userID` (`userID`);

--
-- テーブルのインデックス `lost`
--
ALTER TABLE `lost`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `userID` (`userID`);

--
-- テーブルのインデックス `management`
--
ALTER TABLE `management`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `lostID` (`lostID`),
  ADD KEY `discoveryID` (`discoveryID`);

--
-- テーブルのインデックス `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `AffiliationID` (`AffiliationID`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `affiliation`
--
ALTER TABLE `affiliation`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- テーブルの AUTO_INCREMENT `discovery`
--
ALTER TABLE `discovery`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- テーブルの AUTO_INCREMENT `lost`
--
ALTER TABLE `lost`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- テーブルの AUTO_INCREMENT `management`
--
ALTER TABLE `management`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- テーブルの AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `discovery`
--
ALTER TABLE `discovery`
  ADD CONSTRAINT `discovery_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`ID`);

--
-- テーブルの制約 `lost`
--
ALTER TABLE `lost`
  ADD CONSTRAINT `lost_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`ID`);

--
-- テーブルの制約 `management`
--
ALTER TABLE `management`
  ADD CONSTRAINT `management_ibfk_1` FOREIGN KEY (`lostID`) REFERENCES `lost` (`ID`),
  ADD CONSTRAINT `management_ibfk_2` FOREIGN KEY (`discoveryID`) REFERENCES `discovery` (`ID`);

--
-- テーブルの制約 `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`AffiliationID`) REFERENCES `affiliation` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

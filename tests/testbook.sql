-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2024-12-26 13:07:58
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `testbook`
--

-- --------------------------------------------------------

--
-- 資料表結構 `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `year` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `year`, `price`, `quantity`, `image`) VALUES
(2, '1', '1', 1, 1.00, 1, NULL),
(3, '1', '22', 22, 1.00, 22, 'uploads/1735198030_1735115443_great_gatsby.jpg');

-- --------------------------------------------------------

--
-- 資料表結構 `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `members`
--

INSERT INTO `members` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, '123', '123@123', '$2y$10$BXdHWwd/o98Ae6Qz4oDrOOK6hNGpfeHHSWkITz2QiS.ft7iuMPGqu', '2024-12-26 06:15:38'),
(2, '132', 'wyizhi30@gmail.com', '$2y$10$WBEyIqpySTrp9sNaTSO9cOc6WBXwyzxnJXzke1SaaffeTHpWJl1Ky', '2024-12-26 06:48:54'),
(3, '1324', 'wyizhi3@gmail.com', '$2y$10$uV4ElgbQS2z/bU5XGJSYcemJh4pKy13CbiH.zeAz6zdHQcbTxKMmO', '2024-12-26 06:50:14'),
(4, 's1101734', 'wyizhi0@gmail.com', '$2y$10$s4J0f5R7.HAT0BymICQFEe/Sf7mCQ9xjoXeA4uppVyPIwlXkN3Soy', '2024-12-26 06:50:51'),
(5, '1234', '121@123', '$2y$10$wimzl7S/B8SCMmeL7Qxd3.bu5swroZLXY8K4emSJ5Gy6OebP.NeUm', '2024-12-26 06:52:21'),
(6, '12345', '121@1235', '$2y$10$zy0HzNycP.hghKtYhswmJ.XO/HRqHgp1M8UyohoP/0vaJSYk5sjbC', '2024-12-26 06:56:32'),
(7, '123455', '121@12355', '$2y$10$4zuxacAnqNDn2CYK.uvHGukw5GsZpVLtp5HlKCqcuWkE88iNA2vYm', '2024-12-26 06:57:46'),
(8, '1234555', '1521@12355', '$2y$10$dlHnT5ad6WW5vDYyXsjK1OGgbmdOXHrT8UxoueT1mVy5GcreF7kk.', '2024-12-26 06:59:10'),
(9, '12345555', '15d21@12355', '$2y$10$fNdM6fFyVFJQSuTuV1MkfuEfM6OTdnlfmfj3qjff4HYy0XbeY7.Se', '2024-12-26 06:59:34'),
(10, '12345555d', '15dd21@12355', '$2y$10$FkcQZ4LXKSaGtcwP8k5m/O/mNh9/IILHteSbNLUAX0rdo1.yJlWFe', '2024-12-26 06:59:45'),
(11, '111', '111@111', '$2y$10$u5qhRwsTXPyLx1i3MwAQ0uMmOsewQyQxlhu9OW7FxKcLc1auJNGv6', '2024-12-26 07:00:15'),
(12, '1111', '1111@111', '$2y$10$Cq0vQ3glAank3RDruGNnpOi7seEoeqWlBulM7iXfbcyOrAoPzFbSi', '2024-12-26 07:01:23'),
(13, '222', '222@2222', '$2y$10$AmY9vioXtNiCtPOqFPtiz.I3ENojA4hw8K2rcTAl5rdiIE65QxsPW', '2024-12-26 07:01:56'),
(14, '333', '333@333', '$2y$10$WueSOSvvpzdQcyxyClPmmencHt.RcM/mLIXa7NIn7HqGa1UOJKu16', '2024-12-26 07:05:05'),
(15, '666', '666@666', '$2y$10$MfSF6.0P.VGVQl5cqh/0v.Z6atOg3GDLDo5Iho90183L/itf6OOVq', '2024-12-26 07:12:43'),
(16, '114511', 'rfrfrfr@feffrf', '$2y$10$Z1xNY03T5ze4qNx0VdJZMORr6.kGoG5l72l4L6.EmVAG4Pt6l1Ywa', '2024-12-26 07:13:47'),
(17, 'hyhyh', 'yhyhyh@rg', '$2y$10$ANDqkfKorfIEq31h.HDwhe.tuA58wLdo9RggBqR4Y7iPiD6fNyX1W', '2024-12-26 07:15:07'),
(18, '555', '555@555', '$2y$10$ZlkMgbubTqSHljWRx8yk2eJEcpKxZnxXbSIfXlIm0vDyA1ntMiDTy', '2024-12-26 07:16:28'),
(19, '999', '999@99', '$2y$10$mjf8xbignCAcU7QP3znhMevIndYH.KkxFAS50rXmiNMTOfX4ertuq', '2024-12-26 07:18:11'),
(20, '888', '888@888', '$2y$10$511h01NXoz5zJAqEU0k.aOuoOv3RUxzcQBm9Jm4NAU8UStSpHN2Ui', '2024-12-26 07:21:44'),
(21, '998', '99@999', '$2y$10$LlXsdB/W8JnQLxxjwZXOF.Bvw.fS83T1wRYiuYBKobpRP0gNtZ5Y.', '2024-12-26 08:53:44');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

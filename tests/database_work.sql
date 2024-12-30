-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2024-12-30 14:43:43
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
-- 資料庫： `database_work`
--

-- --------------------------------------------------------

--
-- 資料表結構 `admin`
--

CREATE TABLE `admin` (
  `username` varchar(50) NOT NULL DEFAULT '',
  `passwd` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `admin`
--

INSERT INTO `admin` (`username`, `passwd`) VALUES
('admin', 'admin');

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
(1, '1', '22', 22, 1.00, 22, 'uploads/1735198030_1735115443_great_gatsby.jpg'),
(2, 'PHP實用手冊', '小八', 2024, 600.00, 18, 'uploads/1735474123_下載.jpg'),
(3, '123', '456', 1234, 123.00, 12, 'uploads/1735475924_1735116340_1984.jpg'),
(4, '123', '456', 11111, 11111.00, 123, 'uploads/1735555279_great_gatsby.jpg');

-- --------------------------------------------------------

--
-- 資料表結構 `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `role` varchar(128) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `members`
--

INSERT INTO `members` (`id`, `role`, `username`, `email`, `password`, `created_at`) VALUES
(22, 'admin\r\n', 's1091755', 'cs06431@gmail.com', '$2y$10$YWWI7fy2PEcWqjIwy8AYXe6dfnjUFr65S66xcI8gTszLNQwXkRkau', '2024-12-29 09:55:59'),
(23, 'user\r\n', 's123', 's123@gmail.com', '$2y$10$s4jMk0wSj6FSQnPlHwj7cuOW9Vi/HAo1r22CKciwHw8wq59UrztKC', '2024-12-29 12:26:18'),
(25, 'user', 's124', 's124@gmail.com', '$2y$10$YNbPco.l/9Vl3ndHO1NglOP/0O.s/kl1.A8KPCoADTCg/lDtqsVEW', '2024-12-30 13:39:52'),
(26, 'user', 's125', 's125@gmail.com', '$2y$10$UlueZeCmoa5e8spuc3WJ1uNvV4OlRL5PM1ocXGIZfxSKEtDZ6VW6.', '2024-12-30 13:41:06'),
(27, 'user', 's126', 's126@gmail.com', '$2y$10$j3y3wTJXVD7LXKhNGFSYq.UFGQcgxXpm3GdgeksT0zSPORNck0/DO', '2024-12-30 13:42:13');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`username`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

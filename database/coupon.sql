-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2024 年 08 月 23 日 18:14
-- 伺服器版本： 10.4.28-MariaDB
-- PHP 版本： 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `violin`
--

-- --------------------------------------------------------

--
-- 資料表結構 `coupon`
--

CREATE TABLE `coupon` (
  `id` int(11) NOT NULL,
  `coupon_sid` varchar(10) NOT NULL,
  `coupon_name` varchar(10) NOT NULL,
  `coupon_info` varchar(200) NOT NULL,
  `coupon_rewardType` int(11) NOT NULL,
  `coupon_lowPrice` int(11) NOT NULL,
  `coupon_maxUse` int(11) DEFAULT NULL,
  `coupon_mode` int(11) NOT NULL,
  `coupon_amount` int(11) DEFAULT NULL,
  `coupon_send` int(11) NOT NULL,
  `coupon_startDate` date NOT NULL,
  `coupon_endDate` date NOT NULL,
  `product_id` varchar(7) NOT NULL,
  `coupon_specifyDate` date DEFAULT NULL,
  `coupon_state` int(11) DEFAULT NULL,
  `coupon_createAt` datetime DEFAULT NULL,
  `valid` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `coupon`
--

INSERT INTO `coupon` (`id`, `coupon_sid`, `coupon_name`, `coupon_info`, `coupon_rewardType`, `coupon_lowPrice`, `coupon_maxUse`, `coupon_mode`, `coupon_amount`, `coupon_send`, `coupon_startDate`, `coupon_endDate`, `product_id`, `coupon_specifyDate`, `coupon_state`, `coupon_createAt`, `valid`) VALUES
(1, 'test', 'test', 'hi', 1, 150, 77, 1, 555, 1, '2024-07-05', '2024-08-02', '1', '2024-08-20', 1, '0000-00-00 00:00:00', 0),
(2, '1', '1', 'testr343', 1, 10000, 123, 1, 200, 1, '2024-08-21', '2024-08-30', '1', '2024-08-20', 2, '0000-00-00 00:00:00', 0),
(3, 'r936nZ9H7M', '出清大特賣', 'testr343', 1, 123, 123, 1, 1, 1, '2024-08-21', '2024-08-30', '1', '2024-08-20', 3, '0000-00-00 00:00:00', 0),
(4, 'test2', 'test2', 'testr343', 1, 123, 123, 1, 1, 1, '2024-09-05', '2024-09-30', '1', '2024-08-20', 1, '0000-00-00 00:00:00', 0),
(5, '1111', '出清大特賣', 'testr343', 1, 123, 123, 1, 1, 1, '2024-08-21', '2024-08-30', '1', '2024-08-20', 1, '0000-00-00 00:00:00', 0),
(6, '0AeQWAlZEv', '週年慶', 'testr343', 1, 123, 123, 1, 1, 1, '2024-08-21', '2024-08-30', '1', '2024-08-20', 1, '0000-00-00 00:00:00', 0),
(7, '2', '2', 'testr343', 1, 123, 123, 1, 1, 1, '2024-08-21', '2024-08-30', '1', '2024-08-20', 2, '2024-08-21 04:54:01', 0),
(8, '3', '3', 'testr343', 1, 123, 123, 1, 1, 1, '2024-08-21', '2024-08-30', '1', '2024-08-20', 2, '2024-08-21 04:54:26', 0),
(9, '5', '3', 'testr343', 1, 123, 123, 1, 1, 1, '2024-08-21', '2024-08-30', '1', '2024-08-20', NULL, '2024-08-21 05:14:19', 0),
(10, '6', '6', 'testr343', 1, 123, 123, 1, 1, 1, '2024-08-21', '2024-08-30', '1', '2024-08-20', NULL, '2024-08-21 05:18:39', 0),
(11, '7', '7', 'testr343', 1, 123, 123, 1, 1, 1, '2024-08-21', '2024-08-30', '1', '2024-08-20', NULL, '2024-08-21 05:22:42', 0),
(12, '8', '8', 'testr343', 1, 123, 123, 1, 1, 1, '2024-08-21', '2024-08-30', '1', '2024-08-20', NULL, '2024-08-21 11:30:54', 0),
(13, '22', '22', 'testr343', 1, 123, 123, 1, 1, 1, '2024-08-21', '2024-08-30', '1', '2024-08-20', NULL, '2024-08-21 13:41:08', 1),
(14, '133243413', '12412414', '124214', 1, 0, 1, 1, 100, 1, '2024-08-24', '2024-08-31', '1', '2024-08-22', 2, '2024-08-21 21:42:29', 1),
(15, '0JA2kE6kTC', '123', '123', 1, 1, -1, 1, -1, 1, '2024-07-31', '2024-08-29', '1', '2024-08-09', NULL, '2024-08-23 00:24:12', 1),
(16, '123', '123', '1234', 1, 4567, -1, 1, -1, 1, '2024-08-16', '2024-08-07', '1', '2024-08-23', NULL, '2024-08-23 00:41:46', 1),
(17, '654t54y', '5656y', '56y56y5', 1, 5, -1, 1, -1, 1, '2024-08-09', '2024-08-28', '1', '2024-08-23', NULL, '2024-08-23 00:42:11', 1),
(18, 'wewqe', 'qrafe', 'faefr', 1, 111, -1, 1, -1, 1, '2024-08-24', '2024-08-31', '1', '2024-08-23', NULL, '2024-08-23 14:37:54', 1),
(19, '335', '151235', '2525', 1, 12, -1, 2, -1, 1, '2024-08-01', '2024-08-31', '1', '2024-08-23', NULL, '2024-08-23 15:20:28', 1),
(20, '4245', '12351', '1521', 2, 314, -1, 1, -1, 1, '2024-08-24', '2024-08-26', '1', '2024-08-23', NULL, '2024-08-23 15:23:40', 1),
(21, '35231', '1251', '23512', 2, 352, -1, 2, 235, 3, '2024-08-31', '2024-09-30', '1', '2024-08-23', NULL, '2024-08-23 15:43:43', 1),
(22, '241', '235', '251', 2, 1235, -1, 3, -1, 2, '2024-08-24', '2024-08-31', '1', '2024-08-23', NULL, '2024-08-23 15:49:43', 1),
(23, '2515', '125', '235', 1, 125, -1, 1, -1, 1, '2024-08-31', '2024-09-07', '1', '2024-08-23', NULL, '2024-08-23 15:50:31', 1),
(24, '353', '525', '235', 1, 2135, -1, 1, -1, 1, '2024-08-24', '2024-08-25', '1', '2024-08-23', 1, '2024-08-23 16:02:10', 1);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `coupon`
--
ALTER TABLE `coupon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

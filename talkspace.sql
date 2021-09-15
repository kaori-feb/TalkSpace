-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost:3306
-- 生成日時: 
-- サーバのバージョン： 5.7.24
-- PHP のバージョン: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `talkspace`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `bords`
--

CREATE TABLE `bords` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `talker_id` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `is_finished` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `favoriteusers`
--

CREATE TABLE `favoriteusers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `favoriteuser_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `bord_id` int(11) NOT NULL,
  `view_name` varchar(50) DEFAULT NULL,
  `message` text NOT NULL,
  `post_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `resetpaswds`
--

CREATE TABLE `resetpaswds` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `reset_token` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL COMMENT '1:相談者 2:相談スタッフ 3:管理者',
  `nickname` varchar(50) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` int(11) NOT NULL,
  `gender_open` int(11) DEFAULT NULL COMMENT '0:非公開 1:公開',
  `birth_place` varchar(32) DEFAULT NULL,
  `birth_place2` varchar(50) DEFAULT NULL,
  `birth_place_open` int(11) DEFAULT NULL COMMENT '0:非公開 1:公開',
  `living_place` varchar(32) DEFAULT NULL,
  `living_place2` varchar(50) DEFAULT NULL,
  `living_place_open` int(11) DEFAULT NULL COMMENT '0:非公開 1:公開',
  `free_time1` int(11) DEFAULT NULL COMMENT '99:利用無し',
  `free_time2` int(11) DEFAULT NULL COMMENT '99:利用無し',
  `comment` text,
  `img_path` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `role_id`, `nickname`, `mail`, `password`, `gender`, `gender_open`, `birth_place`, `birth_place2`, `birth_place_open`, `living_place`, `living_place2`, `living_place_open`, `free_time1`, `free_time2`, `comment`, `img_path`, `created_at`, `updated_at`) VALUES
(1, 3, 'admin01', 'admin01@test.com', '$2y$10$ummXuXOHS05AyI5zT5NyHe.lUULC9CuGUWYsakbDZCwlAPDnKcjAC', 3, 0, '0', '0', 0, '0', '0', 0, 99, 99, '管理者その１', '/img/profile_img/samplestaff.png', '2021-06-20 12:39:44', '2021-09-15 14:50:54'),
(2, 1, 'talker01', 'talker01@test.com', '$2y$10$IUtpXIjhY1YJgxD0rtvd2OiWrNDnhEKS1RJgXwxdyQUPGj8vUF6si', 1, 1, '北海道', '0', 0, '栃木県', '0', 0, 99, 99, '相談者その１。\r\nよろしくお願いいたします。', '/img/profile_img/sampletalker01.png', '2021-06-20 12:44:21', '2021-09-15 14:51:08'),
(3, 2, 'staff01', 'staff01@test.com', '$2y$10$wS8OEH355gCxTUy8tLFhH.a3Vxty73EgwVCLesSvdFT1.Fkzkeqou', 1, 1, '静岡県', '0', 1, '静岡県', '0', 1, 20, 99, 'staff01です。\r\nよろしくお願いいたします。', '/img/profile_img/samplestaff02.png', '2021-06-20 14:40:51', '2021-09-15 14:51:19');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `bords`
--
ALTER TABLE `bords`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `favoriteusers`
--
ALTER TABLE `favoriteusers`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `resetpaswds`
--
ALTER TABLE `resetpaswds`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルのAUTO_INCREMENT
--

--
-- テーブルのAUTO_INCREMENT `bords`
--
ALTER TABLE `bords`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルのAUTO_INCREMENT `favoriteusers`
--
ALTER TABLE `favoriteusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルのAUTO_INCREMENT `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルのAUTO_INCREMENT `resetpaswds`
--
ALTER TABLE `resetpaswds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルのAUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

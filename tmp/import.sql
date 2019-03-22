SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) NOT NULL,
  `type` varchar(32) NOT NULL DEFAULT 'news',
  `value` int(10) NOT NULL DEFAULT '0',
  `text_bb` text NOT NULL,
  `text_html` text NOT NULL,
  `user_id_create` int(10) NOT NULL DEFAULT '0',
  `user_id_update` int(10) NOT NULL DEFAULT '0',
  `date_create` int(10) NOT NULL DEFAULT '0',
  `date_update` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `group_permissions` (
  `id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL DEFAULT '0',
  `permission_id` int(10) NOT NULL DEFAULT '0',
  `value` varchar(64) NOT NULL DEFAULT 'false',
  `user_id_create` int(10) NOT NULL DEFAULT '0',
  `user_id_update` int(10) NOT NULL DEFAULT '0',
  `date_create` int(10) NOT NULL DEFAULT '0',
  `date_update` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=325 DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `ip` varchar(15) NOT NULL DEFAULT '127.0.0.1',
  `method` varchar(12) NOT NULL DEFAULT 'POST',
  `controller` varchar(255) NOT NULL DEFAULT '',
  `url` text NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `date` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `subject` varchar(64) NOT NULL DEFAULT '',
  `text_bb` text NOT NULL,
  `text_html` text NOT NULL,
  `is_close` tinyint(1) NOT NULL DEFAULT '0',
  `user_id_create` int(10) NOT NULL DEFAULT '0',
  `user_id_update` int(10) NOT NULL DEFAULT '0',
  `date_create` int(10) NOT NULL DEFAULT '0',
  `date_update` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `message_links` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `message_id` int(10) NOT NULL DEFAULT '0',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `perm_close` tinyint(1) NOT NULL DEFAULT '0',
  `perm_delete` tinyint(1) NOT NULL DEFAULT '0',
  `perm_user_add` tinyint(1) NOT NULL DEFAULT '0',
  `perm_user_remove` tinyint(1) NOT NULL DEFAULT '0',
  `perm_moder_add` tinyint(1) NOT NULL DEFAULT '0',
  `perm_moder_remove` tinyint(1) NOT NULL DEFAULT '0',
  `user_id_create` int(10) NOT NULL DEFAULT '0',
  `user_id_update` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `message_reply` (
  `id` int(10) NOT NULL,
  `message_id` int(10) NOT NULL DEFAULT '0',
  `text_bb` text NOT NULL,
  `text_html` text NOT NULL,
  `user_id_create` int(10) NOT NULL DEFAULT '0',
  `user_id_update` int(10) NOT NULL DEFAULT '0',
  `date_create` int(10) NOT NULL DEFAULT '0',
  `date_update` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(10) NOT NULL,
  `title` varchar(64) NOT NULL DEFAULT '',
  `name` varchar(64) NOT NULL DEFAULT '',
  `text_short_html` text,
  `text_short_bb` text,
  `text_html` text,
  `text_bb` text,
  `image` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `date_create` int(10) NOT NULL DEFAULT '0',
  `date_update` int(10) NOT NULL DEFAULT '0',
  `user_id_create` int(10) NOT NULL DEFAULT '0',
  `user_id_update` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `news_likes` (
  `id` int(10) NOT NULL,
  `new_id` int(10) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `date` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `news_tags` (
  `id` int(10) NOT NULL,
  `title` varchar(32) NOT NULL DEFAULT '',
  `name` varchar(32) NOT NULL DEFAULT '',
  `text` varchar(255) NOT NULL DEFAULT '',
  `date_create` int(10) NOT NULL DEFAULT '0',
  `date_update` int(10) NOT NULL DEFAULT '0',
  `user_id_create` int(10) NOT NULL DEFAULT '0',
  `user_id_update` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `news_tag_links` (
  `id` int(10) NOT NULL,
  `tag_id` int(10) NOT NULL DEFAULT '0',
  `new_id` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `news_views` (
  `id` int(10) NOT NULL,
  `new_id` int(10) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `ip` varchar(16) NOT NULL DEFAULT '127.0.0.1',
  `date` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) NOT NULL,
  `title` varchar(64) NOT NULL DEFAULT '',
  `name` varchar(64) NOT NULL DEFAULT '',
  `default` varchar(64) NOT NULL DEFAULT 'false',
  `system` tinyint(1) NOT NULL DEFAULT '0',
  `type` varchar(32) NOT NULL DEFAULT 'boolean',
  `user_id_create` int(10) NOT NULL DEFAULT '0',
  `user_id_update` int(10) NOT NULL DEFAULT '0',
  `date_create` int(10) NOT NULL DEFAULT '0',
  `date_update` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `statics` (
  `id` int(10) NOT NULL,
  `route` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(64) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `permission` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `user_id_create` int(10) NOT NULL DEFAULT '0',
  `user_id_update` int(10) NOT NULL DEFAULT '0',
  `date_create` int(10) NOT NULL DEFAULT '0',
  `date_update` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `subscribes` (
  `id` int(10) NOT NULL,
  `value` int(10) NOT NULL DEFAULT '0',
  `subscriber_id` int(10) NOT NULL DEFAULT '0',
  `type` varchar(32) NOT NULL DEFAULT '',
  `date` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `user_auth` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `ip` varchar(16) NOT NULL DEFAULT '127.0.0.1',
  `token` varchar(128) NOT NULL DEFAULT '',
  `date_create` int(10) NOT NULL DEFAULT '0',
  `date_expire` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `user_balance` (
  `id` int(10) NOT NULL,
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `realmoney` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bank` decimal(10,2) NOT NULL DEFAULT '0.00',
  `login` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `user_banip` (
  `id` int(10) NOT NULL,
  `ip` varchar(15) NOT NULL DEFAULT '',
  `reason` text NOT NULL,
  `user_id_create` int(10) NOT NULL DEFAULT '0',
  `user_id_update` int(10) NOT NULL DEFAULT '0',
  `date_create` int(10) NOT NULL DEFAULT '0',
  `date_update` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `user_groups` (
  `id` int(10) NOT NULL,
  `title` varchar(32) NOT NULL DEFAULT '',
  `name` varchar(32) NOT NULL DEFAULT '',
  `text` varchar(255) NOT NULL DEFAULT '',
  `date_create` int(10) NOT NULL DEFAULT '0',
  `date_update` int(10) NOT NULL DEFAULT '0',
  `user_id_create` int(10) NOT NULL DEFAULT '0',
  `user_id_update` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `user_likes` (
  `id` int(10) NOT NULL,
  `profile_id` int(10) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `date` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `user_permissions` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `permission_id` int(10) NOT NULL DEFAULT '0',
  `value` varchar(64) NOT NULL DEFAULT 'false',
  `user_id_create` int(10) NOT NULL DEFAULT '0',
  `user_id_update` int(10) NOT NULL DEFAULT '0',
  `date_create` int(10) NOT NULL DEFAULT '0',
  `date_update` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `user_stats` (
  `id` int(11) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `subscribers` int(10) NOT NULL DEFAULT '0',
  `likes` int(10) NOT NULL DEFAULT '0',
  `publications` int(10) NOT NULL DEFAULT '0',
  `comments` int(10) NOT NULL DEFAULT '0',
  `messages` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `user_tokens` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `token` varchar(128) NOT NULL DEFAULT '',
  `ip` varchar(16) NOT NULL DEFAULT '127.0.0.1',
  `type` varchar(32) NOT NULL DEFAULT 'restore',
  `data` text NOT NULL,
  `date_create` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `group_permissions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `message_links`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `message_reply`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `news_likes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `news_tags`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `news_tag_links`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `news_views`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `statics`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `subscribes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user_auth`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user_balance`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user_banip`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user_likes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user_stats`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user_tokens`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `comments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `group_permissions`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=325;
ALTER TABLE `logs`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `messages`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `message_links`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `message_reply`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `news`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `news_likes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `news_tags`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `news_tag_links`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `news_views`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `permissions`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=82;
ALTER TABLE `statics`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `subscribes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_auth`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_balance`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_banip`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_groups`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
ALTER TABLE `user_likes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_permissions`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_tokens`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

INSERT INTO `group_permissions` (`id`, `group_id`, `permission_id`, `value`, `user_id_create`, `user_id_update`, `date_create`, `date_update`) VALUES
(1, 1, 1, 'true', 1, 1, 5553535, 5553535),
(2, 1, 2, 'true', 1, 1, 5553535, 5553535),
(3, 1, 3, 'true', 1, 1, 5553535, 5553535),
(4, 1, 4, 'true', 1, 1, 5553535, 5553535),
(5, 1, 5, 'true', 1, 1, 5553535, 5553535),
(6, 1, 6, 'true', 1, 1, 5553535, 5553535),
(7, 1, 7, 'true', 1, 1, 5553535, 5553535),
(8, 1, 8, 'true', 1, 1, 5553535, 5553535),
(9, 1, 9, 'false', 1, 1, 5553535, 5553535),
(10, 1, 10, 'false', 1, 1, 5553535, 5553535),
(11, 1, 11, 'true', 1, 1, 5553535, 5553535),
(12, 1, 12, 'true', 1, 1, 5553535, 5553535),
(13, 1, 13, 'true', 1, 1, 5553535, 5553535),
(14, 1, 14, 'true', 1, 1, 5553535, 5553535),
(15, 1, 15, 'true', 1, 1, 5553535, 5553535),
(16, 1, 16, 'false', 1, 1, 5553535, 5553535),
(17, 1, 17, 'true', 1, 1, 5553535, 5553535),
(18, 1, 18, 'true', 1, 1, 5553535, 5553535),
(19, 1, 19, 'png,jpg,jpeg', 1, 1, 5553535, 5553535),
(20, 1, 20, '2097152', 1, 1, 5553535, 5553535),
(21, 1, 21, '64x64', 1, 1, 5553535, 5553535),
(22, 1, 22, '256x256', 1, 1, 5553535, 5553535),
(23, 1, 23, 'true', 1, 1, 5553535, 5553535),
(24, 1, 24, 'true', 1, 1, 5553535, 5553535),
(25, 1, 25, 'true', 1, 1, 5553535, 5553535),
(26, 1, 26, 'true', 1, 1, 5553535, 5553535),
(27, 1, 27, 'true', 1, 1, 5553535, 5553535),
(28, 1, 28, 'true', 1, 1, 5553535, 5553535),
(29, 1, 29, 'false', 1, 1, 5553535, 5553535),
(30, 1, 30, 'false', 1, 1, 5553535, 5553535),
(31, 1, 31, 'true', 1, 1, 5553535, 5553535),
(32, 1, 32, 'false', 1, 1, 5553535, 5553535),
(33, 1, 33, 'true', 1, 1, 5553535, 5553535),
(34, 1, 34, 'true', 1, 1, 5553535, 5553535),
(35, 1, 35, 'false', 1, 1, 5553535, 5553535),
(36, 1, 36, 'true', 1, 1, 5553535, 5553535),
(37, 1, 37, 'true', 1, 1, 5553535, 5553535),
(38, 1, 38, 'true', 1, 1, 5553535, 5553535),
(39, 1, 39, 'true', 1, 1, 5553535, 5553535),
(40, 1, 40, 'true', 1, 1, 5553535, 5553535),
(41, 1, 41, 'true', 1, 1, 5553535, 5553535),
(42, 1, 42, 'false', 1, 1, 5553535, 5553535),
(43, 1, 43, 'false', 1, 1, 5553535, 5553535),
(44, 1, 44, 'true', 1, 1, 5553535, 5553535),
(45, 1, 45, 'true', 1, 1, 5553535, 5553535),
(46, 1, 46, 'false', 1, 1, 5553535, 5553535),
(47, 1, 47, 'false', 1, 1, 5553535, 5553535),
(48, 1, 48, 'false', 1, 1, 5553535, 5553535),
(49, 1, 49, 'false', 1, 1, 5553535, 5553535),
(50, 1, 50, 'false', 1, 1, 5553535, 5553535),
(51, 1, 51, 'false', 1, 1, 5553535, 5553535),
(52, 1, 52, 'false', 1, 1, 5553535, 5553535),
(53, 1, 53, 'false', 1, 1, 5553535, 5553535),
(54, 1, 54, 'false', 1, 1, 5553535, 5553535),
(55, 1, 55, 'false', 1, 1, 5553535, 5553535),
(56, 1, 56, 'false', 1, 1, 5553535, 5553535),
(57, 1, 57, 'false', 1, 1, 5553535, 5553535),
(58, 1, 58, 'false', 1, 1, 5553535, 5553535),
(59, 1, 59, 'false', 1, 1, 5553535, 5553535),
(60, 1, 60, '0', 1, 1, 5553535, 5553535),
(61, 1, 61, '', 1, 1, 5553535, 5553535),
(62, 1, 62, 'false', 1, 1, 5553535, 5553535),
(63, 1, 63, 'false', 1, 1, 5553535, 5553535),
(64, 1, 64, 'false', 1, 1, 5553535, 5553535),
(65, 1, 65, 'false', 1, 1, 5553535, 5553535),
(66, 1, 66, 'false', 1, 1, 5553535, 5553535),
(67, 1, 67, 'false', 1, 1, 5553535, 5553535),
(68, 1, 68, 'false', 1, 1, 5553535, 5553535),
(69, 1, 69, 'false', 1, 1, 5553535, 5553535),
(70, 1, 70, 'false', 1, 1, 5553535, 5553535),
(71, 1, 71, 'false', 1, 1, 5553535, 5553535),
(72, 1, 72, 'false', 1, 1, 5553535, 5553535),
(73, 1, 73, 'false', 1, 1, 5553535, 5553535),
(74, 1, 74, 'false', 1, 1, 5553535, 5553535),
(75, 1, 75, 'false', 1, 1, 5553535, 5553535),
(76, 1, 76, 'false', 1, 1, 5553535, 5553535),
(77, 1, 77, 'false', 1, 1, 5553535, 5553535),
(78, 1, 78, 'false', 1, 1, 5553535, 5553535),
(79, 1, 79, 'false', 1, 1, 5553535, 5553535),
(80, 1, 80, 'false', 1, 1, 5553535, 5553535),
(81, 1, 81, 'false', 1, 1, 5553535, 5553535),
(82, 2, 1, 'true', 1, 1, 5553535, 5553535),
(83, 2, 2, 'true', 1, 1, 5553535, 5553535),
(84, 2, 3, 'true', 1, 1, 5553535, 5553535),
(85, 2, 4, 'true', 1, 1, 5553535, 5553535),
(86, 2, 5, 'true', 1, 1, 5553535, 5553535),
(87, 2, 6, 'true', 1, 1, 5553535, 5553535),
(88, 2, 7, 'true', 1, 1, 5553535, 5553535),
(89, 2, 8, 'true', 1, 1, 5553535, 5553535),
(90, 2, 9, 'true', 1, 1, 5553535, 5553535),
(91, 2, 10, 'true', 1, 1, 5553535, 5553535),
(92, 2, 11, 'true', 1, 1, 5553535, 5553535),
(93, 2, 12, 'true', 1, 1, 5553535, 5553535),
(94, 2, 13, 'true', 1, 1, 5553535, 5553535),
(95, 2, 14, 'true', 1, 1, 5553535, 5553535),
(96, 2, 15, 'true', 1, 1, 5553535, 5553535),
(97, 2, 16, 'true', 1, 1, 5553535, 5553535),
(98, 2, 17, 'true', 1, 1, 5553535, 5553535),
(99, 2, 18, 'true', 1, 1, 5553535, 5553535),
(100, 2, 19, 'png,jpg,jpeg,gif', 1, 1, 5553535, 5553535),
(101, 2, 20, '8388608', 1, 1, 5553535, 5553535),
(102, 2, 21, '1x1', 1, 1, 5553535, 5553535),
(103, 2, 22, '1024x1024', 1, 1, 5553535, 5553535),
(104, 2, 23, 'true', 1, 1, 5553535, 5553535),
(105, 2, 24, 'true', 1, 1, 5553535, 5553535),
(106, 2, 25, 'true', 1, 1, 5553535, 5553535),
(107, 2, 26, 'true', 1, 1, 5553535, 5553535),
(108, 2, 27, 'true', 1, 1, 5553535, 5553535),
(109, 2, 28, 'true', 1, 1, 5553535, 5553535),
(110, 2, 29, 'true', 1, 1, 5553535, 5553535),
(111, 2, 30, 'true', 1, 1, 5553535, 5553535),
(112, 2, 31, 'true', 1, 1, 5553535, 5553535),
(113, 2, 32, 'true', 1, 1, 5553535, 5553535),
(114, 2, 33, 'true', 1, 1, 5553535, 5553535),
(115, 2, 34, 'true', 1, 1, 5553535, 5553535),
(116, 2, 35, 'true', 1, 1, 5553535, 5553535),
(117, 2, 36, 'true', 1, 1, 5553535, 5553535),
(118, 2, 37, 'true', 1, 1, 5553535, 5553535),
(119, 2, 38, 'true', 1, 1, 5553535, 5553535),
(120, 2, 39, 'true', 1, 1, 5553535, 5553535),
(121, 2, 40, 'true', 1, 1, 5553535, 5553535),
(122, 2, 41, 'true', 1, 1, 5553535, 5553535),
(123, 2, 42, 'true', 1, 1, 5553535, 5553535),
(124, 2, 43, 'true', 1, 1, 5553535, 5553535),
(125, 2, 44, 'true', 1, 1, 5553535, 5553535),
(126, 2, 45, 'true', 1, 1, 5553535, 5553535),
(127, 2, 46, 'true', 1, 1, 5553535, 5553535),
(128, 2, 47, 'true', 1, 1, 5553535, 5553535),
(129, 2, 48, 'true', 1, 1, 5553535, 5553535),
(130, 2, 49, 'true', 1, 1, 5553535, 5553535),
(131, 2, 50, 'true', 1, 1, 5553535, 5553535),
(132, 2, 51, 'true', 1, 1, 5553535, 5553535),
(133, 2, 52, 'true', 1, 1, 5553535, 5553535),
(134, 2, 53, 'true', 1, 1, 5553535, 5553535),
(135, 2, 54, 'true', 1, 1, 5553535, 5553535),
(136, 2, 55, 'true', 1, 1, 5553535, 5553535),
(137, 2, 56, 'true', 1, 1, 5553535, 5553535),
(138, 2, 57, 'true', 1, 1, 5553535, 5553535),
(139, 2, 58, 'true', 1, 1, 5553535, 5553535),
(140, 2, 59, 'true', 1, 1, 5553535, 5553535),
(141, 2, 60, '33554432', 1, 1, 5553535, 5553535),
(142, 2, 61, 'png,jpg,jpeg,gif,zip,txt,rar,psd', 1, 1, 5553535, 5553535),
(143, 2, 62, 'true', 1, 1, 5553535, 5553535),
(144, 2, 63, 'true', 1, 1, 5553535, 5553535),
(145, 2, 64, 'true', 1, 1, 5553535, 5553535),
(146, 2, 65, 'true', 1, 1, 5553535, 5553535),
(147, 2, 66, 'true', 1, 1, 5553535, 5553535),
(148, 2, 67, 'true', 1, 1, 5553535, 5553535),
(149, 2, 68, 'true', 1, 1, 5553535, 5553535),
(150, 2, 69, 'true', 1, 1, 5553535, 5553535),
(151, 2, 70, 'true', 1, 1, 5553535, 5553535),
(152, 2, 71, 'true', 1, 1, 5553535, 5553535),
(153, 2, 72, 'true', 1, 1, 5553535, 5553535),
(154, 2, 73, 'true', 1, 1, 5553535, 5553535),
(155, 2, 74, 'true', 1, 1, 5553535, 5553535),
(156, 2, 75, 'true', 1, 1, 5553535, 5553535),
(157, 2, 76, 'true', 1, 1, 5553535, 5553535),
(158, 2, 77, 'true', 1, 1, 5553535, 5553535),
(159, 2, 78, 'true', 1, 1, 5553535, 5553535),
(160, 2, 79, 'true', 1, 1, 5553535, 5553535),
(161, 2, 80, 'true', 1, 1, 5553535, 5553535),
(162, 2, 81, 'true', 1, 1, 5553535, 5553535),
(163, 3, 1, 'true', 1, 1, 5553535, 5553535),
(164, 3, 2, 'false', 1, 1, 5553535, 5553535),
(165, 3, 3, 'true', 1, 1, 5553535, 5553535),
(166, 3, 4, 'true', 1, 1, 5553535, 5553535),
(167, 3, 5, 'false', 1, 1, 5553535, 5553535),
(168, 3, 6, 'true', 1, 1, 5553535, 5553535),
(169, 3, 7, 'false', 1, 1, 5553535, 5553535),
(170, 3, 8, 'false', 1, 1, 5553535, 5553535),
(171, 3, 9, 'false', 1, 1, 5553535, 5553535),
(172, 3, 10, 'false', 1, 1, 5553535, 5553535),
(173, 3, 11, 'false', 1, 1, 5553535, 5553535),
(174, 3, 12, 'false', 1, 1, 5553535, 5553535),
(175, 3, 13, 'true', 1, 1, 5553535, 5553535),
(176, 3, 14, 'false', 1, 1, 5553535, 5553535),
(177, 3, 15, 'false', 1, 1, 5553535, 5553535),
(178, 3, 16, 'false', 1, 1, 5553535, 5553535),
(179, 3, 17, 'false', 1, 1, 5553535, 5553535),
(180, 3, 18, 'false', 1, 1, 5553535, 5553535),
(181, 3, 19, '', 1, 1, 5553535, 5553535),
(182, 3, 20, '0', 1, 1, 5553535, 5553535),
(183, 3, 21, '0x0', 1, 1, 5553535, 5553535),
(184, 3, 22, '0x0', 1, 1, 5553535, 5553535),
(185, 3, 23, 'true', 1, 1, 5553535, 5553535),
(186, 3, 24, 'true', 1, 1, 5553535, 5553535),
(187, 3, 25, 'false', 1, 1, 5553535, 5553535),
(188, 3, 26, 'false', 1, 1, 5553535, 5553535),
(189, 3, 27, 'false', 1, 1, 5553535, 5553535),
(190, 3, 28, 'true', 1, 1, 5553535, 5553535),
(191, 3, 29, 'false', 1, 1, 5553535, 5553535),
(192, 3, 30, 'false', 1, 1, 5553535, 5553535),
(193, 3, 31, 'false', 1, 1, 5553535, 5553535),
(194, 3, 32, 'false', 1, 1, 5553535, 5553535),
(195, 3, 33, 'true', 1, 1, 5553535, 5553535),
(196, 3, 34, 'false', 1, 1, 5553535, 5553535),
(197, 3, 35, 'false', 1, 1, 5553535, 5553535),
(198, 3, 36, 'false', 1, 1, 5553535, 5553535),
(199, 3, 37, 'false', 1, 1, 5553535, 5553535),
(200, 3, 38, 'false', 1, 1, 5553535, 5553535),
(201, 3, 39, 'false', 1, 1, 5553535, 5553535),
(202, 3, 40, 'false', 1, 1, 5553535, 5553535),
(203, 3, 41, 'false', 1, 1, 5553535, 5553535),
(204, 3, 42, 'false', 1, 1, 5553535, 5553535),
(205, 3, 43, 'false', 1, 1, 5553535, 5553535),
(206, 3, 44, 'false', 1, 1, 5553535, 5553535),
(207, 3, 45, 'false', 1, 1, 5553535, 5553535),
(208, 3, 46, 'false', 1, 1, 5553535, 5553535),
(209, 3, 47, 'false', 1, 1, 5553535, 5553535),
(210, 3, 48, 'false', 1, 1, 5553535, 5553535),
(211, 3, 49, 'false', 1, 1, 5553535, 5553535),
(212, 3, 50, 'false', 1, 1, 5553535, 5553535),
(213, 3, 51, 'false', 1, 1, 5553535, 5553535),
(214, 3, 52, 'false', 1, 1, 5553535, 5553535),
(215, 3, 53, 'false', 1, 1, 5553535, 5553535),
(216, 3, 54, 'false', 1, 1, 5553535, 5553535),
(217, 3, 55, 'false', 1, 1, 5553535, 5553535),
(218, 3, 56, 'false', 1, 1, 5553535, 5553535),
(219, 3, 57, 'false', 1, 1, 5553535, 5553535),
(220, 3, 58, 'false', 1, 1, 5553535, 5553535),
(221, 3, 59, 'false', 1, 1, 5553535, 5553535),
(222, 3, 60, '0', 1, 1, 5553535, 5553535),
(223, 3, 61, '', 1, 1, 5553535, 5553535),
(224, 3, 62, 'false', 1, 1, 5553535, 5553535),
(225, 3, 63, 'false', 1, 1, 5553535, 5553535),
(226, 3, 64, 'false', 1, 1, 5553535, 5553535),
(227, 3, 65, 'false', 1, 1, 5553535, 5553535),
(228, 3, 66, 'false', 1, 1, 5553535, 5553535),
(229, 3, 67, 'false', 1, 1, 5553535, 5553535),
(230, 3, 68, 'false', 1, 1, 5553535, 5553535),
(231, 3, 69, 'false', 1, 1, 5553535, 5553535),
(232, 3, 70, 'false', 1, 1, 5553535, 5553535),
(233, 3, 71, 'false', 1, 1, 5553535, 5553535),
(234, 3, 72, 'false', 1, 1, 5553535, 5553535),
(235, 3, 73, 'false', 1, 1, 5553535, 5553535),
(236, 3, 74, 'false', 1, 1, 5553535, 5553535),
(237, 3, 75, 'false', 1, 1, 5553535, 5553535),
(238, 3, 76, 'false', 1, 1, 5553535, 5553535),
(239, 3, 77, 'false', 1, 1, 5553535, 5553535),
(240, 3, 78, 'false', 1, 1, 5553535, 5553535),
(241, 3, 79, 'false', 1, 1, 5553535, 5553535),
(242, 3, 80, 'false', 1, 1, 5553535, 5553535),
(243, 3, 81, 'false', 1, 1, 5553535, 5553535),
(244, 4, 1, 'true', 1, 1, 5553535, 5553535),
(245, 4, 2, 'true', 1, 1, 5553535, 5553535),
(246, 4, 3, 'true', 1, 1, 5553535, 5553535),
(247, 4, 4, 'true', 1, 1, 5553535, 5553535),
(248, 4, 5, 'true', 1, 1, 5553535, 5553535),
(249, 4, 6, 'true', 1, 1, 5553535, 5553535),
(250, 4, 7, 'true', 1, 1, 5553535, 5553535),
(251, 4, 8, 'true', 1, 1, 5553535, 5553535),
(252, 4, 9, 'true', 1, 1, 5553535, 5553535),
(253, 4, 10, 'true', 1, 1, 5553535, 5553535),
(254, 4, 11, 'true', 1, 1, 5553535, 5553535),
(255, 4, 12, 'true', 1, 1, 5553535, 5553535),
(256, 4, 13, 'true', 1, 1, 5553535, 5553535),
(257, 4, 14, 'true', 1, 1, 5553535, 5553535),
(258, 4, 15, 'true', 1, 1, 5553535, 5553535),
(259, 4, 16, 'true', 1, 1, 5553535, 5553535),
(260, 4, 17, 'true', 1, 1, 5553535, 5553535),
(261, 4, 18, 'true', 1, 1, 5553535, 5553535),
(262, 4, 19, 'jpg,jpeg,png,gif', 1, 1, 5553535, 5553535),
(263, 4, 20, '2097152', 1, 1, 5553535, 5553535),
(264, 4, 21, '64x64', 1, 1, 5553535, 5553535),
(265, 4, 22, '512x512', 1, 1, 5553535, 5553535),
(266, 4, 23, 'true', 1, 1, 5553535, 5553535),
(267, 4, 24, 'true', 1, 1, 5553535, 5553535),
(268, 4, 25, 'true', 1, 1, 5553535, 5553535),
(269, 4, 26, 'true', 1, 1, 5553535, 5553535),
(270, 4, 27, 'true', 1, 1, 5553535, 5553535),
(271, 4, 28, 'true', 1, 1, 5553535, 5553535),
(272, 4, 29, 'true', 1, 1, 5553535, 5553535),
(273, 4, 30, 'true', 1, 1, 5553535, 5553535),
(274, 4, 31, 'true', 1, 1, 5553535, 5553535),
(275, 4, 32, 'true', 1, 1, 5553535, 5553535),
(276, 4, 33, 'true', 1, 1, 5553535, 5553535),
(277, 4, 34, 'true', 1, 1, 5553535, 5553535),
(278, 4, 35, 'false', 1, 1, 5553535, 5553535),
(279, 4, 36, 'true', 1, 1, 5553535, 5553535),
(280, 4, 37, 'true', 1, 1, 5553535, 5553535),
(281, 4, 38, 'true', 1, 1, 5553535, 5553535),
(282, 4, 39, 'true', 1, 1, 5553535, 5553535),
(283, 4, 40, 'true', 1, 1, 5553535, 5553535),
(284, 4, 41, 'true', 1, 1, 5553535, 5553535),
(285, 4, 42, 'true', 1, 1, 5553535, 5553535),
(286, 4, 43, 'true', 1, 1, 5553535, 5553535),
(287, 4, 44, 'true', 1, 1, 5553535, 5553535),
(288, 4, 45, 'true', 1, 1, 5553535, 5553535),
(289, 4, 46, 'false', 1, 1, 5553535, 5553535),
(290, 4, 47, 'false', 1, 1, 5553535, 5553535),
(291, 4, 48, 'false', 1, 1, 5553535, 5553535),
(292, 4, 49, 'false', 1, 1, 5553535, 5553535),
(293, 4, 50, 'false', 1, 1, 5553535, 5553535),
(294, 4, 51, 'false', 1, 1, 5553535, 5553535),
(295, 4, 52, 'false', 1, 1, 5553535, 5553535),
(296, 4, 53, 'false', 1, 1, 5553535, 5553535),
(297, 4, 54, 'false', 1, 1, 5553535, 5553535),
(298, 4, 55, 'false', 1, 1, 5553535, 5553535),
(299, 4, 56, 'false', 1, 1, 5553535, 5553535),
(300, 4, 57, 'false', 1, 1, 5553535, 5553535),
(301, 4, 58, 'false', 1, 1, 5553535, 5553535),
(302, 4, 59, 'false', 1, 1, 5553535, 5553535),
(303, 4, 60, '0', 1, 1, 5553535, 5553535),
(304, 4, 61, '', 1, 1, 5553535, 5553535),
(305, 4, 62, 'false', 1, 1, 5553535, 5553535),
(306, 4, 63, 'false', 1, 1, 5553535, 5553535),
(307, 4, 64, 'false', 1, 1, 5553535, 5553535),
(308, 4, 65, 'false', 1, 1, 5553535, 5553535),
(309, 4, 66, 'false', 1, 1, 5553535, 5553535),
(310, 4, 67, 'false', 1, 1, 5553535, 5553535),
(311, 4, 68, 'false', 1, 1, 5553535, 5553535),
(312, 4, 69, 'false', 1, 1, 5553535, 5553535),
(313, 4, 70, 'false', 1, 1, 5553535, 5553535),
(314, 4, 71, 'false', 1, 1, 5553535, 5553535),
(315, 4, 72, 'false', 1, 1, 5553535, 5553535),
(316, 4, 73, 'false', 1, 1, 5553535, 5553535),
(317, 4, 74, 'false', 1, 1, 5553535, 5553535),
(318, 4, 75, 'false', 1, 1, 5553535, 5553535),
(319, 4, 76, 'false', 1, 1, 5553535, 5553535),
(320, 4, 77, 'false', 1, 1, 5553535, 5553535),
(321, 4, 78, 'false', 1, 1, 5553535, 5553535),
(322, 4, 79, 'false', 1, 1, 5553535, 5553535),
(323, 4, 80, 'false', 1, 1, 5553535, 5553535),
(324, 4, 81, 'false', 1, 1, 5553535, 5553535);


INSERT INTO `permissions` (`id`, `title`, `name`, `default`, `system`, `type`, `user_id_create`, `user_id_update`, `date_create`, `date_update`) VALUES
(1, 'Общий доступ', 'main', 'true', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(2, 'Авторизация', 'auth', 'true', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(3, 'Просмотр списка новостей', 'news_list', 'true', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(4, 'Полный просмотр новостей', 'news_view', 'true', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(5, 'Добавление комментариев в новостях', 'news_comments_add', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(6, 'Просмотр комментариев в новостях', 'news_comments_view', 'true', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(7, 'Редактирование своих комментариев в новостях', 'news_comments_edit', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(8, 'Удаление своих комментариев в новостях', 'news_comments_remove', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(9, 'Редактирование всех комментариев в новостях', 'news_comments_edit_all', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(10, 'Удаление всех комментариев в новостях', 'news_comments_remove_all', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(11, 'Оценивание новостей', 'news_like', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(12, 'Добавление комментариев в собственном профиле', 'profile_comments_add', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(13, 'Просмотр комментариев в собственном профиле', 'profile_comments_view', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(14, 'Редактирование своих комментариев в собственном профиле', 'profile_comment_edit', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(15, 'Удаление своих комментариев в собственном профиле', 'profile_comment_remove', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(16, 'Редактирование всех комментариев в собственном профиле', 'profile_comment_edit_all', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(17, 'Удаление всех комментариев в собственном профиле', 'profile_comment_remove_all', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(18, 'Изменение аватара', 'profile_avatar_change', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(19, 'Допустимые расширения файла аватара', 'profile_avatar_extensions', 'jpg,jpeg,png', 1, 'string', 1, 1, 1005553535, 1005553535),
(20, 'Максимальный размер файла аватара', 'profile_avatar_max_filesize', '2097152', 1, 'integer', 1, 1, 1005553535, 1005553535),
(21, 'Минимальное разрешение аватара', 'profile_avatar_min_size', '64x32', 1, 'string', 1, 1, 1005553535, 1005553535),
(22, 'Максимальное разрешение аватара', 'profile_avatar_max_size', '64x32', 1, 'string', 1, 1, 1005553535, 1005553535),
(23, 'Просмотр списка личных сообщений', 'profile_messages_list', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(24, 'Полный просмотр личных сообщений', 'profile_messages_view', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(25, 'Удаление личных сообщений', 'profile_messages_remove', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(26, 'Отправка личных сообщений', 'profile_messages_send', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(27, 'Добавление ответов в личных сообщениях', 'profile_messages_reply_add', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(28, 'Просмотр ответов в личных сообщения', 'profile_messages_reply_view', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(29, 'Редактирование своих ответов в личных сообщениях', 'profile_messages_reply_edit', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(30, 'Удаление своих ответов в личных сообщениях', 'profile_messages_reply_remove', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(31, 'Закрытие созданных личных сообщений', 'profile_messages_lock_self', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(32, 'Закрытие всех полученных личных сообщений', 'profile_messages_lock', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(33, 'Просмотр истории активности', 'profile_activity_list', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(34, 'Доступ к настройкам профиля', 'profile_settings', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(35, 'Основной доступ к панели управления', 'admin', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(36, 'Просмотр списка пользователей', 'users', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(37, 'Полный просмотр профиля пользователя', 'user', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(38, 'Добавление комментариев в чужом профиле', 'users_comments_add', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(39, 'Просмотр комментариев в чужом профиле', 'users_comments_view', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(40, 'Редактирование своих комментариев в чужом профиле', 'users_comments_edit', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(41, 'Удаление своих комментариев в чужом профиле', 'users_comments_remove', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(42, 'Редактирование всех комментариев в чужом профиле', 'users_comments_edit_all', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(43, 'Удаление всех комментариев в чужом профиле', 'users_comments_remove_all', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(44, 'Подписка на пользователей', 'users_subscribe', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(45, 'Поиск пользователей', 'users_search', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(46, 'Доступ к просмотру списка статических страниц в ПУ', 'admin_statics_index', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(47, 'Доступ к редактированию статических страниц в ПУ', 'admin_statics_edit', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(48, 'Доступ к добавлению статических страниц в ПУ', 'admin_statics_add', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(49, 'Доступ к удалению статических страниц в ПУ', 'admin_statics_remove', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(50, 'Доступ к публикации статических страниц в ПУ', 'admin_statics_public', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(51, 'Доступ к просмотру списка тегов новостей в ПУ', 'admin_news_tags_index', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(52, 'Доступ к редактированию тегов новостей в ПУ', 'admin_news_tags_edit', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(53, 'Доступ к добавлению тегов новостей в ПУ', 'admin_news_tags_add', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(54, 'Доступ к удалению тегов новостей в ПУ', 'admin_news_tags_remove', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(55, 'Доступ к просмотру списка новостей в ПУ', 'admin_news_index', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(56, 'Доступ к редактированию новостей в ПУ', 'admin_news_edit', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(57, 'Доступ к добавлению новостей в ПУ', 'admin_news_add', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(58, 'Доступ к удалению новостей в ПУ', 'admin_news_remove', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(59, 'Доступ к публикации новостей в ПУ', 'admin_news_public', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(60, 'Максимальный размер загружаемого файла', 'file_uploader_max_size', '0', 1, 'integer', 1, 1, 1005553535, 1005553535),
(61, 'Допустимые расширения загружаемых файлов', 'file_uploader_extensions', '', 1, 'string', 1, 1, 1005553535, 1005553535),
(62, 'Использование файлового загрузчика', 'file_uploader', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(63, 'Доступ к просмотру списка пользователей в ПУ', 'admin_users_index', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(64, 'Доступ к редактированию пользователей в ПУ', 'admin_users_edit', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(65, 'Доступ к добавлению пользователей в ПУ', 'admin_users_add', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(66, 'Доступ к удалению пользователей в ПУ', 'admin_users_remove', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(67, 'Доступ к блокировке пользователей в ПУ', 'admin_users_ban', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(68, 'Доступ к блокировке пользователей по IP в ПУ', 'admin_users_banip', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(69, 'Доступ к изменению группы пользователей в ПУ', 'admin_users_group_change', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(70, 'Доступ к очистке пользователей в ПУ', 'admin_users_clear', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(71, 'Доступ к просмотру списка привилегий в ПУ', 'admin_permissions_index', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(72, 'Доступ к добавлению привилегий в ПУ', 'admin_permissions_add', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(73, 'Доступ к редактированию привилегий в ПУ', 'admin_permissions_edit', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(74, 'Доступ к удалению привилегий в ПУ', 'admin_permissions_remove', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(75, 'Доступ к удалению группы пользователей в ПУ', 'admin_groups_remove', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(76, 'Доступ к просмотру списка групп пользователей в ПУ', 'admin_groups_index', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(77, 'Доступ к добавлению групп пользователей в ПУ', 'admin_groups_add', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(78, 'Доступ к редактированию групп пользователей в ПУ', 'admin_groups_edit', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(79, 'Доступ к просмотру логов действий в ПУ', 'admin_logs_index', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(80, 'Доступ к удалению логов действий в ПУ', 'admin_logs_remove', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535),
(81, 'Доступ к управлению настройками сайта', 'admin_settings', 'false', 1, 'boolean', 1, 1, 1005553535, 1005553535);

INSERT INTO `user_groups` (`id`, `title`, `name`, `text`, `date_create`, `date_update`, `user_id_create`, `user_id_update`) VALUES
(1, 'Пользователи', 'user', 'Зарегистрированные пользователи', 1522276314, 1523651421, 1, 1),
(2, 'Администраторы', 'admin', 'Администраторы сайта', 1522276314, 1524650300, 1, 1),
(3, 'Заблокированные', 'banned', 'Заблокированные пользователи', 1523651064, 1523651064, 1, 1),
(4, 'Модераторы', 'moder', 'Модераторы сайта', 1523651834, 1523651834, 1, 1);

INSERT INTO `statics` (`id`, `route`, `title`, `text`, `permission`, `status`, `user_id_create`, `user_id_update`, `date_create`, `date_update`) VALUES
(1, '404/', 'Страница не найдена', '<!DOCTYPE HTML>\n<html>\n<head>\n    <title>{{pagename}} | {{__META__.sitename}}</title>\n\n    {{include(''header.tpl'')}}\n</head>\n\n<body>\n\n{{ include(''navbar.tpl'') }}\n\n<div class="container p-20">\n    <div class="window m-auto w-50">\n        <div class="text-center"><h2>Ошибка 404</h2></div>\n\n        <div class="text-center pt-40"><b>Страница не найдена</b></div>\n    </div>\n</div>\n\n{{include(''footer.tpl'')}}\n</body>\n</html>', '', 1, 1, 1, 1553220092, 1553221663),
(2, '403/', 'Доступ запрещен', '<!DOCTYPE HTML>\n<html>\n<head>\n    <title>{{pagename}} | {{__META__.sitename}}</title>\n\n    {{include(''header.tpl'')}}\n</head>\n\n<body>\n\n{{ include(''navbar.tpl'') }}\n\n<div class="container p-20">\n    <div class="window m-auto w-50">\n        <div class="text-center"><h2>Ошибка 403</h2></div>\n\n        <div class="text-center pt-40"><b>Доступ к выбранной странице ограничен администрацией</b></div>\n    </div>\n</div>\n\n{{include(''footer.tpl'')}}\n</body>\n</html>', '', 1, 1, 1, 1553221722, 1553221895),
(3, 'contacts/', 'Контакты', '<!DOCTYPE HTML>\n<html>\n    <head>\n        <title>{{pagename}} | {{__META__.sitename}}</title>\n\n        {{include(''header.tpl'')}}\n    </head>\n\n    <body>\n\n        {{ include(''navbar.tpl'') }}\n\n        <div class="container p-20">\n            <div class="window m-auto w-50">\n                      <div class="text-center"><h2>Контакты</h2></div>\n                      \n                      <div class="text-center pt-40"><b><a href="https://vk.com/webmcr" target="_blank">VK: WebMCR</a></b></div>\n           </div>\n        </div>\n\n    {{include(''footer.tpl'')}}\n    </body>\n</html>', '', 1, 1, 1, 1553222124, 1553222128),
(4, 'tos/', 'Условия использования', '<!DOCTYPE HTML>\n<html>\n    <head>\n        <title>{{pagename}} | {{__META__.sitename}}</title>\n\n        {{include(''header.tpl'')}}\n    </head>\n\n    <body>\n\n        {{ include(''navbar.tpl'') }}\n\n        <div class="container p-20">\n            <div class="window">\n                      <div class="text-center"><h2>Условия использования</h2></div>\n                      \n                      <div class="pt-40">\n\n<p>Настоящее Соглашение определяет условия использования Пользователями материалов и сервисов данного сайта.        (далее — «Сайт»).</p>\n\n<p><b>1.Общие условия</b></p>\n\n<p>1.1. Использование материалов и сервисов Сайта регулируется нормами действующего законодательства Российской Федерации.</p>\n\n<p>1.2. Настоящее Соглашение является публичной офертой. Получая доступ к материалам Сайта Пользователь считается присоединившимся к настоящему Соглашению.</p> \n\n<p>1.3. Администрация Сайта вправе в любое время в одностороннем порядке изменять условия настоящего Соглашения. Такие изменения вступают в силу по истечении 3 (Трех) дней с момента размещения новой версии Соглашения на сайте. При несогласии Пользователя с внесенными изменениями он обязан отказаться от доступа к Сайту, прекратить использование материалов и сервисов Сайта.</p>\n\n<br>\n\n<p><b>2. Обязательства Пользователя</b></p>\n\n<p>2.1. Пользователь соглашается не предпринимать действий, которые могут рассматриваться как нарушающие российское законодательство или нормы международного права, в том числе в сфере интеллектуальной собственности, авторских и/или смежных правах, а также любых действий, которые приводят или могут привести к нарушению нормальной работы Сайта и сервисов Сайта.</p>\n\n<p>2.2. Использование материалов Сайта без согласия правообладателей не допускается (статья 1270 Г.К РФ). Для правомерного использования материалов Сайта необходимо заключение лицензионных договоров (получение лицензий) от Правообладателей.</p>\n\n<p>2.3. При цитировании материалов Сайта, включая охраняемые авторские произведения, ссылка на Сайт обязательна (подпункт 1 пункта 1 статьи 1274 Г.К РФ).</p>\n\n<p>2.4. Комментарии и иные записи Пользователя на Сайте не должны вступать в противоречие с требованиями законодательства Российской Федерации и общепринятых норм морали и нравственности.</p>\n\n<p>2.5. Пользователь предупрежден о том, что Администрация Сайта не несет ответственности за посещение и использование им внешних ресурсов, ссылки на которые могут содержаться на сайте.</p>\n\n<p>2.6. Пользователь согласен с тем, что Администрация Сайта не несет ответственности и не имеет прямых или косвенных обязательств перед Пользователем в связи с любыми возможными или возникшими потерями или убытками, связанными с любым содержанием Сайта, регистрацией авторских прав и сведениями о такой регистрации, товарами или услугами, доступными на или полученными через внешние сайты или ресурсы либо иные контакты Пользователя, в которые он вступил, используя размещенную на Сайте информацию или ссылки на внешние ресурсы.</p>\n\n<p>2.7. Пользователь принимает положение о том, что все материалы и сервисы Сайта или любая их часть могут сопровождаться рекламой. Пользователь согласен с тем, что Администрация Сайта не несет какой-либо ответственности и не имеет каких-либо обязательств в связи с такой рекламой.</p>\n\n<br>\n\n<p><b>3. Прочие условия</b></p>\n\n<p>3.1. Все возможные споры, вытекающие из настоящего Соглашения или связанные с ним, подлежат разрешению в соответствии с действующим законодательством Российской Федерации.</p>\n\n<p>3.2. Ничто в Соглашении не может пониматься как установление между Пользователем и Администрации Сайта агентских отношений, отношений товарищества, отношений по совместной деятельности, отношений личного найма, либо каких-то иных отношений, прямо не предусмотренных Соглашением.</p>\n\n<p>3.3. Признание судом какого-либо положения Соглашения недействительным или не подлежащим принудительному исполнению не влечет недействительности иных положений Соглашения.</p>\n\n<p>3.4. Бездействие со стороны Администрации Сайта в случае нарушения кем-либо из Пользователей положений Соглашения не лишает Администрацию Сайта права предпринять позднее соответствующие действия в защиту своих интересов и защиту авторских прав на охраняемые в соответствии с законодательством материалы Сайта.\nПользователь подтверждает, что ознакомлен со всеми пунктами настоящего Соглашения и безусловно принимает их.</p>\n</div>\n           </div>\n        </div>\n\n    {{include(''footer.tpl'')}}\n    </body>\n</html>', '', 1, 1, 1, 1553226904, 1553226930);
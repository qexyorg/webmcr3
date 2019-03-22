SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL DEFAULT '1',
  `email` varchar(128) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `salt` varchar(64) NOT NULL DEFAULT '',
  `login` varchar(32) NOT NULL DEFAULT '',
  `gender` tinyint(1) NOT NULL DEFAULT '0',
  `firstname` varchar(32) NOT NULL DEFAULT '',
  `lastname` varchar(32) NOT NULL DEFAULT '',
  `avatar` varchar(255) NOT NULL DEFAULT '',
  `birthday` int(10) NOT NULL DEFAULT '0',
  `about` text NOT NULL,
  `date_create` int(10) NOT NULL DEFAULT '0',
  `date_update` int(10) NOT NULL DEFAULT '0',
  `ip_create` varchar(16) NOT NULL DEFAULT '127.0.0.1',
  `ip_update` varchar(16) NOT NULL DEFAULT '127.0.0.1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `login` (`login`);
  
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
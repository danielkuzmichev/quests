CREATE DATABASE IF NOT EXISTS quests;
USE quests;

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `cost` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tasks` (`id`, `title`, `description`, `cost`) VALUES
(1,	'test1',	NULL,	10),
(2,	'test2',	NULL,	15),
(3,	'test3',	NULL,	15);

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `balance` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `balance`) VALUES
(1,	'john',	10),
(2,	'vanya', 0),
(3,	'jean',	0);

DROP TABLE IF EXISTS `usertasks`;
CREATE TABLE `usertasks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `task_id` int DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_completion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_77FC5945A76ED395` (`user_id`),
  KEY `IDX_77FC59458DB60186` (`task_id`),
  CONSTRAINT `FK_77FC59458DB60186` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_77FC5945A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `usertasks` (`id`, `user_id`, `task_id`, `status`, `date_of_completion`) VALUES
(1,	1,	1,	'completed',	'2024-03-19 15:19:37'),
(2,	1,	2,	'not_completed',	NULL),
(3,	2,	1,	'not_completed',	NULL);

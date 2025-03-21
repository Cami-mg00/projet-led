
CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `date` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `article_categories` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `dbms_relaydomains` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `domain` varchar(256) NOT NULL,
  `serverid` varchar(24) NULL,
  `userid` int(8) NULL,
  `sourceexec` varchar(12) NOT NULL,
  `ovrservername` varchar(512) NULL,
  `ovrserverport` varchar(512) NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain` (`domain`)
) ENGINE=InnoDB AUTO_INCREMENT=1243 DEFAULT CHARSET=utf8mb4;


CREATE TABLE `dbms_servers` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `servername` varchar(512) NOT NULL,
  `port` int(12) NOT NULL,
  `serverdescr` varchar(512) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;


CREATE TABLE `dbms_users` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `user` varchar(256) NOT NULL,
  `pass` varchar(256) NOT NULL,
  `rank` varchar(25) NOT NULL,
  `tries` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;


LOCK TABLES `dbms_users` WRITE;
/*!40000 ALTER TABLE `bxc_users` DISABLE KEYS */;
INSERT INTO `dbms_users` VALUES (1,'admin','$2y$10$WSzFfKCSzWKRTDkFkAoWFOcX.W9pGLwnGFkhZweFMRxzWWmZp/3xO','admin',1);
/*!40000 ALTER TABLE `bxc_users` ENABLE KEYS */;
UNLOCK TABLES;

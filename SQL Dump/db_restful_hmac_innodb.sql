SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `api_application`
-- ----------------------------
DROP TABLE IF EXISTS `api_application`;
CREATE TABLE `api_application` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `api_key` varchar(500) NOT NULL,
  `api_secret_key` varchar(500) NOT NULL,
  `add_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of api_application
-- ----------------------------
INSERT INTO `api_application` VALUES ('1', 'PHP Restful HMAC', 'b0882ec326528cbdb8617813b5c0c5eccc7537b2', 'afccb252d578f7c7529f253ebea35a3a82d8054e', '2015-12-11 13:37:20');

-- ----------------------------
-- Table structure for `api_entity`
-- ----------------------------
DROP TABLE IF EXISTS `api_entity`;
CREATE TABLE `api_entity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api_application_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `active` bit(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  KEY `FK_api_entity_api_application_id` (`api_application_id`),
  CONSTRAINT `FK_api_entity_api_application_id` FOREIGN KEY (`api_application_id`) REFERENCES `api_application` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of api_entity
-- ----------------------------
INSERT INTO `api_entity` VALUES ('1', '1', 'user', '');

-- ----------------------------
-- Table structure for `api_entity_service`
-- ----------------------------
DROP TABLE IF EXISTS `api_entity_service`;
CREATE TABLE `api_entity_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api_entity_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(200) NOT NULL,
  `http_verb` varchar(10) NOT NULL,
  `active` bit(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `FK_api_entity_service_api_entity_id` (`api_entity_id`),
  CONSTRAINT `FK_api_entity_service_api_entity_id` FOREIGN KEY (`api_entity_id`) REFERENCES `api_entity` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of api_entity_service
-- ----------------------------
INSERT INTO `api_entity_service` VALUES ('1', '1', 'create', 'Create a new user', 'POST', '');
INSERT INTO `api_entity_service` VALUES ('2', '1', 'update', 'Update an existing user', 'PUT', '');
INSERT INTO `api_entity_service` VALUES ('3', '1', 'delete', 'Delete an existing user', 'DELETE', '');

-- ----------------------------
-- Table structure for `api_log`
-- ----------------------------
DROP TABLE IF EXISTS `api_log`;
CREATE TABLE `api_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `api_application_key` varchar(50) NOT NULL,
  `content` longtext,
  `user_token` varchar(30) NOT NULL,
  `entity` varchar(200) NOT NULL,
  `method` varchar(200) NOT NULL,
  `parameter` varchar(255) DEFAULT NULL,
  `http_verb` varchar(10) NOT NULL,
  `client_ip` varchar(50) NOT NULL,
  `server_ip` varchar(50) NOT NULL,
  `add_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_api_log_user_id` (`user_id`),
  CONSTRAINT `FK_api_log_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of api_log
-- ----------------------------

-- ----------------------------
-- Table structure for `api_profile`
-- ----------------------------
DROP TABLE IF EXISTS `api_profile`;
CREATE TABLE `api_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `active` bit(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of api_profile
-- ----------------------------
INSERT INTO `api_profile` VALUES ('1', 'Admin', '');

-- ----------------------------
-- Table structure for `api_profile_service`
-- ----------------------------
DROP TABLE IF EXISTS `api_profile_service`;
CREATE TABLE `api_profile_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api_entity_service_id` int(11) NOT NULL,
  `api_profile_id` int(11) NOT NULL,
  `active` bit(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `FK_api_profile_service_api_entity_service_id` (`api_entity_service_id`),
  KEY `FK_api_profile_service_api_profile_id` (`api_profile_id`),
  CONSTRAINT `FK_api_profile_service_api_entity_service_id` FOREIGN KEY (`api_entity_service_id`) REFERENCES `api_entity_service` (`id`),
  CONSTRAINT `FK_api_profile_service_api_profile_id` FOREIGN KEY (`api_profile_id`) REFERENCES `api_profile` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of api_profile_service
-- ----------------------------
INSERT INTO `api_profile_service` VALUES ('1', '1', '1', '');
INSERT INTO `api_profile_service` VALUES ('2', '2', '1', '');
INSERT INTO `api_profile_service` VALUES ('3', '3', '1', '');

-- ----------------------------
-- Table structure for `api_user_token`
-- ----------------------------
DROP TABLE IF EXISTS `api_user_token`;
CREATE TABLE `api_user_token` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `user_token` varchar(255) NOT NULL,
  `client_ip` varchar(30) NOT NULL,
  `user_agent` varchar(200) DEFAULT NULL,
  `add_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `api_token_UNIQUE` (`user_token`),
  KEY `FK_api_user_token_user_id` (`user_id`),
  CONSTRAINT `FK_api_user_token_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of api_user_token
-- ----------------------------

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `api_profile_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(50) NOT NULL,
  `active` bit(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `FK_user_api_profile_id` (`api_profile_id`),
  CONSTRAINT `FK_user_api_profile_id` FOREIGN KEY (`api_profile_id`) REFERENCES `api_profile` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', '1', 'Magash Zirion', 'john.doe@email.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '');

-- ----------------------------
-- Procedure structure for `sp_auth_user`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_auth_user`;
DELIMITER ;;
CREATE PROCEDURE `sp_auth_user`(
 user_table VARCHAR(1024), 
 user_table_field_id VARCHAR(1024),
 user_table_field_status VARCHAR(1024),
 api_token VARCHAR(1024), 
 entity VARCHAR(150), 
 service VARCHAR(150), 
 http_verb VARCHAR(150))
BEGIN
DECLARE USER_ID BIGINT(20) DEFAULT 0;
SET @VAR_QUERY =
CONCAT('SELECT ', user_table, '.', user_table_field_id, '
		  FROM api_user_token
			 , api_entity
			 , api_entity_service
			 , api_profile_service
			 , api_profile
			 , ', user_table, ' 
		 WHERE api_user_token.user_id = ', user_table, '.', user_table_field_id, '
		   AND api_profile.id = ', user_table, '.api_profile_id
		   AND api_profile_service.api_profile_id = api_profile.id
		   AND api_entity_service.id = api_profile_service.api_entity_service_id
		   AND api_entity.id = api_entity_service.api_entity_id
		   AND api_entity.active = 1
		   AND api_entity_service.active = 1
		   AND api_profile.active = 1
		   AND api_profile_service.active = 1
		   AND ', user_table, '.', user_table_field_status, ' = 1
		   AND api_entity_service.http_verb = \'', http_verb,'\'
		   AND api_entity.name = \'', entity, '\'
		   AND api_entity_service.name = \'', service, '\'
		   AND api_user_token.user_token = \'', api_token, '\'
		   LIMIT 1');

  PREPARE stmt FROM @VAR_QUERY;
  EXECUTE stmt;
  DEALLOCATE PREPARE stmt;
END
;;
DELIMITER ;

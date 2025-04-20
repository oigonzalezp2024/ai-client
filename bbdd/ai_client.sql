-- Base de datos necesaria para el HOME --

CREATE DATABASE `ai_client`;

CREATE TABLE `ai_client`.`user_preferences` (
  `user_id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `background_image_path` varchar(255) DEFAULT NULL,
  `avatar_image_path` varchar(255) DEFAULT NULL,
  `background_color` varchar(20) DEFAULT NULL,
  `button_color` varchar(20) DEFAULT NULL,
  `button_background_color` varchar(20) DEFAULT NULL
);

INSERT INTO `ai_client`.`user_preferences` (`user_id`, `background_image_path`, `avatar_image_path`, `background_color`, `button_color`, `button_background_color`) VALUES
(1, 'portadaf_cleanup.png', 'portadaf_cleanup.png', NULL, NULL, NULL);

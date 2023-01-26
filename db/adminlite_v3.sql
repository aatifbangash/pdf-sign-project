-- Adminer 4.7.8 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `adminlite_v3` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `adminlite_v3`;

DROP TABLE IF EXISTS `ci_activity_log`;
CREATE TABLE `ci_activity_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_id` tinyint(4) NOT NULL,
  `user_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `ci_activity_status`;
CREATE TABLE `ci_activity_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(225) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `ci_activity_status` (`id`, `description`) VALUES
(1,	'User Created'),
(2,	'User Edited'),
(3,	'User Deleted'),
(4,	'Admin Created'),
(5,	'Admin Edited'),
(6,	'Admin Deleted'),
(7,	'Invoice Created'),
(8,	'Invoice Edited'),
(9,	'Invoice Deleted');

DROP TABLE IF EXISTS `ci_admin_roles`;
CREATE TABLE `ci_admin_roles` (
  `admin_role_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_role_title` varchar(30) CHARACTER SET utf8 NOT NULL,
  `admin_role_status` int(11) NOT NULL,
  `admin_role_created_by` int(1) NOT NULL,
  `admin_role_created_on` datetime NOT NULL,
  `admin_role_modified_by` int(11) NOT NULL,
  `admin_role_modified_on` datetime NOT NULL,
  PRIMARY KEY (`admin_role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `ci_admin_roles` (`admin_role_id`, `admin_role_title`, `admin_role_status`, `admin_role_created_by`, `admin_role_created_on`, `admin_role_modified_by`, `admin_role_modified_on`) VALUES
(1,	'Super Admin',	1,	0,	'2018-03-15 12:48:04',	0,	'2018-03-17 12:53:16'),
(2,	'Admin',	1,	0,	'2018-03-15 12:53:19',	0,	'2019-01-26 08:27:34'),
(3,	'Capture',	1,	0,	'2018-03-15 01:46:54',	0,	'2021-05-06 09:23:14'),
(4,	'Super admin',	1,	0,	'2018-03-16 05:52:45',	0,	'2021-06-18 10:18:55');

DROP TABLE IF EXISTS `ci_cities`;
CREATE TABLE `ci_cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `state_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `ci_companies`;
CREATE TABLE `ci_companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile_no` varchar(50) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `ci_companies` (`id`, `name`, `email`, `mobile_no`, `address1`, `address2`, `created_date`) VALUES
(9,	'Codeglamour',	'codeglamour1@gmail.com',	'44785566952',	'27 new jersey - Level 58 - CA 444 United State ',	'',	'2019-11-17 10:11:52'),
(8,	'Codeglamour',	'codeglamour1@gmail.com',	'44785566952',	'27 new jersey - Level 58 - CA 444 United State ',	'',	'2018-04-26 09:04:30'),
(7,	'Codeglamour',	'codeglamour1@gmail.com',	'44785566952',	'27 new jersey - Level 58 - CA 444 United State ',	'',	'2019-03-13 10:03:41'),
(6,	'Codeglamour',	'codeglamour1@gmail.com',	'44785566952',	'27 new jersey - Level 58 - CA 444  United State LLC',	'',	'2017-12-11 08:12:15'),
(10,	'Codeglamour',	'codeglamour1@gmail.com',	'44785566952',	'27 new jersey - Level 58 - CA 444 United State ',	'',	'2019-01-27 10:01:18'),
(11,	'Codeglamour',	'codeglamour1@gmail.com',	'44785566952',	'27 new jersey - Level 58 - CA 444 United State ',	'',	'2019-11-26 09:11:45'),
(12,	'Codeglamour',	'codeglamour1@gmail.com',	'44785566952',	'27 new jersey - Level 58 - CA 444 United State ',	'',	'2019-11-26 09:11:48'),
(13,	'Codeglamour',	'codeglamour1@gmail.com',	'44785566952',	'27 new jersey - Level 58 - CA 444 United State ',	'',	'2019-11-26 09:11:50'),
(14,	'Codeglamour',	'codeglamour1@gmail.com',	'44785566952',	'27 new jersey - Level 58 - CA 444 United State ',	'',	'2019-11-26 09:11:42'),
(15,	'Codeglamour',	'codeglamour1@gmail.com',	'44785566952',	'27 new jersey - Level 58 - CA 444 United State ',	'',	'2020-11-24 12:11:01');

DROP TABLE IF EXISTS `ci_countries`;
CREATE TABLE `ci_countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sortname` varchar(3) NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `phonecode` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ci_email_templates`;
CREATE TABLE `ci_email_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `ci_email_templates` (`id`, `name`, `slug`, `subject`, `body`, `last_update`) VALUES
(1,	'Email Verification',	'email-verification',	'Activate Your Account',	'<p></p>\n\n<p>Hi  <b>{FULLNAME}</b>,<br><br></p><p>Welcome to LightAdmin!<br>Active your account with the link above and start your Career.</p><p>To verify your email, please click the link below:<br> {VERIFICATION_LINK}</p><p>\n\n</p><div><b>Regards,</b></div><div><b>Team</b></div>\n\n<p></p>',	'2019-11-26 18:06:39'),
(2,	'Forget Password',	'forget-password',	'Recover your password',	'<p>\n\n</p><p>Hi  <b>{FULLNAME}</b>,<br><br></p><p>Welcome to LightAdmin!<br></p><p>We have received a request to reset your password. If you did not initiate this request, you can simply ignore this message and no action will be taken.</p><p><br>To reset your password, please click the link below:<br> {RESET_LINK}</p>\n\n<p></p>',	'2019-11-26 17:44:41'),
(3,	'General Notification',	'',	'aaaaa',	'<p>asdfasdfasdfasd </p>',	'2019-08-26 02:42:47');

DROP TABLE IF EXISTS `ci_email_template_variables`;
CREATE TABLE `ci_email_template_variables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) NOT NULL,
  `variable_name` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `ci_email_template_variables` (`id`, `template_id`, `variable_name`) VALUES
(1,	1,	'{FULLNAME}'),
(2,	1,	'{VERIFICATION_LINK}'),
(3,	2,	'{RESET_LINK}'),
(4,	2,	'{FULLNAME}');

DROP TABLE IF EXISTS `ci_files`;
CREATE TABLE `ci_files` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(256) NOT NULL,
  `file_path` varchar(256) NOT NULL,
  `file_width` varchar(50) NOT NULL,
  `file_height` varchar(50) NOT NULL,
  `signature_point` varchar(50) NOT NULL DEFAULT '50x260',
  `folder_id` int(11) NOT NULL,
  `browser` varchar(256) DEFAULT NULL,
  `browser_version` varchar(256) DEFAULT NULL,
  `os` varchar(256) DEFAULT NULL,
  `ip_address` varchar(256) DEFAULT NULL,
  `is_converted` int(1) DEFAULT '0',
  `created_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ci_files` (`file_id`, `file_name`, `file_path`, `file_width`, `file_height`, `signature_point`, `folder_id`, `browser`, `browser_version`, `os`, `ip_address`, `is_converted`, `created_at`, `updated_at`) VALUES
(108,	'delivery-80194320-1624786336.pdf',	'/var/www/html/pdfproj/gitdir/assets/pdf_uploads/delivery-80194320-1624786336.pdf',	'612',	'792',	'48x258',	76,	'Chrome',	'89.0.4389.90',	'Linux',	'::1',	0,	'2021-06-29 12:26:54',	'2021-06-29 12:26:54'),
(109,	'delivery-80188863-05182020-1624786336.pdf',	'/var/www/html/pdfproj/gitdir/assets/pdf_uploads/delivery-80188863-05182020-1624786336.pdf',	'612',	'792',	'52x265',	76,	'Chrome',	'89.0.4389.90',	'Linux',	'::1',	0,	'2021-06-27 14:42:24',	'2021-06-27 14:42:24'),
(110,	'delivery-80194320-1624858368.pdf',	'/var/www/html/pdfproj/gitdir/assets/pdf_uploads/delivery-80194320-1624858368.pdf',	'612',	'792',	'50x260',	77,	'Chrome',	'89.0.4389.90',	'Linux',	'::1',	0,	'2021-06-27 20:32:50',	'2021-06-27 20:32:50'),
(111,	'delivery-80188863-05182020-1624858369.pdf',	'/var/www/html/pdfproj/gitdir/assets/pdf_uploads/delivery-80188863-05182020-1624858369.pdf',	'612',	'792',	'50x260',	77,	'Chrome',	'89.0.4389.90',	'Linux',	'::1',	0,	'2021-06-30 12:02:31',	'2021-06-30 12:02:31');

DROP TABLE IF EXISTS `ci_folders`;
CREATE TABLE `ci_folders` (
  `folder_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(256) NOT NULL,
  `folder_name` varchar(256) NOT NULL,
  `added_by_id` int(11) NOT NULL,
  `address` varchar(256) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `mobile_no` varchar(256) DEFAULT NULL,
  `capture_role_id` int(11) DEFAULT NULL,
  `signature` varchar(256) DEFAULT NULL,
  `signature_path` varchar(256) DEFAULT NULL,
  `is_completed` int(1) DEFAULT '0',
  `date_created` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`folder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ci_folders` (`folder_id`, `company_name`, `folder_name`, `added_by_id`, `address`, `email`, `mobile_no`, `capture_role_id`, `signature`, `signature_path`, `is_completed`, `date_created`) VALUES
(76,	'Codenterprise organization P.V.T',	'codenterprise-organization-pvt-1624786336',	25,	'blue area',	'atif@codenterprise.com',	'12334',	26,	'a8c7da8bbe69282bb1e2d06c13860127.png',	'/var/www/html/pdfproj/gitdir/assets/signatures/a8c7da8bbe69282bb1e2d06c13860127.png',	1,	'2021-06-29 14:13:30'),
(77,	'fai2',	'fai2-1624858369',	25,	'blue area',	'atif@codenterprise.com',	'123445',	26,	'7422fe7e14ff238e42544ded644d98ec.png',	'/var/www/html/pdfproj/gitdir/assets/signatures/7422fe7e14ff238e42544ded644d98ec.png',	0,	'2021-06-30 09:57:05');

DROP TABLE IF EXISTS `ci_general_settings`;
CREATE TABLE `ci_general_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `favicon` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `application_name` varchar(255) DEFAULT NULL,
  `timezone` varchar(255) DEFAULT NULL,
  `currency` varchar(100) DEFAULT NULL,
  `default_language` int(11) NOT NULL,
  `copyright` tinytext,
  `email_from` varchar(100) NOT NULL,
  `smtp_host` varchar(255) DEFAULT NULL,
  `smtp_port` int(11) DEFAULT NULL,
  `smtp_user` varchar(50) DEFAULT NULL,
  `smtp_pass` varchar(50) DEFAULT NULL,
  `facebook_link` varchar(255) DEFAULT NULL,
  `twitter_link` varchar(255) DEFAULT NULL,
  `google_link` varchar(255) DEFAULT NULL,
  `youtube_link` varchar(255) DEFAULT NULL,
  `linkedin_link` varchar(255) DEFAULT NULL,
  `instagram_link` varchar(255) DEFAULT NULL,
  `recaptcha_secret_key` varchar(255) DEFAULT NULL,
  `recaptcha_site_key` varchar(255) DEFAULT NULL,
  `recaptcha_lang` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `ci_general_settings` (`id`, `favicon`, `logo`, `application_name`, `timezone`, `currency`, `default_language`, `copyright`, `email_from`, `smtp_host`, `smtp_port`, `smtp_user`, `smtp_pass`, `facebook_link`, `twitter_link`, `google_link`, `youtube_link`, `linkedin_link`, `instagram_link`, `recaptcha_secret_key`, `recaptcha_site_key`, `recaptcha_lang`, `created_date`, `updated_date`) VALUES
(1,	'assets/img/dc48701e5a6a300744b873b63f772101.png',	'assets/img/dc48701e5a6a300744b873b63f772101.png',	'Admin Lite',	'America/Adak',	'USD',	2,	'Copyright © 2019 Light Admin All rights reserved.',	'no-reply@domain.com',	'mail.domain.com',	587,	'no-reply@email.com',	'#1111@123',	'https://facebook.com',	'https://twitter.com',	'https://google.com',	'https://youtube.com',	'https://linkedin.com',	'https://instagram.com',	'',	'',	'en',	'2021-01-19 10:01:00',	'2021-01-19 10:01:00');

DROP TABLE IF EXISTS `ci_language`;
CREATE TABLE `ci_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(225) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `short_name` varchar(15) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `ci_language` (`id`, `name`, `short_name`, `status`, `created_at`) VALUES
(2,	'English',	'en',	1,	'2019-09-16 01:13:17'),
(3,	'French',	'fr',	1,	'2019-09-16 08:11:08');

DROP TABLE IF EXISTS `ci_payments`;
CREATE TABLE `ci_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_by` tinyint(4) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `invoice_no` varchar(30) NOT NULL,
  `txn_id` varchar(255) NOT NULL,
  `items_detail` longtext NOT NULL,
  `sub_total` decimal(10,2) NOT NULL,
  `total_tax` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `grand_total` decimal(10,2) NOT NULL,
  `currency` varchar(20) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_status` varchar(30) NOT NULL,
  `client_note` longtext NOT NULL,
  `termsncondition` longtext NOT NULL,
  `due_date` date NOT NULL,
  `created_date` date NOT NULL,
  `updated_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `ci_payments` (`id`, `created_by`, `user_id`, `company_id`, `invoice_no`, `txn_id`, `items_detail`, `sub_total`, `total_tax`, `discount`, `grand_total`, `currency`, `payment_method`, `payment_status`, `client_note`, `termsncondition`, `due_date`, `created_date`, `updated_date`) VALUES
(10,	31,	35,	15,	'0032',	'',	'a:5:{s:19:\"product_description\";a:2:{i:0;s:11:\"logo desing\";i:1;s:12:\"flyer design\";}s:8:\"quantity\";a:2:{i:0;s:1:\"1\";i:1;s:1:\"1\";}s:5:\"price\";a:2:{i:0;s:2:\"50\";i:1;s:3:\"100\";}s:3:\"tax\";a:2:{i:0;s:1:\"2\";i:1;s:1:\"5\";}s:5:\"total\";a:2:{i:0;s:5:\"50.00\";i:1;s:6:\"100.00\";}}',	150.00,	6.00,	1.00,	155.00,	'USD',	'',	'Partially Paid',	'flat desing',	'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',	'2020-11-21',	'2020-11-03',	'2020-11-24');

DROP TABLE IF EXISTS `ci_states`;
CREATE TABLE `ci_states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL DEFAULT '1',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `ci_uploaded_files`;
CREATE TABLE `ci_uploaded_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(225) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `ci_uploaded_files` (`id`, `name`, `created_at`) VALUES
(81,	'uploads/0fe0382a27bbc4336939a4dd4b3acee2.jpg',	'2019-11-26 21:07:49');

DROP TABLE IF EXISTS `ci_users`;
CREATE TABLE `ci_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_role_id` int(11) NOT NULL,
  `username` varchar(100) CHARACTER SET utf8 NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile_no` varchar(255) NOT NULL,
  `image` varchar(300) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` mediumtext NOT NULL,
  `last_login` datetime NOT NULL,
  `is_verify` tinyint(4) NOT NULL DEFAULT '1',
  `is_admin` tinyint(4) NOT NULL DEFAULT '1',
  `is_user` tinyint(4) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '0',
  `is_supper` tinyint(4) NOT NULL DEFAULT '0',
  `added_by` tinyint(4) NOT NULL,
  `token` varchar(255) NOT NULL,
  `password_reset_code` varchar(255) NOT NULL,
  `last_ip` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `ci_users` (`user_id`, `admin_role_id`, `username`, `firstname`, `lastname`, `email`, `mobile_no`, `image`, `password`, `address`, `last_login`, `is_verify`, `is_admin`, `is_user`, `is_active`, `is_supper`, `added_by`, `token`, `password_reset_code`, `last_ip`, `created_at`, `updated_at`) VALUES
(25,	2,	'admin',	'Admin',	'User',	'admin@gmail.com',	'544354353',	'',	'$2y$10$GbTNIkTbIQ73TzlIglAbduYnICqo0IpmATNfBSWyIX5SZYNGJqun2',	'',	'2019-01-09 00:00:00',	1,	1,	0,	1,	0,	0,	'',	'',	'',	'2018-03-19 00:00:00',	'2021-05-06 09:05:53'),
(26,	3,	'capture',	'jorge',	'bush',	'bush@gmail.com',	'5446546545665',	'1c576d254c9f8a23c9243702bdb45a11.png',	'$2y$10$zFjcPAiPyrOzXu6te2SXZOeOE3gU3urJhO8Af7xCZSHStprzz9IPS',	'',	'2018-11-01 09:46:23',	1,	1,	0,	1,	0,	0,	'',	'',	'',	'2018-03-19 00:00:00',	'2021-05-06 09:05:12'),
(31,	1,	'superadmin',	'super',	'admin',	'codeglamourofficial@gmail.com',	'123456',	'',	'$2y$10$66iIrbg9dudCsMNx5F7VDuV0hqNRLOgMMsfq/h8RRniB4LWBt9kTy',	'',	'0000-00-00 00:00:00',	1,	1,	0,	1,	1,	0,	'',	'',	'',	'2019-01-16 06:01:58',	'2020-11-24 08:11:59'),
(32,	4,	'client',	'Smitt2',	'Johan',	'johnsmith@gmail.com',	'46545566554865',	'',	'$2y$10$NK/uoe64BPsYREYuVSCz3u30g4aRkIE9ge.HWEv5Kx77kBK.JaU36',	'',	'0000-00-00 00:00:00',	1,	1,	0,	1,	0,	0,	'',	'',	'',	'2019-07-15 08:07:34',	'2021-05-06 09:05:59'),
(36,	5,	'aliraza',	'ali',	'raza',	'malirazaonline@gmail.com',	'12345678',	'',	'$2y$10$vkamnyUBkD1/pVqTRPdco.f5aRx48E76q3JrXxQdKG2ffRz6r.Q4e',	'xyz',	'0000-00-00 00:00:00',	1,	1,	1,	1,	0,	31,	'',	'',	'',	'2020-11-23 11:11:17',	'2021-05-06 09:05:18'),
(37,	5,	'alisuser',	'alies',	'user',	'alisuser@gmail.com',	'45987426666',	'',	'$2y$10$210Bvma9IZF1XTu.fWasa.Mu.JhTdPMb1NJwzM.9L8NydkiwrladW',	'xyz',	'0000-00-00 00:00:00',	1,	1,	1,	1,	0,	36,	'',	'',	'',	'2020-11-23 11:11:33',	'2020-11-24 08:11:41'),
(40,	3,	'capture23',	'jorge2',	'bush2',	'bush2@gmail.com',	'5446546545666',	'1c576d254c9f8a23c9243702bdb45a11.png',	'$2y$10$zFjcPAiPyrOzXu6te2SXZOeOE3gU3urJhO8Af7xCZSHStprzz9IPS',	'',	'2018-11-01 09:46:23',	1,	1,	0,	1,	0,	0,	'',	'',	'',	'2018-03-19 00:00:00',	'2021-05-10 09:05:56');

DROP TABLE IF EXISTS `module`;
CREATE TABLE `module` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(255) NOT NULL,
  `controller_name` varchar(255) NOT NULL,
  `fa_icon` varchar(100) NOT NULL,
  `operation` text NOT NULL,
  `sort_order` tinyint(4) NOT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `module` (`module_id`, `module_name`, `controller_name`, `fa_icon`, `operation`, `sort_order`) VALUES
(1,	'admin',	'admin',	'fa-pie-chart',	'view|add|edit|delete|change_status|access',	3),
(2,	'role_and_permissions',	'admin_roles',	'fa-book',	'view|add|edit|delete|change_status|access',	4),
(3,	'users',	'users',	'fa-users',	'view|add|edit|delete|change_status|access',	3),
(7,	'backup_and_export',	'export',	'fa-database',	'access',	12),
(8,	'settings',	'general_settings',	'fa-cogs',	'view|add|edit|access',	13),
(9,	'dashboard',	'dashboard',	'fa-dashboard',	'view|index_1|index_2|index_3|access',	1),
(10,	'codeigniter_examples',	'example',	'fa-snowflake-o',	'access',	6),
(11,	'invoicing_system',	'invoices',	'fa-files-o',	'access',	9),
(12,	'database_joins_example',	'joins',	'fa-external-link-square',	'access',	7),
(13,	'language_setting',	'languages',	'fa-language',	'access',	14),
(14,	'locations',	'location',	'fa-map-pin',	'access',	11),
(15,	'widgets',	'widgets',	'fa-th',	'access',	19),
(16,	'charts',	'charts',	'fa-line-chart',	'access',	17),
(17,	'ui_elements',	'ui',	'fa-tree',	'access',	18),
(18,	'forms',	'forms',	'fa-edit',	'access',	20),
(19,	'tables',	'tables',	'fa-table',	'access',	21),
(21,	'mailbox',	'mailbox',	'fa-envelope-o',	'access',	23),
(22,	'pages',	'pages',	'fa-book',	'access',	24),
(25,	'profile',	'profile',	'fa-user',	'access',	2),
(26,	'activity_log',	'activity',	'fa-flag-o',	'access',	11),
(27,	'Documents',	'upload_documents',	'fa-file-pdf-o',	'view|add|edit|delete|change_status|access',	1),
(28,	'Sign Documents',	'sign_documents',	'fa-file-pdf-o',	'view|add|edit|delete|change_status|access',	3);

DROP TABLE IF EXISTS `module_access`;
CREATE TABLE `module_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_role_id` int(11) NOT NULL,
  `module` varchar(255) NOT NULL,
  `operation` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `RoleId` (`admin_role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `module_access` (`id`, `admin_role_id`, `module`, `operation`) VALUES
(1,	1,	'users',	'view'),
(2,	1,	'users',	'add'),
(3,	1,	'users',	'edit'),
(5,	1,	'users',	'access'),
(6,	1,	'users',	'change_status'),
(7,	1,	'export',	'access'),
(8,	1,	'general_settings',	'view'),
(9,	1,	'general_settings',	'add'),
(10,	1,	'general_settings',	'edit'),
(11,	1,	'general_settings',	'access'),
(28,	2,	'profile',	'access'),
(36,	2,	'calendar',	'access'),
(54,	5,	'users',	'view'),
(55,	5,	'users',	'access'),
(56,	5,	'users',	'add'),
(57,	5,	'users',	'edit'),
(58,	5,	'users',	'delete'),
(59,	5,	'users',	'change_status'),
(60,	5,	'example',	'access'),
(61,	5,	'joins',	'access'),
(62,	5,	'location',	'access'),
(63,	5,	'widgets',	'access'),
(64,	5,	'ui',	'access'),
(65,	5,	'charts',	'access'),
(66,	5,	'forms',	'access'),
(67,	5,	'tables',	'access'),
(68,	5,	'mailbox',	'access'),
(69,	5,	'pages',	'access'),
(70,	5,	'extras',	'access'),
(71,	5,	'dashboard',	'access'),
(72,	5,	'dashboard',	'view'),
(73,	5,	'profile',	'access'),
(79,	3,	'profile',	'access'),
(82,	4,	'profile',	'access'),
(84,	2,	'Upload_documents',	''),
(85,	3,	'Upload_documents',	''),
(86,	4,	'Upload_documents',	''),
(87,	2,	'Upload_documents',	'access'),
(88,	2,	'upload_documents',	'access'),
(91,	2,	'upload_documents',	'change_status'),
(92,	2,	'upload_documents',	'view'),
(93,	2,	'upload_documents',	'add'),
(94,	2,	'upload_documents',	'edit'),
(95,	2,	'upload_documents',	'delete'),
(108,	4,	'upload_documents',	'view'),
(109,	4,	'upload_documents',	'change_status'),
(110,	4,	'upload_documents',	'access'),
(111,	4,	'upload_documents',	'add'),
(112,	4,	'upload_documents',	'edit'),
(113,	4,	'upload_documents',	'delete'),
(114,	4,	'admin',	'view'),
(115,	4,	'admin',	'change_status'),
(116,	4,	'admin',	'access'),
(117,	4,	'admin',	'add'),
(118,	4,	'admin',	'edit'),
(119,	4,	'admin',	'delete'),
(127,	3,	'sign_documents',	'access');

DROP TABLE IF EXISTS `sub_module`;
CREATE TABLE `sub_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Parent Module ID` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `sub_module` (`id`, `parent`, `name`, `link`, `sort_order`) VALUES
(2,	2,	'module_setting',	'module',	1),
(3,	2,	'role_and_permissions',	'',	2),
(4,	1,	'add_new_admin',	'add',	2),
(6,	1,	'admin_list',	'',	1),
(26,	9,	'dashboard_v1',	'index_1',	1),
(27,	9,	'dashboard_v2',	'index_2',	2),
(28,	9,	'dashboard_v3',	'index_3',	3),
(30,	3,	'users_list',	'',	1),
(31,	3,	'add_new_user',	'add',	2),
(32,	10,	'simple_datatable',	'simple_datatable',	1),
(33,	10,	'ajax_datatable',	'ajax_datatable',	2),
(34,	10,	'pagination',	'pagination',	3),
(35,	10,	'advance_search',	'advance_search',	4),
(36,	10,	'file_upload',	'file_upload',	5),
(37,	11,	'invoice_list',	'',	1),
(38,	11,	'add_new_invoice',	'add',	2),
(39,	12,	'serverside_join',	'',	1),
(40,	12,	'simple_join',	'simple',	2),
(41,	14,	'country',	'',	1),
(42,	14,	'state',	'state',	2),
(43,	14,	'city',	'city',	3),
(44,	16,	'charts_js',	'chartjs',	1),
(45,	16,	'charts_flot',	'flot',	2),
(46,	16,	'charts_inline',	'inline',	3),
(47,	17,	'general',	'general',	1),
(48,	17,	'icons',	'icons',	2),
(49,	17,	'buttons',	'buttons',	3),
(50,	18,	'general_elements',	'general',	1),
(51,	18,	'advanced_elements',	'advanced',	2),
(52,	18,	'editors',	'editors',	3),
(53,	19,	'simple_tables',	'simple',	1),
(54,	19,	'data_tables',	'data',	2),
(55,	21,	'inbox',	'inbox',	1),
(56,	21,	'compose',	'compose',	2),
(57,	21,	'read',	'read_mail',	3),
(58,	22,	'invoice',	'invoice',	1),
(59,	22,	'profile',	'profile',	2),
(60,	22,	'login',	'login',	3),
(61,	22,	'register',	'register',	4),
(62,	22,	'lock_screen',	'Lockscreen',	4),
(63,	23,	'error_404',	'error404',	1),
(64,	23,	'error_500',	'error500',	2),
(65,	23,	'blank_page',	'blank',	3),
(66,	23,	'starter_page',	'starter',	4),
(67,	8,	'general_settings',	'',	1),
(68,	8,	'email_template_settings',	'email_templates',	2),
(69,	25,	'view_profile',	'',	1),
(70,	25,	'change_password',	'change_pwd',	2),
(71,	10,	'multiple_files_upload',	'multi_file_upload',	6),
(72,	10,	'dynamic_charts',	'charts',	7),
(73,	10,	'locations',	'locations',	8),
(74,	27,	'Add Document',	'',	0),
(75,	27,	'List Documents',	'list',	0),
(77,	28,	'Completed',	'completed',	2),
(78,	28,	'Uncompleted',	'uncompleted',	1),
(79,	27,	'Completed Documents',	'completed_documents',	3);

-- 2021-06-30 07:07:27

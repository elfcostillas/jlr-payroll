/*
SQLyog Community v13.1.9 (64 bit)
MySQL - 5.7.33 : Database - jlr_hris
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`jlr_hris` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `jlr_hris`;

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `main_menu` */

DROP TABLE IF EXISTS `main_menu`;

CREATE TABLE `main_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_desc` varchar(32) DEFAULT NULL,
  `menu_icon` varchar(24) DEFAULT NULL,
  `menu_link` varchar(32) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*Data for the table `main_menu` */

insert  into `main_menu`(`id`,`menu_desc`,`menu_icon`,`menu_link`) values 
(1,'Timekeeping','fas fa-user-clock','timekeeping'),
(2,'Employee File','fas fa-user-cog','employee-files');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'2014_10_12_000000_create_users_table',1),
(2,'2014_10_12_100000_create_password_resets_table',1),
(3,'2019_08_19_000000_create_failed_jobs_table',1),
(4,'2019_12_14_000001_create_personal_access_tokens_table',1);

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `payroll_period` */

DROP TABLE IF EXISTS `payroll_period`;

CREATE TABLE `payroll_period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `date_release` date DEFAULT NULL,
  `man_hours` int(11) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;

/*Data for the table `payroll_period` */

insert  into `payroll_period`(`id`,`date_from`,`date_to`,`date_release`,`man_hours`) values 
(1,'2022-08-01','2022-08-15','2022-08-20',1),
(2,'2022-08-20','2022-08-20','2022-08-18',2),
(3,'2022-08-20','2022-08-20','2022-08-20',3),
(4,'2022-08-20','2022-08-20','2022-08-20',4),
(5,'2022-08-20','2022-08-20','2022-08-20',5),
(6,'2022-08-20','2022-08-20','2022-08-20',6),
(7,'2022-08-20','2022-08-20','2022-08-20',7),
(8,'2022-08-20','2022-08-20','2022-08-20',8),
(9,'2022-08-20','2022-08-20','2022-08-20',9),
(10,'2022-08-20','2022-08-20','2022-08-20',10),
(11,'2022-08-20','2022-08-20','2022-08-20',11),
(12,'2022-08-20','2022-08-20','2022-08-20',12),
(13,'2022-08-20','2022-08-20','2022-08-20',13),
(14,'2022-08-20','2022-08-20','2022-08-19',14),
(15,'2022-08-20','2022-08-20','2022-08-20',15),
(16,'2022-08-20','2022-08-20','2022-08-20',20),
(17,'2022-08-20','2022-08-04','2022-08-20',17),
(18,'2022-08-22','2022-08-22','2022-08-22',99);

/*Table structure for table `payroll_period_weekly` */

DROP TABLE IF EXISTS `payroll_period_weekly`;

CREATE TABLE `payroll_period_weekly` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `date_release` date DEFAULT NULL,
  `man_hours` int(11) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `payroll_period_weekly` */

/*Table structure for table `personal_access_tokens` */

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `personal_access_tokens` */

/*Table structure for table `sub_menu` */

DROP TABLE IF EXISTS `sub_menu`;

CREATE TABLE `sub_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sub_menu_desc` varchar(32) DEFAULT NULL,
  `sub_menu_main` int(11) DEFAULT NULL,
  `sub_menu_link` varchar(64) DEFAULT NULL,
  `sub_menu_icon` varchar(24) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

/*Data for the table `sub_menu` */

insert  into `sub_menu`(`id`,`sub_menu_desc`,`sub_menu_main`,`sub_menu_link`,`sub_menu_icon`) values 
(1,'Payroll Period - Semi',1,'timekeeping/payroll-period','fas fa-calendar-alt'),
(2,'Payroll Period - Weekly',1,'timekeeping/payroll-period-weekly','fas fa-calendar-week'),
(3,'Emp. Master Data',2,'employee-files/employee-master-data','fas fa-list-ul');

/*Table structure for table `user_rights` */

DROP TABLE IF EXISTS `user_rights`;

CREATE TABLE `user_rights` (
  `line_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `sub_menu_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`line_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4;

/*Data for the table `user_rights` */

insert  into `user_rights`(`line_id`,`user_id`,`sub_menu_id`) values 
(46,1,2),
(47,1,3),
(48,1,1),
(50,2,1),
(51,2,2),
(53,2,3),
(54,3,1),
(55,3,2),
(56,3,3);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`email_verified_at`,`password`,`remember_token`,`created_at`,`updated_at`) values 
(1,'Elmer Costillas','elmer.costillas@jlr.com',NULL,'$2y$10$3IKUx138HNtav1gRYf9ItuOb4BDoHdzvlOyzLLf9R7OEYndX5yynG',NULL,'2022-08-19 13:06:14','2022-08-19 13:06:14'),
(2,'Genevive Naparate','genevive.naparate@jlr.com',NULL,'$2y$10$9aWkLwUzw3RU8q4dCFvXde9Mfx.0HeaA1X9uKaxd3UC4Sf02gKwsC',NULL,'2022-08-22 05:34:32','2022-08-22 05:34:32'),
(3,'Angelie Abejo','angelie.abejo@jlr.com',NULL,'$2y$10$rwnBy6SaQyvEstn7qyXGD.1vJDL.E631853B3KMPkwfj4Fh98S/gu',NULL,'2022-08-22 05:33:55','2022-08-22 05:33:55');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

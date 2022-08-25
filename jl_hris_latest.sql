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

/*Table structure for table `civil_status` */

DROP TABLE IF EXISTS `civil_status`;

CREATE TABLE `civil_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stat_code` varchar(3) DEFAULT NULL,
  `stat_desc` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Data for the table `civil_status` */

insert  into `civil_status`(`id`,`stat_code`,`stat_desc`) values 
(1,'SIN','Sinlge'),
(2,'MAR','Married'),
(3,'DIV','Divorced'),
(4,'WID','Widowed');

/*Table structure for table `departments` */

DROP TABLE IF EXISTS `departments`;

CREATE TABLE `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dept_div_id` int(11) DEFAULT NULL,
  `dept_code` varchar(12) DEFAULT NULL,
  `dept_name` text,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

/*Data for the table `departments` */

insert  into `departments`(`id`,`dept_div_id`,`dept_code`,`dept_name`) values 
(1,1,'Aggregates','Aggregates'),
(2,1,'Quarry','Quarry'),
(3,1,'RMD','RMD'),
(4,2,'Service','Service'),
(5,2,'QA','QA'),
(6,2,'RMD','RMD'),
(7,3,'FID','Finance'),
(8,3,'HR','Human Resource'),
(9,3,'IT','Information Technology'),
(10,3,'P/W','Purchasing and Warehouse'),
(11,3,'SMD','Sales and Marketing');

/*Table structure for table `divisions` */

DROP TABLE IF EXISTS `divisions`;

CREATE TABLE `divisions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `div_code` varchar(12) DEFAULT NULL,
  `div_name` text,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

/*Data for the table `divisions` */

insert  into `divisions`(`id`,`div_code`,`div_name`) values 
(1,'QAD','Quarry and Aggregates'),
(2,'RMC','Ready Mix Concrete'),
(3,'SSDiv','Shared Services');

/*Table structure for table `dummy` */

DROP TABLE IF EXISTS `dummy`;

CREATE TABLE `dummy` (
  `col1` int(11) DEFAULT NULL,
  `lastname` varchar(32) DEFAULT NULL,
  `name1` varchar(32) DEFAULT NULL,
  `name2` varchar(32) DEFAULT NULL,
  `name3` varchar(32) DEFAULT NULL,
  `name4` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `dummy` */

insert  into `dummy`(`col1`,`lastname`,`name1`,`name2`,`name3`,`name4`) values 
(1535,'Ababa','Jeffrey','','',NULL),
(158,'ABALORIO','EGLLEN','','',NULL),
(205,'ABALORIO','RAUL','','',NULL),
(4,'ABANTO','ROLANDO','','',NULL),
(352,'ABANTO','WILLIAM','','',NULL),
(863,'ABEJO','ANGELIE','','',NULL),
(822,'ABELLANA','MANOLITO','','',NULL),
(876,'ABING','RONALD','','',NULL),
(956,'Adjuh','Sharkey','','',NULL),
(692,'ALBA','RICHARD','','',NULL),
(7,'ALCANTARA','RODERICK','','',NULL),
(824,'ALCAZAR','REY','MARK','',NULL),
(1506,'Alcover','Romel','','',NULL),
(504,'Aldueza','Jerry','','',NULL),
(1516,'Alfantealvin','','','',NULL),
(842,'ALFECHE','HERMESIO','','',NULL),
(739,'ALGAR','NOEL','SEIG','',NULL),
(806,'ALGAR','NIEL','','',NULL),
(947,'Algones','Artchie','','',NULL),
(929,'Allere','Ismael','','',NULL),
(81,'ALMO','RHYAN','','',NULL),
(747,'alojip','','','',NULL),
(607,'ALVAREZ','GERSHWIN','RALPH','',NULL),
(1523,'Ampalayuhan','Maricel','','',NULL),
(952,'Analocas','Epifanio','Jr','',NULL),
(942,'Añasco','Enrique','','',NULL),
(746,'Angco','Rocky','','',NULL),
(873,'ANOBLING','JAKE','','',NULL),
(524,'Añon','Jaime','','',NULL),
(841,'Anore','Jhon','Keven','',NULL),
(1501,'ANTIMARO','ELMER','','',NULL),
(609,'antipona','librado','','',NULL),
(783,'Arcelo','Christian','','',NULL),
(610,'arellano','ricardo','','',NULL),
(672,'ARELLANO','REYMOND','','',NULL),
(512,'Areza','Rodnel','','',NULL),
(997,'Argallon','Joey','','',NULL),
(492,'ARIAS','RICARDO','','',NULL),
(721,'Arias','Thomas','James','',NULL),
(750,'Ariza','Rodchel','','',NULL),
(789,'Ariza','Rolando','','',NULL),
(854,'ARNAIZ','JEROME','','',NULL),
(1505,'Arquisa','Jundy','','',NULL),
(937,'Arreglado','Jose','Marie','',NULL),
(714,'ASAYAS','MAYLENE','','',NULL),
(695,'ASIGNAR','ILDEBRANDO','JR','',NULL),
(817,'Atanoza','Harris','','',NULL),
(664,'ATILLO','JESSA','','',NULL),
(14,'BABASOL','VIRGIL','','',NULL),
(425,'BACAYO','CHARALYN','','',NULL),
(489,'Baculi','Manuelito','','',NULL),
(605,'bacus','jason','','',NULL),
(758,'BACUS','NIELFRED','','',NULL),
(1050,'Bacus','Rico','','',NULL),
(443,'Badilles','Dave','','',NULL),
(734,'BAGUIO','JOHNEL','','',NULL),
(846,'BAGUIO','RICHARD','','',NULL),
(475,'BALUYOT','JAY','','',NULL),
(491,'Barangan','Bombette','','',NULL),
(1517,'Barbecho','Carlos','Jr','',NULL),
(707,'BARIQUIT','leonardo','','',NULL),
(459,'Barredo','Gerardo','','',NULL),
(858,'BARRO','ORIEL','','',NULL),
(586,'Barte','Christopher','','',NULL),
(866,'BARTOLOME','BRYAN','','',NULL),
(19,'BATE','KAN','','',NULL),
(460,'Baute','Bryan','','',NULL),
(829,'Beldeniza','Jovanie','','',NULL),
(855,'Benigay','Alvin','','',NULL),
(825,'BIAIS','ROMEL','','',NULL),
(506,'Bocales','Mariano','','',NULL),
(324,'Bongo','Ivy','','',NULL),
(689,'BONGO','MARIE','','',NULL),
(810,'BRIONES','GAY','MARIE','',NULL),
(359,'Bulado','Julwar','','',NULL),
(951,'Bulfa','Anthony','Lee','',NULL),
(1515,'Caballa','Isidro','','',NULL),
(871,'CABALLERO','MARWEL','','',NULL),
(848,'CABANDAY','EDELYN','','',NULL),
(785,'Cabante','Conrado','','',NULL),
(382,'Cabriana','Efren','Jr.','',NULL),
(945,'Cagang','Danielkin','','',NULL),
(353,'Cajes','Angel','','',NULL),
(468,'Caldito','Ricky','','',NULL),
(585,'Calinawan','Jowanie','','',NULL),
(665,'Calinawan','Jobert','','',NULL),
(717,'Calo','Rodman','','',NULL),
(27,'CAMASURA','HERBERT','','',NULL),
(938,'Caneda','Franc','Oliver','',NULL),
(1518,'Canonego','Reynaldo','Jr','',NULL),
(1045,'Canonigo','Gerald','','',NULL),
(791,'Capul','Dennis','Mark','',NULL),
(582,'CARAMELO','ANGELICA','','',NULL),
(594,'Cardeño','Ricky','','',NULL),
(775,'Cardeño','Rey','Jun','',NULL),
(787,'Casas','Rey','Anthony','',NULL),
(790,'Casona','Jesse','','',NULL),
(724,'Castañares','Prejun','','',NULL),
(1057,'Castañeda','James','','',NULL),
(878,'Catesiano','Timoteo','','',NULL),
(635,'CAUBA','BOYET','','',NULL),
(773,'Caya','Harold','','',NULL),
(930,'Cayetano','Edgardo','','',NULL),
(480,'Cayetuna','Leonardo','','',NULL),
(662,'Cayetuna','Val','','',NULL),
(4010,'Cedeño','Johnjohn','','',NULL),
(827,'Celerian','Jade','','',NULL),
(477,'CELMAR','WILSON','','',NULL),
(803,'CENIZA','RAYMOND','II','',NULL),
(1,'CINCO','MICHAEL','','',NULL),
(29,'CLAROS','ALVIN','','',NULL),
(933,'Colina','Glen','','',NULL),
(334,'Corbo','Kevin','Roy','',NULL),
(1524,'Costanilla','Josefina','','',NULL),
(847,'COSTILLAS','ELMER','','',NULL),
(1510,'Cuizon','Randy','','',NULL),
(1520,'Cuizon','Dondy','','',NULL),
(958,'Cuyos','Merlito','','',NULL),
(163,'DACANAY','LIGAYA','JANE','',NULL),
(1046,'Dacollo','Freddiemar','','',NULL),
(32,'DALAGUETE','CARLITO','','',NULL),
(851,'DALAGUIT','MICHAEL','','',NULL),
(994,'Damon','Lyndon','Webs','',NULL),
(31,'DANIEL','EUGENE','JR','',NULL),
(1503,'Daniel','Cherie','Belle','',NULL),
(510,'Dayonot','Roel','','',NULL),
(807,'DE','ARCE','ROBERTO','',NULL),
(626,'Degracia','Bobet','','',NULL),
(798,'Degracia','Bricks','','',NULL),
(800,'Degracia','Ricardo','','',NULL),
(37,'DEIPARINE','GREGORIO','','',NULL),
(843,'Deiparine','Gregorio','Jr','',NULL),
(853,'DENIEL','JIREH','CORPUZ','',NULL),
(4011,'Dinglas','Garmen','Jay','',NULL),
(4003,'Dinglasa','Lemuel','','',NULL),
(818,'DONGAY','ELINIDO','','',NULL),
(428,'DOSDOS','NIÑO','LITO','',NULL),
(300,'DURANGO','MELCHOR','JR','',NULL),
(496,'Ebo','Glenn','','',NULL),
(704,'EBO','jacinto','','',NULL),
(4000,'Ebrado','Ben','','',NULL),
(679,'Echavez','Zosimo','','',NULL),
(925,'Elizar','Sabino','','',NULL),
(804,'ENDOZO','LEONARD','','',NULL),
(1513,'Enobacan','Arnel','','',NULL),
(948,'Escobal','Marlon','','',NULL),
(832,'ESPINOSA','JUNNIFER','','',NULL),
(935,'Espra','Tirso','','',NULL),
(44,'ESTELLORE','JOSE','','',NULL),
(45,'ESTILLORE','JEFFREY','','',NULL),
(944,'Estopa','Herardo','','',NULL),
(959,'Estopa','Juncris','','',NULL),
(390,'Estrabela','Elpedio','','',NULL),
(39,'ESTRADA','RESTITUTU','','',NULL),
(48,'FEROLINO','LEONARDO','','',NULL),
(501,'Ferolino','Teofilo','','',NULL),
(877,'Ferolino','Jhessler','','',NULL),
(815,'FERRAS','EMERITO','','',NULL),
(835,'FLORES','JOHN','RAYMUND','',NULL),
(1509,'Flores','Mario','','',NULL),
(499,'FRASCO','KART','BRYAN','',NULL),
(833,'GABIANA','CHARLEY','','',NULL),
(589,'Gabinete','Bernie','','',NULL),
(503,'Gabrillo','Julius','','',NULL),
(69,'GALOS','GOROFREDO','','',NULL),
(355,'GALVEZ','RONDY','','',NULL),
(850,'GALVEZ','NEIGEL','','',NULL),
(828,'GAMBA','MARK','ANTHONY','',NULL),
(581,'GANDALON','BENJIE','','',NULL),
(88,'GANON','EDLLY','','',NULL),
(70,'GARCIA','RAFAEL','JR','',NULL),
(188,'GARCIA','RUBEN','','',NULL),
(417,'Garcia','James','','',NULL),
(494,'GARCIA','BRIAN','','',NULL),
(765,'GARCIA','MAYSARRY','','',NULL),
(880,'GARCIA','JUNBELL','','',NULL),
(1529,'Garcia','Ryan','','',NULL),
(830,'GAYON','LEO','','',NULL),
(72,'GENARES','RONILLO','','',NULL),
(73,'GENON','RICARDO','','',NULL),
(856,'GENON','BREN','BRICK','',NULL),
(528,'Gentapa','Jesa','','',NULL),
(1532,'Geoca','Jomarie','','',NULL),
(190,'GERALDEZ','AMBROSIO','JR','',NULL),
(703,'GERALDEZ','roel','','',NULL),
(927,'GIDUQUIO','MCCOLLIN','','',NULL),
(149,'GIMENEZ','EGMEDIO','','',NULL),
(874,'GIMINA','JESSA','MAE','S.',NULL),
(701,'GOC-ONG','NESTOR','','',NULL),
(823,'GOC-ONG','LORENZO','','',NULL),
(77,'GONZALES','RANEL','','',NULL),
(571,'GONZALES','DAVE','JOHN','MARK',NULL),
(860,'GORRES','JASON','','',NULL),
(655,'GORSON','ROGER','','',NULL),
(996,'Guinto','Heart','Ace','',NULL),
(756,'GUTIERREZ','PAUL','VINCENT','',NULL),
(78,'HERAMIL','ANTONIO','','',NULL),
(940,'Herana','Alexander','','',NULL),
(1519,'Herbias','Margarito','Jr','',NULL),
(939,'Herediano','Raffy','','',NULL),
(1058,'Heruela','Warren','','',NULL),
(576,'Higa','Shoji','','',NULL),
(934,'Hingoyon','Danilo','','',NULL),
(743,'HISOLER','RANDELL','','',NULL),
(957,'Ilustrisimo','Vg','','',NULL),
(883,'INFIESTO','JENNIFER','','',NULL),
(882,'ISRAEL','ROQUE','LOUIE','',NULL),
(859,'JABERINA','ELAN','','',NULL),
(946,'Jaime','Marvin','','',NULL),
(875,'JANSON','ISAGANI','','',NULL),
(346,'JOSEPH','JOHAN','','',NULL),
(852,'JUAN','HENGSON','','',NULL),
(173,'JUBAY','WILFREDO','','',NULL),
(839,'JUBAY','JIMUEL','','',NULL),
(684,'Jumamoy','Jerence','','',NULL),
(84,'JUMAO-AS','MANUELITO','','',NULL),
(85,'JUMAO-AS','PERFECTO','','',NULL),
(86,'JUMAO-AS','RAMIR','','',NULL),
(87,'JUMAO-AS','ROBERT','','',NULL),
(490,'JUMAO-AS','ANGELIE','','',NULL),
(723,'Kionesala','Eljhun','','',NULL),
(849,'KRISTENSEN','KENT','','',NULL),
(862,'LABAJO','MICHAEL','','',NULL),
(795,'Labang','Alvin','','',NULL),
(857,'LABRADOR','ARLICE','','',NULL),
(488,'LACASA','ELBERT','','',NULL),
(5,'LADERA','LOUISITO','','',NULL),
(777,'LAMBERTE','MARY','ANN','',NULL),
(796,'Lapiz','Felixberto','Jr','',NULL),
(424,'LARGO','GENARO','','',NULL),
(794,'Larita','Dariel','','',NULL),
(4005,'Laroa','John','Lewis','',NULL),
(411,'LATAYADA','MARIA','FATIMA','',NULL),
(713,'LATAYADA','CHARMINE','','',NULL),
(812,'LATAYADA','JIM','BOY','',NULL),
(358,'Laurente','Jonas','','',NULL),
(620,'Laurente','Jason','','',NULL),
(950,'Laurente','Jell','Boy','',NULL),
(834,'LAVANDERO','ARCHIE','','',NULL),
(4004,'Lawas','Chimar','','',NULL),
(961,'LAZAGA','WILFREDO','','',NULL),
(995,'Legania','Floricho','','',NULL),
(678,'LEGASPI','LEO','','',NULL),
(166,'LENIZO','JILQUIN','','',NULL),
(864,'LIBOD','LIBOD','UBALDO','',NULL),
(708,'LIBREA','pedro','','',NULL),
(709,'LIBREA','oscar','','',NULL),
(601,'LIM','MARIA','SOCORRO','',NULL),
(820,'LIMBAGA','RICARDO','','',NULL),
(821,'LIMBAGA','SATURNINO','','',NULL),
(652,'LINGAOLINGAO','RICHARD','','',NULL),
(885,'LLAGUNO','AGUSTING','','',NULL),
(868,'Luad','Ramil','','',NULL),
(100,'LUTCHANA','ALFREDO','','',NULL),
(797,'MAASIN','ANTHONY','','',NULL),
(192,'MABUNAY','RONALDO','','',NULL),
(554,'MACARAYAN','ARNEL','','',NULL),
(101,'MACARIO','JOEL','','',NULL),
(749,'MADERAZO','JOSHUA','','',NULL),
(102,'MADRID','ALLAN','','',NULL),
(103,'MADRID','CRISALDO','','',NULL),
(104,'MADRID','EDILBERTO','','',NULL),
(514,'Madrid','Christian','','',NULL),
(558,'Madrid','Hilario','','',NULL),
(872,'MAGDASAL','JETHRO','','',NULL),
(836,'Majo','Carlo','magno','',NULL),
(193,'MALUBAY','ELISEO','','',NULL),
(814,'MAMA','ALLAN','','',NULL),
(354,'Manalili','Vincent','','',NULL),
(1521,'Manaytay','Melvin','','',NULL),
(1054,'Manga','Dexter','','',NULL),
(936,'Manguib','Rolex','','',NULL),
(560,'MANGUILIMOTAN','GERAMEL','','',NULL),
(400,'MANGUILIMUTAN','VIRGELIOJR','','',NULL),
(792,'Mansing','Daneboy','','',NULL),
(575,'Mantalaba','Catalino','','',NULL),
(766,'MARANGA','ANGELVIR','','',NULL),
(683,'Margarito','Elcarte','','',NULL),
(819,'MATUS','KENNETH','','',NULL),
(725,'Mauro','Altor','','',NULL),
(884,'Maybanting','Manny','','',NULL),
(943,'Metante','Rocky','','',NULL),
(112,'MIÑOZA','PETRONILO','','',NULL),
(621,'Miñoza','Arnolfo','','',NULL),
(4009,'Mira','Shervayne','','',NULL),
(146,'MODAY','JOSEPH','','',NULL),
(552,'MOLERO','JINGLE','','',NULL),
(451,'MONDALO','JUANITO','','',NULL),
(826,'MORAL','ALTER','','',NULL),
(23,'MORCILLA','JELMAR','','',NULL),
(865,'MORENO','ROY','','',NULL),
(840,'MURCIA','ARJAY','','',NULL),
(1055,'Nabizaga','Aljhondel','','',NULL),
(1534,'Nabizaga','Aljhonnel','','',NULL),
(780,'NAPARATE','GENEVIVE','','',NULL),
(561,'NAPUAR','ELENITA','','',NULL),
(993,'Narsico','Rolly','','',NULL),
(49,'NERI','CANDICE','','',NULL),
(536,'Nocillas','Roy','','',NULL),
(634,'NORTIZA','AILEN','','',NULL),
(1512,'Obaner','Elmer','','',NULL),
(51,'OCAÑA','ARSENIO','','',NULL),
(805,'OCCO','BRENDO','','',NULL),
(4007,'Odango','Isabellou','','',NULL),
(992,'Odlot','Mark','','',NULL),
(379,'Olipe','Henexodo','','',NULL),
(813,'Olipe','John','Henex','',NULL),
(1056,'Ombing','Crister','','',NULL),
(53,'OMBOY','JOHNNY','','',NULL),
(953,'Ornopia','Flaviano','','',NULL),
(949,'Palana','Wendell','','',NULL),
(768,'Paler','Brian','','',NULL),
(167,'PALO','CHARLIE','','',NULL),
(388,'Palo','Carlo','John','',NULL),
(389,'Palo','Chris','','',NULL),
(838,'PALTINGCA','CARLO','','',NULL),
(926,'Panerio','Sabino','Jr','',NULL),
(688,'PANSACALA','ARMANDO','','',NULL),
(988,'Pantorilla','Richard','','',NULL),
(502,'Parajes','Ariel','','',NULL),
(1525,'Pardillo','Constancio','','',NULL),
(1514,'Pasuquin','Albino','','',NULL),
(557,'Patagatay','Jerry','','',NULL),
(61,'PATALINGHUG','VICTORIO','','',NULL),
(793,'Patrolla','Nestor','','',NULL),
(62,'PAYAO','AGRIPINO','','',NULL),
(4006,'Pelegro','Fredmond','','',NULL),
(313,'PELORINA','REYNAN','','',NULL),
(1053,'Penaflor','Jessie','','',NULL),
(564,'PEÑAS','JENALYN','','',NULL),
(691,'Pescadero','Flaviano','','',NULL),
(932,'Piquero','Rey','','',NULL),
(394,'Pitogo','Rino','','',NULL),
(478,'Pitogo','Augustin','','',NULL),
(328,'PORHELIA','JONA','','',NULL),
(311,'PORRAS','NOLIE','','',NULL),
(8,'Prangos','Viemar','','',NULL),
(119,'PRANGOS','JOVENAL','','',NULL),
(636,'PRANGOS','MARVIN','','',NULL),
(705,'PRANGOS','victor','','',NULL),
(1527,'Prangos','Alex','','',NULL),
(1530,'Prangos','Julius','','',NULL),
(870,'QUIAMCO','LORD','KIM','',NULL),
(120,'QUIBOT','AUREO','','',NULL),
(989,'Quidam','Leopoldo','Jr','',NULL),
(121,'QUIMADA','NESTOR','','',NULL),
(142,'QUINAQUIN','JONAVEI','','',NULL),
(922,'Quiño','Jeffrey','','',NULL),
(423,'Quirally','Junrie','','',NULL),
(802,'QUISAY','MICHAEL','','',NULL),
(837,'Rafaela','Villacer','','',NULL),
(325,'RASONABE','JONATHAN','','',NULL),
(801,'REGNER','VERGIL','','',NULL),
(385,'RESUELO','ALDRIN','','',NULL),
(4008,'Ripdos','Jason','','',NULL),
(531,'Ritavale','Dennis','','',NULL),
(126,'RIVAS','ROMEL','','',NULL),
(941,'Roa','Reyland','','',NULL),
(128,'ROBLE','EDDIE','','',NULL),
(808,'ROBLES','CROSANTO','','',NULL),
(129,'RODRIGUEZ','RUBEN','IRWIN','',NULL),
(867,'Rodriguez','Ryan','Jay','',NULL),
(452,'Romales','Marlou','','',NULL),
(955,'Rubostro,','Richard','S.','',NULL),
(861,'RUFIN','ROEL','','',NULL),
(422,'Saavedra','Heizel','Roland','',NULL),
(4002,'Saavedra','Vicente','','',NULL),
(63,'SABROSO','HERMES','','',NULL),
(779,'Sabroso','Gerald','','',NULL),
(1531,'Salmeron','Jade','','',NULL),
(336,'SALUTA','LIEZEL','','',NULL),
(436,'SAPOTALO','GERMAN','','',NULL),
(440,'SARIEGO','FELIX','','',NULL),
(999,'Sayaboc','Roberto','Jr','',NULL),
(1051,'Sayson','Dionesio','','',NULL),
(4001,'Sayson','Chuck','','',NULL),
(706,'SECUYA','castro','','',NULL),
(931,'Segarino','Edito','','',NULL),
(66,'SEGARRA','DIONISIO','','',NULL),
(196,'SEGARRA','ISAIAS','','',NULL),
(737,'Sepada','James','','',NULL),
(831,'SEPUESCA','LIBERT','','',NULL),
(570,'SESALDO','HYEDEE','','',NULL),
(644,'SEVILLANO','DIOSCORO','','',NULL),
(579,'SIEMPRE','RENE','','',NULL),
(786,'Sigarra','Jilwin','','',NULL),
(338,'SISMAR','CARLOS','','',NULL),
(203,'SOLEÑO','MARK','ARVIN','',NULL),
(1528,'Solis','Jonry','','',NULL),
(881,'SOLON','GINO','','',NULL),
(960,'Sultones','Ryan','Jay','',NULL),
(181,'SURIGAO','RONALD','','',NULL),
(133,'TAGACA','JOSE','PEPE','',NULL),
(134,'TAGALOGON','FERNANDO','','',NULL),
(702,'TAGAYLO','NORMAN','','',NULL),
(415,'TAGLINAO','ELMER','','',NULL),
(182,'TAHANLANGIT','GEMAVEL','','',NULL),
(690,'TAMBIGA','MARY','JANE','',NULL),
(156,'TAMPIPI','ABUNDIO','','',NULL),
(645,'Tampipi','Rogien','','',NULL),
(1049,'Tampos','Eugenio','','',NULL),
(4013,'Tampos','Eugenio','Jr','',NULL),
(809,'Tapayan','Eddie','John','',NULL),
(748,'TAYAD','EMMAN','JOSEPH','',NULL),
(1502,'TEOPIS','ROANLY','','',NULL),
(928,'Tomo','Jhonell','','',NULL),
(764,'TULOD','DOMINGUITO','','',NULL),
(696,'TURTOGA','JOHN','ALVIN','',NULL),
(1047,'Turtur','Junkhemer','','',NULL),
(339,'Ubas','Ruzel','','',NULL),
(1533,'Ubod','Jundy','','',NULL),
(3,'Ugtong','Greg','','',NULL),
(990,'Unabia','Kent','Jay','',NULL),
(1526,'Unabia','Charlie','','',NULL),
(879,'URDANETA','KAREN','','',NULL),
(52,'URSAL','EDAN','JESSL','',NULL),
(138,'URSAL','NELSON','','',NULL),
(869,'URSAL','WARENE','','',NULL),
(1504,'Valmores','Axel','','',NULL),
(845,'VANZUELA','JOSE','','',NULL),
(378,'Villacencio','Ritchie','','',NULL),
(710,'VILLAMERO','pablito','','',NULL),
(648,'Villanueva','Renante','','',NULL),
(751,'Villanueva','Gilbert','','',NULL),
(533,'Villarino','Alfonso','Jr','',NULL),
(140,'VILLAROJO','ALBERT','','',NULL),
(647,'Villarojo','Danny','','',NULL),
(507,'Villarubia','Emelio','','',NULL),
(755,'Villarubia','Michael','','',NULL),
(1522,'Villasencio','Marcelina','','',NULL),
(539,'Villasin','Ponciano','','',NULL),
(1507,'Villaso','Reynaldo','','',NULL),
(1052,'Villena','Remar','','',NULL),
(673,'Wagas','Zoilo','','',NULL),
(811,'WOOTEN','BENJAMIN','JR','II',NULL),
(998,'Ybanez','Carlito','Jr','',NULL),
(991,'Ybañez','Carlito','','',NULL),
(844,'YORPO','FRANCISCO','','',NULL),
(4012,'Zanoria','Jiovani','','',NULL);

/*Table structure for table `edtr_raw` */

DROP TABLE IF EXISTS `edtr_raw`;

CREATE TABLE `edtr_raw` (
  `line_id` int(11) NOT NULL AUTO_INCREMENT,
  `punch_date` varchar(5) DEFAULT NULL,
  `punch_time` varchar(5) DEFAULT NULL,
  `biometric_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`line_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `edtr_raw` */

/*Table structure for table `employees` */

DROP TABLE IF EXISTS `employees`;

CREATE TABLE `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(64) DEFAULT NULL,
  `lastname` varchar(64) DEFAULT NULL,
  `middlename` varchar(64) DEFAULT NULL,
  `suffixname` varchar(6) DEFAULT NULL,
  `biometric_id` int(11) DEFAULT NULL,
  `primary_addr` text,
  `secondary_addr` text,
  `remarks` text,
  `sss_no` varchar(24) DEFAULT NULL,
  `deduct_sss` enum('Y','N') DEFAULT 'N',
  `tin_no` varchar(16) DEFAULT NULL,
  `phic_no` varchar(24) DEFAULT NULL,
  `deduct_phic` enum('Y','N') DEFAULT 'N',
  `hdmf_no` varchar(24) DEFAULT NULL,
  `deduct_hdmf` enum('Y','N') DEFAULT 'N',
  `hdmf_contri` decimal(24,2) DEFAULT '0.00',
  `civil_status` int(11) DEFAULT '1',
  `gender` varchar(6) DEFAULT 'M',
  `birthdate` date DEFAULT NULL,
  `employee_stat` int(11) DEFAULT NULL,
  `bank_acct` varchar(10) DEFAULT NULL,
  `basic_salary` decimal(24,2) DEFAULT '0.00',
  `is_daily` enum('Y','N') DEFAULT 'N',
  `exit_status` int(11) DEFAULT '1',
  `contact_no` varchar(32) DEFAULT NULL,
  `division_id` int(1) DEFAULT '1',
  `dept_id` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=512 DEFAULT CHARSET=utf8mb4;

/*Data for the table `employees` */

insert  into `employees`(`id`,`firstname`,`lastname`,`middlename`,`suffixname`,`biometric_id`,`primary_addr`,`secondary_addr`,`remarks`,`sss_no`,`deduct_sss`,`tin_no`,`phic_no`,`deduct_phic`,`hdmf_no`,`deduct_hdmf`,`hdmf_contri`,`civil_status`,`gender`,`birthdate`,`employee_stat`,`bank_acct`,`basic_salary`,`is_daily`,`exit_status`,`contact_no`,`division_id`,`dept_id`) values 
(1,'Jeffrey  ','Ababa',NULL,NULL,1535,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,2,5),
(2,'Egllen  ','Abalorio',NULL,NULL,158,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(3,'Raul  ','Abalorio',NULL,NULL,205,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(4,'Rolando  ','Abanto',NULL,NULL,4,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(5,'William  ','Abanto',NULL,NULL,352,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,3,11),
(6,'Angelie  ','Abejo',NULL,NULL,863,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(7,'Manolito  ','Abellana',NULL,NULL,822,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(8,'Ronald  ','Abing',NULL,NULL,876,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(9,'Sharkey  ','Adjuh',NULL,NULL,956,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(10,'Richard  ','Alba',NULL,NULL,692,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,3,11),
(11,'Roderick  ','Alcantara',NULL,NULL,7,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(12,'Rey Mark ','Alcazar',NULL,NULL,824,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(13,'Romel  ','Alcover',NULL,NULL,1506,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(14,'Jerry  ','Aldueza',NULL,NULL,504,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(15,'  ','Alfantealvin',NULL,NULL,1516,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(16,'Hermesio  ','Alfeche',NULL,NULL,842,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(17,'Noel Seig ','Algar',NULL,NULL,739,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(18,'Niel  ','Algar',NULL,NULL,806,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(19,'Artchie  ','Algones',NULL,NULL,947,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(20,'Ismael  ','Allere',NULL,NULL,929,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(21,'Rhyan  ','Almo',NULL,NULL,81,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(22,'  ','Alojip',NULL,NULL,747,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(23,'Gershwin Ralph ','Alvarez',NULL,NULL,607,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(24,'Maricel  ','Ampalayuhan',NULL,NULL,1523,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(25,'Epifanio Jr ','Analocas',NULL,NULL,952,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(26,'Enrique  ','Añasco',NULL,NULL,942,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(27,'Rocky  ','Angco',NULL,NULL,746,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(28,'Jake  ','Anobling',NULL,NULL,873,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(29,'Jaime  ','Añon',NULL,NULL,524,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(30,'Jhon Keven ','Anore',NULL,NULL,841,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(31,'Elmer  ','Antimaro',NULL,NULL,1501,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(32,'Librado  ','Antipona',NULL,NULL,609,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(33,'Christian  ','Arcelo',NULL,NULL,783,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(34,'Ricardo  ','Arellano',NULL,NULL,610,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(35,'Reymond  ','Arellano',NULL,NULL,672,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(36,'Rodnel  ','Areza',NULL,NULL,512,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(37,'Joey  ','Argallon',NULL,NULL,997,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,3),
(38,'Ricardo  ','Arias',NULL,NULL,492,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(39,'Thomas James ','Arias',NULL,NULL,721,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(40,'Rodchel  ','Ariza',NULL,NULL,750,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(41,'Rolando  ','Ariza',NULL,NULL,789,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(42,'Jerome  ','Arnaiz',NULL,NULL,854,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(43,'Jundy  ','Arquisa',NULL,NULL,1505,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(44,'Jose Marie ','Arreglado',NULL,NULL,937,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(45,'Maylene  ','Asayas',NULL,NULL,714,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(46,'Ildebrando Jr ','Asignar',NULL,NULL,695,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(47,'Harris  ','Atanoza',NULL,NULL,817,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(48,'Jessa  ','Atillo',NULL,NULL,664,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(49,'Virgil  ','Babasol',NULL,NULL,14,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(50,'Charalyn  ','Bacayo',NULL,NULL,425,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(51,'Manuelito  ','Baculi',NULL,NULL,489,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(52,'Jason  ','Bacus',NULL,NULL,605,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(53,'Nielfred  ','Bacus',NULL,NULL,758,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(54,'Rico  ','Bacus',NULL,NULL,1050,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(55,'Dave  ','Badilles',NULL,NULL,443,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(56,'Johnel  ','Baguio',NULL,NULL,734,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(57,'Richard  ','Baguio',NULL,NULL,846,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(58,'Jay  ','Baluyot',NULL,NULL,475,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(59,'Bombette  ','Barangan',NULL,NULL,491,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(60,'Carlos Jr ','Barbecho',NULL,NULL,1517,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(61,'Leonardo  ','Bariquit',NULL,NULL,707,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(62,'Gerardo  ','Barredo',NULL,NULL,459,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(63,'Oriel  ','Barro',NULL,NULL,858,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(64,'Christopher  ','Barte',NULL,NULL,586,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(65,'Bryan  ','Bartolome',NULL,NULL,866,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(66,'Kan  ','Bate',NULL,NULL,19,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(67,'Bryan  ','Baute',NULL,NULL,460,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(68,'Jovanie  ','Beldeniza',NULL,NULL,829,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(69,'Alvin  ','Benigay',NULL,NULL,855,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(70,'Romel  ','Biais',NULL,NULL,825,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(71,'Mariano  ','Bocales',NULL,NULL,506,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(72,'Ivy  ','Bongo',NULL,NULL,324,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(73,'Marie  ','Bongo',NULL,NULL,689,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(74,'Gay Marie ','Briones',NULL,NULL,810,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(75,'Julwar  ','Bulado',NULL,NULL,359,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(76,'Anthony Lee ','Bulfa',NULL,NULL,951,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(77,'Isidro  ','Caballa',NULL,NULL,1515,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(78,'Marwel  ','Caballero',NULL,NULL,871,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(79,'Edelyn  ','Cabanday',NULL,NULL,848,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(80,'Conrado  ','Cabante',NULL,NULL,785,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(81,'Efren Jr. ','Cabriana',NULL,NULL,382,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(82,'Danielkin  ','Cagang',NULL,NULL,945,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(83,'Angel  ','Cajes',NULL,NULL,353,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(84,'Ricky  ','Caldito',NULL,NULL,468,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(85,'Jowanie  ','Calinawan',NULL,NULL,585,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(86,'Jobert  ','Calinawan',NULL,NULL,665,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(87,'Rodman  ','Calo',NULL,NULL,717,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(88,'Herbert  ','Camasura',NULL,NULL,27,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(89,'Franc Oliver ','Caneda',NULL,NULL,938,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(90,'Reynaldo Jr ','Canonego',NULL,NULL,1518,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(91,'Gerald  ','Canonigo',NULL,NULL,1045,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(92,'Dennis Mark ','Capul',NULL,NULL,791,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(93,'Angelica  ','Caramelo',NULL,NULL,582,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(94,'Ricky  ','Cardeño',NULL,NULL,594,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(95,'Rey Jun ','Cardeño',NULL,NULL,775,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(96,'Rey Anthony ','Casas',NULL,NULL,787,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(97,'Jesse  ','Casona',NULL,NULL,790,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(98,'Prejun  ','Castañares',NULL,NULL,724,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(99,'James  ','Castañeda',NULL,NULL,1057,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(100,'Timoteo  ','Catesiano',NULL,NULL,878,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(101,'Boyet  ','Cauba',NULL,NULL,635,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(102,'Harold  ','Caya',NULL,NULL,773,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(103,'Edgardo  ','Cayetano',NULL,NULL,930,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(104,'Leonardo  ','Cayetuna',NULL,NULL,480,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(105,'Val  ','Cayetuna',NULL,NULL,662,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(106,'Johnjohn  ','Cedeño',NULL,NULL,4010,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(107,'Jade  ','Celerian',NULL,NULL,827,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(108,'Wilson  ','Celmar',NULL,NULL,477,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(109,'Raymond Ii ','Ceniza',NULL,NULL,803,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(110,'Michael  ','Cinco',NULL,NULL,1,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(111,'Alvin  ','Claros',NULL,NULL,29,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(112,'Glen  ','Colina',NULL,NULL,933,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(113,'Kevin Roy ','Corbo',NULL,NULL,334,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(114,'Josefina  ','Costanilla',NULL,NULL,1524,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(115,'Elmer  ','Costillas',NULL,NULL,847,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(116,'Randy  ','Cuizon',NULL,NULL,1510,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(117,'Dondy  ','Cuizon',NULL,NULL,1520,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(118,'Merlito  ','Cuyos',NULL,NULL,958,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(119,'Ligaya Jane ','Dacanay',NULL,NULL,163,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(120,'Freddiemar  ','Dacollo',NULL,NULL,1046,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(121,'Carlito  ','Dalaguete',NULL,NULL,32,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(122,'Michael  ','Dalaguit',NULL,NULL,851,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(123,'Lyndon Webs ','Damon',NULL,NULL,994,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(124,'Eugene Jr ','Daniel',NULL,NULL,31,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(125,'Cherie Belle ','Daniel',NULL,NULL,1503,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(126,'Roel  ','Dayonot',NULL,NULL,510,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(127,'Arce Roberto ','De',NULL,NULL,807,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(128,'Bobet  ','Degracia',NULL,NULL,626,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(129,'Bricks  ','Degracia',NULL,NULL,798,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(130,'Ricardo  ','Degracia',NULL,NULL,800,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(131,'Gregorio  ','Deiparine',NULL,NULL,37,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(132,'Gregorio Jr ','Deiparine',NULL,NULL,843,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(133,'Jireh Corpuz ','Deniel',NULL,NULL,853,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(134,'Garmen Jay ','Dinglas',NULL,NULL,4011,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(135,'Lemuel  ','Dinglasa',NULL,NULL,4003,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(136,'Elinido  ','Dongay',NULL,NULL,818,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(137,'Niño Lito ','Dosdos',NULL,NULL,428,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(138,'Melchor Jr ','Durango',NULL,NULL,300,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(139,'Glenn  ','Ebo',NULL,NULL,496,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(140,'Jacinto  ','Ebo',NULL,NULL,704,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(141,'Ben  ','Ebrado',NULL,NULL,4000,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(142,'Zosimo  ','Echavez',NULL,NULL,679,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(143,'Sabino  ','Elizar',NULL,NULL,925,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(144,'Leonard  ','Endozo',NULL,NULL,804,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(145,'Arnel  ','Enobacan',NULL,NULL,1513,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(146,'Marlon  ','Escobal',NULL,NULL,948,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(147,'Junnifer  ','Espinosa',NULL,NULL,832,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(148,'Tirso  ','Espra',NULL,NULL,935,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(149,'Jose  ','Estellore',NULL,NULL,44,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(150,'Jeffrey  ','Estillore',NULL,NULL,45,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(151,'Herardo  ','Estopa',NULL,NULL,944,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(152,'Juncris  ','Estopa',NULL,NULL,959,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(153,'Elpedio  ','Estrabela',NULL,NULL,390,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(154,'Restitutu  ','Estrada',NULL,NULL,39,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(155,'Leonardo  ','Ferolino',NULL,NULL,48,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(156,'Teofilo  ','Ferolino',NULL,NULL,501,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(157,'Jhessler  ','Ferolino',NULL,NULL,877,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(158,'Emerito  ','Ferras',NULL,NULL,815,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(159,'John Raymund ','Flores',NULL,NULL,835,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(160,'Mario  ','Flores',NULL,NULL,1509,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(161,'Kart Bryan ','Frasco',NULL,NULL,499,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(162,'Charley  ','Gabiana',NULL,NULL,833,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(163,'Bernie  ','Gabinete',NULL,NULL,589,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(164,'Julius  ','Gabrillo',NULL,NULL,503,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(165,'Gorofredo  ','Galos',NULL,NULL,69,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(166,'Rondy  ','Galvez',NULL,NULL,355,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(167,'Neigel  ','Galvez',NULL,NULL,850,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(168,'Mark Anthony ','Gamba',NULL,NULL,828,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(169,'Benjie  ','Gandalon',NULL,NULL,581,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(170,'Edlly  ','Ganon',NULL,NULL,88,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(171,'Rafael Jr ','Garcia',NULL,NULL,70,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(172,'Ruben  ','Garcia',NULL,NULL,188,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(173,'James  ','Garcia',NULL,NULL,417,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(174,'Brian  ','Garcia',NULL,NULL,494,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(175,'Maysarry  ','Garcia',NULL,NULL,765,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(176,'Junbell  ','Garcia',NULL,NULL,880,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(177,'Ryan  ','Garcia',NULL,NULL,1529,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(178,'Leo  ','Gayon',NULL,NULL,830,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(179,'Ronillo  ','Genares',NULL,NULL,72,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(180,'Ricardo  ','Genon',NULL,NULL,73,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(181,'Bren Brick ','Genon',NULL,NULL,856,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(182,'Jesa  ','Gentapa',NULL,NULL,528,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(183,'Jomarie  ','Geoca',NULL,NULL,1532,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(184,'Ambrosio Jr ','Geraldez',NULL,NULL,190,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(185,'Roel  ','Geraldez',NULL,NULL,703,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(186,'Mccollin  ','Giduquio',NULL,NULL,927,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(187,'Egmedio  ','Gimenez',NULL,NULL,149,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(188,'Jessa Mae S.','Gimina',NULL,NULL,874,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(189,'Nestor  ','Goc-Ong',NULL,NULL,701,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(190,'Lorenzo  ','Goc-Ong',NULL,NULL,823,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(191,'Ranel  ','Gonzales',NULL,NULL,77,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(192,'Dave John Mark','Gonzales',NULL,NULL,571,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(193,'Jason  ','Gorres',NULL,NULL,860,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(194,'Roger  ','Gorson',NULL,NULL,655,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(195,'Heart Ace ','Guinto',NULL,NULL,996,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(196,'Paul Vincent ','Gutierrez',NULL,NULL,756,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(197,'Antonio  ','Heramil',NULL,NULL,78,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(198,'Alexander  ','Herana',NULL,NULL,940,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(199,'Margarito Jr ','Herbias',NULL,NULL,1519,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(200,'Raffy  ','Herediano',NULL,NULL,939,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(201,'Warren  ','Heruela',NULL,NULL,1058,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(202,'Shoji  ','Higa',NULL,NULL,576,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(203,'Danilo  ','Hingoyon',NULL,NULL,934,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(204,'Randell  ','Hisoler',NULL,NULL,743,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(205,'Vg  ','Ilustrisimo',NULL,NULL,957,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(206,'Jennifer  ','Infiesto',NULL,NULL,883,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(207,'Roque Louie ','Israel',NULL,NULL,882,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(208,'Elan  ','Jaberina',NULL,NULL,859,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(209,'Marvin  ','Jaime',NULL,NULL,946,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(210,'Isagani  ','Janson',NULL,NULL,875,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(211,'Johan  ','Joseph',NULL,NULL,346,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(212,'Hengson  ','Juan',NULL,NULL,852,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(213,'Wilfredo  ','Jubay',NULL,NULL,173,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(214,'Jimuel  ','Jubay',NULL,NULL,839,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(215,'Jerence  ','Jumamoy',NULL,NULL,684,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(216,'Manuelito  ','Jumao-As',NULL,NULL,84,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(217,'Perfecto  ','Jumao-As',NULL,NULL,85,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(218,'Ramir  ','Jumao-As',NULL,NULL,86,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(219,'Robert  ','Jumao-As',NULL,NULL,87,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(220,'Angelie  ','Jumao-As',NULL,NULL,490,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(221,'Eljhun  ','Kionesala',NULL,NULL,723,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(222,'Kent  ','Kristensen',NULL,NULL,849,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(223,'Michael  ','Labajo',NULL,NULL,862,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(224,'Alvin  ','Labang',NULL,NULL,795,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(225,'Arlice  ','Labrador',NULL,NULL,857,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(226,'Elbert  ','Lacasa',NULL,NULL,488,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(227,'Louisito  ','Ladera',NULL,NULL,5,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(228,'Mary Ann ','Lamberte',NULL,NULL,777,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(229,'Felixberto Jr ','Lapiz',NULL,NULL,796,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(230,'Genaro  ','Largo',NULL,NULL,424,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(231,'Dariel  ','Larita',NULL,NULL,794,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(232,'John Lewis ','Laroa',NULL,NULL,4005,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(233,'Maria Fatima ','Latayada',NULL,NULL,411,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(234,'Charmine  ','Latayada',NULL,NULL,713,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(235,'Jim Boy ','Latayada',NULL,NULL,812,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(236,'Jonas  ','Laurente',NULL,NULL,358,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(237,'Jason  ','Laurente',NULL,NULL,620,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(238,'Jell Boy ','Laurente',NULL,NULL,950,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(239,'Archie  ','Lavandero',NULL,NULL,834,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(240,'Chimar  ','Lawas',NULL,NULL,4004,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(241,'Wilfredo  ','Lazaga',NULL,NULL,961,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(242,'Floricho  ','Legania',NULL,NULL,995,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(243,'Leo  ','Legaspi',NULL,NULL,678,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(244,'Jilquin  ','Lenizo',NULL,NULL,166,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(245,'Libod Ubaldo ','Libod',NULL,NULL,864,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(246,'Pedro  ','Librea',NULL,NULL,708,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(247,'Oscar  ','Librea',NULL,NULL,709,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(248,'Maria Socorro ','Lim',NULL,NULL,601,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(249,'Ricardo  ','Limbaga',NULL,NULL,820,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(250,'Saturnino  ','Limbaga',NULL,NULL,821,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(251,'Richard  ','Lingaolingao',NULL,NULL,652,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(252,'Agusting  ','Llaguno',NULL,NULL,885,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(253,'Ramil  ','Luad',NULL,NULL,868,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(254,'Alfredo  ','Lutchana',NULL,NULL,100,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(255,'Anthony  ','Maasin',NULL,NULL,797,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(256,'Ronaldo  ','Mabunay',NULL,NULL,192,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(257,'Arnel  ','Macarayan',NULL,NULL,554,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(258,'Joel  ','Macario',NULL,NULL,101,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(259,'Joshua  ','Maderazo',NULL,NULL,749,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(260,'Allan  ','Madrid',NULL,NULL,102,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(261,'Crisaldo  ','Madrid',NULL,NULL,103,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(262,'Edilberto  ','Madrid',NULL,NULL,104,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(263,'Christian  ','Madrid',NULL,NULL,514,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(264,'Hilario  ','Madrid',NULL,NULL,558,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(265,'Jethro  ','Magdasal',NULL,NULL,872,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(266,'Carlo Magno ','Majo',NULL,NULL,836,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(267,'Eliseo  ','Malubay',NULL,NULL,193,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(268,'Allan  ','Mama',NULL,NULL,814,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(269,'Vincent  ','Manalili',NULL,NULL,354,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(270,'Melvin  ','Manaytay',NULL,NULL,1521,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(271,'Dexter  ','Manga',NULL,NULL,1054,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(272,'Rolex  ','Manguib',NULL,NULL,936,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(273,'Geramel  ','Manguilimotan',NULL,NULL,560,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(274,'Virgeliojr  ','Manguilimutan',NULL,NULL,400,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(275,'Daneboy  ','Mansing',NULL,NULL,792,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(276,'Catalino  ','Mantalaba',NULL,NULL,575,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(277,'Angelvir  ','Maranga',NULL,NULL,766,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(278,'Elcarte  ','Margarito',NULL,NULL,683,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(279,'Kenneth  ','Matus',NULL,NULL,819,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(280,'Altor  ','Mauro',NULL,NULL,725,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(281,'Manny  ','Maybanting',NULL,NULL,884,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(282,'Rocky  ','Metante',NULL,NULL,943,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(283,'Petronilo  ','Miñoza',NULL,NULL,112,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(284,'Arnolfo  ','Miñoza',NULL,NULL,621,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(285,'Shervayne  ','Mira',NULL,NULL,4009,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(286,'Joseph  ','Moday',NULL,NULL,146,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(287,'Jingle  ','Molero',NULL,NULL,552,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(288,'Juanito  ','Mondalo',NULL,NULL,451,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(289,'Alter  ','Moral',NULL,NULL,826,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(290,'Jelmar  ','Morcilla',NULL,NULL,23,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(291,'Roy  ','Moreno',NULL,NULL,865,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(292,'Arjay  ','Murcia',NULL,NULL,840,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(293,'Aljhondel  ','Nabizaga',NULL,NULL,1055,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(294,'Aljhonnel  ','Nabizaga',NULL,NULL,1534,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(295,'Genevive  ','Naparate',NULL,NULL,780,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(296,'Elenita  ','Napuar',NULL,NULL,561,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(297,'Rolly  ','Narsico',NULL,NULL,993,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(298,'Candice  ','Neri',NULL,NULL,49,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(299,'Roy  ','Nocillas',NULL,NULL,536,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(300,'Ailen  ','Nortiza',NULL,NULL,634,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(301,'Elmer  ','Obaner',NULL,NULL,1512,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(302,'Arsenio  ','Ocaña',NULL,NULL,51,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(303,'Brendo  ','Occo',NULL,NULL,805,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(304,'Isabellou  ','Odango',NULL,NULL,4007,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(305,'Mark  ','Odlot',NULL,NULL,992,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(306,'Henexodo  ','Olipe',NULL,NULL,379,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(307,'John Henex ','Olipe',NULL,NULL,813,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(308,'Crister  ','Ombing',NULL,NULL,1056,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(309,'Johnny  ','Omboy',NULL,NULL,53,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(310,'Flaviano  ','Ornopia',NULL,NULL,953,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(311,'Wendell  ','Palana',NULL,NULL,949,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(312,'Brian  ','Paler',NULL,NULL,768,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(313,'Charlie  ','Palo',NULL,NULL,167,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(314,'Carlo John ','Palo',NULL,NULL,388,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(315,'Chris  ','Palo',NULL,NULL,389,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(316,'Carlo  ','Paltingca',NULL,NULL,838,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(317,'Sabino Jr ','Panerio',NULL,NULL,926,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(318,'Armando  ','Pansacala',NULL,NULL,688,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(319,'Richard  ','Pantorilla',NULL,NULL,988,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(320,'Ariel  ','Parajes',NULL,NULL,502,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(321,'Constancio  ','Pardillo',NULL,NULL,1525,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(322,'Albino  ','Pasuquin',NULL,NULL,1514,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(323,'Jerry  ','Patagatay',NULL,NULL,557,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(324,'Victorio  ','Patalinghug',NULL,NULL,61,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(325,'Nestor  ','Patrolla',NULL,NULL,793,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(326,'Agripino  ','Payao',NULL,NULL,62,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(327,'Fredmond  ','Pelegro',NULL,NULL,4006,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(328,'Reynan  ','Pelorina',NULL,NULL,313,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(329,'Jessie  ','Penaflor',NULL,NULL,1053,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(330,'Jenalyn  ','Peñas',NULL,NULL,564,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(331,'Flaviano  ','Pescadero',NULL,NULL,691,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(332,'Rey  ','Piquero',NULL,NULL,932,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(333,'Rino  ','Pitogo',NULL,NULL,394,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(334,'Augustin  ','Pitogo',NULL,NULL,478,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(335,'Jona  ','Porhelia',NULL,NULL,328,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(336,'Nolie  ','Porras',NULL,NULL,311,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(337,'Viemar  ','Prangos',NULL,NULL,8,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(338,'Jovenal  ','Prangos',NULL,NULL,119,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(339,'Marvin  ','Prangos',NULL,NULL,636,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(340,'Victor  ','Prangos',NULL,NULL,705,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(341,'Alex  ','Prangos',NULL,NULL,1527,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(342,'Julius  ','Prangos',NULL,NULL,1530,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(343,'Lord Kim ','Quiamco',NULL,NULL,870,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(344,'Aureo  ','Quibot',NULL,NULL,120,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(345,'Leopoldo Jr ','Quidam',NULL,NULL,989,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(346,'Nestor  ','Quimada',NULL,NULL,121,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(347,'Jonavei  ','Quinaquin',NULL,NULL,142,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(348,'Jeffrey  ','Quiño',NULL,NULL,922,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(349,'Junrie  ','Quirally',NULL,NULL,423,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(350,'Michael  ','Quisay',NULL,NULL,802,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(351,'Villacer  ','Rafaela',NULL,NULL,837,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(352,'Jonathan  ','Rasonabe',NULL,NULL,325,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(353,'Vergil  ','Regner',NULL,NULL,801,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(354,'Aldrin  ','Resuelo',NULL,NULL,385,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(355,'Jason  ','Ripdos',NULL,NULL,4008,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(356,'Dennis  ','Ritavale',NULL,NULL,531,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(357,'Romel  ','Rivas',NULL,NULL,126,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(358,'Reyland  ','Roa',NULL,NULL,941,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(359,'Eddie  ','Roble',NULL,NULL,128,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(360,'Crosanto  ','Robles',NULL,NULL,808,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(361,'Ruben Irwin ','Rodriguez',NULL,NULL,129,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(362,'Ryan Jay ','Rodriguez',NULL,NULL,867,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(363,'Marlou  ','Romales',NULL,NULL,452,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(364,'Richard S. ','Rubostro,',NULL,NULL,955,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(365,'Roel  ','Rufin',NULL,NULL,861,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(366,'Heizel Roland ','Saavedra',NULL,NULL,422,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(367,'Vicente  ','Saavedra',NULL,NULL,4002,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(368,'Hermes  ','Sabroso',NULL,NULL,63,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(369,'Gerald  ','Sabroso',NULL,NULL,779,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(370,'Jade  ','Salmeron',NULL,NULL,1531,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(371,'Liezel  ','Saluta',NULL,NULL,336,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(372,'German  ','Sapotalo',NULL,NULL,436,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(373,'Felix  ','Sariego',NULL,NULL,440,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(374,'Roberto Jr ','Sayaboc',NULL,NULL,999,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(375,'Dionesio  ','Sayson',NULL,NULL,1051,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(376,'Chuck  ','Sayson',NULL,NULL,4001,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(377,'Castro  ','Secuya',NULL,NULL,706,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(378,'Edito  ','Segarino',NULL,NULL,931,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(379,'Dionisio  ','Segarra',NULL,NULL,66,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(380,'Isaias  ','Segarra',NULL,NULL,196,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(381,'James  ','Sepada',NULL,NULL,737,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(382,'Libert  ','Sepuesca',NULL,NULL,831,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(383,'Hyedee  ','Sesaldo',NULL,NULL,570,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(384,'Dioscoro  ','Sevillano',NULL,NULL,644,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(385,'Rene  ','Siempre',NULL,NULL,579,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(386,'Jilwin  ','Sigarra',NULL,NULL,786,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(387,'Carlos  ','Sismar',NULL,NULL,338,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(388,'Mark Arvin ','Soleño',NULL,NULL,203,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(389,'Jonry  ','Solis',NULL,NULL,1528,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(390,'Gino  ','Solon',NULL,NULL,881,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(391,'Ryan Jay ','Sultones',NULL,NULL,960,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(392,'Ronald  ','Surigao',NULL,NULL,181,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(393,'Jose Pepe ','Tagaca',NULL,NULL,133,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(394,'Fernando  ','Tagalogon',NULL,NULL,134,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(395,'Norman  ','Tagaylo',NULL,NULL,702,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(396,'Elmer  ','Taglinao',NULL,NULL,415,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(397,'Gemavel  ','Tahanlangit',NULL,NULL,182,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(398,'Mary Jane ','Tambiga',NULL,NULL,690,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(399,'Abundio  ','Tampipi',NULL,NULL,156,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(400,'Rogien  ','Tampipi',NULL,NULL,645,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(401,'Eugenio  ','Tampos',NULL,NULL,1049,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(402,'Eugenio Jr ','Tampos',NULL,NULL,4013,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(403,'Eddie John ','Tapayan',NULL,NULL,809,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(404,'Emman Joseph ','Tayad',NULL,NULL,748,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(405,'Roanly  ','Teopis',NULL,NULL,1502,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(406,'Jhonell  ','Tomo',NULL,NULL,928,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(407,'Dominguito  ','Tulod',NULL,NULL,764,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(408,'John Alvin ','Turtoga',NULL,NULL,696,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(409,'Junkhemer  ','Turtur',NULL,NULL,1047,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(410,'Ruzel  ','Ubas',NULL,NULL,339,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(411,'Jundy  ','Ubod',NULL,NULL,1533,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(412,'Greg  ','Ugtong',NULL,NULL,3,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(413,'Kent Jay ','Unabia',NULL,NULL,990,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(414,'Charlie  ','Unabia',NULL,NULL,1526,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(415,'Karen  ','Urdaneta',NULL,NULL,879,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(416,'Edan Jessl ','Ursal',NULL,NULL,52,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(417,'Nelson  ','Ursal',NULL,NULL,138,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(418,'Warene  ','Ursal',NULL,NULL,869,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(419,'Axel  ','Valmores',NULL,NULL,1504,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(420,'Jose  ','Vanzuela',NULL,NULL,845,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(421,'Ritchie  ','Villacencio',NULL,NULL,378,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(422,'Pablito  ','Villamero',NULL,NULL,710,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(423,'Renante  ','Villanueva',NULL,NULL,648,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(424,'Gilbert  ','Villanueva',NULL,NULL,751,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(425,'Alfonso Jr ','Villarino',NULL,NULL,533,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(426,'Albert  ','Villarojo',NULL,NULL,140,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(427,'Danny  ','Villarojo',NULL,NULL,647,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(428,'Emelio  ','Villarubia',NULL,NULL,507,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(429,'Michael  ','Villarubia',NULL,NULL,755,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(430,'Marcelina  ','Villasencio',NULL,NULL,1522,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(431,'Ponciano  ','Villasin',NULL,NULL,539,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(432,'Reynaldo  ','Villaso',NULL,NULL,1507,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(433,'Remar  ','Villena',NULL,NULL,1052,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(434,'Zoilo  ','Wagas',NULL,NULL,673,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(435,'Benjamin Jr Ii','Wooten',NULL,NULL,811,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(436,'Carlito Jr ','Ybanez',NULL,NULL,998,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(437,'Carlito  ','Ybañez',NULL,NULL,991,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(438,'Francisco  ','Yorpo',NULL,NULL,844,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1),
(439,'Jiovani  ','Zanoria',NULL,NULL,4012,NULL,NULL,NULL,NULL,'N',NULL,NULL,'N',NULL,'N',0.00,1,'M',NULL,NULL,NULL,0.00,'N',1,NULL,1,1);

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

/*Table structure for table `holiday_location` */

DROP TABLE IF EXISTS `holiday_location`;

CREATE TABLE `holiday_location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `holiday_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `holidayAndLocationIndex` (`holiday_id`,`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

/*Data for the table `holiday_location` */

insert  into `holiday_location`(`id`,`holiday_id`,`location_id`) values 
(1,2,1),
(2,2,2),
(3,2,3);

/*Table structure for table `holiday_types` */

DROP TABLE IF EXISTS `holiday_types`;

CREATE TABLE `holiday_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_description` varchar(24) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Data for the table `holiday_types` */

insert  into `holiday_types`(`id`,`type_description`) values 
(1,'Legal Holiday'),
(2,'Special Holiday'),
(3,'Double Legal Holiday'),
(4,'Company Holiday');

/*Table structure for table `holidays` */

DROP TABLE IF EXISTS `holidays`;

CREATE TABLE `holidays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `holiday_date` date DEFAULT NULL,
  `holiday_remarks` text,
  `holiday_type` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*Data for the table `holidays` */

insert  into `holidays`(`id`,`holiday_date`,`holiday_remarks`,`holiday_type`) values 
(1,'2022-08-23','1231231231','0'),
(2,'2022-08-06','sdsdfsdfsdf','4');

/*Table structure for table `job_titles` */

DROP TABLE IF EXISTS `job_titles`;

CREATE TABLE `job_titles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dept_id` int(11) DEFAULT NULL,
  `job_title_code` varchar(6) DEFAULT NULL,
  `job_title_name` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `job_titles` */

/*Table structure for table `locations` */

DROP TABLE IF EXISTS `locations`;

CREATE TABLE `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_name` varchar(24) DEFAULT NULL,
  `location_address` text,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

/*Data for the table `locations` */

insert  into `locations`(`id`,`location_name`,`location_address`) values 
(1,'BPN','JLR Compound B. Suico Street Tingub Mandaue City, Cebu'),
(2,'BPS','Sombria,Lawaan II,Talisay City, Cebu'),
(3,'QAD','Cogon Naga, Cebu');

/*Table structure for table `main_menu` */

DROP TABLE IF EXISTS `main_menu`;

CREATE TABLE `main_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_desc` varchar(32) DEFAULT NULL,
  `menu_icon` varchar(24) DEFAULT NULL,
  `menu_link` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

/*Data for the table `main_menu` */

insert  into `main_menu`(`id`,`menu_desc`,`menu_icon`,`menu_link`) values 
(1,'Timekeeping','fas fa-user-clock','timekeeping'),
(2,'Employee File','fas fa-user-cog','employee-files'),
(3,'Payroll Transaction','fas fa-calculator','payroll-transaction'),
(4,'Settings','fas fa-cog','settings'),
(5,'Reports','fas fa-chart-pie','reports');

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;

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
(18,'2022-08-22','2022-08-22','2022-08-22',99),
(19,'2022-08-16','2022-08-31','2022-08-24',112);

/*Table structure for table `payroll_period_weekly` */

DROP TABLE IF EXISTS `payroll_period_weekly`;

CREATE TABLE `payroll_period_weekly` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `date_release` date DEFAULT NULL,
  `man_hours` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

/*Data for the table `payroll_period_weekly` */

insert  into `payroll_period_weekly`(`id`,`date_from`,`date_to`,`date_release`,`man_hours`) values 
(1,'2022-08-23','2022-08-23','2022-08-23',0),
(2,'2022-08-22','2022-08-26','2022-09-03',0),
(3,'2022-08-29','2022-09-02','2022-09-03',5);

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

/*Data for the table `sub_menu` */

insert  into `sub_menu`(`id`,`sub_menu_desc`,`sub_menu_main`,`sub_menu_link`,`sub_menu_icon`) values 
(1,'Payroll Period - Semi',1,'timekeeping/payroll-period','fas fa-calendar-alt'),
(2,'Payroll Period - Weekly',1,'timekeeping/payroll-period-weekly','fas fa-calendar-week'),
(3,'Emp. Master Data',2,'employee-files/employee-master-data','fas fa-list-ul'),
(4,'Holidays',1,'timekeeping/holiday','fas fa-calendar-check'),
(5,'Leaves / Absences',1,'timekeeping/leaves-absences','fas fa-plane-departure'),
(6,'Divisions / Departments',2,'employee-files/divisions-departments','fas fa-project-diagram'),
(7,'Job Titles',2,'employee-files/job-title','fas fa-user-tag'),
(8,'Payroll Register',3,'payroll-transaction/payroll-register','fas fa-file-csv'),
(9,'Payslip',3,'payroll-transaction/payslip','fas fa-money-check-alt'),
(10,'Bank Transmittal',3,'payroll-transaction/bank-transmittal','fas fa-credit-card'),
(11,'Locations',4,'settings/locations','fas fa-map-marked-alt'),
(12,'Employee List',5,'reports/employee-report','fas fa-address-book'),
(13,'Manage DTR',1,'timekeeping/manage-dtr','fas fa-user-clock'),
(14,'Upload Log File',1,'timekeeping/upload-log','fas fa-upload');

/*Table structure for table `user_rights` */

DROP TABLE IF EXISTS `user_rights`;

CREATE TABLE `user_rights` (
  `line_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `sub_menu_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`line_id`),
  KEY `userRightsIndex` (`user_id`,`sub_menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb4;

/*Data for the table `user_rights` */

insert  into `user_rights`(`line_id`,`user_id`,`sub_menu_id`) values 
(48,1,1),
(46,1,2),
(47,1,3),
(57,1,4),
(58,1,5),
(59,1,6),
(60,1,7),
(61,1,8),
(62,1,9),
(63,1,10),
(64,1,11),
(92,1,12),
(96,1,13),
(97,1,14),
(50,2,1),
(51,2,2),
(53,2,3),
(66,2,4),
(65,2,5),
(68,2,6),
(67,2,7),
(69,2,8),
(70,2,9),
(72,2,10),
(71,2,11),
(94,2,12),
(54,3,1),
(55,3,2),
(56,3,3),
(73,3,4),
(74,3,5),
(75,3,6),
(76,3,7),
(80,3,8),
(79,3,9),
(78,3,10),
(77,3,11),
(95,3,12),
(81,4,1),
(82,4,2),
(85,4,3),
(83,4,4),
(84,4,5),
(86,4,6),
(87,4,7),
(88,4,8),
(89,4,9),
(90,4,10),
(91,4,11),
(93,4,12);

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`email_verified_at`,`password`,`remember_token`,`created_at`,`updated_at`) values 
(1,'Elmer Costillas','elmer.costillas@jlr.com',NULL,'$2y$10$3IKUx138HNtav1gRYf9ItuOb4BDoHdzvlOyzLLf9R7OEYndX5yynG',NULL,'2022-08-19 13:06:14','2022-08-19 13:06:14'),
(2,'Genevive Naparate','genevive.naparate@jlr.com',NULL,'$2y$10$9aWkLwUzw3RU8q4dCFvXde9Mfx.0HeaA1X9uKaxd3UC4Sf02gKwsC',NULL,'2022-08-22 05:34:32','2022-08-22 05:34:32'),
(3,'Angelie Abejo','angelie.abejo@jlr.com',NULL,'$2y$10$rwnBy6SaQyvEstn7qyXGD.1vJDL.E631853B3KMPkwfj4Fh98S/gu',NULL,'2022-08-22 05:33:55','2022-08-22 05:33:55'),
(4,'Herbert Banaston Camasura','herbert.camasura@jlr.com',NULL,'$2y$10$yBuUMfM1GsL67qjqGUjmV.c5pnhKGcHDWSNGD.7k4bFlb3FEPqDfC',NULL,'2022-08-23 22:35:50','2022-08-23 22:35:50');

/*Table structure for table `work_schedules` */

DROP TABLE IF EXISTS `work_schedules`;

CREATE TABLE `work_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time_in` varchar(5) DEFAULT NULL,
  `time_out` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

/*Data for the table `work_schedules` */

insert  into `work_schedules`(`id`,`time_in`,`time_out`) values 
(1,'07:45','17:00'),
(2,'07:45','17:30'),
(3,'07:00','15:00'),
(4,'07:00','16:00'),
(5,'19:00','03:00'),
(6,'16:00','00:00'),
(7,'19:00','01:00');

/* Function  structure for function  `proper` */

/*!50003 DROP FUNCTION IF EXISTS `proper` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `proper`( str VARCHAR(128) ) RETURNS varchar(128) CHARSET utf8mb4
BEGIN
  DECLARE c CHAR(1);
  DECLARE s VARCHAR(128);
  DECLARE i INT DEFAULT 1;
  DECLARE bool INT DEFAULT 1;
  DECLARE punct CHAR(18) DEFAULT ' ()[]{},.-_\'!@;:?/'; -- David Rabby & Lenny Erickson added \'
  SET s = LCASE( str );
  WHILE i <= LENGTH( str ) DO -- Jesse Palmer corrected from < to <= for last char
    BEGIN
      SET c = SUBSTRING( s, i, 1 );
      IF LOCATE( c, punct ) > 0 THEN
        SET bool = 1;
      ELSEIF bool=1 THEN 
        BEGIN
          IF c >= 'a' AND c <= 'z' THEN 
            BEGIN
              SET s = CONCAT(LEFT(s,i-1),UCASE(c),SUBSTRING(s,i+1));
              SET bool = 0;
            END;
          ELSEIF c >= '0' AND c <= '9' THEN
            SET bool = 0;
          END IF;
        END;
      END IF;
      SET i = i+1;
    END;
  END WHILE;
  RETURN s;
END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

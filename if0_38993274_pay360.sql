/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.5.27-MariaDB, for Linux (x86_64)
--
-- Host: sql313.byetcluster.com    Database: if0_38993274_pay360
-- ------------------------------------------------------
-- Server version	10.6.19-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account` (
  `AccountID` int(11) NOT NULL AUTO_INCREMENT,
  `MemberID` int(11) NOT NULL,
  `Account_Type` varchar(100) NOT NULL,
  `Balance` decimal(10,0) NOT NULL,
  PRIMARY KEY (`AccountID`),
  KEY `Member_ID` (`MemberID`),
  CONSTRAINT `Member_ID` FOREIGN KEY (`MemberID`) REFERENCES `member` (`MemberID`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account`
--

LOCK TABLES `account` WRITE;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
INSERT INTO `account` VALUES (1,1,'Savings',100000),(2,2,'Checking',15000),(3,3,'Savings',7500),(4,4,'Time Deposit',50000),(5,5,'Emergency Fund',30000),(6,6,'Savings',12500),(7,7,'Checking',8000),(8,8,'Time Deposit',100000),(9,9,'Savings',5000),(10,10,'Emergency Fund',20000),(11,5,'Savings',20000),(12,5,'Savings',20000),(13,5,'Savings',20000),(14,5,'Savings',20000),(15,1,'Fixed Account',200000);
/*!40000 ALTER TABLE `account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `account_restrictions`
--

DROP TABLE IF EXISTS `account_restrictions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_restrictions` (
  `restriction_id` int(11) NOT NULL AUTO_INCREMENT,
  `AccountID` int(11) NOT NULL,
  `maturity_date` date DEFAULT NULL,
  `early_withdrawal_penalty` decimal(5,4) DEFAULT NULL,
  `minimum_balance` decimal(15,2) DEFAULT NULL,
  `restriction_description` text DEFAULT NULL,
  PRIMARY KEY (`restriction_id`),
  KEY `fk_account_restriction` (`AccountID`),
  CONSTRAINT `fk_account_restriction` FOREIGN KEY (`AccountID`) REFERENCES `account` (`AccountID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_restrictions`
--

LOCK TABLES `account_restrictions` WRITE;
/*!40000 ALTER TABLE `account_restrictions` DISABLE KEYS */;
INSERT INTO `account_restrictions` VALUES (1,4,'2026-05-10',0.0150,50000.00,'Fixed 1-year term with 1.5% early withdrawal penalty'),(2,8,'2025-11-10',0.0200,100000.00,'6-month term with 2% early withdrawal penalty'),(3,15,'2026-05-17',0.0500,0.00,NULL);
/*!40000 ALTER TABLE `account_restrictions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `account_types`
--

DROP TABLE IF EXISTS `account_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_types` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(50) NOT NULL,
  `allows_deposits` tinyint(1) DEFAULT 1,
  `allows_withdrawals` tinyint(1) DEFAULT 1,
  `has_restrictions` tinyint(1) DEFAULT 0,
  `default_interest_rate` decimal(5,4) DEFAULT NULL,
  `minimum_opening_balance` decimal(15,2) DEFAULT NULL,
  `maintenance_fee` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`type_id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_types`
--

LOCK TABLES `account_types` WRITE;
/*!40000 ALTER TABLE `account_types` DISABLE KEYS */;
INSERT INTO `account_types` VALUES (1,'Savings',1,1,0,0.0025,100.00,0.00,'Standard savings account with flexible deposits and withdrawals'),(2,'Time Deposit',1,1,1,0.0350,1000.00,0.00,'Time-bound deposit with higher interest, 6-month lock-in'),(6,'Fixed Account',1,1,0,NULL,5000.00,NULL,'Fixed-term account with highest interest, 12-month lock-in');
/*!40000 ALTER TABLE `account_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `AdminID` int(11) NOT NULL AUTO_INCREMENT,
  `Firstname` varchar(255) NOT NULL,
  `Middlename` varchar(255) NOT NULL,
  `Lastname` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Contact_No` varchar(20) NOT NULL,
  `Role` varchar(255) NOT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`AdminID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'Raine','Galang','Pangilinan','rainepangilinan11@gmail.com','adminraine44','09304370182','admin','2025-05-08 13:20:10');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `interest_accruals`
--

DROP TABLE IF EXISTS `interest_accruals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `interest_accruals` (
  `accrual_id` int(11) NOT NULL AUTO_INCREMENT,
  `AccountID` int(11) NOT NULL,
  `accrual_date` date NOT NULL,
  `interest_rate` decimal(5,4) NOT NULL,
  `balance_used` decimal(15,2) NOT NULL,
  `interest_amount` decimal(15,2) NOT NULL,
  `is_paid` tinyint(1) DEFAULT 0,
  `payment_date` date DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`accrual_id`),
  KEY `AccountID` (`AccountID`),
  KEY `transaction_id` (`transaction_id`),
  CONSTRAINT `interest_accruals_ibfk_1` FOREIGN KEY (`AccountID`) REFERENCES `account` (`AccountID`),
  CONSTRAINT `interest_accruals_ibfk_2` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interest_accruals`
--

LOCK TABLES `interest_accruals` WRITE;
/*!40000 ALTER TABLE `interest_accruals` DISABLE KEYS */;
INSERT INTO `interest_accruals` VALUES (1,1,'2025-04-30',0.0025,25000.00,5.21,1,'2025-05-01',NULL),(2,2,'2025-04-30',0.0010,15000.00,1.25,1,'2025-05-01',NULL),(3,3,'2025-04-30',0.0025,7500.00,1.56,1,'2025-05-01',NULL),(4,4,'2025-04-30',0.0350,50000.00,145.83,1,'2025-05-01',NULL),(5,5,'2025-04-30',0.0020,30000.00,5.00,1,'2025-05-01',NULL),(6,6,'2025-04-30',0.0025,12500.00,2.60,1,'2025-05-01',NULL),(7,7,'2025-04-30',0.0010,8000.00,0.67,1,'2025-05-01',NULL),(8,8,'2025-04-30',0.0350,100000.00,291.67,1,'2025-05-01',NULL),(9,9,'2025-04-30',0.0025,5000.00,1.04,1,'2025-05-01',NULL),(10,10,'2025-04-30',0.0020,20000.00,3.33,1,'2025-05-01',NULL);
/*!40000 ALTER TABLE `interest_accruals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan`
--

DROP TABLE IF EXISTS `loan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loan` (
  `LoanID` int(11) NOT NULL AUTO_INCREMENT,
  `MemberID` int(11) NOT NULL,
  `Firstname` varchar(255) NOT NULL,
  `Middlename` varchar(255) NOT NULL,
  `Lastname` varchar(255) NOT NULL,
  `Purpose` varchar(255) NOT NULL,
  `Amount` decimal(10,0) NOT NULL,
  `Interest` decimal(10,0) NOT NULL,
  `Total_Amount` decimal(10,0) NOT NULL,
  `Monthly_Amortization` decimal(10,0) NOT NULL,
  `Period` int(255) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `Start_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`LoanID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan`
--

LOCK TABLES `loan` WRITE;
/*!40000 ALTER TABLE `loan` DISABLE KEYS */;
INSERT INTO `loan` VALUES (1,5,'Pedro','Fali','Pascual','Business',100000,5,105000,15000,12,'active','2025-05-09 12:24:55'),(2,9,'Michael','Lee','Garcia','Medical Expenses',80000,4,83200,6933,12,'active','2025-05-08 01:30:00'),(3,3,'Ron','Pans','Ford','Car Payment',50000,5,52500,2188,24,'active','2025-05-15 17:33:42'),(4,11,'Alj','V','Pangilinan','Porsche',100000,20,120000,20000,6,'active','2025-05-22 17:01:14'),(5,10,'Sofia','Angeles','Tan','Calamities',10000,2,10200,5100,2,'active','2025-05-22 20:01:50');
/*!40000 ALTER TABLE `loan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_application`
--

DROP TABLE IF EXISTS `loan_application`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loan_application` (
  `Loan_AppID` int(11) NOT NULL AUTO_INCREMENT,
  `MemberID` int(11) NOT NULL,
  `Firstname` varchar(255) NOT NULL,
  `Middlename` varchar(255) NOT NULL,
  `Lastname` varchar(255) NOT NULL,
  `Purpose` varchar(255) NOT NULL,
  `Amount` decimal(10,0) NOT NULL,
  `Interest` decimal(10,0) NOT NULL,
  `Total_Amount` decimal(10,0) NOT NULL,
  `Monthly_Amortization` decimal(10,0) NOT NULL,
  `Period` int(255) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `Requested_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reference_number` int(11) NOT NULL,
  PRIMARY KEY (`Loan_AppID`),
  KEY `MemberID` (`MemberID`),
  CONSTRAINT `MemberID` FOREIGN KEY (`MemberID`) REFERENCES `member` (`MemberID`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_application`
--

LOCK TABLES `loan_application` WRITE;
/*!40000 ALTER TABLE `loan_application` DISABLE KEYS */;
INSERT INTO `loan_application` VALUES (1,5,'Pedro','Fali','Pascual','Business',100000,5,105000,15000,12,'approved','2025-05-09 12:24:55',0),(2,1,'Jay','P','Ford','Education',20000,10,22000,1833,12,'pending','2025-05-09 11:11:58',0),(3,6,'Maria','Santos','Reyes','Home Renovation',200000,6,212000,17667,12,'pending','2025-05-08 02:30:00',0),(4,7,'Juan','Cruz','Dela Cruz','Vehicle Purchase',150000,5,157500,13125,12,'pending','2025-05-09 01:45:00',0),(5,8,'Elena','Magtoto','Santos','Business Expansion',300000,7,321000,17833,18,'pending','2025-05-07 06:20:00',0),(6,9,'Michael','Lee','Garcia','Medical Expenses',80000,4,83200,6933,12,'approved','2025-05-06 03:15:00',0),(7,10,'Sofia','Angeles','Tan','Travel',50000,5,52500,4375,12,'declined','2025-05-05 07:30:00',0),(8,3,'Ron','Pans','Ford','Car Payment',50000,5,52500,2188,24,'approved','2025-05-15 17:33:42',0),(16,9,'Michael','Lee','Garcia','Home Renovation',50000,15,57500,9583,6,'pending','2025-05-16 10:45:49',0),(17,11,'Alj','V','Pangilinan','Porsche',100000,20,120000,20000,6,'approved','2025-05-22 17:01:14',0),(18,10,'Sofia','Angeles','Tan','Calamities',10000,2,10200,5100,2,'approved','2025-05-22 20:01:50',0);
/*!40000 ALTER TABLE `loan_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_payments`
--

DROP TABLE IF EXISTS `loan_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loan_payments` (
  `PaymentID` int(11) NOT NULL AUTO_INCREMENT,
  `LoanID` int(11) NOT NULL,
  `MemberID` int(11) NOT NULL,
  `Payment_Amount` decimal(15,2) NOT NULL,
  `Payment_Date` timestamp NOT NULL DEFAULT current_timestamp(),
  `Remaining_Balance` decimal(15,2) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Status` enum('Completed','Pending','Failed') DEFAULT 'Completed',
  PRIMARY KEY (`PaymentID`),
  KEY `fk_loan_payment_loan` (`LoanID`),
  KEY `fk_loan_payment_member` (`MemberID`),
  CONSTRAINT `fk_loan_payment_loan` FOREIGN KEY (`LoanID`) REFERENCES `loan` (`LoanID`) ON DELETE CASCADE,
  CONSTRAINT `fk_loan_payment_member` FOREIGN KEY (`MemberID`) REFERENCES `member` (`MemberID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_payments`
--

LOCK TABLES `loan_payments` WRITE;
/*!40000 ALTER TABLE `loan_payments` DISABLE KEYS */;
INSERT INTO `loan_payments` VALUES (1,1,5,50000.00,'2025-05-16 18:34:08',55000.00,'Loan payment for Loan ID: 1','Completed');
/*!40000 ALTER TABLE `loan_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member` (
  `MemberID` int(11) NOT NULL AUTO_INCREMENT,
  `Firstname` varchar(255) NOT NULL,
  `Middlename` varchar(255) NOT NULL,
  `Lastname` varchar(255) NOT NULL,
  `Gender` varchar(10) NOT NULL,
  `Birthdate` date NOT NULL,
  `House_No` int(10) NOT NULL,
  `Street` varchar(255) NOT NULL,
  `Barangay_or_Village` varchar(255) NOT NULL,
  `Municipality` varchar(255) NOT NULL,
  `City` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Contact_No` varchar(20) NOT NULL,
  PRIMARY KEY (`MemberID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member`
--

LOCK TABLES `member` WRITE;
/*!40000 ALTER TABLE `member` DISABLE KEYS */;
INSERT INTO `member` VALUES (1,'Jay','P','Ford','Male','1990-11-11',123,'Here','There','Everywhere','Pampanga','onlypans@gmail.com','12121212','09123456765'),(2,'Ren','Zen','Ford','Male','2000-11-11',123,'Main','reto','Bev Hills','San Fernando','kieltroy@gmail.com','12121212','09123456765'),(3,'Ron','Pans','Ford','Other','2000-11-11',123,'Here','There','Everywhere','Pampanga','onlypans@gmail.com','12121212','09123456765'),(4,'Xy','D','Fe','Female','2011-11-11',123,'Kali','There','Everywhere','San Fernando','123@gmail.com','','09123456765'),(5,'Pedro','Fali','Pascual','Male','1990-11-11',123,'Fe','De','We','Qe','pedro@gmail.com','11111111','09946734091'),(6,'Maria','Santos','Reyes','Female','1985-05-15',456,'Oak Street','San Miguel','Angeles','Pampanga','maria.reyes@gmail.com','password123','09187654321'),(7,'Juan','Cruz','Dela Cruz','Male','1978-12-03',789,'Maple Avenue','Santa Lucia','Magalang','Pampanga','juan.delacruz@gmail.com','securepass','09123459876'),(8,'Elena','Magtoto','Santos','Female','1992-07-22',234,'Pine Street','San Jose','San Fernando','Pampanga','elena.santos@gmail.com','elena2022','09765432109'),(9,'Michael','Lee','Garcia','Male','1980-09-10',567,'Cedar Lane','San Pedro','Mabalacat','Pampanga','michael.garcia@gmail.com','mikepass','09234567890'),(10,'Sofia','Angeles','Tan','Female','1995-02-28',890,'Acacia Road','Santo Niño','Guagua','Pampanga','sofia.tan@gmail.com','sofiasecure','09876543210'),(11,'Alj','V','Pangilinan','Female','2004-04-13',222,'Narra','Irawan','Palawan','Puerto','alj123@gmail.com','12121212\r\n','09346356654'),(12,'Zaira Loureen','Lavetoria','Cruz','Female','2006-03-23',911,'Sandico','Poblacion','Bocaue','Bulacan','zairaloureen.bulsu@gmail.com','12121212','09289636262');
/*!40000 ALTER TABLE `member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `membership_application`
--

DROP TABLE IF EXISTS `membership_application`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `membership_application` (
  `ApplicationID` int(11) NOT NULL AUTO_INCREMENT,
  `Firstname` varchar(255) NOT NULL,
  `Middlename` varchar(255) NOT NULL,
  `Lastname` varchar(255) NOT NULL,
  `Gender` enum('Male','Female','Other') NOT NULL,
  `Birthdate` date NOT NULL,
  `House_No` int(100) NOT NULL,
  `Street` varchar(255) NOT NULL,
  `Barangay_or_Village` varchar(255) NOT NULL,
  `Municipality` varchar(255) NOT NULL,
  `CIty` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Contact_No` varchar(20) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Status` enum('pending','approved','declined') DEFAULT 'pending',
  `Applied_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ApplicationID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `membership_application`
--

LOCK TABLES `membership_application` WRITE;
/*!40000 ALTER TABLE `membership_application` DISABLE KEYS */;
INSERT INTO `membership_application` VALUES (4,'Renzy','F.','Quiros','Female','2005-05-11',900,'Kali','Barrio','Apalit','Pampanga','renzy@gmail.com','09123456765','$2y$10$MH5VGDt28G1ruLjj47oC/.Qr7SWUTuXK0yKAdPo.y1/qyHK9t278i','pending','2025-05-08 13:10:26'),(5,'Zen','Garcia','Ford','Male','2000-11-11',123,'Here','There','Everywhere','San Fernando','kieltroy@gmail.com','09123456765','12345678','pending','2025-05-08 13:24:00'),(7,'Carlos','Mendoza','Lim','Male','1988-08-20',123,'Pine Street','San Isidro','Mexico','Pampanga','carlos.lim@gmail.com','09567891234','$2y$10$qDdGt0CzIABr6Jx4r9z7QOZsKXjJz5tyXi8dZc1qvJ2rR3Ueo5QNi','pending','2025-05-09 01:30:00'),(8,'Anna','Lopez','Reyes','Female','1993-04-15',456,'Maple Avenue','San Rafael','Guagua','Pampanga','anna.reyes@gmail.com','09123457890','$2y$10$TyWdRfGhJwE2KlpO8qJ3MuC7LrTjQwZxYv1SdFkLjPqE8nY6XOG.i','pending','2025-05-09 02:45:00'),(9,'David','Santos','Cruz','Male','1990-12-05',789,'Cedar Lane','Santa Barbara','Bacolor','Pampanga','david.cruz@gmail.com','09876543211','$2y$10$MvXLgKjHs6EqJ.rPfTm5YOXdVxcJ3JhK6aGY5qLjF.Xd4Y7ZvWNbm','pending','2025-05-09 03:15:00'),(10,'Kira','C','De Guzman','Female','2000-11-11',233,'Main St.','Villa','Pasig','Manila','rainepangilinan11@gmail.com','09347859823','$2y$10$/Jb2cHVOa4lCsm6PcRuCleeVI1sxBGpQTM/wv5xkBCwSEu2nKyzue','pending','2025-05-22 16:16:41'),(11,'Alex','P','Cruz','Male','2000-11-11',123,'Main St.','Villa','Pasig','Manila','profxmint@gmail.com','09347859823','$2y$10$I36VohNykC6eaH.oQlOxk.n7MuIIIMLexv.Wr46VdTukJ10ew8PN2','pending','2025-05-22 19:57:35'),(12,'Brent','Q','Santos','Male','2000-02-11',44,'Main St.','Camella','San Fernando','Pampanga','rainepangilinan11@gmail.com','09347859823','$2y$10$zunacWKblM2ZytyaGl75T.RjNnA3OzBGReaQ1POwe4ztVy0I8.Jri','pending','2025-05-23 08:14:50');
/*!40000 ALTER TABLE `membership_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pending_registrations`
--

DROP TABLE IF EXISTS `pending_registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pending_registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `birthdate` date NOT NULL,
  `house_no` varchar(10) NOT NULL,
  `street` varchar(100) NOT NULL,
  `barangay_or_village` varchar(100) NOT NULL,
  `municipality` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_no` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `otp_expiry` datetime NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pending_registrations`
--

LOCK TABLES `pending_registrations` WRITE;
/*!40000 ALTER TABLE `pending_registrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `pending_registrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `saving`
--

DROP TABLE IF EXISTS `saving`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `saving` (
  `SavingID` int(11) NOT NULL AUTO_INCREMENT,
  `MemberID` int(11) DEFAULT NULL,
  `Balance` decimal(10,0) NOT NULL,
  `Transaction_Type` varchar(255) NOT NULL,
  `Transaction_Date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Status` varchar(100) NOT NULL,
  PRIMARY KEY (`SavingID`),
  UNIQUE KEY `MemberID` (`MemberID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saving`
--

LOCK TABLES `saving` WRITE;
/*!40000 ALTER TABLE `saving` DISABLE KEYS */;
/*!40000 ALTER TABLE `saving` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_limits`
--

DROP TABLE IF EXISTS `transaction_limits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_limits` (
  `limit_id` int(11) NOT NULL AUTO_INCREMENT,
  `AccountID` int(11) DEFAULT NULL,
  `account_type_id` int(11) DEFAULT NULL,
  `transaction_type` enum('Deposit','Withdrawal','Transfer') NOT NULL,
  `daily_limit` decimal(15,2) DEFAULT NULL,
  `min_transaction_amount` decimal(15,2) DEFAULT NULL,
  `max_transaction_amount` decimal(15,2) DEFAULT NULL,
  `daily_count_limit` int(11) DEFAULT NULL,
  PRIMARY KEY (`limit_id`),
  KEY `AccountID` (`AccountID`),
  KEY `account_type_id` (`account_type_id`),
  CONSTRAINT `transaction_limits_ibfk_1` FOREIGN KEY (`AccountID`) REFERENCES `account` (`AccountID`) ON DELETE CASCADE,
  CONSTRAINT `transaction_limits_ibfk_2` FOREIGN KEY (`account_type_id`) REFERENCES `account_types` (`type_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_limits`
--

LOCK TABLES `transaction_limits` WRITE;
/*!40000 ALTER TABLE `transaction_limits` DISABLE KEYS */;
INSERT INTO `transaction_limits` VALUES (1,NULL,1,'Withdrawal',10000.00,100.00,5000.00,3),(2,NULL,1,'Transfer',20000.00,100.00,10000.00,5),(3,NULL,2,'Withdrawal',0.00,0.00,0.00,0);
/*!40000 ALTER TABLE `transaction_limits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `AccountID` int(11) NOT NULL,
  `MemberID` int(11) NOT NULL,
  `transaction_type` enum('Deposit','Withdrawal','Interest','Fee','Transfer','Adjustment') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `penalty_amount` decimal(15,2) DEFAULT 0.00,
  `balance_after` decimal(15,2) NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` varchar(255) DEFAULT NULL,
  `reference_number` varchar(50) DEFAULT NULL,
  `status` enum('Pending','Completed','Failed','Canceled') DEFAULT 'Completed',
  PRIMARY KEY (`transaction_id`),
  KEY `fk_transaction_account` (`AccountID`),
  KEY `fk_transaction_member` (`MemberID`),
  CONSTRAINT `fk_transaction_account` FOREIGN KEY (`AccountID`) REFERENCES `account` (`AccountID`) ON DELETE CASCADE,
  CONSTRAINT `fk_transaction_member` FOREIGN KEY (`MemberID`) REFERENCES `member` (`MemberID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (1,1,1,'Deposit',5000.00,0.00,30000.00,'2025-05-01 02:15:30','Initial deposit','DEP-20250501-001','Completed'),(2,1,1,'Withdrawal',2000.00,0.00,28000.00,'2025-05-03 06:23:45','ATM withdrawal','WDR-20250503-001','Completed'),(3,2,2,'Deposit',10000.00,0.00,25000.00,'2025-05-02 01:45:20','Salary deposit','DEP-20250502-001','Completed'),(4,2,2,'Withdrawal',5000.00,0.00,20000.00,'2025-05-05 08:30:15','Check withdrawal','WDR-20250505-001','Completed'),(5,3,3,'Deposit',2500.00,0.00,10000.00,'2025-05-04 03:20:30','Savings deposit','DEP-20250504-001','Completed'),(6,4,4,'Deposit',50000.00,0.00,50000.00,'2025-05-01 05:45:10','Time deposit opening','DEP-20250501-002','Completed'),(7,5,5,'Deposit',5000.00,0.00,35000.00,'2025-05-03 02:05:25','Emergency fund addition','DEP-20250503-002','Completed'),(8,6,6,'Deposit',2500.00,0.00,15000.00,'2025-05-02 07:30:45','Regular savings','DEP-20250502-002','Completed'),(9,6,6,'Withdrawal',1000.00,0.00,14000.00,'2025-05-04 01:15:20','ATM withdrawal','WDR-20250504-001','Completed'),(10,7,7,'Deposit',3000.00,0.00,11000.00,'2025-05-05 06:25:30','Check deposit','DEP-20250505-001','Completed'),(11,8,8,'Deposit',100000.00,0.00,100000.00,'2025-05-01 03:30:15','Time deposit opening','DEP-20250501-003','Completed'),(12,9,9,'Deposit',1000.00,0.00,6000.00,'2025-05-03 08:45:30','Regular savings','DEP-20250503-003','Completed'),(13,10,10,'Deposit',5000.00,0.00,25000.00,'2025-05-04 05:20:10','Emergency fund addition','DEP-20250504-002','Completed'),(14,1,1,'Deposit',50000.00,0.00,75000.00,'2025-05-17 14:43:28','Member deposit','DEP-20250517-001','Completed'),(15,15,1,'Deposit',100000.00,0.00,100000.00,'2025-05-22 19:44:19','Initial deposit','DEP-20250522-015','Completed'),(16,1,1,'Deposit',25000.00,0.00,100000.00,'2025-05-22 19:51:56','Member deposit','DEP-20250522-001','Completed'),(17,15,1,'Deposit',100000.00,0.00,200000.00,'2025-05-22 20:02:33','Deposit to account','DEP1747944153111','Completed');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-29  9:05:30

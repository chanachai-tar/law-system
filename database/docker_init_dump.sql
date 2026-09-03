-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: law_system_db
-- ------------------------------------------------------
-- Server version	8.0.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `api_keys`
--

DROP TABLE IF EXISTS `api_keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `api_keys` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` json DEFAULT NULL,
  `webhook_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `webhook_events` json DEFAULT NULL,
  `ip_whitelist` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_keys_key_unique` (`key`),
  KEY `api_keys_created_by_foreign` (`created_by`),
  CONSTRAINT `api_keys_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `api_keys`
--

LOCK TABLES `api_keys` WRITE;
/*!40000 ALTER TABLE `api_keys` DISABLE KEYS */;
/*!40000 ALTER TABLE `api_keys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `appointment_orders`
--

DROP TABLE IF EXISTS `appointment_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appointment_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_date` date NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointment_orders_order_number_index` (`order_number`),
  KEY `appointment_orders_order_date_index` (`order_date`),
  KEY `appointment_orders_status_index` (`status`),
  KEY `appointment_orders_created_at_index` (`created_at`),
  KEY `appointment_orders_num_date_index` (`order_number`,`order_date`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointment_orders`
--

LOCK TABLES `appointment_orders` WRITE;
/*!40000 ALTER TABLE `appointment_orders` DISABLE KEYS */;
INSERT INTO `appointment_orders` VALUES (2,'12/2569','2026-02-13','ขอส่งคำสั่งแต่งตั้งคณะกรรมการขับเคลื่อนการดำเนินงานป้องกันควบคุมโรคและภัยสุขภาพด้วยกลไกการพัฒนาคุณภาพชีวิตระดับอำเภอ (พชอ) และระบบสุขภาพปฐมภูมิ และขับเคลื่อนภารกิจ ด้านการป้องกันควบคุมโรคและภัยสุขภาพสู่รพ.สต.ในสังกัดองค์กรปกครองส่วนท้องถิ่นๆ','กลุ่มพัฒนานวัตกรรมและวิจัย','active','orders/1770964999_sample.pdf','2026-02-12 23:43:20','2026-02-12 23:43:20'),(3,'12/2569','2026-02-16','ขอส่งคำสั่งแต่งตั้งคณะกรรมการขับเคลื่อนการดำเนินงานป้องกันควบคุมโรคและภัยสุขภาพด้วยกลไกการพัฒนาคุณภาพชีวิตระดับอำเภอ (พชอ) และระบบสุขภาพปฐมภูมิ และขับเคลื่อนภารกิจ ด้านการป้องกันควบคุมโรคและภัยสุขภาพสู่รพ.สต.ในสังกัดองค์กรปกครองส่วนท้องถิ่นๆ','กลุ่มยุทธศาสตร์และแผนงาน','pending','orders/1771230076_sample.pdf','2026-02-16 01:21:16','2026-02-16 01:21:16');
/*!40000 ALTER TABLE `appointment_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('odpc10-lss-cache-5c785c036466adea360111aa28563bfd556b5fba','i:3;',1788407332),('odpc10-lss-cache-5c785c036466adea360111aa28563bfd556b5fba:timer','i:1788407332;',1788407332);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `case_files`
--

DROP TABLE IF EXISTS `case_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `case_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `case_step_id` bigint unsigned NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `case_files_case_step_id_foreign` (`case_step_id`),
  CONSTRAINT `case_files_case_step_id_foreign` FOREIGN KEY (`case_step_id`) REFERENCES `case_steps` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `case_files`
--

LOCK TABLES `case_files` WRITE;
/*!40000 ALTER TABLE `case_files` DISABLE KEYS */;
INSERT INTO `case_files` VALUES (20,36,'cases/8/step_1/y6ncX2vdzUHAOZFk11ZDWbuW6mof2xgsGrsS4on0.pdf','sample.pdf','2026-02-15 21:26:58','2026-02-15 21:26:58'),(21,37,'cases/8/step_2/uZ8PixsgFoOHF3stkHas2qAYPRBXwWT0IBB8mpEk.pdf','sample.pdf','2026-02-15 21:27:13','2026-02-15 21:27:13'),(22,39,'cases/10/step_2/eBYV76oN0Ok2Yohru6zzr85WZJ7UUL8dIr3seBZT.pdf','Laravel.pdf','2026-03-05 01:44:16','2026-03-05 01:44:16'),(23,41,'cases/10/step_4/0uOUhxEaZX2BEOCuxhlG5BPRrYU7h3aQI8HZIk9q.pdf','1771230076_sample (2).pdf','2026-08-24 19:25:25','2026-08-24 19:25:25'),(24,42,'cases/10/step_5/FV7eG0YDg8uy0KG7mH9ZGHjFgQyztAbNtRBAXdZk.pdf','1771230076_sample (1).pdf','2026-08-24 19:25:39','2026-08-24 19:25:39'),(25,43,'cases/10/step_6/dAx2BwWKYMnP5MW9MH4NWK9OsqE4iKLr8rpTqHNo.pdf','1771230076_sample (1).pdf','2026-08-24 19:31:22','2026-08-24 19:31:22'),(26,44,'cases/12/step_1/zK8ZLGp0qETYnbvBMPqkHMcn0QLRx4osAQiwC5pi.pdf','1771230076_sample (1).pdf','2026-08-25 00:18:55','2026-08-25 00:18:55'),(27,45,'cases/12/step_2/5anFpCLsrxfAwm8QNa6mi2FYcQpiTiB2NVaP3pn7.pdf','1771230076_sample (2).pdf','2026-08-25 00:22:53','2026-08-25 00:22:53'),(28,46,'cases/9/step_1/IKznGvNB2WN09wsC5ptlKbGWalVKXqERlKUSZIuB.pdf','1771230076_sample (2).pdf','2026-08-25 02:15:20','2026-08-25 02:15:20'),(29,47,'cases/10/step_7/6Tyy8ivRjhqxR7nm0A1CtobOy7KbCwIK2oy8didJ.pdf','ODPC10-LSS.pdf','2026-08-25 02:18:35','2026-08-25 02:18:35');
/*!40000 ALTER TABLE `case_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `case_steps`
--

DROP TABLE IF EXISTS `case_steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `case_steps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `legal_case_id` bigint unsigned NOT NULL,
  `user_id` int DEFAULT NULL,
  `step_num` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `case_steps_case_step_num_index` (`legal_case_id`,`step_num`),
  CONSTRAINT `case_steps_legal_case_id_foreign` FOREIGN KEY (`legal_case_id`) REFERENCES `legal_cases` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `case_steps`
--

LOCK TABLES `case_steps` WRITE;
/*!40000 ALTER TABLE `case_steps` DISABLE KEYS */;
INSERT INTO `case_steps` VALUES (32,21,7,1,'sdfsdfsdf','2026-02-04 02:11:51','2026-02-04 02:11:51'),(33,21,7,2,'sdfsdfsdf','2026-02-04 02:11:51','2026-02-04 02:11:51'),(34,22,7,1,'45454','2026-02-04 02:41:55','2026-02-04 02:41:55'),(36,8,7,1,'ดำเนินการรอบที่2','2026-02-15 21:26:57','2026-02-15 21:26:57'),(37,8,7,2,'ดำเนินการรอบที่3','2026-02-15 21:27:13','2026-02-15 21:27:13'),(38,10,1,1,'ทดสอบ','2026-03-05 01:43:44','2026-03-05 01:43:44'),(39,10,1,2,'ทดสอบ2','2026-03-05 01:44:15','2026-03-05 01:44:15'),(40,10,7,3,'123456','2026-08-24 18:42:47','2026-08-24 18:42:47'),(41,10,7,4,'ทดสอบ','2026-08-24 19:25:23','2026-08-24 19:25:23'),(42,10,7,5,'ทดสอบ2','2026-08-24 19:25:39','2026-08-24 19:25:39'),(43,10,7,6,'ดเ้ดเ้ดเ้','2026-08-24 19:31:22','2026-08-24 19:31:22'),(44,12,1,1,'ทดสอบแจ้งเตือน','2026-08-25 00:18:55','2026-08-25 00:18:55'),(45,12,1,2,'ทดสอบ2','2026-08-25 00:22:52','2026-08-25 00:22:52'),(46,9,7,1,'ทดสอบ','2026-08-25 02:15:18','2026-08-25 02:15:18'),(47,10,7,7,'หกดหกด','2026-08-25 02:18:34','2026-08-25 02:18:34'),(48,10,1,8,'หกดหกด','2026-08-25 02:21:15','2026-08-25 02:21:15'),(49,12,1,3,'ดเ้ดเ้ดเ้','2026-09-02 20:50:00','2026-09-02 20:50:00');
/*!40000 ALTER TABLE `case_steps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cases`
--

DROP TABLE IF EXISTS `cases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `law_type` int NOT NULL,
  `running_no` int NOT NULL,
  `case_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'เรื่อง/หัวข้อเคส',
  `to` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'เรียน',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'รายละเอียด',
  `incident_date` date DEFAULT NULL COMMENT 'วันที่เกิดเหตุ',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cases_case_number_unique` (`case_number`),
  KEY `cases_law_type_created_at_index` (`law_type`,`created_at`),
  KEY `cases_law_type_index` (`law_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cases`
--

LOCK TABLES `cases` WRITE;
/*!40000 ALTER TABLE `cases` DISABLE KEYS */;
/*!40000 ALTER TABLE `cases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dashboard_summaries`
--

DROP TABLE IF EXISTS `dashboard_summaries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dashboard_summaries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `summary_date` date NOT NULL,
  `all_count` int NOT NULL DEFAULT '0',
  `pending_count` int NOT NULL DEFAULT '0',
  `completed_count` int NOT NULL DEFAULT '0',
  `orders_count` int NOT NULL DEFAULT '0',
  `urgent_count` int NOT NULL DEFAULT '0',
  `overdue_count` int NOT NULL DEFAULT '0',
  `type_counts` json DEFAULT NULL,
  `all_files_count` int NOT NULL DEFAULT '0',
  `ts_files_count` int NOT NULL DEFAULT '0',
  `sl_files_count` int NOT NULL DEFAULT '0',
  `sw_files_count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dashboard_summaries_summary_date_unique` (`summary_date`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dashboard_summaries`
--

LOCK TABLES `dashboard_summaries` WRITE;
/*!40000 ALTER TABLE `dashboard_summaries` DISABLE KEYS */;
INSERT INTO `dashboard_summaries` VALUES (1,'2026-08-27',5,3,2,2,0,0,'[{\"total\": 4, \"law_type\": \"ตรวจสอบข้อเท็จจริง (ตส.)\", \"law_type_id\": 1}, {\"total\": 1, \"law_type\": \"ความรับผิดทางละเมิด (สล.)\", \"law_type_id\": 2}, {\"total\": 0, \"law_type\": \"สอบสวนวินัย (สว.)\", \"law_type_id\": 3}]',10,5,5,0,'2026-08-26 19:19:35','2026-08-26 19:25:38'),(2,'2026-09-02',5,3,2,2,0,0,'[{\"total\": 4, \"law_type\": \"ตรวจสอบข้อเท็จจริง (ตส.)\", \"law_type_id\": 1}, {\"total\": 1, \"law_type\": \"ความรับผิดทางละเมิด (สล.)\", \"law_type_id\": 2}, {\"total\": 0, \"law_type\": \"สอบสวนวินัย (สว.)\", \"law_type_id\": 3}]',10,5,5,0,'2026-09-01 21:05:38','2026-09-01 21:05:38'),(3,'2026-09-03',5,3,2,2,0,0,'[{\"total\": 4, \"law_type\": \"ตรวจสอบข้อเท็จจริง (ตส.)\", \"law_type_id\": 1}, {\"total\": 1, \"law_type\": \"ความรับผิดทางละเมิด (สล.)\", \"law_type_id\": 2}, {\"total\": 0, \"law_type\": \"สอบสวนวินัย (สว.)\", \"law_type_id\": 3}]',10,5,5,0,'2026-09-02 19:53:30','2026-09-02 19:53:30');
/*!40000 ALTER TABLE `dashboard_summaries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (1,'default','{\"uuid\":\"3190168f-1295-4e44-999d-b88088a0d980\",\"displayName\":\"App\\\\Events\\\\CaseStepAdded\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:24:\\\"App\\\\Events\\\\CaseStepAdded\\\":4:{s:4:\\\"case\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\LegalCase\\\";s:2:\\\"id\\\";i:12;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"step\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:19:\\\"App\\\\Models\\\\CaseStep\\\";s:2:\\\"id\\\";i:44;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"message\\\";s:163:\\\"อัปเดตความคืบหน้า ครั้งที่ 1 สำนวน สธ0427.1.1\\/ ตส 4 โดย ผู้ดูแลระบบ\\\";s:4:\\\"user\\\";s:33:\\\"ผู้ดูแลระบบ\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1787642336,\"delay\":null}',0,NULL,1787642336,1787642336),(2,'default','{\"uuid\":\"0f799804-84d2-4da4-833d-cbfea29fa322\",\"displayName\":\"App\\\\Events\\\\CaseStepAdded\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:24:\\\"App\\\\Events\\\\CaseStepAdded\\\":4:{s:4:\\\"case\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\LegalCase\\\";s:2:\\\"id\\\";i:12;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"step\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:19:\\\"App\\\\Models\\\\CaseStep\\\";s:2:\\\"id\\\";i:45;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"message\\\";s:163:\\\"อัปเดตความคืบหน้า ครั้งที่ 2 สำนวน สธ0427.1.1\\/ ตส 4 โดย ผู้ดูแลระบบ\\\";s:4:\\\"user\\\";s:33:\\\"ผู้ดูแลระบบ\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1787642573,\"delay\":null}',0,NULL,1787642573,1787642573),(3,'default','{\"uuid\":\"1760019a-10dc-490b-9406-32cd28be7acb\",\"displayName\":\"App\\\\Events\\\\CaseStepAdded\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:24:\\\"App\\\\Events\\\\CaseStepAdded\\\":4:{s:4:\\\"case\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\LegalCase\\\";s:2:\\\"id\\\";i:9;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"step\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:19:\\\"App\\\\Models\\\\CaseStep\\\";s:2:\\\"id\\\";i:46;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"message\\\";s:149:\\\"อัปเดตความคืบหน้า ครั้งที่ 1 สำนวน สธ0427.1.1\\/ ตส 2 โดย นิติกร1\\\";s:4:\\\"user\\\";s:19:\\\"นิติกร1\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1787649324,\"delay\":null}',0,NULL,1787649324,1787649324),(4,'default','{\"uuid\":\"27b798ce-9cfb-4a02-b16f-6017d683e527\",\"displayName\":\"App\\\\Events\\\\CaseStepAdded\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:24:\\\"App\\\\Events\\\\CaseStepAdded\\\":4:{s:4:\\\"case\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\LegalCase\\\";s:2:\\\"id\\\";i:10;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"step\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:19:\\\"App\\\\Models\\\\CaseStep\\\";s:2:\\\"id\\\";i:47;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"message\\\";s:149:\\\"อัปเดตความคืบหน้า ครั้งที่ 7 สำนวน สธ0427.1.1\\/ สล 1 โดย นิติกร1\\\";s:4:\\\"user\\\";s:19:\\\"นิติกร1\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1787649515,\"delay\":null}',0,NULL,1787649515,1787649515),(5,'default','{\"uuid\":\"dc067b2d-e398-49e2-91d6-ebfdec549886\",\"displayName\":\"App\\\\Events\\\\CaseStepAdded\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:24:\\\"App\\\\Events\\\\CaseStepAdded\\\":4:{s:4:\\\"case\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\LegalCase\\\";s:2:\\\"id\\\";i:10;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"step\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:19:\\\"App\\\\Models\\\\CaseStep\\\";s:2:\\\"id\\\";i:48;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"message\\\";s:163:\\\"อัปเดตความคืบหน้า ครั้งที่ 8 สำนวน สธ0427.1.1\\/ สล 1 โดย ผู้ดูแลระบบ\\\";s:4:\\\"user\\\";s:33:\\\"ผู้ดูแลระบบ\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1787649675,\"delay\":null}',0,NULL,1787649675,1787649675),(6,'default','{\"uuid\":\"340d3670-4923-4931-afa7-853707be204b\",\"displayName\":\"App\\\\Events\\\\CaseStepAdded\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:24:\\\"App\\\\Events\\\\CaseStepAdded\\\":4:{s:4:\\\"case\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\LegalCase\\\";s:2:\\\"id\\\";i:12;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"step\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:19:\\\"App\\\\Models\\\\CaseStep\\\";s:2:\\\"id\\\";i:49;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"message\\\";s:163:\\\"อัปเดตความคืบหน้า ครั้งที่ 3 สำนวน สธ0427.1.1\\/ ตส 4 โดย ผู้ดูแลระบบ\\\";s:4:\\\"user\\\";s:33:\\\"ผู้ดูแลระบบ\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1788407401,\"delay\":null}',0,NULL,1788407401,1788407401),(7,'default','{\"uuid\":\"99525a37-007a-414b-9f0e-fe7657e5744b\",\"displayName\":\"App\\\\Events\\\\CaseClosed\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:21:\\\"App\\\\Events\\\\CaseClosed\\\":2:{s:4:\\\"case\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\LegalCase\\\";s:2:\\\"id\\\";i:12;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"message\\\";s:89:\\\"ปิดสำนวนเรียบร้อยแล้ว: สธ0427.1.1\\/ ตส 4\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1788407443,\"delay\":null}',0,NULL,1788407443,1788407443);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `legal_cases`
--

DROP TABLE IF EXISTS `legal_cases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `legal_cases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `law_type` int NOT NULL,
  `running_no` int NOT NULL,
  `case_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `incident_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `outcome_summary` text COLLATE utf8mb4_unicode_ci,
  `penalty_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `damage_amount` decimal(12,2) DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `legal_cases_case_number_unique` (`case_number`),
  KEY `legal_cases_user_id_foreign` (`user_id`),
  KEY `legal_cases_law_type_index` (`law_type`),
  KEY `legal_cases_status_index` (`status`),
  KEY `legal_cases_incident_date_index` (`incident_date`),
  KEY `legal_cases_law_type_status_index` (`law_type`,`status`),
  KEY `legal_cases_created_at_index` (`created_at`),
  CONSTRAINT `legal_cases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `legal_cases`
--

LOCK TABLES `legal_cases` WRITE;
/*!40000 ALTER TABLE `legal_cases` DISABLE KEYS */;
INSERT INTO `legal_cases` VALUES (8,1,1,'สธ0427.1.1/ ตส 1','ขออนุมัติส่งใบสำคัญเพื่อเบิกค่าใช้จ่ายเพื่อเข้าร่วมประชุมฯ และชดใช้เงินยืม','ผู้อำนวยการสำนักงานป้องกันควบคุมโรคที่ 10 จังหวัดอุบลราชธานี','-','2026-02-16',NULL,'completed',NULL,NULL,NULL,7,'2026-02-15 21:02:33','2026-03-05 01:10:33'),(9,1,2,'สธ0427.1.1/ ตส 2','ขอส่งคำสั่งแต่งตั้งคณะกรรมการขับเคลื่อนการดำเนินงานป้องกันควบคุมโรคและภัยสุขภาพด้วยกลไกการพัฒนาคุณภาพชีวิตระดับอำเภอ (พชอ) และระบบสุขภาพปฐมภูมิ และขับเคลื่อนภารกิจ ด้านการป้องกันควบคุมโรคและภัยสุขภาพสู่รพ.สต.ในสังกัดองค์กรปกครองส่วนท้องถิ่นๆ','ผู้อำนวยการสำนักงานป้องกันควบคุมโรคที่ 10 จังหวัดอุบลราชธานี','ขอส่งคำสั่งแต่งตั้งคณะกรรมการขับเคลื่อนการดำเนินงานป้องกันควบคุมโรคและภัยสุขภาพด้วยกลไกการพัฒนาคุณภาพชีวิตระดับอำเภอ (พชอ) และระบบสุขภาพปฐมภูมิ และขับเคลื่อนภารกิจ ด้านการป้องกันควบคุมโรคและภัยสุขภาพสู่รพ.สต.ในสังกัดองค์กรปกครองส่วนท้องถิ่นๆขอส่งคำสั่งแต่งตั้งคณะกรรมการขับเคลื่อนการดำเนินงานป้องกันควบคุมโรคและภัยสุขภาพด้วยกลไกการพัฒนาคุณภาพชีวิตระดับอำเภอ (พชอ) และระบบสุขภาพปฐมภูมิ และขับเคลื่อนภารกิจ ด้านการป้องกันควบคุมโรคและภัยสุขภาพสู่รพ.สต.ในสังกัดองค์กรปกครองส่วนท้องถิ่นๆ','2026-02-17',NULL,'pending',NULL,NULL,NULL,7,'2026-02-16 00:03:42','2026-02-16 00:03:42'),(10,2,1,'สธ0427.1.1/ สล 1','ขออนุมัติส่งใบสำคัญเพื่อเบิกค่าใช้จ่ายเพื่อเข้าร่วมประชุมฯ และชดใช้เงินยืม','ผู้อำนวยการสำนักงานป้องกันควบคุมโรคที่ 10 จังหวัดอุบลราชธานี','ทดสอบ','2026-02-17',NULL,'pending',NULL,NULL,NULL,7,'2026-02-16 01:17:53','2026-02-16 01:17:53'),(11,1,3,'สธ0427.1.1/ ตส 3','ขอส่งคำสั่งแต่งตั้งคณะกรรมการขับเคลื่อนการดำเนินงานป้องกันควบคุมโรคและภัยสุขภาพด้วยกลไกการพัฒนาคุณภาพชีวิตระดับอำเภอ (พชอ) และระบบสุขภาพปฐมภูมิ และขับเคลื่อนภารกิจ ด้านการป้องกันควบคุมโรคและภัยสุขภาพสู่รพ.สต.ในสังกัดองค์กรปกครองส่วนท้องถิ่นๆ','ผู้อำนวยการสำนักงานป้องกันควบคุมโรคที่ 10 จังหวัดอุบลราชธานี','123456789','2026-03-04',NULL,'completed',NULL,NULL,NULL,7,'2026-03-03 20:52:56','2026-03-05 01:19:42'),(12,1,4,'สธ0427.1.1/ ตส 4','ขอส่งคำสั่งแต่งตั้งคณะกรรมการขับเคลื่อนการดำเนินงานป้องกันควบคุมโรคและภัยสุขภาพด้วยกลไกการพัฒนาคุณภาพชีวิตระดับอำเภอ (พชอ) และระบบสุขภาพปฐมภูมิ และขับเคลื่อนภารกิจ ด้านการป้องกันควบคุมโรคและภัยสุขภาพสู่รพ.สต.ในสังกัดองค์กรปกครองส่วนท้องถิ่นๆ','ผู้อำนวยการสำนักงานป้องกันควบคุมโรคที่ 10 จังหวัดอุบลราชธานี','หกดหกดหกด','2026-08-25',NULL,'completed','สรุป','ยุติเรื่อง',20000.00,7,'2026-08-24 19:34:07','2026-09-02 20:50:43');
/*!40000 ALTER TABLE `legal_cases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_02_03_022314_create_law_system_tables',1),(5,'2026_02_03_035840_add_user_id_to_legal_cases',2),(6,'2026_02_03_063615_add_role_to_users_table',3),(7,'2026_02_04_092036_create_appointment_orders_table',4),(8,'2026_02_05_013925_create_cases_table',5),(9,'2026_02_03_022763_create_legal_cases_table',6),(10,'2026_02_03_022800_create_case_files_table',7),(11,'2026_02_03_022800_create_case_steps_table',7),(12,'2026_02_03_062954_add_status_to_users_table',7),(13,'2026_02_24_000001_add_performance_indexes',7),(14,'2026_02_25_000001_add_due_date_and_outcome_to_legal_cases',8),(15,'2026_02_25_000002_create_regulations_table',8),(16,'2026_08_25_031511_create_api_keys_table',9),(17,'2026_08_25_140000_create_system_settings_table',10),(18,'2026_08_27_021716_create_dashboard_summaries_table',11),(19,'2026_08_27_022435_add_file_counts_to_dashboard_summaries_table',12),(20,'2026_09_02_045100_add_google2fa_secret_to_users_table',13),(21,'2026_09_02_061351_add_two_factor_secret_to_users_table',14),(22,'2026_09_03_025031_change_two_factor_secret_column_type_in_users_table',15),(23,'2026_09_03_091800_add_unique_username_constraint_to_users_table',15);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `regulations`
--

DROP TABLE IF EXISTS `regulations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `regulations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'regulation',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `regulations_user_id_foreign` (`user_id`),
  KEY `regulations_category_index` (`category`),
  CONSTRAINT `regulations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `regulations`
--

LOCK TABLES `regulations` WRITE;
/*!40000 ALTER TABLE `regulations` DISABLE KEYS */;
INSERT INTO `regulations` VALUES (1,'ทดสอบ','regulation','regulations/1787649252_1771230076_sample (2).pdf','1771230076_sample (2).pdf','18.4 KB','ทดสอบ',7,'2026-08-25 02:14:14','2026-08-25 02:14:14');
/*!40000 ALTER TABLE `regulations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('GWXgKn6KDLLrOIPog2h7HaNN8ToAqQyXdugaQyc2',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiREhzSHV2ZTJwZU45N2xHalNVVGxtSUNmQnM2QTFlQmR6UnBLVEQ4biI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1788403338),('lTU16830Qj05VRJTsvMNHLXmx7g9NmOrU792ODAk',NULL,'127.0.0.1','PostmanRuntime/2.5.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiR3kwVjlmd2dlT25wVWRDUU11RzgzN3hFV2pHRmd0WHIyWFdLVm5DciI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1788407338),('Niv2HzHVNYx9n0SMthrJWbvYKnKes4NrcHyH1VqM',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRTJsM3E0M2ZJcWN5NVZYQXd0Ukx3emg4MGRIM1NtWGVwQ3pIQ2dQayI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=',1788408292),('QqkwdUMNxQMnBLIHBNkcnG0e13GeRWHsfxlDN9Fy',NULL,'127.0.0.1','PostmanRuntime/1.9.12','YTo0OntzOjY6Il90b2tlbiI7czo0MDoibWNqcVF2RXlib3pPSHJ3bDMzTTNXb0liNXQ1NzB4NWJwNHZKY2RjUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hdXRoLzJmYS9zZXR1cC9hZG1pbiI7fX0=',1788406376);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_settings`
--

DROP TABLE IF EXISTS `system_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `system_settings_key_unique` (`key`),
  KEY `system_settings_group_index` (`group`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_settings`
--

LOCK TABLES `system_settings` WRITE;
/*!40000 ALTER TABLE `system_settings` DISABLE KEYS */;
INSERT INTO `system_settings` VALUES (1,'telegram_bot_token','8615238459:AAGUL_N8BykMgVW_bCMGR98b8Yt8nZUAhwY','general','2026-08-25 00:12:57','2026-08-25 02:20:17'),(2,'telegram_chat_id','-5352074763','general','2026-08-25 00:12:57','2026-08-25 02:20:57'),(3,'telegram_group_name','ODPC10-LSS','general','2026-08-25 00:12:57','2026-08-25 00:12:57'),(4,'telegram_notify_case_created','1','general','2026-08-25 00:12:57','2026-08-25 00:12:57'),(5,'telegram_notify_step_added','1','general','2026-08-25 00:12:57','2026-08-25 00:12:57'),(6,'telegram_notify_case_closed','1','general','2026-08-25 00:12:57','2026-08-25 00:12:57');
/*!40000 ALTER TABLE `system_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `google2fa_secret` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('staff','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staff',
  `is_active` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `users_role_index` (`role`),
  KEY `users_is_active_index` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'ผู้ดูแลระบบ','admin','chanachai.saisombut@gmail.com',NULL,'$2y$12$9ePTTeajOhBufbMjCAyVuOlu9eezWIrXAehLNEJILGgck41VrLx1C','eyJpdiI6IkNNNGNPYlBLZytNcUlvdWdSNEI3R1E9PSIsInZhbHVlIjoiTlQrN2JiZ1VUTkk1VmhqNXZFY2RsNStQTktQUzJXZStjL256SXZ3THR3Wjh0Rnc0NFU1UWIxL1AwUmgrd08zViIsIm1hYyI6IjBmMzljNmY0YTk2NDgyODExMWY2YzZlZmY2N2MwNmI5MmJhMzI5OTg4OTIxMWFhYmU1MzJiNDc1YmJlMmQxYzIiLCJ0YWciOiIifQ==',NULL,NULL,'2026-02-02 21:09:05','2026-09-02 20:27:45','admin',1),(7,'นิติกร1','law01','law01@odpc10.local',NULL,'$2y$12$p7opsd.PkVdmVdECsMFE9.F3qvu9Y//Z9YgO3E1UIIzGPX9pJh15e',NULL,NULL,NULL,'2026-02-03 00:16:38','2026-09-02 00:41:43','staff',1),(8,'นิติกร2','law02','law02@odpc10.local',NULL,'$2y$12$J2.yRF3N0fevwnnrbz2oCegVqIYOufGy5cQ55G2sS4olX.2A8tEda',NULL,NULL,NULL,'2026-02-03 00:34:31','2026-09-01 23:33:40','staff',1),(9,'นิติกร3','law03','law03@odpc10.local',NULL,'$2y$12$ktpt5suIC5gJXCUkYeHG.ukqlGEfJTkBsuu5D6YDNB8chpwe63sc.',NULL,NULL,NULL,'2026-02-03 00:34:46','2026-09-01 21:09:15','staff',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-03 11:10:23

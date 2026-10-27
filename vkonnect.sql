-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 27, 2026 at 03:13 PM
-- Server version: 10.11.19-MariaDB-cll-lve
-- PHP Version: 8.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vkonnect`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `id` char(36) NOT NULL,
  `message` varchar(200) NOT NULL,
  `level` varchar(16) NOT NULL DEFAULT 'info',
  `link` varchar(255) DEFAULT NULL,
  `audience` varchar(16) NOT NULL DEFAULT 'all',
  `user_id` char(36) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `alerts`
--

INSERT INTO `alerts` (`id`, `message`, `level`, `link`, `audience`, `user_id`, `is_active`, `starts_at`, `ends_at`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('01a096c5-194f-7155-b9bf-e38264057fd0', 'PAN was added to your vault.', 'info', '/alumni/documents', 'alumni', '01a0963f-fb96-72f9-b366-52fd81d9d235', 1, NULL, '2026-09-18 00:58:14', NULL, '2026-09-13 00:58:14', '2026-09-13 00:58:14', NULL),
('01a09fe8-3ff4-739f-b7ec-1371d3865073', 'New job opportunity: SAP ABAP DEVELOPER at VKollab Technologies', 'success', '/alumni/jobs', 'alumni', NULL, 1, NULL, '2026-09-21 19:33:13', '01a09277-60bd-71b1-9fad-a03afb7db784', '2026-09-14 19:33:13', '2026-09-14 19:33:13', NULL),
('01a0a0d9-a356-7141-adf9-f4bbc4f9a561', 'Microsoft copilot session', 'info', NULL, 'alumni', NULL, 1, NULL, '2026-09-21 23:56:52', '01a09277-60bd-71b1-9fad-a03afb7db784', '2026-09-14 23:56:52', '2026-09-14 23:56:52', NULL),
('01a0a100-a4fc-72e6-8de6-19810d2ea543', 'Salary Slips — FY 2025-2026 was added to your vault.', 'info', '/alumni/documents', 'alumni', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 1, NULL, '2026-09-20 00:39:29', NULL, '2026-09-15 00:39:29', '2026-09-15 00:39:29', NULL),
('01a0a529-338f-71ad-a669-0e69e77d6151', 'New job opportunity: SAP CPI DEVELOPER at VKollab Technologies', 'success', '/alumni/jobs', 'alumni', NULL, 1, NULL, '2026-09-22 20:02:15', '01a09277-60bd-71b1-9fad-a03afb7db784', '2026-09-15 20:02:15', '2026-09-15 20:02:15', NULL),
('01a0a6fb-e769-7145-958b-f364e7f32f7f', 'New job opportunity: Supply chain management at VKollab Technologies', 'success', '/alumni/jobs', 'alumni', NULL, 1, NULL, '2026-09-23 04:32:01', '01a0a6c2-2b02-7173-8043-3439b8d31b03', '2026-09-16 04:32:01', '2026-09-16 04:32:01', NULL),
('01a0a6ff-48c0-7271-9451-8c59cd739759', 'Microsoft copilot', 'info', NULL, 'all', NULL, 1, NULL, '2026-09-23 04:35:43', '01a0a6c2-2b02-7173-8043-3439b8d31b03', '2026-09-16 04:35:43', '2026-09-16 04:35:43', NULL),
('01a0aa1c-0220-735f-be68-4744e487c00f', 'Salary Slips — FY 2025-2026 was added to your vault.', 'info', '/alumni/documents', 'alumni', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 1, NULL, '2026-09-21 19:05:57', NULL, '2026-09-16 19:05:57', '2026-09-16 19:05:57', NULL),
('01a0c519-e82f-73ad-b82b-c81a7f0fd176', 'PAN was updated in your vault.', 'info', '/alumni/documents', 'alumni', '01a0963f-fb96-72f9-b366-52fd81d9d235', 1, NULL, '2026-09-27 00:53:24', NULL, '2026-09-22 00:53:24', '2026-09-22 00:53:24', NULL),
('01a0c5d7-edda-701b-b1cc-22a00d8af0ec', 'Salary Slips — FY 2025-2026 was added to your vault.', 'info', '/alumni/documents', 'alumni', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 1, NULL, '2026-09-27 04:20:57', NULL, '2026-09-22 04:20:57', '2026-09-22 04:20:57', NULL),
('01a0ed53-11e6-72b3-a198-30f0d52393f1', 'Experience Letter was added to your vault.', 'info', '/alumni/documents', 'alumni', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 1, NULL, '2026-10-04 20:20:39', NULL, '2026-09-29 20:20:39', '2026-09-29 20:20:39', NULL),
('01a0ed53-d262-7160-a3d2-35d95976a9bb', 'Increment Letter was added to your vault.', 'info', '/alumni/documents', 'alumni', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 1, NULL, '2026-10-04 20:21:28', NULL, '2026-09-29 20:21:28', '2026-09-29 20:21:28', NULL),
('01a0ed54-d02c-735c-8fd5-bdfa072b6003', 'updated Full & Final Settlement was added to your vault.', 'info', '/alumni/documents', 'alumni', '01a0963f-fb96-72f9-b366-52fd81d9d235', 1, NULL, '2026-10-04 20:22:33', NULL, '2026-09-29 20:22:33', '2026-09-29 20:22:33', NULL),
('01a0ed7b-b721-717f-b07d-882ec3a309e5', 'Reliving letters was added to your vault.', 'info', '/alumni/documents', 'alumni', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 1, NULL, '2026-10-04 21:05:03', NULL, '2026-09-29 21:05:03', '2026-09-29 21:05:03', NULL),
('01a0ed7f-8718-71ad-b65e-14bcc9571af4', 'updated Full & Final Settlement was added to your vault.', 'info', '/alumni/documents', 'alumni', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 1, NULL, '2026-10-04 21:09:12', NULL, '2026-09-29 21:09:12', '2026-09-29 21:09:12', NULL),
('01a17836-9c47-7358-8183-7d7e712896ee', 'Salary Slips — FY 2026-2027 was added to your vault.', 'info', '/alumni/documents', 'alumni', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 1, NULL, '2026-10-31 19:36:47', NULL, '2026-10-26 19:36:47', '2026-10-26 19:36:47', NULL),
('01a1783a-5889-7004-8f22-105e71815c2b', 'New job opportunity: AI Developer at VKollab Technologies', 'success', '/alumni/jobs', 'alumni', NULL, 1, NULL, '2026-11-02 19:40:52', '01a0a6c2-2b02-7173-8043-3439b8d31b03', '2026-10-26 19:40:52', '2026-10-26 19:40:52', NULL),
('01a178f4-5ec8-72ef-9362-76b8d0dfbc35', 'Title: Q3 Alumni Meet, Audience: Everyone', 'info', NULL, 'all', NULL, 1, NULL, '2026-11-02 23:04:03', '01a0a6c2-2b02-7173-8043-3439b8d31b03', '2026-10-26 23:04:03', '2026-10-26 23:04:03', NULL),
('01a1797c-14f2-70d4-bc82-7fa77f3fd6d0', 'Salary Slips — FY 2026-2027 was added to your vault.', 'info', '/alumni/documents', 'alumni', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 1, NULL, '2026-11-01 01:32:17', NULL, '2026-10-27 01:32:17', '2026-10-27 01:32:17', NULL),
('01a1798b-e785-704f-8873-eb5e4017d639', 'New job opportunity: AI Developer Position at VKollab Technologies', 'success', '/alumni/jobs', 'alumni', NULL, 1, NULL, '2026-11-03 01:49:34', '01a0a6c2-2b02-7173-8043-3439b8d31b03', '2026-10-27 01:49:34', '2026-10-27 01:49:34', NULL),
('01a1799e-20ba-7293-8128-fd086a41f1b9', 'Experience Letter was added to your vault.', 'info', '/alumni/documents', 'alumni', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 1, NULL, '2026-11-01 02:09:28', NULL, '2026-10-27 02:09:28', '2026-10-27 02:09:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `alumni_import_batches`
--

CREATE TABLE `alumni_import_batches` (
  `id` char(36) NOT NULL,
  `uploaded_by` char(36) DEFAULT NULL,
  `uploaded_by_name` varchar(120) DEFAULT NULL,
  `original_filename` varchar(200) DEFAULT NULL,
  `mode` varchar(16) NOT NULL DEFAULT 'update',
  `status` varchar(16) NOT NULL DEFAULT 'processing',
  `total_rows` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `updated_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `skipped_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `failed_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `failed_rows` longtext DEFAULT NULL,
  `error` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `alumni_import_batches`
--

INSERT INTO `alumni_import_batches` (`id`, `uploaded_by`, `uploaded_by_name`, `original_filename`, `mode`, `status`, `total_rows`, `created_count`, `updated_count`, `skipped_count`, `failed_count`, `failed_rows`, `error`, `ip_address`, `completed_at`, `created_at`, `updated_at`) VALUES
('01a0a008-6e42-716b-bf77-e6029c05803c', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'alumni-master-template.xlsx', 'update', 'completed', 2, 2, 0, 0, 0, '[]', NULL, '49.205.164.180', '2026-09-14 20:09:02', '2026-09-14 20:08:22', '2026-09-14 20:09:02'),
('01a0aa08-7f38-730a-96c2-254bc9e3c77a', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'alumni-master-2026-09-14.xlsx', 'update', 'completed', 3, 0, 3, 0, 0, '[]', NULL, '49.205.164.180', '2026-09-16 18:45:04', '2026-09-16 18:44:38', '2026-09-16 18:45:04'),
('01a0aa0b-e372-71e8-8858-818e981b85aa', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'alumni-master-2026-09-14.xlsx', 'update', 'completed', 3, 0, 3, 0, 0, '[]', NULL, '49.205.164.180', '2026-09-16 18:49:02', '2026-09-16 18:48:20', '2026-09-16 18:49:02'),
('01a0aa14-cbc4-7327-a4d0-bbcaf09e747b', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'alumni-master-2026-09-14.xlsx', 'update', 'completed', 3, 0, 3, 0, 0, '[]', NULL, '49.205.164.180', '2026-09-16 18:59:03', '2026-09-16 18:58:04', '2026-09-16 18:59:03'),
('01a0b51f-9667-7148-b731-4ae46a110537', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'alumni-master-template.xlsx', 'update', 'completed', 1, 1, 0, 0, 0, '[]', NULL, '49.205.164.180', '2026-09-18 22:26:02', '2026-09-18 22:25:41', '2026-09-18 22:26:02'),
('01a0c473-8536-7230-a84c-c478aa7b895c', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'alumni-master-template (1).xlsx', 'update', 'completed', 1, 0, 1, 0, 0, '[]', NULL, '49.205.164.180', '2026-09-21 21:52:02', '2026-09-21 21:51:40', '2026-09-21 21:52:02'),
('01a0c475-eb05-7254-8fdf-55627b2d0e14', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'alumni-master-template (1).xlsx', 'update', 'completed', 2, 1, 1, 0, 0, '[]', NULL, '49.205.164.180', '2026-09-21 21:55:03', '2026-09-21 21:54:17', '2026-09-21 21:55:03'),
('01a0c50c-8366-703c-ba7e-3824892dc23a', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'alumni master.xlsx', 'update', 'completed', 2, 0, 1, 0, 1, '[{\"row\":3,\"vkollabId\":\"ALM2003\",\"reasons\":[\"Missing PAN Number\",\"Missing Email\",\"Missing Mobile Number\",\"Missing Department\",\"Missing Designation\",\"Missing DOB\",\"Missing Date of Joining\",\"Missing Last Working Date\",\"Missing UAN Number\",\"Missing Last Working Location\",\"Missing Band\",\"Missing Supervisor Name\",\"Missing Supervisor Email\"]}]', NULL, '49.205.164.180', '2026-09-22 00:39:03', '2026-09-22 00:38:46', '2026-09-22 00:39:03'),
('01a179c4-4583-705c-929f-0c1f7d277082', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'alumni-master-template.xlsxs.xlsx', 'update', 'completed', 1, 0, 1, 0, 0, '[]', NULL, '49.205.164.180', '2026-10-27 02:52:02', '2026-10-27 02:51:08', '2026-10-27 02:52:02'),
('01a179c6-9937-72e3-9a15-4580717fa36b', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'alumni-master-template.xlsxs.xlsx', 'update', 'completed', 1, 0, 1, 0, 0, '[]', NULL, '49.205.164.180', '2026-10-27 02:54:05', '2026-10-27 02:53:40', '2026-10-27 02:54:05');

-- --------------------------------------------------------

--
-- Table structure for table `alumni_masters`
--

CREATE TABLE `alumni_masters` (
  `id` char(36) NOT NULL,
  `vkollab_id` varchar(40) NOT NULL,
  `pan` text DEFAULT NULL,
  `pan_hash` char(64) DEFAULT NULL,
  `name` varchar(160) NOT NULL,
  `email` varchar(160) DEFAULT NULL,
  `designation` varchar(120) DEFAULT NULL,
  `department` varchar(120) DEFAULT NULL,
  `organization` varchar(160) DEFAULT NULL,
  `current_designation` varchar(120) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `location` varchar(120) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `joined_on` date DEFAULT NULL,
  `exited_on` date DEFAULT NULL,
  `last_working_location` varchar(160) DEFAULT NULL,
  `band` varchar(40) DEFAULT NULL,
  `supervisor_name` varchar(160) DEFAULT NULL,
  `supervisor_email` varchar(160) DEFAULT NULL,
  `uan_number` varchar(20) DEFAULT NULL,
  `pf_number` varchar(40) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `claimed_by` char(36) DEFAULT NULL,
  `claimed_at` timestamp NULL DEFAULT NULL,
  `imported_by` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `vkollab_id_live` varchar(40) GENERATED ALWAYS AS (case when `deleted_at` is null then `vkollab_id` end) VIRTUAL,
  `email_live` varchar(160) GENERATED ALWAYS AS (case when `deleted_at` is null then `email` end) VIRTUAL,
  `pan_live` char(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `alumni_masters`
--

INSERT INTO `alumni_masters` (`id`, `vkollab_id`, `pan`, `pan_hash`, `name`, `email`, `designation`, `department`, `organization`, `current_designation`, `phone`, `location`, `dob`, `joined_on`, `exited_on`, `last_working_location`, `band`, `supervisor_name`, `supervisor_email`, `uan_number`, `pf_number`, `status`, `claimed_by`, `claimed_at`, `imported_by`, `created_at`, `updated_at`, `deleted_at`, `pan_live`) VALUES
('01a09615-e8b5-71c2-9a38-99288b86a3e1', '8583001', 'eyJpdiI6Ing2cWRsbjllNE9BckdpZFlmL1lqYlE9PSIsInZhbHVlIjoicTc4bTMwZ2p2MDN4by8wU3RZcHVEZz09IiwibWFjIjoiMDE3MTQ2NTJjMWU5MzJmNGZmNTdhYTM5MjE2OTdjYzEyYThmYzkyMzdiODBkZDBiODA2YmQxYmY2MDc3ODhhZCIsInRhZyI6IiJ9', '8a4348a230e25bb03466f87e49593a7e3fc28158d1086eb4c32fd023a6dca957', 'Manikanta', 'kgsmanikanta1010@gmail.com', 'Developer', 'Analytics', NULL, NULL, '9618752377', NULL, '2005-03-08', '2025-02-12', '2026-06-12', 'Guntur', 'l5', 'Harsha Venkat', 'harshavenkat@vkollab.com', '100073882576', 'KA/582he9w4hbdk', 'active', NULL, NULL, '01a09277-60bd-71b1-9fad-a03afb7db784', '2026-09-12 21:46:53', '2026-09-12 22:13:06', '2026-09-12 22:13:06', NULL),
('01a0962f-aa3b-708c-86fe-9e447f456fee', '8583002', 'eyJpdiI6IkoxNmZ0eWZtY1BFQlo1UzRXNlMvRlE9PSIsInZhbHVlIjoiVVRKNlNNYW1tY3FzZnlGWmZXOW9ldz09IiwibWFjIjoiMTc0ZjI0ZTdmN2I0YmJjZmQzZmRmZjVkZjMzNGEyZmZkNTUyZDFjMmRmYjY5MzYyMjgzZjBlN2E4Mzc4YmViZSIsInRhZyI6IiJ9', '8a4348a230e25bb03466f87e49593a7e3fc28158d1086eb4c32fd023a6dca957', 'Manikanta', 'geethasaimanikanta.k@gmail.com', 'Developer', 'Analytics', NULL, NULL, '9618752377', NULL, '2005-09-08', '2024-06-12', '2026-01-01', 'Guntur', 'l5', 'Harsha Venkat', 'harshavenkat@vkollab.com', '100073882576', 'KA/582he9w4hbdk', 'active', '01a0963f-fb96-72f9-b366-52fd81d9d235', '2026-09-12 22:32:50', '01a0a6c2-2b02-7173-8043-3439b8d31b03', '2026-09-12 22:15:01', '2026-09-16 18:45:04', NULL, NULL),
('01a0a009-0bdc-7189-bb2d-0442223b2900', 'ALM1001', 'eyJpdiI6InFhUlRXMGVHOUJXNDQ1TDRPMGxCYUE9PSIsInZhbHVlIjoiY0pmRENxVjh2dWpBRVFwaWJ5UllVZz09IiwibWFjIjoiODYyODEzN2ViYTY3OGQ1ZmM0NTE2ZjkxM2FhZGFiNTNjNmZiNWMzZjBmZGFhZDYzZmY0NDg2NDk3N2MxYjUxYyIsInRhZyI6IiJ9', 'fa0d03cd8d258c103361b87266a87dd70fdfb93126adee4115e912b7701d5af6', 'Monish', 'claudeai0801@gmail.com', 'SAP ABAP Developer updated', 'SAP Functional', NULL, NULL, '7894561235', NULL, '2003-08-23', '2022-04-08', '2026-03-28', 'Guntur', 'B1', 'Harsha', 'harsha@vkollab.com', '100200909093', 'KA/BNG/00123/457', 'active', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', '2026-09-14 20:16:09', '01a0a6c2-2b02-7173-8043-3439b8d31b03', '2026-09-14 20:09:02', '2026-09-16 22:21:36', NULL, NULL),
('01a0a009-0bf0-710e-b0e9-7794808b2c58', 'ALM1002', 'eyJpdiI6ImtIV2dZaDlNeVFadUFqODgrZWw5MGc9PSIsInZhbHVlIjoiN25FZ09vcDZmQlU5U0xsYW92bVBYUT09IiwibWFjIjoiZjFmNGYyNTk2NmZjZjUzYWYzZTFmMDZjY2JmM2RmZDQ2NDY3N2MxODc5YjdhZTU2MmRkN2MxMzZhYzRmNDdlNiIsInRhZyI6IiJ9', '076225b7a2708df94ea031d4825f91aa44ad87c630ab79b03fa708e1c13005b7', 'Bhargav', 'mani9618752377@gmail.com', 'SAP CPI Developer', 'SAP Technical', NULL, NULL, '9874561235', NULL, '2004-09-20', '2023-05-02', '2026-08-23', 'Hyderabad', 'B2', 'Harsha', 'harsha@vkollab.com', '100345678765', 'KA/BNG/00123/458', 'active', NULL, NULL, '01a0a6c2-2b02-7173-8043-3439b8d31b03', '2026-09-14 20:09:02', '2026-09-16 18:45:04', NULL, NULL),
('01a0a6b5-335b-71e6-ab88-817b99c6c069', 'ALM1003', 'eyJpdiI6IkNJSEgrbmZJeGxoSnkxYW1OdllIRHc9PSIsInZhbHVlIjoiOWFWcW9vaUplZlVMcWZqa1VEVFpqZz09IiwibWFjIjoiMmU2OWFmZTcwZjhkM2JjODE5MTNmMTJlNzExMmY0YmZmNjE2ZThhZTgxMDhhZmQ5ZDY3NTQ5MGRmZWE5YTI5YyIsInRhZyI6IiJ9', '36918b4e67a2d5b009c8e1a3076551906e899e58da4d45b5823b03990e174ab5', 'Mahesh', 'patlurimaheshbabu756@gmail.com', 'SAP ABAP DEVELOPER', 'SAP Technical', NULL, NULL, '7569918822', NULL, '2002-06-13', '2024-07-16', '2026-02-17', 'Hyderabad', 'B4', 'Harsha', 'harsha@vkollab.com', '100234569874', 'KA/BND/34567/345', 'active', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', '2026-09-19 01:40:43', '01a09225-0284-7153-8eb0-bd4f0484a94a', '2026-09-16 03:14:48', '2026-09-19 01:40:43', NULL, NULL),
('01a0b51f-ea94-73c2-ba9b-52d1d7ceec04', 'ALM2001', 'eyJpdiI6Ikt6RllCWW8vNEpTTnRudlErbUQ5OVE9PSIsInZhbHVlIjoiMjluUE1zTW1VQW15ZkJnQ2tKTGVoQT09IiwibWFjIjoiN2EzZmY5ZWQwZTM3ODQ4MTM5OGYwM2VhNTA5YzczYzNkZDAzYzJlOGUxMTU5ZDM1ZTI2MWFhODdiOGNjY2MyNCIsInRhZyI6IiJ9', '81a3aee3f49c7fb246ba1831a0182c3adf3efc2dfe6e02748eb01401101b31c8', 'Asha Rao', 'asha.rao@example.com', 'SAP ABAP Developer', 'SAP Technical', NULL, NULL, '9000000000', NULL, '1992-07-15', '2019-04-01', '2024-03-31', 'Bengaluru Office', 'B4', 'Priya Sharma', 'priya.sharma@vkollab.com', '100234567890', 'KA/BNG/00123/456', 'active', NULL, NULL, '01a09225-0284-7153-8eb0-bd4f0484a94a', '2026-09-18 22:26:02', '2026-09-18 22:26:02', NULL, NULL),
('01a0c476-a059-716a-a211-35dbabb7bf94', 'ALM2002', 'eyJpdiI6IjB5K3Bqa0xnTnV2c3NoT1dVcDRvd0E9PSIsInZhbHVlIjoiWHVVanVwTStPMnNrTmJqTW9VOUFzUT09IiwibWFjIjoiM2E0NGU1MGNjYzU4ODFiOGNjZGQzMTdlM2MxNDhjMDdkODk0NjczNmI0NTJlNDhiZWJmZjAwYmE1MTcwMDY3MCIsInRhZyI6IiJ9', 'a015f1d04f8231d04c67d1f008aed6e448c4f499187a0b27f7885936d755e4a7', 'Asha Rao', 'asha.rfao@example.com', 'SAP ABAP Developer', 'SAP Technical', NULL, NULL, '9080000000', NULL, '1992-07-15', '2019-04-01', '2024-03-31', 'Bengaluru Office', 'B4', 'Priya Sharma', 'priya.shahrma@vkollab.com', '109284567890', 'TSHYD9876543210ABCD123', 'active', NULL, NULL, '01a09225-0284-7153-8eb0-bd4f0484a94a', '2026-09-21 21:55:03', '2026-10-27 02:52:02', NULL, NULL),
('01a17832-57fe-707f-a163-925d7cc1558e', 'ALM1006', 'eyJpdiI6Ik80QmpocWUxZ3NrVHhHS2xVSUtoT1E9PSIsInZhbHVlIjoiVVJ1YjVBSm9XUmduWkhuOCtQTjA5QT09IiwibWFjIjoiZDg0NGI1YjU4MDBiYjMwYzJkZWZhZGE4MjExODQ1NWM3MjdlYTgyNzc4YTE2M2ZhYmU1ZGMxYjVjM2I5MmU0NiIsInRhZyI6IiJ9', '5e62573b611eca5418f66c9bbb8950cb1c8047ea80ab128b03e2515beb5a9eed', 'Mohan kumar', 'potarlankamohankumar@gmail.com', 'Analytics', 'AI', NULL, NULL, '9951587158', NULL, '2003-08-06', '2025-06-27', '2026-08-05', 'Hyderabad', 'B4', 'Harsha', 'harsha@vkollab.com', '100234569874', 'KA/BND/34567/345', 'active', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '2026-10-26 19:36:02', '01a0a6c2-2b02-7173-8043-3439b8d31b03', '2026-10-26 19:32:07', '2026-10-26 19:36:02', NULL, NULL),
('01a178f8-cc9e-710d-a393-8b1382b638f1', 'ALM2003', 'eyJpdiI6IkJUUmM5RTh3ZDhTb2c2amRKdERteFE9PSIsInZhbHVlIjoib3RYWGFHMVFwbDRWMTA1UFVCeXpGZz09IiwibWFjIjoiZDUzZjc4ZWUxYTRlOWY1ODZlZTUzYzJhMjc2MDAzYjI0MzFmOTNjODRlM2U1MGQ2ZjlkMzIzY2FmYjMwZWZmYSIsInRhZyI6IiJ9', 'f01cea94e5e207933bb5874942a957713e9203fcb4c74baed74914f5935ef842', 'Sachin Tendulkar', 'sachin@gmail.com', 'Developer', 'ABAP', NULL, NULL, '9876543210', NULL, '1999-10-27', '2022-03-23', '2026-04-22', 'Hyderabad', 'B2', 'Anil Kumar', 'anil@gmail.com', '123456789101', 'AP/HYD/1234567/000/12345', 'active', NULL, NULL, '01a0a6c2-2b02-7173-8043-3439b8d31b03', '2026-10-26 23:08:53', '2026-10-27 01:18:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `alumni_profiles`
--

CREATE TABLE `alumni_profiles` (
  `id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `vkollab_id` varchar(40) DEFAULT NULL,
  `pan` text DEFAULT NULL,
  `pan_hash` char(64) DEFAULT NULL,
  `designation` varchar(120) DEFAULT NULL,
  `department` varchar(120) DEFAULT NULL,
  `current_organization` varchar(160) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `location` varchar(120) DEFAULT NULL,
  `joined_on` date DEFAULT NULL,
  `exited_on` date DEFAULT NULL,
  `last_working_location` varchar(160) DEFAULT NULL,
  `band` varchar(40) DEFAULT NULL,
  `supervisor_name` varchar(160) DEFAULT NULL,
  `supervisor_email` varchar(160) DEFAULT NULL,
  `uan_number` varchar(20) DEFAULT NULL,
  `pf_number` varchar(40) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `profile_completion` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `alumni_profiles`
--

INSERT INTO `alumni_profiles` (`id`, `user_id`, `vkollab_id`, `pan`, `pan_hash`, `designation`, `department`, `current_organization`, `phone`, `location`, `joined_on`, `exited_on`, `last_working_location`, `band`, `supervisor_name`, `supervisor_email`, `uan_number`, `pf_number`, `bio`, `profile_completion`, `created_at`, `updated_at`, `deleted_at`) VALUES
('01a0963f-fba6-718d-9113-013972d42e85', '01a0963f-fb96-72f9-b366-52fd81d9d235', '8583002', 'eyJpdiI6IklWYUJLT3IyckF1Y0RpdEdZSFFFOVE9PSIsInZhbHVlIjoidVYyZFNKTFVOaXNGbUJXaDJNYUMxdz09IiwibWFjIjoiNWQ4OGQzNWI4NmJjMTgzZThjNzE4NTRmNzg2ODJiOWQ2OTYzZjdhYmMzYmU5M2IwOTFlYzYyZDhlNDJiMGQ3YyIsInRhZyI6IiJ9', '8a4348a230e25bb03466f87e49593a7e3fc28158d1086eb4c32fd023a6dca957', 'Developer', 'Analytics', NULL, '9618752377', NULL, '2024-06-12', '2026-01-01', 'Guntur', 'l5', 'Harsha Venkat', 'harshavenkat@vkollab.com', '100073882576', 'KA/582he9w4hbdk', NULL, 30, '2026-09-12 22:32:50', '2026-09-12 22:32:50', NULL),
('01a0a00f-8e57-73a8-bd3b-0c0181f0e047', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'ALM1001', 'eyJpdiI6InAxMjhrUEJvS21DaDdKdWU1RXd3QkE9PSIsInZhbHVlIjoicUxVQk9Gd0REK01pQkw1OGZkcjRoZz09IiwibWFjIjoiZTFhMmFmMmE5MzhlMDZjNzU0YTcwZGI5NGJjNmFmMWRmN2RlMjg0YTkyMTJlNWY4YWZmOTE5YmQwY2UzOWE4OSIsInRhZyI6IiJ9', 'fa0d03cd8d258c103361b87266a87dd70fdfb93126adee4115e912b7701d5af6', 'SAP ABAP Developer updated', 'SAP Functional', NULL, '7894561235', NULL, '2022-04-08', '2026-03-28', 'Guntur', 'B1', 'Harsha', 'harsha@vkollab.com', '100200909093', 'KA/BNG/00123/457', NULL, 30, '2026-09-14 20:16:09', '2026-09-16 22:21:36', NULL),
('01a0b5d2-24a8-700f-b837-00b464dd1b4d', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'ALM1003', 'eyJpdiI6IlVxY2ZLcTFwUXQ2Q2kxSmpEdGF0MkE9PSIsInZhbHVlIjoiS3dHVi9vbjRUZWdCZXMydW0yZkJQZz09IiwibWFjIjoiNDI4Mzc5N2VhNTY3OGExODg0ZGI5YzE5ZTdkOGYxYjI2NDRhYTE0YTVkNjM0OGFmNmNiNWMxOTI0MjY5YTk1NyIsInRhZyI6IiJ9', '36918b4e67a2d5b009c8e1a3076551906e899e58da4d45b5823b03990e174ab5', 'SAP ABAP DEVELOPER', 'SAP Technical', NULL, '7569918822', NULL, '2024-07-16', '2026-02-17', 'Hyderabad', 'B4', 'Harsha', 'harsha@vkollab.com', '100234569874', 'KA/BND/34567/345', NULL, 30, '2026-09-19 01:40:43', '2026-09-19 01:40:43', NULL),
('01a17835-ee3b-7396-94a9-71be8ad0cf73', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'ALM1006', 'eyJpdiI6Ijd1K3ZmeHQ3YURWK09QR2tOakJ6N1E9PSIsInZhbHVlIjoiS1AzakxjTlEvK0pYVUFFSUtHRE5Fdz09IiwibWFjIjoiOWNlZDA5ODRlYWY5ODNmM2RjYzhlOGE2ZjU4N2E0MzM5YmY2OTQ2YTEyNTM2ZDIzYzA4NWVhZTEzMDRjODAzNCIsInRhZyI6IiJ9', '5e62573b611eca5418f66c9bbb8950cb1c8047ea80ab128b03e2515beb5a9eed', 'Analytics', 'AI', NULL, '9951587158', NULL, '2025-06-27', '2026-08-05', 'Hyderabad', 'B4', 'Harsha', 'harsha@vkollab.com', '100234569874', 'KA/BND/34567/345', NULL, 30, '2026-10-26 19:36:02', '2026-10-26 19:36:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` char(36) NOT NULL,
  `author_id` char(36) DEFAULT NULL,
  `title` varchar(180) NOT NULL,
  `body` longtext NOT NULL,
  `audience` varchar(16) NOT NULL DEFAULT 'all',
  `status` varchar(16) NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `author_id`, `title`, `body`, `audience`, `status`, `published_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
('01a0a0d9-a348-7166-8646-99f2bdc5fe38', '01a09277-60bd-71b1-9fad-a03afb7db784', 'Microsoft copilot session', 'Everyone must and should attend the 3 days session', 'alumni', 'published', '2026-09-14 23:56:52', '2026-09-14 23:56:52', '2026-09-15 21:18:20', '2026-09-15 21:18:20'),
('01a0a6ff-48af-7328-8d11-0db40283185f', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Microsoft copilot', 'fhdhfdf', 'all', 'published', '2026-09-16 04:35:43', '2026-09-16 04:35:43', '2026-09-16 04:35:43', NULL),
('01a178f4-5eb7-729d-9fad-ba1a298aae35', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Title: Q3 Alumni Meet, Audience: Everyone', 'FDFDFD', 'all', 'published', '2026-10-26 23:04:03', '2026-10-26 23:04:03', '2026-10-26 23:04:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `app_notifications`
--

CREATE TABLE `app_notifications` (
  `id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `type` varchar(32) NOT NULL DEFAULT 'system',
  `title` varchar(180) NOT NULL,
  `body` varchar(500) DEFAULT NULL,
  `action_url` varchar(255) DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `app_notifications`
--

INSERT INTO `app_notifications` (`id`, `user_id`, `type`, `title`, `body`, `action_url`, `read_at`, `created_at`, `updated_at`) VALUES
('0029a1d0-ed28-4994-bd99-4ee07288b85c', '01a09277-60bd-71b1-9fad-a03afb7db784', 'document', 'Document Download Activity Detected', 'Manikanta downloaded PAN.', '/hr/documents', '2026-09-15 03:48:37', '2026-09-13 01:03:51', '2026-09-15 03:48:37'),
('013e6e52-77ee-47cd-85bf-a181cdefbebe', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'alumni', 'Alumni Master Imported', 'Harsha imported alumni-master-2026-09-14.xlsx: 0 created, 3 updated, 0 failed.', '/super-admin/alumni-master', NULL, '2026-09-16 18:59:03', '2026-09-16 18:59:03'),
('01a096c5-1945-72a7-b379-e2c89953e111', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'document', 'New document available', 'PAN was added to your vault.', '/alumni/documents', '2026-09-13 01:04:12', '2026-09-13 00:58:14', '2026-09-13 01:04:12'),
('01a0a100-a4f8-70d9-bc5a-46607be4388f', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'document', 'New document available', 'Salary Slips — FY 2025-2026 was added to your vault.', '/alumni/documents', '2026-09-18 23:40:27', '2026-09-15 00:39:29', '2026-09-18 23:40:27'),
('01a0aa1c-0219-70bd-9ea1-150fa2e01a98', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'document', 'New document available', 'Salary Slips — FY 2025-2026 was added to your vault.', '/alumni/documents', '2026-09-18 23:40:27', '2026-09-16 19:05:57', '2026-09-18 23:40:27'),
('01a0c519-e829-7008-af9c-f205afdb550c', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'document', 'Document updated', 'PAN was updated in your vault.', '/alumni/documents', NULL, '2026-09-22 00:53:24', '2026-09-22 00:53:24'),
('01a0c5d7-edd4-7125-bb0d-467d4d1af6c6', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'document', 'New document available', 'Salary Slips — FY 2025-2026 was added to your vault.', '/alumni/documents', NULL, '2026-09-22 04:20:57', '2026-09-22 04:20:57'),
('01a0ed53-11de-7116-a0c9-5cd5736c65be', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'document', 'New document available', 'Experience Letter was added to your vault.', '/alumni/documents', NULL, '2026-09-29 20:20:39', '2026-09-29 20:20:39'),
('01a0ed53-d25d-70a9-ab65-634294e8d8db', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'document', 'New document available', 'Increment Letter was added to your vault.', '/alumni/documents', NULL, '2026-09-29 20:21:28', '2026-09-29 20:21:28'),
('01a0ed54-d025-7207-a745-895c987fbf54', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'document', 'New document available', 'updated Full & Final Settlement was added to your vault.', '/alumni/documents', NULL, '2026-09-29 20:22:33', '2026-09-29 20:22:33'),
('01a0ed7b-b6d9-725a-81a0-9948b8da4104', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'document', 'New document available', 'Reliving letters was added to your vault.', '/alumni/documents', NULL, '2026-09-29 21:05:03', '2026-09-29 21:05:03'),
('01a0ed7f-870a-7054-8dff-5f9e25d2dc0a', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'document', 'New document available', 'updated Full & Final Settlement was added to your vault.', '/alumni/documents', NULL, '2026-09-29 21:09:12', '2026-09-29 21:09:12'),
('01a17836-9c3d-71be-b466-b5bce5ef9ec3', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'document', 'New document available', 'Salary Slips — FY 2026-2027 was added to your vault.', '/alumni/documents', '2026-10-26 19:39:02', '2026-10-26 19:36:47', '2026-10-26 19:39:02'),
('01a178ec-ef39-7164-a9ef-bc0792706ff7', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'application', 'Application update: AI Developer', 'Good news — you have been shortlisted.', '/alumni/applications', NULL, '2026-10-26 22:55:56', '2026-10-26 22:55:56'),
('01a1797c-14eb-725b-b63e-687f4e8228fc', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'document', 'New document available', 'Salary Slips — FY 2026-2027 was added to your vault.', '/alumni/documents', NULL, '2026-10-27 01:32:17', '2026-10-27 01:32:17'),
('01a1799e-20b1-72e6-adaf-85d9a75db2a1', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'document', 'New document available', 'Experience Letter was added to your vault.', '/alumni/documents', NULL, '2026-10-27 02:09:28', '2026-10-27 02:09:28'),
('09e5b2f9-cf34-493f-a720-36423c5605e2', '01a09277-60bd-71b1-9fad-a03afb7db784', 'document', 'Document Download Activity Detected', 'Mahesh downloaded Increment Letter.', '/hr/documents', NULL, '2026-10-27 01:43:27', '2026-10-27 01:43:27'),
('0ce7e23b-3012-4dbe-ab13-730a0ec17543', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'application', 'New Job Application Received', 'Mohan kumar applied for AI Developer.', '/hr/applications', NULL, '2026-10-26 19:51:37', '2026-10-26 19:51:37'),
('0f1693e6-0d56-499b-9d8c-7ca417369931', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'announcement', 'Announcement Published', 'Harsha published \"Title: Q3 Alumni Meet, Audience: Everyone\".', '/super-admin/dashboard', NULL, '2026-10-26 23:04:03', '2026-10-26 23:04:03'),
('0ffb83f7-2db9-4913-9af5-3c4c53c9a1bd', '01a09277-60bd-71b1-9fad-a03afb7db784', 'system', 'Platform Update', 'Super Admin updated platform settings.', '/hr/settings', NULL, '2026-09-18 23:08:30', '2026-09-18 23:08:30'),
('1586cb66-c38e-41bc-9640-c596a23aef96', '01a09277-60bd-71b1-9fad-a03afb7db784', 'system', 'Platform Update', 'Super Admin updated platform settings.', '/hr/settings', NULL, '2026-09-29 18:46:19', '2026-09-29 18:46:19'),
('16b53b2c-70e2-48ff-87ba-08c2c5af4d01', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'announcement', 'Microsoft copilot', 'fhdhfdf', NULL, NULL, '2026-09-16 04:35:43', '2026-09-16 04:35:43'),
('17a55502-4eb6-4e02-baad-29ddc5e15541', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'alumni', 'Alumni Master Imported', 'Super Admin imported alumni-master-template (1).xlsx: 1 created, 1 updated, 0 failed.', '/super-admin/alumni-master', NULL, '2026-09-21 21:55:03', '2026-09-21 21:55:03'),
('1a241607-1bb5-48fd-86b6-25ab2f453075', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'announcement', 'Microsoft copilot session', 'Everyone must and should attend the 3 days session', NULL, '2026-09-18 23:40:27', '2026-09-14 23:56:52', '2026-09-18 23:40:27'),
('1a77dc1b-2faf-44d6-bf85-50645d4af0ac', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'alumni', 'Alumni Master Imported', 'Super Admin imported alumni-master-template.xlsxs.xlsx: 0 created, 1 updated, 0 failed.', '/super-admin/alumni-master', NULL, '2026-10-27 02:54:05', '2026-10-27 02:54:05'),
('1ac1406b-2b3e-452a-ac46-3baa697b307d', '01a09277-60bd-71b1-9fad-a03afb7db784', 'system', 'Platform Update', 'Super Admin updated platform settings.', '/hr/settings', NULL, '2026-09-17 01:10:32', '2026-09-17 01:10:32'),
('1eb529fd-79c6-4d94-a8d4-159e8a37d5f4', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'alumni', 'Alumni Master Imported', 'Super Admin imported alumni master.xlsx: 0 created, 1 updated, 1 failed.', '/super-admin/alumni-master', NULL, '2026-09-22 00:39:03', '2026-09-22 00:39:03'),
('204ee9f8-41da-4819-aa56-14cabf9ec475', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'document', 'Document Deleted', 'Super Admin deleted \"updated Full & Final Settlement\" belonging to Mahesh.', '/super-admin/dashboard', NULL, '2026-09-29 21:10:32', '2026-09-29 21:10:32'),
('20f065fa-e3f9-4912-ae23-78587ed7684e', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'system', 'Platform Update', 'Super Admin updated platform settings.', '/hr/settings', NULL, '2026-09-18 23:30:53', '2026-09-18 23:30:53'),
('225fda79-b5d1-49b3-ad7e-f31bd1a5238f', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'application', 'New Job Application Received', 'Monish applied for Supply chain management.', '/hr/applications', NULL, '2026-09-16 20:39:10', '2026-09-16 20:39:10'),
('2768b389-710d-431a-997d-071a00d36871', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'announcement', 'Announcement Published', 'Harsha published \"Microsoft copilot\".', '/super-admin/dashboard', NULL, '2026-09-16 04:35:43', '2026-09-16 04:35:43'),
('295181d6-e8b0-4a92-aaae-e72ccf20ab44', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'system', 'Platform Update', 'Super Admin updated \"The trusted enterprise home for VKollab Alumni.\" content.', '/hr/settings', NULL, '2026-10-26 20:07:21', '2026-10-26 20:07:21'),
('29a92ea1-5a83-4244-960a-973de8b4f055', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'document', 'Document Download Activity Detected', 'Monish downloaded Salary Slips — FY 2025-2026.', '/hr/documents', NULL, '2026-09-16 19:06:14', '2026-09-16 19:06:14'),
('2d489ecb-772a-468b-b8ff-571df4225bb0', '01a09277-60bd-71b1-9fad-a03afb7db784', 'system', 'Platform Update', 'Super Admin updated platform settings.', '/hr/settings', NULL, '2026-09-18 23:06:53', '2026-09-18 23:06:53'),
('30543b24-6884-44fd-bb66-f376b2b36203', '01a09277-60bd-71b1-9fad-a03afb7db784', 'system', 'Platform Update', 'Super Admin updated platform settings.', '/hr/settings', NULL, '2026-09-18 23:08:31', '2026-09-18 23:08:31'),
('32eb22d9-6d77-4590-89e4-c746e4eb8932', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'job', 'Job Posted', 'Harsha posted \"Supply chain management\".', '/super-admin/dashboard', NULL, '2026-09-16 04:32:01', '2026-09-16 04:32:01'),
('37c35396-623d-47e9-b99b-4744d143fe78', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'job', 'New job posted: AI Developer', 'VKollab Technologies · Guntur', '/alumni/jobs', NULL, '2026-10-26 19:40:52', '2026-10-26 19:40:52'),
('3d071006-4480-406e-baf8-e65017dfad21', '01a09277-60bd-71b1-9fad-a03afb7db784', 'system', 'Platform Update', 'Super Admin updated \"Terms & Conditions\" content.', '/hr/settings', '2026-09-13 00:58:25', '2026-09-13 00:48:17', '2026-09-13 00:58:25'),
('3ecd9e00-6740-435a-abdf-553eabab19d1', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'job', 'New job posted: Supply chain management', 'VKollab Technologies · Guntur', '/alumni/jobs', NULL, '2026-09-16 04:32:01', '2026-09-16 04:32:01'),
('3f1bcc9e-8d0a-41aa-9006-cf2177eb4e81', '01a09277-60bd-71b1-9fad-a03afb7db784', 'document', 'Document Download Activity Detected', 'Mahesh downloaded Experience Letter.', '/hr/documents', NULL, '2026-10-27 01:37:11', '2026-10-27 01:37:11'),
('3f273192-3e68-4056-a2d6-b15788859190', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'alumni', 'Roster Record Deleted', 'MANIKANTA removed Manikanta (8583001) from the alumni roster.', '/super-admin/alumni-master', NULL, '2026-09-12 22:13:06', '2026-09-12 22:13:06'),
('3f3112ff-1778-4b13-8382-52f5440ce163', '01a09277-60bd-71b1-9fad-a03afb7db784', 'document', 'Document Download Activity Detected', 'Monish downloaded Salary Slips — FY 2025-2026.', '/hr/documents', NULL, '2026-09-18 23:37:43', '2026-09-18 23:37:43'),
('4174be5b-df38-4a32-b15e-3bad0936c809', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'document', 'Document Download Activity Detected', 'Mohan kumar downloaded Salary Slips — FY 2026-2027.', '/hr/documents', NULL, '2026-10-26 19:37:25', '2026-10-26 19:37:25'),
('43a4002b-2113-4a95-8e63-57a76aecc40c', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'alumni', 'Alumni Master Imported', 'Harsha imported alumni-master-2026-09-14.xlsx: 0 created, 3 updated, 0 failed.', '/super-admin/alumni-master', NULL, '2026-09-16 18:59:03', '2026-09-16 18:59:03'),
('45a70618-c82c-4620-a48e-0173a2809a6a', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'system', 'Platform Update', 'Super Admin updated platform settings.', '/hr/settings', NULL, '2026-09-18 23:06:53', '2026-09-18 23:06:53'),
('461703f3-627d-46ab-93f0-30663a851252', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'document', 'Document Download Activity Detected', 'Mahesh downloaded Experience Letter.', '/hr/documents', NULL, '2026-10-27 01:37:11', '2026-10-27 01:37:11'),
('4b4f9751-7112-4037-be59-d31568f13046', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'alumni', 'Alumni Added', 'Harsha added Mohan kumar (ALM1006) to the alumni roster.', '/super-admin/alumni-master', NULL, '2026-10-26 19:32:07', '2026-10-26 19:32:07'),
('4b754f18-f5cb-42be-a1ce-167020d5304a', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'application', 'New Job Application Received', 'Mahesh applied for AI Developer.', '/hr/applications', NULL, '2026-10-27 01:42:04', '2026-10-27 01:42:04'),
('4bf3aa75-67c4-4b39-9057-6ce328d8df2d', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'job', 'New job posted: SAP CPI DEVELOPER', 'VKollab Technologies · Hyderabad', '/alumni/jobs', NULL, '2026-09-15 20:02:15', '2026-09-15 20:02:15'),
('4cd44451-0d97-43ff-a8e0-8aff45b31da8', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'job', 'New job posted: AI Developer Position', 'VKollab Technologies · Guntur - Andhra Pradesh', '/alumni/jobs', NULL, '2026-10-27 01:49:34', '2026-10-27 01:49:34'),
('4d8facb6-486a-45bc-a71e-b139ac777a86', '01a09277-60bd-71b1-9fad-a03afb7db784', 'system', 'Platform Update', 'Super Admin updated platform settings.', '/hr/settings', NULL, '2026-09-18 23:30:53', '2026-09-18 23:30:53'),
('53ea3ec6-ea83-4665-93a4-3bb4f5216d41', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'job', 'New job posted: Supply chain management', 'VKollab Technologies · Guntur', '/alumni/jobs', '2026-09-18 23:40:27', '2026-09-16 04:32:01', '2026-09-18 23:40:27'),
('55746c3c-03f6-45d8-bd8e-22f37e42349f', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'job', 'Job Posted', 'MANIKANTA posted \"SAP ABAP DEVELOPER\".', '/super-admin/dashboard', NULL, '2026-09-14 19:33:13', '2026-09-14 19:33:13'),
('56b4e1bc-c879-4afe-9ed9-1f28d683c213', '01a09277-60bd-71b1-9fad-a03afb7db784', 'announcement', 'Microsoft copilot', 'fhdhfdf', NULL, NULL, '2026-09-16 04:35:43', '2026-09-16 04:35:43'),
('587a05a5-70cf-4fcd-a4f8-4c3fd7c00885', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'job', 'Job Posted', 'Harsha posted \"AI Developer Position\".', '/super-admin/dashboard', NULL, '2026-10-27 01:49:34', '2026-10-27 01:49:34'),
('5982fbdb-7a9b-46a9-b2dc-265188c7f442', '01a09277-60bd-71b1-9fad-a03afb7db784', 'document', 'Document Download Activity Detected', 'Mahesh downloaded updated Full & Final Settlement.', '/hr/documents', NULL, '2026-09-29 21:10:09', '2026-09-29 21:10:09'),
('59b1e9de-5e09-4b69-9ec8-68951d3b8fca', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'announcement', 'Announcement Published', 'Harsha published \"Title: Q3 Alumni Meet, Audience: Everyone\".', '/super-admin/dashboard', NULL, '2026-10-26 23:04:03', '2026-10-26 23:04:03'),
('5b66e312-a807-43a0-a981-4bc8e0989a1f', '01a09277-60bd-71b1-9fad-a03afb7db784', 'system', 'Platform Update', 'Super Admin updated \"The trusted enterprise home for VKollab Alumni.\" content.', '/hr/settings', NULL, '2026-10-26 20:07:21', '2026-10-26 20:07:21'),
('5c0a92a1-51c0-4474-95e9-f6b4e2a7b222', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'job', 'Job Posted', 'Harsha posted \"Supply chain management\".', '/super-admin/dashboard', NULL, '2026-09-16 04:32:01', '2026-09-16 04:32:01'),
('5ed02976-c1c7-4e50-a01e-66fc5bfea88e', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'job', 'New job posted: SAP ABAP DEVELOPER', 'VKollab Technologies · Guntur', '/alumni/jobs', NULL, '2026-09-14 19:33:13', '2026-09-14 19:33:13'),
('64e8fa5a-8bab-4e11-973f-436759797c21', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'job', 'New job posted: AI Developer Position', 'VKollab Technologies · Guntur - Andhra Pradesh', '/alumni/jobs', NULL, '2026-10-27 01:49:34', '2026-10-27 01:49:34'),
('650006c4-9d01-4c31-be89-7c126f202ee5', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'announcement', 'Title: Q3 Alumni Meet, Audience: Everyone', 'FDFDFD', NULL, NULL, '2026-10-26 23:04:03', '2026-10-26 23:04:03'),
('65764969-f24d-4742-904f-651745f94610', '01a09277-60bd-71b1-9fad-a03afb7db784', 'system', 'Platform Update', 'Super Admin updated platform settings.', '/hr/settings', NULL, '2026-10-27 02:13:56', '2026-10-27 02:13:56'),
('66d06c4e-37a8-4ad9-b2db-1b22bbbcf3ad', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'alumni', 'Alumni Added', 'MANIKANTA added Manikanta (8583001) to the alumni roster.', '/super-admin/alumni-master', NULL, '2026-09-12 21:46:53', '2026-09-12 21:46:53'),
('67d305ba-0065-4884-ae35-cdf6d07bb9a5', '01a09277-60bd-71b1-9fad-a03afb7db784', 'announcement', 'Title: Q3 Alumni Meet, Audience: Everyone', 'FDFDFD', NULL, NULL, '2026-10-26 23:04:03', '2026-10-26 23:04:03'),
('6d0fb75e-3be8-4b0c-acc0-b748981eb4b3', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'alumni', 'Alumni Master Imported', 'MANIKANTA imported alumni-master-template.xlsx: 2 created, 0 updated, 0 failed.', '/super-admin/alumni-master', NULL, '2026-09-14 20:09:02', '2026-09-14 20:09:02'),
('6d92da6e-2ee5-4b1e-b24e-778c2947caa5', '01a09277-60bd-71b1-9fad-a03afb7db784', 'system', 'Platform Update', 'Super Admin updated \"Privacy Policy\" content.', '/hr/settings', '2026-09-13 00:58:25', '2026-09-13 00:47:38', '2026-09-13 00:58:25'),
('6f09603f-28e0-46b7-94b1-168cb7a1d113', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'alumni', 'Alumni Added', 'Harsha added Sachin Tendulkar (ALM2003) to the alumni roster.', '/super-admin/alumni-master', NULL, '2026-10-26 23:08:53', '2026-10-26 23:08:53'),
('6ff6b1f4-033f-4e3a-9c71-b4c12ad7ba9d', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'document', 'Document Download Activity Detected', 'Monish downloaded Salary Slips — FY 2025-2026.', '/hr/documents', NULL, '2026-09-18 23:37:48', '2026-09-18 23:37:48'),
('76a18761-ce95-43c5-b7c5-b4d288f44026', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'system', 'Platform Update', 'Super Admin updated platform settings.', '/hr/settings', NULL, '2026-09-29 18:46:19', '2026-09-29 18:46:19'),
('79f6089a-6093-4cb6-810e-eb560a3051d1', '01a09277-60bd-71b1-9fad-a03afb7db784', 'application', 'New Job Application Received', 'Mahesh applied for AI Developer.', '/hr/applications', NULL, '2026-10-27 01:42:04', '2026-10-27 01:42:04'),
('7ab54e5f-605e-4d4f-906e-d290d54926f4', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'job', 'Job Posted', 'Harsha posted \"AI Developer\".', '/super-admin/dashboard', NULL, '2026-10-26 19:40:52', '2026-10-26 19:40:52'),
('7dac05f2-9b29-4f58-9591-8f5daea56167', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'application', 'New Job Application Received', 'Monish applied for Supply chain management.', '/hr/applications', NULL, '2026-09-16 20:39:10', '2026-09-16 20:39:10'),
('7e0523fe-cfe6-4a98-ab1c-b1e813c19334', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'alumni', 'Alumni Master Imported', 'Harsha imported alumni-master-2026-09-14.xlsx: 0 created, 3 updated, 0 failed.', '/super-admin/alumni-master', NULL, '2026-09-16 18:45:04', '2026-09-16 18:45:04'),
('805bf20d-519e-4556-9c02-d33170b8a44f', '01a09277-60bd-71b1-9fad-a03afb7db784', 'system', 'Platform Update', 'Super Admin updated platform settings.', '/hr/settings', NULL, '2026-09-17 01:10:46', '2026-09-17 01:10:46'),
('815e9af8-b9ad-44d3-ba59-3a8f6a6c47d9', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'alumni', 'Alumni Master Imported', 'Super Admin imported alumni-master-template.xlsx: 1 created, 0 updated, 0 failed.', '/super-admin/alumni-master', NULL, '2026-09-18 22:26:02', '2026-09-18 22:26:02'),
('848b5a15-176c-4d41-a770-8bc72112a123', '01a09277-60bd-71b1-9fad-a03afb7db784', 'system', 'Platform Update', 'Super Admin updated platform settings.', '/hr/settings', NULL, '2026-09-18 23:07:29', '2026-09-18 23:07:29'),
('85b85ab2-db4a-4ab9-95ec-437eaf7d3246', '01a09277-60bd-71b1-9fad-a03afb7db784', 'application', 'New Job Application Received', 'Mohan kumar applied for AI Developer.', '/hr/applications', NULL, '2026-10-26 19:51:37', '2026-10-26 19:51:37'),
('898b00b2-09fb-4e18-b799-1e7083f840d7', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'job', 'Job Posted', 'Harsha posted \"AI Developer Position\".', '/super-admin/dashboard', NULL, '2026-10-27 01:49:34', '2026-10-27 01:49:34'),
('89bdd65b-5326-4c7a-b2bd-b0fecb54968f', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'alumni', 'Alumni Master Imported', 'Super Admin imported alumni master.xlsx: 0 created, 1 updated, 1 failed.', '/super-admin/alumni-master', NULL, '2026-09-22 00:39:03', '2026-09-22 00:39:03'),
('8b8ae161-65d7-4254-9f2a-97c67cdb5bfe', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'alumni', 'Alumni Master Imported', 'Super Admin imported alumni-master-template (1).xlsx: 1 created, 1 updated, 0 failed.', '/super-admin/alumni-master', NULL, '2026-09-21 21:55:03', '2026-09-21 21:55:03'),
('8cf1b6d6-59cc-4cd4-aca8-2eec42623439', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'announcement', 'Microsoft copilot session', 'Everyone must and should attend the 3 days session', NULL, NULL, '2026-09-14 23:56:52', '2026-09-14 23:56:52'),
('91745b3f-db4a-4502-b773-b5ea83d390d3', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'application', 'New Job Application Received', 'Mahesh applied for AI Developer.', '/hr/applications', NULL, '2026-10-27 01:42:04', '2026-10-27 01:42:04'),
('91b15e83-f7d3-47cf-b4e9-7c93f5a0b793', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'application', 'New Job Application Received', 'Mohan kumar applied for AI Developer.', '/hr/applications', NULL, '2026-10-26 19:51:37', '2026-10-26 19:51:37'),
('93a1e08a-959f-412c-8564-383e3e51852b', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'announcement', 'Title: Q3 Alumni Meet, Audience: Everyone', 'FDFDFD', NULL, NULL, '2026-10-26 23:04:03', '2026-10-26 23:04:03'),
('9439f39a-c982-4639-ad3e-2a057549001f', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'job', 'New job posted: AI Developer', 'VKollab Technologies · Guntur', '/alumni/jobs', NULL, '2026-10-26 19:40:52', '2026-10-26 19:40:52'),
('94bc3820-b0ed-4c27-85f8-e7b9bd7f434b', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'alumni', 'Alumni Added', 'Harsha added Mohan kumar (ALM1006) to the alumni roster.', '/super-admin/alumni-master', NULL, '2026-10-26 19:32:07', '2026-10-26 19:32:07'),
('96255463-8dc0-4baf-bf64-badca99572d5', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'job', 'New job posted: SAP CPI DEVELOPER', 'VKollab Technologies · Hyderabad', '/alumni/jobs', '2026-09-18 23:40:27', '2026-09-15 20:02:15', '2026-09-18 23:40:27'),
('96259dc8-c7db-419a-83f1-f1a5933b385a', '01a09277-60bd-71b1-9fad-a03afb7db784', 'document', 'Document Download Activity Detected', 'Monish downloaded Salary Slips — FY 2025-2026.', '/hr/documents', NULL, '2026-09-16 19:06:14', '2026-09-16 19:06:14'),
('966392c5-e31a-4f6b-a1d8-d253b77f3bb5', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'alumni', 'Alumni Master Imported', 'Super Admin imported alumni-master-template (1).xlsx: 0 created, 1 updated, 0 failed.', '/super-admin/alumni-master', NULL, '2026-09-21 21:52:02', '2026-09-21 21:52:02'),
('9d10c5df-fa4b-4561-8d1b-684dd05abc2c', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'job', 'New job posted: AI Developer', 'VKollab Technologies · Guntur', '/alumni/jobs', '2026-10-26 21:53:17', '2026-10-26 19:40:52', '2026-10-26 21:53:17'),
('9f678db3-4595-49cc-b358-171178d484a8', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'alumni', 'Alumni Master Imported', 'Super Admin imported alumni-master-template.xlsxs.xlsx: 0 created, 1 updated, 0 failed.', '/super-admin/alumni-master', NULL, '2026-10-27 02:52:02', '2026-10-27 02:52:02'),
('a1d46320-3018-4378-a4fb-18d50d371af2', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'document', 'Document Deleted', 'Super Admin deleted \"updated Full & Final Settlement\" belonging to Mahesh.', '/super-admin/dashboard', NULL, '2026-09-29 21:10:32', '2026-09-29 21:10:32'),
('a86eccdf-c619-432a-84b0-b0bb1e975a4a', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'document', 'Document Download Activity Detected', 'Mahesh downloaded updated Full & Final Settlement.', '/hr/documents', NULL, '2026-09-29 21:10:09', '2026-09-29 21:10:09'),
('aaf9eeca-b9dd-4967-b1e3-6d165aeb4fe1', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'announcement', 'Title: Q3 Alumni Meet, Audience: Everyone', 'FDFDFD', NULL, NULL, '2026-10-26 23:04:03', '2026-10-26 23:04:03'),
('ac054d4d-57e2-41f1-8294-5f44120be237', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'announcement', 'Title: Q3 Alumni Meet, Audience: Everyone', 'FDFDFD', NULL, NULL, '2026-10-26 23:04:03', '2026-10-26 23:04:03'),
('acc646a3-fc5c-4138-8bb7-cb7bca1c0048', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'alumni', 'Alumni Master Imported', 'Super Admin imported alumni-master-template.xlsx: 1 created, 0 updated, 0 failed.', '/super-admin/alumni-master', NULL, '2026-09-18 22:26:02', '2026-09-18 22:26:02'),
('ad4b9398-e54b-4121-9647-55de206cb00e', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'alumni', 'Alumni Master Imported', 'Harsha imported alumni-master-2026-09-14.xlsx: 0 created, 3 updated, 0 failed.', '/super-admin/alumni-master', NULL, '2026-09-16 18:49:02', '2026-09-16 18:49:02'),
('afea3dfd-e0c7-4140-922f-8c25f370c333', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'job', 'New job posted: AI Developer Position', 'VKollab Technologies · Guntur - Andhra Pradesh', '/alumni/jobs', NULL, '2026-10-27 01:49:34', '2026-10-27 01:49:34'),
('b2f3612b-bc3b-46e3-91eb-e26e6600133a', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'alumni', 'Alumni Master Imported', 'Super Admin imported alumni-master-template (1).xlsx: 0 created, 1 updated, 0 failed.', '/super-admin/alumni-master', NULL, '2026-09-21 21:52:02', '2026-09-21 21:52:02'),
('b35acc9d-f548-447b-a3a1-a42e18f6ca8e', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'system', 'Platform Update', 'Super Admin updated platform settings.', '/hr/settings', NULL, '2026-10-27 02:13:56', '2026-10-27 02:13:56'),
('b3b8912e-09b2-4a13-8f77-24fc2d203090', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'job', 'Job Posted', 'MANIKANTA posted \"SAP CPI DEVELOPER\".', '/super-admin/dashboard', NULL, '2026-09-15 20:02:15', '2026-09-15 20:02:15'),
('b7bb0cc7-d30f-40d7-b52d-94b119d7284a', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'system', 'Platform Update', 'Super Admin updated platform settings.', '/hr/settings', NULL, '2026-10-27 02:13:51', '2026-10-27 02:13:51'),
('ba13ebfb-3cd8-4e3c-a7ec-9e13c2ea46bb', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'application', 'New Job Application Received', 'Monish applied for Supply chain management.', '/hr/applications', NULL, '2026-09-16 20:39:10', '2026-09-16 20:39:10'),
('bb1b40d5-f082-4b85-882e-b6af025c4f62', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'system', 'Platform Update', 'Super Admin updated platform settings.', '/hr/settings', NULL, '2026-09-17 01:10:32', '2026-09-17 01:10:32'),
('bb6cd811-de7e-4e78-9d03-1830f4df9e99', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'job', 'New job posted: AI Developer', 'VKollab Technologies · Guntur', '/alumni/jobs', NULL, '2026-10-26 19:40:52', '2026-10-26 19:40:52'),
('bed85437-7716-4373-9e18-7396a7930179', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'application', 'New Job Application Received', 'Mahesh applied for AI Developer.', '/hr/applications', NULL, '2026-10-27 01:42:04', '2026-10-27 01:42:04'),
('bfb9838a-a0e8-4356-a739-0487bc31ee91', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'application', 'New Job Application Received', 'Mohan kumar applied for AI Developer.', '/hr/applications', NULL, '2026-10-26 19:51:37', '2026-10-26 19:51:37'),
('c29b7fe7-a637-4ed0-90c6-ec1bf7453f25', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'alumni', 'Alumni Master Imported', 'Harsha imported alumni-master-2026-09-14.xlsx: 0 created, 3 updated, 0 failed.', '/super-admin/alumni-master', NULL, '2026-09-16 18:49:02', '2026-09-16 18:49:02'),
('ca5a357d-5a0d-4707-bbeb-a198fb4c0eb2', '01a09277-60bd-71b1-9fad-a03afb7db784', 'document', 'Document Download Activity Detected', 'Monish downloaded Salary Slips — FY 2025-2026.', '/hr/documents', NULL, '2026-09-18 23:37:48', '2026-09-18 23:37:48'),
('ca759bc8-553d-4a9e-a5ff-18d8ce269d24', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'document', 'Document Download Activity Detected', 'Mahesh downloaded Increment Letter.', '/hr/documents', NULL, '2026-10-27 01:43:27', '2026-10-27 01:43:27'),
('cb172702-c3c0-4c5b-9a83-1052bac51a88', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'job', 'New job posted: AI Developer Position', 'VKollab Technologies · Guntur - Andhra Pradesh', '/alumni/jobs', NULL, '2026-10-27 01:49:34', '2026-10-27 01:49:34'),
('cbb8fd8f-9ac4-49a1-b2bd-830f204a01ee', '01a09277-60bd-71b1-9fad-a03afb7db784', 'document', 'Document Download Activity Detected', 'Mohan kumar downloaded Salary Slips — FY 2026-2027.', '/hr/documents', NULL, '2026-10-26 19:37:25', '2026-10-26 19:37:25'),
('ce84b1a0-d214-4aef-bce0-7f347f517a4b', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'alumni', 'Alumni Added', 'MANIKANTA added Manikanta (8583002) to the alumni roster.', '/super-admin/alumni-master', NULL, '2026-09-12 22:15:01', '2026-09-12 22:15:01'),
('d10436cb-3cff-49ff-b499-6376afc05125', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'announcement', 'Announcement Published', 'MANIKANTA published \"Microsoft copilot session\".', '/super-admin/dashboard', '2026-09-15 00:49:05', '2026-09-14 23:56:52', '2026-09-15 00:49:05'),
('d3c2d169-3ad2-44fc-af86-fbafadcd5ddf', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'job', 'Job Posted', 'MANIKANTA posted \"SAP CPI DEVELOPER\".', '/super-admin/dashboard', NULL, '2026-09-15 20:02:15', '2026-09-15 20:02:15'),
('d856faac-a98a-4059-8d5d-cc17ce12f85d', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'system', 'Platform Update', 'Super Admin updated platform settings.', '/hr/settings', NULL, '2026-09-18 23:08:30', '2026-09-18 23:08:30'),
('dc5da116-928a-44fd-8776-3c6cea86cd64', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'document', 'Document Download Activity Detected', 'Monish downloaded Salary Slips — FY 2025-2026.', '/hr/documents', NULL, '2026-09-18 23:37:43', '2026-09-18 23:37:43'),
('dcadcab8-1ad1-4643-b479-16fee0d90708', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'alumni', 'Alumni Master Imported', 'Harsha imported alumni-master-2026-09-14.xlsx: 0 created, 3 updated, 0 failed.', '/super-admin/alumni-master', NULL, '2026-09-16 18:45:04', '2026-09-16 18:45:04'),
('de4effc3-6c05-4b98-8ac2-27fd3b14416e', '01a09277-60bd-71b1-9fad-a03afb7db784', 'application', 'New Job Application Received', 'Monish applied for Supply chain management.', '/hr/applications', NULL, '2026-09-16 20:39:10', '2026-09-16 20:39:10'),
('de6ba6be-345f-4698-841a-ed9b9ff2823e', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'system', 'Platform Update', 'Super Admin updated platform settings.', '/hr/settings', NULL, '2026-09-18 23:08:31', '2026-09-18 23:08:31'),
('dfe2077f-d10b-4c50-9faa-e1eec9b032cf', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'alumni', 'Alumni Master Imported', 'Super Admin imported alumni-master-template.xlsxs.xlsx: 0 created, 1 updated, 0 failed.', '/super-admin/alumni-master', NULL, '2026-10-27 02:54:05', '2026-10-27 02:54:05'),
('e559bf41-9550-452b-8ced-5a89f1b131cc', '01a09277-60bd-71b1-9fad-a03afb7db784', 'system', 'Platform Update', 'Super Admin updated platform settings.', '/hr/settings', '2026-09-13 00:58:25', '2026-09-12 23:53:30', '2026-09-13 00:58:25'),
('e95243d2-7846-4f68-bb4f-1eb4bfb6265d', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'announcement', 'Microsoft copilot', 'fhdhfdf', NULL, NULL, '2026-09-16 04:35:43', '2026-09-16 04:35:43'),
('eb2f7ab9-93be-4fed-b4b7-9249668e63b7', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'alumni', 'Alumni Added', 'Harsha added Sachin Tendulkar (ALM2003) to the alumni roster.', '/super-admin/alumni-master', NULL, '2026-10-26 23:08:53', '2026-10-26 23:08:53'),
('eb9e2c27-8c81-444f-921d-5d14d83ade4b', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'announcement', 'Microsoft copilot', 'fhdhfdf', NULL, '2026-09-18 23:40:27', '2026-09-16 04:35:43', '2026-09-18 23:40:27'),
('ed903f36-9f04-48ad-9983-d2029af93f76', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'announcement', 'Announcement Published', 'Harsha published \"Microsoft copilot\".', '/super-admin/dashboard', NULL, '2026-09-16 04:35:43', '2026-09-16 04:35:43'),
('f2ae5af4-3748-4e9f-a6aa-d78cf06d25b5', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'system', 'Platform Update', 'Super Admin updated platform settings.', '/hr/settings', NULL, '2026-09-18 23:07:29', '2026-09-18 23:07:29'),
('f4bf5dbc-3ad9-4117-8ec6-26847fadf8fd', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'job', 'Job Posted', 'Harsha posted \"AI Developer\".', '/super-admin/dashboard', NULL, '2026-10-26 19:40:52', '2026-10-26 19:40:52'),
('f4f8c5c1-6ac9-4bb6-8285-c943212f8092', '01a09277-60bd-71b1-9fad-a03afb7db784', 'system', 'Platform Update', 'Super Admin updated platform settings.', '/hr/settings', NULL, '2026-10-27 02:13:51', '2026-10-27 02:13:51'),
('f50d0360-b317-4d83-b5eb-876774ff9f04', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'alumni', 'Alumni Master Imported', 'Super Admin imported alumni-master-template.xlsxs.xlsx: 0 created, 1 updated, 0 failed.', '/super-admin/alumni-master', NULL, '2026-10-27 02:52:02', '2026-10-27 02:52:02'),
('f9f0023b-a39e-490e-a761-c10c513fbc17', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'announcement', 'Title: Q3 Alumni Meet, Audience: Everyone', 'FDFDFD', NULL, NULL, '2026-10-26 23:04:03', '2026-10-26 23:04:03'),
('fc7230e8-df36-43eb-a844-b5f92a40c557', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'system', 'Platform Update', 'Super Admin updated platform settings.', '/hr/settings', NULL, '2026-09-17 01:10:46', '2026-09-17 01:10:46');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` char(36) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `actor_name` varchar(120) DEFAULT NULL,
  `actor_role` varchar(32) DEFAULT NULL,
  `action` varchar(64) NOT NULL,
  `status` varchar(16) NOT NULL DEFAULT 'success',
  `module` varchar(40) NOT NULL,
  `subject_type` varchar(120) DEFAULT NULL,
  `subject_id` char(36) DEFAULT NULL,
  `meta` longtext DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `actor_name`, `actor_role`, `action`, `status`, `module`, `subject_type`, `subject_id`, `meta`, `ip_address`, `user_agent`, `created_at`) VALUES
('01a09259-9ea1-7157-846b-d9390b5c6128', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-12 04:22:21'),
('01a09267-b27f-70fc-a057-ab77ebd2abdf', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-12 04:37:44'),
('01a09268-2515-7248-970d-9cae518b10b5', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-12 04:38:13'),
('01a09277-60cf-71f2-8c49-6292189771d8', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'hr_admin.created', 'success', 'hr', NULL, '01a09277-60bd-71b1-9fad-a03afb7db784', '{\"name\":\"MANIKANTA\",\"email\":\"kgsmanikanta1010@gmail.com\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-12 04:54:52'),
('01a09277-60d8-7371-beaa-b50df5fe9e27', NULL, NULL, NULL, 'email.admin_welcome_sent', 'success', 'hr', NULL, NULL, '{\"to\":\"kg***@gmail.com\",\"role\":\"hr_admin\"}', NULL, NULL, '2026-09-12 04:54:52'),
('01a09279-2880-71e4-89f4-5ab6bc694b1f', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"kg***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-12 04:56:48'),
('01a09279-911a-7033-8227-845378412fa0', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '2401:4900:9700:65a8:2443:30ff:fe99:5777', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Mobile Safari/537.36', '2026-09-12 04:57:15'),
('01a09557-e933-70dc-9774-075c481a595e', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-12 18:19:21'),
('01a095f1-056a-70c3-bc06-5ad34a6c5d39', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-12 21:06:35'),
('01a095fc-43f1-7197-9b71-4f506319769c', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-12 21:18:52'),
('01a095fc-e1ef-731c-9fd9-25ed032e826d', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-12 21:19:33'),
('01a09604-57ff-700b-9ef0-f24f73bc0e5d', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-12 21:27:42'),
('01a09608-2913-7296-868a-be1e6d03c417', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-12 21:31:52'),
('01a09608-e22e-72fa-b2a8-a798e80c3f00', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-12 21:32:39'),
('01a09609-20b8-7298-af3d-d7bae913cc45', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-12 21:32:55'),
('01a09609-afe9-7021-a054-519fc6c847d2', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-12 21:33:32'),
('01a0960d-54c2-70bc-ac54-84608a1b4b20', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"kg***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-12 21:37:31'),
('01a0960e-3e46-739b-95ae-18065b382629', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 21:38:30'),
('01a0960f-4941-7350-9257-f8ded64b229a', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'permission.user_updated', 'success', 'permissions', NULL, '01a09277-60bd-71b1-9fad-a03afb7db784', '{\"user\":\"MANIKANTA\",\"count\":24}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-12 21:39:39'),
('01a09610-369e-736a-bac9-7dfcfbca3559', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'permission.user_updated', 'success', 'permissions', NULL, '01a09277-60bd-71b1-9fad-a03afb7db784', '{\"user\":\"MANIKANTA\",\"count\":46}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-12 21:40:40'),
('01a09611-148f-71a9-a22b-b0bfee3d3d42', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'permission.user_updated', 'success', 'permissions', NULL, '01a09277-60bd-71b1-9fad-a03afb7db784', '{\"user\":\"MANIKANTA\",\"count\":45}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-12 21:41:36'),
('01a09611-96c5-70c5-a6b5-28f0466a5b4b', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'permission.user_updated', 'success', 'permissions', NULL, '01a09277-60bd-71b1-9fad-a03afb7db784', '{\"user\":\"MANIKANTA\",\"count\":45}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-12 21:42:10'),
('01a09615-e8b9-715d-bf9b-5ec302e3c565', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni_master.created', 'success', 'alumni', NULL, '01a09615-e8b5-71c2-9a38-99288b86a3e1', '{\"vkollabId\":\"8583001\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 21:46:53'),
('01a09615-e8c1-73b5-86d0-d6d51755c5b6', '01a09277-60bd-71b1-9fad-a03afb7db784', NULL, NULL, 'email.invitation_sent', 'success', 'alumni', 'App\\Models\\AlumniMaster', '01a09615-e8b5-71c2-9a38-99288b86a3e1', '{\"email\":\"kg***@gmail.com\",\"source\":\"manual\",\"status\":\"queued\"}', NULL, NULL, '2026-09-12 21:46:53'),
('01a09615-e8c5-727e-abc8-4e41ef4bd3f0', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"alumni\",\"title\":\"Alumni Added\",\"roles\":[\"super_admin\"],\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 21:46:53'),
('01a09616-6294-71a1-833b-508af956ad39', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 21:47:24'),
('01a09618-0fa9-7234-bd1d-2017f69462f1', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"kg***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-12 21:49:14'),
('01a09619-3937-7333-95a7-ad93d687f4d4', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 21:50:30'),
('01a09619-83df-7365-b027-afe733ca7fa3', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni_master.updated', 'success', 'alumni', NULL, '01a09615-e8b5-71c2-9a38-99288b86a3e1', '{\"vkollabId\":\"8583001\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 21:50:49'),
('01a0961b-4d98-7013-8aab-a6a1154bdca5', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 21:52:46'),
('01a0962c-9bfd-72bb-8775-9c84ebe2577f', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"kg***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-12 22:11:41'),
('01a0962d-c152-7039-9559-a69c6f1454e8', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:12:56'),
('01a0962d-e9b5-70e4-a71b-4b6a85d80bff', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni_master.deleted', 'success', 'alumni', NULL, '01a09615-e8b5-71c2-9a38-99288b86a3e1', '{\"vkollabId\":\"8583001\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:13:06'),
('01a0962d-e9b9-70c5-8156-671918f1e554', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"alumni\",\"title\":\"Roster Record Deleted\",\"roles\":[\"super_admin\"],\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:13:06'),
('01a0962f-aa3f-71cf-b3c7-597e7bec72ca', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni_master.created', 'success', 'alumni', NULL, '01a0962f-aa3b-708c-86fe-9e447f456fee', '{\"vkollabId\":\"8583002\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:15:01'),
('01a0962f-aa4e-7214-9c75-33fd005e6564', '01a09277-60bd-71b1-9fad-a03afb7db784', NULL, NULL, 'email.invitation_sent', 'success', 'alumni', 'App\\Models\\AlumniMaster', '01a0962f-aa3b-708c-86fe-9e447f456fee', '{\"email\":\"ge***@gmail.com\",\"source\":\"manual\",\"status\":\"queued\"}', NULL, NULL, '2026-09-12 22:15:01'),
('01a0962f-aa54-719e-bb75-cbb2e8b4f813', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"alumni\",\"title\":\"Alumni Added\",\"roles\":[\"super_admin\"],\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:15:01'),
('01a09631-03be-73a5-80ca-cf669d45a9a9', NULL, 'Manikanta', NULL, 'email.registration_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"ge***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-12 22:16:29'),
('01a09633-4a76-71a8-82db-0c5902aa9f44', NULL, 'Manikanta', NULL, 'email.registration_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"ge***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-12 22:18:58'),
('01a0963a-2725-7348-9ac9-86cb4c338e67', NULL, 'Manikanta', NULL, 'email.registration_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"ge***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-12 22:26:28'),
('01a0963f-2515-7025-8b1f-818ff4a1d53c', NULL, 'Manikanta', NULL, 'email.registration_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"ge***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-12 22:31:55'),
('01a0963f-fbac-73d0-8463-2262d24cfe66', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'Manikanta', 'alumni', 'auth.registered', 'success', 'auth', NULL, NULL, '{\"vkollabId\":\"8583002\",\"termsAccepted\":true,\"privacyAccepted\":true}', '2401:4900:96ea:410f:419b:6e6c:16c7:87cd', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Mobile Safari/537.36', '2026-09-12 22:32:50'),
('01a0963f-fbb5-735a-a0f1-af987d778b65', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'Manikanta', NULL, 'email.welcome_sent', 'success', 'auth', 'App\\Models\\User', '01a0963f-fb96-72f9-b366-52fd81d9d235', '{\"email\":\"ge***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-12 22:32:50'),
('01a09640-471c-72c0-9636-f47275e03444', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'Manikanta', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"ge***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-12 22:33:10'),
('01a09642-069f-70f3-94ef-49819b5efa1d', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'Manikanta', 'alumni', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"alumni\"}', '2401:4900:96ea:410f:419b:6e6c:16c7:87cd', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Mobile Safari/537.36', '2026-09-12 22:35:04'),
('01a09642-fc8a-7286-9c3a-d85c8e49c3eb', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0963f-fb96-72f9-b366-52fd81d9d235', '{\"alumnus\":\"Manikanta\",\"changes\":{\"dashboardEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:36:07'),
('01a09643-0279-729b-90ee-eac12b75f199', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0963f-fb96-72f9-b366-52fd81d9d235', '{\"alumnus\":\"Manikanta\",\"changes\":{\"dashboardEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:36:09'),
('01a09643-09f9-72e7-b8d6-c48904bfea3f', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0963f-fb96-72f9-b366-52fd81d9d235', '{\"alumnus\":\"Manikanta\",\"changes\":{\"announcementsEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:36:11'),
('01a09643-0cf1-71d2-af8a-3e03b2226b76', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0963f-fb96-72f9-b366-52fd81d9d235', '{\"alumnus\":\"Manikanta\",\"changes\":{\"tickerEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:36:11'),
('01a09643-11ec-731b-ab42-1d23bbef5e66', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0963f-fb96-72f9-b366-52fd81d9d235', '{\"alumnus\":\"Manikanta\",\"changes\":{\"notificationsEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:36:13'),
('01a09643-13e7-7080-aac0-c4332e0f1e41', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0963f-fb96-72f9-b366-52fd81d9d235', '{\"alumnus\":\"Manikanta\",\"changes\":{\"applicationsEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:36:13'),
('01a09643-185b-73cf-ad22-39514b956b2e', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0963f-fb96-72f9-b366-52fd81d9d235', '{\"alumnus\":\"Manikanta\",\"changes\":{\"jobsEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:36:14'),
('01a09643-1be7-737b-92e5-a2a5150e386e', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0963f-fb96-72f9-b366-52fd81d9d235', '{\"alumnus\":\"Manikanta\",\"changes\":{\"documentsEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:36:15'),
('01a09643-2294-720d-b9cc-02936b528f06', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0963f-fb96-72f9-b366-52fd81d9d235', '{\"alumnus\":\"Manikanta\",\"changes\":{\"profileEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:36:17'),
('01a09643-25d3-711a-88d6-bdac637c7fcd', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0963f-fb96-72f9-b366-52fd81d9d235', '{\"alumnus\":\"Manikanta\",\"changes\":{\"downloadsEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:36:18'),
('01a09643-2883-7270-9acb-945a90bb8d0e', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0963f-fb96-72f9-b366-52fd81d9d235', '{\"alumnus\":\"Manikanta\",\"changes\":{\"profileEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:36:18'),
('01a09643-7658-70f0-a279-760e3f96a529', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0963f-fb96-72f9-b366-52fd81d9d235', '{\"alumnus\":\"Manikanta\",\"changes\":{\"downloadsEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:36:38'),
('01a09643-7a6c-7073-a6ae-d435f59998d4', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0963f-fb96-72f9-b366-52fd81d9d235', '{\"alumnus\":\"Manikanta\",\"changes\":{\"documentsEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:36:39'),
('01a09643-849a-70ee-8fcf-c3c230f04a2e', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0963f-fb96-72f9-b366-52fd81d9d235', '{\"alumnus\":\"Manikanta\",\"changes\":{\"jobsEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:36:42'),
('01a09643-9e78-71bd-b00f-b21b64820a27', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0963f-fb96-72f9-b366-52fd81d9d235', '{\"alumnus\":\"Manikanta\",\"changes\":{\"applicationsEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:36:49'),
('01a09643-a2d4-712c-87b6-29b62a4e2f22', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0963f-fb96-72f9-b366-52fd81d9d235', '{\"alumnus\":\"Manikanta\",\"changes\":{\"notificationsEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:36:50'),
('01a09643-a643-71df-abcb-4cc3b7ece2af', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0963f-fb96-72f9-b366-52fd81d9d235', '{\"alumnus\":\"Manikanta\",\"changes\":{\"tickerEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:36:51'),
('01a09643-a862-71cf-8754-b85ebb5f1305', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0963f-fb96-72f9-b366-52fd81d9d235', '{\"alumnus\":\"Manikanta\",\"changes\":{\"announcementsEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:36:51'),
('01a09644-7191-7370-a4d4-61fa3e7f84e0', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'permission.role_updated', 'success', 'permissions', NULL, NULL, '{\"role\":\"alumni\",\"count\":12}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-12 22:37:43'),
('01a09644-d81e-71f8-ab38-ce464590ec2a', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'permission.role_updated', 'success', 'permissions', NULL, NULL, '{\"role\":\"hr_admin\",\"count\":46}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-12 22:38:09'),
('01a09645-055d-723c-b9bf-e9fba354deb1', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0963f-fb96-72f9-b366-52fd81d9d235', '{\"alumnus\":\"Manikanta\",\"changes\":{\"profileEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:38:20'),
('01a09646-4e13-7103-a69b-012970b28c52', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0963f-fb96-72f9-b366-52fd81d9d235', '{\"alumnus\":\"Manikanta\",\"changes\":{\"profileEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:39:45'),
('01a09646-d03d-72b1-9545-b7b6e4520318', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:40:18'),
('01a09647-24eb-706e-bcfc-8b0755eb5085', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'Manikanta', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"ge***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-12 22:40:40'),
('01a09647-d3f2-7237-a9ac-e26a24122aa5', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'Manikanta', 'alumni', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-12 22:41:24'),
('01a09683-a613-73c1-a3e4-5fc4060a576f', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-12 23:46:45'),
('01a09689-d600-72c0-a7c6-35624c13d08f', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'settings.updated', 'success', 'settings', NULL, NULL, '{\"changed\":[\"alumni_document_email_enabled\"]}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-12 23:53:30'),
('01a09689-d605-73bf-b9f5-6ba7a40f9525', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"system\",\"title\":\"Platform Update\",\"roles\":[\"hr_admin\"],\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-12 23:53:30'),
('01a0969f-c03d-70dc-946c-d48d2b371898', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-13 00:17:26'),
('01a096a0-f7e3-7064-a783-51286d434abd', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-13 00:18:46'),
('01a096a1-ed1d-7317-8b25-899dec76c6ad', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-13 00:19:49'),
('01a096bb-62bf-71b2-8e60-ad708c5647f3', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'content.updated', 'success', 'content', NULL, 'b77ca255-ead9-409c-b814-e76796cc17ba', '{\"key\":\"privacy\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-13 00:47:38'),
('01a096bb-62c3-7038-aeed-36ed46e8b12e', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"system\",\"title\":\"Platform Update\",\"roles\":[\"hr_admin\"],\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-13 00:47:38'),
('01a096bb-fe2c-7233-bf11-8a8f337a7b9d', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'content.updated', 'success', 'content', NULL, '8346e495-dcd1-4764-973f-843662038b6f', '{\"key\":\"terms\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-13 00:48:17'),
('01a096bb-fe30-718f-acc8-d773aa295d4a', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"system\",\"title\":\"Platform Update\",\"roles\":[\"hr_admin\"],\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-13 00:48:17'),
('01a096c3-928d-7040-b439-aba011410b70', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'Manikanta', 'alumni', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-13 00:56:34'),
('01a096c3-eeea-72a7-ac5d-a6d66b5ba82f', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"kg***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-13 00:56:58'),
('01a096c4-6ccb-72ba-9079-8d604f186d83', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-13 00:57:30'),
('01a096c5-194b-73a5-a774-f83253af470c', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, '01a096c5-1945-72a7-b379-e2c89953e111', '{\"type\":\"document\",\"title\":\"New document available\",\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-13 00:58:14'),
('01a096c5-1957-7086-9932-63ed6454a675', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'document.uploaded', 'success', 'documents', NULL, '01a096c5-193a-7245-a6e7-c07d8a8913f1', '{\"title\":\"PAN\",\"owner\":\"Manikanta\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-13 00:58:14'),
('01a096c6-5e33-71f0-a15a-403986160bf1', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-13 00:59:37'),
('01a096c8-d547-707f-8253-e25d58726205', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'Manikanta', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"ge***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-13 01:02:19'),
('01a096ca-0cf5-73b8-a4bb-c6163c5aa0f7', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'Manikanta', 'alumni', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-13 01:03:39'),
('01a096ca-2912-7092-9d07-5f3eca7f9c58', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'Manikanta', 'alumni', 'document.emailed', 'success', 'documents', NULL, '01a096c5-193a-7245-a6e7-c07d8a8913f1', '{\"title\":\"PAN\",\"category\":\"Personal Documents\",\"to\":\"ge***@gmail.com\",\"delivery\":\"attachment\",\"sizeKb\":1362}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-13 01:03:46'),
('01a096ca-3f03-73cc-b0a7-49f3b80adaa7', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'Manikanta', 'alumni', 'document.downloaded', 'success', 'documents', NULL, '01a096c5-193a-7245-a6e7-c07d8a8913f1', '{\"title\":\"PAN\",\"category\":\"Personal Documents\",\"mode\":\"view\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-13 01:03:51'),
('01a096ca-3f0b-7337-9ebf-43562fc0bbd9', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'Manikanta', 'alumni', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"document\",\"title\":\"Document Download Activity Detected\",\"roles\":[\"hr_admin\"],\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-13 01:03:51'),
('01a09faa-18e9-72b7-bf4f-597b346b6142', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-14 18:25:19'),
('01a09fab-2993-721d-aa01-ef7ebff2af70', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-14 18:26:29'),
('01a09fe5-645e-7218-89ca-9b8c71b4c322', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"kg***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-14 19:30:05'),
('01a09fe6-ad06-7242-a761-6107da1fe123', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 19:31:30'),
('01a09fe8-3fea-728c-b7d6-e9f1e75f518b', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'job.created', 'success', 'jobs', NULL, '01a09fe8-3fe1-7397-a519-8f856698251a', '{\"title\":\"SAP ABAP DEVELOPER\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 19:33:13'),
('01a09fe8-3fef-7386-bcf7-d86af42c98e7', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"job\",\"title\":\"New job posted: SAP ABAP DEVELOPER\",\"roles\":[\"alumni\"],\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 19:33:13'),
('01a09fe8-3ff7-7206-9ac2-38989f06a0eb', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'ticker.created', 'success', 'announcements', NULL, '01a09fe8-3ff4-739f-b7ec-1371d3865073', '{\"message\":\"New job opportunity: SAP ABAP DEVELOPER at VKollab Technologies\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 19:33:13'),
('01a09fe8-3ffb-732d-a898-4141ec3f1dfb', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"job\",\"title\":\"Job Posted\",\"roles\":[\"super_admin\"],\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 19:33:13'),
('01a0a009-0bee-722e-803e-64d3d1cfdf32', '01a09277-60bd-71b1-9fad-a03afb7db784', NULL, NULL, 'email.invitation_sent', 'success', 'alumni', 'App\\Models\\AlumniMaster', '01a0a009-0bdc-7189-bb2d-0442223b2900', '{\"email\":\"cl***@gmail.com\",\"source\":\"import\",\"status\":\"queued\"}', NULL, NULL, '2026-09-14 20:09:02'),
('01a0a009-0bf3-71bf-bed3-60b898e87e9c', '01a09277-60bd-71b1-9fad-a03afb7db784', NULL, NULL, 'email.invitation_sent', 'success', 'alumni', 'App\\Models\\AlumniMaster', '01a0a009-0bf0-710e-b0e9-7794808b2c58', '{\"email\":\"ma***@gmail.com\",\"source\":\"import\",\"status\":\"queued\"}', NULL, NULL, '2026-09-14 20:09:02'),
('01a0a009-0bf6-7096-b66a-7985ac422eda', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'alumni_master.imported', 'success', 'alumni', NULL, '01a0a008-6e42-716b-bf77-e6029c05803c', '{\"file\":\"alumni-master-template.xlsx\",\"mode\":\"update\",\"created\":2,\"updated\":0,\"skipped\":0,\"failed\":0}', '49.205.164.180', NULL, '2026-09-14 20:09:02'),
('01a0a009-0c04-708f-b8ea-0424ba53618c', NULL, NULL, NULL, 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"alumni\",\"title\":\"Alumni Master Imported\",\"roles\":[\"super_admin\"],\"recipients\":1}', '127.0.0.1', 'Symfony', '2026-09-14 20:09:02'),
('01a0a00e-2220-738f-adb8-9c70e9d98c70', NULL, 'Monish', NULL, 'email.registration_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"cl***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-14 20:14:35'),
('01a0a00f-8e6c-73b1-94e6-db643e66c4bb', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'auth.registered', 'success', 'auth', NULL, NULL, '{\"vkollabId\":\"ALM1001\",\"termsAccepted\":true,\"privacyAccepted\":true}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 20:16:09'),
('01a0a00f-8e77-73b3-b0dc-b6e9e6c0d404', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', NULL, 'email.welcome_sent', 'success', 'auth', 'App\\Models\\User', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', '{\"email\":\"cl***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-14 20:16:09'),
('01a0a00f-dab8-72a0-bdb7-6ae29cf7f379', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"cl***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-14 20:16:28'),
('01a0a010-ed14-7076-b80c-9d69d1975994', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"cl***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-14 20:17:38'),
('01a0a011-a671-7125-ac01-5bbd0abfb9de', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 20:18:26'),
('01a0a011-b396-7252-a720-1642981e0825', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 20:18:29'),
('01a0a011-f25a-718e-8a25-89691b0271fd', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"cl***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-14 20:18:45'),
('01a0a012-8f9a-7287-82cc-83f7390d8a5a', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 20:19:26'),
('01a0a012-a1d3-717b-aa0a-ec363f6360b0', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 20:19:30'),
('01a0a012-a7d6-7081-975e-97fdfb545ac7', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 20:19:32'),
('01a0a012-ddc5-72a9-86db-6407253df970', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"cl***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-14 20:19:46'),
('01a0a013-6e87-7390-8509-af7fce2d07f2', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 20:20:23'),
('01a0a014-0d66-73ab-8c52-d932d92cd20c', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 20:21:03'),
('01a0a014-1f86-7103-a98a-2cc59f91648a', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 20:21:08'),
('01a0a014-2575-71db-ab68-b5882479c84e', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 20:21:10'),
('01a0a014-72eb-7182-bbba-356350355027', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"cl***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-14 20:21:29'),
('01a0a015-7534-71c7-ae04-9f48db91bfce', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 20:22:35'),
('01a0a016-61fd-710c-b4db-52e451400fc2', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 20:23:36'),
('01a0a01a-531a-7162-aeb8-dc6ddb3023ae', NULL, 'Bhargav', NULL, 'email.registration_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"ma***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-14 20:27:54'),
('01a0a02e-5708-7397-a7e6-2701500a154c', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"cl***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-14 20:49:46'),
('01a0a043-7811-7035-9826-b8eaf75a946e', NULL, 'Bhargav', NULL, 'email.registration_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"ma***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-14 21:12:51'),
('01a0a0d9-a34e-738f-a8dd-a4e624a40bc0', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'announcement.created', 'success', 'announcements', NULL, '01a0a0d9-a348-7166-8646-99f2bdc5fe38', '{\"title\":\"Microsoft copilot session\",\"published\":true}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 23:56:52'),
('01a0a0d9-a352-73dd-bc41-066b18d395cd', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"announcement\",\"title\":\"Microsoft copilot session\",\"roles\":[\"alumni\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 23:56:52'),
('01a0a0d9-a358-7368-9ed1-7a129920e8b5', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'ticker.created', 'success', 'announcements', NULL, '01a0a0d9-a356-7141-adf9-f4bbc4f9a561', '{\"message\":\"Microsoft copilot session\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 23:56:52'),
('01a0a0d9-a35f-7391-aa18-6ab03b27634e', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"announcement\",\"title\":\"Announcement Published\",\"roles\":[\"super_admin\"],\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 23:56:52'),
('01a0a0fb-d8a0-70f4-9871-9cfec07d522c', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'document_category.created', 'success', 'documents', NULL, '01a0a0fb-d89d-7199-a3d0-4e3dee08e178', '{\"name\":\"Reliving letter\",\"hrEnabled\":false}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 00:34:14'),
('01a0a0fc-2df2-710b-a5c4-bc279b16ef64', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document_category.hr_access_changed', 'success', 'documents', NULL, '01a0a0fb-d89d-7199-a3d0-4e3dee08e178', '{\"name\":\"Reliving letter\",\"hrEnabled\":true}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-15 00:34:36'),
('01a0a100-a4fa-73ed-af04-832153473dfc', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, '01a0a100-a4f8-70d9-bc5a-46607be4388f', '{\"type\":\"document\",\"title\":\"New document available\",\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-15 00:39:29'),
('01a0a100-a500-7164-9ce4-b2e43f18d3c8', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document.uploaded', 'success', 'documents', NULL, '01a0a100-a4f3-72df-b73b-26a00c502330', '{\"title\":\"Salary Slips \\u2014 FY 2025-2026\",\"owner\":\"Monish\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-15 00:39:29'),
('01a0a102-6ae7-7015-8669-cef4e97081a9', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_master.exported', 'success', 'alumni', NULL, NULL, '{\"filters\":[]}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-15 00:41:25'),
('01a0a105-e6c9-73e4-bbac-1cef6ae9803a', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'super_admin.created', 'success', 'platform', NULL, '01a0a105-e6be-7286-8bb1-38a359a6660c', '{\"name\":\"Manikanta\",\"email\":\"claudeai0801@gmail.com\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-15 00:45:13'),
('01a0a105-e6e8-7080-a1a2-dbb78c5756bd', NULL, NULL, NULL, 'email.admin_welcome_sent', 'success', 'platform', NULL, NULL, '{\"to\":\"cl***@gmail.com\",\"role\":\"super_admin\"}', NULL, NULL, '2026-09-15 00:45:13'),
('01a0a107-46c6-701b-a2c8-4d8cd1a52052', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'Manikanta', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"cl***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-15 00:46:43'),
('01a0a108-1a9e-7319-8a3e-43ee1966191f', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'Manikanta', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-15 00:47:38'),
('01a0a12d-b7b9-72ff-9e02-f3f2b59430f3', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 01:28:43'),
('01a0a15d-4276-73e7-879c-bfaba7b3eacd', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"kg***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-15 02:20:38'),
('01a0a175-7739-7289-ae4b-d46fc88ec15e', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', NULL, 'email.forgot_password_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"kg***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-15 02:47:05'),
('01a0a177-5d6b-71c7-a3b1-dc20bea80521', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'auth.password_reset', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 02:49:09'),
('01a0a177-72bb-7173-a161-fe0bb14111f4', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'auth.password_reset', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 02:49:15'),
('01a0a177-cd84-72d5-b1a8-4807d4428886', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"HR1002\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 02:49:38');
INSERT INTO `audit_logs` (`id`, `user_id`, `actor_name`, `actor_role`, `action`, `status`, `module`, `subject_type`, `subject_id`, `meta`, `ip_address`, `user_agent`, `created_at`) VALUES
('01a0a178-4b03-73e3-a3ce-9f0dba87ce9d', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"kg***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-15 02:50:10'),
('01a0a179-f00f-710d-8455-f171032c249b', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 02:51:58'),
('01a0a17a-188b-7331-b362-3ddc9615d2e6', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 02:52:08'),
('01a0a17a-ec30-737e-93d8-3a6b79ef4895', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 02:53:02'),
('01a0a17e-5be1-7239-a62c-447ffacd90c0', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-15 02:56:47'),
('01a0a17e-bdd8-729c-a4c2-4d1da90b08e2', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-15 02:57:13'),
('01a0a180-0c23-713c-a76a-43355e40d51d', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-15 02:58:38'),
('01a0a180-3b9e-7138-9e59-755a369780d1', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-15 02:58:50'),
('01a0a180-5224-7382-9403-ae9f9ba8e92d', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-15 02:58:56'),
('01a0a180-c3d0-7202-8b2f-fe24741003e7', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-15 02:59:25'),
('01a0a181-db32-7381-ad6b-ce8e3d7571e1', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-15 03:00:37'),
('01a0a182-27df-7052-9612-fac674466b86', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-15 03:00:56'),
('01a0a197-f185-7013-9205-a43616073879', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-15 03:24:44'),
('01a0a198-89fc-727d-852c-055a0a3c403b', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-15 03:25:23'),
('01a0a198-e52f-71be-819e-2b0ab89c4afa', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', '2026-09-15 03:25:47'),
('01a0a1ab-c011-70d9-97d2-b20a719e66bd', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"kg***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-15 03:46:22'),
('01a0a1ac-d70c-711d-9953-d140b0418942', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"kg***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-15 03:47:34'),
('01a0a1ad-261d-7021-ace3-d03d9dca1841', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 03:47:54'),
('01a0a1ad-acf2-7241-afb1-1c6598511de5', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-15 03:48:28'),
('01a0a1ae-9913-7005-8b7a-8cecb8a94b77', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"password_step_missing\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 03:49:29'),
('01a0a1af-86bd-732f-9556-72f93ba1e6c3', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"kg***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-15 03:50:30'),
('01a0a1b0-38fe-7150-8ac0-210c191426fc', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 03:51:15'),
('01a0a4ec-c3d9-739f-8a1f-3a8a0ff055bf', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"HR1002\",\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-15 18:56:15'),
('01a0a4ec-e4b5-7180-9abc-eecf3b526bd0', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"kg***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-15 18:56:23'),
('01a0a4ed-76ef-7295-b5ea-9705c9b5947e', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-15 18:57:01'),
('01a0a4fc-906c-7246-8567-5722b80f88bd', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-15 19:13:30'),
('01a0a4fd-16c0-71bb-a72f-ba4808c68184', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 19:14:04'),
('01a0a509-726c-7196-8553-c18894d6d6ab', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'document_category.updated', 'success', 'documents', NULL, '01a0a0fb-d89d-7199-a3d0-4e3dee08e178', '{\"name\":\"Reliving letters\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-15 19:27:34'),
('01a0a510-fe05-73c7-abf2-6e311584e532', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'document_category.deleted', 'success', 'documents', NULL, '01a0a0fb-d89d-7199-a3d0-4e3dee08e178', '{\"name\":\"Reliving letters\",\"documentsOrphaned\":0,\"forced\":false}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-15 19:35:49'),
('01a0a520-4d84-709d-a47c-113e9cb199a0', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'job.status_changed', 'success', 'jobs', NULL, '01a09fe8-3fe1-7397-a519-8f856698251a', '{\"status\":\"closed\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-15 19:52:32'),
('01a0a520-5bff-7271-a6f1-bc54a9de8802', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'job.status_changed', 'success', 'jobs', NULL, '01a09fe8-3fe1-7397-a519-8f856698251a', '{\"status\":\"open\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-15 19:52:36'),
('01a0a529-3384-7332-a1b7-89328f34f489', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'job.created', 'success', 'jobs', NULL, '01a0a529-3380-73f6-8e32-51b82bd4d066', '{\"title\":\"SAP CPI DEVELOPER\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-15 20:02:15'),
('01a0a529-338c-72aa-a7a4-0379aef77617', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"job\",\"title\":\"New job posted: SAP CPI DEVELOPER\",\"roles\":[\"alumni\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-15 20:02:15'),
('01a0a529-3392-7385-9bd0-b8ce4e823836', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'ticker.created', 'success', 'announcements', NULL, '01a0a529-338f-71ad-a669-0e69e77d6151', '{\"message\":\"New job opportunity: SAP CPI DEVELOPER at VKollab Technologies\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-15 20:02:15'),
('01a0a529-339c-7206-9b67-4b7ff95c3071', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"job\",\"title\":\"Job Posted\",\"roles\":[\"super_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-15 20:02:15'),
('01a0a56e-db17-7273-8f16-f805851d60e3', '01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'hr_admin', 'announcement.deleted', 'success', 'announcements', NULL, '01a0a0d9-a348-7166-8646-99f2bdc5fe38', '{\"title\":\"Microsoft copilot session\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-15 21:18:20'),
('01a0a655-8bc3-7140-bd93-2b0818265011', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-16 01:30:19'),
('01a0a655-de2b-73ce-a507-c8463e970dae', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 01:30:40'),
('01a0a6a7-849f-73c6-8d45-58faf9b7d97d', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-16 02:59:51'),
('01a0a6a7-e1a1-73ad-b398-d112c71ed00d', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 03:00:15'),
('01a0a6b5-3362-7336-aebc-d095de9b7826', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_master.created', 'success', 'alumni', NULL, '01a0a6b5-335b-71e6-ab88-817b99c6c069', '{\"vkollabId\":\"ALM1003\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 03:14:48'),
('01a0a6b5-3382-7322-804e-8f986c74697f', '01a09225-0284-7153-8eb0-bd4f0484a94a', NULL, NULL, 'email.invitation_sent', 'success', 'alumni', 'App\\Models\\AlumniMaster', '01a0a6b5-335b-71e6-ab88-817b99c6c069', '{\"email\":\"pa***@gmail.com\",\"source\":\"manual\",\"status\":\"queued\"}', NULL, NULL, '2026-09-16 03:14:48'),
('01a0a6b6-cec8-72cf-85b0-f193f09129cf', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 03:16:33'),
('01a0a6c0-6631-7231-a3cc-4c5c13957f4f', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-16 03:27:02'),
('01a0a6c0-c47a-7179-9ecc-7e76db1a4e92', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 03:27:26'),
('01a0a6c2-2b2a-725b-b60f-1abcd93de8d1', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'hr_admin.created', 'success', 'hr', NULL, '01a0a6c2-2b02-7173-8043-3439b8d31b03', '{\"name\":\"Harsha\",\"email\":\"patlurimaheshbabu756@gmail.com\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 03:28:58'),
('01a0a6c2-2b37-7180-9eb3-cff62e3947df', NULL, NULL, NULL, 'email.admin_welcome_sent', 'success', 'hr', NULL, NULL, '{\"to\":\"pa***@gmail.com\",\"role\":\"hr_admin\"}', NULL, NULL, '2026-09-16 03:28:58'),
('01a0a6d6-9fa9-73ec-918a-d27057dbdfbb', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-16 03:51:18'),
('01a0a6d6-ec29-7357-846e-2de21dfcbc21', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '2401:4900:cbdc:7318:885e:a43a:1d74:3f33', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/148.0.7778.166 Mobile/15E148 Safari/604.1', '2026-09-16 03:51:38'),
('01a0a6d7-f325-7005-ac85-0b2d47556c9b', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-16 03:52:45'),
('01a0a6da-3730-7343-a024-0fe14786b681', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 03:55:13'),
('01a0a6da-7572-712b-aac6-5667209fca2a', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"SA1001\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 03:55:29'),
('01a0a6de-28ee-7378-b43f-39a0e9686880', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.forgot_password_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-16 03:59:32'),
('01a0a6e7-c76e-700b-890d-868e6379baf3', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"password_step_missing\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 04:10:02'),
('01a0a6e8-abe7-7060-ba07-fc9d2391807f', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-16 04:11:01'),
('01a0a6e9-0205-72a4-81f7-63288b826c29', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 04:11:23'),
('01a0a6f0-32c1-73c5-b143-5088c9366a91', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'document_category.created', 'success', 'documents', NULL, '01a0a6f0-32bd-706e-89e1-a98cf8c0c7c7', '{\"name\":\"Reliving letters\",\"hrEnabled\":false}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 04:19:14'),
('01a0a6f3-72dd-73a1-956c-85b8387a855c', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'document_category.updated', 'success', 'documents', NULL, '01a09205-3e90-7283-8886-192598f5b9a4', '{\"name\":\"updated Full & Final Settlement\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 04:22:47'),
('01a0a6f8-a535-72a5-9ac7-ae8e03862c80', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'job.updated', 'success', 'jobs', NULL, '01a0a529-3380-73f6-8e32-51b82bd4d066', '{\"title\":\"SAP CPI DEVELOPER\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 04:28:28'),
('01a0a6f9-f911-7320-b613-bcf6e1385bc8', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'job.status_changed', 'success', 'jobs', NULL, '01a0a529-3380-73f6-8e32-51b82bd4d066', '{\"status\":\"closed\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 04:29:55'),
('01a0a6fa-e9f5-7199-ad71-a3b0c7bd512b', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'job.deleted', 'success', 'jobs', NULL, '01a09fe8-3fe1-7397-a519-8f856698251a', '{\"title\":\"SAP ABAP DEVELOPER\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 04:30:56'),
('01a0a6fb-e730-70b4-86c8-d217a8baf95a', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'job.created', 'success', 'jobs', NULL, '01a0a6fb-e729-71a6-acea-20ca3960fa68', '{\"title\":\"Supply chain management\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 04:32:01'),
('01a0a6fb-e73e-7388-9bf7-77474013aa31', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"job\",\"title\":\"New job posted: Supply chain management\",\"roles\":[\"alumni\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 04:32:01'),
('01a0a6fb-e775-7399-88ea-8ddd4762be50', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'ticker.created', 'success', 'announcements', NULL, '01a0a6fb-e769-7145-958b-f364e7f32f7f', '{\"message\":\"New job opportunity: Supply chain management at VKollab Technologies\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 04:32:01'),
('01a0a6fb-e781-72fb-82da-d6b3c67e5e51', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"job\",\"title\":\"Job Posted\",\"roles\":[\"super_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 04:32:01'),
('01a0a6ff-48b6-7043-bf16-1204bf73bba5', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'announcement.created', 'success', 'announcements', NULL, '01a0a6ff-48af-7328-8d11-0db40283185f', '{\"title\":\"Microsoft copilot\",\"published\":true}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 04:35:43'),
('01a0a6ff-48bc-7287-8f4d-35f7422a2f3c', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"announcement\",\"title\":\"Microsoft copilot\",\"roles\":[\"alumni\",\"hr_admin\"],\"recipients\":4}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 04:35:43'),
('01a0a6ff-48c2-73be-8c31-6e67b75e4fbf', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'ticker.created', 'success', 'announcements', NULL, '01a0a6ff-48c0-7271-9451-8c59cd739759', '{\"message\":\"Microsoft copilot\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 04:35:43'),
('01a0a6ff-48c6-7383-902d-2063adf29e22', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"announcement\",\"title\":\"Announcement Published\",\"roles\":[\"super_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 04:35:43'),
('01a0a9ee-4372-71de-a4ab-5fdfc6b91533', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"ALM1001\",\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 18:15:59'),
('01a0a9f0-cb53-7050-8e74-7a1dfc20d88e', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"HR1001\",\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 18:18:45'),
('01a0a9f2-acf0-72bc-9368-8972f75c8caa', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-16 18:20:48'),
('01a0a9f2-fba0-71ad-a1bd-f53e7d890328', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 18:21:08'),
('01a0a9f5-478c-7339-98b2-408b7afe3ba2', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"8583003\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 18:23:39'),
('01a0a9f5-776b-7040-add8-fd6c2e1e4173', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"8583003\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 18:23:51'),
('01a0a9f5-e899-711f-831d-98811341570a', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"8583003\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 18:24:20'),
('01a0a9f6-5e88-71fc-b6e9-a2c6698140e2', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"8583003\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 18:24:50'),
('01a0a9f7-a065-736f-9f18-7821cd58ba7c', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"8583002\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 18:26:13'),
('01a0a9f7-c87d-7132-8252-3aac7f0132a7', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"8583002\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 18:26:23'),
('01a0a9f7-edcd-71b5-a296-5eabf7fd67d6', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"8583002\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 18:26:32'),
('01a0a9f8-815a-7047-86ad-8c1a19baac96', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"ALM1001\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 18:27:10'),
('01a0a9f8-9b05-70b2-9ca8-efe013ed2e49', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', NULL, 'email.forgot_password_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"cl***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-16 18:27:17'),
('01a0a9f9-52ee-7158-a8d8-cf5a093ce2b3', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'auth.password_reset', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 18:28:04'),
('01a0a9f9-9151-7105-a6fd-38522483618c', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"cl***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-16 18:28:20'),
('01a0a9fa-a1c8-7394-a566-af25d6578413', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 18:29:30'),
('01a0a9fe-46ad-703f-9d7c-ff9f07d3ffd2', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 18:33:28'),
('01a0a9ff-e8b3-70fc-910e-9519395ecd17', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-16 18:35:15'),
('01a0aa00-407f-71ed-b17d-aa45afdbf1f8', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 18:35:38'),
('01a0aa08-e2e8-7068-a61f-7d7574f33bd7', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni_master.imported', 'success', 'alumni', NULL, '01a0aa08-7f38-730a-96c2-254bc9e3c77a', '{\"file\":\"alumni-master-2026-09-14.xlsx\",\"mode\":\"update\",\"created\":0,\"updated\":3,\"skipped\":0,\"failed\":0}', '49.205.164.180', NULL, '2026-09-16 18:45:04'),
('01a0aa08-e324-7350-9f69-19f66ef94642', NULL, NULL, NULL, 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"alumni\",\"title\":\"Alumni Master Imported\",\"roles\":[\"super_admin\"],\"recipients\":2}', '127.0.0.1', 'Symfony', '2026-09-16 18:45:04'),
('01a0aa0c-878f-7211-9e6f-f81c30d614d5', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni_master.imported', 'success', 'alumni', NULL, '01a0aa0b-e372-71e8-8858-818e981b85aa', '{\"file\":\"alumni-master-2026-09-14.xlsx\",\"mode\":\"update\",\"created\":0,\"updated\":3,\"skipped\":0,\"failed\":0}', '49.205.164.180', NULL, '2026-09-16 18:49:02'),
('01a0aa0c-87a6-7024-b689-edcb453fc2b7', NULL, NULL, NULL, 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"alumni\",\"title\":\"Alumni Master Imported\",\"roles\":[\"super_admin\"],\"recipients\":2}', '127.0.0.1', 'Symfony', '2026-09-16 18:49:03'),
('01a0aa15-b03d-7003-89d4-46f37e0c25cd', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni_master.imported', 'success', 'alumni', NULL, '01a0aa14-cbc4-7327-a4d0-bbcaf09e747b', '{\"file\":\"alumni-master-2026-09-14.xlsx\",\"mode\":\"update\",\"created\":0,\"updated\":3,\"skipped\":0,\"failed\":0}', '49.205.164.180', NULL, '2026-09-16 18:59:03'),
('01a0aa15-b052-71ad-8d0a-ecaa34281ca2', NULL, NULL, NULL, 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"alumni\",\"title\":\"Alumni Master Imported\",\"roles\":[\"super_admin\"],\"recipients\":2}', '127.0.0.1', 'Symfony', '2026-09-16 18:59:03'),
('01a0aa18-c189-7285-8e59-3ee2bfc50646', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 19:02:24'),
('01a0aa19-f1eb-724f-a84c-68dcd3fa3fab', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-16 19:03:42'),
('01a0aa1a-8299-724a-8857-cac940cbcb0e', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 19:04:19'),
('01a0aa1a-c832-7044-a6d5-b22a12f21617', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 19:04:37'),
('01a0aa1c-021d-7120-8efc-5a40c16c0423', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, '01a0aa1c-0219-70bd-9ea1-150fa2e01a98', '{\"type\":\"document\",\"title\":\"New document available\",\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 19:05:57'),
('01a0aa1c-0223-72aa-bff1-798be2041721', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'document.uploaded', 'success', 'documents', NULL, '01a0aa1c-0213-73ea-9578-a8384b2cc5d7', '{\"title\":\"Salary Slips \\u2014 FY 2025-2026\",\"owner\":\"Monish\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 19:05:57'),
('01a0aa1c-435b-7145-99d4-c5f68701b445', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'document.downloaded', 'success', 'documents', NULL, '01a0aa1c-0213-73ea-9578-a8384b2cc5d7', '{\"title\":\"Salary Slips \\u2014 FY 2025-2026\",\"category\":\"Salary Slips\",\"mode\":\"download\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 19:06:14'),
('01a0aa1c-4361-710d-94c4-c1c259b2f175', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"document\",\"title\":\"Document Download Activity Detected\",\"roles\":[\"hr_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 19:06:14'),
('01a0aa71-5b44-7136-a807-54a43e71041c', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'application.created', 'success', 'applications', NULL, '01a0aa71-5b3e-71a6-83ac-890b0ff32215', '{\"job\":\"Supply chain management\",\"applicant\":\"Monish\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 20:39:10'),
('01a0aa71-5b4c-73e7-94d2-4151cf49a66b', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"application\",\"title\":\"New Job Application Received\",\"roles\":[\"hr_admin\",\"super_admin\"],\"recipients\":4}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 20:39:10'),
('01a0aa73-da4e-7391-a284-2d5efc69c393', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 20:41:54'),
('01a0aa74-5fb4-70eb-8495-95a7c420283e', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"8583002\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 20:42:28'),
('01a0aa74-e672-71a3-866b-7ee8a608ed2e', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"AL\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 20:43:03'),
('01a0aa74-fdb3-70c0-95f2-222ed182ab62', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"AL\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 20:43:08'),
('01a0aa75-17e5-7114-97f6-ef0008802715', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"cl***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-16 20:43:15'),
('01a0aa75-f186-73a9-9ca5-3eb4b9ae1ea9', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"cl***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-16 20:44:11'),
('01a0aa78-2283-7222-95b9-8355716a3cbd', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"cl***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-16 20:46:35'),
('01a0aa7d-3cb8-7310-956c-7e36be81e03a', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-16 20:52:09'),
('01a0aa7d-825f-726e-aca8-62613ad92cc5', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 20:52:27'),
('01a0aabf-3d76-7353-b624-6a96d6c6ebe9', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 22:04:14'),
('01a0aabf-7849-725e-9abc-132a8644712e', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"ALM1001\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 22:04:30'),
('01a0aaca-0ef3-722d-ab61-ceffd243efa4', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"HR1001\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 22:16:03'),
('01a0aaca-8779-719f-86fe-bcb8e5cb58c0', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-16 22:16:34'),
('01a0aacb-4b7b-7121-a301-3b30b8172adb', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 22:17:24'),
('01a0aacd-87b1-7093-b5ef-8e285698ea23', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni_master.updated', 'success', 'alumni', NULL, '01a0a009-0bdc-7189-bb2d-0442223b2900', '{\"vkollabId\":\"ALM1001\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 22:19:51'),
('01a0aacf-2394-7341-9359-163d53c0671b', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'alumni.profile_synced_from_roster', 'success', 'alumni', 'App\\Models\\AlumniMaster', '01a0a009-0bdc-7189-bb2d-0442223b2900', '{\"fields\":[\"joined_on\",\"exited_on\",\"designation\"]}', NULL, NULL, '2026-09-16 22:21:36'),
('01a0aacf-239b-71c4-bc27-3255db7bea58', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni_master.updated', 'success', 'alumni', NULL, '01a0a009-0bdc-7189-bb2d-0442223b2900', '{\"vkollabId\":\"ALM1001\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 22:21:36'),
('01a0aae1-5595-73df-bde0-cd354493029e', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 22:41:29'),
('01a0aae3-0c53-73fe-a33f-741d9e11c586', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', NULL, 'email.forgot_password_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-16 22:43:21'),
('01a0aae4-8449-732e-a267-3afc0e52f03f', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-16 22:44:57'),
('01a0aae4-ce0a-713c-8451-eb35c6f83483', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 22:45:16'),
('01a0aaef-dce2-7027-a9a5-d26feb51039c', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni_master.exported', 'success', 'alumni', NULL, NULL, '{\"filters\":[]}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-16 22:57:21'),
('01a0ab46-69c1-705e-8e1a-2e81bae317f8', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-17 00:31:53'),
('01a0ab47-31c0-72e4-83b6-3c48ccd37fdf', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-17 00:32:44'),
('01a0ab47-a778-73a9-b03b-3399debe0d1e', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-17 00:33:15'),
('01a0ab48-e549-71e0-94c5-f40e3f8b4a6c', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'audit.exported', 'success', 'reports', NULL, NULL, '{\"filters\":[]}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-17 00:34:36'),
('01a0ab54-cd49-70cb-af81-eef742e5028c', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"cl***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-17 00:47:36'),
('01a0ab55-876e-7018-8b2d-9f5f1861b74b', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 00:48:24'),
('01a0ab69-cb75-7336-b564-9be13c72b0f7', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'settings.updated', 'success', 'settings', NULL, NULL, '{\"changed\":[\"footer_phone\"]}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-17 01:10:32'),
('01a0ab69-cb84-73a7-9d16-38be1ff1fdcf', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"system\",\"title\":\"Platform Update\",\"roles\":[\"hr_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-17 01:10:32'),
('01a0ab6a-0201-716d-8358-95022f4e1779', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'settings.updated', 'success', 'settings', NULL, NULL, '{\"changed\":[\"footer_phone\"]}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-17 01:10:46'),
('01a0ab6a-0205-73bc-9a06-896a213f7e08', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"system\",\"title\":\"Platform Update\",\"roles\":[\"hr_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-17 01:10:46'),
('01a0ab73-684e-70d4-869d-9ebbae1ee98f', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'permission.user_updated', 'success', 'permissions', NULL, '01a0a6c2-2b02-7173-8043-3439b8d31b03', '{\"user\":\"Harsha\",\"count\":24}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-17 01:21:02'),
('01a0ab78-db08-73fa-a28d-cd551133cbd7', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-17 01:26:59'),
('01a0ab79-5f1f-7121-9702-739eb60f5eb1', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-17 01:27:33'),
('01a0ab7a-2c02-71ea-9451-925b27557e00', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-17 01:28:25'),
('01a0b516-fd47-7265-ac92-7108287b37cb', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"8583003\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 22:16:17'),
('01a0b517-3acd-7182-aa7a-e6f21aae586b', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"8583003\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 22:16:33'),
('01a0b517-706d-7129-9935-e454be5e12dd', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"ALM1001\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 22:16:47'),
('01a0b517-fcee-7182-a64f-6a5de247a615', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-18 22:17:23'),
('01a0b518-5e80-71a3-88ba-f5bd11d4895d', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 22:17:48'),
('01a0b51b-2f24-7055-9a72-ec35d1432562', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', '{\"alumnus\":\"Monish\",\"changes\":{\"profileEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 22:20:52'),
('01a0b51b-31d4-7117-ae77-87093d9a4ca1', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', '{\"alumnus\":\"Monish\",\"changes\":{\"profileEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 22:20:53'),
('01a0b51b-39b1-71e6-8c5e-77041cc9d51d', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', '{\"alumnus\":\"Monish\",\"changes\":{\"profileEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 22:20:55');
INSERT INTO `audit_logs` (`id`, `user_id`, `actor_name`, `actor_role`, `action`, `status`, `module`, `subject_type`, `subject_id`, `meta`, `ip_address`, `user_agent`, `created_at`) VALUES
('01a0b51b-4c4b-709b-81e5-d3da1fe77379', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', '{\"alumnus\":\"Monish\",\"changes\":{\"profileEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 22:21:00'),
('01a0b51b-5b8e-7037-8a73-a5661c0e7dc3', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', '{\"alumnus\":\"Monish\",\"changes\":{\"downloadsEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 22:21:04'),
('01a0b51b-7bb9-72ac-b662-3638476ba406', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', '{\"alumnus\":\"Monish\",\"changes\":{\"downloadsEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 22:21:12'),
('01a0b51c-15a2-70c7-b611-07c9c4a82375', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a0963f-fb96-72f9-b366-52fd81d9d235', '{\"alumnus\":\"Manikanta\",\"changes\":{\"downloadsEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 22:21:51'),
('01a0b51f-eab2-735a-8bf4-08bcb41fe94d', '01a09225-0284-7153-8eb0-bd4f0484a94a', NULL, NULL, 'email.invitation_sent', 'success', 'alumni', 'App\\Models\\AlumniMaster', '01a0b51f-ea94-73c2-ba9b-52d1d7ceec04', '{\"email\":\"as***@example.com\",\"source\":\"import\",\"status\":\"queued\"}', NULL, NULL, '2026-09-18 22:26:02'),
('01a0b51f-eabb-71d0-900e-541f46b68b06', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_master.imported', 'success', 'alumni', NULL, '01a0b51f-9667-7148-b731-4ae46a110537', '{\"file\":\"alumni-master-template.xlsx\",\"mode\":\"update\",\"created\":1,\"updated\":0,\"skipped\":0,\"failed\":0}', '49.205.164.180', NULL, '2026-09-18 22:26:02'),
('01a0b51f-ead1-710f-986f-4705d219c37c', NULL, NULL, NULL, 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"alumni\",\"title\":\"Alumni Master Imported\",\"roles\":[\"super_admin\"],\"recipients\":2}', '127.0.0.1', 'Symfony', '2026-09-18 22:26:02'),
('01a0b535-f195-7247-8690-bfea24ca6f5a', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'permission.user_updated', 'success', 'permissions', NULL, '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', '{\"user\":\"Monish\",\"count\":12}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 22:50:06'),
('01a0b545-510f-712d-8e9d-e3e8787ac615', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'settings.updated', 'success', 'settings', NULL, NULL, '{\"changed\":[\"alumni_document_email_enabled\"]}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:06:53'),
('01a0b545-5115-7340-aa8b-fc07e347b665', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"system\",\"title\":\"Platform Update\",\"roles\":[\"hr_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:06:53'),
('01a0b545-db9e-7280-aa30-b5f28a07bc97', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'settings.updated', 'success', 'settings', NULL, NULL, '{\"changed\":[\"alumni_document_email_enabled\"]}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:07:29'),
('01a0b545-dba2-7116-969e-a5d16ae6c3fd', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"system\",\"title\":\"Platform Update\",\"roles\":[\"hr_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:07:29'),
('01a0b546-c927-72d8-84ea-eadddf4b47f5', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'settings.updated', 'success', 'settings', NULL, NULL, '{\"changed\":[\"alumni_document_email_enabled\"]}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:08:30'),
('01a0b546-c92e-71df-9578-7ea61bd6f165', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"system\",\"title\":\"Platform Update\",\"roles\":[\"hr_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:08:30'),
('01a0b546-ced6-71f1-b519-dc1e47f44fb9', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'settings.updated', 'success', 'settings', NULL, NULL, '{\"changed\":[\"alumni_document_email_enabled\"]}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:08:31'),
('01a0b546-cedb-7362-ac04-09e325344af9', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"system\",\"title\":\"Platform Update\",\"roles\":[\"hr_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:08:31'),
('01a0b552-288c-73fc-ae01-38ffc202e7e1', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:20:55'),
('01a0b553-b264-731a-b130-0fb5f229baaa', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-18 23:22:36'),
('01a0b554-1b71-710d-94d4-e03547928e27', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:23:03'),
('01a0b55b-4723-715f-83a5-92ca93237fd6', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'settings.updated', 'success', 'settings', NULL, NULL, '{\"changed\":[\"footer_phone\"]}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:30:53'),
('01a0b55b-4728-7093-bd29-5edbfe36249e', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"system\",\"title\":\"Platform Update\",\"roles\":[\"hr_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:30:53'),
('01a0b55c-9555-70d6-b36a-942af03595ca', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:32:18'),
('01a0b55d-4681-7247-8369-c1591bc26d9d', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'Manikanta', 'alumni', 'account.auto_locked', 'failure', 'auth', NULL, NULL, '{\"attempts\":5,\"lockedForMinutes\":15}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:33:04'),
('01a0b55d-4683-7259-b4fa-2795c9f1130d', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"8583002\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:33:04'),
('01a0b55d-bd0c-70e5-8b16-7e81496e7d82', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'Manikanta', 'alumni', 'account.auto_locked', 'failure', 'auth', NULL, NULL, '{\"attempts\":6,\"lockedForMinutes\":15}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:33:34'),
('01a0b55d-bd0e-72a1-8b22-a78ab33b333d', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"8583002\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:33:34'),
('01a0b55e-0869-7341-b4b5-82c6c1ca1e69', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'Manikanta', NULL, 'email.forgot_password_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"ge***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-18 23:33:53'),
('01a0b55e-b27a-72b0-a3bc-caa02c787532', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'Manikanta', 'alumni', 'auth.password_reset', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-18 23:34:37'),
('01a0b55e-dedc-7261-aba1-237fe6d9f41d', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'Manikanta', 'alumni', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"locked\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-18 23:34:48'),
('01a0b55f-83d1-70d9-abf3-c36edfdea7ae', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'Manikanta', 'alumni', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"locked\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-18 23:35:30'),
('01a0b560-167c-70c1-8545-be84dbfa81cd', '01a0963f-fb96-72f9-b366-52fd81d9d235', 'Manikanta', 'alumni', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"locked\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:36:08'),
('01a0b560-77b1-72b6-8b17-051f5d3d68db', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"cl***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-18 23:36:33'),
('01a0b560-c183-70a6-8fa0-9749bf1a3259', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:36:52'),
('01a0b561-8853-733a-a2b5-6acbe82d7368', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'document.downloaded', 'success', 'documents', NULL, '01a0aa1c-0213-73ea-9578-a8384b2cc5d7', '{\"title\":\"Salary Slips \\u2014 FY 2025-2026\",\"category\":\"Salary Slips\",\"mode\":\"view\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:37:43'),
('01a0b561-885f-7359-b7fc-a4bb920a003a', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"document\",\"title\":\"Document Download Activity Detected\",\"roles\":[\"hr_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:37:43'),
('01a0b561-9ba2-7172-9108-aa397881d237', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'document.downloaded', 'success', 'documents', NULL, '01a0aa1c-0213-73ea-9578-a8384b2cc5d7', '{\"title\":\"Salary Slips \\u2014 FY 2025-2026\",\"category\":\"Salary Slips\",\"mode\":\"download\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:37:48'),
('01a0b561-9ba7-72c8-9219-fa48bb6b8143', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"document\",\"title\":\"Document Download Activity Detected\",\"roles\":[\"hr_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:37:48'),
('01a0b561-cfc0-73b0-80d8-f8b80e926d57', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'alumni', 'document.emailed', 'success', 'documents', NULL, '01a0aa1c-0213-73ea-9578-a8384b2cc5d7', '{\"title\":\"Salary Slips \\u2014 FY 2025-2026\",\"category\":\"Salary Slips\",\"to\":\"cl***@gmail.com\",\"delivery\":\"attachment\",\"sizeKb\":13}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 23:38:01'),
('01a0b5cc-c161-70fd-b52e-fe4dc10f478c', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"SA1001\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-19 01:34:50'),
('01a0b5cd-9c68-7350-84a4-3db9669b4485', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-19 01:35:46'),
('01a0b5ce-626e-71fe-bc51-1d8b51110fda', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-19 01:36:36'),
('01a0b5d0-f0fc-710a-a220-baa80c70d9fd', NULL, 'Mahesh', NULL, 'email.registration_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-19 01:39:24'),
('01a0b5d2-24b1-71d7-b35a-776a9de982bc', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'alumni', 'auth.registered', 'success', 'auth', NULL, NULL, '{\"vkollabId\":\"ALM1003\",\"termsAccepted\":true,\"privacyAccepted\":true}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 01:40:43'),
('01a0b5d2-24c3-73e4-98e9-4f3a8a504721', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', NULL, 'email.welcome_sent', 'success', 'auth', 'App\\Models\\User', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-19 01:40:43'),
('01a0b5d2-f849-718b-9c7d-675fb20c5e81', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-19 01:41:37'),
('01a0b5d3-51d4-7384-8632-6eb39dcfb359', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'alumni', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 01:42:00'),
('01a0b5d6-5bec-70ad-9aab-7dd92b989d58', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'alumni', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 01:45:19'),
('01a0c419-5c42-71b1-919d-a014691d190d', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-21 20:13:11'),
('01a0c419-f014-720a-b285-aef26c96e632', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-21 20:13:49'),
('01a0c473-dd1c-70c2-b0fa-2f4db6e81ad6', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_master.imported', 'success', 'alumni', NULL, '01a0c473-8536-7230-a84c-c478aa7b895c', '{\"file\":\"alumni-master-template (1).xlsx\",\"mode\":\"update\",\"created\":0,\"updated\":1,\"skipped\":0,\"failed\":0}', '49.205.164.180', NULL, '2026-09-21 21:52:02'),
('01a0c473-dd62-729d-82e3-42b458f4f446', NULL, NULL, NULL, 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"alumni\",\"title\":\"Alumni Master Imported\",\"roles\":[\"super_admin\"],\"recipients\":2}', '127.0.0.1', 'Symfony', '2026-09-21 21:52:02'),
('01a0c476-a07b-7090-9183-60b24fb5caf6', '01a09225-0284-7153-8eb0-bd4f0484a94a', NULL, NULL, 'email.invitation_sent', 'success', 'alumni', 'App\\Models\\AlumniMaster', '01a0c476-a059-716a-a211-35dbabb7bf94', '{\"email\":\"ve***@gmail.com\",\"source\":\"import\",\"status\":\"queued\"}', NULL, NULL, '2026-09-21 21:55:03'),
('01a0c476-a085-73bf-b9b2-9f11f8f0d278', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_master.imported', 'success', 'alumni', NULL, '01a0c475-eb05-7254-8fdf-55627b2d0e14', '{\"file\":\"alumni-master-template (1).xlsx\",\"mode\":\"update\",\"created\":1,\"updated\":1,\"skipped\":0,\"failed\":0}', '49.205.164.180', NULL, '2026-09-21 21:55:03'),
('01a0c476-a0a2-707e-a52e-ed013fb9505c', NULL, NULL, NULL, 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"alumni\",\"title\":\"Alumni Master Imported\",\"roles\":[\"super_admin\"],\"recipients\":2}', '127.0.0.1', 'Symfony', '2026-09-21 21:55:03'),
('01a0c4fe-0e6d-73a4-99a8-d1f10eb84410', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-22 00:22:59'),
('01a0c4fe-5edc-71e1-9ea7-2eefee885b8b', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-22 00:23:19'),
('01a0c509-1eea-725a-9a83-b1440bf6e276', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'audit.exported', 'success', 'reports', NULL, NULL, '{\"filters\":[]}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-22 00:35:04'),
('01a0c50c-c57e-72f8-a214-ad1090dba00a', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_master.imported', 'success', 'alumni', NULL, '01a0c50c-8366-703c-ba7e-3824892dc23a', '{\"file\":\"alumni master.xlsx\",\"mode\":\"update\",\"created\":0,\"updated\":1,\"skipped\":0,\"failed\":1}', '49.205.164.180', NULL, '2026-09-22 00:39:03'),
('01a0c50c-c5a9-730e-9a37-2f93e0bbe2c8', NULL, NULL, NULL, 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"alumni\",\"title\":\"Alumni Master Imported\",\"roles\":[\"super_admin\"],\"recipients\":2}', '127.0.0.1', 'Symfony', '2026-09-22 00:39:03'),
('01a0c50d-9a2f-71a9-8167-279ae4d059f8', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_master.deleted', 'success', 'alumni', NULL, '01a0c476-a059-716a-a211-35dbabb7bf94', '{\"vkollabId\":\"ALM2002\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-22 00:39:58'),
('01a0c519-e82c-7116-929a-1d7cbea90f3b', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, '01a0c519-e829-7008-af9c-f205afdb550c', '{\"type\":\"document\",\"title\":\"Document updated\",\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-22 00:53:24'),
('01a0c519-e832-7270-b59d-ffd9e0aa9a2f', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document.replaced', 'success', 'documents', NULL, '01a096c5-193a-7245-a6e7-c07d8a8913f1', '{\"title\":\"PAN\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-22 00:53:24'),
('01a0c5ca-46b8-7165-8b85-7f3d26e6258a', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'success', 'alumni', 'failure', NULL, '{\"row\":2,\"vkollabId\":\"ALM1004\",\"errors\":\"Missing UAN Number\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-22 04:06:03'),
('01a0c5cb-5b89-716a-9907-840571a58c3c', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'success', 'alumni', 'failure', NULL, '{\"row\":2,\"vkollabId\":\"ALM1004\",\"errors\":\"Missing Department, Missing UAN Number\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-22 04:07:13'),
('01a0c5d6-8f6a-7335-a992-7165f1af4c97', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'failure', 'alumni', NULL, NULL, '{\"row\":2,\"vkollabId\":\"ALM1004\",\"errors\":\"Missing Department, Missing UAN Number\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-22 04:19:28'),
('01a0c5d7-edd6-7253-b064-450e184a4324', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, '01a0c5d7-edd4-7125-bb0d-467d4d1af6c6', '{\"type\":\"document\",\"title\":\"New document available\",\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-22 04:20:57'),
('01a0c5d7-eddc-72f4-b9bf-e4769a9ae8a3', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document.uploaded', 'success', 'documents', NULL, '01a0c5d7-edce-7182-a4c0-d09dfcba433f', '{\"title\":\"Salary Slips \\u2014 FY 2025-2026\",\"owner\":\"Monish\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-22 04:20:57'),
('01a0c5eb-9fa9-73b2-8252-a4c7bc448e63', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'failure', 'alumni', NULL, NULL, '{\"row\":2,\"vkollabId\":\"ALM1004\",\"errors\":\"Missing Department, Missing UAN Number\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-22 04:42:28'),
('01a0c5eb-b030-70d7-8ff9-ef4041b5f79a', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'failure', 'alumni', NULL, NULL, '{\"row\":2,\"vkollabId\":\"ALM1004\",\"errors\":\"Missing Department, Missing UAN Number\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-22 04:42:32'),
('01a0e8b7-f614-7396-a9f0-cdc00967ea04', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"cl***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-28 22:52:45'),
('01a0e939-16ee-7314-84dd-bfe464125834', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-29 01:13:47'),
('01a0e939-81c8-7269-8041-db8d66d70d94', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-29 01:14:15'),
('01a0e93a-7a23-7105-ab3e-810a83703a51', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document_category.hr_access_changed', 'success', 'documents', NULL, '01a0a6f0-32bd-706e-89e1-a98cf8c0c7c7', '{\"name\":\"Reliving letters\",\"hrEnabled\":true}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-29 01:15:18'),
('01a0ecdf-230c-70b7-b72b-a3b45d3f1af0', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-29 18:14:01'),
('01a0ecdf-9abb-7075-a6dc-fbdb63d27e87', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 18:14:32'),
('01a0ecfc-b3be-7131-be9f-e65d3920c30c', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'settings.updated', 'success', 'settings', NULL, NULL, '{\"changed\":[\"footer_phone\",\"organization_logo\",\"portal_logo\"]}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 18:46:19'),
('01a0ecfc-b3c5-7251-9f7b-905dd6009523', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"system\",\"title\":\"Platform Update\",\"roles\":[\"hr_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 18:46:19'),
('01a0ed0b-5749-7081-9ad8-3ab2ca1ba64e', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'failure', 'alumni', NULL, NULL, '{\"row\":2,\"vkollabId\":\"ALM1004\",\"errors\":\"Missing Department, Missing UAN Number\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 19:02:18'),
('01a0ed0e-911a-708a-b3c4-f472c8a0c895', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'failure', 'alumni', NULL, NULL, '{\"row\":2,\"vkollabId\":\"ALM1004\",\"errors\":\"Missing Department, Missing UAN Number, Missing PF Number\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 19:05:49'),
('01a0ed0f-e049-7309-b6ed-8210bffb33b7', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'failure', 'alumni', NULL, NULL, '{\"row\":2,\"vkollabId\":\"ALM1004\",\"errors\":\"Missing Department, Missing Designation\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 19:07:15'),
('01a0ed43-e862-7078-a50f-cb749537b518', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'failure', 'alumni', NULL, NULL, '{\"row\":1,\"errors\":\"Invalid file uploaded. Please use the Alumni Import Template.\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 20:04:05'),
('01a0ed53-11e2-7214-97eb-2ce98031b189', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, '01a0ed53-11de-7116-a0c9-5cd5736c65be', '{\"type\":\"document\",\"title\":\"New document available\",\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 20:20:39'),
('01a0ed53-11eb-72b7-b988-0861be5b6462', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document.uploaded', 'success', 'documents', NULL, '01a0ed53-11d7-7291-9ee3-1c91d3f67b90', '{\"title\":\"Experience Letter\",\"owner\":\"Mahesh\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 20:20:39'),
('01a0ed53-d25f-73ac-b6d1-9ea234884e8e', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, '01a0ed53-d25d-70a9-ab65-634294e8d8db', '{\"type\":\"document\",\"title\":\"New document available\",\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 20:21:28'),
('01a0ed53-d264-714d-8a55-522fa020308e', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document.uploaded', 'success', 'documents', NULL, '01a0ed53-d259-7278-99b8-5de53296d297', '{\"title\":\"Increment Letter\",\"owner\":\"Mahesh\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 20:21:28'),
('01a0ed54-d028-7351-b0eb-83dd4d4950ab', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, '01a0ed54-d025-7207-a745-895c987fbf54', '{\"type\":\"document\",\"title\":\"New document available\",\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 20:22:33'),
('01a0ed54-d02e-7242-ac85-669bec4ed088', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document.uploaded', 'success', 'documents', NULL, '01a0ed54-d01f-7122-9997-6affb7687a3d', '{\"title\":\"updated Full & Final Settlement\",\"owner\":\"Manikanta\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 20:22:33'),
('01a0ed73-3124-72ca-9efd-3588484e4ea3', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"SA1001\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-29 20:55:44'),
('01a0ed74-3be0-71ed-a0e5-46a25b8a0f82', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-29 20:56:52'),
('01a0ed74-a937-7248-b294-305c07c9d862', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-29 20:57:20'),
('01a0ed75-0043-731d-8b4b-d82e505a4183', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'failure', 'alumni', NULL, NULL, '{\"row\":1,\"errors\":\"Invalid file uploaded. Please use the Alumni Import Template.\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-29 20:57:43'),
('01a0ed76-d23c-716d-8db2-9c2f1e83280b', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'failure', 'alumni', NULL, NULL, '{\"row\":2,\"vkollabId\":\"ALM2001\",\"errors\":\"Invalid PAN format (ABCDE12345), Invalid email format (asha.raoexample.com), Invalid mobile number (961875237) \\u2014 enter 10 digits, Missing Mobile Number, Invalid date in Date of Joining (TEXt), Invalid date in Last Working Date (Text), Missing DOB, Missing Date of Joining, Missing Last Working Date, Missing UAN Number, Invalid PF Number (KA\\/BNG\\/00123\\/46) - must be exactly 22 alphanumeric characters\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-29 20:59:42'),
('01a0ed78-8506-7264-980c-dd5e9318ed61', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'failure', 'alumni', NULL, NULL, '{\"row\":2,\"vkollabId\":\"ALM2001\",\"errors\":\"Missing PAN Number, Invalid email format (asha.), Missing Mobile Number, Missing Department, Missing Designation, Missing DOB, Missing Date of Joining, Missing Last Working Date, Missing UAN Number, Missing PF Number, Missing Last Working Location, Missing Band, Missing Supervisor Name, Missing Supervisor Email\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-29 21:01:33'),
('01a0ed7b-b6f4-706c-9f4f-f0b9507867d9', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, '01a0ed7b-b6d9-725a-81a0-9948b8da4104', '{\"type\":\"document\",\"title\":\"New document available\",\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-29 21:05:03'),
('01a0ed7b-b726-7288-8124-1db0ad6c044d', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document.uploaded', 'success', 'documents', NULL, '01a0ed7b-b68e-730d-b3ae-06337639a78e', '{\"title\":\"Reliving letters\",\"owner\":\"Mahesh\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-29 21:05:03'),
('01a0ed7e-3f54-71b4-8005-312a19de08ef', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-29 21:07:49'),
('01a0ed7e-ae88-72cf-9846-5bd1a5d78258', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'alumni', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 21:08:17'),
('01a0ed7f-8713-70c5-b474-12ac970c84ad', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, '01a0ed7f-870a-7054-8dff-5f9e25d2dc0a', '{\"type\":\"document\",\"title\":\"New document available\",\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-29 21:09:12'),
('01a0ed7f-871c-72ad-ae18-d6e1c23cc11b', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document.uploaded', 'success', 'documents', NULL, '01a0ed7f-8704-7223-a63b-fdfd96ddc26b', '{\"title\":\"updated Full & Final Settlement\",\"owner\":\"Mahesh\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-29 21:09:12'),
('01a0ed80-623b-7341-a17f-8d8e79c3902c', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'alumni', 'document.downloaded', 'success', 'documents', NULL, '01a0ed7f-8704-7223-a63b-fdfd96ddc26b', '{\"title\":\"updated Full & Final Settlement\",\"category\":\"updated Full & Final Settlement\",\"mode\":\"view\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 21:10:09'),
('01a0ed80-6244-7296-a932-5ffa25ef965a', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'alumni', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"document\",\"title\":\"Document Download Activity Detected\",\"roles\":[\"hr_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 21:10:09'),
('01a0ed80-bd45-72ac-a0af-341135c0e103', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document.deleted', 'success', 'documents', NULL, '01a0ed7f-8704-7223-a63b-fdfd96ddc26b', '{\"title\":\"updated Full & Final Settlement\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-29 21:10:32'),
('01a0ed80-bd4b-7269-ac26-c1de72d56f40', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"document\",\"title\":\"Document Deleted\",\"roles\":[\"super_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-29 21:10:32'),
('01a0ed84-4ca2-722e-8cb8-db0a2f9d5ead', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-29 21:14:25'),
('01a0ed87-a8a8-71b1-81c5-6c08b9f489e5', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'alumni', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 21:18:05'),
('01a0ed8a-20cc-72ba-b902-7e04188f1fe2', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-29 21:20:47'),
('01a0ed8a-7b65-73a7-a3ab-290d3aded9be', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-09-29 21:21:10'),
('01a0ed9c-6f69-71be-9fdf-6eb568508b80', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-29 21:40:47'),
('01a0ed9c-d601-7049-a4fc-89908686f38c', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 21:41:13'),
('01a0ed9e-1ac5-72a6-81bc-bbec8a63b8bf', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '2401:4900:937e:dc:9de8:46ca:d36d:a95a', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 21:42:36'),
('01a0ed9e-96cd-7207-b496-6c9ede157c4a', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-29 21:43:08'),
('01a0ed9f-5188-7393-9c1f-910dd8cd50e1', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-29 21:43:56'),
('01a0ed9f-b4ae-7126-a20c-a251f35af58f', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '2401:4900:937e:dc:9de8:46ca:d36d:a95a', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 21:44:21'),
('01a0edd1-3312-73cd-8f24-23e0fc33e704', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-09-29 22:38:25'),
('01a0edd1-b561-710d-bb1c-de45c4454f32', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 22:38:58'),
('01a0edd2-4b4d-737c-b491-f761d0d3e39d', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'failure', 'alumni', NULL, NULL, '{\"row\":1,\"errors\":\"Invalid file uploaded. Please use the Alumni Import Template.\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 22:39:37'),
('01a0edd3-f08c-7338-98f4-338d78b578a3', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'failure', 'alumni', NULL, NULL, '{\"row\":2,\"vkollabId\":\"ALM1004\",\"errors\":\"Missing Department, Missing Designation\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-09-29 22:41:25'),
('01a1781b-e1fd-7291-a4b4-7c1837da9491', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-10-26 19:07:35'),
('01a1781c-3faf-7204-81a3-cf09fde222c3', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-26 19:07:59'),
('01a1781f-3ad7-72ce-939d-5918ff491d1f', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'permission.user_updated', 'success', 'permissions', NULL, '01a0a6c2-2b02-7173-8043-3439b8d31b03', '{\"user\":\"Harsha\",\"count\":46}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-26 19:11:15'),
('01a1781f-647d-71dc-a38c-87bc1f1a252c', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'permission.user_updated', 'success', 'permissions', NULL, '01a09277-60bd-71b1-9fad-a03afb7db784', '{\"user\":\"MANIKANTA\",\"count\":46}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-26 19:11:25'),
('01a17828-5243-73ce-864c-05fcd2599608', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"HR1002\",\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:21:10'),
('01a17828-7670-7364-b6b4-43f9e7baba5d', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"HR1003\",\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:21:20'),
('01a17828-917b-73f3-9129-7cb710ee08eb', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-10-26 19:21:27'),
('01a17828-fc15-71ee-9bf7-2b8f92abe4d7', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:21:54'),
('01a17832-5803-71fb-9d66-dece8c2cccab', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni_master.created', 'success', 'alumni', NULL, '01a17832-57fe-707f-a163-925d7cc1558e', '{\"vkollabId\":\"ALM1006\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:32:07'),
('01a17832-5812-7161-a404-befbf1b49134', '01a0a6c2-2b02-7173-8043-3439b8d31b03', NULL, NULL, 'email.invitation_sent', 'success', 'alumni', 'App\\Models\\AlumniMaster', '01a17832-57fe-707f-a163-925d7cc1558e', '{\"email\":\"po***@gmail.com\",\"source\":\"manual\",\"status\":\"queued\"}', NULL, NULL, '2026-10-26 19:32:07'),
('01a17832-5819-7160-899e-183f6ee52513', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"alumni\",\"title\":\"Alumni Added\",\"roles\":[\"super_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:32:07'),
('01a17833-ada3-70fc-9542-081f5af3fb5d', NULL, 'Mohan kumar', NULL, 'email.registration_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"po***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-10-26 19:33:35'),
('01a17835-ee46-73ee-a82d-e14fae9f72d1', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', 'alumni', 'auth.registered', 'success', 'auth', NULL, NULL, '{\"vkollabId\":\"ALM1006\",\"termsAccepted\":true,\"privacyAccepted\":true}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-26 19:36:02'),
('01a17835-ee55-7040-962e-bbeb4ba9ed0a', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', NULL, 'email.welcome_sent', 'success', 'auth', 'App\\Models\\User', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '{\"email\":\"po***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-10-26 19:36:02'),
('01a17836-2399-73ae-b0ac-a3a504baa3eb', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"po***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-10-26 19:36:16'),
('01a17836-9c40-7255-acbb-86a86e0a6ded', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, '01a17836-9c3d-71be-b466-b5bce5ef9ec3', '{\"type\":\"document\",\"title\":\"New document available\",\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:36:47'),
('01a17836-9c4b-7214-a7a4-45511116de75', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'document.uploaded', 'success', 'documents', NULL, '01a17836-9c37-7221-a1f1-bc4ab06bc8d1', '{\"title\":\"Salary Slips \\u2014 FY 2026-2027\",\"owner\":\"Mohan kumar\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:36:47'),
('01a17836-f89b-723f-aa30-aa0986f8c2d0', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', 'alumni', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-26 19:37:10');
INSERT INTO `audit_logs` (`id`, `user_id`, `actor_name`, `actor_role`, `action`, `status`, `module`, `subject_type`, `subject_id`, `meta`, `ip_address`, `user_agent`, `created_at`) VALUES
('01a17837-3312-7300-a1cd-8db16da380de', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', 'alumni', 'document.downloaded', 'success', 'documents', NULL, '01a17836-9c37-7221-a1f1-bc4ab06bc8d1', '{\"title\":\"Salary Slips \\u2014 FY 2026-2027\",\"category\":\"Salary Slips\",\"mode\":\"download\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-26 19:37:25'),
('01a17837-3317-71da-aea2-85e3ebea0b1d', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', 'alumni', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"document\",\"title\":\"Document Download Activity Detected\",\"roles\":[\"hr_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-26 19:37:25'),
('01a17837-884c-7005-974c-238d453fed0a', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', 'alumni', 'document.emailed', 'success', 'documents', NULL, '01a17836-9c37-7221-a1f1-bc4ab06bc8d1', '{\"title\":\"Salary Slips \\u2014 FY 2026-2027\",\"category\":\"Salary Slips\",\"to\":\"po***@gmail.com\",\"delivery\":\"attachment\",\"sizeKb\":13}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-26 19:37:47'),
('01a1783a-587f-70df-b07e-8709a07395e3', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'job.created', 'success', 'jobs', NULL, '01a1783a-587b-7072-af47-56ca02afb77a', '{\"title\":\"AI Developer\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:40:52'),
('01a1783a-5886-71e0-a88e-542608309755', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"job\",\"title\":\"New job posted: AI Developer\",\"roles\":[\"alumni\"],\"recipients\":4}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:40:52'),
('01a1783a-588c-71fd-9f2d-646d3b573524', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'ticker.created', 'success', 'announcements', NULL, '01a1783a-5889-7004-8f22-105e71815c2b', '{\"message\":\"New job opportunity: AI Developer at VKollab Technologies\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:40:52'),
('01a1783a-5894-7358-9b2f-e0728a9ff955', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"job\",\"title\":\"Job Posted\",\"roles\":[\"super_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:40:52'),
('01a17842-8470-7236-8646-33745d7efa39', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '{\"alumnus\":\"Mohan kumar\",\"changes\":{\"tickerEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:49:47'),
('01a17842-8de0-738c-ab68-b1312adea0ff', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '{\"alumnus\":\"Mohan kumar\",\"changes\":{\"tickerEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:49:50'),
('01a17842-efb9-719b-beac-6728dd5951c5', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '{\"alumnus\":\"Mohan kumar\",\"changes\":{\"profileEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:50:15'),
('01a17842-f3a6-70eb-a229-e0c5c621677b', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '{\"alumnus\":\"Mohan kumar\",\"changes\":{\"downloadsEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:50:16'),
('01a17843-0376-72a8-b3c9-a119937e6899', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '{\"alumnus\":\"Mohan kumar\",\"changes\":{\"documentsEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:50:20'),
('01a17843-0bc5-7368-a359-a7034241f631', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '{\"alumnus\":\"Mohan kumar\",\"changes\":{\"jobsEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:50:22'),
('01a17843-0f42-7099-88d5-83c4d89e0343', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '{\"alumnus\":\"Mohan kumar\",\"changes\":{\"applicationsEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:50:23'),
('01a17843-16a4-7182-a465-277d04aa3284', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '{\"alumnus\":\"Mohan kumar\",\"changes\":{\"notificationsEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:50:25'),
('01a17843-18e1-72a6-8d6e-269bf9235cd7', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '{\"alumnus\":\"Mohan kumar\",\"changes\":{\"tickerEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:50:25'),
('01a17843-1b9e-70e6-a784-0ceba265fb36', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '{\"alumnus\":\"Mohan kumar\",\"changes\":{\"announcementsEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:50:26'),
('01a17843-1ef5-709f-857c-f08ef81be393', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '{\"alumnus\":\"Mohan kumar\",\"changes\":{\"dashboardEnabled\":false}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:50:27'),
('01a17843-b382-7350-9597-4cb557da2503', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '{\"alumnus\":\"Mohan kumar\",\"changes\":{\"profileEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:51:05'),
('01a17843-b638-70a0-a8c7-1ce1afa7d2a0', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '{\"alumnus\":\"Mohan kumar\",\"changes\":{\"downloadsEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:51:05'),
('01a17843-b934-701c-bcef-1256bc9bb95f', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '{\"alumnus\":\"Mohan kumar\",\"changes\":{\"documentsEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:51:06'),
('01a17843-bc1e-73d1-ab81-fd654eae529f', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '{\"alumnus\":\"Mohan kumar\",\"changes\":{\"jobsEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:51:07'),
('01a17843-be7e-7022-825a-0b85b5c8094f', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '{\"alumnus\":\"Mohan kumar\",\"changes\":{\"applicationsEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:51:08'),
('01a17843-c437-71c2-9b56-0c1281670671', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '{\"alumnus\":\"Mohan kumar\",\"changes\":{\"notificationsEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:51:09'),
('01a17843-c71c-7111-b856-2ac02a772df3', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '{\"alumnus\":\"Mohan kumar\",\"changes\":{\"tickerEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:51:10'),
('01a17843-c908-7172-acb6-d51185adeedf', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '{\"alumnus\":\"Mohan kumar\",\"changes\":{\"announcementsEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:51:10'),
('01a17843-cdde-7025-812c-10af683a6336', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni.controls_updated', 'success', 'alumni', NULL, '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '{\"alumnus\":\"Mohan kumar\",\"changes\":{\"dashboardEnabled\":true}}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 19:51:11'),
('01a17844-316e-7047-8c21-fdb5689d3ab9', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', 'alumni', 'application.created', 'success', 'applications', NULL, '01a17844-316a-70f1-9700-e36d4a353fbb', '{\"job\":\"AI Developer\",\"applicant\":\"Mohan kumar\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-26 19:51:37'),
('01a17844-3174-7306-be1f-358ed2c4be8d', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', 'alumni', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"application\",\"title\":\"New Job Application Received\",\"roles\":[\"hr_admin\",\"super_admin\"],\"recipients\":4}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-26 19:51:37'),
('01a17852-9a62-7303-b6e7-077521196b8b', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'content.updated', 'success', 'content', NULL, '01a09205-3d52-7257-bc93-702adb6c2f2f', '{\"key\":\"landing_about\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-26 20:07:21'),
('01a17852-9a6b-71ad-b919-1430e3bbcf4b', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"system\",\"title\":\"Platform Update\",\"roles\":[\"hr_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-26 20:07:21'),
('01a178b1-1633-70d3-91ad-8f46c6900b49', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-10-26 21:50:33'),
('01a178b1-6677-72ae-8d12-46f883d48801', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-26 21:50:54'),
('01a178b2-4467-7269-b3fd-91904087c6e4', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"HR1002\",\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 21:51:51'),
('01a178b2-6951-712d-876c-436f99b4525e', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-10-26 21:52:00'),
('01a178b2-b3e6-7138-92c2-e5c78416b6b0', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 21:52:19'),
('01a178bc-c318-705d-9680-febfba3c5608', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', 'alumni', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-26 22:03:19'),
('01a178bd-55e9-70b9-89f2-f64a81f321d0', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-10-26 22:03:56'),
('01a178bd-ef44-7057-87f4-4f8c52093fec', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-26 22:04:35'),
('01a178ce-b6f0-7137-b28b-e330d7fbcad3', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"SA1001\",\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 22:22:55'),
('01a178d8-aa5a-727e-832b-e7b107fe8b93', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"SA1001\",\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 22:33:47'),
('01a178e2-9cbe-7143-a1b7-3ffe50dbc4ac', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"HR1001\",\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 22:44:39'),
('01a178e2-da5a-7326-9735-55cd1820dee5', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"HR1002\",\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 22:44:55'),
('01a178e3-6738-706b-a619-b30ed7f4126a', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-10-26 22:45:31'),
('01a178e4-4a91-7106-837c-bb919bccbeb4', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 22:46:29'),
('01a178ec-ef30-73f1-a024-21bdf8745b21', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'application.status_changed', 'success', 'applications', NULL, '01a17844-316a-70f1-9700-e36d4a353fbb', '{\"status\":\"shortlisted\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 22:55:56'),
('01a178ec-ef3b-725c-b6c0-0802168c4e87', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, '01a178ec-ef39-7164-a9ef-bc0792706ff7', '{\"type\":\"application\",\"title\":\"Application update: AI Developer\",\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 22:55:56'),
('01a178f4-5ebd-7350-9702-d76966c9a47d', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'announcement.created', 'success', 'announcements', NULL, '01a178f4-5eb7-729d-9fad-ba1a298aae35', '{\"title\":\"Title: Q3 Alumni Meet, Audience: Everyone\",\"published\":true}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 23:04:03'),
('01a178f4-5ec3-737a-9f7b-820f54f1acd8', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"announcement\",\"title\":\"Title: Q3 Alumni Meet, Audience: Everyone\",\"roles\":[\"alumni\",\"hr_admin\"],\"recipients\":6}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 23:04:03'),
('01a178f4-5ecc-71ea-88df-6b9ee57fbb12', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'ticker.created', 'success', 'announcements', NULL, '01a178f4-5ec8-72ef-9362-76b8d0dfbc35', '{\"message\":\"Title: Q3 Alumni Meet, Audience: Everyone\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 23:04:03'),
('01a178f4-5ed1-7170-884c-41fbbdcb30ff', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"announcement\",\"title\":\"Announcement Published\",\"roles\":[\"super_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 23:04:03'),
('01a178f8-cca1-70cc-9b58-55c359ae09c0', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni_master.created', 'success', 'alumni', NULL, '01a178f8-cc9e-710d-a393-8b1382b638f1', '{\"vkollabId\":\"ALM2003\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 23:08:53'),
('01a178f8-ccae-71a4-8ac9-c7fca4c4435a', '01a0a6c2-2b02-7173-8043-3439b8d31b03', NULL, NULL, 'email.invitation_sent', 'success', 'alumni', 'App\\Models\\AlumniMaster', '01a178f8-cc9e-710d-a393-8b1382b638f1', '{\"email\":\"sa***@gmail.com\",\"source\":\"manual\",\"status\":\"queued\"}', NULL, NULL, '2026-10-26 23:08:53'),
('01a178f8-ccb2-73d6-936f-e38aae058b8b', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"alumni\",\"title\":\"Alumni Added\",\"roles\":[\"super_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-26 23:08:53'),
('01a1793e-ceea-7140-b458-23439b033515', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"HR1002\",\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 00:25:21'),
('01a1793e-fc8d-73e8-8f37-7a89fcc8d754', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-10-27 00:25:33'),
('01a1793f-a78f-7234-afad-7d85f9926a5e', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 00:26:17'),
('01a1793f-e5fb-722b-b735-04c38bd76d01', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"hr_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 00:26:33'),
('01a17955-37e1-7087-b423-e4c80df9132b', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-10-27 00:49:50'),
('01a17955-c5b9-701f-bb70-6068050da43e', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 00:50:26'),
('01a1795e-c2ea-7182-8080-063902c39d90', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni_master.updated', 'success', 'alumni', NULL, '01a178f8-cc9e-710d-a393-8b1382b638f1', '{\"vkollabId\":\"ALM2003\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:00:15'),
('01a17961-cb1f-729b-be15-ca2a2541bca2', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni_master.updated', 'success', 'alumni', NULL, '01a178f8-cc9e-710d-a393-8b1382b638f1', '{\"vkollabId\":\"ALM2003\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:03:34'),
('01a1796f-01b4-70c2-a6f9-ad307fb1070d', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni_master.updated', 'success', 'alumni', NULL, '01a178f8-cc9e-710d-a393-8b1382b638f1', '{\"vkollabId\":\"ALM2003\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:18:00'),
('01a1796f-9298-73a9-9953-4ede906e3233', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'alumni_master.updated', 'success', 'alumni', NULL, '01a0a009-0bf0-710e-b0e9-7794808b2c58', '{\"vkollabId\":\"ALM1002\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:18:37'),
('01a17972-4bc2-71a6-8c98-793338c9cd16', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:21:36'),
('01a17972-a276-70a3-a7b5-78d219eff9ae', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"8583003\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:21:58'),
('01a17973-0795-701d-a37f-1f6dd89d1c6c', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"ALM005\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:22:24'),
('01a17973-192d-7042-af37-747454cc6380', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"ALM006\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:22:28'),
('01a17973-b724-7203-b8a2-145eeb1f3b64', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"ALM1006\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:23:09'),
('01a17973-f70a-71a7-a8cb-a0046dca0c76', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"ALM1006\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:23:25'),
('01a17974-0ab4-713d-9829-dfa8faeae88a', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"ALM1006\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:23:30'),
('01a17974-6b26-71d0-9d10-609b338f39a6', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-10-27 01:23:55'),
('01a17974-fe95-7300-889b-09424bd3f428', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'alumni', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:24:32'),
('01a17979-622c-7309-886d-fad902b5b6b8', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'document.downloaded', 'success', 'documents', NULL, '01a17836-9c37-7221-a1f1-bc4ab06bc8d1', '{\"title\":\"Salary Slips \\u2014 FY 2026-2027\",\"by\":\"hr\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:29:20'),
('01a1797c-14ee-7269-bfdf-8c4c4f20deca', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, '01a1797c-14eb-725b-b63e-687f4e8228fc', '{\"type\":\"document\",\"title\":\"New document available\",\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:32:17'),
('01a1797c-14f6-7258-ab51-e03b58615bde', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'document.uploaded', 'success', 'documents', NULL, '01a1797c-14e6-7231-bd0b-f2273c565eb3', '{\"title\":\"Salary Slips \\u2014 FY 2026-2027\",\"owner\":\"Mohan kumar\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:32:17'),
('01a17980-9032-7143-8c4c-a577b08a504c', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'alumni', 'document.downloaded', 'success', 'documents', NULL, '01a0ed53-11d7-7291-9ee3-1c91d3f67b90', '{\"title\":\"Experience Letter\",\"category\":\"Experience Letter\",\"mode\":\"download\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:37:11'),
('01a17980-9036-706b-b36e-4cdfbed62e8e', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'alumni', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"document\",\"title\":\"Document Download Activity Detected\",\"roles\":[\"hr_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:37:11'),
('01a17981-6624-729a-a2cc-503cbb4e3166', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'document.downloaded', 'success', 'documents', NULL, '01a0ed54-d01f-7122-9997-6affb7687a3d', '{\"title\":\"updated Full & Final Settlement\",\"by\":\"hr\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:38:05'),
('01a17981-adf6-736f-9994-672956845a09', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'document.downloaded', 'success', 'documents', NULL, '01a0ed7b-b68e-730d-b3ae-06337639a78e', '{\"title\":\"Reliving letters\",\"by\":\"hr\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:38:24'),
('01a17985-0b69-7071-87d1-2fad3ba90c2d', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'alumni', 'application.created', 'success', 'applications', NULL, '01a17985-0b65-7045-ace9-e5c65eee90aa', '{\"job\":\"AI Developer\",\"applicant\":\"Mahesh\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:42:04'),
('01a17985-0b71-729a-92ca-e9388c4b5c25', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'alumni', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"application\",\"title\":\"New Job Application Received\",\"roles\":[\"hr_admin\",\"super_admin\"],\"recipients\":4}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:42:04'),
('01a17986-4f7c-7294-897a-30a3afaeb6c8', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'alumni', 'document.downloaded', 'success', 'documents', NULL, '01a0ed53-d259-7278-99b8-5de53296d297', '{\"title\":\"Increment Letter\",\"category\":\"Personal Documents\",\"mode\":\"download\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:43:27'),
('01a17986-4f83-724b-9897-857348c8b158', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'alumni', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"document\",\"title\":\"Document Download Activity Detected\",\"roles\":[\"hr_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:43:27'),
('01a1798b-e77d-70ba-a42b-5872533babed', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'job.created', 'success', 'jobs', NULL, '01a1798b-e779-7262-968b-995efa7fc27d', '{\"title\":\"AI Developer Position\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:49:34'),
('01a1798b-e782-732d-bd4f-24ff08d488fa', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"job\",\"title\":\"New job posted: AI Developer Position\",\"roles\":[\"alumni\"],\"recipients\":4}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:49:34'),
('01a1798b-e787-73a0-a41d-9b797fb9e30c', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'ticker.created', 'success', 'announcements', NULL, '01a1798b-e785-704f-8873-eb5e4017d639', '{\"message\":\"New job opportunity: AI Developer Position at VKollab Technologies\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:49:34'),
('01a1798b-e78c-7225-b79e-de8e49b4f288', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"job\",\"title\":\"Job Posted\",\"roles\":[\"super_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:49:34'),
('01a17991-35c7-7386-8bc7-79a8c3b96664', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'job.status_changed', 'success', 'jobs', NULL, '01a1798b-e779-7262-968b-995efa7fc27d', '{\"status\":\"closed\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:55:22'),
('01a17991-5c10-7335-9611-8b308be914bf', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'job.status_changed', 'success', 'jobs', NULL, '01a1798b-e779-7262-968b-995efa7fc27d', '{\"status\":\"open\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:55:31'),
('01a17991-6e91-7254-912c-042d12b200e8', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'hr_admin', 'job.status_changed', 'success', 'jobs', NULL, '01a1798b-e779-7262-968b-995efa7fc27d', '{\"status\":\"closed\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 01:55:36'),
('01a17997-142a-71fa-adce-1b18eb60ca4a', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-10-27 02:01:46'),
('01a17997-9cf5-734d-a8a0-1e99906e7632', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"super_admin\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:02:21'),
('01a17999-5d38-70cd-9c14-6870a19101ed', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_master.exported', 'success', 'alumni', NULL, NULL, '{\"filters\":[]}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:04:16'),
('01a1799a-4c4a-73a0-b659-9bafcd9f9a16', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_master.exported', 'success', 'alumni', NULL, NULL, '{\"filters\":[]}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:05:17'),
('01a1799d-9c45-7177-9f87-6547461b967b', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document.downloaded', 'success', 'documents', NULL, '01a0ed53-11d7-7291-9ee3-1c91d3f67b90', '{\"title\":\"Experience Letter\",\"by\":\"hr\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:08:54'),
('01a1799e-20b4-7386-ab94-4dced1c1001c', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, '01a1799e-20b1-72e6-adaf-85d9a75db2a1', '{\"type\":\"document\",\"title\":\"New document available\",\"recipients\":1}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:09:28'),
('01a1799e-20bd-719a-ac9a-395e13c0393b', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document.uploaded', 'success', 'documents', NULL, '01a1799e-20ac-7176-a1c8-ff823eedb893', '{\"title\":\"Experience Letter\",\"owner\":\"Mahesh\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:09:28'),
('01a1799f-6fbf-706b-9c09-5960634785a4', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document_category.hr_access_changed', 'success', 'documents', NULL, '01a09205-3edc-73de-a58a-e707c9391466', '{\"name\":\"Personal Documents\",\"hrEnabled\":false}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:10:54'),
('01a179a0-6a2b-730c-b073-0be9542b3927', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document_category.hr_access_changed', 'success', 'documents', NULL, '01a09205-3edc-73de-a58a-e707c9391466', '{\"name\":\"Personal Documents\",\"hrEnabled\":true}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:11:58'),
('01a179a0-8804-703e-91d6-1739083da00c', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document_category.created', 'success', 'documents', NULL, '01a179a0-8802-72f6-b45a-ad0547a66ef3', '{\"name\":\"Salary panel\",\"hrEnabled\":false}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:12:06'),
('01a179a0-a484-7333-a819-daed774a8998', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document_category.deleted', 'success', 'documents', NULL, '01a179a0-8802-72f6-b45a-ad0547a66ef3', '{\"name\":\"Salary panel\",\"documentsOrphaned\":0,\"forced\":false}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:12:13'),
('01a179a0-c09a-70d9-85e3-17626427fb7a', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document_category.created', 'success', 'documents', NULL, '01a179a0-c096-73aa-b0f9-4dc425b1565f', '{\"name\":\"Salary panel\",\"hrEnabled\":false}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:12:20'),
('01a179a1-9d3d-7192-8de3-67190deeb5fd', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document_category.deleted', 'success', 'documents', NULL, '01a179a0-c096-73aa-b0f9-4dc425b1565f', '{\"name\":\"Salary panel\",\"documentsOrphaned\":0,\"forced\":false}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:13:17'),
('01a179a1-d0ca-734e-ba3a-3ee3a0c62022', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document_category.updated', 'success', 'documents', NULL, '01a09205-3df5-71e7-bb62-58e02998c2b7', '{\"name\":\"Experience Letter\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:13:30'),
('01a179a2-21b5-724b-af9f-5e9e4a03849a', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'settings.updated', 'success', 'settings', NULL, NULL, '{\"changed\":[\"alumni_document_email_enabled\"]}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:13:51'),
('01a179a2-21bb-708c-8859-d1d14de7a138', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"system\",\"title\":\"Platform Update\",\"roles\":[\"hr_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:13:51'),
('01a179a2-3822-70e2-b9c1-147ef9aa0266', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'settings.updated', 'success', 'settings', NULL, NULL, '{\"changed\":[\"alumni_document_email_enabled\"]}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:13:56'),
('01a179a2-3827-73d4-9658-32d1783120c0', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"system\",\"title\":\"Platform Update\",\"roles\":[\"hr_admin\"],\"recipients\":2}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:13:56'),
('01a179a3-eb64-712f-97f6-2ce41827f633', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document_category.updated', 'success', 'documents', NULL, '01a0a6f0-32bd-706e-89e1-a98cf8c0c7c7', '{\"name\":\"Reliving letter\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:15:48'),
('01a179a4-3cce-701a-b45e-b06549378c74', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document_category.created', 'success', 'documents', NULL, '01a179a4-3ccb-70fe-8c04-86c7d01db979', '{\"name\":\"promotion letter\",\"hrEnabled\":false}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:16:09'),
('01a179a4-4e9f-72f4-939f-07d3fc85405a', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document_category.hr_access_changed', 'success', 'documents', NULL, '01a179a4-3ccb-70fe-8c04-86c7d01db979', '{\"name\":\"promotion letter\",\"hrEnabled\":true}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:16:13'),
('01a179a4-c621-71f3-b17e-c36415b21289', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'document_category.deleted', 'success', 'documents', NULL, '01a179a4-3ccb-70fe-8c04-86c7d01db979', '{\"name\":\"promotion letter\",\"documentsOrphaned\":0,\"forced\":false}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:16:44'),
('01a179b6-8a20-72a5-9e90-cae4e64a6cd1', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:36:08'),
('01a179b7-b626-7013-97da-cdacbf14e30d', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"ALM1006\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:37:25'),
('01a179b7-d63d-7224-859a-3f3a9fe99619', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', 'alumni', 'account.auto_locked', 'failure', 'auth', NULL, NULL, '{\"attempts\":5,\"lockedForMinutes\":15}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:37:33'),
('01a179b7-d63e-725f-b907-f3fd6233c0da', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"ALM1006\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:37:33'),
('01a179b7-e980-7288-9175-44b47cbed1ef', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', 'alumni', 'account.auto_locked', 'failure', 'auth', NULL, NULL, '{\"attempts\":6,\"lockedForMinutes\":15}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:37:38'),
('01a179b7-e981-70ed-bc62-3023aa5070ed', NULL, NULL, NULL, 'auth.login', 'failure', 'auth', NULL, NULL, '{\"vkollabId\":\"ALM1006\",\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:37:38'),
('01a179b8-c504-7267-bce6-27324f0c042e', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', NULL, 'email.forgot_password_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"po***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-10-27 02:38:34'),
('01a179b9-c629-7283-9ff0-48330a1fbb2f', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'failure', 'alumni', NULL, NULL, '{\"row\":2,\"vkollabId\":\"ALM1004\",\"errors\":\"Missing Department, Missing Designation\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:39:40'),
('01a179b9-f9cf-7247-a37d-ac2e1e8397e4', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', 'alumni', 'auth.password_reset', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:39:53'),
('01a179ba-33c5-73f1-bf1b-c328d4e22ee1', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', 'alumni', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"locked\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:40:08'),
('01a179ba-a61c-73f2-bbb5-cdfed1a6e35a', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', 'alumni', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"locked\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:40:37'),
('01a179bb-5753-7097-a396-04c8c75822d3', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-10-27 02:41:23'),
('01a179bb-bbe3-7075-8e2a-0dad2a919466', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'alumni', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:41:48'),
('01a179bc-c76b-7122-bf81-99b2699a2f62', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'failure', 'alumni', NULL, NULL, '{\"row\":2,\"vkollabId\":\"ALM2002\",\"errors\":\"Invalid PF Number (KA\\/BNG\\/00123\\/456) - must be exactly 22 alphanumeric characters, PAN already used by ALM2001, Email already used by ALM2001\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:42:57'),
('01a179bf-42f6-7337-a165-3a83a296a17e', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'failure', 'alumni', NULL, NULL, '{\"row\":2,\"vkollabId\":\"ALM2002\",\"errors\":\"Invalid PF Number (KA\\/BNG\\/00123\\/453) - must be exactly 22 alphanumeric characters\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:45:40');
INSERT INTO `audit_logs` (`id`, `user_id`, `actor_name`, `actor_role`, `action`, `status`, `module`, `subject_type`, `subject_id`, `meta`, `ip_address`, `user_agent`, `created_at`) VALUES
('01a179c0-3308-736c-b3ca-6813c40047bc', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'failure', 'alumni', NULL, NULL, '{\"row\":2,\"vkollabId\":\"ALM2002\",\"errors\":\"Invalid PF Number (KA\\/BNG\\/00123\\/4654) - must be exactly 22 alphanumeric characters\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:46:41'),
('01a179c0-7f3c-7156-8e15-b075d6f696d4', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'failure', 'alumni', NULL, NULL, '{\"row\":2,\"vkollabId\":\"ALM2002\",\"errors\":\"Invalid PF Number (KA\\/BNG\\/00123\\/46987654) - must be exactly 22 alphanumeric characters\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:47:01'),
('01a179c1-5aa4-714d-8cd4-4a88fc991599', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'failure', 'alumni', NULL, NULL, '{\"row\":2,\"vkollabId\":\"ALM2002\",\"errors\":\"Invalid PF Number (TS\\/HYD\\/98765\\/43210) - must be exactly 22 alphanumeric characters\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:47:57'),
('01a179c1-f7ca-71bc-8d77-45091251096b', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'failure', 'alumni', NULL, NULL, '{\"row\":2,\"vkollabId\":\"ALM2002\",\"errors\":\"Invalid PF Number (TS\\/HYD\\/98765\\/43210) - must be exactly 22 alphanumeric characters\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:48:37'),
('01a179c2-bfa5-735a-a095-3fc167bc9623', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'failure', 'alumni', NULL, NULL, '{\"row\":2,\"vkollabId\":\"ALM2002\",\"errors\":\"Invalid PF Number (TSHYD9876543210ABCD12) - must be exactly 22 alphanumeric characters\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:49:28'),
('01a179c5-1908-7113-8ca7-423b5679b66f', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_master.imported', 'success', 'alumni', NULL, '01a179c4-4583-705c-929f-0c1f7d277082', '{\"file\":\"alumni-master-template.xlsxs.xlsx\",\"mode\":\"update\",\"created\":0,\"updated\":1,\"skipped\":0,\"failed\":0}', '49.205.164.180', NULL, '2026-10-27 02:52:02'),
('01a179c5-1919-7382-ade5-950dd29a23e2', NULL, NULL, NULL, 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"alumni\",\"title\":\"Alumni Master Imported\",\"roles\":[\"super_admin\"],\"recipients\":2}', '127.0.0.1', 'Symfony', '2026-10-27 02:52:02'),
('01a179c5-dee5-72ac-9382-9b96c6ecc62d', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'failure', 'alumni', NULL, NULL, '{\"row\":1,\"errors\":\"Invalid file uploaded. Please use the Alumni Import Template.\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:52:53'),
('01a179c6-fa0e-718f-9278-1be3050b96b8', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_master.imported', 'success', 'alumni', NULL, '01a179c6-9937-72e3-9a15-4580717fa36b', '{\"file\":\"alumni-master-template.xlsxs.xlsx\",\"mode\":\"update\",\"created\":0,\"updated\":1,\"skipped\":0,\"failed\":0}', '49.205.164.180', NULL, '2026-10-27 02:54:05'),
('01a179c6-fa20-73bb-9cbd-8aa7dd0172b6', NULL, NULL, NULL, 'notification.sent', 'success', 'notifications', NULL, NULL, '{\"type\":\"alumni\",\"title\":\"Alumni Master Imported\",\"roles\":[\"super_admin\"],\"recipients\":2}', '127.0.0.1', 'Symfony', '2026-10-27 02:54:05'),
('01a179c7-0654-7307-80fe-62e9582f84fd', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"po***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-10-27 02:54:08'),
('01a179c7-797e-719c-9b31-bb3e29f026f0', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', 'alumni', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:54:38'),
('01a179c7-fd8b-731a-bb2d-fe4a13a411de', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'super_admin', 'alumni_import.validation_failed', 'failure', 'alumni', NULL, NULL, '{\"row\":2,\"vkollabId\":\"ALM2002\",\"errors\":\"Missing Designation, Missing UAN Number\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:55:12'),
('01a179ca-7f79-726b-b5bd-0710ffa66f12', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', 'alumni', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:57:56'),
('01a179cb-4aa6-7310-ba38-f0919882efa9', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-10-27 02:58:48'),
('01a179cb-dd78-7085-9333-bdf78fd1fd26', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'alumni', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:59:26'),
('01a179cb-fa86-72c9-adbe-feb292239250', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'alumni', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:59:33'),
('01a179cc-059e-73b3-b98e-917d96516415', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'alumni', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:59:36'),
('01a179cc-16b2-7232-98ae-90274ef2a7f2', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'alumni', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:59:40'),
('01a179cc-2a43-704d-b00e-e35349d0f82e', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'alumni', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:59:45'),
('01a179cc-40d1-7320-8749-5f9c0e9578cd', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'alumni', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 02:59:51'),
('01a179cd-809c-73cf-9f96-5564b51d6ebe', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'alumni', 'auth.logout', 'success', 'auth', NULL, NULL, NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-27 03:01:13'),
('01a179d1-78d5-73e7-ac66-786cb891239f', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"pa***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-10-27 03:05:33'),
('01a179d9-c99d-72a4-ae01-88aaeb28a977', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', NULL, 'email.login_otp_sent', 'success', 'auth', NULL, NULL, '{\"email\":\"po***@gmail.com\",\"status\":\"queued\"}', NULL, NULL, '2026-10-27 03:14:38'),
('01a179da-4ec9-70b2-9bc9-ec8c6d9fda09', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', 'alumni', 'auth.login', 'failure', 'auth', NULL, NULL, '{\"reason\":\"invalid_otp\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 03:15:12'),
('01a179da-d379-7257-a041-eb9b46a08cca', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', 'alumni', 'auth.login', 'success', 'auth', NULL, NULL, '{\"realm\":\"alumni\"}', '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', '2026-10-27 03:15:46');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `content_blocks`
--

CREATE TABLE `content_blocks` (
  `id` char(36) NOT NULL,
  `key` varchar(40) NOT NULL,
  `title` varchar(160) NOT NULL,
  `body` longtext NOT NULL,
  `updated_by` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `content_blocks`
--

INSERT INTO `content_blocks` (`id`, `key`, `title`, `body`, `updated_by`, `created_at`, `updated_at`) VALUES
('01a09205-3d52-7257-bc93-702adb6c2f2f', 'landing_about', 'The trusted enterprise home for VKollab Alumni.', 'VKONNECT is VKollab Technologies\' enterprise alumni engagement platform — built to formalize and sustain the organization\'s relationship with its alumni community for the long term. It brings together verified alumni identity, professional networking, career opportunities, and secure document access in a single governed environment, so the relationship an alumnus built with VKollab continues to grow long after their tenure.', '01a09225-0284-7153-8eb0-bd4f0484a94a', '2026-09-12 02:50:12', '2026-10-26 20:07:21'),
('01a09205-3eb1-709f-8930-6cc4edcba08a', 'landing_hero', 'Your Career at VKollab Doesn\'t End Here', 'VKONNECT is the trusted enterprise platform connecting VKollab Technologies alumni for life — lifelong professional networking, career growth opportunities, secure access to your employment documents, and priority visibility into new roles across the organization and its partner network.', NULL, '2026-09-12 02:50:12', '2026-09-12 02:50:12'),
('01a09205-3eb3-73ae-836c-fbe29598984a', 'landing_cta', 'Your VKollab alumni identity, ready when you are.', 'Join VKONNECT to unlock lifelong networking, career opportunities, and secure access to your documents — all in one trusted enterprise platform.', NULL, '2026-09-12 02:50:12', '2026-09-12 02:50:12'),
('8346e495-dcd1-4764-973f-843662038b6f', 'terms', 'Terms & Conditions', 'The portal will also require Terms & Conditions that users can review and accept while using the platform.\n\nI searched the VKollab website but could not locate a Terms & Conditions page. Please share the approved Terms & Conditions document or confirm if a new one needs to be drafted.\n\nRequirement Summary\n\nPlease provide:\n\nOfficial alumni email ID (e.g., alumni@vkollab.com)\nPrivacy Policy document/content\nTerms & Conditions document/content\nThese items are required for completing the authentication, notification, compliance, and user onboarding features of the VKONNECT Alumni Portal.', '01a09225-0284-7153-8eb0-bd4f0484a94a', '2026-09-13 00:39:30', '2026-09-13 00:48:17'),
('b77ca255-ead9-409c-b814-e76796cc17ba', 'privacy', 'Privacy Policy', 'The alumni portal will collect and manage alumni information, including:\n\nEmployee ID\nPersonal Email Address\nMobile Number\nPAN Details\nEmployment Information\nDocuments (Form 16, Payslips, Experience Letters, etc.)\nTo ensure compliance and proper user consent, we require an official Privacy Policy document for VKollab.\n\nI searched the VKollab website but could not find an accessible Privacy Policy page. Please share the latest approved Privacy Policy or let us know if one needs to be created.', '01a09225-0284-7153-8eb0-bd4f0484a94a', '2026-09-13 00:39:30', '2026-09-13 00:47:38');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` char(36) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `alumni_master_id` char(36) DEFAULT NULL,
  `category_id` char(36) DEFAULT NULL,
  `uploaded_by` char(36) DEFAULT NULL,
  `title` varchar(160) NOT NULL,
  `financial_year` varchar(9) DEFAULT NULL,
  `period_month` tinyint(3) UNSIGNED DEFAULT NULL,
  `disk_path` varchar(255) NOT NULL,
  `original_name` varchar(200) NOT NULL,
  `mime_type` varchar(120) NOT NULL,
  `size_kb` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `user_id`, `alumni_master_id`, `category_id`, `uploaded_by`, `title`, `financial_year`, `period_month`, `disk_path`, `original_name`, `mime_type`, `size_kb`, `created_at`, `updated_at`, `deleted_at`) VALUES
('01a096c5-193a-7245-a6e7-c07d8a8913f1', '01a0963f-fb96-72f9-b366-52fd81d9d235', NULL, '01a09205-3edc-73de-a58a-e707c9391466', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'PAN', NULL, NULL, 'documents/01a0963f-fb96-72f9-b366-52fd81d9d235/8583002_PERSONAL_DOCS.pdf', '8583002_PERSONAL_DOCS.pdf', 'application/pdf', 13, '2026-09-13 00:58:14', '2026-09-22 00:53:24', NULL),
('01a0a100-a4f3-72df-b73b-26a00c502330', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', NULL, '01a09205-3e04-7163-9576-b1a3037767ef', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Salary Slips — FY 2025-2026', '2025-2026', 9, 'documents/01a0a00f-8e23-711d-b2c4-1b9cf2b46163/ALM1001_SALARY_SLIPS_09_2025.pdf', 'ALM1001_SALARY_SLIPS_09_2025.pdf', 'application/pdf', 13, '2026-09-15 00:39:29', '2026-09-15 00:39:29', NULL),
('01a0aa1c-0213-73ea-9578-a8384b2cc5d7', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', NULL, '01a09205-3e04-7163-9576-b1a3037767ef', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Salary Slips — FY 2025-2026', '2025-2026', 9, 'documents/01a0a00f-8e23-711d-b2c4-1b9cf2b46163/ALM1001_SALARY_SLIPS_09_2025_2.pdf', 'ALM1001_SALARY_SLIPS_09_2025_2.pdf', 'application/pdf', 13, '2026-09-16 19:05:57', '2026-09-16 19:05:57', NULL),
('01a0c5d7-edce-7182-a4c0-d09dfcba433f', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', NULL, '01a09205-3e04-7163-9576-b1a3037767ef', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Salary Slips — FY 2025-2026', '2025-2026', 9, 'documents/01a0a00f-8e23-711d-b2c4-1b9cf2b46163/ALM1001_SALARY_SLIPS_09_2025_3.pdf', 'ALM1001_SALARY_SLIPS_09_2025_3.pdf', 'application/pdf', 13, '2026-09-22 04:20:57', '2026-09-22 04:20:57', NULL),
('01a0ed53-11d7-7291-9ee3-1c91d3f67b90', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', NULL, '01a09205-3df5-71e7-bb62-58e02998c2b7', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Experience Letter', NULL, NULL, 'documents/01a0b5d2-245f-71e1-93d4-5b86c83fb723/ALM1003_EXPERIENCE_LETTER.pdf', 'ALM1003_EXPERIENCE_LETTER.pdf', 'application/pdf', 13, '2026-09-29 20:20:39', '2026-09-29 20:20:39', NULL),
('01a0ed53-d259-7278-99b8-5de53296d297', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', NULL, '01a09205-3edc-73de-a58a-e707c9391466', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Increment Letter', NULL, NULL, 'documents/01a0b5d2-245f-71e1-93d4-5b86c83fb723/ALM1003_INCREMENT_LETTER.pdf', 'ALM1003_INCREMENT_LETTER.pdf', 'application/pdf', 13, '2026-09-29 20:21:28', '2026-09-29 20:21:28', NULL),
('01a0ed54-d01f-7122-9997-6affb7687a3d', '01a0963f-fb96-72f9-b366-52fd81d9d235', NULL, '01a09205-3e90-7283-8886-192598f5b9a4', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'updated Full & Final Settlement', NULL, NULL, 'documents/01a0963f-fb96-72f9-b366-52fd81d9d235/8583002_FULL_FINAL_SETTLEMENT.pdf', '8583002_FULL_FINAL_SETTLEMENT.pdf', 'application/pdf', 13, '2026-09-29 20:22:33', '2026-09-29 20:22:33', NULL),
('01a0ed7b-b68e-730d-b3ae-06337639a78e', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', NULL, '01a0a6f0-32bd-706e-89e1-a98cf8c0c7c7', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Reliving letters', NULL, NULL, 'documents/01a0b5d2-245f-71e1-93d4-5b86c83fb723/ALM1003_RELIVING_LETTERS.pdf', 'ALM1003_RELIVING_LETTERS.pdf', 'application/pdf', 227, '2026-09-29 21:05:02', '2026-09-29 21:05:02', NULL),
('01a0ed7f-8704-7223-a63b-fdfd96ddc26b', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', NULL, '01a09205-3e90-7283-8886-192598f5b9a4', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'updated Full & Final Settlement', NULL, NULL, 'documents/01a0b5d2-245f-71e1-93d4-5b86c83fb723/ALM1003_FULL_FINAL_SETTLEMENT.pdf', 'ALM1003_FULL_FINAL_SETTLEMENT.pdf', 'application/pdf', 227, '2026-09-29 21:09:12', '2026-09-29 21:10:32', '2026-09-29 21:10:32'),
('01a17836-9c37-7221-a1f1-bc4ab06bc8d1', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', NULL, '01a09205-3e04-7163-9576-b1a3037767ef', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Salary Slips — FY 2026-2027', '2026-2027', 10, 'documents/01a17835-ee0e-73f4-9d39-2ed7ea978df3/ALM1006_SALARY_SLIPS_10_2026.pdf', 'ALM1006_SALARY_SLIPS_10_2026.pdf', 'application/pdf', 13, '2026-10-26 19:36:47', '2026-10-26 19:36:47', NULL),
('01a1797c-14e6-7231-bd0b-f2273c565eb3', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', NULL, '01a09205-3e04-7163-9576-b1a3037767ef', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Salary Slips — FY 2026-2027', '2026-2027', 10, 'documents/01a17835-ee0e-73f4-9d39-2ed7ea978df3/ALM1006_SALARY_SLIPS_10_2026_2.pdf', 'ALM1006_SALARY_SLIPS_10_2026_2.pdf', 'application/pdf', 13, '2026-10-27 01:32:17', '2026-10-27 01:32:17', NULL),
('01a1799e-20ac-7176-a1c8-ff823eedb893', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', NULL, '01a09205-3df5-71e7-bb62-58e02998c2b7', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'Experience Letter', NULL, NULL, 'documents/01a0b5d2-245f-71e1-93d4-5b86c83fb723/ALM1003_EXPERIENCE_LETTER_2.pdf', 'ALM1003_EXPERIENCE_LETTER_2.pdf', 'application/pdf', 227, '2026-10-27 02:09:28', '2026-10-27 02:09:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `document_categories`
--

CREATE TABLE `document_categories` (
  `id` char(36) NOT NULL,
  `name` varchar(80) NOT NULL,
  `slug` varchar(80) NOT NULL,
  `filename_code` varchar(40) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `sort_order` smallint(6) NOT NULL DEFAULT 0,
  `hr_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `document_categories`
--

INSERT INTO `document_categories` (`id`, `name`, `slug`, `filename_code`, `description`, `sort_order`, `hr_enabled`, `created_at`, `updated_at`) VALUES
('01a09205-3df5-71e7-bb62-58e02998c2b7', 'Experience Letter', 'experience-letter', 'EXPERIENCE_LETTERR', 'Confirmation of tenure and separation from the organization', 6, 1, '2026-09-12 02:50:12', '2026-10-27 02:13:30'),
('01a09205-3e04-7163-9576-b1a3037767ef', 'Salary Slips', 'salary-slips', 'SALARY_SLIPS', 'Most recent six months of salary slips', 4, 1, '2026-09-12 02:50:12', '2026-09-12 02:50:12'),
('01a09205-3e07-725f-8490-37823a7bac03', 'Form 16', 'form-16', 'FORM16', 'Annual tax deduction certificate', 3, 1, '2026-09-12 02:50:12', '2026-09-12 02:50:12'),
('01a09205-3e90-7283-8886-192598f5b9a4', 'updated Full & Final Settlement', 'full-and-final-settlement', 'FULL_FINAL_SETTLEMENT', 'Final settlement statement on exit', 5, 1, '2026-09-12 02:50:12', '2026-09-16 04:22:47'),
('01a09205-3edc-73de-a58a-e707c9391466', 'Personal Documents', 'personal-documents', 'PERSONAL_DOCS', 'Any other personal document not covered by a dedicated category', 0, 1, '2026-09-12 02:50:12', '2026-10-27 02:11:58'),
('01a0a6f0-32bd-706e-89e1-a98cf8c0c7c7', 'Reliving letter', 'reliving-letters', 'RELIVING_LETTERS', NULL, 0, 1, '2026-09-16 04:19:14', '2026-10-27 02:15:48');

-- --------------------------------------------------------

--
-- Table structure for table `document_downloads`
--

CREATE TABLE `document_downloads` (
  `id` char(36) NOT NULL,
  `document_id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `downloaded_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `document_downloads`
--

INSERT INTO `document_downloads` (`id`, `document_id`, `user_id`, `ip_address`, `downloaded_at`) VALUES
('01a096ca-40f3-700d-b440-7eb8f71e6812', '01a096c5-193a-7245-a6e7-c07d8a8913f1', '01a0963f-fb96-72f9-b366-52fd81d9d235', '49.205.164.180', '2026-09-13 01:03:52'),
('01a0aa1c-4431-7193-81a9-e1df0864ff67', '01a0aa1c-0213-73ea-9578-a8384b2cc5d7', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', '49.205.164.180', '2026-09-16 19:06:14'),
('01a0b561-89ce-7238-b294-dfc84f048987', '01a0aa1c-0213-73ea-9578-a8384b2cc5d7', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', '49.205.164.180', '2026-09-18 23:37:43'),
('01a0b561-9cac-71a7-b25a-d5e7feadb474', '01a0aa1c-0213-73ea-9578-a8384b2cc5d7', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', '49.205.164.180', '2026-09-18 23:37:48'),
('01a0ed80-6462-7269-ac1b-c0bf419f7424', '01a0ed7f-8704-7223-a63b-fdfd96ddc26b', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', '49.205.164.180', '2026-09-29 21:10:09'),
('01a17837-3420-722f-8872-10a401f5207e', '01a17836-9c37-7221-a1f1-bc4ab06bc8d1', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '49.205.164.180', '2026-10-26 19:37:26'),
('01a17979-6313-7126-9bae-02ef9d752faf', '01a17836-9c37-7221-a1f1-bc4ab06bc8d1', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', '49.205.164.180', '2026-10-27 01:29:20'),
('01a17980-90fa-71f2-8be8-e6b52c4e320b', '01a0ed53-11d7-7291-9ee3-1c91d3f67b90', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', '49.205.164.180', '2026-10-27 01:37:11'),
('01a17981-aedf-709e-978c-27210934cb01', '01a0ed7b-b68e-730d-b3ae-06337639a78e', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', '49.205.164.180', '2026-10-27 01:38:24'),
('01a17986-5059-7147-9be6-c37177000bec', '01a0ed53-d259-7278-99b8-5de53296d297', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', '49.205.164.180', '2026-10-27 01:43:27'),
('01a1799d-9d21-703d-8eb9-1070e12a0661', '01a0ed53-11d7-7291-9ee3-1c91d3f67b90', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', '49.205.164.180', '2026-10-27 02:08:54');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr_admins`
--

CREATE TABLE `hr_admins` (
  `id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `vkollab_id` varchar(40) DEFAULT NULL,
  `department` varchar(120) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_admins`
--

INSERT INTO `hr_admins` (`id`, `user_id`, `vkollab_id`, `department`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('01a09277-60cb-73b5-a21b-f01abcc4b84f', '01a09277-60bd-71b1-9fad-a03afb7db784', 'HR1002', 'Human Resource', '01a09225-0284-7153-8eb0-bd4f0484a94a', '2026-09-12 04:54:52', '2026-09-12 04:54:52', NULL),
('01a0a6c2-2b26-71b6-95de-41197d2de663', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'HR1001', 'Human Resource', '01a09225-0284-7153-8eb0-bd4f0484a94a', '2026-09-16 03:28:58', '2026-09-16 03:28:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `id` char(36) NOT NULL,
  `job_posting_id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'submitted',
  `resume_path` varchar(255) DEFAULT NULL,
  `cover_note` text DEFAULT NULL,
  `reviewed_by` char(36) DEFAULT NULL,
  `applied_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_applications`
--

INSERT INTO `job_applications` (`id`, `job_posting_id`, `user_id`, `status`, `resume_path`, `cover_note`, `reviewed_by`, `applied_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
('01a0aa71-5b3e-71a6-83ac-890b0ff32215', '01a0a6fb-e729-71a6-acea-20ca3960fa68', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'submitted', 'resumes/01a0a00f-8e23-711d-b2c4-1b9cf2b46163/qKQKUGWm6THEme87B7ZjIQFC3aUahhND7YsjEkk1.pdf', 'fffd', NULL, '2026-09-16 20:39:10', '2026-09-16 20:39:10', '2026-09-16 20:39:10', NULL),
('01a17844-316a-70f1-9700-e36d4a353fbb', '01a1783a-587b-7072-af47-56ca02afb77a', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'shortlisted', NULL, NULL, '01a0a6c2-2b02-7173-8043-3439b8d31b03', '2026-10-26 19:51:37', '2026-10-26 19:51:37', '2026-10-26 22:55:56', NULL),
('01a17985-0b65-7045-ace9-e5c65eee90aa', '01a1783a-587b-7072-af47-56ca02afb77a', '01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'submitted', 'resumes/01a0b5d2-245f-71e1-93d4-5b86c83fb723/ImlDueAhaLzOy2f8t46Uvcz9yMdlc1WTgzM9nc5O.pdf', NULL, NULL, '2026-10-27 01:42:04', '2026-10-27 01:42:04', '2026-10-27 01:42:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_postings`
--

CREATE TABLE `job_postings` (
  `id` char(36) NOT NULL,
  `posted_by` char(36) DEFAULT NULL,
  `title` varchar(160) NOT NULL,
  `organization` varchar(160) NOT NULL,
  `location` varchar(120) NOT NULL,
  `type` varchar(24) NOT NULL DEFAULT 'full_time',
  `is_remote` tinyint(1) NOT NULL DEFAULT 0,
  `description` longtext NOT NULL,
  `requirements` longtext DEFAULT NULL,
  `status` varchar(16) NOT NULL DEFAULT 'open',
  `closes_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_postings`
--

INSERT INTO `job_postings` (`id`, `posted_by`, `title`, `organization`, `location`, `type`, `is_remote`, `description`, `requirements`, `status`, `closes_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
('01a09fe8-3fe1-7397-a519-8f856698251a', '01a09277-60bd-71b1-9fad-a03afb7db784', 'SAP ABAP DEVELOPER', 'VKollab Technologies', 'Guntur', 'full_time', 1, 'ddfdjdjfd jdjdfdf jdd kdkkk', NULL, 'open', NULL, '2026-09-14 19:33:13', '2026-09-16 04:30:56', '2026-09-16 04:30:56'),
('01a0a529-3380-73f6-8e32-51b82bd4d066', '01a09277-60bd-71b1-9fad-a03afb7db784', 'SAP CPI DEVELOPER', 'VKollab Technologies', 'Hyderabad and Guntur', 'full_time', 0, 'sap cpi developer with 5 years of experience', NULL, 'closed', NULL, '2026-09-15 20:02:15', '2026-09-16 04:29:55', NULL),
('01a0a6fb-e729-71a6-acea-20ca3960fa68', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Supply chain management', 'VKollab Technologies', 'Guntur', 'full_time', 0, 'dddddfdfdf', NULL, 'open', NULL, '2026-09-16 04:32:01', '2026-09-16 04:32:01', NULL),
('01a1783a-587b-7072-af47-56ca02afb77a', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'AI Developer', 'VKollab Technologies', 'Guntur', 'contract', 0, 'we require 3+ years of experianced candidates', NULL, 'open', NULL, '2026-10-26 19:40:52', '2026-10-26 19:40:52', NULL),
('01a1798b-e779-7262-968b-995efa7fc27d', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'AI Developer Position', 'VKollab Technologies', 'Guntur - Andhra Pradesh', 'full_time', 1, 'Looking for an AI Developer with knowledge of Python, Machine Learning, and Generative AI to build and support AI-powered applications.', NULL, 'closed', NULL, '2026-10-27 01:49:34', '2026-10-27 01:55:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_12_204238_create_personal_access_tokens_table', 1),
(5, '2026_06_12_204239_create_permission_tables', 1),
(6, '2026_06_13_000001_create_profile_tables', 1),
(7, '2026_06_13_000002_create_communication_and_career_tables', 1),
(8, '2026_06_13_000003_create_documents_and_applications_tables', 1),
(9, '2026_06_13_000004_create_notifications_table', 1),
(10, '2026_06_13_000006_create_settings_table', 1),
(11, '2026_06_13_000007_create_role_permission_defaults_table', 1),
(12, '2026_06_13_000008_create_audit_logs_table', 1),
(13, '2026_06_13_000009_add_employee_id_to_users_table', 1),
(14, '2026_06_15_000001_create_alumni_master_records', 1),
(15, '2026_06_15_000002_create_content_blocks_table', 1),
(16, '2026_06_15_000003_create_alerts_table', 1),
(17, '2026_06_15_000004_rename_employee_id_to_vkollab_id', 1),
(18, '2026_06_15_000005_extend_alumni_masters_and_import_batches', 1),
(19, '2026_07_21_000001_add_alumni_control_columns_to_users_table', 1),
(20, '2026_07_22_000001_add_permissions_customized_at_to_users_table', 1),
(21, '2026_07_22_000002_backfill_dashboard_view_permission', 1),
(22, '2026_07_22_000003_remove_require_hr_approval_setting', 1),
(23, '2026_07_22_000004_add_status_to_audit_logs_table', 1),
(24, '2026_07_22_000005_add_footer_phone_and_map_url_settings', 1),
(25, '2026_07_23_000001_regroup_contact_settings', 1),
(26, '2026_07_23_000002_add_landing_about_content_block', 1),
(27, '2026_07_24_000001_add_uniqueness_constraints_for_data_integrity', 1),
(28, '2026_07_27_000001_remove_dead_security_settings', 1),
(29, '2026_07_27_000002_add_failed_login_attempts_to_users_table', 1),
(30, '2026_07_27_000003_add_super_admin_scoped_permissions', 1),
(31, '2026_07_28_000001_add_document_categories_manage_permission', 1),
(32, '2026_07_29_153301_make_documents_financial_year_nullable', 1),
(33, '2026_07_30_000001_normalize_document_categories_to_five_types', 1),
(34, '2026_07_30_003410_add_hr_managed_fields_to_alumni_profiles_and_masters', 1),
(35, '2026_07_30_010000_remap_document_categories_to_personal_documents', 1),
(36, '2026_07_30_020000_add_filename_code_to_document_categories', 1),
(37, '2026_07_30_030000_add_landing_page_content_and_settings', 1),
(38, '2026_07_30_040000_add_supervisor_email_to_alumni_profiles_and_masters', 1),
(39, '2026_07_30_050000_add_personal_documents_category', 1),
(40, '2026_07_30_060000_fold_pan_aadhaar_into_personal_documents', 1),
(41, '2026_07_30_060000_rename_salary_slips_category', 1),
(42, '2026_07_30_070000_add_profile_disabled_at_to_users_table', 1),
(43, '2026_07_30_080000_add_user_id_to_alerts_table', 1),
(44, '2026_07_31_000001_exclude_soft_deleted_users_from_unique_constraints', 1),
(45, '2026_07_31_000002_exclude_soft_deleted_roster_rows_from_unique_constraints', 1),
(46, '2026_07_31_000003_allow_documents_against_roster_records', 1),
(47, '2026_07_31_000004_add_hr_capability_columns_to_users_table', 1),
(48, '2026_07_31_000005_add_announcement_and_service_request_capability_columns', 1),
(49, '2026_08_04_000001_add_alumni_document_email_setting', 1),
(50, '2026_08_04_000002_remove_maintenance_mode_setting', 1),
(51, '2026_08_05_000001_move_document_email_setting_to_documents_group', 1),
(52, '2026_08_05_000002_align_capability_columns_with_feature_registry', 1),
(53, '2026_08_05_000003_default_hr_document_categories_switch_to_off', 1),
(54, '2026_08_06_000001_restore_service_requests_capability_column', 1),
(55, '2026_08_06_000002_add_service_requests_platform_setting', 1),
(56, '2026_08_06_000003_allow_documents_without_a_category', 1),
(57, '2026_08_06_000004_add_hr_enabled_to_document_categories', 1),
(58, '2026_08_11_000001_store_audit_meta_as_text_not_json', 1),
(59, '2026_08_11_000002_widen_locked_until_beyond_the_timestamp_epoch', 1),
(60, '2026_08_20_000001_add_period_month_to_documents', 1),
(61, '2026_08_26_000001_widen_pan_and_add_blind_index_column', 1),
(62, '2026_08_26_000002_backfill_encrypted_pan_and_hash', 1),
(63, '2026_08_26_000003_move_pan_unique_index_onto_blind_index', 2);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(1, 'App\\Models\\User', '01a0963f-fb96-72f9-b366-52fd81d9d235'),
(1, 'App\\Models\\User', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163'),
(1, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(1, 'App\\Models\\User', '01a0b5d2-245f-71e1-93d4-5b86c83fb723'),
(1, 'App\\Models\\User', '01a17835-ee0e-73f4-9d39-2ed7ea978df3'),
(5, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(5, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(6, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(6, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(7, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(7, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(8, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(8, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(9, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(9, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(10, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(10, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(11, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(11, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(12, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(12, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(13, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(13, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(14, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(14, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(15, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(15, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(16, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(16, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(17, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(17, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(18, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(18, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(19, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(19, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(20, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(20, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(21, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(21, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(22, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(22, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(23, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(23, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(24, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(24, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(25, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(25, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(26, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(26, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(27, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(27, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(28, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(28, 'App\\Models\\User', '01a0963f-fb96-72f9-b366-52fd81d9d235'),
(28, 'App\\Models\\User', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163'),
(28, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(28, 'App\\Models\\User', '01a0b5d2-245f-71e1-93d4-5b86c83fb723'),
(28, 'App\\Models\\User', '01a17835-ee0e-73f4-9d39-2ed7ea978df3'),
(29, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(29, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(30, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(30, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(31, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(31, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(32, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(32, 'App\\Models\\User', '01a0963f-fb96-72f9-b366-52fd81d9d235'),
(32, 'App\\Models\\User', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163'),
(32, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(32, 'App\\Models\\User', '01a0b5d2-245f-71e1-93d4-5b86c83fb723'),
(32, 'App\\Models\\User', '01a17835-ee0e-73f4-9d39-2ed7ea978df3'),
(33, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(33, 'App\\Models\\User', '01a0963f-fb96-72f9-b366-52fd81d9d235'),
(33, 'App\\Models\\User', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163'),
(33, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(33, 'App\\Models\\User', '01a0b5d2-245f-71e1-93d4-5b86c83fb723'),
(33, 'App\\Models\\User', '01a17835-ee0e-73f4-9d39-2ed7ea978df3'),
(34, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(34, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(35, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(35, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(36, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(36, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(37, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(37, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(38, 'App\\Models\\User', '01a0963f-fb96-72f9-b366-52fd81d9d235'),
(38, 'App\\Models\\User', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163'),
(38, 'App\\Models\\User', '01a0b5d2-245f-71e1-93d4-5b86c83fb723'),
(38, 'App\\Models\\User', '01a17835-ee0e-73f4-9d39-2ed7ea978df3'),
(39, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(39, 'App\\Models\\User', '01a0963f-fb96-72f9-b366-52fd81d9d235'),
(39, 'App\\Models\\User', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163'),
(39, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(39, 'App\\Models\\User', '01a0b5d2-245f-71e1-93d4-5b86c83fb723'),
(39, 'App\\Models\\User', '01a17835-ee0e-73f4-9d39-2ed7ea978df3'),
(40, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(40, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(41, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(41, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(42, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(42, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(43, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(43, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(44, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(44, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(45, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(45, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(46, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(46, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(47, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(47, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(48, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(48, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(49, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(49, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(50, 'App\\Models\\User', '01a0963f-fb96-72f9-b366-52fd81d9d235'),
(50, 'App\\Models\\User', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163'),
(50, 'App\\Models\\User', '01a0b5d2-245f-71e1-93d4-5b86c83fb723'),
(50, 'App\\Models\\User', '01a17835-ee0e-73f4-9d39-2ed7ea978df3'),
(51, 'App\\Models\\User', '01a0963f-fb96-72f9-b366-52fd81d9d235'),
(51, 'App\\Models\\User', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163'),
(51, 'App\\Models\\User', '01a0b5d2-245f-71e1-93d4-5b86c83fb723'),
(51, 'App\\Models\\User', '01a17835-ee0e-73f4-9d39-2ed7ea978df3'),
(52, 'App\\Models\\User', '01a0963f-fb96-72f9-b366-52fd81d9d235'),
(52, 'App\\Models\\User', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163'),
(52, 'App\\Models\\User', '01a0b5d2-245f-71e1-93d4-5b86c83fb723'),
(52, 'App\\Models\\User', '01a17835-ee0e-73f4-9d39-2ed7ea978df3'),
(53, 'App\\Models\\User', '01a0963f-fb96-72f9-b366-52fd81d9d235'),
(53, 'App\\Models\\User', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163'),
(53, 'App\\Models\\User', '01a0b5d2-245f-71e1-93d4-5b86c83fb723'),
(53, 'App\\Models\\User', '01a17835-ee0e-73f4-9d39-2ed7ea978df3'),
(54, 'App\\Models\\User', '01a0963f-fb96-72f9-b366-52fd81d9d235'),
(54, 'App\\Models\\User', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163'),
(54, 'App\\Models\\User', '01a0b5d2-245f-71e1-93d4-5b86c83fb723'),
(54, 'App\\Models\\User', '01a17835-ee0e-73f4-9d39-2ed7ea978df3'),
(55, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(55, 'App\\Models\\User', '01a0963f-fb96-72f9-b366-52fd81d9d235'),
(55, 'App\\Models\\User', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163'),
(55, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(55, 'App\\Models\\User', '01a0b5d2-245f-71e1-93d4-5b86c83fb723'),
(55, 'App\\Models\\User', '01a17835-ee0e-73f4-9d39-2ed7ea978df3');

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', '01a09225-0284-7153-8eb0-bd4f0484a94a'),
(1, 'App\\Models\\User', '01a0a105-e6be-7286-8bb1-38a359a6660c'),
(2, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784'),
(2, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03'),
(3, 'App\\Models\\User', '01a0963f-fb96-72f9-b366-52fd81d9d235'),
(3, 'App\\Models\\User', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163'),
(3, 'App\\Models\\User', '01a0b5d2-245f-71e1-93d4-5b86c83fb723'),
(3, 'App\\Models\\User', '01a17835-ee0e-73f4-9d39-2ed7ea978df3');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'dashboard.view', 'web', '2026-09-12 02:50:11', '2026-09-12 02:50:11'),
(2, 'super_admins.manage', 'web', '2026-09-12 02:50:12', '2026-09-12 02:50:12'),
(3, 'content.manage', 'web', '2026-09-12 02:50:12', '2026-09-12 02:50:12'),
(4, 'permissions.manage', 'web', '2026-09-12 02:50:12', '2026-09-12 02:50:12'),
(5, 'document_categories.manage', 'web', '2026-09-12 02:50:12', '2026-09-12 02:50:12'),
(6, 'alumni.view', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(7, 'alumni.edit', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(8, 'alumni.disable', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(9, 'alumni.activate', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(10, 'alumni.delete', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(11, 'alumni.lock', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(12, 'alumni.unlock', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(13, 'alumni.reset_password', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(14, 'alumni.disable_downloads', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(15, 'alumni.disable_documents', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(16, 'alumni.disable_jobs', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(17, 'alumni.disable_applications', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(18, 'alumni.disable_notifications', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(19, 'alumni.disable_ticker', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(20, 'alumni.disable_profile', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(21, 'alumni.disable_announcements', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(22, 'alumni.disable_dashboard', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(23, 'alumni.disable_service_requests', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(24, 'alumni_master.view', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(25, 'alumni_master.import', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(26, 'alumni_master.manage', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(27, 'alumni_master.export', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(28, 'documents.view', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(29, 'documents.upload', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(30, 'documents.replace', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(31, 'documents.delete', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(32, 'documents.download', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(33, 'jobs.view', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(34, 'jobs.create', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(35, 'jobs.edit', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(36, 'jobs.delete', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(37, 'jobs.close', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(38, 'jobs.apply', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(39, 'applications.view', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(40, 'applications.update_status', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(41, 'applications.download_resume', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(42, 'announcements.create', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(43, 'announcements.edit', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(44, 'announcements.delete', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(45, 'ticker.manage', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(46, 'reports.view', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(47, 'reports.export', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(48, 'hr_settings.access', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(49, 'hr_admins.manage', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(50, 'profile.view', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(51, 'profile.edit', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(52, 'announcements.view', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(53, 'notifications.view', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(54, 'service_request.view', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55'),
(55, 'preferences.manage', 'web', '2026-09-12 03:12:55', '2026-09-12 03:12:55');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` char(36) NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(9, 'App\\Models\\User', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'super_admin-token', '538ed2b418b5b79211a0cd686026d785360794e3569903c0df1f28a5284500e0', '[\"super_admin:*\"]', '2026-09-13 04:06:06', '2026-09-14 04:04:45', '2026-09-13 00:19:49', '2026-09-13 04:06:06'),
(15, 'App\\Models\\User', '01a0a105-e6be-7286-8bb1-38a359a6660c', 'super_admin-token', '9caec1564aa70288548e5961ae991a0fc6f1ddaa1072ac54cc14a31c9603275b', '[\"super_admin:*\"]', '2026-09-15 01:35:14', '2026-09-15 02:35:14', '2026-09-15 00:47:38', '2026-09-15 01:35:14'),
(18, 'App\\Models\\User', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'super_admin-token', '3795e149b5e6b001df458f661718f7cfa8187cb6e0019d01688294f0bdd8a9dc', '[\"super_admin:*\"]', '2026-09-15 04:22:10', '2026-09-15 05:20:18', '2026-09-15 03:25:47', '2026-09-15 04:22:10'),
(19, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784', 'hr_admin-token', '993c2f3cc7160182db5cc3ff7643efabfb2e2556b0fd91c1299e8030c01eaadb', '[\"hr_admin:*\"]', '2026-09-15 04:56:56', '2026-09-15 05:52:47', '2026-09-15 03:48:28', '2026-09-15 04:56:56'),
(20, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784', 'hr_admin-token', '32f678089e1070b3db2fa61300f65f60d1e5193730bbacb7a1b9fa771e7c7b1b', '[\"hr_admin:*\"]', '2026-09-15 04:58:35', '2026-09-15 05:57:15', '2026-09-15 03:51:15', '2026-09-15 04:58:35'),
(21, 'App\\Models\\User', '01a09277-60bd-71b1-9fad-a03afb7db784', 'hr_admin-token', '7c7742067ff67e8c45155c73b27962729aa6197e642ba7dcd86019842c21c02f', '[\"hr_admin:*\"]', '2026-09-15 22:05:11', '2026-09-15 23:02:11', '2026-09-15 18:57:01', '2026-09-15 22:05:11'),
(22, 'App\\Models\\User', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'super_admin-token', 'cb7e28107e18a7d9854822394196ddc6076adf63058a68ced8bc203886daa0f0', '[\"super_admin:*\"]', '2026-09-15 22:04:18', '2026-09-15 23:04:08', '2026-09-15 19:14:04', '2026-09-15 22:04:18'),
(23, 'App\\Models\\User', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'super_admin-token', '4c0996e01680ea6cbfe201a1b7c6b0cae9b9c1f97895a014e23830ff2f827a02', '[\"super_admin:*\"]', '2026-09-16 01:58:38', '2026-09-16 02:56:15', '2026-09-16 01:30:40', '2026-09-16 01:58:38'),
(26, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'hr_admin-token', '633196ce7d94aa3ded7bb643e5c26498ea870ffaa94cc67dc4b3e9f1cac2fb55', '[\"hr_admin:*\"]', '2026-09-16 03:51:39', '2026-09-16 04:51:38', '2026-09-16 03:51:38', '2026-09-16 03:51:39'),
(27, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'hr_admin-token', '3920b2056805497ccadc39f16aaca81aa8e0c00180220a062b599fd20cadd7a2', '[\"hr_admin:*\"]', '2026-09-16 04:55:02', '2026-09-16 05:53:11', '2026-09-16 04:11:23', '2026-09-16 04:55:02'),
(29, 'App\\Models\\User', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'alumni-token', '64574a654e7dbedd687ce6a8409409c2f2f5dd33a148b51a39ac1cb3b2ad87bf', '[\"alumni:*\"]', '2026-09-16 22:29:49', '2026-09-16 23:25:50', '2026-09-16 18:29:30', '2026-09-16 22:29:49'),
(36, 'App\\Models\\User', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'alumni-token', '17c28aadf58f3078a369ca964dda436ebd7255aa8e18a67e3158abda16864db8', '[\"alumni:*\"]', '2026-09-17 05:52:13', '2026-09-17 06:51:13', '2026-09-17 00:48:24', '2026-09-17 05:52:13'),
(37, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'hr_admin-token', 'c79bb1d2d35b7c3203a141ac093959919470716c43d014c01acdf31fa883a447', '[\"hr_admin:*\"]', '2026-09-17 02:21:13', '2026-09-17 03:21:13', '2026-09-17 01:28:25', '2026-09-17 02:21:13'),
(40, 'App\\Models\\User', '01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'alumni-token', '7b13bb1ac79ebcf925482002c15454e4228f42656a0ae84797f0e19181e5ed04', '[\"alumni:*\"]', '2026-09-18 23:53:57', '2026-09-19 00:52:57', '2026-09-18 23:36:52', '2026-09-18 23:53:57'),
(41, 'App\\Models\\User', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'super_admin-token', '7697cd45dd2dcc652f274a103ce3fa618bf167a534dd5130e2c63d06c11305e4', '[\"super_admin:*\"]', '2026-09-19 01:57:54', '2026-09-19 02:57:54', '2026-09-19 01:36:36', '2026-09-19 01:57:54'),
(43, 'App\\Models\\User', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'super_admin-token', 'bda0fe698c26bfed15cc17263e94764e220487fef1c36318f68622efc6a2d018', '[\"super_admin:*\"]', '2026-09-21 23:00:12', '2026-09-21 23:59:51', '2026-09-21 20:13:49', '2026-09-21 23:00:12'),
(44, 'App\\Models\\User', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'super_admin-token', 'ada0ff4aed5b35e12a51809e8a6d10446b82a1cb65591ba1b17fc34356487d6e', '[\"super_admin:*\"]', '2026-09-22 05:01:44', '2026-09-22 06:01:44', '2026-09-22 00:23:19', '2026-09-22 05:01:44'),
(45, 'App\\Models\\User', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'super_admin-token', 'ca113bfff69b379e60686e9078cb3339398af3131376f2deaf1717395927394d', '[\"super_admin:*\"]', '2026-09-29 04:31:24', '2026-09-29 05:30:23', '2026-09-29 01:14:15', '2026-09-29 04:31:24'),
(46, 'App\\Models\\User', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'super_admin-token', '95d5a79b6f83dd2b790094e18b12d6558a2f5fe0dc6318c2f7a3c690d10eef4d', '[\"super_admin:*\"]', '2026-09-29 20:36:56', '2026-09-29 21:36:10', '2026-09-29 18:14:32', '2026-09-29 20:36:56'),
(49, 'App\\Models\\User', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'super_admin-token', 'a2ee6fea45b0661dfe38831f3da58946235f68659338bcd4a1d59eac56435ef4', '[\"super_admin:*\"]', '2026-09-29 22:20:40', '2026-09-29 23:20:31', '2026-09-29 21:21:10', '2026-09-29 22:20:40'),
(51, 'App\\Models\\User', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'super_admin-token', '3f5cb4d4ea025701a5625065110fdf4e1be540bfc5f2505318bf99d4c75911d7', '[\"super_admin:*\"]', '2026-09-29 21:45:46', '2026-09-29 22:44:21', '2026-09-29 21:44:21', '2026-09-29 21:45:46'),
(52, 'App\\Models\\User', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'super_admin-token', '1d8ebbc3caf17047b3ee6733aacae28e870d15923aa3bc63f8281ff9a112d6ad', '[\"super_admin:*\"]', '2026-09-29 23:01:38', '2026-09-30 00:01:38', '2026-09-29 22:38:58', '2026-09-29 23:01:38'),
(53, 'App\\Models\\User', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'super_admin-token', 'a0b8a2edb144ba83206d9ae29ce9b9b5f6ad95bbfaf1b782f137e4f8262a7d60', '[\"super_admin:*\"]', '2026-10-26 20:29:06', '2026-10-26 21:29:06', '2026-10-26 19:07:59', '2026-10-26 20:29:06'),
(54, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'hr_admin-token', '8c660b4ca37e2cc262d4ee777542421786bcc227fc038d56d15445bf5b9a57dc', '[\"hr_admin:*\"]', '2026-10-26 20:46:36', '2026-10-26 21:46:36', '2026-10-26 19:21:54', '2026-10-26 20:46:36'),
(56, 'App\\Models\\User', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'super_admin-token', '6da1e4cacb540836e8f104f2679e9fdc0d6db96c5f7248076974191ba8bfcd41', '[\"super_admin:*\"]', '2026-10-26 23:01:02', '2026-10-27 00:00:31', '2026-10-26 21:50:54', '2026-10-26 23:01:02'),
(57, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'hr_admin-token', 'a53fc660d1f7174a91f9143c3d6caf0e0ba4fb49835c59854c637117d3d91c25', '[\"hr_admin:*\"]', '2026-10-26 23:24:36', '2026-10-27 00:20:36', '2026-10-26 21:52:19', '2026-10-26 23:24:36'),
(58, 'App\\Models\\User', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'super_admin-token', '955ac12ad0b358e8e7c02067c9b87d27309e781f136449ff6a62a333358d03da', '[\"super_admin:*\"]', '2026-10-26 22:59:18', '2026-10-26 23:59:18', '2026-10-26 22:04:35', '2026-10-26 22:59:18'),
(59, 'App\\Models\\User', '01a0a6c2-2b02-7173-8043-3439b8d31b03', 'hr_admin-token', '6f823d9ccd106e7f7f36e17e45b7ea4084b6f63ddf1349fd8069f0e32b3e8bd1', '[\"hr_admin:*\"]', '2026-10-27 03:23:32', '2026-10-27 04:20:28', '2026-10-26 22:46:29', '2026-10-27 03:23:32'),
(61, 'App\\Models\\User', '01a09225-0284-7153-8eb0-bd4f0484a94a', 'super_admin-token', '8e6ee94736d9147261ba3e036467feae009b1505fc7656e9d45293b3d175d904', '[\"super_admin:*\"]', '2026-10-27 03:02:51', '2026-10-27 03:58:00', '2026-10-27 00:50:26', '2026-10-27 03:02:51'),
(65, 'App\\Models\\User', '01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'alumni-token', '059cba752f1c35d667e459bbf7f929b669f37b0ad2fdda1d10236fadceb92073', '[\"alumni:*\"]', '2026-10-27 03:19:44', '2026-10-27 04:15:46', '2026-10-27 03:15:46', '2026-10-27 03:19:44');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'web', '2026-09-12 03:22:22', '2026-09-12 03:22:22'),
(2, 'hr_admin', 'web', '2026-09-12 04:52:53', '2026-09-12 04:52:53'),
(3, 'alumni', 'web', '2026-09-12 22:28:14', '2026-09-12 22:28:14');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_permission_defaults`
--

CREATE TABLE `role_permission_defaults` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(32) NOT NULL,
  `permission` varchar(64) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permission_defaults`
--

INSERT INTO `role_permission_defaults` (`id`, `role`, `permission`, `created_at`, `updated_at`) VALUES
(4, 'alumni', 'dashboard.view', '2026-09-12 22:37:43', '2026-09-12 22:37:43'),
(5, 'alumni', 'documents.view', '2026-09-12 22:37:43', '2026-09-12 22:37:43'),
(6, 'alumni', 'documents.download', '2026-09-12 22:37:43', '2026-09-12 22:37:43'),
(7, 'alumni', 'jobs.view', '2026-09-12 22:37:43', '2026-09-12 22:37:43'),
(8, 'alumni', 'jobs.apply', '2026-09-12 22:37:43', '2026-09-12 22:37:43'),
(9, 'alumni', 'applications.view', '2026-09-12 22:37:43', '2026-09-12 22:37:43'),
(10, 'alumni', 'profile.view', '2026-09-12 22:37:43', '2026-09-12 22:37:43'),
(11, 'alumni', 'profile.edit', '2026-09-12 22:37:43', '2026-09-12 22:37:43'),
(12, 'alumni', 'announcements.view', '2026-09-12 22:37:43', '2026-09-12 22:37:43'),
(13, 'alumni', 'notifications.view', '2026-09-12 22:37:43', '2026-09-12 22:37:43'),
(14, 'alumni', 'service_request.view', '2026-09-12 22:37:43', '2026-09-12 22:37:43'),
(15, 'alumni', 'preferences.manage', '2026-09-12 22:37:43', '2026-09-12 22:37:43'),
(16, 'hr_admin', 'document_categories.manage', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(17, 'hr_admin', 'dashboard.view', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(18, 'hr_admin', 'alumni.view', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(19, 'hr_admin', 'alumni.edit', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(20, 'hr_admin', 'alumni.disable', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(21, 'hr_admin', 'alumni.activate', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(22, 'hr_admin', 'alumni.delete', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(23, 'hr_admin', 'alumni.lock', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(24, 'hr_admin', 'alumni.unlock', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(25, 'hr_admin', 'alumni.reset_password', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(26, 'hr_admin', 'alumni.disable_downloads', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(27, 'hr_admin', 'alumni.disable_documents', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(28, 'hr_admin', 'alumni.disable_jobs', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(29, 'hr_admin', 'alumni.disable_applications', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(30, 'hr_admin', 'alumni.disable_notifications', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(31, 'hr_admin', 'alumni.disable_ticker', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(32, 'hr_admin', 'alumni.disable_profile', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(33, 'hr_admin', 'alumni.disable_announcements', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(34, 'hr_admin', 'alumni.disable_dashboard', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(35, 'hr_admin', 'alumni.disable_service_requests', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(36, 'hr_admin', 'alumni_master.view', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(37, 'hr_admin', 'alumni_master.import', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(38, 'hr_admin', 'alumni_master.manage', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(39, 'hr_admin', 'alumni_master.export', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(40, 'hr_admin', 'documents.view', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(41, 'hr_admin', 'documents.upload', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(42, 'hr_admin', 'documents.replace', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(43, 'hr_admin', 'documents.delete', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(44, 'hr_admin', 'documents.download', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(45, 'hr_admin', 'jobs.view', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(46, 'hr_admin', 'jobs.create', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(47, 'hr_admin', 'jobs.edit', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(48, 'hr_admin', 'jobs.delete', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(49, 'hr_admin', 'jobs.close', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(50, 'hr_admin', 'applications.view', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(51, 'hr_admin', 'applications.update_status', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(52, 'hr_admin', 'applications.download_resume', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(53, 'hr_admin', 'announcements.create', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(54, 'hr_admin', 'announcements.edit', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(55, 'hr_admin', 'announcements.delete', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(56, 'hr_admin', 'ticker.manage', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(57, 'hr_admin', 'reports.view', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(58, 'hr_admin', 'reports.export', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(59, 'hr_admin', 'hr_settings.access', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(60, 'hr_admin', 'hr_admins.manage', '2026-09-12 22:38:09', '2026-09-12 22:38:09'),
(61, 'hr_admin', 'preferences.manage', '2026-09-12 22:38:09', '2026-09-12 22:38:09');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('a5gS3rPwG6jfw3dDjFZ1XostQg36w08Njp5bTPHe', NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQVhkZTNkSDVhUGs2ZkY4eXBSbDNldmdVUWREOGhHOXE0aG1UQklHNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly92a29ubmVjdC52a29sbGFiLmNvbS9zdXBlci1hZG1pbi9hbHVtbmktbWFzdGVyIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1793045138),
('Ly9nIm4sAgB6Wu9q7agsGAKdnQdSXNhCgWvcJbpP', NULL, '103.4.250.140', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieWkyZjBGRnM2NzdydGR6MTN2bDFuM1pueGkxV1lGSEthWHBlU1hqZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly92a29ubmVjdC52a29sbGFiLmNvbS9wcml2YWN5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1793052286),
('MEDmIQgcXteoUPpRlmIO8pvDRDG8zP09gRzGW2XG', NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSExYejQ0N1Y1blZHNHhJWVRCaDFsdUJxS1F1czdPSWNTUm9BbVgwQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly92a29ubmVjdC52a29sbGFiLmNvbS9zdXBlci1hZG1pbi9hbHVtbmktbWFzdGVyIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1793045054),
('UgYcOFrKptYSuGIxcMhy84dyQtHgVUrcL8nz6ixJ', NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWU82c2wxN3B5MVZiOGxSVDVSNENSbTFsdUdDS2dHek1HOGlDQTFRdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly92a29ubmVjdC52a29sbGFiLmNvbS9hbHVtbmkvbG9naW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1793044280),
('WHbnkkOSv1dU06lHhDcjbZgDoVwZmDSuuqEwBoYX', NULL, '49.205.164.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSDU5WUlsWWxKUVh0MWw4NjdwcVdXMWNNcDR3RmFMbUVFTnFZRjRyUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly92a29ubmVjdC52a29sbGFiLmNvbS9hbHVtbmkvbm90aWZpY2F0aW9ucyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1793045976);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `key` varchar(64) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(16) NOT NULL DEFAULT 'string',
  `label` varchar(120) DEFAULT NULL,
  `group` varchar(32) NOT NULL DEFAULT 'general',
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`key`, `value`, `type`, `label`, `group`, `updated_at`) VALUES
('alumni_document_email_enabled', '1', 'boolean', 'Let alumni email their own documents to themselves', 'documents', NULL),
('footer_copyright_holder', 'VKollab Technologies', 'string', 'Copyright holder name', 'footer', NULL),
('footer_map_url', 'https://www.google.com/maps/search/?api=1&query=VKollab+Technologies%2C+India', 'string', 'Google Maps URL for the footer address', 'contact', NULL),
('footer_phone', '+91 80 4567 8901', 'string', 'Support phone number', 'contact', NULL),
('landing_about_pillar_1', 'Lifelong alumni engagement, backed by a verified professional identity', 'string', 'About pillar 1', 'landing', NULL),
('landing_about_pillar_2', 'Structured professional networking with colleagues across every batch and function', 'string', 'About pillar 2', 'landing', NULL),
('landing_about_pillar_3', 'Priority access to career growth opportunities within the organization and its partner network', 'string', 'About pillar 3', 'landing', NULL),
('landing_about_pillar_4', 'Secure, self-service access to your employment documents and records', 'string', 'About pillar 4', 'landing', NULL),
('landing_cta_primary_label', 'Join VKONNECT', 'string', 'Primary button label (Hero + CTA sections)', 'landing', NULL),
('landing_cta_secondary_label', 'Member Login', 'string', 'Secondary button label (Hero + CTA sections)', 'landing', NULL),
('landing_hero_eyebrow', 'VKollab Alumni Network', 'string', 'Hero eyebrow badge text', 'landing', NULL),
('organization_logo', NULL, 'string', 'Organization Logo', 'branding', NULL),
('portal_logo', NULL, 'string', 'Portal Logo', 'branding', NULL),
('service_requests_enabled', '0', 'boolean', 'Service Request', 'access', NULL),
('social_instagram', 'https://www.instagram.com/vkollab', 'string', 'Instagram URL', 'footer', NULL),
('social_linkedin', 'https://www.linkedin.com/company/vkollab', 'string', 'LinkedIn URL', 'footer', NULL),
('social_youtube', 'https://www.youtube.com/@vkollab', 'string', 'YouTube URL', 'footer', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` char(36) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(160) NOT NULL,
  `vkollab_id` varchar(40) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(32) NOT NULL DEFAULT 'alumni',
  `status` varchar(16) NOT NULL DEFAULT 'active',
  `avatar_path` varchar(255) DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(45) DEFAULT NULL,
  `locked_until` datetime DEFAULT NULL,
  `failed_login_attempts` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `downloads_disabled_at` timestamp NULL DEFAULT NULL,
  `documents_disabled_at` timestamp NULL DEFAULT NULL,
  `jobs_disabled_at` timestamp NULL DEFAULT NULL,
  `applications_disabled_at` timestamp NULL DEFAULT NULL,
  `notifications_disabled_at` timestamp NULL DEFAULT NULL,
  `ticker_disabled_at` timestamp NULL DEFAULT NULL,
  `profile_disabled_at` timestamp NULL DEFAULT NULL,
  `permissions_customized_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `vkollab_id_live` varchar(40) GENERATED ALWAYS AS (case when `deleted_at` is null then `vkollab_id` end) VIRTUAL,
  `email_live` varchar(160) GENERATED ALWAYS AS (case when `deleted_at` is null then `email` end) VIRTUAL,
  `hr_documents_disabled_at` timestamp NULL DEFAULT NULL,
  `hr_document_categories_disabled_at` timestamp NULL DEFAULT NULL,
  `hr_jobs_disabled_at` timestamp NULL DEFAULT NULL,
  `hr_applications_disabled_at` timestamp NULL DEFAULT NULL,
  `hr_announcements_disabled_at` timestamp NULL DEFAULT NULL,
  `hr_ticker_disabled_at` timestamp NULL DEFAULT NULL,
  `hr_alumni_disabled_at` timestamp NULL DEFAULT NULL,
  `hr_alumni_master_disabled_at` timestamp NULL DEFAULT NULL,
  `announcements_disabled_at` timestamp NULL DEFAULT NULL,
  `hr_dashboard_disabled_at` timestamp NULL DEFAULT NULL,
  `hr_settings_disabled_at` timestamp NULL DEFAULT NULL,
  `hr_notifications_disabled_at` timestamp NULL DEFAULT NULL,
  `dashboard_disabled_at` timestamp NULL DEFAULT NULL,
  `service_requests_disabled_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `vkollab_id`, `email_verified_at`, `password`, `role`, `status`, `avatar_path`, `last_login_at`, `last_login_ip`, `locked_until`, `failed_login_attempts`, `downloads_disabled_at`, `documents_disabled_at`, `jobs_disabled_at`, `applications_disabled_at`, `notifications_disabled_at`, `ticker_disabled_at`, `profile_disabled_at`, `permissions_customized_at`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `hr_documents_disabled_at`, `hr_document_categories_disabled_at`, `hr_jobs_disabled_at`, `hr_applications_disabled_at`, `hr_announcements_disabled_at`, `hr_ticker_disabled_at`, `hr_alumni_disabled_at`, `hr_alumni_master_disabled_at`, `announcements_disabled_at`, `hr_dashboard_disabled_at`, `hr_settings_disabled_at`, `hr_notifications_disabled_at`, `dashboard_disabled_at`, `service_requests_disabled_at`) VALUES
('01a09225-0284-7153-8eb0-bd4f0484a94a', 'Super Admin', 'patlurimaheshbabu756@gmail.com', 'SA1001', NULL, '$2y$12$HhuoYvwZbiXGPmxfBCRmZu40xjcfLTw3viKRBdLfyyb30MMBAwOwy', 'super_admin', 'active', NULL, '2026-10-27 02:02:21', '49.205.164.180', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-12 03:24:54', '2026-10-27 02:02:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('01a09277-60bd-71b1-9fad-a03afb7db784', 'MANIKANTA', 'kgsmanikanta1010@gmail.com', 'HR1002', NULL, '$2y$12$L0.pMB.GE78p5uKIkxqC6eRgHR457UrT43/y1HPy.zfzEzRFHfZai', 'hr_admin', 'active', NULL, '2026-09-15 18:57:01', '49.205.164.180', NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-26 19:11:25', NULL, '2026-09-12 04:54:52', '2026-10-27 00:25:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('01a0963f-fb96-72f9-b366-52fd81d9d235', 'Manikanta', 'geethasaimanikanta.k@gmail.com', '8583002', NULL, '$2y$12$rCqjLLXasuy7jdyrIQCN5eUyQHcn64xhQDCtUyU/D79vVTETXaBkq', 'alumni', 'active', NULL, '2026-09-13 01:03:39', '49.205.164.180', '2026-09-18 16:48:34', 6, '2026-09-18 22:21:51', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-12 22:32:50', '2026-09-18 23:34:37', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('01a0a00f-8e23-711d-b2c4-1b9cf2b46163', 'Monish', 'claudeai0801@gmail.com', 'ALM1001', NULL, '$2y$12$AOCgTHw4RM7KbD5nCjEFkuvI1lQaGXMPDcOTZGuie1NKLRM60lnrG', 'alumni', 'active', NULL, '2026-09-18 23:36:52', '49.205.164.180', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-18 22:50:06', NULL, '2026-09-14 20:16:09', '2026-09-18 23:36:52', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('01a0a105-e6be-7286-8bb1-38a359a6660c', 'Manikanta', 'claudeai0801@gmail.com', 'SA1002', NULL, '$2y$12$s43lW4FxfLfYYh2vpS2CQu8T4FGeBnKPxCEFeQ7alpNAmuVWKryVG', 'super_admin', 'active', NULL, '2026-09-15 00:47:38', '49.205.164.180', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-15 00:45:13', '2026-09-15 00:47:38', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('01a0a6c2-2b02-7173-8043-3439b8d31b03', 'Harsha', 'patlurimaheshbabu756@gmail.com', 'HR1001', NULL, '$2y$12$A5Y28mIpwyGRlDvPL3iv1OilhLbBaMUjVhwPEgvWRaVbt5a3Vd8P6', 'hr_admin', 'active', NULL, '2026-10-27 00:26:33', '49.205.164.180', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-26 19:11:15', NULL, '2026-09-16 03:28:57', '2026-10-27 00:26:33', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('01a0b5d2-245f-71e1-93d4-5b86c83fb723', 'Mahesh', 'patlurimaheshbabu756@gmail.com', 'ALM1003', NULL, '$2y$12$6wRmz6RlQd4nv2x2is0gcu5VmlqwznWFyvQvH7ZScHZaXBiLoU0pS', 'alumni', 'active', NULL, '2026-10-27 01:24:32', '49.205.164.180', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-19 01:40:43', '2026-10-27 01:24:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('01a17835-ee0e-73f4-9d39-2ed7ea978df3', 'Mohan kumar', 'potarlankamohankumar@gmail.com', 'ALM1006', NULL, '$2y$12$GeXRxYc1kYlBDejUNlVB0ufLR8Cp5gTVkT7M8iB4czaAA3AxU.q9e', 'alumni', 'active', NULL, '2026-10-27 03:15:46', '49.205.164.180', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-26 19:36:02', '2026-10-27 03:15:46', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alerts_created_by_foreign` (`created_by`),
  ADD KEY `alerts_is_active_audience_index` (`is_active`,`audience`),
  ADD KEY `alerts_user_id_is_active_index` (`user_id`,`is_active`);

--
-- Indexes for table `alumni_import_batches`
--
ALTER TABLE `alumni_import_batches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alumni_import_batches_uploaded_by_created_at_index` (`uploaded_by`,`created_at`),
  ADD KEY `alumni_import_batches_status_index` (`status`);

--
-- Indexes for table `alumni_masters`
--
ALTER TABLE `alumni_masters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alumni_masters_vkollab_id_unique_idx` (`vkollab_id_live`),
  ADD UNIQUE KEY `alumni_masters_email_unique_idx` (`email_live`),
  ADD KEY `alumni_masters_claimed_by_foreign` (`claimed_by`),
  ADD KEY `alumni_masters_imported_by_foreign` (`imported_by`),
  ADD KEY `alumni_masters_email_index` (`email`),
  ADD KEY `alumni_masters_department_index` (`department`),
  ADD KEY `alumni_masters_pan_hash_idx` (`pan_hash`);

--
-- Indexes for table `alumni_profiles`
--
ALTER TABLE `alumni_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alumni_profiles_user_id_unique` (`user_id`),
  ADD KEY `alumni_profiles_employee_id_index` (`vkollab_id`),
  ADD KEY `alumni_profiles_department_index` (`department`),
  ADD KEY `alumni_profiles_pan_hash_idx` (`pan_hash`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcements_author_id_foreign` (`author_id`),
  ADD KEY `announcements_status_published_at_index` (`status`,`published_at`),
  ADD KEY `announcements_audience_index` (`audience`);

--
-- Indexes for table `app_notifications`
--
ALTER TABLE `app_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `app_notifications_user_id_read_at_index` (`user_id`,`read_at`),
  ADD KEY `app_notifications_user_id_created_at_index` (`user_id`,`created_at`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `audit_logs_module_created_at_index` (`module`,`created_at`),
  ADD KEY `audit_logs_action_index` (`action`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `content_blocks`
--
ALTER TABLE `content_blocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `content_blocks_key_unique` (`key`),
  ADD KEY `content_blocks_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_uploaded_by_foreign` (`uploaded_by`),
  ADD KEY `documents_user_id_category_id_index` (`user_id`,`category_id`),
  ADD KEY `documents_user_id_financial_year_index` (`user_id`,`financial_year`),
  ADD KEY `documents_alumni_master_id_index` (`alumni_master_id`),
  ADD KEY `documents_category_id_foreign` (`category_id`);

--
-- Indexes for table `document_categories`
--
ALTER TABLE `document_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `document_categories_slug_unique` (`slug`);

--
-- Indexes for table `document_downloads`
--
ALTER TABLE `document_downloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_downloads_document_id_index` (`document_id`),
  ADD KEY `document_downloads_user_id_downloaded_at_index` (`user_id`,`downloaded_at`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `hr_admins`
--
ALTER TABLE `hr_admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hr_admins_user_id_unique` (`user_id`),
  ADD KEY `hr_admins_created_by_foreign` (`created_by`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `job_applications_job_posting_id_user_id_unique` (`job_posting_id`,`user_id`),
  ADD KEY `job_applications_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `job_applications_user_id_status_index` (`user_id`,`status`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_postings`
--
ALTER TABLE `job_postings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_postings_posted_by_foreign` (`posted_by`),
  ADD KEY `job_postings_status_type_index` (`status`,`type`),
  ADD KEY `job_postings_created_at_index` (`created_at`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `role_permission_defaults`
--
ALTER TABLE `role_permission_defaults`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_permission_defaults_role_permission_unique` (`role`,`permission`),
  ADD KEY `role_permission_defaults_role_index` (`role`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_vkollab_id_unique_idx` (`vkollab_id_live`),
  ADD UNIQUE KEY `users_role_email_unique_idx` (`role`,`email_live`),
  ADD KEY `users_role_status_index` (`role`,`status`),
  ADD KEY `users_status_index` (`status`),
  ADD KEY `users_email_index` (`email`),
  ADD KEY `users_employee_id_index` (`vkollab_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `role_permission_defaults`
--
ALTER TABLE `role_permission_defaults`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alerts`
--
ALTER TABLE `alerts`
  ADD CONSTRAINT `alerts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `alerts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `alumni_import_batches`
--
ALTER TABLE `alumni_import_batches`
  ADD CONSTRAINT `alumni_import_batches_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `alumni_masters`
--
ALTER TABLE `alumni_masters`
  ADD CONSTRAINT `alumni_masters_claimed_by_foreign` FOREIGN KEY (`claimed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `alumni_masters_imported_by_foreign` FOREIGN KEY (`imported_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `alumni_profiles`
--
ALTER TABLE `alumni_profiles`
  ADD CONSTRAINT `alumni_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `app_notifications`
--
ALTER TABLE `app_notifications`
  ADD CONSTRAINT `app_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `content_blocks`
--
ALTER TABLE `content_blocks`
  ADD CONSTRAINT `content_blocks_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_alumni_master_id_foreign` FOREIGN KEY (`alumni_master_id`) REFERENCES `alumni_masters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `document_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `documents_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `document_downloads`
--
ALTER TABLE `document_downloads`
  ADD CONSTRAINT `document_downloads_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `document_downloads_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hr_admins`
--
ALTER TABLE `hr_admins`
  ADD CONSTRAINT `hr_admins_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hr_admins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD CONSTRAINT `job_applications_job_posting_id_foreign` FOREIGN KEY (`job_posting_id`) REFERENCES `job_postings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_applications_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `job_applications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_postings`
--
ALTER TABLE `job_postings`
  ADD CONSTRAINT `job_postings_posted_by_foreign` FOREIGN KEY (`posted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

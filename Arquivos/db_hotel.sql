-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Tempo de geração: 04/11/2024 às 20:37
-- Versão do servidor: 8.0.40
-- Versão do PHP: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_hotel`
--
CREATE DATABASE IF NOT EXISTS `db_hotel` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `db_hotel`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount_value`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'DISCOUNT10', 10.00, NULL, '2024-11-04 18:34:29', '2024-11-04 16:40:16'),
(2, 'SUMMERSALE', 20.00, NULL, NULL, NULL),
(3, 'WELCOME5', 5.00, NULL, NULL, NULL),
(4, 'DISCOUNT11', 25.00, '2024-11-04 16:39:59', '2024-11-04 16:39:59', '2024-11-04 18:34:36'),
(5, 'MEOMEO', 25.00, '2024-11-04 18:33:22', '2024-11-04 18:33:22', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `dailies`
--

CREATE TABLE `dailies` (
  `id` bigint UNSIGNED NOT NULL,
  `reserveId` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `dailies`
--

INSERT INTO `dailies` (`id`, `reserveId`, `date`, `value`, `created_at`, `updated_at`) VALUES
(1, 1, '2022-12-01', 100.00, NULL, NULL),
(2, 1, '2022-12-02', 100.00, NULL, NULL),
(3, 2, '2022-12-03', 150.00, NULL, NULL),
(4, 1, '2024-11-01', 150.00, '2024-11-04 16:40:38', '2024-11-04 16:40:38'),
(5, 1, '2024-11-01', 150.00, '2024-11-04 18:34:59', '2024-11-04 18:34:59');

-- --------------------------------------------------------

--
-- Estrutura para tabela `guests`
--

CREATE TABLE `guests` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `guests`
--

INSERT INTO `guests` (`id`, `name`, `lastName`, `phone`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Fulaninho', 'de Tal', '5571995959595', NULL, NULL, NULL),
(2, 'Fulaninha', 'de Tal', '5571998989898', NULL, NULL, NULL),
(3, 'Cicrano', 'da Silva', '5571991919191', NULL, NULL, NULL),
(4, 'Italo', 'Silva', '77998535172', '2024-11-04 16:49:35', '2024-11-04 18:36:01', '2024-11-04 18:36:06'),
(5, 'Silva', 'Moreirea', '77998535172', '2024-11-04 18:35:57', '2024-11-04 18:35:57', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `hotels`
--

CREATE TABLE `hotels` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `hotels`
--

INSERT INTO `hotels` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Hotel Foco Prime', NULL, NULL),
(2, 'Hotel Foco Beach', NULL, NULL),
(3, 'Hotel Foco Privillege', NULL, NULL),
(5, 'FOCO CETEIA', '2024-11-04 18:36:28', '2024-11-04 18:36:28');

-- --------------------------------------------------------

--
-- Estrutura para tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2024_10_27_234040_create_personal_access_tokens_table', 1),
(2, '2024_10_29_182235_create_hotels_table', 1),
(3, '2024_10_29_183143_create_rooms_table', 1),
(4, '2024_10_29_184609_create_guests_table', 1),
(5, '2024_10_29_184927_create_reserves_table', 1),
(6, '2024_10_29_185953_create_reserveGuests_table', 1),
(7, '2024_10_29_201303_create_dailies_table', 1),
(8, '2024_10_29_201552_create_payments_table', 1),
(9, '2024_10_30_113451_create_coupons_table', 1),
(10, '2024_10_30_170220_create_user_table', 1),
(11, '2024_10_31_123318_rename_user_to_users', 1),
(12, '2024_11_01_234658_add_method_and_paid_payments_table', 1),
(13, '2024_11_04_130110_make_checkout_nullable_in_reserves_table', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `reserveId` bigint UNSIGNED NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `payments`
--

INSERT INTO `payments` (`id`, `reserveId`, `value`, `method`, `paid`, `created_at`, `updated_at`) VALUES
(1, 1, 100.00, NULL, 0, NULL, NULL),
(2, 2, 150.00, NULL, 0, NULL, NULL),
(3, 3, 200.00, NULL, 0, NULL, NULL),
(5, 1, 100.50, 'credit', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(4, 'App\\Models\\User', 4, 'João da Silva', '01aa248efee7500f7e81bd7e67d1a4cdd46eda017b659b16b93e511dc51b8c23', '[\"*\"]', NULL, NULL, '2024-11-04 18:37:25', '2024-11-04 18:37:25');

-- --------------------------------------------------------

--
-- Estrutura para tabela `reserves`
--

CREATE TABLE `reserves` (
  `id` bigint UNSIGNED NOT NULL,
  `hotelCode` bigint UNSIGNED NOT NULL,
  `roomCode` bigint UNSIGNED NOT NULL,
  `checkIn` date NOT NULL,
  `checkOut` date DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `discounts` decimal(10,2) DEFAULT NULL,
  `additional_charges` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `reserves`
--

INSERT INTO `reserves` (`id`, `hotelCode`, `roomCode`, `checkIn`, `checkOut`, `total`, `discounts`, `additional_charges`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, '2022-12-01', '2022-12-04', 300.00, NULL, NULL, NULL, NULL, '2024-11-04 16:39:26'),
(2, 1, 2, '2022-12-07', '2022-12-09', 500.00, NULL, NULL, NULL, NULL, '2024-11-04 18:32:43'),
(3, 2, 3, '2022-12-15', '2022-12-20', 750.00, NULL, NULL, NULL, NULL, NULL),
(4, 2, 4, '2022-11-01', '2022-11-04', 900.00, NULL, NULL, NULL, NULL, NULL),
(5, 1, 2, '2022-11-25', '2022-11-27', 500.00, NULL, NULL, NULL, NULL, NULL),
(6, 2, 3, '2022-10-01', '2022-10-04', 450.00, NULL, NULL, NULL, NULL, NULL),
(7, 2, 2, '2024-11-10', '2024-11-15', 270.00, 0.00, 20.00, '2024-11-04 16:03:07', '2024-11-04 18:32:34', NULL),
(8, 1, 1, '2024-11-10', '2024-11-15', 520.00, 0.00, 20.00, '2024-11-04 18:32:12', '2024-11-04 18:32:12', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `reserve_guests`
--

CREATE TABLE `reserve_guests` (
  `reserveId` bigint UNSIGNED NOT NULL,
  `guestId` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `reserve_guests`
--

INSERT INTO `reserve_guests` (`reserveId`, `guestId`, `created_at`, `updated_at`) VALUES
(5, 3, '2024-11-04 18:35:37', '2024-11-04 18:35:37');

-- --------------------------------------------------------

--
-- Estrutura para tabela `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint UNSIGNED NOT NULL,
  `hotelCode` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `availability` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `rooms`
--

INSERT INTO `rooms` (`id`, `hotelCode`, `name`, `availability`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Room 1 Hotel 1', 4, NULL, '2024-11-04 18:31:37', NULL),
(2, 1, 'Room 2 Hotel 1', 0, NULL, NULL, NULL),
(3, 2, 'Room 1 Hotel 2', 0, NULL, NULL, NULL),
(4, 2, 'Room 2 Hotel 2', 0, NULL, NULL, NULL),
(5, 3, 'Room 1 Hotel 3', 0, NULL, NULL, NULL),
(6, 3, 'Room 2 Hotel 3', 0, NULL, NULL, NULL),
(7, 1, 'Quarto Standard', 18, '2024-11-04 16:00:56', '2024-11-04 16:00:56', NULL),
(8, 1, 'Quarto Standard', 18, '2024-11-04 18:31:30', '2024-11-04 18:31:30', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'receptionist',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'João Silva', 'joao.silva@gmail.com', '$2y$12$/yxYJqCleWf/JI9PwEfIleLrcVs2I5a.kuAS4qH6y268nqJwYde6u', 'admin', NULL, NULL, '2024-11-04 18:29:47'),
(2, 'Maria Souza', 'maria.souza@yahoo.com', '$2y$12$WgEEUjl2q4suYbpSare9g.X/d6fJB3shFDTJgz4e.Zc4Sq529hVcK', 'receptionist', NULL, NULL, NULL),
(3, 'Carlos Santos', 'carlos.santos@outlook.com', '$2y$12$mtRX8PhzGJFZYwCAznszWO7aQ8hx8ybgzcMKAJOpxhA7/TpthEv6e', 'receptionist', NULL, NULL, '2024-11-04 18:29:55'),
(4, 'João da Silva', 'joao@gmail.com', '$2y$12$puoMLfU1y/Rzjs38qmZu9OeoJ7Gz3VS.hvAEWj/vvOYB1Kn8ykQVW', 'admin', '2024-11-04 14:12:01', '2024-11-04 14:12:01', NULL),
(5, 'Jonas', 'jonas22@gmail.com', '$2y$12$96pBLgW9LDIaHDPMjsw2tuHLNyeX1qsEIUHnvKsc5go5fnvH86YmC', 'admin', '2024-11-04 14:45:48', '2024-11-04 18:29:36', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`);

--
-- Índices de tabela `dailies`
--
ALTER TABLE `dailies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dailies_reserveid_foreign` (`reserveId`);

--
-- Índices de tabela `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_reserveid_foreign` (`reserveId`);

--
-- Índices de tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Índices de tabela `reserves`
--
ALTER TABLE `reserves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reserves_hotelcode_foreign` (`hotelCode`),
  ADD KEY `reserves_roomcode_foreign` (`roomCode`);

--
-- Índices de tabela `reserve_guests`
--
ALTER TABLE `reserve_guests`
  ADD PRIMARY KEY (`reserveId`,`guestId`),
  ADD KEY `reserve_guests_guestid_foreign` (`guestId`);

--
-- Índices de tabela `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rooms_hotelcode_foreign` (`hotelCode`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_email_unique` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `dailies`
--
ALTER TABLE `dailies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `guests`
--
ALTER TABLE `guests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `reserves`
--
ALTER TABLE `reserves`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `dailies`
--
ALTER TABLE `dailies`
  ADD CONSTRAINT `dailies_reserveid_foreign` FOREIGN KEY (`reserveId`) REFERENCES `reserves` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_reserveid_foreign` FOREIGN KEY (`reserveId`) REFERENCES `reserves` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `reserves`
--
ALTER TABLE `reserves`
  ADD CONSTRAINT `reserves_hotelcode_foreign` FOREIGN KEY (`hotelCode`) REFERENCES `hotels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reserves_roomcode_foreign` FOREIGN KEY (`roomCode`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `reserve_guests`
--
ALTER TABLE `reserve_guests`
  ADD CONSTRAINT `reserve_guests_guestid_foreign` FOREIGN KEY (`guestId`) REFERENCES `guests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reserve_guests_reserveid_foreign` FOREIGN KEY (`reserveId`) REFERENCES `reserves` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_hotelcode_foreign` FOREIGN KEY (`hotelCode`) REFERENCES `hotels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- ============================================================
-- SISTEMA POS WEB — RESTAURANTE (base de datos: solemia)
-- MySQL 8.0+ compatible con phpMyAdmin
-- Versión 1.0.0 | Junio 2026
-- ============================================================

CREATE DATABASE IF NOT EXISTS `solemia`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `solemia`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- MÓDULO 7 - USUARIOS
-- ============================================================

CREATE TABLE `users` (
  `id`                BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `name`              VARCHAR(100)      NOT NULL,
  `email`             VARCHAR(150)      NOT NULL,
  `email_verified_at` TIMESTAMP         NULL DEFAULT NULL,
  `password`          VARCHAR(255)      NOT NULL,
  `pin`               CHAR(4)           NULL DEFAULT NULL,
  `avatar`            VARCHAR(255)      NULL DEFAULT NULL,
  `is_active`         TINYINT(1)        NOT NULL DEFAULT 1,
  `remember_token`    VARCHAR(100)      NULL DEFAULT NULL,
  `created_at`        TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at`        TIMESTAMP         NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `audit_logs` (
  `id`             BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `user_id`        BIGINT UNSIGNED   NULL DEFAULT NULL,
  `event`          VARCHAR(100)      NOT NULL,
  `auditable_type` VARCHAR(100)      NULL DEFAULT NULL,
  `auditable_id`   BIGINT UNSIGNED   NULL DEFAULT NULL,
  `old_values`     JSON              NULL DEFAULT NULL,
  `new_values`     JSON              NULL DEFAULT NULL,
  `ip_address`     VARCHAR(45)       NULL DEFAULT NULL,
  `user_agent`     VARCHAR(255)      NULL DEFAULT NULL,
  `created_at`     TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- MÓDULO 8 - CONFIGURACIÓN
-- ============================================================

CREATE TABLE `restaurant_settings` (
  `id`                    BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `trade_name`            VARCHAR(150)      NOT NULL,
  `legal_name`            VARCHAR(150)      NULL DEFAULT NULL,
  `ruc`                   VARCHAR(13)       NULL DEFAULT NULL,
  `address`               VARCHAR(255)      NULL DEFAULT NULL,
  `phone`                 VARCHAR(20)       NULL DEFAULT NULL,
  `email`                 VARCHAR(150)      NULL DEFAULT NULL,
  `logo_path`             VARCHAR(255)      NULL DEFAULT NULL,
  `currency`              CHAR(3)           NOT NULL DEFAULT 'USD',
  `timezone`              VARCHAR(60)       NOT NULL DEFAULT 'America/Guayaquil',
  `date_format`           VARCHAR(20)       NOT NULL DEFAULT 'Y-m-d',
  `decimal_separator`     CHAR(1)           NOT NULL DEFAULT '.',
  `tax_rate`              DECIMAL(5,2)      NOT NULL DEFAULT 15.00,
  `service_charge_active` TINYINT(1)        NOT NULL DEFAULT 0,
  `service_charge_rate`   DECIMAL(5,2)      NOT NULL DEFAULT 0.00,
  `tip_suggestions`       JSON              NULL DEFAULT NULL,
  `sri_environment`       ENUM('test','production') NOT NULL DEFAULT 'test',
  `sri_certificate_path`  VARCHAR(255)      NULL DEFAULT NULL,
  `sri_certificate_pass`  VARCHAR(255)      NULL DEFAULT NULL,
  `sri_taxpayer_type`     ENUM('rimpe','contribuyente_especial','otro') NOT NULL DEFAULT 'otro',
  `kds_alert_minutes`     TINYINT UNSIGNED  NOT NULL DEFAULT 10,
  `session_timeout_json`  JSON              NULL DEFAULT NULL,
  `created_at`            TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`            TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `shifts` (
  `id`         BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(80)       NOT NULL,
  `start_time` TIME              NOT NULL,
  `end_time`   TIME              NOT NULL,
  `is_active`  TINYINT(1)        NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `shift_user` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `shift_id`   BIGINT UNSIGNED NOT NULL,
  `user_id`    BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_shift_user` (`shift_id`,`user_id`),
  CONSTRAINT `fk_su_shift` FOREIGN KEY (`shift_id`) REFERENCES `shifts`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_su_user`  FOREIGN KEY (`user_id`)  REFERENCES `users`(`id`)  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `printers` (
  `id`           BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `name`         VARCHAR(80)       NOT NULL,
  `type`         ENUM('thermal_escpos','laser') NOT NULL DEFAULT 'thermal_escpos',
  `ip_address`   VARCHAR(45)       NOT NULL,
  `port`         SMALLINT UNSIGNED NOT NULL DEFAULT 9100,
  `model`        VARCHAR(80)       NULL DEFAULT NULL,
  `printer_function` SET('ticket_client','kitchen','cash_close','labels') NOT NULL DEFAULT 'ticket_client',
  `kitchen_area` VARCHAR(60)       NULL DEFAULT NULL,
  `is_active`    TINYINT(1)        NOT NULL DEFAULT 1,
  `created_at`   TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- MÓDULO 1 - POS / SALA: ZONAS Y MESAS
-- ============================================================

CREATE TABLE `zones` (
  `id`             BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `name`           VARCHAR(80)       NOT NULL,
  `description`    VARCHAR(255)      NULL DEFAULT NULL,
  `total_capacity` SMALLINT UNSIGNED NULL DEFAULT NULL,
  `sort_order`     TINYINT UNSIGNED  NOT NULL DEFAULT 0,
  `is_active`      TINYINT(1)        NOT NULL DEFAULT 1,
  `created_at`     TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tables` (
  `id`         BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `zone_id`    BIGINT UNSIGNED   NOT NULL,
  `number`     VARCHAR(10)       NOT NULL,
  `capacity`   TINYINT UNSIGNED  NOT NULL DEFAULT 4,
  `shape`      ENUM('square','round','rectangle') NOT NULL DEFAULT 'square',
  `pos_x`      SMALLINT          NOT NULL DEFAULT 0,
  `pos_y`      SMALLINT          NOT NULL DEFAULT 0,
  `width`      SMALLINT UNSIGNED NOT NULL DEFAULT 80,
  `height`     SMALLINT UNSIGNED NOT NULL DEFAULT 80,
  `status`     ENUM('available','occupied','reserved','billing','blocked') NOT NULL DEFAULT 'available',
  `is_active`  TINYINT(1)        NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_table_number` (`zone_id`,`number`),
  CONSTRAINT `fk_table_zone` FOREIGN KEY (`zone_id`) REFERENCES `zones`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- MÓDULO 3 - MENÚ Y PRODUCTOS
-- ============================================================

CREATE TABLE `categories` (
  `id`               BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `parent_id`        BIGINT UNSIGNED   NULL DEFAULT NULL,
  `name`             VARCHAR(100)      NOT NULL,
  `image_path`       VARCHAR(255)      NULL DEFAULT NULL,
  `sort_order`       TINYINT UNSIGNED  NOT NULL DEFAULT 0,
  `is_active`        TINYINT(1)        NOT NULL DEFAULT 1,
  `available_shifts` JSON              NULL DEFAULT NULL,
  `created_at`       TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_cat_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `products` (
  `id`                    BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `category_id`           BIGINT UNSIGNED   NOT NULL,
  `sku`                   VARCHAR(50)       NULL DEFAULT NULL,
  `name`                  VARCHAR(150)      NOT NULL,
  `description`           TEXT              NULL DEFAULT NULL,
  `image_path`            VARCHAR(255)      NULL DEFAULT NULL,
  `base_price`            DECIMAL(10,2)     NOT NULL DEFAULT 0.00,
  `takeaway_price`        DECIMAL(10,2)     NULL DEFAULT NULL,
  `happy_hour_price`      DECIMAL(10,2)     NULL DEFAULT NULL,
  `tags`                  JSON              NULL DEFAULT NULL,
  `prep_time_minutes`     TINYINT UNSIGNED  NOT NULL DEFAULT 10,
  `kitchen_area`          VARCHAR(60)       NULL DEFAULT NULL,
  `is_active`             TINYINT(1)        NOT NULL DEFAULT 1,
  `is_available`          TINYINT(1)        NOT NULL DEFAULT 1,
  `auto_disable_on_stock` TINYINT(1)        NOT NULL DEFAULT 0,
  `available_dine_in`     TINYINT(1)        NOT NULL DEFAULT 1,
  `available_takeaway`    TINYINT(1)        NOT NULL DEFAULT 1,
  `available_delivery`    TINYINT(1)        NOT NULL DEFAULT 1,
  `created_at`            TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`            TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at`            TIMESTAMP         NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_product_sku` (`sku`),
  INDEX `idx_product_category` (`category_id`),
  INDEX `idx_product_active` (`is_active`),
  CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `modifier_groups` (
  `id`          BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(100)      NOT NULL,
  `type`        ENUM('single','multiple','required') NOT NULL DEFAULT 'single',
  `min_options` TINYINT UNSIGNED  NOT NULL DEFAULT 0,
  `max_options` TINYINT UNSIGNED  NOT NULL DEFAULT 1,
  `sort_order`  TINYINT UNSIGNED  NOT NULL DEFAULT 0,
  `created_at`  TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_modifier_group` (
  `product_id`        BIGINT UNSIGNED  NOT NULL,
  `modifier_group_id` BIGINT UNSIGNED  NOT NULL,
  `sort_order`        TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`product_id`,`modifier_group_id`),
  CONSTRAINT `fk_pmg_product`  FOREIGN KEY (`product_id`)        REFERENCES `products`(`id`)        ON DELETE CASCADE,
  CONSTRAINT `fk_pmg_modgroup` FOREIGN KEY (`modifier_group_id`) REFERENCES `modifier_groups`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `modifier_options` (
  `id`                BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `modifier_group_id` BIGINT UNSIGNED   NOT NULL,
  `name`              VARCHAR(100)      NOT NULL,
  `extra_price`       DECIMAL(10,2)     NOT NULL DEFAULT 0.00,
  `sort_order`        TINYINT UNSIGNED  NOT NULL DEFAULT 0,
  `is_active`         TINYINT(1)        NOT NULL DEFAULT 1,
  `created_at`        TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_mopt_group` FOREIGN KEY (`modifier_group_id`) REFERENCES `modifier_groups`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `promotions` (
  `id`             BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `name`           VARCHAR(150)      NOT NULL,
  `description`    TEXT              NULL DEFAULT NULL,
  `type`           ENUM('combo','percent_discount','fixed_discount') NOT NULL,
  `discount_value` DECIMAL(10,2)     NOT NULL DEFAULT 0.00,
  `valid_from`     DATE              NULL DEFAULT NULL,
  `valid_to`       DATE              NULL DEFAULT NULL,
  `active_from_time` TIME            NULL DEFAULT NULL,
  `active_to_time`   TIME            NULL DEFAULT NULL,
  `channel`        SET('dine_in','takeaway','delivery') NOT NULL DEFAULT 'dine_in,takeaway,delivery',
  `is_automatic`   TINYINT(1)        NOT NULL DEFAULT 0,
  `is_active`      TINYINT(1)        NOT NULL DEFAULT 1,
  `created_at`     TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `promotion_products` (
  `promotion_id` BIGINT UNSIGNED  NOT NULL,
  `product_id`   BIGINT UNSIGNED  NOT NULL,
  `quantity`     TINYINT UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`promotion_id`,`product_id`),
  CONSTRAINT `fk_pp_promo`   FOREIGN KEY (`promotion_id`) REFERENCES `promotions`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pp_product` FOREIGN KEY (`product_id`)   REFERENCES `products`(`id`)   ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- MÓDULO 4 - CAJA Y PAGOS
-- ============================================================

CREATE TABLE `cash_registers` (
  `id`              BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `user_id`         BIGINT UNSIGNED   NOT NULL,
  `name`            VARCHAR(80)       NOT NULL DEFAULT 'Caja 1',
  `opening_amount`  DECIMAL(10,2)     NOT NULL DEFAULT 0.00,
  `closing_amount`  DECIMAL(10,2)     NULL DEFAULT NULL,
  `expected_amount` DECIMAL(10,2)     NULL DEFAULT NULL,
  `difference`      DECIMAL(10,2)     NULL DEFAULT NULL,
  `observations`    TEXT              NULL DEFAULT NULL,
  `status`          ENUM('open','closed') NOT NULL DEFAULT 'open',
  `opened_at`       TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `closed_at`       TIMESTAMP         NULL DEFAULT NULL,
  `created_at`      TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_cr_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- MÓDULO 1 - COMANDAS
-- ============================================================

CREATE TABLE `orders` (
  `id`               BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `table_id`         BIGINT UNSIGNED   NULL DEFAULT NULL,
  `user_id`          BIGINT UNSIGNED   NOT NULL,
  `cashier_id`       BIGINT UNSIGNED   NULL DEFAULT NULL,
  `cash_register_id` BIGINT UNSIGNED   NULL DEFAULT NULL,
  `type`             ENUM('dine_in','takeaway','delivery') NOT NULL DEFAULT 'dine_in',
  `status`           ENUM('open','sent','partial','complete','cancelled') NOT NULL DEFAULT 'open',
  `notes`            TEXT              NULL DEFAULT NULL,
  `subtotal`         DECIMAL(10,2)     NOT NULL DEFAULT 0.00,
  `discount`         DECIMAL(10,2)     NOT NULL DEFAULT 0.00,
  `tax`              DECIMAL(10,2)     NOT NULL DEFAULT 0.00,
  `tip`              DECIMAL(10,2)     NOT NULL DEFAULT 0.00,
  `total`            DECIMAL(10,2)     NOT NULL DEFAULT 0.00,
  `customer_name`    VARCHAR(100)      NULL DEFAULT NULL,
  `customer_phone`   VARCHAR(20)       NULL DEFAULT NULL,
  `customer_address` TEXT              NULL DEFAULT NULL,
  `opened_at`        TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `closed_at`        TIMESTAMP         NULL DEFAULT NULL,
  `created_at`       TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at`       TIMESTAMP         NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_order_table`  (`table_id`),
  INDEX `idx_order_status` (`status`),
  INDEX `idx_order_date`   (`created_at`),
  INDEX `idx_order_register` (`cash_register_id`),
  CONSTRAINT `fk_order_table`    FOREIGN KEY (`table_id`)         REFERENCES `tables`(`id`)         ON DELETE SET NULL,
  CONSTRAINT `fk_order_user`     FOREIGN KEY (`user_id`)          REFERENCES `users`(`id`)          ON DELETE RESTRICT,
  CONSTRAINT `fk_order_cashier`  FOREIGN KEY (`cashier_id`)       REFERENCES `users`(`id`)          ON DELETE SET NULL,
  CONSTRAINT `fk_order_register` FOREIGN KEY (`cash_register_id`) REFERENCES `cash_registers`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_items` (
  `id`              BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `order_id`        BIGINT UNSIGNED   NOT NULL,
  `product_id`      BIGINT UNSIGNED   NOT NULL,
  `quantity`        TINYINT UNSIGNED  NOT NULL DEFAULT 1,
  `unit_price`      DECIMAL(10,2)     NOT NULL,
  `modifiers_total` DECIMAL(10,2)     NOT NULL DEFAULT 0.00,
  `subtotal`        DECIMAL(10,2)     NOT NULL,
  `notes`           VARCHAR(255)      NULL DEFAULT NULL,
  `kitchen_status`  ENUM('pending','preparing','ready','cancelled') NOT NULL DEFAULT 'pending',
  `kitchen_area`    VARCHAR(60)       NULL DEFAULT NULL,
  `sent_at`         TIMESTAMP         NULL DEFAULT NULL,
  `ready_at`        TIMESTAMP         NULL DEFAULT NULL,
  `cancelled_at`    TIMESTAMP         NULL DEFAULT NULL,
  `cancelled_by`    BIGINT UNSIGNED   NULL DEFAULT NULL,
  `cancel_reason`   VARCHAR(255)      NULL DEFAULT NULL,
  `created_at`      TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at`      TIMESTAMP         NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_oi_kitchen_status` (`kitchen_status`),
  INDEX `idx_oi_order` (`order_id`),
  CONSTRAINT `fk_oi_order`   FOREIGN KEY (`order_id`)    REFERENCES `orders`(`id`)   ON DELETE CASCADE,
  CONSTRAINT `fk_oi_product` FOREIGN KEY (`product_id`)  REFERENCES `products`(`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_oi_cancel`  FOREIGN KEY (`cancelled_by`) REFERENCES `users`(`id`)   ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_item_modifiers` (
  `id`                 BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `order_item_id`      BIGINT UNSIGNED   NOT NULL,
  `modifier_option_id` BIGINT UNSIGNED   NOT NULL,
  `option_name`        VARCHAR(100)      NOT NULL,
  `extra_price`        DECIMAL(10,2)     NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_oim_item`   FOREIGN KEY (`order_item_id`)      REFERENCES `order_items`(`id`)      ON DELETE CASCADE,
  CONSTRAINT `fk_oim_option` FOREIGN KEY (`modifier_option_id`) REFERENCES `modifier_options`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_send_logs` (
  `id`       BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `user_id`  BIGINT UNSIGNED NOT NULL,
  `item_ids` JSON            NOT NULL,
  `sent_at`  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_osl_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_osl_user`  FOREIGN KEY (`user_id`)  REFERENCES `users`(`id`)  ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_discounts` (
  `id`             BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `order_id`       BIGINT UNSIGNED   NOT NULL,
  `promotion_id`   BIGINT UNSIGNED   NULL DEFAULT NULL,
  `type`           ENUM('percent','fixed','voucher') NOT NULL,
  `description`    VARCHAR(150)      NULL DEFAULT NULL,
  `voucher_code`   VARCHAR(50)       NULL DEFAULT NULL,
  `discount_value` DECIMAL(10,2)     NOT NULL,
  `applied_by`     BIGINT UNSIGNED   NOT NULL,
  `created_at`     TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_od_order`     FOREIGN KEY (`order_id`)    REFERENCES `orders`(`id`)     ON DELETE CASCADE,
  CONSTRAINT `fk_od_promotion` FOREIGN KEY (`promotion_id`) REFERENCES `promotions`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_od_user`      FOREIGN KEY (`applied_by`)  REFERENCES `users`(`id`)      ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `payments` (
  `id`               BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `order_id`         BIGINT UNSIGNED   NOT NULL,
  `cash_register_id` BIGINT UNSIGNED   NULL DEFAULT NULL,
  `method`           ENUM('cash','credit_card','debit_card','bank_transfer','qr_wallet','internal_credit') NOT NULL,
  `amount`           DECIMAL(10,2)     NOT NULL,
  `cash_tendered`    DECIMAL(10,2)     NULL DEFAULT NULL,
  `cash_change`      DECIMAL(10,2)     NULL DEFAULT NULL,
  `reference_number` VARCHAR(100)      NULL DEFAULT NULL,
  `gateway`          VARCHAR(50)       NULL DEFAULT NULL,
  `gateway_tx_id`    VARCHAR(150)      NULL DEFAULT NULL,
  `status`           ENUM('pending','approved','failed','refunded') NOT NULL DEFAULT 'approved',
  `processed_by`     BIGINT UNSIGNED   NOT NULL,
  `processed_at`     TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at`       TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at`       TIMESTAMP         NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_pay_order`    FOREIGN KEY (`order_id`)         REFERENCES `orders`(`id`)         ON DELETE RESTRICT,
  CONSTRAINT `fk_pay_register` FOREIGN KEY (`cash_register_id`) REFERENCES `cash_registers`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_pay_user`     FOREIGN KEY (`processed_by`)     REFERENCES `users`(`id`)          ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `invoices` (
  `id`                 BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `order_id`           BIGINT UNSIGNED   NOT NULL,
  `type`               ENUM('factura','nota_venta','nota_credito') NOT NULL DEFAULT 'factura',
  `sequential`         VARCHAR(20)       NOT NULL,
  `access_key`         VARCHAR(49)       NULL DEFAULT NULL,
  `authorization_date` TIMESTAMP         NULL DEFAULT NULL,
  `xml_path`           VARCHAR(255)      NULL DEFAULT NULL,
  `ride_path`          VARCHAR(255)      NULL DEFAULT NULL,
  `sri_status`         ENUM('draft','sent','authorized','rejected','cancelled') NOT NULL DEFAULT 'draft',
  `customer_name`      VARCHAR(150)      NULL DEFAULT NULL,
  `customer_ruc`       VARCHAR(13)       NULL DEFAULT NULL,
  `customer_email`     VARCHAR(150)      NULL DEFAULT NULL,
  `customer_address`   VARCHAR(255)      NULL DEFAULT NULL,
  `sri_response`       JSON              NULL DEFAULT NULL,
  `created_at`         TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`         TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_inv_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- MÓDULO 5 - INVENTARIO
-- ============================================================

CREATE TABLE `suppliers` (
  `id`             BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `name`           VARCHAR(150)      NOT NULL,
  `ruc`            VARCHAR(13)       NULL DEFAULT NULL,
  `phone`          VARCHAR(20)       NULL DEFAULT NULL,
  `email`          VARCHAR(150)      NULL DEFAULT NULL,
  `contact_person` VARCHAR(100)      NULL DEFAULT NULL,
  `payment_terms`  VARCHAR(100)      NULL DEFAULT NULL,
  `notes`          TEXT              NULL DEFAULT NULL,
  `is_active`      TINYINT(1)        NOT NULL DEFAULT 1,
  `created_at`     TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at`     TIMESTAMP         NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `ingredient_categories` (
  `id`   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(80)     NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `ingredients` (
  `id`                     BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `ingredient_category_id` BIGINT UNSIGNED   NULL DEFAULT NULL,
  `name`                   VARCHAR(150)      NOT NULL,
  `unit`                   VARCHAR(20)       NOT NULL,
  `stock_current`          DECIMAL(12,4)     NOT NULL DEFAULT 0.0000,
  `stock_minimum`          DECIMAL(12,4)     NOT NULL DEFAULT 0.0000,
  `unit_cost`              DECIMAL(10,4)     NOT NULL DEFAULT 0.0000,
  `is_active`              TINYINT(1)        NOT NULL DEFAULT 1,
  `created_at`             TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`             TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at`             TIMESTAMP         NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_ing_cat` FOREIGN KEY (`ingredient_category_id`) REFERENCES `ingredient_categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `recipes` (
  `id`            BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `product_id`    BIGINT UNSIGNED   NOT NULL,
  `ingredient_id` BIGINT UNSIGNED   NOT NULL,
  `quantity`      DECIMAL(12,4)     NOT NULL,
  `auto_deduct`   TINYINT(1)        NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_recipe` (`product_id`,`ingredient_id`),
  CONSTRAINT `fk_rec_product`    FOREIGN KEY (`product_id`)    REFERENCES `products`(`id`)     ON DELETE CASCADE,
  CONSTRAINT `fk_rec_ingredient` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients`(`id`)  ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `inventory_movements` (
  `id`             BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `ingredient_id`  BIGINT UNSIGNED   NOT NULL,
  `user_id`        BIGINT UNSIGNED   NOT NULL,
  `type`           ENUM('purchase','sale','manual_in','manual_out','adjustment') NOT NULL,
  `quantity`       DECIMAL(12,4)     NOT NULL,
  `stock_before`   DECIMAL(12,4)     NOT NULL,
  `stock_after`    DECIMAL(12,4)     NOT NULL,
  `unit_cost`      DECIMAL(10,4)     NULL DEFAULT NULL,
  `reference_id`   BIGINT UNSIGNED   NULL DEFAULT NULL,
  `reference_type` VARCHAR(80)       NULL DEFAULT NULL,
  `reason`         VARCHAR(255)      NULL DEFAULT NULL,
  `created_at`     TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_im_ingredient` (`ingredient_id`),
  INDEX `idx_im_date`       (`created_at`),
  CONSTRAINT `fk_im_ingredient` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients`(`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_im_user`       FOREIGN KEY (`user_id`)       REFERENCES `users`(`id`)       ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `purchase_orders` (
  `id`          BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `supplier_id` BIGINT UNSIGNED   NOT NULL,
  `user_id`     BIGINT UNSIGNED   NOT NULL,
  `status`      ENUM('draft','sent','received','cancelled') NOT NULL DEFAULT 'draft',
  `total`       DECIMAL(12,2)     NOT NULL DEFAULT 0.00,
  `notes`       TEXT              NULL DEFAULT NULL,
  `ordered_at`  DATE              NULL DEFAULT NULL,
  `received_at` DATE              NULL DEFAULT NULL,
  `created_at`  TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_po_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_po_user`     FOREIGN KEY (`user_id`)     REFERENCES `users`(`id`)     ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `purchase_order_items` (
  `id`                BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `purchase_order_id` BIGINT UNSIGNED   NOT NULL,
  `ingredient_id`     BIGINT UNSIGNED   NOT NULL,
  `quantity`          DECIMAL(12,4)     NOT NULL,
  `unit_cost`         DECIMAL(10,4)     NOT NULL,
  `subtotal`          DECIMAL(12,2)     NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_poi_order`      FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_poi_ingredient` FOREIGN KEY (`ingredient_id`)     REFERENCES `ingredients`(`id`)     ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- MÓDULO 9 - NOTIFICACIONES
-- ============================================================

CREATE TABLE `notifications` (
  `id`              CHAR(36)        NOT NULL,
  `type`            VARCHAR(255)    NOT NULL,
  `notifiable_type` VARCHAR(100)    NOT NULL,
  `notifiable_id`   BIGINT UNSIGNED NOT NULL,
  `data`            JSON            NOT NULL,
  `read_at`         TIMESTAMP       NULL DEFAULT NULL,
  `created_at`      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_notif_notifiable` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- MÓDULO 10 - WHATSAPP MARKETING Y CHATBOT
-- ============================================================

CREATE TABLE `whatsapp_contacts` (
  `id`          BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `phone`       VARCHAR(20)       NOT NULL,
  `name`        VARCHAR(100)      NULL DEFAULT NULL,
  `tags`        JSON              NULL DEFAULT NULL,
  `opt_out`     TINYINT(1)        NOT NULL DEFAULT 0,
  `opt_out_at`  TIMESTAMP         NULL DEFAULT NULL,
  `consent_at`  TIMESTAMP         NULL DEFAULT NULL,
  `status`      ENUM('active','blocked','opt_out') NOT NULL DEFAULT 'active',
  `last_seen_at` TIMESTAMP        NULL DEFAULT NULL,
  `created_at`  TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_wa_phone` (`phone`),
  INDEX `idx_wa_contact_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `whatsapp_templates` (
  `id`               BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `meta_template_id` VARCHAR(100)      NULL DEFAULT NULL,
  `name`             VARCHAR(100)      NOT NULL,
  `type`             ENUM('TEXT','IMAGE_TEXT','VIDEO_TEXT','DOCUMENT','CAROUSEL','CATALOG') NOT NULL DEFAULT 'TEXT',
  `category`         VARCHAR(50)       NULL DEFAULT NULL,
  `language`         VARCHAR(10)       NOT NULL DEFAULT 'es',
  `body_text`        TEXT              NOT NULL,
  `header_media_url` VARCHAR(255)      NULL DEFAULT NULL,
  `buttons`          JSON              NULL DEFAULT NULL,
  `meta_status`      ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `is_active`        TINYINT(1)        NOT NULL DEFAULT 1,
  `created_at`       TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `whatsapp_campaigns` (
  `id`                   BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `whatsapp_template_id` BIGINT UNSIGNED   NOT NULL,
  `name`                 VARCHAR(150)      NOT NULL,
  `audience_type`        ENUM('all','segment','manual_list') NOT NULL DEFAULT 'all',
  `audience_criteria`    JSON              NULL DEFAULT NULL,
  `scheduled_at`         TIMESTAMP         NULL DEFAULT NULL,
  `recurrence`           ENUM('none','daily','weekly') NOT NULL DEFAULT 'none',
  `recurrence_time`      TIME              NULL DEFAULT NULL,
  `recurrence_day`       TINYINT UNSIGNED  NULL DEFAULT NULL,
  `variables`            JSON              NULL DEFAULT NULL,
  `daily_send_limit`     SMALLINT UNSIGNED NULL DEFAULT NULL,
  `status`               ENUM('draft','scheduled','sending','sent','cancelled') NOT NULL DEFAULT 'draft',
  `sent_at`              TIMESTAMP         NULL DEFAULT NULL,
  `created_by`           BIGINT UNSIGNED   NOT NULL,
  `created_at`           TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`           TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_wc_template` FOREIGN KEY (`whatsapp_template_id`) REFERENCES `whatsapp_templates`(`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_wc_user`     FOREIGN KEY (`created_by`)           REFERENCES `users`(`id`)              ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `whatsapp_campaign_logs` (
  `id`              BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `campaign_id`     BIGINT UNSIGNED   NOT NULL,
  `contact_id`      BIGINT UNSIGNED   NOT NULL,
  `meta_message_id` VARCHAR(100)      NULL DEFAULT NULL,
  `status`          ENUM('queued','sent','delivered','read','failed','opt_out') NOT NULL DEFAULT 'queued',
  `error_code`      VARCHAR(20)       NULL DEFAULT NULL,
  `error_message`   VARCHAR(255)      NULL DEFAULT NULL,
  `sent_at`         TIMESTAMP         NULL DEFAULT NULL,
  `delivered_at`    TIMESTAMP         NULL DEFAULT NULL,
  `read_at`         TIMESTAMP         NULL DEFAULT NULL,
  `responded_at`    TIMESTAMP         NULL DEFAULT NULL,
  `created_at`      TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_wcl_campaign` (`campaign_id`),
  INDEX `idx_wcl_status`   (`status`),
  CONSTRAINT `fk_wcl_campaign` FOREIGN KEY (`campaign_id`) REFERENCES `whatsapp_campaigns`(`id`)  ON DELETE CASCADE,
  CONSTRAINT `fk_wcl_contact`  FOREIGN KEY (`contact_id`)  REFERENCES `whatsapp_contacts`(`id`)   ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `whatsapp_conversations` (
  `id`              BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `contact_id`      BIGINT UNSIGNED   NOT NULL,
  `agent_id`        BIGINT UNSIGNED   NULL DEFAULT NULL,
  `direction`       ENUM('inbound','outbound') NOT NULL,
  `message_type`    ENUM('text','image','video','document','template','catalog','button','location','audio') NOT NULL DEFAULT 'text',
  `content`         TEXT              NULL DEFAULT NULL,
  `media_url`       VARCHAR(255)      NULL DEFAULT NULL,
  `meta_message_id` VARCHAR(100)      NULL DEFAULT NULL,
  `bot_active`      TINYINT(1)        NOT NULL DEFAULT 1,
  `is_read`         TINYINT(1)        NOT NULL DEFAULT 0,
  `sent_at`         TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at`      TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_wconv_contact` (`contact_id`),
  INDEX `idx_wconv_sent`    (`sent_at`),
  CONSTRAINT `fk_wconv_contact` FOREIGN KEY (`contact_id`) REFERENCES `whatsapp_contacts`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_wconv_agent`   FOREIGN KEY (`agent_id`)   REFERENCES `users`(`id`)             ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `whatsapp_chatbot_flows` (
  `id`           BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `name`         VARCHAR(100)      NOT NULL,
  `trigger_expr` VARCHAR(255)      NOT NULL,
  `type`         ENUM('keyword','button','fallback') NOT NULL DEFAULT 'keyword',
  `response`     JSON              NOT NULL,
  `next_flow_id` BIGINT UNSIGNED   NULL DEFAULT NULL,
  `sort_order`   TINYINT UNSIGNED  NOT NULL DEFAULT 0,
  `is_active`    TINYINT(1)        NOT NULL DEFAULT 1,
  `created_at`   TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_cbf_next` FOREIGN KEY (`next_flow_id`) REFERENCES `whatsapp_chatbot_flows`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `whatsapp_catalog_items` (
  `id`             BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `product_id`     BIGINT UNSIGNED   NOT NULL,
  `meta_item_id`   VARCHAR(100)      NULL DEFAULT NULL,
  `meta_status`    ENUM('active','out_of_stock','not_synced') NOT NULL DEFAULT 'not_synced',
  `last_synced_at` TIMESTAMP         NULL DEFAULT NULL,
  `created_at`     TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_wci_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `whatsapp_orders` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id`    BIGINT UNSIGNED NOT NULL,
  `contact_id`  BIGINT UNSIGNED NOT NULL,
  `campaign_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at`  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_wo_order`    FOREIGN KEY (`order_id`)    REFERENCES `orders`(`id`)              ON DELETE CASCADE,
  CONSTRAINT `fk_wo_contact`  FOREIGN KEY (`contact_id`)  REFERENCES `whatsapp_contacts`(`id`)   ON DELETE RESTRICT,
  CONSTRAINT `fk_wo_campaign` FOREIGN KEY (`campaign_id`) REFERENCES `whatsapp_campaigns`(`id`)  ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- DATOS SEMILLA BASE
-- ============================================================

INSERT INTO `zones` (`name`, `description`, `sort_order`) VALUES
  ('Sala principal', 'Area principal del restaurante', 1),
  ('Terraza', 'Area exterior', 2),
  ('Barra', 'Barra de atencion directa', 3);

INSERT INTO `shifts` (`name`, `start_time`, `end_time`) VALUES
  ('Manana', '08:00:00', '14:00:00'),
  ('Tarde',  '14:00:00', '20:00:00'),
  ('Noche',  '20:00:00', '23:59:00');

INSERT INTO `restaurant_settings`
  (`trade_name`, `currency`, `timezone`, `tax_rate`, `tip_suggestions`, `kds_alert_minutes`)
VALUES
  ('Mi Restaurante', 'USD', 'America/Guayaquil', 15.00, '[10, 15, 20]', 10);

INSERT INTO `categories` (`name`, `sort_order`) VALUES
  ('Entradas', 1),
  ('Platos fuertes', 2),
  ('Bebidas', 3),
  ('Postres', 4);

INSERT INTO `ingredient_categories` (`name`) VALUES
  ('Carnes'),
  ('Lacteos'),
  ('Bebidas'),
  ('Verduras'),
  ('Insumos de limpieza');

INSERT INTO `whatsapp_chatbot_flows` (`name`, `trigger_expr`, `type`, `response`, `sort_order`) VALUES
  ('Bienvenida',
   'hola|buenos dias|hi|buenas',
   'keyword',
   '{"text": "Bienvenido! En que podemos ayudarte?", "buttons": ["Ver menu", "Hacer un pedido", "Promociones", "Hablar con alguien"]}',
   1),
  ('Ver menu',
   'menu|carta|que tienen|que hay',
   'keyword',
   '{"text": "Aqui tienes nuestro menu", "action": "send_catalog"}',
   2),
  ('Pedido',
   'pedido|ordenar|quiero pedir',
   'keyword',
   '{"text": "Tu pedido es para llevar o delivery?", "buttons": ["Para llevar", "Delivery"]}',
   3),
  ('Handoff',
   'agente|persona|humano|ayuda',
   'keyword',
   '{"text": "Te conectamos con un asesor", "action": "handoff"}',
   4),
  ('Despedida',
   'gracias|listo|ok|bye',
   'keyword',
   '{"text": "Hasta pronto!", "action": "close_session"}',
   5);

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- FIN DEL ESQUEMA — solemia v1.0 | Junio 2026
-- ============================================================
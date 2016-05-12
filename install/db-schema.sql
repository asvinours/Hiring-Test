-- ---
-- Globals
-- ---

-- SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
-- SET FOREIGN_KEY_CHECKS=0;


DROP TABLE IF EXISTS `stocks`;
DROP TABLE IF EXISTS `prices`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `countries`;
DROP TABLE IF EXISTS `currencies`;


-- ---
-- Table 'categories'
-- 
-- ---

DROP TABLE IF EXISTS `categories`;
		
CREATE TABLE `categories` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(55) NOT NULL,
  `parent_id` INTEGER NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'countries'
-- 
-- ---

DROP TABLE IF EXISTS `countries`;
		
CREATE TABLE `countries` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(55) NOT NULL,
  `code` VARCHAR(2) NOT NULL,
  `currency_id` INTEGER NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'currencies'
-- 
-- ---

DROP TABLE IF EXISTS `currencies`;
		
CREATE TABLE `currencies` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(3) NOT NULL,
  `format` VARCHAR(15) NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'prices'
-- 
-- --

DROP TABLE IF EXISTS `prices`;
		
CREATE TABLE `prices` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `price` DECIMAL NOT NULL,
  `country_id` INTEGER NULL DEFAULT NULL,
  `product_id` INTEGER NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'products'
-- 
-- ---

DROP TABLE IF EXISTS `products`;
		
CREATE TABLE `products` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `category_id` INTEGER NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'stocks'
-- 
-- ---

DROP TABLE IF EXISTS `stocks`;
		
CREATE TABLE `stocks` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `quantity` INTEGER NOT NULL DEFAULT 0,
  `product_id` INTEGER NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Foreign Keys 
-- ---

ALTER TABLE `categories` ADD FOREIGN KEY (parent_id) REFERENCES `categories` (`id`);
ALTER TABLE `countries` ADD FOREIGN KEY (currency_id) REFERENCES `currencies` (`id`);
ALTER TABLE `prices` ADD FOREIGN KEY (country_id) REFERENCES `countries` (`id`);
ALTER TABLE `prices` ADD FOREIGN KEY (product_id) REFERENCES `products` (`id`);
ALTER TABLE `products` ADD FOREIGN KEY (category_id) REFERENCES `categories` (`id`);
ALTER TABLE `stocks` ADD FOREIGN KEY (product_id) REFERENCES `products` (`id`);

-- ---
-- Table Properties
-- ---

ALTER TABLE `categories` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
ALTER TABLE `countries` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
ALTER TABLE `currencies` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
ALTER TABLE `prices` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
ALTER TABLE `products` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
ALTER TABLE `stocks` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
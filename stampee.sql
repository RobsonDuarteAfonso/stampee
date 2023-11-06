
-- -----------------------------------------------------
-- Schema stampee
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `stampee` DEFAULT CHARACTER SET utf8 ;
USE `stampee` ;

-- -----------------------------------------------------
-- Table `stampee`.`access`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `stampee`.`access` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stampee`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `stampee`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `access_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  INDEX `fk_User_access_idx` (`access_id` ASC),
  CONSTRAINT `fk_User_access`
    FOREIGN KEY (`access_id`)
    REFERENCES `stampee`.`access` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stampee`.`status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `stampee`.`status` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `status` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stampee`.`state`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `stampee`.`state` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `state` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stampee`.`stamp`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `stampee`.`stamp` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` TEXT NOT NULL,
  `country` VARCHAR(45) NOT NULL,
  `state_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_stamp_state1_idx` (`state_id` ASC),
  CONSTRAINT `fk_stamp_state1`
    FOREIGN KEY (`state_id`)
    REFERENCES `stampee`.`state` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stampee`.`auction`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `stampee`.`auction` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` TEXT NOT NULL,
  `country` VARCHAR(45) NOT NULL,
  `date_start` DATE NOT NULL,
  `date_end` DATE NOT NULL,
  `price` DECIMAL(18,2) NOT NULL,
  `user_id` INT NOT NULL,
  `status_id` INT NOT NULL,
  `stamp_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_auction_User1_idx` (`user_id` ASC),
  INDEX `fk_auction_status1_idx` (`status_id` ASC),
  INDEX `fk_auction_stamp1_idx` (`stamp_id` ASC),
  CONSTRAINT `fk_auction_User1`
    FOREIGN KEY (`user_id`)
    REFERENCES `stampee`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_auction_status1`
    FOREIGN KEY (`status_id`)
    REFERENCES `stampee`.`status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_auction_stamp1`
    FOREIGN KEY (`stamp_id`)
    REFERENCES `stampee`.`stamp` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stampee`.`favorite`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `stampee`.`favorite` (
  `user_id` INT NOT NULL,
  `auction_id` INT NOT NULL,
  PRIMARY KEY (`user_id`, `auction_id`),
  INDEX `fk_User_has_auction_auction1_idx` (`auction_id` ASC),
  INDEX `fk_User_has_auction_User1_idx` (`user_id` ASC),
  CONSTRAINT `fk_User_has_auction_User1`
    FOREIGN KEY (`user_id`)
    REFERENCES `stampee`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_User_has_auction_auction1`
    FOREIGN KEY (`auction_id`)
    REFERENCES `stampee`.`auction` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stampee`.`comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `stampee`.`comment` (
  `user_id` INT NOT NULL,
  `auction_id` INT NOT NULL,
  PRIMARY KEY (`user_id`, `auction_id`),
  INDEX `fk_User_has_auction_auction2_idx` (`auction_id` ASC),
  INDEX `fk_User_has_auction_User2_idx` (`user_id` ASC),
  CONSTRAINT `fk_User_has_auction_User2`
    FOREIGN KEY (`user_id`)
    REFERENCES `stampee`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_User_has_auction_auction2`
    FOREIGN KEY (`auction_id`)
    REFERENCES `stampee`.`auction` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stampee`.`image`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `stampee`.`image` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `link` VARCHAR(255) NOT NULL,
  `main` INT NULL,
  `stamp_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_image_stamp1_idx` (`stamp_id` ASC),
  CONSTRAINT `fk_image_stamp1`
    FOREIGN KEY (`stamp_id`)
    REFERENCES `stampee`.`stamp` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `stampee`.`bid`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `stampee`.`bid` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `price` DECIMAL(18,2) NOT NULL,
  `date` DATETIME NOT NULL,
  `auction_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_bid_auction1_idx` (`auction_id` ASC),
  INDEX `fk_bid_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_bid_auction1`
    FOREIGN KEY (`auction_id`)
    REFERENCES `stampee`.`auction` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_bid_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `stampee`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- -----------------------------------------------------
-- Donnée initiale - Access
-- -----------------------------------------------------
INSERT INTO `stampee`.`access` (type) VALUES ('Admin');
INSERT INTO `stampee`.`access` (type) VALUES ('Client');


-- -----------------------------------------------------
-- Donnée initiale - Status
-- -----------------------------------------------------
INSERT INTO `stampee`.`status` (status) VALUES ('Créé');
INSERT INTO `stampee`.`status` (status) VALUES ('En cours');
INSERT INTO `stampee`.`status` (status) VALUES ('Terminé');


-- -----------------------------------------------------
-- Donnée initiale - State
-- -----------------------------------------------------
INSERT INTO `stampee`.`state` (state) VALUES ('Neuf');
INSERT INTO `stampee`.`state` (state) VALUES ('Sous Neuf');
INSERT INTO `stampee`.`state` (state) VALUES ('Usagé');
INSERT INTO `stampee`.`state` (state) VALUES ('Endommagé');
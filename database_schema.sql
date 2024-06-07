DROP SCHEMA IF EXISTS `stock` ;
CREATE SCHEMA IF NOT EXISTS `stock` 

CREATE TABLE IF NOT EXISTS `stock`.`coming` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `product_id` INT NOT NULL,
  `count` INT NOT NULL,
  `unloading` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 13
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci

CREATE TABLE IF NOT EXISTS `stock`.`expenses` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `product_id` INT NOT NULL,
  `count` INT NOT NULL,
  `done` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 13
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci

CREATE TABLE IF NOT EXISTS `stock`.`products` (
  `product_id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `weight` FLOAT NOT NULL,
  `volume` FLOAT NOT NULL,
  PRIMARY KEY (`product_id`),
  CONSTRAINT `KEY_com`
    FOREIGN KEY (`product_id`)
    REFERENCES `stock`.`coming` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `KEY_exp`
    FOREIGN KEY (`product_id`)
    REFERENCES `stock`.`expenses` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci

CREATE TABLE IF NOT EXISTS `stock`.`storage` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `section` INT NOT NULL,
  `product_id` INT NOT NULL,
  `count` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 12
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci

DELIMITER $$

DELIMITER $$
USE `stock`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `coming_proc`(IN prodid INT, IN prodcount INT)
BEGIN
	DECLARE MAX_VOLUME INT DEFAULT 300;
	DECLARE id_stock INT;
    DECLARE count_stock INT;
    
    DECLARE c INT;
    DECLARE storage_section INT;
    
	DECLARE volume FLOAT;
    
	DECLARE done INT DEFAULT 0;
    DECLARE cur_coming CURSOR FOR SELECT storage.id, storage.count FROM storage	WHERE storage.product_id = prodid;
    DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
    
    SET volume = (SELECT products.volume FROM products WHERE product_id = prodid LIMIT 1);
        
    OPEN cur_coming;
    REPEAT
		FETCH cur_coming INTO id_stock, count_stock;
        IF NOT done THEN
			BEGIN
				SET c = (MAX_VOLUME - count_stock * volume) DIV volume;
				IF c > prodcount THEN
					BEGIN
						UPDATE storage
						SET storage.count = storage.count + prodcount
						WHERE storage.id = id_stock;
						SET prodcount = 0;
						SET done = 1;
					END;
				ELSE
					BEGIN
						UPDATE storage
						SET storage.count = storage.count + c
						WHERE storage.id = id_stock;
						SET prodcount = prodcount - c;
					END;
				END IF;
			END;
		END IF;
	UNTIL done END REPEAT;
    
	CLOSE cur_coming;
        
	WHILE prodcount != 0 DO
		BEGIN
			SET c = MAX_VOLUME DIV volume;
			IF c > prodcount THEN
				BEGIN
					SET storage_section = search_empty_sections();
					INSERT INTO storage (section, product_id, count)
					VALUES (storage_section, prodid, prodcount);
					SET prodcount = 0;
				END;
			ELSE
				BEGIN
					SET storage_section = search_empty_sections();
					INSERT INTO storage (section, product_id, count)
					VALUES (storage_section, prodid, c);
					SET prodcount = prodcount - c;
				END;
			END IF;
        END;
	END WHILE;
END$$

DELIMITER ;

DELIMITER $$

DELIMITER $$
USE `stock`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `coming_proc_table`(IN prodid INT, IN prodcount INT)
BEGIN
DECLARE MAX_VOLUME INT DEFAULT 300;
	DECLARE id_stock INT;
    DECLARE count_stock INT;
    
    DECLARE c INT;
    DECLARE storage_section INT;
    
	DECLARE volume FLOAT;
    DECLARE weight FLOAT;
    
	DECLARE done INT DEFAULT 0;
    DECLARE cur_coming CURSOR FOR SELECT storage.id, storage.count FROM storage	WHERE storage.product_id = prodid;
    DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
    
    SET volume = (SELECT products.volume FROM products WHERE product_id = prodid LIMIT 1);
    SET weight = (SELECT products.weight FROM products WHERE product_id = prodid LIMIT 1);
    
    DROP TABLE IF EXISTS coming_tbl;
    CREATE TEMPORARY TABLE coming_tbl(
		section int,
		count int,
        weight float
		);
        
    OPEN cur_coming;
    REPEAT
		FETCH cur_coming INTO id_stock, count_stock;
        IF NOT done THEN
			BEGIN
				SET c = (MAX_VOLUME - count_stock * volume) DIV volume;
                IF c > 0 THEN
					BEGIN
						IF c > prodcount THEN
							BEGIN
								SET storage_section = (SELECT section FROM storage WHERE id = id_stock);
								INSERT INTO coming_tbl(section, count, weight)
								VALUES(storage_section, prodcount, prodcount * weight);
								SET prodcount = 0;
								SET done = 1;
							END;
						ELSE
							BEGIN
								SET storage_section = (SELECT section FROM storage WHERE id = id_stock);
								INSERT INTO coming_tbl(section, count, weight)
								VALUES(storage_section, (MAX_VOLUME - count_stock * volume) DIV volume, c * weight);
								SET prodcount = prodcount - c;
								-- SELECT count_stock;
							END;
						END IF;
					END;
				END IF;
			END;
		END IF;
	UNTIL done END REPEAT;
    
	CLOSE cur_coming;
    
	WHILE prodcount != 0 DO
		BEGIN
			SET c = MAX_VOLUME DIV volume;
			IF c > prodcount THEN
				BEGIN
					SET storage_section = search_empty_sections();
                    INSERT INTO coming_tbl(section, count, weight)
					VALUES(storage_section, prodcount, prodcount * weight);
					SET prodcount = 0;
				END;
			ELSE
				BEGIN
					SET storage_section = search_empty_sections();
					INSERT INTO coming_tbl(section, count, weight)
					VALUES(storage_section, c, c * weight);
					SET prodcount = prodcount - c;
				END;
			END IF;
        END;
	END WHILE;
    SELECT * FROM coming_tbl;
END$$

DELIMITER ;

DELIMITER $$

DELIMITER $$
USE `stock`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `expense_proc_table`(IN prodid INT, IN prodcount INT)
BEGIN
	DECLARE id_stock INT;
    DECLARE count_stock INT;
    
    DECLARE asolute_count INT;

    DECLARE storage_section INT;
    DECLARE weight FLOAT;
    
	DECLARE done INT DEFAULT 0;
    DECLARE cur_exp CURSOR FOR SELECT storage.id, storage.count FROM storage WHERE storage.product_id = prodid;
    DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
    
    SET asolute_count = (SELECT SUM(count) FROM storage WHERE product_id = prodid);
    SET weight = (SELECT products.weight FROM products WHERE product_id = prodid LIMIT 1);
    
    IF asolute_count < prodcount THEN
		SELECT "err";
        ROLLBACK;
    END IF;
    
    DROP TABLE IF EXISTS expense_tbl;
	CREATE TEMPORARY TABLE expense_tbl(
		section int,
		count int,
		weight float
	);
    
    OPEN cur_exp;
    REPEAT
		FETCH cur_exp INTO id_stock, count_stock;
        IF NOT done THEN
				IF count_stock > prodcount THEN
					BEGIN
						SET storage_section = (SELECT section FROM storage WHERE id = id_stock);
						INSERT INTO expense_tbl(section, count, weight)
                        VALUES(storage_section, prodcount, prodcount * weight);
						SET prodcount = 0;
                        SET done = 1;
					END;
				ELSE
					BEGIN
						SET storage_section = (SELECT section FROM storage WHERE id = id_stock);
						INSERT INTO expense_tbl(section, count, weight)
                        VALUES(storage_section, count_stock, count_stock * weight);
                        SET prodcount = prodcount - count_stock;
					END;
				END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE cur_exp;
IF (SELECT COUNT(*) FROM expense_tbl) = 0 THEN
	SELECT "err";
ELSE
	SELECT * FROM expense_tbl;
END IF;
END$$

DELIMITER ;

DELIMITER $$

DELIMITER $$
USE `stock`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `expense_func`(prodid INT, prodcount INT) RETURNS int
    DETERMINISTIC
BEGIN
	DECLARE id_stock INT;
    DECLARE count_stock INT;
    
    DECLARE asolute_count INT;

    DECLARE storage_section INT;
    
	DECLARE done INT DEFAULT 0;
    DECLARE cur_exp CURSOR FOR SELECT storage.id, storage.count FROM storage WHERE storage.product_id = prodid;
    DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
    
    SET asolute_count = (SELECT SUM(count) FROM storage WHERE product_id = prodid);
    
    IF asolute_count < prodcount THEN
		RETURN 0;
    END IF;
    
    OPEN cur_exp;
    REPEAT
		FETCH cur_exp INTO id_stock, count_stock;
        IF NOT done THEN
				IF count_stock > prodcount THEN
					BEGIN
						UPDATE storage
						SET storage.count = storage.count - prodcount
						WHERE storage.id = id_stock;
						SET prodcount = 0;
                        SET done = 1;
					END;
				ELSE
					BEGIN
						DELETE FROM storage
						WHERE storage.id = id_stock;
						SET prodcount = prodcount - count_stock;
					END;
				END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE cur_exp;
RETURN 1;
END$$

DELIMITER ;

DELIMITER $$

DELIMITER $$
USE `stock`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `search_empty_sections`() RETURNS int
    DETERMINISTIC
BEGIN
	DECLARE sect INT DEFAULT 1;
    
    WHILE sect != (SELECT MAX(section) FROM storage) DO
		BEGIN
			IF sect NOT IN (SELECT section FROM storage) THEN
				RETURN sect;
			END IF;
			SET sect = sect + 1;
		END;
    END WHILE;
RETURN (SELECT MAX(section) FROM storage) + 1;
END$$

DELIMITER ;

CREATE USER 'admin'@'localhost' IDENTIFIED BY 'admin';
CREATE ROLE 'stockuser'@'localhost';
CREATE USER 'user'@'localhost' IDENTIFIED BY 'user';
GRANT 'stockuser'@'localhost' TO  'user'@'localhost';
CREATE USER 'manager'@'localhost' IDENTIFIED BY 'manager';

GRANT 'stockuser'@'localhost' TO 'user'@'localhost';
GRANT ALL ON stock.* TO 'manager'@'localhost';
GRANT SELECT ON stock.products TO 'stockuser'@'localhost';
GRANT SELECT, UPDATE ON stock.coming TO 'stockuser'@'localhost';
GRANT SELECT, UPDATE ON stock.expenses TO 'stockuser'@'localhost';
GRANT ALL ON stock.storage TO 'stockuser'@'localhost';
GRANT EXECUTE ON PROCEDURE stock.coming_proc TO 'stockuser'@'localhost';
GRANT EXECUTE ON PROCEDURE stock.coming_proc_table TO 'stockuser'@'localhost';
GRANT EXECUTE ON FUNCTION stock.search_empty_sections TO 'stockuser'@'localhost';
GRANT EXECUTE ON FUNCTION stock.expense_func TO 'stockuser'@'localhost';
GRANT EXECUTE ON PROCEDURE stock.expense_proc_table TO 'stockuser'@'localhost';

SET DEFAULT ROLE ALL TO 'user'@'localhost';
FLUSH PRIVILEGES;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

-- -----------------------------------------------------
-- Table `Registro`.`car_situacoes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `car_situacoes` (
  `idSituacoes` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(50) NOT NULL ,
  PRIMARY KEY (`idsituacoes`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Registro`.`car_pedidos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `car_pedidos` (
  `idPedido` INT NOT NULL AUTO_INCREMENT ,
  `datapedido` TIMESTAMP NOT NULL ,
  `tipodocumentorequerente` ENUM('1','2','5') NULL ,
  `documentorequerente` VARCHAR(14) NULL ,
  `requerente` VARCHAR(50) NOT NULL ,
  `telefone` VARCHAR(50) NULL ,
  `dataprevista` DATE NULL ,
  `datacancelamento` DATE NULL ,
  `valorpedido` DOUBLE NOT NULL ,
  `valordeposito` DOUBLE NULL ,
  `valorreceber` DOUBLE NOT NULL ,
  `idSituacoes` INT NOT NULL ,
  PRIMARY KEY (`idPedido`) ,
  INDEX `fk_pedidos_situacoes1` (`idSituacoes` ASC) ,
    FOREIGN KEY (`idSituacoes`) REFERENCES `car_situacoes` (`idSituacoes`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Registro`.`car_natureza`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `car_natureza` (
  `idNatureza` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(50) NOT NULL ,
  PRIMARY KEY (`idNatureza`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Registro`.`car_tipodocumentos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `car_tipodocumentos` (
  `idTipodocumentos` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(50) NOT NULL ,
  `natureza` VARCHAR(45) NOT NULL ,
  `idNatureza` INT NOT NULL ,
  PRIMARY KEY (`idTipodocumentos`) ,
  INDEX `fk_car_tipodocumentos_car_natureza1` (`idNatureza` ASC) ,
    FOREIGN KEY (`idNatureza`) REFERENCES `car_natureza` (`idNatureza`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Registro`.`car_vigencia`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `car_vigencia` (
  `idVigencia` INT NOT NULL AUTO_INCREMENT ,
  `data` DATE NOT NULL ,
  PRIMARY KEY (`idVigencia`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Registro`.`car_emolumentos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `car_emolumentos` (
  `idEmolumentos` INT NOT NULL AUTO_INCREMENT ,
  `valorinicial` DOUBLE NOT NULL ,
  `valorfinal` DOUBLE NOT NULL ,
  `emolumentos` DOUBLE NOT NULL ,
  `idVigencia` INT NOT NULL ,
  `idTipodocumentos` INT NOT NULL ,
  PRIMARY KEY (`idEmolumentos`) ,
  INDEX `fk_car_emolumentos_car_vigencia1` (`idVigencia` ASC) ,
  INDEX `fk_car_emolumentos_car_tipodocumentos1` (`idTipodocumentos` ASC) ,
    FOREIGN KEY (`idVigencia`) REFERENCES `car_vigencia` (`idVigencia`),
    FOREIGN KEY (`idTipodocumentos`) REFERENCES `car_tipodocumentos` (`idTipodocumentos`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Registro`.`car_pessoas`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `car_pessoas` (
  `idPessoas` INT NOT NULL AUTO_INCREMENT ,
  `tipodocumento` ENUM('1','2','3','4','5') NULL ,
  `documento` VARCHAR(45) NULL ,
  `nome` VARCHAR(50) NULL ,
  PRIMARY KEY (`idPessoas`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Registro`.`car_itempedidos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `car_itempedidos` (
  `idItempedido` INT NOT NULL AUTO_INCREMENT ,
  `datasituacao` DATE NULL ,
  `idEmolumentos` INT NOT NULL ,
  `numeropaginas` INT NOT NULL ,
  `numerovias` VARCHAR(45) NULL ,
  `quantidadepessoasnotificadas` INT NULL ,
  `valordocumento` DOUBLE NULL ,
  `outrasdespesas` DOUBLE NULL ,
  `notificante_fkpessoas` INT NULL ,
  `notificado_fkpessoas` INT NULL ,
  `motivo` BLOB NULL ,
  `idPedido` INT NOT NULL ,
  `idSituacoes` INT NOT NULL ,
  `idTipodocumentos` INT NOT NULL ,
  `idPessoasnotificado` INT NOT NULL ,
  `idPessoasnotificante` INT NOT NULL ,
  PRIMARY KEY (`idItempedido`) ,
  INDEX `fk_itempedido_pedido` (`idPedido` ASC) ,
  INDEX `fk_itempedidos_situacoes1` (`idSituacoes` ASC) ,
  INDEX `fk_car_itempedidos_car_tipodocumentos1` (`idTipodocumentos` ASC) ,
  INDEX `fk_car_itempedidos_car_emolumentos1` (`idEmolumentos` ASC) ,
  INDEX `fk_car_itempedidos_car_pessoas1` (`idPessoasnotificado` ASC) ,
  INDEX `fk_car_itempedidos_car_pessoas2` (`idPessoasnotificante` ASC) ,
    FOREIGN KEY (`idPedido`) REFERENCES `car_pedidos` (`idPedido`),
    FOREIGN KEY (`idSituacoes`) REFERENCES `car_situacoes` (`idSituacoes`),
    FOREIGN KEY (`idTipodocumentos`) REFERENCES `car_tipodocumentos` (`idTipodocumentos`),
    FOREIGN KEY (`idEmolumentos`) REFERENCES `car_emolumentos` (`idEmolumentos`),
    FOREIGN KEY (`idPessoasnotificado`) REFERENCES `car_pessoas` (`idPessoas`),
    FOREIGN KEY (`idPessoasnotificante`) REFERENCES `car_pessoas` (`idPessoas`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Registro`.`car_selos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `car_selos` (
  `idSelos` INT NOT NULL ,
  `tipo` VARCHAR(50) NULL ,
  `serie` VARCHAR(5) NULL ,
  `numeroinicial` INT NULL ,
  `numerofinal` INT NULL ,
  `notafiscal` INT NULL ,
  `data_nota` DATE NULL ,
  `data_inclusao` TIMESTAMP NULL ,
  PRIMARY KEY (`idSelos`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Registro`.`car_controleselos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `car_controleselos` (
  `idControleselos` INT NOT NULL ,
  `numero` INT NOT NULL ,
  `idSelos` INT NOT NULL ,
  PRIMARY KEY (`idControleselos`) ,
  INDEX `fk_car_controleselos_car_selos1` (`idSelos` ASC) ,
    FOREIGN KEY (`idSelos`) REFERENCES `car_selos` (`idSelos`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Registro`.`car_registro`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `car_registro` (
  `idRegistro` INT NOT NULL AUTO_INCREMENT,
  `idProtocolo` INT NOT NULL ,
  `idItempedido` INT NOT NULL ,
  `livro` VARCHAR(1) NOT NULL ,
  `observacao` BLOB NULL ,
  `idSituacoes` INT NOT NULL ,
  `data` DATE NULL ,
  `idControleselos` INT NOT NULL ,
  PRIMARY KEY (`idRegistro`, `idSituacoes`) ,
  INDEX `fk_registro_car_itempedidos1` (`idItempedido` ASC) ,
  INDEX `fk_car_registro_car_situacoes1` (`idSituacoes` ASC) ,
  INDEX `fk_car_registro_car_controleselos1` (`idControleselos` ASC) ,
  INDEX `fk_car_registro_car_protocolo1` (`idProtocolo` ASC) ,
    FOREIGN KEY (`idItempedido`) REFERENCES `car_itempedidos` (`idItempedido`),
    FOREIGN KEY (`idSituacoes`) REFERENCES `car_situacoes` (`idSituacoes`),
    FOREIGN KEY (`idControleselos`) REFERENCES `car_controleselos` (`idControleselos`),
	FOREIGN KEY (`idProtocolo`) REFERENCES `car_protocolo` (`idProtocolo`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `Registro`.`car_protocolo`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `car_protocolo` (
  `idProtocolo` INT NOT NULL AUTO_INCREMENT ,
  `data` DATE NULL ,
  PRIMARY KEY (`idProtocolo`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `Registro`.`car_pessoascitadas`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `car_pessoascitadas` (
  `idPessoascitadas` INT NOT NULL AUTO_INCREMENT ,
  `idPessoas` INT NOT NULL ,
  `idRegistro` INT NOT NULL ,
  `notificar` ENUM('1','2') NOT NULL ,
  PRIMARY KEY (`idPessoascitadas`) ,
  INDEX `fk_car_pessoascitadas_car_pessoas1` (`idPessoas` ASC) ,
  INDEX `fk_car_pessoascitadas_car_registro1` (`idRegistro` ASC) ,
    FOREIGN KEY (`idPessoas`) REFERENCES `car_pessoas` (`idPessoas`),
    FOREIGN KEY (`idRegistro`) REFERENCES `car_registro` (`idRegistro`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Registro`.`car_pedidocertidao`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `car_pedidocertidao` (
  `idPedidocertidao` INT NOT NULL AUTO_INCREMENT ,
  `datasituacao` DATE NULL ,
  `datapedido` TIMESTAMP NULL ,
  `idSituacoes` INT NOT NULL ,
  `idPessoas` INT NOT NULL ,
  PRIMARY KEY (`idPedidocertidao`) ,
  INDEX `fk_car_pedidocertidao_car_situacoes1` (`idSituacoes` ASC) ,
  INDEX `fk_car_pedidocertidao_car_pessoas1` (`idPessoas` ASC) ,
    FOREIGN KEY (`idSituacoes`) REFERENCES `car_situacoes` (`idSituacoes`),
    FOREIGN KEY (`idPessoas`) REFERENCES `car_pessoas` (`idPessoas`))
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

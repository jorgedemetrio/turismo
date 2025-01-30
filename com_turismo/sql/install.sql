CREATE TABLE IF NOT EXISTS `#__turismo_culinaria` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(250) NOT NULL,
    PRIMARY KEY (`id`)
);

-- Inserts para tipos de culinária
INSERT INTO `#__turismo_culinaria` (`nome`) VALUES
('Mediterrânea'),
('Oriental'),
('Chinesa'),
('Tailandesa'),
('Brasileira'),
('Italiana'),
('Mexicana'),
('Francesa'),
('Japonesa'),
('Indiana');

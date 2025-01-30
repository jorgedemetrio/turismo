-- Criação da tabela de locais
CREATE TABLE IF NOT EXISTS `#__turismo_locais` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(250) NOT NULL,
    `tipo_local_id` INT(11) NOT NULL,
    `endereco` VARCHAR(255) NOT NULL,
    `cep` VARCHAR(10) NOT NULL,
    `bairro` VARCHAR(100) NOT NULL,
    `numero` VARCHAR(10) NOT NULL,
    `estado_id` INT(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`tipo_local_id`) REFERENCES `#__turismo_tipo_local`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`estado_id`) REFERENCES `#__turismo_estados`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela de tipos de locais
CREATE TABLE IF NOT EXISTS `#__turismo_tipo_local` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(250) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela de cardápios
CREATE TABLE IF NOT EXISTS `#__turismo_cardapio` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `local_id` INT(11) NOT NULL,
    `descricao` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`local_id`) REFERENCES `#__turismo_locais`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela de fotos de cardápios
CREATE TABLE IF NOT EXISTS `#__turismo_fotos_cardapio` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `cardapio_id` INT(11) NOT NULL,
    `image_path` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`cardapio_id`) REFERENCES `#__turismo_cardapio`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela de log de acesso
CREATE TABLE IF NOT EXISTS `#__turismo_log_acesso` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `data_acesso` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `#__users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela de log de alteração de locais
CREATE TABLE IF NOT EXISTS `#__turismo_log_alteracao_local` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `local_id` INT(11) NOT NULL,
    `data_alteracao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`local_id`) REFERENCES `#__turismo_locais`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela de log de buscas
CREATE TABLE IF NOT EXISTS `#__turismo_log_buscas` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `busca` VARCHAR(255) NOT NULL,
    `data_busca` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `#__users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela associativa de locais com usuários
CREATE TABLE IF NOT EXISTS `#__turismo_local_user` (
    `local_id` INT(11) NOT NULL,
    `user_id` INT(11) NOT NULL,
    PRIMARY KEY (`local_id`, `user_id`),
    FOREIGN KEY (`local_id`) REFERENCES `#__turismo_locais`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `#__users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela de mensagens
CREATE TABLE IF NOT EXISTS `#__turismo_mensagens` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `local_id` INT(11) NOT NULL,
    `mensagem` TEXT NOT NULL,
    `data_mensagem` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`local_id`) REFERENCES `#__turismo_locais`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela de CEPs
CREATE TABLE IF NOT EXISTS `#__turismo_ceps` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `cep` VARCHAR(10) NOT NULL,
    `local_id` INT(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`local_id`) REFERENCES `#__turismo_locais`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela de palavras proibidas (badwords)
CREATE TABLE IF NOT EXISTS `#__turismo_badwords` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `palavra` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Inserir estados
INSERT INTO `#__turismo_estados` (`nome`) VALUES
('Acre'),
('Alagoas'),
('Amapá'),
('Amazonas'),
('Bahia'),
('Ceará'),
('Distrito Federal'),
('Espírito Santo'),
('Goiás'),
('Maranhão'),
('Mato Grosso'),
('Mato Grosso do Sul'),
('Minas Gerais'),
('Pará'),
('Paraíba'),
('Paraná'),
('Pernambuco'),
('Piauí'),
('Rio de Janeiro'),
('Rio Grande do Norte'),
('Rio Grande do Sul'),
('Rondônia'),
('Roraima'),
('Santa Catarina'),
('São Paulo'),
('Sergipe'),
('Tocantins');

-- Inserir tipos de estabelecimento
INSERT INTO `#__turismo_tipos_estabelecimento` (`nome`) VALUES
('Restaurante'),
('Hotel'),
('Pousada'),
('Bar'),
('Café'),
('Loja de Souvenirs'),
('Agência de Viagens');

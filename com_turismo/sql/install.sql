-- Criação da tabela de estados
CREATE TABLE IF NOT EXISTS `#__turismo_estados` (
    `uf` CHAR(2) NOT NULL,
    `nome` VARCHAR(250) NOT NULL,
    PRIMARY KEY (`uf`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela de cidades
CREATE TABLE IF NOT EXISTS `#__turismo_cidades` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `uf` CHAR(2) NOT NULL,
    `nome` VARCHAR(250) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`uf`) REFERENCES `#__turismo_estados`(`uf`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela de tipos de locais
CREATE TABLE IF NOT EXISTS `#__turismo_tipo_local` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(250) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela de locais
CREATE TABLE IF NOT EXISTS `#__turismo_locais` (
    `id` CHAR(36) NOT NULL,
    `nome` VARCHAR(250) NOT NULL,
    `alias` VARCHAR(250) NOT NULL,
    `reserva` TINYINT(1) NOT NULL,
    `cep` VARCHAR(9) NOT NULL,
    `endereco` VARCHAR(250) NOT NULL,
    `numero` VARCHAR(20) NOT NULL,
    `cidade_id` INT(11) NOT NULL,
    `bairro` VARCHAR(250) NOT NULL,
    `complemento` VARCHAR(250),
    `faixa_preco` DECIMAL(10,2),
    `horarios_funcionamento` TEXT,
    `email_responsavel` VARCHAR(250),
    `telefone_contato` VARCHAR(250),
    `descricao` TEXT,
    `id_user_criador` INT(11) NOT NULL,
    `id_user_alterador` INT(11),
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `data_alteracao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `ip_criador` VARCHAR(45) NOT NULL,
    `ip_alterador` VARCHAR(45),
    `ip_proxy_criador` VARCHAR(45),
    `ip_proxy_alterador` VARCHAR(45),
    `destaque` TINYINT(1) DEFAULT 0,
    `status` ENUM('ATIVO', 'REPROVADO', 'NOVO', 'REMOVIDO') NOT NULL DEFAULT 'NOVO',
    `cnpj` VARCHAR(14),
    `acessos` INT(11) DEFAULT 0,
    `media_avaliacoes` DECIMAL(3,2) DEFAULT 0,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`cidade_id`) REFERENCES `#__turismo_cidades`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela de cardápios
CREATE TABLE IF NOT EXISTS `#__turismo_cardapio` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `local_id` CHAR(36) NOT NULL,
    `descricao` TEXT NOT NULL,
    `destaque` TINYINT(1) DEFAULT 0,
    `status` ENUM('ATIVO', 'REPROVADO', 'NOVO', 'REMOVIDO') NOT NULL DEFAULT 'NOVO',
    `faixa_preco` DECIMAL(10,2),
    `exibir_preco` TINYINT(1) DEFAULT 1,
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
    `local_id` CHAR(36) NOT NULL,
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
    `local_id` CHAR(36) NOT NULL,
    `user_id` INT(11) NOT NULL,
    PRIMARY KEY (`local_id`, `user_id`),
    FOREIGN KEY (`local_id`) REFERENCES `#__turismo_locais`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `#__users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela de mensagens
CREATE TABLE IF NOT EXISTS `#__turismo_mensagens` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nome_remetente` VARCHAR(250) NOT NULL,
    `email` VARCHAR(250) NOT NULL,
    `telefone` VARCHAR(250),
    `mensagem` TEXT NOT NULL,
    `id_local` CHAR(36) NOT NULL,
    `data_envio` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `ip` VARCHAR(45) NOT NULL,
    `ip_proxy` VARCHAR(45),
    PRIMARY KEY (`id`),
    FOREIGN KEY (`id_local`) REFERENCES `#__turismo_locais`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela de CEPs
CREATE TABLE IF NOT EXISTS `#__turismo_ceps` (
    `cep` VARCHAR(9) NOT NULL,
    `id_cidade` INT(11) NOT NULL,
    `endereco` VARCHAR(255),
    `latitude` DECIMAL(10,8),
    `longitude` DECIMAL(11,8),
    PRIMARY KEY (`cep`),
    FOREIGN KEY (`id_cidade`) REFERENCES `#__turismo_cidades`(`id`) ON DELETE CASCADE
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

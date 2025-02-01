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
    `estabelecimento` TINYINT(1) DEFAULT 1,
    `catid` INT(11) NOT NULL,
    `state` TINYINT(3) NOT NULL DEFAULT 1,
    `ordering` INT(11) NOT NULL DEFAULT 0,
    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT(11) NOT NULL,
    `modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `modified_by` INT(11),
    `checked_out` INT(11),
    `checked_out_time` DATETIME,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`catid`) REFERENCES `#__categories`(`id`) ON DELETE CASCADE
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

-- Criação da tabela de quartos
CREATE TABLE IF NOT EXISTS `#__turismo_quartos` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `quantidade_pessoas` INT(11) NOT NULL,
    `valor` DECIMAL(10,2) NOT NULL,
    `quantidade_disponiveis` INT(11) NOT NULL,
    `tamanho` VARCHAR(50),
    `local_id` CHAR(36) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`local_id`) REFERENCES `#__turismo_locais`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela de recursos de quartos
CREATE TABLE IF NOT EXISTS `#__turismo_recursos_quarto` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(250) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela associativa de quartos e recursos
CREATE TABLE IF NOT EXISTS `#__turismo_quarto_recurso` (
    `quarto_id` INT(11) NOT NULL,
    `recurso_id` INT(11) NOT NULL,
    PRIMARY KEY (`quarto_id`, `recurso_id`),
    FOREIGN KEY (`quarto_id`) REFERENCES `#__turismo_quartos`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`recurso_id`) REFERENCES `#__turismo_recursos_quarto`(`id`) ON DELETE CASCADE
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

-- Inserir recursos de quartos
INSERT INTO `#__turismo_recursos_quarto` (`nome`) VALUES
('Banheira'),
('Ar condicionado'),
('Jacu'),
('Piscina'),
('Vista para praia'),
('Garagem'),
('Wifi Incluso'),
('Wifi'),
('Aceita animais'),
('Sacada'),
('Academia'),
('Cabelereiro'),
('Restaurante'),
('Cozinha privativa'),
('TV'),
('Frigobar'),
('Cofre'),
('Serviço de quarto'),
('Serviço de streaming'),
('Produtos de higiene pessoal gratuitos'),
('Roupa de cama'),
('Toalhas'),
('Mesa de trabalho'),
('Cowork'),
('Aquecimento'),
('Lareira'),
('Secador de cabelo');

-- Inserir estados
INSERT INTO `#__turismo_estados` (`uf`, `nome`) VALUES
('AC', 'Acre'),
('AL', 'Alagoas'),
('AP', 'Amapá'),
('AM', 'Amazonas'),
('BA', 'Bahia'),
('CE', 'Ceará'),
('DF', 'Distrito Federal'),
('ES', 'Espírito Santo'),
('GO', 'Goiás'),
('MA', 'Maranhão'),
('MT', 'Mato Grosso'),
('MS', 'Mato Grosso do Sul'),
('MG', 'Minas Gerais'),
('PA', 'Pará'),
('PB', 'Paraíba'),
('PR', 'Paraná'),
('PE', 'Pernambuco'),
('PI', 'Piauí'),
('RJ', 'Rio de Janeiro'),
('RN', 'Rio Grande do Norte'),
('RS', 'Rio Grande do Sul'),
('RO', 'Rondônia'),
('RR', 'Roraima'),
('SC', 'Santa Catarina'),
('SP', 'São Paulo'),
('SE', 'Sergipe'),
('TO', 'Tocantins');



-- Inserção de cidades do Paraná (PR)
INSERT INTO `#__turismo_cidades` (`uf`, `nome`) VALUES
('PR', 'Curitiba'),
('PR', 'Londrina'),
('PR', 'Maringá'),
('PR', 'Ponta Grossa'),
('PR', 'Cascavel'),
('PR', 'São José dos Pinhais'),
('PR', 'Foz do Iguaçu'),
('PR', 'Colombo'),
('PR', 'Guarapuava'),
('PR', 'Paranaguá'),
('PR', 'Araucária'),
('PR', 'Toledo'),
('PR', 'Apucarana'),
('PR', 'Pinhais'),
('PR', 'Campo Largo'),
('PR', 'Arapongas'),
('PR', 'Almirante Tamandaré'),
('PR', 'Piraquara'),
('PR', 'Umuarama'),
('PR', 'Sarandi'),
('PR', 'Cambé'),
('PR', 'Paranavaí'),
('PR', 'Fazenda Rio Grande'),
('PR', 'Rolândia'),
('PR', 'Pato Branco'),
('PR', 'Castro'),
('PR', 'Ibiporã'),
('PR', 'Campo Mourão'),
('PR', 'Telêmaco Borba'),
('PR', 'Cianorte'),
('PR', 'Francisco Beltrão'),
('PR', 'Palmas'),
('PR', 'Irati'),
('PR', 'Assis Chateaubriand'),
('PR', 'Quatro Barras'),
('PR', 'Mandaguari'),
('PR', 'Medianeira'),
('PR', 'Matinhos'),
('PR', 'Guaratuba'),
('PR', 'Cornélio Procópio'),
('PR', 'São Mateus do Sul'),
('PR', 'Lapa'),
('PR', 'Marechal Cândido Rondon'),
('PR', 'Jaguariaíva'),
('PR', 'Santo Antônio da Platina'),
('PR', 'Realeza'),
('PR', 'Palmeira'),
('PR', 'Sertanópolis'),
('PR', 'Pérola'),
('PR', 'Ivaiporã'),
('PR', 'Antonina'),
('PR', 'Siqueira Campos'),
('PR', 'Wenceslau Braz'),
('PR', 'Rio Negro'),
('PR', 'Pontal do Paraná'),
('PR', 'Santa Helena'),
('PR', 'Jandaia do Sul'),
('PR', 'Mandirituba'),
('PR', 'Prudentópolis'),
('PR', 'Reserva'),
('PR', 'Tibagi'),
('PR', 'Bituruna'),
('PR', 'Piraí do Sul'),
('PR', 'São João do Ivaí'),
('PR', 'Nova Esperança'),
('PR', 'Ribeirão Claro'),
('PR', 'Goioerê'),
('PR', 'Ampére'),
('PR', 'Capanema'),
('PR', 'Iporã'),
('PR', 'Santo Antônio do Sudoeste'),
('PR', 'Terra Rica'),
('PR', 'Santa Terezinha de Itaipu'),
('PR', 'Astorga'),
('PR', 'Pérola d"Oeste'),
('PR', 'Clevelândia'),
('PR', 'Mallet'),
('PR', 'Pinhão'),
('PR', 'Laranjeiras do Sul'),
('PR', 'Guaraqueçaba'),
('PR', 'Rondon'),
('PR', 'São Miguel do Iguaçu'),
('PR', 'Joaquim Távora'),
('PR', 'Carlópolis'),
('PR', 'Sengés'),
('PR', 'Tomazina'),
('PR', 'Nova Londrina'),
('PR', 'Santa Mariana'),
('PR', 'Andirá'),
('PR', 'Cambará'),
('PR', 'Bandeirantes'),
('PR', 'Jacarezinho'),
('PR', 'Ibaiti'),
('PR', 'Wenceslau Braz'),
('PR', 'Siqueira Campos'),
('PR', 'Joaquim Távora'),
('PR', 'Carlópolis'),
('PR', 'Sengés'),
('PR', 'Tomazina'),
('PR', 'Nova Londrina'),
('PR', 'Santa Mariana'),
('PR', 'Andirá'),
('PR', 'Cambará'),
('PR', 'Bandeirantes'),
('PR', 'Jacarezinho'),
('PR', 'Ibaiti');

-- Criar categoria raiz para tipos de locais se não existir
INSERT IGNORE INTO `#__categories` (`extension`, `title`, `alias`, `description`, `published`, `access`, `params`, `metadesc`, `metakey`, `metadata`, `created_time`, `modified_time`, `hits`, `language`, `version`)
VALUES ('com_turismo', 'Tipos de Locais', 'tipos-de-locais', 'Categoria raiz para tipos de locais turísticos', 1, 1, '{"category_layout":"","image":""}', '', '', '{"author":"","robots":""}', NOW(), NOW(), 0, '*', 1);

-- Inserir tipos de estabelecimento vinculados às categorias
INSERT INTO `#__turismo_tipo_local` (`nome`, `estabelecimento`, `catid`, `created_by`, `state`) VALUES
('Restaurante', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Hotel', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Pousada', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Bar', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Café', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Loja de Souvenirs', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Agência de Viagens', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Praças', 0, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Parques', 0, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Ponto Turístico', 0, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Balada', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Museus', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Eventos', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Zoológico', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Parque de eletrônico', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Parque Aquatico', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Cinema', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Shopping', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Biblioteca', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1);

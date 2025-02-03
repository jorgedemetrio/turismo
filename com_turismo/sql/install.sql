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
    PRIMARY KEY (`id`),
    FOREIGN KEY (`catid`) REFERENCES `#__categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




-- Criação da tabela de tipos de locais
CREATE TABLE IF NOT EXISTS `#__turismo_tipo_cozinha` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(250) NOT NULL,
    `ip_criador` VARCHAR(45) NOT NULL,
    `ip_alterador` VARCHAR(45),
    `ip_proxy_criador` VARCHAR(45),
    `ip_proxy_alterador` VARCHAR(45),
    `ordering` INT(11) NOT NULL DEFAULT 0,
    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT(11) NOT NULL,
    `modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `modified_by` INT(11),
    FOREIGN KEY (`created_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`modified_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Criação da tabela de tipos de locais
CREATE TABLE IF NOT EXISTS `#__turismo_bom_para` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(250) NOT NULL,
    `ip_criador` VARCHAR(45) NOT NULL,
    `ip_alterador` VARCHAR(45),
    `ip_proxy_criador` VARCHAR(45),
    `ip_proxy_alterador` VARCHAR(45),
    `ordering` INT(11) NOT NULL DEFAULT 0,
    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT(11) NOT NULL,
    `modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `modified_by` INT(11),
    `publish`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `unpublish` DATETIME NULL,
    FOREIGN KEY (`created_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`modified_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Criação da tabela de locais
CREATE TABLE IF NOT EXISTS `#__turismo_local` (
    `id` CHAR(36) NOT NULL,
    `nome` VARCHAR(250) NOT NULL,
    `site` VARCHAR(250) NULL,
    `instagram` VARCHAR(250) NULL,
    `alias` VARCHAR(250) NOT NULL,
    `reserva` TINYINT(1) NOT NULL,
    `pet_fredly` TINYINT(1) NOT NULL,
    `aceita_criancas` TINYINT(1) NOT NULL,
    `cep` VARCHAR(9) NOT NULL,
    `endereco` VARCHAR(250) NOT NULL,
    `numero` VARCHAR(20) NOT NULL,
    `cidade_id` INT(11) NOT NULL,
    `bairro` VARCHAR(250) NOT NULL,
    `complemento` VARCHAR(250),
    `faixa_preco` DECIMAL(10,2),
    `horarios_funcionamento` TEXT, -- <-- Arrumar isso
    `descricao` TEXT,
    `ip_criador` VARCHAR(45) NOT NULL,
    `ip_alterador` VARCHAR(45),
    `ip_proxy_criador` VARCHAR(45),
    `ip_proxy_alterador` VARCHAR(45),
    `destaque` TINYINT(1) DEFAULT 0,
    `status` ENUM('ATIVO', 'REPROVADO', 'NOVO', 'REMOVIDO') NOT NULL DEFAULT 'NOVO',
    `cnpj` VARCHAR(14),
    `acessos` INT(11) DEFAULT 0,
    `media_avaliacoes` DECIMAL(3,2) DEFAULT 0,
    `ordering` INT(11) NOT NULL DEFAULT 0,
    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT(11) NOT NULL,
    `modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `modified_by` INT(11),
    FOREIGN KEY (`created_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`modified_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`cidade_id`) REFERENCES `#__turismo_cidades`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Criação da tabela de tipos de locais
CREATE TABLE IF NOT EXISTS `#__turismo_horario_funcionamento` (
    `id` INT(11) NOT NULL,
    `id_local` CHAR(36) NOT NULL,

    `abre_feriados` TINYINT(1) DEFAULT 0,
    `abre_domingo` VARCHAR(5) NOT NULL,
    `abre_segunda` VARCHAR(5) NOT NULL,
    `abre_terca` VARCHAR(5) NOT NULL,
    `abre_quarta` VARCHAR(5) NOT NULL,
    `abre_quinta` VARCHAR(5) NOT NULL,
    `abre_sexta` VARCHAR(5) NOT NULL,
    `abre_sabado` VARCHAR(5) NOT NULL,

    `fecha_domingo` VARCHAR(5) NOT NULL,
    `fecha_segunda` VARCHAR(5) NOT NULL,
    `fecha_terca` VARCHAR(5) NOT NULL,
    `fecha_quarta` VARCHAR(5) NOT NULL,
    `fecha_quinta` VARCHAR(5) NOT NULL,
    `fecha_sexta` VARCHAR(5) NOT NULL,
    `fecha_sabado` VARCHAR(5) NOT NULL,

    `ip_criador` VARCHAR(45) NOT NULL,
    `ip_alterador` VARCHAR(45),
    `ip_proxy_criador` VARCHAR(45),
    `ip_proxy_alterador` VARCHAR(45),
    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT(11) NOT NULL,
    `modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `modified_by` INT(11),
   
    FOREIGN KEY (`id_local`) REFERENCES `#__turismo_local`(`id`) ON DELETE CASCADE,

    FOREIGN KEY (`created_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`modified_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;







-- Criação da tabela de tipos de locais
CREATE TABLE IF NOT EXISTS `#__turismo_local_bom_para` (
    `id_bom_para` INT(11) NOT NULL,
    `id_local` CHAR(36) NOT NULL,
    
    `ip_criador` VARCHAR(45) NOT NULL,
    `ip_proxy_criador` VARCHAR(45),
    `ordering` INT(11) NOT NULL DEFAULT 0,
    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT(11) NOT NULL,

    FOREIGN KEY (`id_bom_para`) REFERENCES `#__turismo_bom_para`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_local`) REFERENCES `#__turismo_local`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`created_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    PRIMARY KEY (`id_bom_para`, `id_local`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- Criação da tabela de eventos
CREATE TABLE IF NOT EXISTS `#__turismo_evento` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `id_local` CHAR(36) ,
    `incio` DATETIME NOT NULL,
    `fim` DATETIME,
    `valor_meia` DECIMAL(10,2),
    `dados_evento` TEXT,
    `ip_criador` VARCHAR(45) NOT NULL,
    `ip_alterador` VARCHAR(45),
    `ip_proxy_criador` VARCHAR(45),
    `ip_proxy_alterador` VARCHAR(45),
    `ordering` INT(11) NOT NULL DEFAULT 0,
    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT(11) NOT NULL,
    `modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `modified_by` INT(11),
    PRIMARY KEY (`id`)
    FOREIGN KEY (`id_local`) REFERENCES `#__turismo_local`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`created_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`modified_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
) ENGINE=InnoDB DEFAULT CHARSET=utf8;





-- Criação da tabela de cozinha para bares, restaurantes e afins
CREATE TABLE IF NOT EXISTS `#__turismo_cozinha` (
    `id_tipo_cozinha` INT(11),
    `id_local` CHAR(36) ,
    `ip_criador` VARCHAR(45) NOT NULL,
    `ip_proxy_criador` VARCHAR(45),
    `ordering` INT(11) NOT NULL DEFAULT 0,
    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT(11) NOT NULL,
    PRIMARY KEY (`id_tipo_cozinha`, `id_local`)
    FOREIGN KEY (`id_local`) REFERENCES `#__turismo_local`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_tipo_cozinha`) REFERENCES `#__turismo_tipo_cozinha`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`created_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- Criação da tabela de emails
CREATE TABLE IF NOT EXISTS `#__turismo_email` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `id_local` CHAR(36) ,
    `email` VARCHAR(255) NOT NULL,
    `nome` VARCHAR(255) NOT NULL,
    `id_user` INT(11) NULL,
    `ip_criador` VARCHAR(45) NOT NULL,
    `ip_alterador` VARCHAR(45),
    `ip_proxy_criador` VARCHAR(45),
    `ip_proxy_alterador` VARCHAR(45),
    `ordering` INT(11) NOT NULL DEFAULT 0,
    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT(11) NOT NULL,
    `modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `modified_by` INT(11),
    PRIMARY KEY (`id`)
    FOREIGN KEY (`id_local`) REFERENCES `#__turismo_local`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`created_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_user`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`modified_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Criação da tabela de telefones
CREATE TABLE IF NOT EXISTS `#__turismo_telefone` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `id_local` CHAR(36) ,

    `ddd` VARCHAR(3),
    `telefone` VARCHAR(255) NOT NULL,
    `id_cidade`  INT(11),
    `id_user` INT(11) NULL,

    `ip_criador` VARCHAR(45) NOT NULL,
    `ip_alterador` VARCHAR(45),
    `ip_proxy_criador` VARCHAR(45),
    `ip_proxy_alterador` VARCHAR(45),
    `ordering` INT(11) NOT NULL DEFAULT 0,
    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT(11) NOT NULL,
    `modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `modified_by` INT(11),
    PRIMARY KEY (`id`)
    FOREIGN KEY (`id_cidade`) REFERENCES `#__turismo_cidades`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_local`) REFERENCES `#__turismo_local`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`created_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_user`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`modified_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Criação da tabela de quartos
CREATE TABLE IF NOT EXISTS `#__turismo_quartos` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `quantidade_pessoas` INT(11) NOT NULL,
    `valor` DECIMAL(10,2) NOT NULL,
    `quantidade_disponiveis` INT(11) NOT NULL,
    `tamanho` VARCHAR(50),
    `local_id` CHAR(36) NOT NULL,

    `state` TINYINT(3) NOT NULL DEFAULT 1,
    `ordering` INT(11) NOT NULL DEFAULT 0,
    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT(11) NOT NULL,
    `modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `modified_by` INT(11),
    FOREIGN KEY (`created_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`modified_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`local_id`) REFERENCES `#__turismo_local`(`id`) ON DELETE CASCADE
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

    `state` TINYINT(3) NOT NULL DEFAULT 1,
    `ordering` INT(11) NOT NULL DEFAULT 0,
    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT(11) NOT NULL,
    `modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `modified_by` INT(11),
    FOREIGN KEY (`created_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`modified_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
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

    `state` TINYINT(3) NOT NULL DEFAULT 1,
    `ordering` INT(11) NOT NULL DEFAULT 0,
    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT(11) NOT NULL,
    `modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `modified_by` INT(11),
    FOREIGN KEY (`created_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`modified_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`local_id`) REFERENCES `#__turismo_local`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela de fotos de cardápios
CREATE TABLE IF NOT EXISTS `#__turismo_fotos_cardapio` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `cardapio_id` INT(11) NOT NULL,
    `image_path` VARCHAR(255) NOT NULL,

    `state` TINYINT(3) NOT NULL DEFAULT 1,
    `ordering` INT(11) NOT NULL DEFAULT 0,
    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT(11) NOT NULL,
    `modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `modified_by` INT(11),
    FOREIGN KEY (`created_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`modified_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
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
    `descricao` TEXT NOT NULL,

    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT(11) NOT NULL,
    `modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `modified_by` INT(11),
    FOREIGN KEY (`created_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`modified_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`local_id`) REFERENCES `#__turismo_local`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela de log de buscas
CREATE TABLE IF NOT EXISTS `#__turismo_log_buscas` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `busca` VARCHAR(255) NOT NULL,


    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT(11) NOT NULL,
    `modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `modified_by` INT(11),
    FOREIGN KEY (`created_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`modified_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `#__users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela associativa de locais com usuários
CREATE TABLE IF NOT EXISTS `#__turismo_local_user` (
    `local_id` CHAR(36) NOT NULL,
    `user_id` INT(11) NOT NULL,


    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT(11) NOT NULL,
    `modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `modified_by` INT(11),
    FOREIGN KEY (`created_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`modified_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    PRIMARY KEY (`local_id`, `user_id`),
    FOREIGN KEY (`local_id`) REFERENCES `#__turismo_local`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `#__users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela de mensagens
CREATE TABLE IF NOT EXISTS `#__turismo_mensagens` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nome_remetente` VARCHAR(250) NOT NULL,
    `email` VARCHAR(250) NOT NULL,
    `telefone` VARCHAR(250),
    `mensagem` TEXT NOT NULL,

    `id_local` CHAR(36) NULL,  -- Enviado no formulário de dúvidas do local
    `id_encontro` INT(11) NULL, -- Enviado no formulário de encontros
    `id_destinatario` INT(11) NULL, -- Enviado diretamente para um usuário
    `id_mensagem_resposta` INT(11) NULL, -- Respondendo uma mensagem, neste caso tb deve ter o id_destinatario

    `data_envio` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `ip` VARCHAR(45) NOT NULL,
    `ip_proxy` VARCHAR(45),


    `state` TINYINT(3) NOT NULL DEFAULT 1,
    `ordering` INT(11) NOT NULL DEFAULT 0,
    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT(11) NULL,
    
    PRIMARY KEY (`id`),


    FOREIGN KEY (`created_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_destinatario`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`createid_mensagem_respostad_by`) REFERENCES `#__turismo_mensagens`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_encontro`) REFERENCES `#__turismo_encontros`(`id`) ON DELETE CASCADE
    FOREIGN KEY (`id_local`) REFERENCES `#__turismo_local`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criação da tabela de CEPs
CREATE TABLE IF NOT EXISTS `#__turismo_ceps` (
    `cep` VARCHAR(9) NOT NULL,
    `id_cidade` INT(11) NOT NULL,
    `endereco` VARCHAR(255) NOT NULL,
    `bairro` VARCHAR(255) NOT NULL,

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







-- -----------------------------------------------------
-- Table `#__turismo_encontros`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__turismo_encontros` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `id_local` BIGINT NOT NULL,

	`titulo` VARCHAR(255) NOT NULL,
	`alias` VARCHAR(255) NOT NULL,
    `descricao` TEXT  NOT NULL,
	`id_bom_para` INT(11) NOT NULL,

    
    `data_hora` DATETIME NOT NULL,
    `limite_participantes` INT DEFAULT 0,
	`publico` TINYINT(1) DEFAULT 1,
    `destaque` TINYINT(1) DEFAULT 0,

	
    `ip_criador` VARCHAR(45) NOT NULL,
    `ip_alterador` VARCHAR(45),
    `ip_proxy_criador` VARCHAR(45),
    `ip_proxy_alterador` VARCHAR(45),
    
    `status` ENUM('ATIVO', 'REPROVADO', 'NOVO', 'REMOVIDO') NOT NULL DEFAULT 'NOVO',
    `acessos` INT(11) DEFAULT 0,
    `media_avaliacoes` DECIMAL(3,2) DEFAULT 0,
    `ordering` INT(11) NOT NULL DEFAULT 0,
    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT(11) NOT NULL,
    `modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `modified_by` INT(11),

    PRIMARY KEY (`id`),
	FOREIGN KEY (`id_bom_para`) REFERENCES `#__turismo_bom_para`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_local`) REFERENCES `#__turismo_locais`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`created_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`modified_by`) REFERENCES `#__users`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB;



CREATE TABLE IF NOT EXISTS `#__turismo_encontros_confirmados` (
    `id_encontro` INT(11) NOT NULL,
    `id_user` INT(11) NOT NULL,
    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ip_criador` VARCHAR(45) NOT NULL,
    `ip_proxy_criador` VARCHAR(45),

    PRIMARY KEY (`id_user`,`id_encontro`),
    FOREIGN KEY (`id_user`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_encontro`) REFERENCES `#__turismo_encontros`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB;






SELECT max(id) INTO @IDUSUARIO FROM #__users limit 1;
SELECT SUBSTRING_INDEX(USER(), '@', -1) INTO @IPACESSO;


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
-- Com quartos
('Hotel', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Motel', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Sitio', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Pousada', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
-- Com cardapio
('Bar', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Restaurante', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Soverteria', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Tabacaria', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Distribuidora', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Panificadora', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Café', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),

('Loja de Souvenirs', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Agência de Viagens', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Praças', 0, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Parques', 0, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Ponto Turístico', 0, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Balada', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Museu', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Evento', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Zoológico', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Parque de eletrônico', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Parque Aquatico', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Cinema', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Shopping', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Mirante', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Praia', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1),
('Biblioteca', 1, (SELECT id FROM `#__categories` WHERE alias='tipos-de-locais' LIMIT 1), 1, 1);

INSERT INTO `#__turismo_bom_para` (`nome`, `ip_criador`, `created_by`, `ordering`) VALUES
('Instagrama', @IPACESSO, @IDUSUARIO, 1 ),
('Café da manhã diferente', @IPACESSO, @IDUSUARIO, 2 ),
('Roteiro Aleatório', @IPACESSO, @IDUSUARIO, 2 ),
('Dia dos pais', @IPACESSO, @IDUSUARIO, 0 ),
('Dia das mães', @IPACESSO, @IDUSUARIO, 0 ),
('Dia dos namorados', @IPACESSO, @IDUSUARIO, 0 ),
('Dia das crianças', @IPACESSO, @IDUSUARIO, 0 ),
('Halow Ween', @IPACESSO, @IDUSUARIO, 0 ),
('Carnaval / Folia', @IPACESSO, @IDUSUARIO, 0 ),
('Festa Junina', @IPACESSO, @IDUSUARIO, 0 ),
('Tomar um quentão', @IPACESSO, @IDUSUARIO, 0 ),
('Fazer amigos', @IPACESSO, @IDUSUARIO, 1 ),
('Fazer compras', @IPACESSO, @IDUSUARIO, 2 ),
('Despairecer', @IPACESSO, @IDUSUARIO, 2 ),
('Diversão', @IPACESSO, @IDUSUARIO, 2 ),
('Adrenalina/Radical', @IPACESSO, @IDUSUARIO, 2 ),
('Loucuras de amor', @IPACESSO, @IDUSUARIO, 2 ),
('Pedir em namoro', @IPACESSO, @IDUSUARIO, 2 ),
('Pedir em casamento', @IPACESSO, @IDUSUARIO, 2 ),
('Passeio com Filhos', @IPACESSO, @IDUSUARIO, 2 ),
('Noites quentes', @IPACESSO, @IDUSUARIO, 2 ),
('Refrescar', @IPACESSO, @IDUSUARIO, 2 ),
('Sair da rotina', @IPACESSO, @IDUSUARIO, 1 ),
('Passeio com Cães', @IPACESSO, @IDUSUARIO),
('Petiscar', @IPACESSO, @IDUSUARIO, 2 ),
('Pet-frendly', @IPACESSO, @IDUSUARIO, 2 ),
('Encontro', @IPACESSO, @IDUSUARIO, 1 ),
('Primeiro encontro', @IPACESSO, @IDUSUARIO, 1 ),
('Comemorar', @IPACESSO, @IDUSUARIO, 1 ),
('Relaxar', @IPACESSO, @IDUSUARIO, 2 ),
('Trabalhar', @IPACESSO, @IDUSUARIO, 2 ),
('Estudar', @IPACESSO, @IDUSUARIO, 2 ),
('Curtir a dois', @IPACESSO, @IDUSUARIO, 1 ),
('Conversar', @IPACESSO, @IDUSUARIO, 1 ),
('Impressionar', @IPACESSO, @IDUSUARIO, 1 ),
('Experimentar algo diferente', @IPACESSO, @IDUSUARIO, 1 ),
('Sair com amigos', @IPACESSO, @IDUSUARIO, 1 ),
('Festa de aniversário', @IPACESSO, @IDUSUARIO, 2 ),
('Reunião de negócios', @IPACESSO, @IDUSUARIO, 2 ),
('Jantar romântico', @IPACESSO, @IDUSUARIO, 2 ),
('Almoço em família', @IPACESSO, @IDUSUARIO, 2 ),
('Comemoração especial', @IPACESSO, @IDUSUARIO, 2 ),
('Descontrair', @IPACESSO, @IDUSUARIO, 2 ),
('Happy hour', @IPACESSO, @IDUSUARIO, 2 ),
('Despedida de solteiro(a)', @IPACESSO, @IDUSUARIO, 2 ),
('Evento corporativo', @IPACESSO, @IDUSUARIO, 2 ),
('Celebração de formatura', @IPACESSO, @IDUSUARIO, 2 ),
('Reunião de amigos', @IPACESSO, @IDUSUARIO, 1 ),
('Jantar casual', @IPACESSO, @IDUSUARIO, 2 ),
('Brunch de domingo', @IPACESSO, @IDUSUARIO, 2 ),
('Evento cultural', @IPACESSO, @IDUSUARIO, 2 );





INSERT INTO `#__turismo_tipo_cozinha` (`nome`, `ip_criador`, `created_by`, `ordering`) VALUES
('Rodizio de Pizza', @IPACESSO, @IDUSUARIO, 1 ),
('Rodizio de Carne', @IPACESSO, @IDUSUARIO, 1 ),
('Fondueria', @IPACESSO, @IDUSUARIO, 1 ),
('Café Colonial', @IPACESSO, @IDUSUARIO, 1 ),
('Costelaria', @IPACESSO, @IDUSUARIO, 1 ),
('Hamburgieria', @IPACESSO, @IDUSUARIO, 1 ),
('Hotdog', @IPACESSO, @IDUSUARIO, 1 ),
('Temaqueria', @IPACESSO, @IDUSUARIO, 1 ),
('Rodizio de Sushi', @IPACESSO, @IDUSUARIO, 1 ),
('Rodizio de Massas', @IPACESSO, @IDUSUARIO, 1 ),
('Rodizio de Frutos do Mar', @IPACESSO, @IDUSUARIO, 1 ),
('Rodizio de Petiscos', @IPACESSO, @IDUSUARIO, 1 ),
('Rodizio de Comida Japonesa', @IPACESSO, @IDUSUARIO, 1 ),
('Rodizio de Comida Chinesa', @IPACESSO, @IDUSUARIO, 1 ),
('Rodizio de Comida Italiana', @IPACESSO, @IDUSUARIO, 1 ),
('Rodizio de Comida Brasileira', @IPACESSO, @IDUSUARIO, 1 ),
('Rodizio de Comida Mexicana', @IPACESSO, @IDUSUARIO, 1 ),
('Italiana', @IPACESSO, @IDUSUARIO, 2 ),
('Japonesa', @IPACESSO, @IDUSUARIO, 2 ),
('Chinesa', @IPACESSO, @IDUSUARIO, 2 ),
('Brasileira', @IPACESSO, @IDUSUARIO, 2 ),
('Mexicana', @IPACESSO, @IDUSUARIO, 2 ),
('Francesa', @IPACESSO, @IDUSUARIO, 2 ),
('Indiana', @IPACESSO, @IDUSUARIO, 2 ),
('Tailandesa', @IPACESSO, @IDUSUARIO, 2 ),
('Espanhola', @IPACESSO, @IDUSUARIO, 2 ),
('Árabe', @IPACESSO, @IDUSUARIO, 2 ),
('Grega', @IPACESSO, @IDUSUARIO, 2 ),
('Portuguesa', @IPACESSO, @IDUSUARIO, 2 ),
('Coreana', @IPACESSO, @IDUSUARIO, 2 ),
('Vietnamita', @IPACESSO, @IDUSUARIO, 2 ),
('Turca', @IPACESSO, @IDUSUARIO, 2 ),
('Peruana', @IPACESSO, @IDUSUARIO, 2 ),
('Argentina', @IPACESSO, @IDUSUARIO, 2 ),
('Colombiana', @IPACESSO, @IDUSUARIO, 2 ),
('Cubana', @IPACESSO, @IDUSUARIO, 2 ),
('Venezuelana', @IPACESSO, @IDUSUARIO, 2 ),
('Libanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Marroquina', @IPACESSO, @IDUSUARIO, 2 ),
('Etíope', @IPACESSO, @IDUSUARIO, 2 ),
('Russa', @IPACESSO, @IDUSUARIO, 2 ),
('Alemã', @IPACESSO, @IDUSUARIO, 2 ),
('Austríaca', @IPACESSO, @IDUSUARIO, 2 ),
('Suíça', @IPACESSO, @IDUSUARIO, 2 ),
('Belga', @IPACESSO, @IDUSUARIO, 2 ),
('Holandesa', @IPACESSO, @IDUSUARIO, 2 ),
('Escandinava', @IPACESSO, @IDUSUARIO, 2 ),
('Polonesa', @IPACESSO, @IDUSUARIO, 2 ),
('Húngara', @IPACESSO, @IDUSUARIO, 2 ),
('Tcheca', @IPACESSO, @IDUSUARIO, 2 ),
('Eslovaca', @IPACESSO, @IDUSUARIO, 2 ),
('Ucraniana', @IPACESSO, @IDUSUARIO, 2 ),
('Búlgara', @IPACESSO, @IDUSUARIO, 2 ),
('Romena', @IPACESSO, @IDUSUARIO, 2 ),
('Croata', @IPACESSO, @IDUSUARIO, 2 ),
('Sérvia', @IPACESSO, @IDUSUARIO, 2 ),
('Eslovena', @IPACESSO, @IDUSUARIO, 2 ),
('Bósnia', @IPACESSO, @IDUSUARIO, 2 ),
('Macedônia', @IPACESSO, @IDUSUARIO, 2 ),
('Albanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Kosovar', @IPACESSO, @IDUSUARIO, 2 ),
('Montenegrina', @IPACESSO, @IDUSUARIO, 2 ),
('Georgiana', @IPACESSO, @IDUSUARIO, 2 ),
('Armênia', @IPACESSO, @IDUSUARIO, 2 ),
('Azeri', @IPACESSO, @IDUSUARIO, 2 ),
('Cazaque', @IPACESSO, @IDUSUARIO, 2 ),
('Uzbeque', @IPACESSO, @IDUSUARIO, 2 ),
('Quirguiz', @IPACESSO, @IDUSUARIO, 2 ),
('Tajique', @IPACESSO, @IDUSUARIO, 2 ),
('Turcomena', @IPACESSO, @IDUSUARIO, 2 ),
('Afegã', @IPACESSO, @IDUSUARIO, 2 ),
('Paquistanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Bangladesh', @IPACESSO, @IDUSUARIO, 2 ),
('Nepalesa', @IPACESSO, @IDUSUARIO, 2 ),
('Butanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Malaia', @IPACESSO, @IDUSUARIO, 2 ),
('Indonésia', @IPACESSO, @IDUSUARIO, 2 ),
('Filipina', @IPACESSO, @IDUSUARIO, 2 ),
('Singapurense', @IPACESSO, @IDUSUARIO, 2 ),
('Bruneiana', @IPACESSO, @IDUSUARIO, 2 ),
('Timorense', @IPACESSO, @IDUSUARIO, 2 ),
('Australiana', @IPACESSO, @IDUSUARIO, 2 ),
('Neozelandesa', @IPACESSO, @IDUSUARIO, 2 ),
('Sul-Africana', @IPACESSO, @IDUSUARIO, 2 ),
('Nigeriana', @IPACESSO, @IDUSUARIO, 2 ),
('Ganesa', @IPACESSO, @IDUSUARIO, 2 ),
('Senegalesa', @IPACESSO, @IDUSUARIO, 2 ),
('Angolana', @IPACESSO, @IDUSUARIO, 2 ),
('Moçambicana', @IPACESSO, @IDUSUARIO, 2 ),
('Cabo-Verdiana', @IPACESSO, @IDUSUARIO, 2 ),
('São-Tomense', @IPACESSO, @IDUSUARIO, 2 ),
('Guineense', @IPACESSO, @IDUSUARIO, 2 ),
('Equato-Guineense', @IPACESSO, @IDUSUARIO, 2 ),
('Zimbabuana', @IPACESSO, @IDUSUARIO, 2 ),
('Zambiana', @IPACESSO, @IDUSUARIO, 2 ),
('Malauiana', @IPACESSO, @IDUSUARIO, 2 ),
('Tanzaniana', @IPACESSO, @IDUSUARIO, 2 ),
('Ugandense', @IPACESSO, @IDUSUARIO, 2 ),
('Ruandesa', @IPACESSO, @IDUSUARIO, 2 ),
('Burundiana', @IPACESSO, @IDUSUARIO, 2 ),
('Congolesa', @IPACESSO, @IDUSUARIO, 2 ),
('Gabonesa', @IPACESSO, @IDUSUARIO, 2 ),
('Centro-Africana', @IPACESSO, @IDUSUARIO, 2 ),
('Chadiana', @IPACESSO, @IDUSUARIO, 2 ),
('Sudanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Sul-Sudanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Eritreia', @IPACESSO, @IDUSUARIO, 2 ),
('Djibutiana', @IPACESSO, @IDUSUARIO, 2 ),
('Somali', @IPACESSO, @IDUSUARIO, 2 ),
('Etíope', @IPACESSO, @IDUSUARIO, 2 ),
('Queniana', @IPACESSO, @IDUSUARIO, 2 ),
('Ugandense', @IPACESSO, @IDUSUARIO, 2 ),
('Ruandesa', @IPACESSO, @IDUSUARIO, 2 ),
('Burundiana', @IPACESSO, @IDUSUARIO, 2 ),
('Congolesa', @IPACESSO, @IDUSUARIO, 2 ),
('Gabonesa', @IPACESSO, @IDUSUARIO, 2 ),
('Centro-Africana', @IPACESSO, @IDUSUARIO, 2 ),
('Chadiana', @IPACESSO, @IDUSUARIO, 2 ),
('Sudanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Sul-Sudanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Eritreia', @IPACESSO, @IDUSUARIO, 2 ),
('Djibutiana', @IPACESSO, @IDUSUARIO, 2 ),
('Somali', @IPACESSO, @IDUSUARIO, 2 ),
('Etíope', @IPACESSO, @IDUSUARIO, 2 ),
('Queniana', @IPACESSO, @IDUSUARIO, 2 ),
('Ugandense', @IPACESSO, @IDUSUARIO, 2 ),
('Ruandesa', @IPACESSO, @IDUSUARIO, 2 ),
('Burundiana', @IPACESSO, @IDUSUARIO, 2 ),
('Congolesa', @IPACESSO, @IDUSUARIO, 2 ),
('Gabonesa', @IPACESSO, @IDUSUARIO, 2 ),
('Centro-Africana', @IPACESSO, @IDUSUARIO, 2 ),
('Chadiana', @IPACESSO, @IDUSUARIO, 2 ),
('Sudanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Sul-Sudanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Eritreia', @IPACESSO, @IDUSUARIO, 2 ),
('Djibutiana', @IPACESSO, @IDUSUARIO, 2 ),
('Somali', @IPACESSO, @IDUSUARIO, 2 ),
('Etíope', @IPACESSO, @IDUSUARIO, 2 ),
('Queniana', @IPACESSO, @IDUSUARIO, 2 ),
('Ugandense', @IPACESSO, @IDUSUARIO, 2 ),
('Ruandesa', @IPACESSO, @IDUSUARIO, 2 ),
('Burundiana', @IPACESSO, @IDUSUARIO, 2 ),
('Congolesa', @IPACESSO, @IDUSUARIO, 2 ),
('Gabonesa', @IPACESSO, @IDUSUARIO, 2 ),
('Centro-Africana', @IPACESSO, @IDUSUARIO, 2 ),
('Chadiana', @IPACESSO, @IDUSUARIO, 2 ),
('Sudanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Sul-Sudanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Eritreia', @IPACESSO, @IDUSUARIO, 2 ),
('Djibutiana', @IPACESSO, @IDUSUARIO, 2 ),
('Somali', @IPACESSO, @IDUSUARIO, 2 ),
('Etíope', @IPACESSO, @IDUSUARIO, 2 ),
('Queniana', @IPACESSO, @IDUSUARIO, 2 ),
('Ugandense', @IPACESSO, @IDUSUARIO, 2 ),
('Ruandesa', @IPACESSO, @IDUSUARIO, 2 ),
('Burundiana', @IPACESSO, @IDUSUARIO, 2 ),
('Congolesa', @IPACESSO, @IDUSUARIO, 2 ),
('Gabonesa', @IPACESSO, @IDUSUARIO, 2 ),
('Centro-Africana', @IPACESSO, @IDUSUARIO, 2 ),
('Chadiana', @IPACESSO, @IDUSUARIO, 2 ),
('Sudanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Sul-Sudanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Eritreia', @IPACESSO, @IDUSUARIO, 2 ),
('Djibutiana', @IPACESSO, @IDUSUARIO, 2 ),
('Somali', @IPACESSO, @IDUSUARIO, 2 ),
('Etíope', @IPACESSO, @IDUSUARIO, 2 ),
('Queniana', @IPACESSO, @IDUSUARIO, 2 ),
('Ugandense', @IPACESSO, @IDUSUARIO, 2 ),
('Ruandesa', @IPACESSO, @IDUSUARIO, 2 ),
('Burundiana', @IPACESSO, @IDUSUARIO, 2 ),
('Congolesa', @IPACESSO, @IDUSUARIO, 2 ),
('Gabonesa', @IPACESSO, @IDUSUARIO, 2 ),
('Centro-Africana', @IPACESSO, @IDUSUARIO, 2 ),
('Chadiana', @IPACESSO, @IDUSUARIO, 2 ),
('Sudanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Sul-Sudanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Eritreia', @IPACESSO, @IDUSUARIO, 2 ),
('Djibutiana', @IPACESSO, @IDUSUARIO, 2 ),
('Somali', @IPACESSO, @IDUSUARIO, 2 ),
('Etíope', @IPACESSO, @IDUSUARIO, 2 ),
('Queniana', @IPACESSO, @IDUSUARIO, 2 ),
('Ugandense', @IPACESSO, @IDUSUARIO, 2 ),
('Ruandesa', @IPACESSO, @IDUSUARIO, 2 ),
('Burundiana', @IPACESSO, @IDUSUARIO, 2 ),
('Congolesa', @IPACESSO, @IDUSUARIO, 2 ),
('Gabonesa', @IPACESSO, @IDUSUARIO, 2 ),
('Centro-Africana', @IPACESSO, @IDUSUARIO, 2 ),
('Chadiana', @IPACESSO, @IDUSUARIO, 2 ),
('Sudanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Sul-Sudanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Eritreia', @IPACESSO, @IDUSUARIO, 2 ),
('Djibutiana', @IPACESSO, @IDUSUARIO, 2 ),
('Somali', @IPACESSO, @IDUSUARIO, 2 ),
('Etíope', @IPACESSO, @IDUSUARIO, 2 ),
('Queniana', @IPACESSO, @IDUSUARIO, 2 ),
('Ugandense', @IPACESSO, @IDUSUARIO, 2 ),
('Ruandesa', @IPACESSO, @IDUSUARIO, 2 ),
('Burundiana', @IPACESSO, @IDUSUARIO, 2 ),
('Congolesa', @IPACESSO, @IDUSUARIO, 2 ),
('Gabonesa', @IPACESSO, @IDUSUARIO, 2 ),
('Centro-Africana', @IPACESSO, @IDUSUARIO, 2 ),
('Chadiana', @IPACESSO, @IDUSUARIO, 2 ),
('Sudanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Sul-Sudanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Eritreia', @IPACESSO, @IDUSUARIO, 2 ),
('Djibutiana', @IPACESSO, @IDUSUARIO, 2 ),
('Somali', @IPACESSO, @IDUSUARIO, 2 ),
('Etíope', @IPACESSO, @IDUSUARIO, 2 ),
('Queniana', @IPACESSO, @IDUSUARIO, 2 ),
('Ugandense', @IPACESSO, @IDUSUARIO, 2 ),
('Ruandesa', @IPACESSO, @IDUSUARIO, 2 ),
('Burundiana', @IPACESSO, @IDUSUARIO, 2 ),
('Congolesa', @IPACESSO, @IDUSUARIO, 2 ),
('Gabonesa', @IPACESSO, @IDUSUARIO, 2 ),
('Centro-Africana', @IPACESSO, @IDUSUARIO, 2 ),
('Chadiana', @IPACESSO, @IDUSUARIO, 2 ),
('Sudanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Sul-Sudanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Eritreia', @IPACESSO, @IDUSUARIO, 2 ),
('Djibutiana', @IPACESSO, @IDUSUARIO, 2 ),
('Somali', @IPACESSO, @IDUSUARIO, 2 ),
('Etíope', @IPACESSO, @IDUSUARIO, 2 ),
('Queniana', @IPACESSO, @IDUSUARIO, 2 ),
('Ugandense', @IPACESSO, @IDUSUARIO, 2 ),
('Ruandesa', @IPACESSO, @IDUSUARIO, 2 ),
('Burundiana', @IPACESSO, @IDUSUARIO, 2 ),
('Congolesa', @IPACESSO, @IDUSUARIO, 2 ),
('Gabonesa', @IPACESSO, @IDUSUARIO, 2 ),
('Centro-Africana', @IPACESSO, @IDUSUARIO, 2 ),
('Chadiana', @IPACESSO, @IDUSUARIO, 2 ),
('Sudanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Sul-Sudanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Eritreia', @IPACESSO, @IDUSUARIO, 2 ),
('Djibutiana', @IPACESSO, @IDUSUARIO, 2 ),
('Somali', @IPACESSO, @IDUSUARIO, 2 ),
('Etíope', @IPACESSO, @IDUSUARIO, 2 ),
('Queniana', @IPACESSO, @IDUSUARIO, 2 ),
('Ugandense', @IPACESSO, @IDUSUARIO, 2 ),
('Ruandesa', @IPACESSO, @IDUSUARIO, 2 ),
('Burundiana', @IPACESSO, @IDUSUARIO, 2 ),
('Congolesa', @IPACESSO, @IDUSUARIO, 2 ),
('Gabonesa', @IPACESSO, @IDUSUARIO, 2 ),
('Centro-Africana', @IPACESSO, @IDUSUARIO, 2 ),
('Chadiana', @IPACESSO, @IDUSUARIO, 2 ),
('Sudanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Sul-Sudanesa', @IPACESSO, @IDUSUARIO, 2 ),
('Eritreia', @IPACESSO, @IDUSUARIO, 2 ),
('Djibutiana', @IPACESSO, @IDUSUARIO, 2 ),
('Somali', @IPACESSO, @IDUSUARIO, 2 ),
('Etíope', @IPACESSO, @IDUSUARIO, 2 ),
('Queniana', @IPACESSO, @IDUSUARIO, 2 ),
('Ugandense', @IPACESSO, @IDUSUARIO, 2 ),
('Ruandesa', '127.0.0.1', 1);
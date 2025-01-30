-- Arquivo de atualização inicial para a versão 1.0.0
-- Este arquivo é necessário para o controle de versão do banco de dados
-- Como esta é a versão inicial, não há alterações a serem feitas

-- Registra a versão no esquema
INSERT INTO `#__schemas` (`extension_id`, `version_id`)
SELECT `extension_id`, '1.0.0'
FROM `#__extensions`
WHERE `type` = 'component'
AND `element` = 'com_turismo';

-- Remoção da tabela associativa de quartos e recursos
DROP TABLE IF EXISTS `#__turismo_quarto_recurso`;

-- Remoção da tabela de recursos de quartos
DROP TABLE IF EXISTS `#__turismo_recursos_quarto`;

-- Remoção da tabela de quartos
DROP TABLE IF EXISTS `#__turismo_quartos`;

-- Remoção da tabela de locais
DROP TABLE IF EXISTS `#__turismo_local`;

-- Remoção da tabela de tipos de locais
DROP TABLE IF EXISTS `#__turismo_tipo_local`;

-- Remoção da tabela de cidades
DROP TABLE IF EXISTS `#__turismo_cidades`;

-- Remoção da tabela de estados
DROP TABLE IF EXISTS `#__turismo_estados`;

-- Remoção da tabela de cardápios
DROP TABLE IF EXISTS `#__turismo_cardapio`;

-- Remoção da tabela de fotos de cardápios
DROP TABLE IF EXISTS `#__turismo_fotos_cardapio`;

-- Remoção da tabela de log de acesso
DROP TABLE IF EXISTS `#__turismo_log_acesso`;

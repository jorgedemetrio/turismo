<?php
defined('_JEXEC') or die;

class TurismoSiteModelLocal extends JModelList
{
    public function getListQuery($data)
    {
        // Lógica para obter a consulta da lista de locais
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        
        // Definindo os campos a serem retornados
        $query->select($db->quoteName(array('l.id', 'l.nome', 'l.alias', 'l.destaque', 'l.media_avaliacoes', 'l.bairro', 'l.cep', 'l.endereco', 'l.numero', 'tl.nome AS tipo_local', 'tc.nome AS tipo_cozinha')))
              ->from($db->quoteName('#__turismo_locais', 'l'))
              ->leftJoin($db->quoteName('#__turismo_tipo_locais', 'tl') . ' ON l.id_tipo_local = tl.id')
              ->leftJoin($db->quoteName('#__turismo_culinaria', 'tc') . ' ON l.id_culinaria = tc.id');

        // Adicionando filtros se necessário
        if (!empty($data['filter_bairro'])) {
            $bairro = trim(strtoupper($data['filter_bairro']));
            $query->where($db->quoteName('l.bairro') . ' LIKE ' . $db->quote('%' . $bairro . '%'));
        }
        if (!empty($data['filter_cep'])) {
            $cep = trim($data['filter_cep']);
            $query->where($db->quoteName('l.cep') . ' = ' . $db->quote($cep));
        }
        if (!empty($data['filter_tipo'])) {
            $query->where($db->quoteName('l.id_tipo_local') . ' = ' . $db->quote($data['filter_tipo']));
        }

        // Ordenação padrão
        $query->order($db->quoteName('l.destaque') . ' DESC, ' . $db->quoteName('l.media_avaliacoes') . ' DESC, ' . $db->quoteName('l.data_criacao') . ' DESC');

        // Executar a consulta
        $db->setQuery($query);
        $items = $db->loadObjectList();

        // Se houver mais de um registro, registrar a consulta
        if (count($items) > 0) {
            $this->registrarConsulta($data);
        }

        return $query;
    }

    public function registrarConsulta($data)
    {
        // Lógica para registrar a consulta na tabela de logs
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        
        // Verificar se já existe a URL antes de registrar a consulta
        $url = JUri::getInstance()->toString();
        $queryCheck = $db->getQuery(true)
            ->select('COUNT(*)')
            ->from($db->quoteName('#__turismo_consultas'))
            ->where($db->quoteName('url') . ' = ' . $db->quote($url));

        $db->setQuery($queryCheck);
        $urlExists = $db->loadResult();

        if ($urlExists == 0) {
            // Inserir na tabela de logs
            $query->insert($db->quoteName('#__turismo_consultas'))
                  ->columns($db->quoteName(array('url', 'data')))
                  ->values($db->quote($url) . ', NOW()');

            $db->setQuery($query);
            $db->execute();
        }
    }
}

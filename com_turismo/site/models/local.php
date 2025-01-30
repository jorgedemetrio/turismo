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
        $query->select($db->quoteName(array('id', 'nome', 'alias', 'destaque', 'media_avaliacoes')))
              ->from($db->quoteName('#__turismo_locais'));

        // Adicionando filtros se necessário
        if (!empty($data['filter_bairro'])) {
            $query->where($db->quoteName('bairro') . ' LIKE ' . $db->quote('%' . $data['filter_bairro'] . '%'));
        }
        if (!empty($data['filter_cep'])) {
            $query->where($db->quoteName('cep') . ' = ' . $db->quote($data['filter_cep']));
        }
        if (!empty($data['filter_tipo'])) {
            $query->where($db->quoteName('id_tipo_local') . ' = ' . $db->quote($data['filter_tipo']));
        }

        // Ordenação e paginação
        $query->order($db->quoteName('destaque') . ' DESC, ' . $db->quoteName('media_avaliacoes') . ' DESC');

        return $query;
    }

    public function registrarConsulta($data)
    {
        // Lógica para registrar a consulta na tabela de logs
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        
        // Inserir na tabela de logs
        $query->insert($db->quoteName('#__turismo_consultas'))
              ->columns($db->quoteName(array('url', 'data')))
              ->values($db->quote(JUri::getInstance()->toString()) . ', NOW()');

        $db->setQuery($query);
        $db->execute();
    }
}

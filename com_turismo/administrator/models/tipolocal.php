<?php
defined('_JEXEC') or die;

class TurismoModelTipolocal extends JModelAdmin
{
    public function getTable($type = 'Tipolocal', $prefix = 'TurismoTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getItem($pk = null)
    {
        // Lógica para obter um tipo local
        $item = parent::getItem($pk);
        return $item;
    }

    public function getListQuery()
    {
        // Lógica para obter a consulta da lista de tipos de locais
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        
        // Definindo os campos a serem retornados
        $query->select($db->quoteName(array('id', 'nome', 'estabelecimento', 'catid', 'state')))
              ->from($db->quoteName('#__turismo_tipo_local'));

        // Ordenação padrão por nome
        $query->order($db->quoteName('nome') . ' ASC');

        return $query;
    }
}

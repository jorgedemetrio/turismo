<?php
defined('_JEXEC') or die;

class TurismoModelLocal extends JModelAdmin
{
    public function getTable($type = 'Local', $prefix = 'TurismoTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getItem($pk = null)
    {
        // Lógica para obter um local
        $item = parent::getItem($pk);
        return $item;
    }

    public function getListQuery()
    {
        // Lógica para obter a consulta da lista de locais
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        
        // Definindo os campos a serem retornados
        $query->select($db->quoteName(array('id', 'nome', 'cnpj', 'cep', 'bairro', 'data_criacao')))
              ->from($db->quoteName('#__turismo_locais'));

        // Adicionando filtros se necessário
        $app = JFactory::getApplication();
        $filter_name = $app->input->get('filter_name', '', 'STRING');
        $filter_cnpj = $app->input->get('filter_cnpj', '', 'STRING');
        $filter_cep = $app->input->get('filter_cep', '', 'STRING');
        $filter_bairro = $app->input->get('filter_bairro', '', 'STRING');

        if (!empty($filter_name)) {
            $query->where($db->quoteName('nome') . ' LIKE ' . $db->quote('%' . $filter_name . '%'));
        }
        if (!empty($filter_cnpj)) {
            $query->where($db->quoteName('cnpj') . ' = ' . $db->quote($filter_cnpj));
        }
        if (!empty($filter_cep)) {
            $query->where($db->quoteName('cep') . ' = ' . $db->quote($filter_cep));
        }
        if (!empty($filter_bairro)) {
            $query->where($db->quoteName('bairro') . ' LIKE ' . $db->quote('%' . $filter_bairro . '%'));
        }

        // Ordenação padrão por data de criação
        $query->order($db->quoteName('data_criacao') . ' DESC');

        return $query;
    }
    public function updateHighlight($id, $highlight)
    {
        // Obtém a tabela
        $table = $this->getTable();

        // Carrega o registro
        if (!$table->load($id)) {
            $this->setError(JText::_('Failed to load record'));
            return false;
        }

        // Altera o destaque
        $table->highlight = $highlight;

        // Salva o registro
        if (!$table->store()) {
            $this->setError($table->getError());
            return false;
        }

        return true;
    }

    public function updateStatus($id, $status)
    {
        // Obtém a tabela
        $table = $this->getTable();

        // Carrega o registro
        if (!$table->load($id)) {
            $this->setError(JText::_('Failed to load record'));
            return false;
        }

        // Altera o status
        $table->status = $status;

        // Salva o registro
        if (!$table->store()) {
            $this->setError($table->getError());
            return false;
        }

        return true;
    }

    // Implementar métodos adicionais conforme necessário
}

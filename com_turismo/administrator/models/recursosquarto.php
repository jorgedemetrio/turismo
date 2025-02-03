<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\AdminModel;

class TurismoModelRecursosQuarto extends AdminModel
{
    protected function getTable($type = 'RecursosQuarto', $prefix = 'TurismoTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getItem($pk = null)
    {
        return parent::getItem($pk);
    }

    public function getItems($order = 'id ASC')
    {
        $query = $this->_db->getQuery(true);
        $query->select('id, nome') // Ajustar os campos conforme a tabela
              ->from($this->getTable()->getTableName())
              ->order($this->_db->quoteName($order));
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    public function save($data)
    {
        // Validação mínima dos campos
        if (empty($data['nome'])) {
            $this->setError(Text::_('COM_TURISMO_RECURSOS_QUARTO_ERROR_VALIDATION'));
            return false;
        }

        $table = $this->getTable();
        $table->bind($data);
        return $table->check() && $table->store();
    }

    public function delete($pk)
    {
        return parent::delete($pk);
    }
}

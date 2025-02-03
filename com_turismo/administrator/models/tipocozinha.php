<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\AdminModel;

class TurismoModelTipoCozinha extends AdminModel
{
    protected function getTable($type = 'TipoCozinha', $prefix = 'TurismoTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getItem($pk = null)
    {
        return parent::getItem($pk);
    }

    public function getItems($order = 'created DESC')
    {
        $query = $this->_db->getQuery(true);
        $query->select('tc.id, tc.nome, u.name AS creator_name')
              ->from($this->getTable()->getTableName() . ' AS tc')
              ->join('LEFT', '#__users AS u ON u.id = tc.created_by')
              ->order($this->_db->quoteName($order));
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    public function save($data)
    {
        // Validação mínima dos campos
        if (empty($data['nome'])) {
            $this->setError(Text::_('COM_TURISMO_TIPO_COZINHA_ERROR_VALIDATION'));
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

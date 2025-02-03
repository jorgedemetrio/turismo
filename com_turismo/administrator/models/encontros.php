<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\AdminModel;

class TurismoModelEncontros extends AdminModel
{
    protected function getTable($type = 'Encontros', $prefix = 'TurismoTable', $config = array())
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
        $query->select('e.id, e.nome, e.titulo, e.created, u.name AS creator_name')
              ->from($this->getTable()->getTableName() . ' AS e')
              ->join('LEFT', '#__users AS u ON u.id = e.created_by')
              ->order($this->_db->quoteName($order));
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    public function getConfirmedParticipants($encontroId)
    {
        $query = $this->_db->getQuery(true);
        $query->select('u.name AS participant_name')
              ->from('#__turismo_encontros_confirmados AS ec')
              ->join('INNER', '#__users AS u ON u.id = ec.user_id')
              ->where('ec.encontro_id = ' . (int) $encontroId);
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    public function save($data)
    {
        // Validação mínima dos campos
        if (empty($data['nome']) || empty($data['titulo'])) {
            $this->setError(Text::_('COM_TURISMO_ENCONTROS_ERROR_VALIDATION'));
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

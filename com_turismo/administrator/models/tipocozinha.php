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

    public function getItems($data, $order = 'created DESC')
    {
        $query = $this->_db->getQuery(true);
        $query->select('tc.id, tc.nome, u.name AS creator_name, u2.name AS modifier_name, `ip_criador`, `ip_alterador`, `ip_proxy_criador`, `ip_proxy_alterador`')
              ->from($this->getTable()->getTableName() . ' AS tc')
              ->join('LEFT', '#__users AS u ON u.id = tc.created_by')
              ->join('LEFT', '#__users AS u ON u2.id = e.modified_by')
              ->order($this->_db->quoteName($order));


        if ( empty($data['nome']) && trim($data['nome']) != '') {
            $query->where('upper(' . $this->_quoteName('nome') . ') LIKE ' . strtoupper( trim($this->_db->quote($data['nome']) . '%')));
        }
        if ( empty($data['created']) && trim($data['created']) != '') {
            $query->where( $this->_quoteName('created')  . ' = ' .  $this->_db->quote($data['created']));
        }
        if ( empty($data['modified']) && trim($data['modified']) != '') {
            $query->where( $this->_quoteName('created') . ' = ' . $this->_db->quote($data['created']));
        }
        if ( empty($data['creator_name']) && trim($data['creator_name']) != '') {
            $query->where('upper(' . $this->_quoteName('u.name') . ') LIKE ' . strtoupper(trim($this->_db->quote($data['creator_name']) . '%')));
        }
        if ( empty($data['modifier_name']) && trim($data['modifier_name']) != '') {
            $query->where('upper(' . $this->_quoteName('u2.name') . ') LIKE ' . strtoupper( trim($this->_db->quote($data['modifier_name']) . '%')));
        }



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

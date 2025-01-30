<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2023 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;
use Joomla\CMS\Factory;
use Joomla\Registry\Registry;
use Joomla\CMS\Language\Text;

/**
 * Tabela de Tipo de Local
 *
 * @since  1.0.0
 */
class TurismoTableTipoLocal extends Table
{
    /**
     * Constructor
     *
     * @param   DatabaseDriver  $db  Database connector object
     *
     * @since   1.0.0
     */
    public function __construct(DatabaseDriver $db)
    {
        parent::__construct('#__turismo_tipo_local', 'id', $db);

        // Definir campos que devem ser convertidos para JSON
        $this->_jsonEncode = array();

        // Definir campos que devem ser convertidos para datas
        $this->_columnAlias = array();
    }

    /**
     * Método para vincular os dados ao objeto
     *
     * @param   array|object  $array   Um array associativo ou objeto
     * @param   mixed        $ignore  Um array ou espaço separado por string de campos a serem ignorados
     *
     * @return  boolean  True em caso de sucesso, false em caso de falha
     *
     * @since   1.0.0
     */
    public function bind($array, $ignore = '')
    {
        if (isset($array['params']) && is_array($array['params']))
        {
            $registry = new Registry($array['params']);
            $array['params'] = (string) $registry;
        }

        return parent::bind($array, $ignore);
    }

    /**
     * Método executado antes de armazenar um registro
     *
     * @param   boolean  $updateNulls  True para atualizar campos mesmo se forem null
     *
     * @return  boolean  True em caso de sucesso, false em caso de falha
     *
     * @since   1.0.0
     */
    public function store($updateNulls = false)
    {
        $date = Factory::getDate();
        $user = Factory::getApplication()->getIdentity();

        // Definir campos de criação/modificação
        if (empty($this->id))
        {
            if (empty($this->created))
            {
                $this->created = $date->toSql();
            }

            if (empty($this->created_by))
            {
                $this->created_by = $user->id;
            }
        }
        else
        {
            $this->modified = $date->toSql();
            $this->modified_by = $user->id;
        }

        // Verificar se a categoria existe e pertence ao componente
        if (!empty($this->catid))
        {
            $query = $this->_db->getQuery(true)
                ->select('extension')
                ->from('#__categories')
                ->where('id = ' . (int) $this->catid);
            
            $this->_db->setQuery($query);
            $extension = $this->_db->loadResult();

            if ($extension !== 'com_turismo')
            {
                $this->setError(Text::_('COM_TURISMO_ERROR_INVALID_CATEGORY'));
                return false;
            }
        }

        return parent::store($updateNulls);
    }

    /**
     * Método para verificar se o registro pode ser excluído
     *
     * @param   integer  $pk  ID do registro
     *
     * @return  boolean  True se pode ser excluído, false caso contrário
     *
     * @since   1.0.0
     */
    public function delete($pk = null)
    {
        // Verificar se existem locais usando este tipo
        $query = $this->_db->getQuery(true)
            ->select('COUNT(*)')
            ->from('#__turismo_locais')
            ->where('tipo_id = ' . (int) $pk);
        
        $this->_db->setQuery($query);
        $count = $this->_db->loadResult();

        if ($count > 0)
        {
            $this->setError(Text::_('COM_TURISMO_ERROR_TIPO_LOCAL_IN_USE'));
            return false;
        }

        return parent::delete($pk);
    }

    /**
     * Método para verificar os dados antes de salvar
     *
     * @return  boolean  True se os dados são válidos, false caso contrário
     *
     * @since   1.0.0
     */
    public function check()
    {
        if (trim($this->nome) == '')
        {
            $this->setError(Text::_('COM_TURISMO_ERROR_TIPO_LOCAL_NAME_REQUIRED'));
            return false;
        }

        if (empty($this->catid))
        {
            $this->setError(Text::_('COM_TURISMO_ERROR_TIPO_LOCAL_CATEGORY_REQUIRED'));
            return false;
        }

        return true;
    }
}

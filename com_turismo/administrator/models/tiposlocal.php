<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2023 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Associations;
use Joomla\Database\ParameterType;

/**
 * Modelo de Lista de Tipos de Local
 *
 * @since  1.0.0
 */
class TurismoModelTiposLocal extends ListModel
{
    /**
     * Constructor.
     *
     * @param   array  $config  An optional associative array of configuration settings.
     *
     * @see     JController
     * @since   1.0.0
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'nome', 'a.nome',
                'estabelecimento', 'a.estabelecimento',
                'catid', 'a.catid', 'category_title',
                'state', 'a.state',
                'ordering', 'a.ordering',
                'created', 'a.created',
                'created_by', 'a.created_by',
                'modified', 'a.modified',
                'modified_by', 'a.modified_by'
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * @param   string  $ordering   An optional ordering field.
     * @param   string  $direction  An optional direction (asc|desc).
     *
     * @return  void
     *
     * @since   1.0.0
     */
    protected function populateState($ordering = 'a.nome', $direction = 'asc')
    {
        $app = Factory::getApplication();

        // Lista de estados
        $this->setState('filter.state', $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string'));

        // Filtro de categoria
        $this->setState('filter.category_id', $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id', '', 'cmd'));

        // Filtro de estabelecimento
        $this->setState('filter.estabelecimento', $this->getUserStateFromRequest($this->context . '.filter.estabelecimento', 'filter_estabelecimento', '', 'string'));

        // Filtro de busca
        $this->setState('filter.search', $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search', '', 'string'));

        // Configuração de limite por página
        $limit = $app->getUserStateFromRequest($this->context . '.list.limit', 'limit', $app->get('list_limit'), 'uint');
        $this->setState('list.limit', $limit);

        // Configuração de página atual
        $limitstart = $app->input->get('limitstart', 0, 'uint');
        $this->setState('list.start', $limitstart);

        // Configuração de ordenação
        parent::populateState($ordering, $direction);
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * @param   string  $id  A prefix for the store id.
     *
     * @return  string  A store id.
     *
     * @since   1.0.0
     */
    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.state');
        $id .= ':' . $this->getState('filter.category_id');
        $id .= ':' . $this->getState('filter.estabelecimento');

        return parent::getStoreId($id);
    }

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return  string  An SQL query
     *
     * @since   1.0.0
     */
    protected function getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Selecionar campos
        $query->select(
            $this->getState(
                'list.select',
                'a.id, a.nome, a.estabelecimento, a.catid, a.state, ' .
                'a.ordering, a.created, a.created_by, a.modified, a.modified_by, ' .
                'a.checked_out, a.checked_out_time'
            )
        );

        $query->from($db->quoteName('#__turismo_tipo_local', 'a'));

        // Join com categorias
        $query->select('c.title AS category_title')
            ->join('LEFT', '#__categories AS c ON c.id = a.catid');

        // Join com usuários (criador)
        $query->select('uc.name AS creator')
            ->join('LEFT', '#__users AS uc ON uc.id = a.created_by');

        // Join com usuários (modificador)
        $query->select('um.name AS modifier')
            ->join('LEFT', '#__users AS um ON um.id = a.modified_by');

        // Filtro de busca
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            if (stripos($search, 'id:') === 0)
            {
                $query->where('a.id = ' . (int) substr($search, 3));
            }
            else
            {
                $search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
                $query->where('(a.nome LIKE ' . $search . ')');
            }
        }

        // Filtro de estado
        $state = $this->getState('filter.state');
        if (is_numeric($state))
        {
            $query->where('a.state = ' . (int) $state);
        }
        elseif ($state === '')
        {
            $query->where('(a.state IN (0, 1))');
        }

        // Filtro de categoria
        $categoryId = $this->getState('filter.category_id');
        if (is_numeric($categoryId))
        {
            $query->where('a.catid = ' . (int) $categoryId);
        }

        // Filtro de estabelecimento
        $estabelecimento = $this->getState('filter.estabelecimento');
        if (is_numeric($estabelecimento))
        {
            $query->where('a.estabelecimento = ' . (int) $estabelecimento);
        }

        // Adicionar ordenação
        $orderCol = $this->state->get('list.ordering', 'a.nome');
        $orderDirn = $this->state->get('list.direction', 'ASC');

        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    /**
     * Method to get an array of data items.
     *
     * @return  mixed  An array of data items on success, false on failure.
     *
     * @since   1.0.0
     */
    public function getItems()
    {
        $items = parent::getItems();

        if ($items)
        {
            foreach ($items as $item)
            {
                $item->category_title = $item->category_title ?? 'COM_TURISMO_NO_CATEGORY';
            }
        }

        return $items;
    }
}

<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2023 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Helper\ContentHelper;

/**
 * View para listar tipos de local
 *
 * @since  1.0.0
 */
class TurismoViewTiposLocal extends HtmlView
{
    /**
     * Array contendo os itens a serem exibidos
     *
     * @var  array
     */
    protected $items;

    /**
     * O objeto de paginação
     *
     * @var  JPagination
     */
    protected $pagination;

    /**
     * O modelo de estado
     *
     * @var  JObject
     */
    protected $state;

    /**
     * Form com os filtros
     *
     * @var  array
     */
    public $filterForm;

    /**
     * Lista de filtros ativos
     *
     * @var  array
     */
    public $activeFilters;

    /**
     * Display the view
     *
     * @param   string  $tpl  Template name
     *
     * @return  void
     *
     * @since   1.0.0
     */
    public function display($tpl = null)
    {
        $this->items         = $this->get('Items');
        $this->pagination    = $this->get('Pagination');
        $this->state         = $this->get('State');
        $this->filterForm    = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

        // Verificar erros
        $errors = $this->get('Errors');
        if (count($errors))
        {
            throw new Exception(implode("\n", $errors), 500);
        }

        // Adicionar a toolbar
        $this->addToolbar();

        // Exibir a view
        parent::display($tpl);
    }

    /**
     * Adiciona a Toolbar
     *
     * @return  void
     *
     * @since   1.0.0
     */
    protected function addToolbar()
    {
        $canDo = ContentHelper::getActions('com_turismo');
        $user  = Factory::getApplication()->getIdentity();

        // Título da página
        ToolbarHelper::title(Text::_('COM_TURISMO_TIPOSLOCAL_TITLE'), 'list-2');

        if ($canDo->get('core.create'))
        {
            ToolbarHelper::addNew('tipolocal.add');
        }

        if ($canDo->get('core.edit') || $canDo->get('core.edit.own'))
        {
            ToolbarHelper::editList('tipolocal.edit');
        }

        if ($canDo->get('core.edit.state'))
        {
            ToolbarHelper::publish('tiposlocal.publish', 'JTOOLBAR_PUBLISH', true);
            ToolbarHelper::unpublish('tiposlocal.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            ToolbarHelper::archiveList('tiposlocal.archive');
        }

        if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete'))
        {
            ToolbarHelper::deleteList('', 'tiposlocal.delete', 'JTOOLBAR_EMPTY_TRASH');
        }
        elseif ($canDo->get('core.edit.state'))
        {
            ToolbarHelper::trash('tiposlocal.trash');
        }

        if ($canDo->get('core.admin'))
        {
            ToolbarHelper::preferences('com_turismo');
        }

        // Adicionar botão de ajuda
        ToolbarHelper::help('tiposlocal', true);
    }

    /**
     * Retorna um array de campos que o usuário pode ordenar
     *
     * @return  array  Array contendo os campos para ordenação
     *
     * @since   1.0.0
     */
    protected function getSortFields()
    {
        return array(
            'a.state' => Text::_('JSTATUS'),
            'a.nome' => Text::_('COM_TURISMO_TIPOLOCAL_FIELD_NOME_LABEL'),
            'category_title' => Text::_('JCATEGORY'),
            'a.estabelecimento' => Text::_('COM_TURISMO_TIPOLOCAL_FIELD_ESTABELECIMENTO_LABEL'),
            'a.id' => Text::_('JGRID_HEADING_ID')
        );
    }
}

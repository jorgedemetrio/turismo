<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;

class TurismoViewTipoCozinha extends HtmlView
{
    protected $items;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        // Adicionando botões do JToolBar
        JToolBarHelper::title(Text::_('COM_TURISMO_TIPO_COZINHA_TITLE'), 'tipo_cozinha');
        JToolBarHelper::addNew('tipo_cozinha.add');
        JToolBarHelper::editList('tipo_cozinha.edit');
        JToolBarHelper::deleteList('', 'tipo_cozinha.delete');
        
        parent::display($tpl);
    }
}

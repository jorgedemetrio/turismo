<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;

class TurismoViewRecursosQuarto extends HtmlView
{
    protected $items;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        // Adicionando botões do JToolBar
        JToolBarHelper::title(Text::_('COM_TURISMO_RECURSOS_QUARTO_TITLE'), 'recursos_quarto');
        JToolBarHelper::addNew('recursos_quarto.add');
        JToolBarHelper::editList('recursos_quarto.edit');
        JToolBarHelper::deleteList('', 'recursos_quarto.delete');
        
        parent::display($tpl);
    }
}

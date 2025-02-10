<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;

class TurismoViewEncontros extends HtmlView
{
    protected $items;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        // Adicionando botões do JToolBar
        JToolBarHelper::title(Text::_('COM_TURISMO_ENCONTROS_TITLE'), 'encontros');
        JToolBarHelper::addNew('encontros.add');
        JToolBarHelper::editList('encontros.edit');
        JToolBarHelper::deleteList('', 'encontros.delete');
        
        parent::display($tpl);
    }
}

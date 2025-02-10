<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;

class TurismoViewCeps extends HtmlView
{
    protected $items;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        // Adicionando botões do JToolBar
        JToolBarHelper::title(Text::_('COM_TURISMO_CEPS_TITLE'), 'ceps');
        JToolBarHelper::addNew('ceps.add');
        JToolBarHelper::editList('ceps.edit');
        JToolBarHelper::deleteList('', 'ceps.delete');
        
        parent::display($tpl);
    }
}

<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;

class TurismoViewBomPara extends HtmlView
{
    protected $items;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        // Adicionando botões do JToolBar
        JToolBarHelper::title(Text::_('COM_TURISMO_BOM_PARA_TITLE'), 'bom_para');
        JToolBarHelper::addNew('bom_para.add');
        JToolBarHelper::editList('bom_para.edit');
        JToolBarHelper::deleteList('', 'bom_para.delete');
        
        parent::display($tpl);
    }
}

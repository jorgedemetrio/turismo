<?php
defined('_JEXEC') or die;

class TurismoViewLocals extends JViewLegacy
{
    protected $items;
    protected $pagination;
    protected $state;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');

        // Adiciona a barra de ferramentas
        $this->addToolBar();

        parent::display($tpl);
    }

    protected function addToolBar()
    {
        JToolBarHelper::title(JText::_('COM_TURISMO_MANAGER_LOCALS'), 'locals.png');
        JToolBarHelper::deleteList('', 'locals.delete', 'JTOOLBAR_DELETE');
        JToolBarHelper::editList('local.edit');
        JToolBarHelper::addNew('local.add');
    }
}
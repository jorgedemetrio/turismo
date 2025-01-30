<?php
defined('_JEXEC') or die;

class TurismoSiteViewLocal extends JViewLegacy
{
    public function display($tpl = null)
    {
        // Lógica para exibir a visão de locais
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        // Importar Bootstrap e jQuery
        echo JHtml::_('bootstrap.framework');
        echo JHtml::_('jquery.framework');
        HTMLHelper::_('bootstrap.tooltip');
        HTMLHelper::_('formbehavior.chosen');

        parent::display($tpl);
    }
}

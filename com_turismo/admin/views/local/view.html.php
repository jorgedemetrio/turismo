<?php
defined('_JEXEC') or die;

class TurismoViewLocal extends JViewLegacy
{
    public function display($tpl = null)
    {
        // Lógica para exibir a visão de locais
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        parent::display($tpl);
    }
}

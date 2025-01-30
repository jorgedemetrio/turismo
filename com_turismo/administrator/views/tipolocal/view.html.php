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
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Helper\ContentHelper;

/**
 * View para edição de tipo de local
 *
 * @since  1.0.0
 */
class TurismoViewTipoLocal extends HtmlView
{
    /**
     * O formulário
     *
     * @var  Form
     */
    protected $form;

    /**
     * O item sendo editado
     *
     * @var  object
     */
    protected $item;

    /**
     * O estado do modelo
     *
     * @var  object
     */
    protected $state;

    /**
     * Display the view
     *
     * @param   string  $tpl  The name of the template file to parse
     *
     * @return  void
     *
     * @since   1.0.0
     */
    public function display($tpl = null)
    {
        $this->form  = $this->get('Form');
        $this->item  = $this->get('Item');
        $this->state = $this->get('State');

        // Verificar erros
        $errors = $this->get('Errors');
        if (count($errors))
        {
            throw new Exception(implode("\n", $errors), 500);
        }

        $this->addToolbar();

        parent::display($tpl);
    }

    /**
     * Adiciona a toolbar
     *
     * @return  void
     *
     * @since   1.0.0
     */
    protected function addToolbar()
    {
        Factory::getApplication()->input->set('hidemainmenu', true);

        $user       = Factory::getApplication()->getIdentity();
        $userId     = $user->id;
        $isNew      = ($this->item->id == 0);
        $canDo      = ContentHelper::getActions('com_turismo');

        // Título da página
        ToolbarHelper::title(
            Text::_('COM_TURISMO_TIPOLOCAL_' . ($isNew ? 'ADD' : 'EDIT')),
            'edit tipolocal'
        );

        if ($canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId))
        {
            ToolbarHelper::apply('tipolocal.apply');
            ToolbarHelper::save('tipolocal.save');
        }

        if ($canDo->get('core.create'))
        {
            ToolbarHelper::save2new('tipolocal.save2new');
        }

        if (!$isNew && $canDo->get('core.create'))
        {
            ToolbarHelper::save2copy('tipolocal.save2copy');
        }

        if (empty($this->item->id))
        {
            ToolbarHelper::cancel('tipolocal.cancel');
        }
        else
        {
            ToolbarHelper::cancel('tipolocal.cancel', 'JTOOLBAR_CLOSE');
        }

        ToolbarHelper::divider();

        if ($canDo->get('core.admin'))
        {
            ToolbarHelper::preferences('com_turismo');
        }
    }
}

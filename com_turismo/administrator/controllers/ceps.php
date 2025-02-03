<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\FormController;

class TurismoControllerCeps extends FormController
{
    protected $text_prefix = 'COM_TURISMO_CEPS';

    public function __construct()
    {
        parent::__construct();
    }

    public function add()
    {
        $this->input = Factory::getApplication()->input;
        $data = $this->input->get('jform', array(), 'array');
        $model = $this->getModel('Ceps');

        if ($model->save($data)) {
            $this->setMessage(Text::_('COM_TURISMO_CEPS_SAVED'));
        } else {
            $this->setMessage(Text::_('COM_TURISMO_CEPS_ERROR_SAVE'), 'error');
        }

        $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=ceps', false));
    }

    public function edit($key = 'id', $urlVar = 'cid')
    {
        $this->input = Factory::getApplication()->input;
        $data = $this->input->get('jform', array(), 'array');
        $model = $this->getModel('Ceps');

        if ($model->save($data)) {
            $this->setMessage(Text::_('COM_TURISMO_CEPS_SAVED'));
        } else {
            $this->setMessage(Text::_('COM_TURISMO_CEPS_ERROR_SAVE'), 'error');
        }

        $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=ceps', false));
    }

    public function delete()
    {
        $this->input = Factory::getApplication()->input;
        $cid = $this->input->get('cid', array(), 'array');
        $model = $this->getModel('Ceps');

        if ($model->delete($cid)) {
            $this->setMessage(Text::_('COM_TURISMO_CEPS_DELETED'));
        } else {
            $this->setMessage(Text::_('COM_TURISMO_CEPS_ERROR_DELETE'), 'error');
        }

        $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=ceps', false));
    }

    public function display($cachable = false, $urlvars = array())
    {
        parent::display($cachable, $urlvars);
    }
}

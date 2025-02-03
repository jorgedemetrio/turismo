<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;

class TurismoControllerTipolocal extends FormController
{
    protected $text_prefix = 'COM_TURISMO_TIPLOCAL';

    public function getModel($name = 'Tipolocal', $prefix = 'TurismoModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function save($key = null, $urlVar = null)
    {
        $app = Factory::getApplication();
        $input = $app->input;

        // Obtém os dados do formulário
        $data = $input->get('jform', array(), 'array');

        // Salvar os dados
        $model = $this->getModel();
        if ($model->save($data)) {
            $app->enqueueMessage(Text::_('COM_TURISMO_TIPLOCAL_SALVO_COM_SUCESSO'), 'success');
            $this->setRedirect('index.php?option=com_turismo&view=tipolocal');
        } else {
            $app->enqueueMessage(Text::_('COM_TURISMO_ERRO_SALVAR_TIPLOCAL'), 'error');
            $this->setRedirect('index.php?option=com_turismo&view=tipolocal&layout=edit&id=' . $data['id']);
        }
    }

    public function delete($key = 'id')
    {
        $app = Factory::getApplication();
        $model = $this->getModel();

        // Obtém o ID do tipo local a ser removido
        $ids = $app->input->get('cid', array(), 'array');

        if (empty($ids)) {
            $app->enqueueMessage(Text::_('COM_TURISMO_ERRO_SELECIONE_TIPOS_LOCAL'), 'error');
            return false;
        }

        foreach($ids as $id) {
            // Remove os tipos locais
            if (!$model->delete($id)) {
                $app->enqueueMessage(Text::_('COM_TURISMO_ERRO_REMOVER_TIPLOCAL'), 'error');
                $this->setRedirect('index.php?option=com_turismo&view=tipolocal');
                return;
            }
        }
        $app->enqueueMessage(Text::_('COM_TURISMO_TIPLOCAL_REMOVIDO_COM_SUCESSO'), 'success');

        $this->setRedirect('index.php?option=com_turismo&view=tipolocal');
    }

    public function cancel($key = 'id')
    {
        $this->setRedirect('index.php?option=com_turismo&view=tipolocal');
    }
}

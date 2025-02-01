<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Helper\ContentHelper;

/**
 * Controller para manipulação de tipos de locais
 *
 * @since  1.0.0
 */
class TurismoControllerTiposLocal extends FormController
{
    protected $text_prefix = 'COM_TURISMO_TIPOSLOCAL';

    public function getModel($name = 'TiposLocal', $prefix = 'TurismoModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function save($key = null, $urlVar = null)
    {
        // Lógica para salvar um novo tipo de local
        $app = Factory::getApplication();
        $input = $app->input;

        // Obtém os dados do formulário
        $data = $input->get('jform', array(), 'array');

        // Validação de CNPJ e CEP
        if (empty($data['cnpj']) || !preg_match('/^\d{14}$/', $data['cnpj'])) {
            $app->enqueueMessage(Text::_('COM_TURISMO_ERRO_CNPJ_INVALIDO'), 'error');
            return false;
        }

        if (empty($data['cep']) || !preg_match('/^\d{5}-\d{3}$/', $data['cep'])) {
            $app->enqueueMessage(Text::_('COM_TURISMO_ERRO_CEP_INVALIDO'), 'error');
            return false;
        }

        // Salvar os dados
        $model = $this->getModel();
        if ($model->save($data)) {
            $app->enqueueMessage(Text::_('COM_TURISMO_TIPOLOCAL_SALVO_COM_SUCESSO'), 'success');
            $this->setRedirect('index.php?option=com_turismo&view=tipolocal');
        } else {
            $app->enqueueMessage(Text::_('COM_TURISMO_ERRO_SALVAR_TIPOLOCAL'), 'error');
            $this->setRedirect('index.php?option=com_turismo&view=tipolocal&layout=edit&id=' . $data['id']);
        }
    }

    public function delete($key = 'id')
    {
        // Lógica para remover um tipo de local
        $app = Factory::getApplication();
        $model = $this->getModel();

        // Obtém o ID do tipo de local a ser removido
        $ids = $app->input->get('cid', array(), 'array');

        if (empty($ids)) {
            $app->enqueueMessage(Text::_('COM_TURISMO_ERRO_SELECIONE_TIPOSLOCAL'), 'error');
            return false;
        }

        // Remove os tipos de locais
        if ($model->delete($ids)) {
            $app->enqueueMessage(Text::_('COM_TURISMO_TIPOSLOCAL_REMOVIDO_COM_SUCESSO'), 'success');
        } else {
            $app->enqueueMessage(Text::_('COM_TURISMO_ERRO_REMOVER_TIPOSLOCAL'), 'error');
        }

        $this->setRedirect('index.php?option=com_turismo&view=tiposlocal');
    }

    public function cancel($key = 'id')
    {
        // Lógica para cancelar a operação
        $this->setRedirect('index.php?option=com_turismo&view=tiposlocal');
    }
}

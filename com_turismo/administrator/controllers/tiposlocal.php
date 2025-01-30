<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2023 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\Utilities\ArrayHelper;

/**
 * Controller para lista de Tipos de Local
 *
 * @since  1.0.0
 */
class TurismoControllerTiposLocal extends AdminController
{
    /**
     * Proxy para getModel
     *
     * @param   string  $name    O nome do modelo
     * @param   string  $prefix  O prefixo do modelo
     * @param   array   $config  Array de configuração
     *
     * @return  object  O modelo
     *
     * @since   1.0.0
     */
    public function getModel($name = 'TipoLocal', $prefix = 'TurismoModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

    /**
     * Método para salvar a ordenação
     *
     * @return  boolean  Resultado da operação
     *
     * @since   1.0.0
     */
    public function saveorder()
    {
        // Verificar token
        Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

        $ids = $this->input->post->get('cid', array(), 'array');
        $order = $this->input->post->get('order', array(), 'array');

        // Sanitizar os arrays
        $ids = ArrayHelper::toInteger($ids);
        $order = ArrayHelper::toInteger($order);

        $model = $this->getModel();
        $return = $model->saveorder($ids, $order);

        if ($return)
        {
            echo "1";
        }

        // Fechar a aplicação
        Factory::getApplication()->close();
    }

    /**
     * Método para publicar/despublicar itens
     *
     * @return  void
     *
     * @since   1.0.0
     */
    public function publish()
    {
        // Verificar token
        Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

        // Obter itens selecionados
        $ids = $this->input->get('cid', array(), 'array');
        $values = array('publish' => 1, 'unpublish' => 0, 'archive' => 2, 'trash' => -2, 'report' => -3);
        $task = $this->getTask();
        $value = ArrayHelper::getValue($values, $task, 0, 'int');

        if (empty($ids))
        {
            $this->setMessage(Text::_('JERROR_NO_ITEMS_SELECTED'), 'warning');
        }
        else
        {
            // Obter o modelo e tentar mudar o estado
            $model = $this->getModel();
            $ids = ArrayHelper::toInteger($ids);

            // Publicar os itens
            try
            {
                $model->publish($ids, $value);
                $errors = $model->getErrors();
                $ntext = null;

                if ($value === 1)
                {
                    $ntext = 'COM_TURISMO_N_ITEMS_PUBLISHED';
                }
                elseif ($value === 0)
                {
                    $ntext = 'COM_TURISMO_N_ITEMS_UNPUBLISHED';
                }
                elseif ($value === 2)
                {
                    $ntext = 'COM_TURISMO_N_ITEMS_ARCHIVED';
                }

                if (count($errors) == 0)
                {
                    $this->setMessage(Text::plural($ntext, count($ids)));
                }
                else
                {
                    $this->setMessage(Text::plural($ntext . '_ERROR', count($ids), implode('<br />', $errors)), 'error');
                }
            }
            catch (\Exception $e)
            {
                $this->setMessage($e->getMessage(), 'error');
            }
        }

        $this->setRedirect(Route::_('index.php?option=com_turismo&view=tiposlocal', false));
    }

    /**
     * Método para excluir itens
     *
     * @return  void
     *
     * @since   1.0.0
     */
    public function delete()
    {
        // Verificar token
        Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

        // Obter itens selecionados
        $ids = $this->input->get('cid', array(), 'array');

        if (empty($ids))
        {
            $this->setMessage(Text::_('JERROR_NO_ITEMS_SELECTED'), 'warning');
        }
        else
        {
            // Obter o modelo e tentar deletar
            $model = $this->getModel();
            $ids = ArrayHelper::toInteger($ids);

            // Remover os itens
            try
            {
                $model->delete($ids);
                $errors = $model->getErrors();

                if (count($errors) == 0)
                {
                    $this->setMessage(Text::plural('COM_TURISMO_N_ITEMS_DELETED', count($ids)));
                }
                else
                {
                    $this->setMessage(Text::plural('COM_TURISMO_N_ITEMS_DELETED_ERROR', count($ids), implode('<br />', $errors)), 'error');
                }
            }
            catch (\Exception $e)
            {
                $this->setMessage($e->getMessage(), 'error');
            }
        }

        $this->setRedirect(Route::_('index.php?option=com_turismo&view=tiposlocal', false));
    }
}

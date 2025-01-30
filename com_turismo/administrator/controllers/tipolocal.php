<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2023 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

/**
 * Controller para Tipo de Local
 *
 * @since  1.0.0
 */
class TurismoControllerTipoLocal extends FormController
{
    /**
     * Método para verificar se o usuário pode adicionar um novo registro
     *
     * @param   array  $data  Os dados do formulário
     *
     * @return  boolean
     *
     * @since   1.0.0
     */
    protected function allowAdd($data = array())
    {
        $user = Factory::getApplication()->getIdentity();
        $categoryId = array_key_exists('catid', $data) ? $data['catid'] : $this->input->getInt('filter_category_id');
        $allow = null;

        if ($categoryId)
        {
            // Se a categoria foi definida, verifique a permissão nela
            $allow = $user->authorise('core.create', 'com_turismo.category.' . $categoryId);
        }

        if ($allow === null)
        {
            // No caso de não haver categoria definida, use a permissão padrão
            return parent::allowAdd($data);
        }

        return $allow;
    }

    /**
     * Método para verificar se o usuário pode editar um registro
     *
     * @param   array   $data  Os dados do formulário
     * @param   string  $key   A chave do registro
     *
     * @return  boolean
     *
     * @since   1.0.0
     */
    protected function allowEdit($data = array(), $key = 'id')
    {
        $recordId = (int) isset($data[$key]) ? $data[$key] : 0;
        $user = Factory::getApplication()->getIdentity();

        // Verificar permissão geral primeiro
        if ($user->authorise('core.edit', 'com_turismo'))
        {
            return true;
        }

        // Verificar permissão específica na categoria
        if ($recordId)
        {
            // Obter a categoria do registro
            $db = Factory::getDbo();
            $query = $db->getQuery(true)
                ->select($db->quoteName('catid'))
                ->from($db->quoteName('#__turismo_tipo_local'))
                ->where($db->quoteName('id') . ' = ' . (int) $recordId);
            $db->setQuery($query);
            $categoryId = $db->loadResult();

            if ($categoryId)
            {
                return $user->authorise('core.edit', 'com_turismo.category.' . $categoryId);
            }
        }

        // Por padrão, verificar a permissão de editar próprio
        return $user->authorise('core.edit.own', 'com_turismo');
    }

    /**
     * Método para salvar os dados do formulário
     *
     * @param   string  $key     A chave do registro
     * @param   string  $urlVar  A variável da URL
     *
     * @return  boolean
     *
     * @since   1.0.0
     */
    public function save($key = null, $urlVar = null)
    {
        // Verificar token
        Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

        $result = parent::save($key, $urlVar);

        // Se salvou com sucesso, redirecionar para a listagem
        if ($result)
        {
            $this->setRedirect(Route::_('index.php?option=com_turismo&view=tiposlocal', false));
        }

        return $result;
    }

    /**
     * Método para cancelar uma ação
     *
     * @param   string  $key  A chave do registro
     *
     * @return  boolean
     *
     * @since   1.0.0
     */
    public function cancel($key = null)
    {
        Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

        $result = parent::cancel($key);

        // Redirecionar para a listagem
        $this->setRedirect(Route::_('index.php?option=com_turismo&view=tiposlocal', false));

        return $result;
    }
}

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
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;

/**
 * Script file do componente Turismo
 *
 * @since  1.0.0
 */
class Com_TurismoInstallerScript
{
    /**
     * Versão mínima do PHP necessária para instalar o componente
     *
     * @var    string
     * @since  1.0.0
     */
    protected $minimumPhp = '7.4';

    /**
     * Versão mínima do Joomla necessária para instalar o componente
     *
     * @var    string
     * @since  1.0.0
     */
    protected $minimumJoomla = '4.0';

    /**
     * Método para executar antes da instalação
     *
     * @param   string            $type    Tipo de instalação
     * @param   InstallerAdapter  $parent  Objeto parent
     *
     * @return  boolean  True caso sucesso
     *
     * @since   1.0.0
     */
    public function preflight($type, $parent)
    {
        // Verificar versão mínima do PHP
        if (version_compare(PHP_VERSION, $this->minimumPhp, 'lt'))
        {
            $msg = Text::sprintf('COM_TURISMO_PHP_VERSION_ERROR', $this->minimumPhp);
            Log::add($msg, Log::WARNING, 'jerror');

            return false;
        }

        // Verificar versão mínima do Joomla
        if (version_compare(JVERSION, $this->minimumJoomla, 'lt'))
        {
            $msg = Text::sprintf('COM_TURISMO_JOOMLA_VERSION_ERROR', $this->minimumJoomla);
            Log::add($msg, Log::WARNING, 'jerror');

            return false;
        }

        return true;
    }

    /**
     * Método para executar após a instalação
     *
     * @param   InstallerAdapter  $parent  Objeto parent
     *
     * @return  boolean  True caso sucesso
     *
     * @since   1.0.0
     */
    public function install($parent)
    {
        $this->createImageFolder();
        
        Factory::getApplication()->enqueueMessage(Text::_('COM_TURISMO_INSTALACAO_SUCESSO'));

        return true;
    }

    /**
     * Método para executar após a atualização
     *
     * @param   InstallerAdapter  $parent  Objeto parent
     *
     * @return  boolean  True caso sucesso
     *
     * @since   1.0.0
     */
    public function update($parent)
    {
        Factory::getApplication()->enqueueMessage(Text::_('COM_TURISMO_ATUALIZACAO_SUCESSO'));

        return true;
    }

    /**
     * Método para executar após a desinstalação
     *
     * @param   InstallerAdapter  $parent  Objeto parent
     *
     * @return  boolean  True caso sucesso
     *
     * @since   1.0.0
     */
    public function uninstall($parent)
    {
        Factory::getApplication()->enqueueMessage(Text::_('COM_TURISMO_DESINSTALACAO_SUCESSO'));

        return true;
    }

    /**
     * Método para criar a pasta de imagens
     *
     * @return  void
     *
     * @since   1.0.0
     */
    private function createImageFolder()
    {
        $path = JPATH_ROOT . '/images/turismo';

        if (!file_exists($path))
        {
            mkdir($path, 0755, true);
        }

        // Criar arquivo index.html para proteção
        if (!file_exists($path . '/index.html'))
        {
            $content = '<html><body bgcolor="#FFFFFF"></body></html>';
            file_put_contents($path . '/index.html', $content);
        }
    }
}

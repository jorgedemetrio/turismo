<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2025 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace JorgeDemetrio\Component\Turismo\Site\Service\Router;

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Component\Router\RouterInterface;

/**
 * Interface da fábrica de roteamento para com_turismo
 *
 * @since  1.0.0
 */
interface RouterFactoryInterface
{
    /**
     * Cria uma instância do roteador
     *
     * @param   string  $name  O nome do roteador
     *
     * @return  RouterInterface
     *
     * @throws  \InvalidArgumentException
     *
     * @since   1.0.0
     */
    public function createRouter($name): RouterInterface;
}

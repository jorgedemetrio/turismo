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

use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Categories\CategoryFactoryInterface;
use Joomla\CMS\Component\Router\RouterInterface;
use Joomla\CMS\Menu\AbstractMenu;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Database\DatabaseInterface;

/**
 * Fábrica de roteamento para com_turismo
 *
 * @since  1.0.0
 */
class RouterFactory implements RouterFactoryInterface
{
    /**
     * A aplicação
     *
     * @var    CMSApplicationInterface
     * @since  1.0.0
     */
    private $application;

    /**
     * O menu
     *
     * @var    AbstractMenu
     * @since  1.0.0
     */
    private $menu;

    /**
     * A fábrica de categorias
     *
     * @var    CategoryFactoryInterface
     * @since  1.0.0
     */
    private $categoryFactory;

    /**
     * A fábrica MVC
     *
     * @var    MVCFactoryInterface
     * @since  1.0.0
     */
    private $mvcFactory;

    /**
     * A conexão com o banco de dados
     *
     * @var    DatabaseInterface
     * @since  1.0.0
     */
    private $db;

    /**
     * Construtor.
     *
     * @param   CMSApplicationInterface    $application      A aplicação
     * @param   AbstractMenu              $menu             O menu
     * @param   CategoryFactoryInterface  $categoryFactory  A fábrica de categorias
     * @param   MVCFactoryInterface       $mvcFactory       A fábrica MVC
     * @param   DatabaseInterface         $db               A conexão com o banco de dados
     *
     * @since   1.0.0
     */
    public function __construct(
        CMSApplicationInterface $application,
        AbstractMenu $menu,
        CategoryFactoryInterface $categoryFactory,
        MVCFactoryInterface $mvcFactory,
        DatabaseInterface $db
    ) {
        $this->application = $application;
        $this->menu = $menu;
        $this->categoryFactory = $categoryFactory;
        $this->mvcFactory = $mvcFactory;
        $this->db = $db;
    }

    /**
     * Cria uma instância do roteador
     *
     * @param   string  $name  O nome do roteador
     *
     * @return  RouterInterface
     *
     * @since   1.0.0
     */
    public function createRouter($name): RouterInterface
    {
        $className = '\\JorgeDemetrio\\Component\\Turismo\\Site\\Service\\Router\\' . ucfirst($name) . 'Router';

        if (!class_exists($className)) {
            throw new \InvalidArgumentException(
                sprintf('Class %s does not exist', $className),
                404
            );
        }

        return new $className($this->application, $this->menu, $this->categoryFactory, $this->mvcFactory, $this->db);
    }
}

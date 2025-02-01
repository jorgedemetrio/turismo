<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2025 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Categories\CategoryFactoryInterface;
use Joomla\CMS\Component\Router\RouterFactoryInterface;
use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\CategoryFactory;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\HTML\Registry;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use JorgeDemetrio\Component\Turismo\Administrator\Extension\TurismoComponent;
use JorgeDemetrio\Component\Turismo\Administrator\Service\HTML\AdministratorService;

/**
 * The Turismo service provider.
 *
 * @since  1.0.0
 */
return new class implements ServiceProviderInterface
{
    /**
     * Registers the service provider with a DI container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  void
     *
     * @since   1.0.0
     */
    public function register(Container $container)
    {
        $container->registerServiceProvider(new CategoryFactory('\\JorgeDemetrio\\Component\\Turismo'));
        $container->registerServiceProvider(new MVCFactory('\\JorgeDemetrio\\Component\\Turismo'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('\\JorgeDemetrio\\Component\\Turismo'));
        $container->registerServiceProvider(new RouterFactory('\\JorgeDemetrio\\Component\\Turismo'));

        $container->set(
            ComponentInterface::class,
            function (Container $container) {
                $component = new TurismoComponent($container->get(ComponentDispatcherFactoryInterface::class));

                $component->setRegistry($container->get(Registry::class));
                $component->setMVCFactory($container->get(MVCFactoryInterface::class));
                $component->setCategoryFactory($container->get(CategoryFactoryInterface::class));
                $component->setRouterFactory($container->get(RouterFactoryInterface::class));

                return $component;
            }
        );

        // Registra serviços HTML
        $container->set(
            AdministratorService::class,
            function (Container $container) {
                return new AdministratorService();
            }
        );
    }
};

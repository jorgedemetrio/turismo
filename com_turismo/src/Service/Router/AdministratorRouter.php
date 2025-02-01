<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2025 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace JorgeDemetrio\Component\Turismo\Administrator\Service\Router;

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Menu\AbstractMenu;

/**
 * Classe de roteamento do administrador para o componente Turismo
 *
 * @since  1.0.0
 */
class AdministratorRouter extends RouterView
{
    /**
     * Construtor do roteador do administrador
     *
     * @param   CMSApplicationInterface  $app   A aplicação
     * @param   AbstractMenu            $menu  O menu
     *
     * @since   1.0.0
     */
    public function __construct(CMSApplicationInterface $app, AbstractMenu $menu)
    {
        // Configuração da view de locais
        $locais = new RouterViewConfiguration('locais');
        $this->registerView($locais);

        // Configuração da view de local
        $local = new RouterViewConfiguration('local');
        $local->setKey('id');
        $this->registerView($local);

        // Configuração da view de tipos
        $tipos = new RouterViewConfiguration('tipos');
        $this->registerView($tipos);

        // Configuração da view de tipo
        $tipo = new RouterViewConfiguration('tipo');
        $tipo->setKey('id');
        $this->registerView($tipo);

        // Configuração da view de avaliações
        $avaliacoes = new RouterViewConfiguration('avaliacoes');
        $this->registerView($avaliacoes);

        // Configuração da view de avaliação
        $avaliacao = new RouterViewConfiguration('avaliacao');
        $avaliacao->setKey('id');
        $this->registerView($avaliacao);

        // Configuração da view de categorias
        $categorias = new RouterViewConfiguration('categories');
        $this->registerView($categorias);

        // Configuração da view de categoria
        $categoria = new RouterViewConfiguration('category');
        $categoria->setKey('id');
        $this->registerView($categoria);

        // Configuração da view de cardápios
        $cardapios = new RouterViewConfiguration('cardapios');
        $this->registerView($cardapios);

        // Configuração da view de cardápio
        $cardapio = new RouterViewConfiguration('cardapio');
        $cardapio->setKey('id');
        $this->registerView($cardapio);

        // Configuração da view de quartos
        $quartos = new RouterViewConfiguration('quartos');
        $this->registerView($quartos);

        // Configuração da view de quarto
        $quarto = new RouterViewConfiguration('quarto');
        $quarto->setKey('id');
        $this->registerView($quarto);

        parent::__construct($app, $menu);

        $this->attachRule(new MenuRules($this));
        $this->attachRule(new StandardRules($this));
        $this->attachRule(new NomenuRules($this));
    }

    /**
     * Método para processar o build da URL
     *
     * @param   array  &$query  A query da URL
     *
     * @return  array  Os segmentos da URL
     *
     * @since   1.0.0
     */
    public function build(&$query)
    {
        $segments = parent::build($query);

        // Adiciona o prefixo 'administrator' para URLs administrativas
        if (!isset($query['layout']) || $query['layout'] !== 'modal') {
            array_unshift($segments, 'administrator');
        }

        return $segments;
    }

    /**
     * Método para processar o parse da URL
     *
     * @param   array  $segments  Os segmentos da URL
     *
     * @return  array  A query da URL
     *
     * @since   1.0.0
     */
    public function parse(&$segments)
    {
        // Remove o prefixo 'administrator' para URLs administrativas
        if ($segments[0] === 'administrator') {
            array_shift($segments);
        }

        return parent::parse($segments);
    }
}

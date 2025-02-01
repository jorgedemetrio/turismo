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
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Menu\AbstractMenu;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Database\DatabaseInterface;

/**
 * Classe de roteamento para o componente Turismo
 *
 * @since  1.0.0
 */
class SiteRouter extends RouterView
{
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
     * Cache de alias de locais
     *
     * @var    array
     * @since  1.0.0
     */
    private $locaisCache = [];

    /**
     * Cache de alias de categorias
     *
     * @var    array
     * @since  1.0.0
     */
    private $categoriasCache = [];

    /**
     * Construtor do roteador de turismo
     *
     * @param   CMSApplicationInterface   $app              A aplicação
     * @param   AbstractMenu             $menu             O menu
     * @param   CategoryFactoryInterface  $categoryFactory  A fábrica de categorias
     * @param   MVCFactoryInterface      $mvcFactory       A fábrica MVC
     * @param   DatabaseInterface        $db               A conexão com o banco de dados
     *
     * @since   1.0.0
     */
    public function __construct(
        CMSApplicationInterface $app,
        AbstractMenu $menu,
        CategoryFactoryInterface $categoryFactory,
        MVCFactoryInterface $mvcFactory,
        DatabaseInterface $db
    ) {
        $this->categoryFactory = $categoryFactory;
        $this->mvcFactory = $mvcFactory;
        $this->db = $db;

        // Configuração da view de categorias
        $categories = new RouterViewConfiguration('categories');
        $categories->setKey('id');
        $this->registerView($categories);

        // Configuração da view de categoria
        $category = new RouterViewConfiguration('category');
        $category->setKey('id')->setParent($categories);
        $this->registerView($category);

        // Configuração da view de locais
        $locais = new RouterViewConfiguration('locais');
        $locais->setKey('id')->setParent($category, 'catid');
        $this->registerView($locais);

        // Configuração da view de local
        $local = new RouterViewConfiguration('local');
        $local->setKey('id')->setParent($locais);
        $this->registerView($local);

        // Configuração da view de avaliações
        $avaliacoes = new RouterViewConfiguration('avaliacoes');
        $avaliacoes->setKey('id')->setParent($local, 'local_id');
        $this->registerView($avaliacoes);

        parent::__construct($app, $menu);

        $this->attachRule(new MenuRules($this));
        $this->attachRule(new StandardRules($this));
        $this->attachRule(new NomenuRules($this));
    }

    /**
     * Método para obter o segmento de uma categoria
     *
     * @param   string  $id     O ID da categoria
     * @param   array   $query  A query da URL
     *
     * @return  array|string  O segmento da URL da categoria
     *
     * @since   1.0.0
     */
    public function getCategorySegment($id, $query)
    {
        if (!isset($this->categoriasCache[$id])) {
            $category = $this->categoryFactory->createCategory(['access' => true]);
            $category->load($id);
            $this->categoriasCache[$id] = $category;
        }

        $path = array_reverse($this->categoriasCache[$id]->getPath(), true);
        $path[] = $id;

        $result = [];

        foreach ($path as $catid) {
            if (!isset($this->categoriasCache[$catid])) {
                $this->categoriasCache[$catid] = $this->categoryFactory->createCategory(['access' => true]);
                $this->categoriasCache[$catid]->load($catid);
            }

            $result[] = $this->categoriasCache[$catid]->alias;
        }

        return $result;
    }

    /**
     * Método para obter o ID de uma categoria a partir do segmento
     *
     * @param   string  $segment  O segmento da URL
     * @param   array   $query    A query da URL
     *
     * @return  integer|false  O ID da categoria ou false
     *
     * @since   1.0.0
     */
    public function getCategoryId($segment, $query)
    {
        $query = $this->db->getQuery(true)
            ->select($this->db->quoteName('id'))
            ->from($this->db->quoteName('#__categories'))
            ->where($this->db->quoteName('alias') . ' = ' . $this->db->quote($segment))
            ->where($this->db->quoteName('extension') . ' = ' . $this->db->quote('com_turismo'));

        $this->db->setQuery($query);

        return (int) $this->db->loadResult();
    }

    /**
     * Método para obter o segmento de um local
     *
     * @param   string  $id     O ID do local
     * @param   array   $query  A query da URL
     *
     * @return  array|string  O segmento da URL do local
     *
     * @since   1.0.0
     */
    public function getLocalSegment($id, $query)
    {
        if (!isset($this->locaisCache[$id])) {
            $query = $this->db->getQuery(true)
                ->select($this->db->quoteName('alias'))
                ->from($this->db->quoteName('#__turismo_locais'))
                ->where($this->db->quoteName('id') . ' = ' . (int) $id);

            $this->db->setQuery($query);
            $this->locaisCache[$id] = $this->db->loadResult();
        }

        return [$id => $this->locaisCache[$id]];
    }

    /**
     * Método para obter o ID de um local a partir do segmento
     *
     * @param   string  $segment  O segmento da URL
     * @param   array   $query    A query da URL
     *
     * @return  integer|false  O ID do local ou false
     *
     * @since   1.0.0
     */
    public function getLocalId($segment, $query)
    {
        $query = $this->db->getQuery(true)
            ->select($this->db->quoteName('id'))
            ->from($this->db->quoteName('#__turismo_locais'))
            ->where($this->db->quoteName('alias') . ' = ' . $this->db->quote($segment));

        $this->db->setQuery($query);

        return (int) $this->db->loadResult();
    }

    /**
     * Método para obter o segmento de uma avaliação
     *
     * @param   string  $id     O ID da avaliação
     * @param   array   $query  A query da URL
     *
     * @return  array|string  O segmento da URL da avaliação
     *
     * @since   1.0.0
     */
    public function getAvaliacaoSegment($id, $query)
    {
        $query = $this->db->getQuery(true)
            ->select($this->db->quoteName('alias'))
            ->from($this->db->quoteName('#__turismo_avaliacoes'))
            ->where($this->db->quoteName('id') . ' = ' . (int) $id);

        $this->db->setQuery($query);
        $alias = $this->db->loadResult();

        return [$id => $alias];
    }

    /**
     * Método para obter o ID de uma avaliação a partir do segmento
     *
     * @param   string  $segment  O segmento da URL
     * @param   array   $query    A query da URL
     *
     * @return  integer|false  O ID da avaliação ou false
     *
     * @since   1.0.0
     */
    public function getAvaliacaoId($segment, $query)
    {
        $query = $this->db->getQuery(true)
            ->select($this->db->quoteName('id'))
            ->from($this->db->quoteName('#__turismo_avaliacoes'))
            ->where($this->db->quoteName('alias') . ' = ' . $this->db->quote($segment));

        $this->db->setQuery($query);

        return (int) $this->db->loadResult();
    }
}

<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2025 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace JorgeDemetrio\Component\Turismo\Administrator\Extension;

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Categories\CategoryServiceInterface;
use Joomla\CMS\Categories\CategoryServiceTrait;
use Joomla\CMS\Component\Router\RouterServiceInterface;
use Joomla\CMS\Component\Router\RouterServiceTrait;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use Joomla\CMS\Tag\TagServiceInterface;
use Joomla\CMS\Tag\TagServiceTrait;
use Psr\Container\ContainerInterface;
use JorgeDemetrio\Component\Turismo\Administrator\Service\HTML\AdministratorService;

/**
 * Component class for com_turismo
 *
 * @since  1.0.0
 */
class TurismoComponent extends MVCComponent implements 
    BootableExtensionInterface,
    CategoryServiceInterface,
    RouterServiceInterface,
    TagServiceInterface
{
    use CategoryServiceTrait;
    use HTMLRegistryAwareTrait;
    use RouterServiceTrait;
    use TagServiceTrait;

    /**
     * Booting the extension. This is the function to set up the environment of the extension like
     * registering new class loaders, etc.
     *
     * @param   ContainerInterface  $container  The container
     *
     * @return  void
     *
     * @since   1.0.0
     */
    public function boot(ContainerInterface $container)
    {
        // Registra o serviço HTML
        $this->getRegistry()->register('turismoadministrator', new AdministratorService);
    }

    /**
     * Returns the table for the count items functions for the given section.
     *
     * @param   string  $section  The section
     *
     * @return  string|null
     *
     * @since   1.0.0
     */
    protected function getTableNameForSection(string $section = null)
    {
        return ($section === 'category' ? 'categories' : 'turismo_locais');
    }

    /**
     * Returns the state column for the count items functions for the given section.
     *
     * @param   string  $section  The section
     *
     * @return  string
     *
     * @since   1.0.0
     */
    public function getStateColumnForSection(string $section = null): string
    {
        return 'published';
    }


    /**
     * Returns the table for the count items functions for the given section.
     *
     * @param   string  $section  The section
     *
     * @return  string|null
     *
     * @since   1.0.0
     */
    protected function getTaggableTableNameForSection(string $section = null)
    {
        return ($section === 'category' ? 'categories' : 'turismo_locais');
    }

    /**
     * Returns the type alias for the given section.
     *
     * @param   string  $section  The section
     *
     * @return  string
     *
     * @since   1.0.0
     */
    public function getTypeAlias(string $section = null): string
    {
        return 'com_turismo.' . ($section ?: 'local');
    }



    /**
     * Returns the table name for the category table.
     *
     * @param   string  $section  The section
     *
     * @return  string
     *
     * @since   1.0.0
     */
    public function getCategoryTableNameForSection(string $section = null): string
    {
        return '#__categories';
    }

    /**
     * Returns the table name for the tag map table.
     *
     * @param   string  $section  The section
     *
     * @return  string
     *
     * @since   1.0.0
     */
    public function getTagTableNameForSection(string $section = null): string
    {
        return '#__contentitem_tag_map';
    }

    /**
     * Returns the context for the category table.
     *
     * @param   string  $section  The section
     *
     * @return  string
     *
     * @since   1.0.0
     */
    public function getCategoryContextForSection(string $section = null): string
    {
        return 'com_turismo.' . ($section ?: 'local');
    }

    /**
     * Returns the URL for the item.
     *
     * @param   string  $id        The id of the item
     * @param   string  $language  The language code
     * @param   string  $layout   The layout to use
     *
     * @return  string
     *
     * @since   1.0.0
     */
    public function getItemUrl($id, $language = null, $layout = null): string
    {
        // Cria a URL base
        $url = 'index.php?option=com_turismo&view=local&id=' . $id;

        // Adiciona o idioma se fornecido
        if ($language) {
            $url .= '&lang=' . $language;
        }

        // Adiciona o layout se fornecido
        if ($layout) {
            $url .= '&layout=' . $layout;
        }

        return $url;
    }

    /**
     * Returns the form field for the content type.
     *
     * @param   string  $section  The section
     *
     * @return  string
     *
     * @since   1.0.0
     */
    public function getFormFieldForSection(string $section = null): string
    {
        return 'turismo';
    }

    /**
     * Returns the route for the item.
     *
     * @param   integer  $id        The id of the item
     * @param   string   $language  The language code
     * @param   string   $layout    The layout to use
     *
     * @return  string
     *
     * @since   1.0.0
     */
    public function getItemRoute($id, $language = null, $layout = null): string
    {
        $url = $this->getItemUrl($id, $language, $layout);

        return Route::_($url);
    }
}

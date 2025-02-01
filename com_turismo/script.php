<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2025 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;
use Joomla\Database\DatabaseInterface;
use Joomla\CMS\Version;

/**
 * Script file of Turismo Component
 *
 * @since  1.0.0
 */
class Com_TurismoInstallerScript
{
    /**
     * Minimum Joomla version to check
     *
     * @var    string
     * @since  1.0.0
     */
    private $minimumJoomlaVersion = '4.0';

    /**
     * Minimum PHP version to check
     *
     * @var    string
     * @since  1.0.0
     */
    private $minimumPHPVersion = '7.4';

    /**
     * Method to install the component
     *
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since   1.0.0
     */
    public function install($parent): bool
    {
        $this->createImageFolders();
        $this->addMenuItems();
        $this->enablePlugin();

        echo Text::_('COM_TURISMO_INSTALL_SUCCESS');

        return true;
    }

    /**
     * Method to uninstall the component
     *
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since   1.0.0
     */
    public function uninstall($parent): bool
    {
        $this->removeImageFolders();

        echo Text::_('COM_TURISMO_UNINSTALL_SUCCESS');

        return true;
    }

    /**
     * Method to update the component
     *
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since   1.0.0
     */
    public function update($parent): bool
    {
        $this->createImageFolders();
        $this->addMenuItems();
        $this->enablePlugin();

        echo Text::_('COM_TURISMO_UPDATE_SUCCESS');

        return true;
    }

    /**
     * Function called before extension installation/update/removal procedure commences
     *
     * @param   string            $type    The type of change (install, update or discover_install, not uninstall)
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since   1.0.0
     */
    public function preflight($type, $parent): bool
    {
        if ($type !== 'uninstall') {
            // Check for the minimum PHP version before continuing
            if (!empty($this->minimumPHPVersion) && version_compare(PHP_VERSION, $this->minimumPHPVersion, '<')) {
                Log::add(
                    Text::sprintf('COM_TURISMO_ERROR_INSTALL_PHP_VERSION', $this->minimumPHPVersion),
                    Log::WARNING,
                    'jerror'
                );

                return false;
            }

            // Check for the minimum Joomla version before continuing
            if (!empty($this->minimumJoomlaVersion) && version_compare(JVERSION, $this->minimumJoomlaVersion, '<')) {
                Log::add(
                    Text::sprintf('COM_TURISMO_ERROR_INSTALL_JOOMLA_VERSION', $this->minimumJoomlaVersion),
                    Log::WARNING,
                    'jerror'
                );

                return false;
            }
        }

        return true;
    }

    /**
     * Function called after extension installation/update/removal procedure commences
     *
     * @param   string            $type    The type of change (install, update or discover_install, not uninstall)
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since   1.0.0
     */
    public function postflight($type, $parent)
    {
        return true;
    }

    /**
     * Create necessary image folders
     *
     * @return  void
     *
     * @since   1.0.0
     */
    private function createImageFolders(): void
    {
        $folders = [
            JPATH_ROOT . '/images/turismo',
            JPATH_ROOT . '/images/turismo/locais',
            JPATH_ROOT . '/images/turismo/locais/thumbs',
            JPATH_ROOT . '/images/turismo/temp'
        ];

        foreach ($folders as $folder) {
            if (!Folder::exists($folder)) {
                try {
                    Folder::create($folder);
                    File::write($folder . '/index.html', '<!DOCTYPE html><title></title>');
                } catch (\Exception $e) {
                    Log::add(
                        Text::sprintf('COM_TURISMO_ERROR_CREATING_FOLDER', $folder),
                        Log::WARNING,
                        'jerror'
                    );
                }
            }
        }
    }

    /**
     * Remove image folders on uninstall
     *
     * @return  void
     *
     * @since   1.0.0
     */
    private function removeImageFolders(): void
    {
        $folders = [
            JPATH_ROOT . '/images/turismo/locais/thumbs',
            JPATH_ROOT . '/images/turismo/locais',
            JPATH_ROOT . '/images/turismo/temp',
            JPATH_ROOT . '/images/turismo'
        ];

        foreach ($folders as $folder) {
            if (Folder::exists($folder)) {
                try {
                    Folder::delete($folder);
                } catch (\Exception $e) {
                    // Log error but continue
                    Log::add(
                        Text::sprintf('COM_TURISMO_ERROR_REMOVING_FOLDER', $folder),
                        Log::WARNING,
                        'jerror'
                    );
                }
            }
        }
    }

    /**
     * Add menu items
     *
     * @return  void
     *
     * @since   1.0.0
     */
    private function addMenuItems(): void
    {
        $db = Factory::getContainer()->get(DatabaseInterface::class);

        // Add menu types
        $menuTypes = [
            [
                'menutype'    => 'turismo',
                'title'       => 'Turismo',
                'description' => 'Menu para o componente de Turismo'
            ]
        ];

        Table::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_menus/tables');

        foreach ($menuTypes as $menuType) {
            $table = Table::getInstance('MenuType');

            if (!$table->load(['menutype' => $menuType['menutype']])) {
                $table->save($menuType);
            }
        }

        // Add menu items
        $menuItems = [
            [
                'menutype'     => 'turismo',
                'title'        => 'Lista de Locais',
                'alias'        => 'lista-de-locais',
                'link'         => 'index.php?option=com_turismo&view=locais',
                'component_id' => $this->getExtensionId('com_turismo'),
                'published'    => 1,
                'parent_id'    => 1,
                'level'        => 1,
                'home'         => 0
            ]
        ];

        Table::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_menus/tables');

        foreach ($menuItems as $menuItem) {
            $table = Table::getInstance('Menu');

            if (!$table->load(['alias' => $menuItem['alias'], 'menutype' => $menuItem['menutype']])) {
                $table->setLocation(1, 'last-child');
                $table->save($menuItem);
            }
        }
    }

    /**
     * Enable the Turismo plugin
     *
     * @return  void
     *
     * @since   1.0.0
     */
    private function enablePlugin(): void
    {
        $db = Factory::getContainer()->get(DatabaseInterface::class);

        try {
            $query = $db->getQuery(true)
                ->update($db->quoteName('#__extensions'))
                ->set($db->quoteName('enabled') . ' = 1')
                ->where($db->quoteName('type') . ' = ' . $db->quote('plugin'))
                ->where($db->quoteName('element') . ' = ' . $db->quote('turismo'))
                ->where($db->quoteName('folder') . ' = ' . $db->quote('system'));

            $db->setQuery($query);
            $db->execute();
        } catch (\Exception $e) {
            Log::add(
                Text::sprintf('COM_TURISMO_ERROR_ENABLING_PLUGIN', $e->getMessage()),
                Log::WARNING,
                'jerror'
            );
        }
    }

    /**
     * Get extension ID
     *
     * @param   string  $element  Extension element
     *
     * @return  integer  Extension ID
     *
     * @since   1.0.0
     */
    private function getExtensionId(string $element): int
    {
        $db = Factory::getContainer()->get(DatabaseInterface::class);

        $query = $db->getQuery(true)
            ->select($db->quoteName('extension_id'))
            ->from($db->quoteName('#__extensions'))
            ->where($db->quoteName('element') . ' = ' . $db->quote($element))
            ->where($db->quoteName('type') . ' = ' . $db->quote('component'));

        $db->setQuery($query);

        return (int) $db->loadResult();
    }
}

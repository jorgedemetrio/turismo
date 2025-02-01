<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2025 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace JorgeDemetrio\Component\Turismo\Administrator\Service\HTML;

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Database\DatabaseDriver;

/**
 * HTML helper class for com_turismo
 *
 * @since  1.0.0
 */
class AdministratorService
{
    /**
     * @var    DatabaseDriver
     * @since  1.0.0
     */
    private $db;

    /**
     * Constructor
     *
     * @since   1.0.0
     */
    public function __construct()
    {
        $this->db = Factory::getContainer()->get('DatabaseDriver');
    }

    /**
     * Exibe o status de um item
     *
     * @param   integer  $value      O valor do status
     * @param   integer  $i          O índice da linha
     * @param   boolean  $canChange  Se o usuário pode mudar o status
     *
     * @return  string  O HTML do status
     *
     * @since   1.0.0
     */
    public function published($value, $i, $canChange = true)
    {
        $states = [
            1  => ['unpublish', 'JPUBLISHED', 'JLIB_HTML_UNPUBLISH_ITEM', 'JPUBLISHED', true, 'publish', 'publish'],
            0  => ['publish', 'JUNPUBLISHED', 'JLIB_HTML_PUBLISH_ITEM', 'JUNPUBLISHED', true, 'unpublish', 'unpublish'],
            2  => ['unpublish', 'JARCHIVED', 'JLIB_HTML_UNPUBLISH_ITEM', 'JARCHIVED', true, 'archive', 'archive'],
            -2 => ['publish', 'JTRASHED', 'JLIB_HTML_PUBLISH_ITEM', 'JTRASHED', true, 'trash', 'trash'],
        ];

        return HTMLHelper::_('jgrid.state', $states, $value, $i, 'locais.', $canChange, true);
    }

    /**
     * Exibe o status de destaque de um item
     *
     * @param   integer  $value      O valor do status
     * @param   integer  $i          O índice da linha
     * @param   boolean  $canChange  Se o usuário pode mudar o status
     *
     * @return  string  O HTML do status
     *
     * @since   1.0.0
     */
    public function featured($value, $i, $canChange = true)
    {
        if ($value == 1) {
            $icon = 'featured';
            $alt = Text::_('COM_TURISMO_FEATURED_ITEM');
        } else {
            $icon = 'unfeatured';
            $alt = Text::_('COM_TURISMO_UNFEATURED_ITEM');
        }

        $prefix = 'locais.';
        $task = $value ? 'unfeatured' : 'featured';
        $active_title = $value ? Text::_('COM_TURISMO_FEATURED_REMOVE_ITEM') : Text::_('COM_TURISMO_FEATURED_ADD_ITEM');
        $inactive_title = $value ? Text::_('COM_TURISMO_FEATURED_ITEM') : Text::_('COM_TURISMO_UNFEATURED_ITEM');

        $html = HTMLHelper::_('image', 'admin/' . $icon . '.png', $alt, null, true);

        if ($canChange) {
            $html = '<a href="#" onclick="return Joomla.listItemTask(\'cb' . $i . '\',\'' . $prefix . $task . '\')"'
                . ' title="' . ($value ? $active_title : $inactive_title) . '">'
                . $html . '</a>';
        }

        return $html;
    }

    /**
     * Exibe a classificação de um local
     *
     * @param   float  $rating  A classificação
     *
     * @return  string  O HTML da classificação
     *
     * @since   1.0.0
     */
    public function rating($rating)
    {
        $rating = (float) $rating;
        $html = '<div class="rating">';
        
        for ($i = 1; $i <= 5; $i++) {
            if ($rating >= $i) {
                $class = 'fas fa-star';
            } elseif ($rating > ($i - 1)) {
                $class = 'fas fa-star-half-alt';
            } else {
                $class = 'far fa-star';
            }
            
            $html .= '<i class="' . $class . '"></i>';
        }
        
        $html .= ' <span class="rating-value">(' . number_format($rating, 1) . ')</span>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Exibe o tipo de local
     *
     * @param   integer  $tipoId  O ID do tipo
     *
     * @return  string  O nome do tipo
     *
     * @since   1.0.0
     */
    public function tipo($tipoId)
    {
        static $tipos = [];

        if (!isset($tipos[$tipoId])) {
            $query = $this->db->getQuery(true)
                ->select($this->db->quoteName('titulo'))
                ->from($this->db->quoteName('#__turismo_tipos'))
                ->where($this->db->quoteName('id') . ' = ' . (int) $tipoId);

            $this->db->setQuery($query);
            $tipos[$tipoId] = $this->db->loadResult() ?: Text::_('COM_TURISMO_TIPO_NAO_DEFINIDO');
        }

        return $tipos[$tipoId];
    }

    /**
     * Exibe o número de avaliações de um local
     *
     * @param   integer  $localId  O ID do local
     *
     * @return  string  O número de avaliações formatado
     *
     * @since   1.0.0
     */
    public function avaliacoes($localId)
    {
        $query = $this->db->getQuery(true)
            ->select('COUNT(*)')
            ->from($this->db->quoteName('#__turismo_avaliacoes'))
            ->where($this->db->quoteName('local_id') . ' = ' . (int) $localId)
            ->where($this->db->quoteName('state') . ' = 1');

        $this->db->setQuery($query);
        $count = (int) $this->db->loadResult();

        return '<span class="badge bg-info">' . $count . '</span>';
    }

    /**
     * Exibe o status de moderação de uma avaliação
     *
     * @param   integer  $value      O valor do status
     * @param   integer  $i          O índice da linha
     * @param   boolean  $canChange  Se o usuário pode mudar o status
     *
     * @return  string  O HTML do status
     *
     * @since   1.0.0
     */
    public function moderacao($value, $i, $canChange = true)
    {
        $states = [
            1  => ['unmoderado', 'COM_TURISMO_MODERADO', 'COM_TURISMO_DESMODERAR_ITEM', 'COM_TURISMO_MODERADO', true, 'publish', 'publish'],
            0  => ['moderado', 'COM_TURISMO_NAO_MODERADO', 'COM_TURISMO_MODERAR_ITEM', 'COM_TURISMO_NAO_MODERADO', true, 'unpublish', 'unpublish'],
        ];

        return HTMLHelper::_('jgrid.state', $states, $value, $i, 'avaliacoes.', $canChange, true);
    }

    /**
     * Exibe o tamanho de um arquivo em formato legível
     *
     * @param   integer  $bytes     O tamanho em bytes
     * @param   integer  $decimals  O número de casas decimais
     *
     * @return  string  O tamanho formatado
     *
     * @since   1.0.0
     */
    public function formatBytes($bytes, $decimals = 2)
    {
        $size = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$size[$factor];
    }

    /**
     * Exibe a miniatura de uma imagem
     *
     * @param   string   $path     O caminho da imagem
     * @param   integer  $width    A largura da miniatura
     * @param   integer  $height   A altura da miniatura
     * @param   string   $alt      O texto alternativo
     * @param   array    $attribs  Atributos adicionais
     *
     * @return  string  O HTML da miniatura
     *
     * @since   1.0.0
     */
    public function thumbnail($path, $width = 100, $height = 100, $alt = '', $attribs = [])
    {
        $attribs['width'] = $width;
        $attribs['height'] = $height;
        $attribs['loading'] = 'lazy';

        if (empty($alt) && !empty($attribs['title'])) {
            $alt = $attribs['title'];
        }

        if (!file_exists(JPATH_ROOT . '/' . $path)) {
            $path = 'media/com_turismo/images/noimage.png';
        }

        return HTMLHelper::_('image', $path, $alt, $attribs);
    }
}

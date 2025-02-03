<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Pagination\Pagination;

$this->document->setTitle(Text::_('COM_TURISMO_BOM_PARA'));

?>
<h1><?php echo Text::_('COM_TURISMO_BOM_PARA'); ?></h1>

<form action="<?php echo JRoute::_('index.php?option=com_turismo&task=bom_para.display'); ?>" method="post" id="adminForm">
    <div class="filter-search">
        <label for="filter_search"><?php echo Text::_('COM_TURISMO_FILTER_SEARCH_LABEL'); ?></label>
        <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />
        <button type="submit"><?php echo Text::_('JSEARCH_FILTER_SUBMIT'); ?></button>
    </div>
</form>

<table class="table">
    <thead>
        <tr>
            <th><?php echo Text::_('COM_TURISMO_NOME'); ?></th>
            <th><?php echo Text::_('COM_TURISMO_ACTIONS'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->items as $item): ?>
        <tr>
            <td><?php echo $item->nome; ?></td>
            <td>
                <a href="<?php echo JRoute::_('index.php?option=com_turismo&task=bom_para.edit&id=' . $item->id); ?>">
                    <?php echo Text::_('COM_TURISMO_EDIT'); ?>
                </a>
                <a href="<?php echo JRoute::_('index.php?option=com_turismo&task=bom_para.delete&id=' . $item->id); ?>">
                    <?php echo Text::_('COM_TURISMO_DELETE'); ?>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="pagination">
    <?php
    $pagination = new Pagination($this->total, $this->state->get('limitstart'), $this->state->get('limit'));
    echo $pagination->getPagesLinks();
    ?>
</div>

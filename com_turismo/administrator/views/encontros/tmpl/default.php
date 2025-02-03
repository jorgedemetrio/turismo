<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Pagination\Pagination;

$this->document->setTitle(Text::_('COM_TURISMO_ENCONTROS'));

?>
<h1><?php echo Text::_('COM_TURISMO_ENCONTROS'); ?></h1>

<form action="<?php echo JRoute::_('index.php?option=com_turismo&task=encontros.display'); ?>" method="post" id="adminForm">
    <div class="filter-search">
        <label for="filter_created_by"><?php echo Text::_('COM_TURISMO_FILTER_CREATED_BY_LABEL'); ?></label>
        <input type="text" name="filter_created_by" id="filter_created_by" value="<?php echo $this->escape($this->state->get('filter.created_by')); ?>" />
        
        <label for="filter_modified_by"><?php echo Text::_('COM_TURISMO_FILTER_MODIFIED_BY_LABEL'); ?></label>
        <input type="text" name="filter_modified_by" id="filter_modified_by" value="<?php echo $this->escape($this->state->get('filter.modified_by')); ?>" />
        
        <label for="filter_created"><?php echo Text::_('COM_TURISMO_FILTER_CREATED_LABEL'); ?></label>
        <input type="date" name="filter_created" id="filter_created" value="<?php echo $this->escape($this->state->get('filter.created')); ?>" />
        
        <label for="filter_modified"><?php echo Text::_('COM_TURISMO_FILTER_MODIFIED_LABEL'); ?></label>
        <input type="date" name="filter_modified" id="filter_modified" value="<?php echo $this->escape($this->state->get('filter.modified')); ?>" />
        
        <label for="filter_search"><?php echo Text::_('COM_TURISMO_FILTER_SEARCH_LABEL'); ?></label>
        <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />
        <button type="submit"><?php echo Text::_('JSEARCH_FILTER_SUBMIT'); ?></button>
    </div>
</form>

<table class="table">
    <thead>
        <tr>
            <th><?php echo Text::_('COM_TURISMO_TITULO'); ?></th>
            <th><?php echo Text::_('COM_TURISMO_ACTIONS'); ?></th>
            <th><?php echo Text::_('COM_TURISMO_CREATED_BY'); ?></th>
            <th><?php echo Text::_('COM_TURISMO_MODIFIED_BY'); ?></th>
            <th><?php echo Text::_('COM_TURISMO_CREATED_DATE'); ?></th>
            <th><?php echo Text::_('COM_TURISMO_MODIFIED_DATE'); ?></th>
            
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->items as $item): ?>
        <tr>
            <td><?php echo $item->titulo; ?></td>
            <td>
                <a href="<?php echo JRoute::_('index.php?option=com_turismo&task=encontros.edit&id=' . $item->id); ?>">
                    <?php echo Text::_('COM_TURISMO_EDIT'); ?>
                </a>
                <a href="<?php echo JRoute::_('index.php?option=com_turismo&task=encontros.delete&id=' . $item->id); ?>">
                    <?php echo Text::_('COM_TURISMO_DELETE'); ?>
                </a>
            </td>
            <td><?php echo $item->created_by; ?></td>
            <td><?php echo $item->modified_by; ?></td>
            <td><?php echo $item->created; ?></td>
            <td><?php echo $item->modified; ?></td>
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

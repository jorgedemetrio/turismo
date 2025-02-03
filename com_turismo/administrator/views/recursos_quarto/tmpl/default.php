<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

$this->document->setTitle(Text::_('COM_TURISMO_RECURSOS_QUARTO'));

?>
<h1><?php echo Text::_('COM_TURISMO_RECURSOS_QUARTO'); ?></h1>
<table class="table">
    <thead>
        <tr>
            <th><?php echo Text::_('COM_TURISMO_RECURSOS_QUARTO'); ?></th>
            <th><?php echo Text::_('COM_TURISMO_ACTIONS'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->items as $item): ?>
        <tr>
            <td><?php echo $item->nome; ?></td>
            <td>
                <a href="<?php echo JRoute::_('index.php?option=com_turismo&task=recursos_quarto.edit&id=' . $item->id); ?>">
                    <?php echo Text::_('COM_TURISMO_EDIT'); ?>
                </a>
                <a href="<?php echo JRoute::_('index.php?option=com_turismo&task=recursos_quarto.delete&id=' . $item->id); ?>">
                    <?php echo Text::_('COM_TURISMO_DELETE'); ?>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

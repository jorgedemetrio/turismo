<?php
defined('_JEXEC') or die;

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<form action="<?php echo JRoute::_('index.php?option=com_turismo&view=locals'); ?>" method="post" name="adminForm" id="adminForm">
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="1%"><?php echo JText::_('COM_TURISMO_LOCALS_HEADING_ID'); ?></th>
                <th width="20%"><?php echo JText::_('COM_TURISMO_LOCALS_HEADING_NAME'); ?></th>
                <th width="10%"><?php echo JText::_('COM_TURISMO_LOCALS_HEADING_HIGHLIGHT'); ?></th>
                <th width="10%"><?php echo JText::_('COM_TURISMO_LOCALS_HEADING_STATUS'); ?></th>
                <th width="1%"><?php echo JText::_('COM_TURISMO_LOCALS_HEADING_ACTIONS'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->items as $i => $item) : ?>
                <tr>
                    <td><?php echo $item->id; ?></td>
                    <td><?php echo $item->nome; ?></td>
                    <td>
                        <input type="number" name="highlight[<?php echo $item->id; ?>]" value="<?php echo $item->highlight; ?>" min="0" max="9">
                    </td>
                    <td>
                        <select name="status[<?php echo $item->id; ?>]">
                            <option value="APROVADO" <?php echo $item->status == 'APROVADO' ? 'selected' : ''; ?>>Aprovado</option>
                            <option value="REPROVADO" <?php echo $item->status == 'REPROVADO' ? 'selected' : ''; ?>>Reprovado</option>
                            <option value="REMOVIDO" <?php echo $item->status == 'REMOVIDO' ? 'selected' : ''; ?>>Removido</option>
                        </select>
                    </td>
                    <td>
                        <button type="submit" name="task" value="local.changeHighlight"><?php echo JText::_('COM_TURISMO_UPDATE_HIGHLIGHT'); ?></button>
                        <button type="submit" name="task" value="local.changeStatus"><?php echo JText::_('COM_TURISMO_UPDATE_STATUS'); ?></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <input type="hidden" name="task" value="">
    <input type="hidden" name="boxchecked" value="0">
    <?php echo JHtml::_('form.token'); ?>
</form>
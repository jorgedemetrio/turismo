<?php
defined('_JEXEC') or die;

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<form action="<?php echo JRoute::_('index.php?option=com_turismo&view=tipolocal'); ?>" method="post" name="adminForm" id="adminForm">
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="1%"><?php echo JText::_('COM_TURISMO_TIPLOCAL_HEADING_ID'); ?></th>
                <th width="20%"><?php echo JText::_('COM_TURISMO_TIPLOCAL_HEADING_NAME'); ?></th>
                <th width="1%"><?php echo JText::_('COM_TURISMO_TIPLOCAL_HEADING_ESTABELECIMENTO'); ?></th>
                <th width="1%"><?php echo JText::_('COM_TURISMO_TIPLOCAL_HEADING_ACTIONS'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->items as $i => $item) : ?>
                <tr>
                    <td><?php echo $item->id; ?></td>
                    <td><?php echo $item->nome; ?></td>
                    <td><?php echo $item->estabelecimento ? JText::_('JYES') : JText::_('JNO'); ?></td>
                    <td>
                        <button type="submit" name="task" value="tipolocal.edit"><?php echo JText::_('JEDIT'); ?></button>
                        <button type="submit" name="task" value="tipolocal.delete"><?php echo JText::_('JDELETE'); ?></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <input type="hidden" name="task" value="">
    <input type="hidden" name="boxchecked" value="0">
    <?php echo JHtml::_('form.token'); ?>
</form>

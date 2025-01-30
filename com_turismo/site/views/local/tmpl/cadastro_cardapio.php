<?php
defined('_JEXEC') or die;

// Importar o JavaScript necessário
$document = JFactory::getDocument();
$document->addScript(JURI::base() . 'media/com_turismo/js/local_edit.js?v='.time());

// Exibir o título da página
?>
<h1><?php echo JText::_('COM_TURISMO_CADASTRO_DE_CARDAPIO'); ?></h1>

<form method="post" action="<?php echo JRoute::_('index.php?option=com_turismo&task=local.saveCardapio'); ?>">
    <label for="nome"><?php echo JText::_('COM_TURISMO_NOME_ITEM'); ?>:</label>
    <input type="text" name="nome" required />

    <label for="descricao"><?php echo JText::_('COM_TURISMO_DESCRICAO'); ?>:</label>
    <textarea name="descricao" required></textarea>

    <label for="preco"><?php echo JText::_('COM_TURISMO_PRECO'); ?>:</label>
    <input type="number" name="preco" required />

    <input type="submit" value="<?php echo JText::_('COM_TURISMO_SALVAR'); ?>" />
</form>

<h2><?php echo JText::_('COM_TURISMO_LISTA_DE_CARDAPIO'); ?></h2>
<table class="table">
    <thead>
        <tr>
            <th><?php echo JText::_('COM_TURISMO_ID'); ?></th>
            <th><?php echo JText::_('COM_TURISMO_NOME_ITEM'); ?></th>
            <th><?php echo JText::_('COM_TURISMO_DESCRICAO'); ?></th>
            <th><?php echo JText::_('COM_TURISMO_PRECO'); ?></th>
            <th><?php echo JText::_('COM_TURISMO_ACOES'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->cardapio as $item): ?>
        <tr>
            <td><?php echo $item->id; ?></td>
            <td><?php echo $item->nome; ?></td>
            <td><?php echo $item->descricao; ?></td>
            <td><?php echo $item->preco; ?></td>
            <td>
                <a href="<?php echo JRoute::_('index.php?option=com_turismo&task=local.editCardapio&id=' . $item->id); ?>"><?php echo JText::_('COM_TURISMO_EDITAR'); ?></a>
                <a href="<?php echo JRoute::_('index.php?option=com_turismo&task=local.removeCardapio&id=' . $item->id); ?>"><?php echo JText::_('COM_TURISMO_REMOVER'); ?></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

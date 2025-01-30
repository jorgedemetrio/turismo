<?php
defined('_JEXEC') or die;

// Importar o JavaScript necessário
$document = JFactory::getDocument();
$document->addScript(JURI::base() . 'media/com_turismo/js/local_edit.js?v='.time());

// Exibir o título da página
?>
<h1><?php echo JText::_('COM_TURISMO_CADASTRO_DE_QUARTOS'); ?></h1>

<form method="post" action="<?php echo JRoute::_('index.php?option=com_turismo&task=local.saveQuarto'); ?>">
    <label for="nome"><?php echo JText::_('COM_TURISMO_NOME_QUARTO'); ?>:</label>
    <input type="text" name="nome" required />

    <label for="descricao"><?php echo JText::_('COM_TURISMO_DESCRICAO'); ?>:</label>
    <textarea name="descricao" required></textarea>

    <label for="preco"><?php echo JText::_('COM_TURISMO_PRECO'); ?>:</label>
    <input type="number" name="preco" required />

    <input type="submit" value="<?php echo JText::_('COM_TURISMO_SALVAR'); ?>" />
</form>

<h2><?php echo JText::_('COM_TURISMO_LISTA_DE_QUARTOS'); ?></h2>
<table class="table">
    <thead>
        <tr>
            <th><?php echo JText::_('COM_TURISMO_ID'); ?></th>
            <th><?php echo JText::_('COM_TURISMO_NOME_QUARTO'); ?></th>
            <th><?php echo JText::_('COM_TURISMO_DESCRICAO'); ?></th>
            <th><?php echo JText::_('COM_TURISMO_PRECO'); ?></th>
            <th><?php echo JText::_('COM_TURISMO_ACOES'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->quartos as $quarto): ?>
        <tr>
            <td><?php echo $quarto->id; ?></td>
            <td><?php echo $quarto->nome; ?></td>
            <td><?php echo $quarto->descricao; ?></td>
            <td><?php echo $quarto->preco; ?></td>
            <td>
                <a href="<?php echo JRoute::_('index.php?option=com_turismo&task=local.editQuarto&id=' . $quarto->id); ?>"><?php echo JText::_('COM_TURISMO_EDITAR'); ?></a>
                <a href="<?php echo JRoute::_('index.php?option=com_turismo&task=local.removeQuarto&id=' . $quarto->id); ?>"><?php echo JText::_('COM_TURISMO_REMOVER'); ?></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

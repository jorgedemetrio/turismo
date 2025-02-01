<?php
defined('_JEXEC') or die;

// Exibir a lista de locais
?>
<h1><?php echo JText::_('COM_TURISMO_LISTA_DE_LOCAIS'); ?></h1>
<form method="get">
    <label for="filter_name"><?php echo JText::_('COM_TURISMO_FILTRO_NOME'); ?>:</label>
    <input type="text" name="filter_name" />
    
    <label for="filter_cnpj"><?php echo JText::_('COM_TURISMO_FILTRO_CNPJ'); ?>:</label>
    <input type="text" name="filter_cnpj" />
    
    <label for="filter_cep"><?php echo JText::_('COM_TURISMO_FILTRO_CEP'); ?>:</label>
    <input type="text" name="filter_cep" />
    
    <label for="filter_bairro"><?php echo JText::_('COM_TURISMO_FILTRO_BAIRRO'); ?>:</label>
    <input type="text" name="filter_bairro" />
    
    <input type="submit" value="<?php echo JText::_('COM_TURISMO_BUSCAR'); ?>" />
</form>

<table class="table">
    <thead>
        <tr>
            <th><?php echo JText::_('COM_TURISMO_ID'); ?></th>
            <th><?php echo JText::_('COM_TURISMO_NOME'); ?></th>
            <th><?php echo JText::_('COM_TURISMO_CNPJ'); ?></th>
            <th><?php echo JText::_('COM_TURISMO_DATA_CRIACAO'); ?></th>
            <th><?php echo JText::_('COM_TURISMO_ACOES'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->items as $item): ?>
        <tr>
            <td><?php echo $item->id; ?></td>
            <td><?php echo $item->nome; ?></td>
            <td><?php echo $item->cnpj; ?></td>
            <td><?php echo $item->data_criacao; ?></td>
            <td>
                <a href="<?php echo JRoute::_('index.php?option=com_turismo&task=local.edit&id=' . $item->id); ?>"><?php echo JText::_('COM_TURISMO_EDITAR'); ?></a>
                <a href="<?php echo JRoute::_('index.php?option=com_turismo&task=local.delete&id=' . $item->id); ?>"><?php echo JText::_('COM_TURISMO_REMOVER'); ?></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Paginação -->
<div>
    <?php echo $this->pagination->getPagesLinks(); ?>
</div>

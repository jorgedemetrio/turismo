<?php
defined('_JEXEC') or die;

// Exibir a lista de locais
?>
<h1><?php echo JText::_('COM_TURISMO_LISTA_DE_LOCAIS'); ?></h1>
<form method="get">
    <label for="filter_bairro"><?php echo JText::_('COM_TURISMO_FILTRO_BAIRRO'); ?>:</label>
    <input type="text" name="filter_bairro" />
    
    <label for="filter_cep"><?php echo JText::_('COM_TURISMO_FILTRO_CEP'); ?>:</label>
    <input type="text" name="filter_cep" />
    
    <label for="filter_tipo"><?php echo JText::_('COM_TURISMO_FILTRO_TIPO'); ?>:</label>
    <select name="filter_tipo">
        <option value=""><?php echo JText::_('COM_TURISMO_SELECIONE'); ?></option>
        <option value="1"><?php echo JText::_('COM_TURISMO_HOTEIS'); ?></option>
        <option value="2"><?php echo JText::_('COM_TURISMO_RESTAURANTES'); ?></option>
        <!-- Adicionar outros tipos -->
    </select>
    
    <input type="submit" value="<?php echo JText::_('COM_TURISMO_BUSCAR'); ?>" />
</form>

<table class="table">
    <thead>
        <tr>
            <th><?php echo JText::_('COM_TURISMO_ID'); ?></th>
            <th><?php echo JText::_('COM_TURISMO_NOME'); ?></th>
            <th><?php echo JText::_('COM_TURISMO_TIPO'); ?></th>
            <th><?php echo JText::_('COM_TURISMO_MEDIA_AVALIACOES'); ?></th>
            <th><?php echo JText::_('COM_TURISMO_ACOES'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->items as $item): ?>
        <tr>
            <td><?php echo $item->id; ?></td>
            <td><?php echo $item->nome; ?></td>
            <td><?php echo $item->id_tipo_local; // Aqui você deve buscar o nome do tipo de local ?></td>
            <td><?php echo $item->media_avaliacoes; ?></td>
            <td>
                <a href="<?php echo JRoute::_('index.php?option=com_turismo&task=local.detail&id=' . $item->id); ?>"><?php echo JText::_('COM_TURISMO_VER_DETALHES'); ?></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Paginação -->
<div>
    <?php echo $this->pagination->getPagesLinks(); ?>
</div>

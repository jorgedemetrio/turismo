<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

$this->document->setTitle(Text::_('COM_TURISMO_EDIT_ENCONTRO'));

?>
<h1><?php echo Text::_('COM_TURISMO_EDIT_ENCONTRO'); ?></h1>

<form action="<?php echo JRoute::_('index.php?option=com_turismo&task=encontros.save'); ?>" method="post" id="adminForm">
    <div>
        <label for="titulo"><?php echo Text::_('COM_TURISMO_FIELD_TITULO_LABEL'); ?></label>
        <input type="text" name="titulo" id="titulo" value="<?php echo $this->escape($this->item->titulo); ?>" required />
    </div>
    <div>
        <label for="descricao"><?php echo Text::_('COM_TURISMO_FIELD_DESCRICAO_LABEL'); ?></label>
        <textarea name="descricao" id="descricao" required><?php echo $this->escape($this->item->descricao); ?></textarea>
    </div>
    <div>
        <label for="data"><?php echo Text::_('COM_TURISMO_FIELD_HORA_LABEL'); ?></label>
        <input type="date" name="data" id="data" value="<?php echo (!empty($this->item->data)) ? JFactory::getDate($this->escape($this->item->data))->format('d-m-Y') :''; ?>" required />
    </div>
    <div>
        <label for="hora"><?php echo Text::_('COM_TURISMO_FIELD_HORA_LABEL'); ?></label>
        <input type="date" name="hora" id="hora" value="<?php echo (!empty($this->item->data)) ? JFactory::getDate($this->escape($this->item->data))->format('H:i') :''; ?>" required />
    </div>
    <!-- Campos não editáveis para informações de criação e modificação -->
    <?php if (!empty($this->item->created_by)): ?>
    <div>
        <label><?php echo Text::_('COM_TURISMO_CREATED_BY'); ?></label>
        <p><?php echo $this->escape($this->item->created_by); ?></p>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($this->item->modified_by)): ?>
    <div>
        <label><?php echo Text::_('COM_TURISMO_MODIFIED_BY'); ?></label>
        <p><?php echo $this->escape($this->item->modified_by); ?></p>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($this->item->created)): ?>
    <div>
        <label><?php echo Text::_('COM_TURISMO_CREATED_DATE'); ?></label>
        <p><?php echo JFactory::getDate($this->item->created)->format('d/m/Y'); ?></p>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($this->item->modified)): ?>
    <div>
        <label><?php echo Text::_('COM_TURISMO_MODIFIED_DATE'); ?></label>
        <p><?php echo JFactory::getDate($this->item->modified)->format('d/m/Y'); ?></p>
    </div>
    <?php endif; ?>

    <div>
        <label for="alias"><?php echo Text::_('COM_TURISMO_FIELD_ALIAS_LABEL'); ?></label>
        <input type="text" name="alias" id="alias" value="<?php echo $this->escape($this->item->alias); ?>" required />
    </div>
    <div>
        <label for="id_bom_para"><?php echo Text::_('COM_TURISMO_FIELD_ID_BOM_PARA_LABEL'); ?></label>
        <input type="number" name="id_bom_para" id="id_bom_para" value="<?php echo $this->escape($this->item->id_bom_para); ?>" required />
    </div>
    <div>
        <label for="limite_participantes"><?php echo Text::_('COM_TURISMO_FIELD_LIMITE_PARTICIPANTES_LABEL'); ?></label>
        <input type="number" name="limite_participantes" id="limite_participantes" value="<?php echo $this->escape($this->item->limite_participantes); ?>" />
    </div>
    <div>
        <label for="publico"><?php echo Text::_('COM_TURISMO_FIELD_PUBLICO_LABEL'); ?></label>
        <input type="checkbox" name="publico" id="publico" value="1" <?php echo $this->item->publico ? 'checked' : ''; ?> />
    </div>
    <div>
        <label for="destaque"><?php echo Text::_('COM_TURISMO_FIELD_DESTAQUE_LABEL'); ?></label>
        <input type="checkbox" name="destaque" id="destaque" value="1" <?php echo $this->item->destaque ? 'checked' : ''; ?> />
    </div>
    <div>
        <label for="status"><?php echo Text::_('COM_TURISMO_FIELD_STATUS_LABEL'); ?></label>
        <select name="status" id="status">
            <option value="ATIVO" <?php echo $this->item->status == 'ATIVO' ? 'selected' : ''; ?>><?php echo Text::_('COM_TURISMO_STATUS_ATIVO'); ?></option>
            <option value="REPROVADO" <?php echo $this->item->status == 'REPROVADO' ? 'selected' : ''; ?>><?php echo Text::_('COM_TURISMO_STATUS_REPROVADO'); ?></option>
            <option value="NOVO" <?php echo $this->item->status == 'NOVO' ? 'selected' : ''; ?>><?php echo Text::_('COM_TURISMO_STATUS_NOVO'); ?></option>
            <option value="REMOVIDO" <?php echo $this->item->status == 'REMOVIDO' ? 'selected' : ''; ?>><?php echo Text::_('COM_TURISMO_STATUS_REMOVIDO'); ?></option>
        </select>
    </div>
    <div>
        <label><?php echo Text::_('COM_TURISMO_FIELD_ACESSOS_LABEL'); ?></label>
        <p><?php echo $this->escape($this->item->acessos); ?></p>
    </div>
    <div>
        <input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
        <button type="submit"><?php echo Text::_('COM_TURISMO_SAVE'); ?></button>
    </div>
    
    <!-- Campos não editáveis para informações de criação e modificação -->
    <?php if (!empty($this->item->created_by)): ?>
    <div>
        <label><?php echo Text::_('COM_TURISMO_CREATED_BY'); ?></label>
        <p><?php echo $this->escape($this->item->created_by); ?></p>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($this->item->modified_by)): ?>
    <div>
        <label><?php echo Text::_('COM_TURISMO_MODIFIED_BY'); ?></label>
        <p><?php echo $this->escape($this->item->modified_by); ?></p>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($this->item->created)): ?>
    <div>
        <label><?php echo Text::_('COM_TURISMO_CREATED_DATE'); ?></label>
        <p><?php echo JFactory::getDate($this->item->created)->format('d/m/Y'); ?></p>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($this->item->modified)): ?>
    <div>
        <label><?php echo Text::_('COM_TURISMO_MODIFIED_DATE'); ?></label>
        <p><?php echo JFactory::getDate($this->item->modified)->format('d/m/Y'); ?></p>
    </div>
    <?php endif; ?>
</form>

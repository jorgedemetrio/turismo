<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

$this->document->setTitle(Text::_('COM_TURISMO_EDIT_TIPO_LOCAL'));

?>
<h1><?php echo Text::_('COM_TURISMO_EDIT_TIPO_LOCAL'); ?></h1>

<form action="<?php echo JRoute::_('index.php?option=com_turismo&task=tipolocal.save'); ?>" method="post" id="adminForm">
    <div>
        <label for="nome"><?php echo Text::_('COM_TURISMO_FIELD_NOME_LABEL'); ?></label>
        <input type="text" name="nome" id="nome" value="<?php echo $this->escape($this->item->nome); ?>" required maxlength="250"/>
    </div>
    <div>
        <label for="estabelecimento"><?php echo Text::_('COM_TURISMO_FIELD_ESTABELECIMENTO_LABEL'); ?></label>
        <input type="checkbox" name="estabelecimento" id="estabelecimento" value="1" <?php echo $this->item->estabelecimento ? 'checked' : ''; ?> />
    </div>
    <div>
        <label for="status"><?php echo Text::_('COM_TURISMO_FIELD_STATUS_LABEL'); ?></label>
        <select name="status" id="status">
            <option value="ATIVO" <?php echo $this->item->status == 1 ? 'selected' : ''; ?>><?php echo Text::_('COM_TURISMO_STATUS_ATIVO'); ?></option>
            <option value="REMOVIDO" <?php echo $this->item->status == 0 ? 'selected' : ''; ?>><?php echo Text::_('COM_TURISMO_STATUS_REMOVIDO'); ?></option>
        </select>
    </div>
    <div>
        <label for="catid"><?php echo Text::_('COM_TURISMO_FIELD_CATEGORIA_LABEL'); ?></label>
        <select name="catid" id="catid">
            <option value=""><?php echo Text::_('COM_TURISMO_SELECT_CATEGORIA'); ?></option>
            <?php 
            if($this->categorias):
                foreach ($this->categorias as $categoria): ?>
                    <option value="<?php echo $categoria->id; ?>" <?php echo ($this->item->catid==$categoria->id? ' SELECTED ': '') ?>><?php echo $categoria->name; ?></option>
<?php 
                endforeach; 
            endif;?>
        </select>
    </div>
    <div>
        <input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
        <button type="submit"><?php echo Text::_('COM_TURISMO_SAVE'); ?></button>
    </div>
</form>

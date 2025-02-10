<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

$this->document->setTitle(Text::_('COM_TURISMO_EDIT_TIPO_COZINHA'));

?>
<h1><?php echo Text::_('COM_TURISMO_EDIT_TIPO_COZINHA'); ?></h1>

<form action="<?php echo JRoute::_('index.php?option=com_turismo&task=tipo_cozinha.save'); ?>" method="post" id="adminForm">
    <div>
        <label for="nome"><?php echo Text::_('COM_TURISMO_FIELD_NOME_LABEL'); ?></label>
        <input type="text" name="nome" id="nome" value="<?php echo $this->escape($this->item->nome); ?>" required maxlength="250"/>
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
        <input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
        <button type="submit"><?php echo Text::_('COM_TURISMO_SAVE'); ?></button>
    </div>
</form>

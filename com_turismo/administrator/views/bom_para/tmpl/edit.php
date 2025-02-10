<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

$this->document->setTitle(Text::_('COM_TURISMO_EDIT_BOM_PARA'));

?>
<h1><?php echo Text::_('COM_TURISMO_EDIT_BOM_PARA'); ?></h1>

<form action="<?php echo JRoute::_('index.php?option=com_turismo&task=bom_para.save'); ?>" method="post" id="adminForm">
    <div>
        <label for="nome"><?php echo Text::_('COM_TURISMO_FIELD_NOME_LABEL'); ?></label>
        <input type="text" name="nome" id="nome" value="<?php echo $this->escape($this->item->nome); ?>" required
        maxlength="250" />
    </div>
    <div>
        <label for="publish"><?php echo Text::_('COM_TURISMO_FIELD_PUBLISH_LABEL'); ?></label>
        <input type="date" name="publish" id="publish" value="<?php echo (!empty($this->item->publish)) ? JFactory::getDate($this->escape($this->item->publish))->format('d-m-Y') : date('d-m-Y'); ?>" required />
    </div>
    <div>
        <label for="unpublish"><?php echo Text::_('COM_TURISMO_FIELD_UNPUBLISH_LABEL'); ?></label>
        <input type="date" name="unpublish" id="unpublish" value="<?php echo (!empty($this->item->unpublish)) ? JFactory::getDate($this->escape($this->item->unpublish))->format('d-m-Y') :''; ?>"  />
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

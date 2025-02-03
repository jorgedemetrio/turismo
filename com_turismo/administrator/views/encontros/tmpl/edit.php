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
        <label for="data_hora"><?php echo Text::_('COM_TURISMO_FIELD_DATA_HORA_LABEL'); ?></label>
        <input type="datetime-local" name="data_hora" id="data_hora" value="<?php echo $this->escape($this->item->data_hora); ?>" required />
    </div>
    <div>
        <input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
        <button type="submit"><?php echo Text::_('COM_TURISMO_SAVE'); ?></button>
    </div>
</form>

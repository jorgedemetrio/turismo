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
        <input type="text" name="nome" id="nome" value="<?php echo $this->escape($this->item->nome); ?>" required />
    </div>
    <div>
        <input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
        <button type="submit"><?php echo Text::_('COM_TURISMO_SAVE'); ?></button>
    </div>
</form>

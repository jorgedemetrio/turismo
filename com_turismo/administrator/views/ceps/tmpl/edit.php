<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

$this->document->setTitle(Text::_('COM_TURISMO_EDIT_CEP'));

?>
<h1><?php echo Text::_('COM_TURISMO_EDIT_CEP'); ?></h1>

<form action="<?php echo JRoute::_('index.php?option=com_turismo&task=ceps.save'); ?>" method="post" id="adminForm">
    <div>
        <label for="cep"><?php echo Text::_('COM_TURISMO_FIELD_CEP_LABEL'); ?></label>
        <input type="text" name="cep" id="cep" value="<?php echo $this->escape($this->item->cep); ?>" required />
    </div>
    <div>
        <label for="id_cidade"><?php echo Text::_('COM_TURISMO_FIELD_ID_CIDADE_LABEL'); ?></label>
        <input type="number" name="id_cidade" id="id_cidade" value="<?php echo $this->escape($this->item->id_cidade); ?>" required />
    </div>
    <div>
        <label for="endereco"><?php echo Text::_('COM_TURISMO_FIELD_ENDERECO_LABEL'); ?></label>
        <input type="text" name="endereco" id="endereco" value="<?php echo $this->escape($this->item->endereco); ?>" />
    </div>
    <div>
        <label for="latitude"><?php echo Text::_('COM_TURISMO_FIELD_LATITUDE_LABEL'); ?></label>
        <input type="text" name="latitude" id="latitude" value="<?php echo $this->escape($this->item->latitude); ?>" />
    </div>
    <div>
        <label for="longitude"><?php echo Text::_('COM_TURISMO_FIELD_LONGITUDE_LABEL'); ?></label>
        <input type="text" name="longitude" id="longitude" value="<?php echo $this->escape($this->item->longitude); ?>" />
    </div>
    <div>
        <input type="hidden" name="cep" value="<?php echo $this->item->cep; ?>" />
        <button type="submit"><?php echo Text::_('COM_TURISMO_SAVE'); ?></button>
    </div>
</form>

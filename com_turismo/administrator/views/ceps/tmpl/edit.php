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
        <input type="text" name="cep" id="cep" value="<?php echo $this->escape($this->item->cep); ?>" required
        maxlength="9" pattern="[0-9]{5}\-[0-9]{2}" />
    </div>

    <div>
        <label for="estado"><?php echo Text::_('COM_TURISMO_FIELD_ESTADO_LABEL'); ?></label>
        <select id="estado" onchange="filterCities()">
            <option value=""><?php echo Text::_('COM_TURISMO_SELECT_ESTADO'); ?></option>
            <?php 
            if($this->estados):
            foreach ($this->estados as $estado): ?>
                <option value="<?php echo $estado->uf; ?>" <?php echo ($this->item->uf==$estado->uf? ' SELECTED ': '') ?>><?php echo $estado->nome; ?></option>
            <?php endforeach; 
            endif;?>
        </select>
    </div>
    <div>
        <label for="id_cidade"><?php echo Text::_('COM_TURISMO_FIELD_ID_CIDADE_LABEL'); ?></label>
        <select name="id_cidade" id="id_cidade">
            <option value=""><?php echo Text::_('COM_TURISMO_SELECT_CIDADE'); ?></option>
            <!-- As cidades serão carregadas aqui via Ajax -->
            <?php 
            if($this->cidades):
                foreach ($this->cidades as $cidade): ?>
                    <option value="<?php echo $cidade->id_cidade; ?>" <?php echo ($this->item->id_cidade==$cidade->id_cidade? ' SELECTED ': '') ?>><?php echo $cidade->nome; ?></option>
<?php 
                endforeach; 
            endif;?>
        </select>
    </div>
    <div>
        <label for="endereco"><?php echo Text::_('COM_TURISMO_FIELD_ENDERECO_LABEL'); ?></label>
        <input type="text" name="endereco" id="endereco" value="<?php echo $this->escape($this->item->endereco); ?>" 
            maxlength="250" required/>
    </div>
    <div>
        <label for="latitude"><?php echo Text::_('COM_TURISMO_FIELD_LATITUDE_LABEL'); ?></label>
        <input type="number" name="latitude" id="latitude" value="<?php echo $this->escape($this->item->latitude); ?>" 
            max="999999999" min="1" required/>
    </div>
    <div>
        <label for="number"><?php echo Text::_('COM_TURISMO_FIELD_LONGITUDE_LABEL'); ?></label>
        <input type="text" name="longitude" id="longitude" value="<?php echo $this->escape($this->item->longitude); ?>" 
        max="999999999" min="1" required/>
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

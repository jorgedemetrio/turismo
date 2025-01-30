<?php
defined('_JEXEC') or die;

// Exibir o formulário de edição de local
?>
<h1><?php echo JText::_('COM_TURISMO_EDITAR_LOCAL'); ?></h1>
<form action="<?php echo JRoute::_('index.php?option=com_turismo&task=local.save'); ?>" method="post" class="form-validate">
    <input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
    
    <div class="mb-3">
        <label for="nome" class="form-label"><?php echo JText::_('COM_TURISMO_NOME'); ?></label>
        <input type="text" class="form-control" name="nome" value="<?php echo $this->item->nome; ?>" required />
    </div>

    <div class="mb-3">
        <label for="cnpj" class="form-label"><?php echo JText::_('COM_TURISMO_CNPJ'); ?></label>
        <input type="text" class="form-control" name="cnpj" value="<?php echo $this->item->cnpj; ?>" />
    </div>

    <div class="mb-3">
        <label for="cep" class="form-label"><?php echo JText::_('COM_TURISMO_CEP'); ?></label>
        <input type="text" class="form-control" name="cep" value="<?php echo $this->item->cep; ?>" required />
    </div>

    <div class="mb-3">
        <label for="endereco" class="form-label"><?php echo JText::_('COM_TURISMO_ENDERECO'); ?></label>
        <input type="text" class="form-control" name="endereco" value="<?php echo $this->item->endereco; ?>" required />
    </div>

    <div class="mb-3">
        <label for="numero" class="form-label"><?php echo JText::_('COM_TURISMO_NUMERO'); ?></label>
        <input type="text" class="form-control" name="numero" value="<?php echo $this->item->numero; ?>" required />
    </div>

    <div class="mb-3">
        <label for="bairro" class="form-label"><?php echo JText::_('COM_TURISMO_BAIRRO'); ?></label>
        <input type="text" class="form-control" name="bairro" value="<?php echo $this->item->bairro; ?>" required />
    </div>

    <div class="mb-3">
        <label for="tipo_local" class="form-label"><?php echo JText::_('COM_TURISMO_TIPO_LOCAL'); ?></label>
        <select name="tipo_local" class="form-select" required>
            <option value=""><?php echo JText::_('COM_TURISMO_SELECIONE'); ?></option>
            <option value="1" <?php echo ($this->item->id_tipo_local == 1) ? 'selected' : ''; ?>><?php echo JText::_('COM_TURISMO_HOTEIS'); ?></option>
            <option value="2" <?php echo ($this->item->id_tipo_local == 2) ? 'selected' : ''; ?>><?php echo JText::_('COM_TURISMO_RESTAURANTES'); ?></option>
            <!-- Adicionar outros tipos -->
        </select>
    </div>

    <button type="submit" class="btn btn-primary"><?php echo JText::_('COM_TURISMO_SALVAR'); ?></button>
</form>

<?php
defined('_JEXEC') or die;

// Importar o JavaScript de validação
$document = JFactory::getDocument();
$document->addScript(JURI::base() . 'media/com_turismo/js/local_edit.js?v='.time());
?>
<h1><?php echo JText::_('COM_TURISMO_EDITAR_LOCAL'); ?></h1>
<form action="<?php echo JRoute::_('index.php?option=com_turismo&task=local.save'); ?>" method="post" class="form-validate">
    <input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
    <input type="hidden" name="<?php echo JSession::getFormToken(); ?>" value="1" />

    <div class="mb-3">
        <label for="tipo_local" class="form-label"><?php echo JText::_('COM_TURISMO_TIPO_LOCAL'); ?> *</label>
        <select name="tipo_local" class="form-select" id="tipo_local" required>
            <option value=""><?php echo JText::_('COM_TURISMO_SELECIONE'); ?></option>
            <?php foreach ($this->tipos as $tipo): ?>
                <option value="<?php echo $tipo->id; ?>"><?php echo $tipo->nome; ?></option>
            <?php endforeach; ?>
        </select>
        <div class="invalid-feedback">
            <?php echo JText::_('COM_TURISMO_TIPO_LOCAL_OBRIGATORIO'); ?>
        </div>
    </div>

    <div id="campos_adicionais" style="display: none;">
        <div class="mb-3">
            <label for="cep" class="form-label"><?php echo JText::_('COM_TURISMO_CEP'); ?> *</label>
            <input type="text" class="form-control" name="cep" value="<?php echo $this->item->cep; ?>" required />
            <div class="invalid-feedback">
                <?php echo JText::_('COM_TURISMO_CEP_OBRIGATORIO'); ?>
            </div>
        </div>

        <div class="mb-3">
            <label for="nome" class="form-label"><?php echo JText::_('COM_TURISMO_NOME'); ?> *</label>
            <input type="text" class="form-control" name="nome" value="<?php echo $this->item->nome; ?>" required />
        </div>

        <div class="mb-3">
            <label for="cnpj" class="form-label"><?php echo JText::_('COM_TURISMO_CNPJ'); ?></label>
            <input type="text" class="form-control" name="cnpj" value="<?php echo $this->item->cnpj; ?>" />
        </div>

        <div class="mb-3">
            <label for="endereco" class="form-label"><?php echo JText::_('COM_TURISMO_ENDERECO'); ?> *</label>
            <input type="text" class="form-control" name="endereco" value="<?php echo $this->item->endereco; ?>" required />
        </div>

        <div class="mb-3">
            <label for="numero" class="form-label"><?php echo JText::_('COM_TURISMO_NUMERO'); ?> *</label>
            <input type="text" class="form-control" name="numero" value="<?php echo $this->item->numero; ?>" required />
        </div>

        <div class="mb-3">
            <label for="bairro" class="form-label"><?php echo JText::_('COM_TURISMO_BAIRRO'); ?> *</label>
            <input type="text" class="form-control" name="bairro" value="<?php echo $this->item->bairro; ?>" required />
        </div>

        <div class="mb-3">
            <label for="valor_medio" class="form-label"><?php echo JText::_('COM_TURISMO_VALOR_MEDIO'); ?> *</label>
            <input type="number" class="form-control" name="valor_medio" value="<?php echo $this->item->valor_medio; ?>" required />
        </div>

        <div id="campos_evento" style="display: none;">
            <div class="mb-3">
                <label for="data_inicio" class="form-label"><?php echo JText::_('COM_TURISMO_DATA_INICIO'); ?> *</label>
                <input type="datetime-local" class="form-control" name="data_inicio" required />
            </div>

            <div class="mb-3">
                <label for="data_fim" class="form-label"><?php echo JText::_('COM_TURISMO_DATA_FIM'); ?> *</label>
                <input type="datetime-local" class="form-control" name="data_fim" required />
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary"><?php echo JText::_('COM_TURISMO_SALVAR'); ?></button>
</form>

<!-- Botão para abrir o modal de ajuda -->
<button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#helpModal">
    <?php echo JText::_('COM_TURISMO_AJUDA'); ?>
</button>

<!-- Modal de Ajuda -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="helpModalLabel"><?php echo JText::_('COM_TURISMO_AJUDA'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?php echo JText::_('COM_TURISMO_EXPLICACAO_CADASTRO'); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo JText::_('COM_TURISMO_FECHAR'); ?></button>
            </div>
        </div>
    </div>
</div>


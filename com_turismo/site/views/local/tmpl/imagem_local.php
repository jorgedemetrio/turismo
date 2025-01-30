<?php
defined('_JEXEC') or die;

// Importar o JavaScript de validação
$document = JFactory::getDocument();
$document->addScript(JURI::base() . 'media/com_turismo/js/imagem_local.js?v='.time());
?>
<h1><?php echo JText::_('COM_TURISMO_UPLOAD_IMAGENS'); ?></h1>
<form action="<?php echo JRoute::_('index.php?option=com_turismo&task=local.uploadImages'); ?>" method="post" enctype="multipart/form-data" class="form-validate">
    <input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
    <input type="hidden" name="<?php echo JSession::getFormToken(); ?>" value="1" />

    <div class="mb-3">
        <label for="image_upload" class="form-label"><?php echo JText::_('COM_TURISMO_SELECIONE_IMAGEM'); ?></label>
        <input type="file" class="form-control" name="images[]" id="image_upload" multiple accept="image/*" required />
        <div class="invalid-feedback">
            <?php echo JText::_('COM_TURISMO_IMAGEM_OBRIGATORIA'); ?>
        </div>
    </div>

    <div id="drop_area" class="drop-area">
        <p><?php echo JText::_('COM_TURISMO_DRAG_DROP_IMAGENS_AQUI'); ?></p>
    </div>

    <button type="submit" class="btn btn-primary"><?php echo JText::_('COM_TURISMO_UPLOAD'); ?></button>
</form>

<h2><?php echo JText::_('COM_TURISMO_IMAGENS_CADASTRADAS'); ?></h2>
<div id="uploaded_images">
    <!-- Aqui será exibida a lista de imagens já cadastradas -->
</div>

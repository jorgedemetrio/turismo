<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2023 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');

$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
    ->useScript('form.validate')
    ->useScript('bootstrap.collapse');
?>

<form action="<?php echo Route::_('index.php?option=com_turismo&layout=edit&id=' . (int) $this->item->id); ?>"
    method="post" name="adminForm" id="tipolocal-form" class="form-validate">

    <?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>

    <div class="main-card">
        <?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => 'details')); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'details', Text::_('COM_TURISMO_TIPOLOCAL_TAB_DETAILS')); ?>
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <?php echo $this->form->renderField('nome'); ?>
                        <?php echo $this->form->renderField('estabelecimento'); ?>
                        <?php echo $this->form->renderField('catid'); ?>
                        <?php echo $this->form->renderField('state'); ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <?php echo LayoutHelper::render('joomla.edit.global', $this); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'publishing', Text::_('JGLOBAL_FIELDSET_PUBLISHING')); ?>
        <div class="row">
            <div class="col-12 col-lg-6">
                <fieldset id="fieldset-publishingdata" class="options-form">
                    <legend><?php echo Text::_('JGLOBAL_FIELDSET_PUBLISHING'); ?></legend>
                    <div>
                        <?php echo LayoutHelper::render('joomla.edit.publishingdata', $this); ?>
                    </div>
                </fieldset>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo HTMLHelper::_('uitab.endTabSet'); ?>
    </div>

    <input type="hidden" name="task" value="">
    <?php echo HTMLHelper::_('form.token'); ?>
</form>

<script type="text/javascript">
    Joomla = window.Joomla || {};

    (function() {
        'use strict';
        
        document.addEventListener('DOMContentLoaded', function() {
            // Validação personalizada se necessário
        });
    })();
</script>

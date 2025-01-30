<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2023 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.multiselect');

$user      = Factory::getApplication()->getIdentity();
$userId    = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'a.ordering';

if ($saveOrder && !empty($this->items))
{
    $saveOrderingUrl = 'index.php?option=com_turismo&task=tiposlocal.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
    HTMLHelper::_('draggablelist.draggable');
}
?>

<form action="<?php echo Route::_('index.php?option=com_turismo&view=tiposlocal'); ?>" method="post" name="adminForm" id="adminForm">
    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container">
                <?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

                <?php if (empty($this->items)) : ?>
                    <div class="alert alert-info">
                        <span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
                        <?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                    </div>
                <?php else : ?>
                    <table class="table" id="tiposLocalList">
                        <caption class="visually-hidden">
                            <?php echo Text::_('COM_TURISMO_TIPOSLOCAL_TABLE_CAPTION'); ?>,
                            <span id="orderedBy"><?php echo Text::_('JGLOBAL_SORTED_BY'); ?> </span>,
                            <span id="filteredBy"><?php echo Text::_('JGLOBAL_FILTERED_BY'); ?></span>
                        </caption>
                        <thead>
                            <tr>
                                <td class="w-1 text-center">
                                    <?php echo HTMLHelper::_('grid.checkall'); ?>
                                </td>
                                <th scope="col" class="w-1 text-center">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_TURISMO_TIPOLOCAL_FIELD_NOME_LABEL', 'a.nome', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-10 d-none d-md-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'JCATEGORY', 'category_title', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-10 d-none d-md-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_TURISMO_TIPOLOCAL_FIELD_ESTABELECIMENTO_LABEL', 'a.estabelecimento', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-3 d-none d-lg-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody <?php if ($saveOrder) : ?> class="js-draggable" data-url="<?php echo $saveOrderingUrl; ?>" data-direction="<?php echo strtolower($listDirn); ?>" data-nested="true"<?php endif; ?>>
                            <?php foreach ($this->items as $i => $item) :
                                $ordering   = ($listOrder == 'a.ordering');
                                $canCreate  = $user->authorise('core.create',     'com_turismo.category.' . $item->catid);
                                $canEdit    = $user->authorise('core.edit',       'com_turismo.category.' . $item->catid);
                                $canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || is_null($item->checked_out);
                                $canChange  = $user->authorise('core.edit.state', 'com_turismo.category.' . $item->catid) && $canCheckin;
                                ?>
                                <tr class="row<?php echo $i % 2; ?>" data-draggable-group="<?php echo $item->catid; ?>">
                                    <td class="text-center">
                                        <?php echo HTMLHelper::_('grid.id', $i, $item->id, false, 'cid', 'cb', $item->nome); ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'tiposlocal.', $canChange); ?>
                                    </td>
                                    <th scope="row" class="has-context">
                                        <?php if ($item->checked_out) : ?>
                                            <?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'tiposlocal.', $canCheckin); ?>
                                        <?php endif; ?>
                                        <?php if ($canEdit) : ?>
                                            <a href="<?php echo Route::_('index.php?option=com_turismo&task=tipolocal.edit&id=' . (int) $item->id); ?>" title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape($item->nome); ?>">
                                                <?php echo $this->escape($item->nome); ?>
                                            </a>
                                        <?php else : ?>
                                            <?php echo $this->escape($item->nome); ?>
                                        <?php endif; ?>
                                    </th>
                                    <td class="small d-none d-md-table-cell">
                                        <?php echo $this->escape($item->category_title); ?>
                                    </td>
                                    <td class="small d-none d-md-table-cell">
                                        <?php echo $item->estabelecimento ? Text::_('JYES') : Text::_('JNO'); ?>
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        <?php echo (int) $item->id; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?php echo $this->pagination->getListFooter(); ?>
                <?php endif; ?>

                <input type="hidden" name="task" value="">
                <input type="hidden" name="boxchecked" value="0">
                <?php echo HTMLHelper::_('form.token'); ?>
            </div>
        </div>
    </div>
</form>

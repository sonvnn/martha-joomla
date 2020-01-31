<?php
/**
* @package      EasyBlog
* @copyright    Copyright (C) 2010 - 2017 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<form action="index.php" method="post" name="adminForm" id="adminForm" data-grid-eb>

	<div class="app-filter-bar">
		<div class="app-filter-bar__cell">
			<?php echo $this->html('filter.search', $search); ?>
		</div>

		<div class="app-filter-bar__cell app-filter-bar__cell--divider-left">
			<div class="app-filter-bar__filter-wrap">
				<?php echo $this->html('filter.custom', $filterGroups); ?>
			</div>
		</div>

		<div class="app-filter-bar__cell app-filter-bar__cell--divider-left"></div>

		<div class="app-filter-bar__cell app-filter-bar__cell--divider-left app-filter-bar__cell--last t-text--center">
			<div class="app-filter-bar__filter-wrap">
				<?php echo $this->html('filter.limit', $limit); ?>
			</div>
		</div>
	</div>

	<div class="panel-table">
		<table class="app-table table table-striped table-eb table-hover" data-table-grid>
			<thead>
				<th width="1%" class="center">
					<?php echo $this->html('grid.checkAll'); ?>
				</th>

				<th style="text-align: left;">
					<?php echo JHTML::_('grid.sort', 'COM_EASYBLOG_TABLE_COLUMN_TITLE', 'title', $orderDirection, $order); ?>
				</th>

				<th width="5%" class="center nowrap">
					<?php echo JText::_('COM_EASYBLOG_PUBLISHED'); ?>
				</th>
				<th width="5%" class="center nowrap">
					<?php echo JText::_('COM_EASYBLOG_TABLE_COLUMN_REQUIRED'); ?>
				</th>
				<?php if ($showOrdering) { ?>
				<th width="6%" class="center nowrap">
					<?php echo JHTML::_('grid.sort', 'Order', 'ordering', $orderDirection, $order); ?>
					<?php echo JHTML::_('grid.order', $fields, '', 'fields.saveorder'); ?>
				</th>
				<?php } ?>
				<th width="15%" class="center nowrap">
					<?php echo JText::_('COM_EASYBLOG_TABLE_COLUMN_FIELD_GROUP'); ?>
				</th>
				<th width="15%" class="center nowrap">
					<?php echo JText::_('COM_EASYBLOG_TABLE_COLUMN_FIELD_TYPE'); ?>
				</th>
				<th width="1%" class="center">
					<?php echo JHTML::_('grid.sort', 'COM_EASYBLOG_ID', 'id', $orderDirection, $order); ?>
				</th>
			</thead>
			<tbody>
				<?php if ($fields) { ?>
					<?php $i = 0; ?>
					<?php foreach ($fields as $field) { ?>
					<tr>
						<td width="1%" class="center nowrap">
							<?php echo $this->html('grid.id', $i, $field->id);?>
						</td>
						<td>
							<a href="index.php?option=com_easyblog&view=fields&layout=form&id=<?php echo $field->id;?>"><?php echo JText::_($field->title);?></a>
						</td>

						<td class="center nowrap">
							<?php echo $this->html('grid.published', $field, 'fields', 'state'); ?>
						</td>

						<td class="center nowrap">
							<?php echo $this->html('grid.published', $field, 'fields', 'required', array('fields.setRequired', 'fields.removeRequired')); ?>
						</td>

						<?php if ($showOrdering) { ?>

							<?php $orderkey = array_search($field->id, $ordering); ?>

							<td class="order center">
								<div class="app-order-group">
									<div class="app-order-group__item">
										<?php $disabled = 'disabled="disabled"'; ?>
										<input type="text" name="order[]" value="<?php echo $orderkey + 1;?>" <?php echo $disabled ?> class="order-value input-xsmall"/>
										<?php $originalOrders[] = $orderkey + 1; ?>
									</div>
									
									<div class="app-order-group__item">
										<?php if ($saveOrder) { ?>
											<span class="order-up"><?php echo $pagination->orderUpIcon($i, isset($ordering[$orderkey - 1]), 'fields.orderup', 'Move Up', $ordering); ?></span>
											<span class="order-down"><?php echo $pagination->orderDownIcon($i, $pagination->total, isset($ordering[$orderkey + 1]), 'fields.orderdown', 'Move Down', $ordering); ?></span>
										<?php } ?>
									</div>
								</div>
							</td>



						<?php } ?>

						<td class="center">
							<?php echo $field->getGroupTitle(); ?>
						</td>

						<td class="center">
							<span class="label label-primary"><?php echo ucfirst(JText::_($field->type)); ?></span>
						</td>

						<td class="center">
							<?php echo $field->id; ?>
						</td>
					</tr>
					<?php $i++; ?>
					<?php } ?>
				<?php } else { ?>
					<tr>
						<td colspan="8" class="empty">
							<?php echo JText::_('COM_EASYBLOG_FIELDS_NO_FIELDS_CREATED_YET');?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="7">
						<?php echo $pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>

	<?php echo $this->html('form.action'); ?>
	<input type="hidden" name="view" value="fields" />
	<input type="hidden" name="layout" value="fields" />
	<input type="hidden" name="filter_order" value="<?php echo $order; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
</form>

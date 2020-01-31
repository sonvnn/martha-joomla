<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2017 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<form name="adminForm" id="adminForm" method="post" data-table-grid>
	<div class="app-filter-bar">
		<div class="app-filter-bar__cell">
			<?php echo $this->html('filter.search', $search); ?>
		</div>

		<div class="app-filter-bar__cell app-filter-bar__cell--divider-left">
			<div class="app-filter-bar__filter-wrap">
				<select class="o-form-control" name="filter" id="filterType" data-table-grid-filter>
					<option value="all"<?php echo $filter == 'all' ? ' selected="selected"' : '';?>><?php echo JText::_('COM_EASYSOCIAL_FILTER_ALL_AUDIOS'); ?></option>
					<option value="1"<?php echo $filter === 1 ? ' selected="selected"' : '';?>><?php echo JText::_('COM_EASYSOCIAL_FILTER_PUBLISHED'); ?></option>
					<option value="0"<?php echo $filter === 0 ? ' selected="selected"' : '';?>><?php echo JText::_('COM_EASYSOCIAL_FILTER_UNPUBLISHED'); ?></option>
					<option value="2"<?php echo $filter === 2 ? ' selected="selected"' : '';?>><?php echo JText::_('COM_EASYSOCIAL_FILTER_PENDING'); ?></option>
				</select>
			</div>
		</div>

		<div class="app-filter-bar__cell app-filter-bar__cell--divider-left"></div>

		<div class="app-filter-bar__cell app-filter-bar__cell--divider-left app-filter-bar__cell--last t-text--center">
			<div class="app-filter-bar__filter-wrap">
				<?php echo $this->html('filter.limit' , $limit); ?>
			</div>
		</div>
	</div>

	<div id="audios" class="panel-table">
		<table class="app-table table" data-pending-audios>
			<thead>
				<tr>
					<?php if (!$simple) { ?>
					<th width="5">
						<input type="checkbox" name="toggle" value="" data-table-grid-checkall />
					</th>
					<?php } ?>

					<th style="text-align: left;">
						<?php echo $this->html('grid.sort', 'title', JText::_('COM_EASYSOCIAL_TABLE_COLUMN_TITLE'), $ordering, $direction); ?>
					</th>

					<?php if (!$simple) { ?>
					<th width="5%" class="center">
						<?php echo JText::_('COM_EASYSOCIAL_TABLE_COLUMN_FEATURED'); ?>
					</th>

					<th width="5%" class="center">
						<?php echo JText::_('COM_EASYSOCIAL_TABLE_COLUMN_STATE'); ?>
					</th>

					<th width="10%" class="center">
						<?php echo JText::_('COM_EASYSOCIAL_TABLE_COLUMN_GENRE'); ?>
					</th>
					<?php } ?>

					<th width="<?php echo $simple ? '20%' : '10%';?>" class="center">
						<?php echo JText::_('COM_EASYSOCIAL_TABLE_COLUMN_CREATED'); ?>
					</th>
					<th width="<?php echo $simple ? '10%' : '5%';?>" class="center">
						<?php echo $this->html('grid.sort', 'id', JText::_('COM_EASYSOCIAL_TABLE_COLUMN_ID'), $ordering, $direction); ?>
					</th>
				</tr>
			</thead>

			<tbody>
			<?php if ($audios) { ?>
				<?php $i = 0; ?>

				<?php foreach ($audios as $audio) { ?>
				<tr>
					<?php if (!$simple) { ?>
					<td>
						<?php echo $this->html('grid.id', $i++, $audio->id); ?>
					</td>
					<?php } ?>

					<td align="left">
						<a href="<?php echo ($simple) ? 'javascript:void(0);' : FRoute::_('index.php?option=com_easysocial&view=audios&layout=form&id=' . $audio->id);?>"
							data-audio-insert
							data-id="<?php echo $audio->id;?>"
							data-alias="<?php echo $audio->getAlias();?>"
							data-title="<?php echo $this->html('string.escape', $audio->title);?>"
						><?php echo $audio->title;?></a>
					</td>

					<?php if (!$simple) { ?>
					<td class="center">
						<?php if ($audio->isPendingProcess() || $audio->isProcessing()) { ?>
						&mdash;
						<?php } else { ?>
							<?php echo $this->html('grid.featured', $audio, 'audios', 'featured'); ?>
						<?php } ?>
					</td>
					<td class="center">
						<?php if ($audio->isPendingProcess() || $audio->isProcessing()) { ?>
							<a class="es-state-pending" href="javascript:void(0);" data-es-provide="tooltip" data-original-title="<?php echo JText::_('COM_ES_AUDIO_CURRENTLY_PENDING');?>"></a>
						<?php } else { ?>
							<?php echo $this->html('grid.published', $audio, 'audios'); ?>
						<?php } ?>
					</td>
					<td style="text-align: center;">
						<a href="<?php echo FRoute::_('index.php?option=com_easysocial&view=audiogenres&layout=form&id=' . $audio->getGenre()->id);?>"><?php echo $audio->getGenre()->title;?></a>
					</td>

					<?php } ?>

					<td class="center">
						<?php echo $audio->getCreatedDate()->toSql();?>
					</td>
					<td class="center">
						<?php echo $audio->id;?>
					</td>
				</tr>
				<?php } ?>

			<?php } else { ?>
				<tr class="is-empty">
					<td colspan="7" class="center empty">
						<div>
							<?php echo JText::_('COM_ES_AUDIO_EMPTY_MESSAGE'); ?>
						</div>
					</td>
				</tr>
			<?php } ?>
			</tbody>

			<tfoot>
				<tr>
					<td colspan="7">
						<div class="footer-pagination">
							<?php echo $pagination->getListFooter(); ?>
						</div>
					</td>
				</tr>
			</tfoot>

		</table>
	</div>

	<?php echo JHTML::_('form.token'); ?>
	<input type="hidden" name="ordering" value="<?php echo $ordering;?>" data-table-grid-ordering />
	<input type="hidden" name="direction" value="<?php echo $direction;?>" data-table-grid-direction />
	<input type="hidden" name="boxchecked" value="0" data-table-grid-box-checked />
	<input type="hidden" name="task" value="" data-table-grid-task />
	<input type="hidden" name="option" value="com_easysocial" />
	<input type="hidden" name="controller" value="audios" />
	<input type="hidden" name="view" value="audios" />
</form>

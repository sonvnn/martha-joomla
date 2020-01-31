<?php
/**
 * @package	HikaShop for Joomla!
 * @version	4.2.2
 * @author	hikashop.com
 * @copyright	(C) 2010-2019 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php
class plgHikashopShopclosehours extends hikashopPlugin {

	var $multiple = true;
	var $name = 'shopclosehours';

	var $pluginConfig = array(
		'store_open_day' => array('OPENS_ON', 'list',array(
			'0' => 'HIKA_ALL',
			'1' => 'MONDAY',
			'2' => 'TUESDAY',
			'3' => 'WEDNESDAY',
			'4' => 'THURSDAY',
			'5' => 'FRIDAY',
			'6' => 'SATURDAY',
			'7' => 'SUNDAY'
		)),
		'store_open_time' => array('OPENS_AT', 'time'),
		'store_close_day' => array('CLOSES_ON', 'list',array(
			'0' => 'HIKA_ALL',
			'1' => 'MONDAY',
			'2' => 'TUESDAY',
			'3' => 'WEDNESDAY',
			'4' => 'THURSDAY',
			'5' => 'FRIDAY',
			'6' => 'SATURDAY',
			'7' => 'SUNDAY'
		)),
		'store_close_time' => array('CLOSES_AT', 'time'),
	);

	public function pluginConfigDisplay($fieldType, $data, $type, $paramsType, $key, $element){
		if($fieldType=='time'){
			$map = 'data['.$type.']['.$paramsType.']['.$key.']';
			$value = @$element->$paramsType->$key;
			return '<input type="text" style="width:30px" name="'.$map.'[hour]" placeholder="'.JText::_('HIKA_HH').'" value="'.@$value['hour'].'"/>:<input type="text" style="width:30px" name="'.$map.'[minute]" placeholder="'.JText::_('HIKA_MM').'" value="'.@$value['minute'].'"/>';
		}
	}

	public function onCheckoutWorkflowLoad(&$checkout_workflow, &$shop_closed, $cart_id) {
		if(!hikashop_level(1))
			return;

		$isClosed = $this->isShopClosed();
		if(!$isClosed)
			return;

		$shop_closed = true;

		$checkoutHelper = hikashopCheckoutHelper::get();
		$msg = JText::_('THE_STORE_IS_CLOSED').'<br/>';
		$msg .= JText::_('OPEN_HOURS').'<br/>';
		$ranges = array();
		foreach($this->ranges as $range){
			if($range->store_open_day == '0'&& $range->store_close_day == '0')
				$ranges []= JText::sprintf('EVERY_DAY_FROM_X_TO_X',$range->store_open_hour.':'.sprintf('%02d', $range->store_open_minute),$range->store_close_hour.':'.sprintf('%02d', $range->store_close_minute));
			else
				$ranges []= JText::sprintf('FROM_X_ON_X_TO_X_ON_X',JText::_($this->pluginConfig['store_open_day'][2][$range->store_open_day]),$range->store_open_hour.':'.sprintf('%02d', $range->store_open_minute),JText::_($this->pluginConfig['store_open_day'][2][$range->store_close_day]),$range->store_close_hour.':'.sprintf('%02d', $range->store_close_minute));
		}
		$msg .= implode('<br/>', $ranges);
		$checkoutHelper->addMessage('shop_closed',$msg);
	}

	public function onBeforeOrderCreate(&$order, &$do) {
		if(!hikashop_level(1))
			return;

		$app = JFactory::getApplication();
		$option = hikaInput::get()->getString('option', '');
		if(hikashop_isClient('administrator') || $option != 'com_hikashop')
			return;

		$isClosed = $this->isShopClosed();
		if(!$isClosed)
			return;

		$do = false;
	}

	public function loadRanges(){
		if(!empty($this->ranges))
			return;

		$this->ranges = array();
		$ids = array();
		parent::listPlugins($this->name, $ids, false);

		if(empty($ids) || !count($ids)){
			return;
		}
		foreach($ids as $id) {
			parent::pluginParams($id);
			$this->plugin_params->store_open_hour = $this->plugin_params->store_open_time['hour'];
			$this->plugin_params->store_open_minute = $this->plugin_params->store_open_time['minute'];
			$this->plugin_params->store_close_hour = $this->plugin_params->store_close_time['hour'];
			$this->plugin_params->store_close_minute = $this->plugin_params->store_close_time['minute'];

			if(!strlen($this->plugin_params->store_open_hour) || !strlen($this->plugin_params->store_close_hour) || !strlen($this->plugin_params->store_open_minute) || !strlen($this->plugin_params->store_close_minute)){
				$app = JFactory::getApplication();
				$app->enqueueMessage(JText::sprintf('PLUGIN_X_IS_NOT_CONFIGURED_CORRECTLY_MISSING_DATA',$this->plugin_data->plugin_name));
				continue;
			}

			if(($this->plugin_params->store_open_day == '0' && $this->plugin_params->store_close_day != '0') || ($this->plugin_params->store_open_day != '0' && $this->plugin_params->store_close_day == '0')){
				$app = JFactory::getApplication();
				$app->enqueueMessage(JText::sprintf('PLUGIN_X_IS_NOT_CONFIGURED_CORRECTLY_DAYS_ISSUE',$this->plugin_data->plugin_name));
				continue;
			}

			if($this->plugin_params->store_open_day == '0' && $this->plugin_params->store_close_day == '0' && ($this->plugin_params->store_close_hour<$this->plugin_params->store_open_hour || $this->plugin_params->store_close_hour==$this->plugin_params->store_open_hour && $this->plugin_params->store_close_minute<$this->plugin_params->store_open_minute)){
				$app = JFactory::getApplication();
				$app->enqueueMessage(JText::sprintf('PLUGIN_X_IS_NOT_CONFIGURED_CORRECTLY_DAYS_ISSUE',$this->plugin_data->plugin_name));
				continue;
			}

			$this->ranges[] = $this->plugin_params;
		}
	}

	private function isShopClosed() {
		$this->loadRanges();
		if(empty($this->ranges)){
			return false;
		}

		$now = time();
		$current_day = hikashop_getDate($now, 'N');
		$current_hour = hikashop_getDate($now, '%H');
		$current_minute = hikashop_getDate($now, '%M');
		$closed = true;
		foreach($this->ranges as $r) {
			if($r->store_open_day == '0'){
				if($r->store_open_hour == $r->store_close_hour && $r->store_open_minute == $r->store_close_minute)
				continue;

				if($r->store_open_hour < $r->store_close_hour || ($r->store_open_hour == $r->store_close_hour && $r->store_open_minute < $r->store_close_minute)) {
					$closed = false;
					if($current_hour < $r->store_open_hour || ($current_hour == $r->store_open_hour && $current_minute < $r->store_open_minute)) {
						$closed = true;
					}
					if($r->store_close_hour<$current_hour || ($current_hour == $r->store_close_hour && $r->store_close_minute < $current_minute)) {
						$closed = true;
					}
				} else {
					if($current_hour < $r->store_close_hour || ($current_hour == $r->store_close_hour && $current_minute < $r->store_close_minute)) {
						$closed = false;
					}
					if($r->store_open_hour < $current_hour || ($current_hour == $r->store_open_hour && $r->store_open_minute < $current_minute)) {
						$closed = false;
					}
				}
				if(!$closed){
					return false;
				}
				continue;
			}

			if($r->store_open_day<=$r->store_close_day){
				if($r->store_open_day>$current_day || $r->store_close_day<$current_day){
					continue;
				}
				if($r->store_open_day<$current_day && $r->store_close_day>$current_day)
					return false;
			}else{
				if($r->store_open_day<$current_day || $r->store_close_day>$current_day){
					continue;
				}
				if($r->store_open_day>$current_day && $r->store_close_day<$current_day)
					return false;
			}
			if($r->store_close_day == $current_day){
				if($current_hour < $r->store_close_hour || ($current_hour == $r->store_close_hour && $current_minute < $r->store_close_minute)) {
					if($r->store_open_day != $current_day){
						return false;
					}
				}else{
					continue;
				}
			}
			if($r->store_open_day == $current_day){
				if($r->store_open_hour < $current_hour || ($current_hour == $r->store_open_hour && $r->store_open_minute < $current_minute)) {
					return false;
				}
				continue;
			}
		}
		if(!$closed){
			return false;
		}
		return true;
	}
}

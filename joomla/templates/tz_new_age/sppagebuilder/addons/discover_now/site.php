<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2019 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('Restricted access');

class SppagebuilderAddonDiscover_Now extends SppagebuilderAddons {

	public function render() {
		$settings = $this->addon->settings;
		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$alignment = (isset($settings->alignment) && $settings->alignment) ? $settings->alignment : '';
		$scroll_to_id = (isset($settings->scroll_to_id) && $settings->scroll_to_id) ? $settings->scroll_to_id : '';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';
		$addon_id = '#sppb-addon-' . $this->addon->id;

		$output  = '<div class="sppb-addon sppb-addon-discover-now ' . $alignment . ' ' . $class . '">';

		if($title) {
			$output .= '<'.$heading_selector.' class="sppb-addon-title">' . $title . '</'.$heading_selector.'>';
		}

		$output .= '<div class="sppb-addon-content"><a class="mousey" href="#'.$scroll_to_id.'"><div class="scroller"></div></a></div>';
		$output  .= '</div>';

		return $output;
	}

	public static function getTemplate(){
		$output = '
		<div class="sppb-addon sppb-addon-discover-now {{ data.class }} {{ data.alignment }}">
			<# if( !_.isEmpty( data.title ) ){ #><{{ data.heading_selector }} class="sppb-addon-title sp-inline-editable-element" data-id={{data.id}} data-fieldName="title" contenteditable="true">{{ data.title }}</{{ data.heading_selector }}><# } #>
			<div class="sppb-addon-content"><a class="mouse"><div class="wheel"></div></a></div>
		</div>
		';

		return $output;
	}
}

<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2018 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('Restricted access');

class SppagebuilderAddonGallery_Parallax extends SppagebuilderAddons{

	public function render() {

		$class = (isset($this->addon->settings->class) && $this->addon->settings->class) ? $this->addon->settings->class : '';
		$title = (isset($this->addon->settings->title) && $this->addon->settings->title) ? $this->addon->settings->title : '';
		$heading_selector = (isset($this->addon->settings->heading_selector) && $this->addon->settings->heading_selector) ? $this->addon->settings->heading_selector : 'h3';

		$output  = '<div class="sppb-addon sppb-addon-gallery-parallax ' . $class . '">';
		$output .= ($title) ? '<'.$heading_selector.' class="sppb-addon-title">' . $title . '</'.$heading_selector.'>' : '';
		$output .= '<div class="sppb-addon-content">';
		$output .= '<div class="sppb-gallery-parallax">';

		if(isset($this->addon->settings->sp_gallery_item) && count((array) $this->addon->settings->sp_gallery_item)){
			foreach ($this->addon->settings->sp_gallery_item as $key => $value) {
				if($value->image) {
					$style  =       isset($value->max_width) && $value->max_width ? 'max-width:'. $value->max_width.';' : '';
					$style  .=      isset($value->left) && $value->left!= '' ? 'left:'. $value->left.';' : '';
					$style  .=      isset($value->top) && $value->top!= '' ? 'top:'. $value->top.';' : '';
					$style  .=      isset($value->right) && $value->right!= '' ? 'right:'. $value->right.';' : '';
					$style  .=      isset($value->bottom) && $value->bottom != ''? 'bottom:'. $value->bottom.';' : '';
					if(isset($value->image_shadow) && is_object($value->image_shadow)){
						$ho = (isset($value->image_shadow->ho) && $value->image_shadow->ho != '') ? $value->image_shadow->ho.'px' : '0px';
						$vo = (isset($value->image_shadow->vo) && $value->image_shadow->vo != '') ? $value->image_shadow->vo.'px' : '0px';
						$blur = (isset($value->image_shadow->blur) && $value->image_shadow->blur != '') ? $value->image_shadow->blur.'px' : '0px';
						$spread = (isset($value->image_shadow->spread) && $value->image_shadow->spread != '') ? $value->image_shadow->spread.'px' : '0px';
						$color = (isset($value->image_shadow->color) && $value->image_shadow->color != '') ? $value->image_shadow->color : '';

						if(!empty($color)){
							$style .= "box-shadow: ${ho} ${vo} ${blur} ${spread} ${color};";
						}
					}
					$rellax_data =  isset($value->rellax_speed) && $value->rellax_speed != '' ? ' data-rellax-speed="'.$value->rellax_speed.'"' : '';
					$rellax_data .= isset($value->rellax_min) && $value->rellax_min != '' ? ' data-rellax-min="'.$value->rellax_min.'"' : '';
					$rellax_data .= isset($value->rellax_max) && $value->rellax_max != '' ? ' data-rellax-max="'.$value->rellax_max.'"' : '';
					$rellax_data .= isset($value->rellax_zindex) && $value->rellax_zindex != '' ? ' data-rellax-zindex="'.$value->rellax_zindex.'"' : '';
					$output .= '<img class="rellax" '.$rellax_data.' src="' . $value->image . '" alt="' . $value->title . '" style="'.$style.'"/>';
				}
			}
		}
		$output .= '</div>';
		$output	.= '</div>';
		$output .= '</div>';
		return $output;
	}

	public function css() {
		$addon_id = '#sppb-addon-' . $this->addon->id;

		$gallery_height = (isset($this->addon->settings->gallery_height) && $this->addon->settings->gallery_height) ? $this->addon->settings->gallery_height : '';
		$gallery_height_sm = (isset($this->addon->settings->gallery_height_sm) && $this->addon->settings->gallery_height_sm) ? $this->addon->settings->gallery_height_sm : '';
		$gallery_height_xs = (isset($this->addon->settings->gallery_height_xs) && $this->addon->settings->gallery_height_xs) ? $this->addon->settings->gallery_height_xs : '';

		$css = '';
		if($gallery_height){
			$css .= $addon_id .' .sppb-gallery-parallax:after {';
			$css .= 'height:'.$gallery_height.'px;';
			$css .= '}';

			$css .= '@media (min-width: 768px) and (max-width: 991px) {';

			$css .= $addon_id .' .sppb-gallery-parallax:after {';
			$css .= 'height:'.$gallery_height_sm.'px;';
			$css .= '}';

			$css .= '}';

			$css .= '@media (max-width: 767px) {';

			$css .= $addon_id .' .sppb-gallery-parallax:after {';
			$css .= 'height:'.$gallery_height_xs.'px;';
			$css .= '}';

			$css .= '}';
		}
		return $css;
	}
}

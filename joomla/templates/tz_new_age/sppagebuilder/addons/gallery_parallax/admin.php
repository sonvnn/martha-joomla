<?php
/**
* @package SP Page Builder
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2018 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('Restricted access');

SpAddonsConfig::addonConfig(
	array(
		'type'=>'repeatable',
		'addon_name'=>'sp_gallery_parallax',
		'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GALLERY_PARALLAX'),
		'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GALLERY_PARALLAX_DESC'),
		'category'=>'Media',
		'attr'=>array(
			'general' => array(

				'admin_label'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ADMIN_LABEL'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ADMIN_LABEL_DESC'),
					'std'=> ''
				),

				'title'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_DESC'),
					'std'=>  ''
				),

				'heading_selector'=>array(
					'type'=>'select',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_DESC'),
					'values'=>array(
						'h1'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_H1'),
						'h2'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_H2'),
						'h3'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_H3'),
						'h4'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_H4'),
						'h5'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_H5'),
						'h6'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_H6'),
					),
					'std'=>'h3',
					'depends'=>array(array('title', '!=', '')),
				),

				'title_font_family'=>array(
					'type'=>'fonts',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_FONT_FAMILY'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_FONT_FAMILY_DESC'),
					'depends'=>array(array('title', '!=', '')),
					'selector'=> array(
						'type'=>'font',
						'font'=>'{{ VALUE }}',
						'css'=>'.sppb-addon-title { font-family: "{{ VALUE }}"; }'
					)
				),

				'title_fontsize'=>array(
					'type'=>'slider',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_FONT_SIZE'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_FONT_SIZE_DESC'),
					'std'=>'',
					'depends'=>array(array('title', '!=', '')),
					'responsive' => true,
					'max'=> 400,
				),

				'title_lineheight'=>array(
					'type'=>'slider',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_LINE_HEIGHT'),
					'std'=>'',
					'depends'=>array(array('title', '!=', '')),
					'responsive' => true,
					'max'=> 400,
				),

				'title_font_style'=>array(
					'type'=>'fontstyle',
					'title'=> JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_FONT_STYLE'),
					'depends'=>array(array('title', '!=', '')),
				),

				'title_letterspace'=>array(
					'type'=>'select',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_LETTER_SPACING'),
					'values'=>array(
						'0'=> 'Default',
						'1px'=> '1px',
						'2px'=> '2px',
						'3px'=> '3px',
						'4px'=> '4px',
						'5px'=> '5px',
						'6px'=>	'6px',
						'7px'=>	'7px',
						'8px'=>	'8px',
						'9px'=>	'9px',
						'10px'=> '10px'
					),
					'std'=>'0',
					'depends'=>array(array('title', '!=', '')),
				),

				'title_text_color'=>array(
					'type'=>'color',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_TEXT_COLOR'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_TEXT_COLOR_DESC'),
					'depends'=>array(array('title', '!=', '')),
				),

				'title_margin_top'=>array(
					'type'=>'slider',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_MARGIN_TOP'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_MARGIN_TOP_DESC'),
					'placeholder'=>'10',
					'depends'=>array(array('title', '!=', '')),
					'responsive' => true,
					'max'=> 400,
				),

				'title_margin_bottom'=>array(
					'type'=>'slider',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_MARGIN_BOTTOM'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_MARGIN_BOTTOM_DESC'),
					'placeholder'=>'10',
					'depends'=>array(array('title', '!=', '')),
					'responsive' => true,
					'max'=> 400,
				),

				'gallery_height'=>array(
					'type'=>'slider',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GALLERY_HEIGHT'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GALLERY_HEIGHT_DESC'),
					'placeholder'=>'530',
					'responsive' => true,
					'max'=> 1000,
				),

				'class'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_CLASS'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_CLASS_DESC'),
					'std'=>''
				),
				// Repeatable Items
				'sp_gallery_item'=>array(
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GALLERY_ITEMS'),
					'attr'=>array(
						'title'=>array(
							'type'=>'text',
							'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GALLERY_ITEM_TITLE'),
							'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GALLERY_ITEM_TITLE_DESC'),
							'std'=>'Gallery Item 1'
						),
						'image'=>array(
							'type'=>'media',
							'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GALLERY_IMAGE'),
							'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GALLERY_IMAGE_DESC'),
							'std'=>'https://sppagebuilder.com/addons/gallery/gallery1.jpg'
						),

						'max_width'=>array(
							'type'=>'text',
							'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GALLERY_ITEM_MAX_WIDTH'),
							'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GALLERY_ITEM_MAX_WIDTH_DESC'),
							'std'=>'100%'
						),

						'left'=>array(
							'type'=>'text',
							'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GALLERY_ITEM_LEFT'),
							'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GALLERY_ITEM_LEFT_DESC'),
							'std'=>''
						),

						'top'=>array(
							'type'=>'text',
							'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GALLERY_ITEM_TOP'),
							'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GALLERY_ITEM_TOP_DESC'),
							'std'=>''
						),

						'right'=>array(
							'type'=>'text',
							'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GALLERY_ITEM_RIGHT'),
							'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GALLERY_ITEM_RIGHT_DESC'),
							'std'=>''
						),

						'bottom'=>array(
							'type'=>'text',
							'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GALLERY_ITEM_BOTTOM'),
							'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GALLERY_ITEM_BOTTOM_DESC'),
							'std'=>''
						),

						'image_shadow'=>array(
							'type'=>'boxshadow',
							'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_IMAGE_SHADOW'),
							'std'=>'',
							'config' => array(
								'spread' => false
							)
						),

						'rellax_speed' => array(
							'type'=>'number',
							'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_RELLAX_SPEED'),
							'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_RELLAX_SPEED_DESC'),
							'std'=>'10',
							'depends'=>array(array('use_rellax', '!=', '0')),
						),

						'rellax_min' => array(
							'type'=>'number',
							'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_RELLAX_MIN'),
							'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_RELLAX_MIN_DESC'),
							'std'=>'',
							'depends'=>array(array('use_rellax', '!=', '0')),
						),

						'rellax_max' => array(
							'type'=>'number',
							'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_RELLAX_MAX'),
							'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_RELLAX_MAX_DESC'),
							'std'=>'',
							'depends'=>array(array('use_rellax', '!=', '0')),
						),

						'rellax_zindex' => array(
							'type'=>'text',
							'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_RELLAX_ZINDEX'),
							'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_RELLAX_ZINDEX_DESC'),
							'std'=>'',
							'depends'=>array(array('use_rellax', '!=', '0')),
						),

					),
				),
			)
		),
	)
);

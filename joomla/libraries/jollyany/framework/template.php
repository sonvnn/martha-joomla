<?php
/**
 * @package   Jollyany Framework
 * @author    TemPlaza https://www.templaza.com
 * @copyright Copyright (C) 2009 - 2019 TemPlaza.
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
 */
defined('_JEXEC') or die;
jimport('astroid.framework.template');
jimport('astroid.framework.helper');
jimport('astroid.framework.element');

class JollyanyFrameworkTemplate extends AstroidFrameworkTemplate{
	public function __construct($template) {
		parent::__construct($template);
	}

	public function loadLayout($partial = '', $display = true, $params = null) {
		$this->setLog("Rending template partial : " . $partial);
		if (file_exists(JPATH_SITE . '/templates/' . $this->template . '/html/frontend/' . str_replace('.', '/', $partial) . '.php')) {
			$layout = new JLayoutFile($partial, JPATH_SITE . '/templates/' . $this->template . '/html/frontend');
		} elseif (file_exists(JPATH_SITE . '/templates/' . $this->template . '/frontend/' . str_replace('.', '/', $partial) . '.php')) {
			$layout = new JLayoutFile($partial, JPATH_SITE . '/templates/' . $this->template . '/frontend');
		} else {
			$layout = new JLayoutFile($partial, JPATH_SITE . '/libraries/jollyany/framework/frontend');
		}
		$data = [];
		$data['template'] = $this;
		if (!empty($params)) {
			$data['params'] = $params;
		}
		if ($display) {
			echo $layout->render($data);
		} else {
			return $layout->render($data);
		}
		$this->setLog("Template partial rendered!: " . $partial, 'success');
	}

	/**
	 * Get Preset data
	 * @return array
	 */
	public function getPresets() {
		$presets_path = JPATH_SITE . "/templates/{$this->template}/astroid/presets/";
		if (!file_exists($presets_path)) {
			return [];
		}
		$files = array_filter(glob($presets_path . '/' . '*.json'), 'is_file');
		$presets = [];
		foreach ($files as $file) {
			$json = file_get_contents($file);
			$data = \json_decode($json, true);
			$preset = ['title' => pathinfo($file)['filename'], 'colors' => [], 'preset' => [], 'thumbnail' => '', 'name' => pathinfo($file)['filename']];
			if (isset($data['title']) && !empty($data['title'])) {
				$preset['title'] = \JText::_($data['title']);
			}
			if (isset($data['thumbnail']) && !empty($data['thumbnail'])) {
				$preset['thumbnail'] = \JURI::root() . 'templates/' . $this->template . '/' . $data['thumbnail'];
			}
			if (isset($data['colors'])) {
				$colors = [];
				$properties = [];
				foreach ($data['colors'] as $prop => $color) {
					if (is_array($color)) {
						foreach ($color as $subprop => $color2) {
							if (!empty($color2)) {
								$properties[$prop][$subprop] = $color2;
								$colors[] = $color;
							}
						}
					} else {
						if (!empty($color)) {
							$properties[$prop] = $color;
							$colors[] = $color;
						}
					}
				}
				$colors = array_keys(array_count_values($colors));
				$preset['colors'] = array_unique($colors);
				$preset['preset'] = $properties;
			}
			$presets[] = $preset;
		}
		return $presets;
	}

	/**
	 * @param string $components
	 */
	public function loadFrameworkJS($components = '') {
		$this->setLog("Loading Javascripts");
		$components = explode(',', $components);
		$template_directory = JPATH_LIBRARIES . "/jollyany/framework/assets/js/";
		$document = JFactory::getDocument();
		foreach ($components as $component) {
			if (file_exists($template_directory . $component)) {
				JHtml::_('script', JURI::root() . 'libraries/jollyany/framework/assets/js/' . $component, array('version' => $document->getMediaVersion(), 'relative' => true));
			}
		}
		$this->setLog("Javascripts Loaded!", "success");
	}
}
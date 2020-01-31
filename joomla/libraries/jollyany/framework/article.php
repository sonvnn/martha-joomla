<?php
/**
 * @package   Jollyany Framework
 * @author    TemPlaza https://www.templaza.com
 * @copyright Copyright (C) 2009 - 2019 TemPlaza.
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
 */
defined('_JEXEC') or die;
jimport('astroid.framework.constants');
jimport('jollyany.framework.jollyany');

class JollyanyFrameworkArticle extends AstroidFrameworkArticle {
	protected $params;

	function __construct($article, $categoryView = false) {
		parent::__construct($article, $categoryView);
		$this->template = JollyanyFramework::getTemplate();
	}
}

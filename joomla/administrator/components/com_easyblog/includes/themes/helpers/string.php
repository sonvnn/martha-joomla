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

class EasyBlogThemesHelperString
{
	public static function escape($string)
	{
		return EB::string()->escape($string);
	}

	/**
	 * Formats a given date string with a given date format
	 *
	 * @since   1.0
	 * @access  public
	 */
	public static function date($timestamp, $format = '' , $withOffset = true)
	{
		// Get the current date object based on the timestamp provided.
		$date = EB::date($timestamp, $withOffset);

		// If format is not provided, we should use DATE_FORMAT_LC2 by default.
		$format = empty($format) ? 'DATE_FORMAT_LC2' : $format;

		// Get the proper format.
		$format = JText::_($format);
		
		$dateString = $date->format($format);

		return $dateString;
	}

	/**
	 * Pluralize the string if necessary.
	 *
	 * @since   1.2
	 * @access  public
	 */
	public static function pluralize($languageKey, $count)
	{
		return Foundry::string()->computeNoun( $languageKey , $count );
	}

	/**
	 * Truncates a string at a centrain length and add a more link
	 *
	 * @deprecated	5.0
	 * @access  public
	 */
	public static function truncater($text, $max = 250)
	{
		return self::truncate($text, $max, '');
	}

	/**
	 * Alternative to @truncater to truncate contents with HTML codes
	 *
	 * @since	5.3
	 * @access	public
	 */
	public static function truncate($text, $max = 250, $ending = '', $exact = false, $showMore = true, $overrideReadmore = false, $stripTags = false)
	{
		if (!$ending) {
			$ending = JText::_('COM_EASYBLOG_ELLIPSES');
		}

		// If the plain text is shorter than the maximum length, return the whole text
		if ((JString::strlen(preg_replace('/<.*?>/', '', $text)) <= $max) || !$max) {
			return $text;
		}

		// Truncate the string natively without retaining the original format.
		if ($stripTags) {
			$truncate = trim(strip_tags($text));
			$truncate = EBString::substr($truncate, 0, $max) . $ending;
		} else {
			$stringLib = EB::string();
			$truncate = $stringLib->truncateWithHtml($text, $max, $ending, $exact);
		}

		$theme = EB::themes();
		$theme->set('truncated', $truncate);
		$theme->set('original', $text);
		$theme->set('showMore', $showMore);
		$theme->set('overrideReadmore', $overrideReadmore);

		$output = $theme->output('admin/html/string.truncater');

		return $output;
	}	
}
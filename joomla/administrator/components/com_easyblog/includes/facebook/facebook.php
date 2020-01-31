<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2019 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

class EasyBlogFacebook extends EasyBlog
{
	/**
	 * Get the image to be share on facebook
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function getImage(EasyBlogPost &$blog, $rawIntroText = '')
	{
		// First, we try to search to see if there's a blog image. If there is already, just ignore the rest.
		if ($blog->hasImage()) {
			return $blog->getImage('large', true, true);
		}

		// For image posts.
		if (isset($blog->images[0])) {
			return $blog->images[0];
		}

		$fullcontent = $blog->getContent('entry', false, true, array('processAdsense' => false));
		$source = EB::string()->getImage($fullcontent);

		if ($source !== false) {
			return $source;
		}

		// If there's still no image, use author's avatar
		if ($source == false && $this->config->get('main_facebook_opengraph_imageavatar', false)) {
			$author = $blog->getAuthor();
			$source = $author->getAvatar();
			return $source;
		}

		// Default post image if the blog post doesn't contain any image
		// $app = JFactory::getApplication();
		$override = JPATH_ROOT . '/templates/' . $this->app->getTemplate() . '/html/com_easyblog/images/placeholder-facebook.png';

		if (JFile::exists($override)) {
			$source = rtrim(JURI::root(), '/') . '/templates/' . $this->app->getTemplate() . '/html/com_easyblog/images/placeholder-facebook.png';
		} else {
			$source = rtrim(JURI::root(), '/') . '/components/com_easyblog/themes/wireframe/images/placeholder-facebook.png';
		}

		return $source;
	}

	/**
	 * Add opengraph data on the page
	 *
	 * @since	5.3.4
	 * @access	public
	 */
	public function addOpenGraphTags($obj)
	{
		if (!$this->config->get('main_facebook_opengraph')) {
			return false;
		}

		if ($obj instanceof EasyBlogPost) {
			return $this->addOpenGraphTagsForPost($obj);
		}

		if ($obj instanceof EasyBlogTableProfile) {
			return $this->addOpenGraphTagsForAuthor($obj);
		}
	}

	/**
	 * Attaches the open graph tags in the header
	 *
	 * @since	5.3.4
	 * @access	public
	 */
	public function addOpenGraphTagsForAuthor($author)
	{
		// Get the absolute permalink for this blog item.
		$url = $author->getExternalPermalink();

		// Add the author avatar as the image
		$avatar = $author->getAvatar(true);

		// Add the title tag
		$title = $author->getName();
		$description = $author->getBiography();
		$description = $this->formatDescription($description);

		$this->doc->addCustomTag('<meta property="og:title" content="' . $title . '" />');
		$this->addImageOpengraphTags($avatar);
		$this->addImageWidthOpengraphTags(200);
		$this->addImageHeightOpengraphTags(200);
		$this->doc->addCustomTag('<meta property="og:description" content="' .  $description . '" />');
		$this->doc->addCustomTag('<meta property="og:type" content="article" />');
		$this->doc->addCustomTag('<meta property="og:url" content="' . $url . '" />');
		$this->addAuthorOpengraphTags($author);
		$this->addAdminOpengraphTags();
		$this->addAppOpengraphTags();

		return true;
	}

	/**
	 * Attaches the open graph tags in the header
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function addOpenGraphTagsForPost(EasyBlogPost &$post)
	{
		if (!$this->config->get('main_facebook_opengraph')) {
			return;
		}

		// Get the absolute permalink for this blog item.
		$url = $post->getExternalPermalink();

		// Get the image of the blog post.
		$image  = $this->getImage($post);

		// Get image dimension if supported
		$dimension = $post->getCoverDimension('large');

		// Convert double quotes to html entities.
		$title = $this->formatTitle($post->title);

		// Load any necessary meta data for the blog
		$post->loadMeta();

		// If there's a meta set for the blog, use the stored meta version
		$description = !empty($post->meta->description) ? $post->meta->description : $post->getIntro();
		$description = $this->formatDescription($description);

		// Add the blog image.
		$this->addImageOpengraphTags($image);
		
		if ($dimension->width) {
			$this->addImageWidthOpengraphTags($dimension->width);
		}

		if ($dimension->height) {
			$this->addImageWidthOpengraphTags($dimension->height);
		}

		// Add the title tag
		$this->doc->addCustomTag('<meta property="og:title" content="' . $title . '" />');
		$this->doc->addCustomTag('<meta property="og:description" content="' .  $description . '" />');
		$this->doc->addCustomTag('<meta property="og:type" content="article" />');
		$this->doc->addCustomTag('<meta property="og:url" content="' . $url . '" />');

		$author = $post->getAuthor();
		$this->addAuthorOpengraphTags($author);
		$this->addAdminOpengraphTags();
		$this->addAppOpengraphTags();

		return true;
	}

	/**
	 * Adds the app id for opengraph meta data
	 *
	 * @since	5.3.4
	 * @access	public
	 */
	public function addImageOpengraphTags($imageUrl)
	{
		$this->doc->addCustomTag('<meta property="og:image" content="' . $imageUrl . '"/>');
	}

	/**
	 * Adds the app id for opengraph meta data
	 *
	 * @since	5.3.4
	 * @access	public
	 */
	public function addImageWidthOpengraphTags($width)
	{
		$this->doc->addCustomTag('<meta property="og:image:width" content="' . $width . '"/>');
	}

	/**
	 * Adds the app id for opengraph meta data
	 *
	 * @since	5.3.4
	 * @access	public
	 */
	public function addImageHeightOpengraphTags($height)
	{
		$this->doc->addCustomTag('<meta property="og:image:height" content="' . $height . '"/>');
	}

	/**
	 * Adds the app id for opengraph meta data
	 *
	 * @since	5.3.4
	 * @access	public
	 */
	public function addAppOpengraphTags()
	{
		// If app id is provided, attach it on the head
		$appId = $this->config->get('main_facebook_like_appid');

		if ($appId) {
			$this->doc->addCustomTag('<meta property="fb:app_id" content="' . $appId . '"/>');
		}
	}

	/**
	 * Adds the admin id for opengraph meta data
	 *
	 * @since	5.3.4
	 * @access	public
	 */
	public function addAdminOpengraphTags()
	{
		$adminId = $this->config->get('main_facebook_like_admin');

		if ($adminId) {
			$this->doc->addCustomTag('<meta property="fb:admins" content="' . $adminId . '"/>');
		}
	}

	/**
	 * Adds the app id for opengraph meta data
	 *
	 * @since	5.3.4
	 * @access	public
	 */
	public function addAuthorOpengraphTags($author)
	{
		$authorParams = $author->getParams();

		// Determines if we should add the article:author meta data
		$facebookProfileUrl = $authorParams->get('facebook_profile_url', '');

		if ($facebookProfileUrl && $this->config->get('main_facebook_ogauthor')) {
			$this->doc->addCustomTag('<meta property="article:author" content="' . $facebookProfileUrl . '" />');
		}
	}

	/**
	 * Formats the description to be used for opengraph description
	 *
	 * @since	5.3.4
	 * @access	public
	 */
	private function formatTitle($title)
	{
		$title = htmlspecialchars($title, ENT_QUOTES);
		
		return $title;
	}

	/**
	 * Formats the description to be used for opengraph description
	 *
	 * @since	5.3.4
	 * @access	public
	 */
	private function formatDescription($description)
	{
		// Remove unwanted tags
		$description = EB::stripEmbedTags($description);

		// Remove newlines
		$description = str_ireplace("\r\n", "", $description);

		// Replace &nbsp; with spaces
		$description = EBString::str_ireplace('&nbsp;', ' ', $description);

		// Remove any html tags
		$description = strip_tags($description);

		// Ensure that newlines wouldn't affect the header
		$description = trim($description);

		// Replace htmlentities with the counterpert
		// Perhaps we need to explicitly replace &nbsp; with a space?
		$description = html_entity_decode($description);

		// Remove any quotes (") from the content
		$description = str_ireplace('"', '', $description);

		// If there's a maximum length specified, we should respect it.
		$max = $this->config->get('integrations_facebook_blogs_length');

		if ($max && (EBString::strlen($description) > $max)) {
			$description = EBString::substr($description, 0, $max) . JText::_('COM_EASYBLOG_ELLIPSES');
		}

		return $description;		
	}
}
<?php
/**
 * @package   Astroid Framework
 * @author    JoomDev https://www.joomdev.com
 * @copyright Copyright (C) 2009 - 2019 JoomDev.
 * @license   GNU/GPLv2 and later
 */
// no direct access
defined('_JEXEC') or die;

class pkg_jollyanyInstallerScript {

   /**
    * 
    * Function to run before installing the component	 
    */
   public function preflight($type, $parent) {
      
   }

   /**
    *
    * Function to run when installing the component
    * @return void
    */
   public function install($parent) {
      $this->getJoomlaVersion();
      $this->removeUnnecessary();
      $this->displayAstroidWelcome($parent);
   }

   /**
    *
    * Function to run when un-installing the component
    * @return void
    */
   public function uninstall($parent) {
      
   }

   /**
    * 
    * Function to run when updating the component
    * @return void
    */
   function update($parent) {
	   $this->removeUnnecessary();
      $this->displayAstroidWelcome($parent);
   }

   /**
    * 
    * Function to update database schema
    */
   public function updateDatabaseSchema($update) {
      
   }

   public function getJoomlaVersion() {
      $version = new \JVersion;
      $version = $version->getShortVersion();
      $version = substr($version, 0, 1);
      define('ASTROID_JOOMLA_VERSION', $version);
   }

   public function removeUnnecessary() {
	   $removefile  =   array(
		   'frontend/backtotop.php',
		   'frontend/colors.php',
		   'frontend/comingsoon.php',
		   'frontend/contactinfo.php',
		   'frontend/custom.php',
		   'frontend/dropdownmenumodule.php',
		   'frontend/footer.php',
		   'frontend/header.php',
		   'frontend/hikashop.php',
		   'frontend/joomlalogin.php',
		   'frontend/language.php',
		   'frontend/menumodule.php',
		   'frontend/mobilemenu.php',
		   'frontend/offcanvas.php',
		   'frontend/preloader.php',
		   'frontend/social.php',
		   'frontend/typography.php',
		   'frontend/blog/modules/author_info.php',
		   'frontend/blog/modules/badge.php',
		   'frontend/blog/modules/comments.php',
		   'frontend/blog/modules/image.php',
		   'frontend/blog/modules/posttype.php',
		   'frontend/blog/modules/rating.php',
		   'frontend/blog/modules/readtime.php',
		   'frontend/blog/modules/related.php',
		   'frontend/blog/modules/social.php',
		   'frontend/blog/audio.php',
		   'frontend/blog/gallery.php',
		   'frontend/blog/quote.php',
		   'frontend/blog/review.php',
		   'frontend/blog/video.php',
		   'frontend/header/horizontal.php',
		   'frontend/header/stacked.php',
		   'frontend/header/sticky.php',
		   'frontend/header/sidebar.php',
		   'frontend/header/sidebar.php',
		   'astroid/options/article.xml',
		   'astroid/options/basic.xml',
		   'astroid/options/custom.xml',
		   'astroid/options/footer.xml',
		   'astroid/options/header.xml',
		   'astroid/options/layout.xml',
		   'astroid/options/social.xml',
		   'astroid/options/theming.xml',
		   'astroid/options/typography.xml',
		   'astroid/options/colors.xml',
		   'astroid/options/dashboard.xml',
		   'astroid/options/extensions.xml',
	   );
	   jimport('joomla.filesystem.file');
	   foreach ($removefile as $file) {
		   if (JFile::exists(JPATH_ROOT.'/templates/tz_new_age/'.$file)) {
			   JFile::delete(JPATH_ROOT.'/templates/tz_new_age/'.$file);
		   }
	   }
   }

   /**
    * 
    * Function to display welcome page after installing
    */
   public function displayAstroidWelcome($parent) {
      ?>
      <style>
         .astroid-install {
            margin: 20px 0;
            padding: 40px 0;
            text-align: center;
            border-radius: 0px;
            position: relative;
            border: 1px solid #f8f8f8;
            background:#fff url(<?php echo JURI::root(); ?>media/astroid/assets/images/moon-surface.png); 
            background-repeat: no-repeat; 
            background-position: bottom;
         }
         .astroid-install p {
            margin: 0;
            font-size: 16px;
            line-height: 1.5;
         }
         .astroid-install .install-message {
            width: 90%;
            max-width: 800px;
            margin: 50px auto;
         }
         .astroid-install .install-message h3 {
            display: block;
            font-size: 20px;
            line-height: 27px;
            margin: 25px 0;
         }
         .astroid-install .install-message h3 span {
            display: block;
            color: #7f7f7f;
            font-size: 13px;
            font-weight: 600;
            line-height: normal;
         }
         .astroid-install-actions .btn {
            color: #fff;
            overflow: hidden;
            font-size: 18px;
            box-shadow: none;
            font-weight: 400;
            padding: 15px 50px;
            border-radius: 50px;
            background: transparent linear-gradient(to right, #8E2DE2, #4A00E0) repeat scroll 0 0 !important;
            line-height: normal;
            border: none;
            font-weight: bold;
            position: relative;
            box-shadow:0px 0px 30px #b0b7e2;
            transition: linear 0.1s;
         }
         .astroid-install-actions .btn:after{
            top: 50%;
            width: 20px;
            opacity: 0;
            content:"";
            right: 80px;
            height: 17px;
            display: block;
            position: absolute;
            transform: translateY(-50%);
            -moz-transform: translateY(-50%);
            -webkit-transform: translateY(-50%);
            background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAARCAYAAADdRIy+AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6OERDRjlBMjY0OTIzMTFFODkyQTI4MzYzN0ZGQ0Y1NTMiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6OERDRjlBMjc0OTIzMTFFODkyQTI4MzYzN0ZGQ0Y1NTMiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo4RENGOUEyNDQ5MjMxMUU4OTJBMjgzNjM3RkZDRjU1MyIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo4RENGOUEyNTQ5MjMxMUU4OTJBMjgzNjM3RkZDRjU1MyIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PvXGU3IAAADpSURBVHjarNShCsJAHMfxO1iyyEw+gsFoEIMKA5uoxSfwKQSjYWASq4JVEKPggwgmi2ASFcMUdefvj3/QsMEdd3/4lO32Zey2SaWU0JwsLOEGndRVFNTkw0N9Z562ziRIAog4OnMRJDV4c3TsIki6EHM0dBEkbfWbgYsgqcKRo3065mGjm1CHM0ihP084wA7yMIRIokonPOFoKNjjO4zptTS4ltZuoQUVPhbaPsMC7PkZjmw3pQx3jk1sdzn4i01t38MiXDi2sP1SSnDl2Mr2W87BiWNryCStkwb/Qx82/D9swCtp0UeAAQDi4gvA12LkbAAAAABJRU5ErkJggg==') no-repeat;
            -webkit-transition: all 0.4s;
            -moz-transition: all 0.4s;
            transition: all 0.4s;
         }
         .astroid-install-actions .btn:hover{
            transition: linear 0.1s;
            box-shadow:0px 0px 30px #4b57d9;
         }
         .astroid-install-actions .btn:hover:after{
            opacity: 1;
            right: 20px;
            margin-left: 0;
         }
         .astroid-support-link{
            color: #8E2DE2;
            padding: 30px 0 10px;
         }
         .astroid-support-link a{
            color: #8E2DE2;
            text-decoration: none;
         }
         .astroid-support-link a:hover {
            text-decoration: underline;
         }
         .astroid-poweredby a{
            bottom: 0;
            display: block;
            font-size: 0;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
         }
         .astroid-poweredby span{
            font-size: 0;
         }
      </style>
       <div class="astroid-install">
           <img src="<?php echo JURI::root() . 'templates/tz_new_age/images/default/new-age-logo.png'; ?>" alt="astroid-logo" />
           <div class="install-message">
               <h3>New Age | Creative Agency Joomla Template
                   <span>v <?php echo $parent->get('manifest')->version; ?></span>
               </h3>
               <p>New Age is a creative and modern Joomla template for company, corporation, start-up, portfolio, blog, mini-shop and social network. The template supports SP Page Builder TZ Portfolio, EasyBlog, EasySocial and Hikashop to help to build a fully functional website. New Age also supports video, gallery page and all default Joomla pages with customized style.</p>
           </div>
           <div class="astroid-install-actions">
               <a href="index.php?option=com_templates" class="btn btn-default">Get started</a>
           </div>
           <div class="astroid-support-link">
               <a href="https://jollyany.co/support/documentation" target="_blank">Documentation</a> <span>|</span> <a href="https://1.envato.market/zODvW" target="_blank">Changelog</a> <span>|</span> <a href="https://www.templaza.com/Forums.html" target="_blank">Forum</a>
           </div>
       </div>
      <?php
   }

   /**
    * 
    * Function to run after installing the component	 
    */
   public function postflight($type, $parent) {
      
   }

}
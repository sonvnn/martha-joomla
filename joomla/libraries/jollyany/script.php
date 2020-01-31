<?php
/**
 * @package   Jollyany Framework
 * @author    TemPlaza https://www.templaza.com
 * @copyright Copyright (C) 2011 - 2019 TemPlaza.
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
 */
// no direct access
defined('_JEXEC') or die;
jimport('joomla.filesystem.file');

class jollyanyInstallerScript {

   /**
    * 
    * Function to run before installing the component	 
    */
   public function preflight($type, $parent) {
      $plugin_dir = JPATH_LIBRARIES . '/' . 'jollyany' . '/' . 'plugins' . '/';
      $plugins = array_filter(glob($plugin_dir . '*'), 'is_dir');
      foreach ($plugins as $plugin) {
         if ($type == "uninstall") {
            $this->uninstallPlugin($plugin, $plugin_dir);
         }
      }
   }

   /**
    * 
    * Function to run after installing the component	 
    */
   public function postflight($type, $parent) {
      $plugin_dir = JPATH_LIBRARIES . '/' . 'jollyany' . '/' . 'plugins' . '/';
      $plugins = array_filter(glob($plugin_dir . '*'), 'is_dir');
      foreach ($plugins as $plugin) {
         if ($type == "install" || $type == "update") {
            $this->installPlugin($plugin, $plugin_dir);
         }
      }
   }

   public function installPlugin($plugin, $plugin_dir) {
      $db = JFactory::getDbo();
      $plugin_name = str_replace($plugin_dir, '', $plugin);

      $installer = new JInstaller;
      $installer->install($plugin);

      $query = $db->getQuery(true);
      $query->update('#__extensions');
      $query->set($db->quoteName('enabled') . ' = 1');
      $query->where($db->quoteName('element') . ' = ' . $db->quote($plugin_name));
      $query->where($db->quoteName('type') . ' = ' . $db->quote('plugin'));
      $db->setQuery($query);
      $db->execute();
      return true;
   }

   public function uninstallPlugin($plugin, $plugin_dir) {
      $db = JFactory::getDbo();
      $plugin_name = str_replace($plugin_dir, '', $plugin);
      $query = $db->getQuery(true);
      $query->update('#__extensions');
      $query->set($db->quoteName('enabled') . ' = 0');
      $query->where($db->quoteName('element') . ' = ' . $db->quote($plugin_name));
      $query->where($db->quoteName('type') . ' = ' . $db->quote('plugin'));
      $db->setQuery($query);
      $db->execute();
      return true;
   }

}
